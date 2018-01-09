<?php

class Ibhpp_Process_Chase_Payment
{

    /**
     *
     * @var type string
     */
    private $referer;

    /**
     *
     * @var type object
     */
    protected static $instance = null;

    /**
     *
     * @var type array
     */
    private $response_code = array(
        '000' => 'Successful request.',
        '100' => 'Merchant Identifier left blank or not valid. The transaction was not processed.',
        '110' => 'Session Identifier left blank. The transaction was not processed.',
        '200' => 'Name not present.',
        '300' => 'Amount not specified or invalid value entered.',
        '310' => 'Credit card number left blank or did not pass Mod10.',
        '315' => 'Credit card number did not pass Mod10.',
        '320' => 'Credit card type left blank or invalid.',
        '330' => 'Expiration month left blank.',
        '340' => 'Expiration year left blank.',
        '350' => 'CVV2 field submitted but does not match the card.',
        '355' => 'CVV2 required but not present.',
        '357' => 'An invalid character was entered, such as a letter in a numeric field.',
        '360' => 'Payment declined by financial institution, or some other error has occurred.',
        '370' => 'Expiration date invalid.',
        '400' => 'Transaction tracer value does not match.',
        '500' => 'Address one field required but left blank.',
        '510' => 'City field required but left blank.',
        '520' => 'State field required but left blank.',
        '530' => 'Zip/Pin Code  field required but left blank.',
        '531' => 'Invalid Zip/Pin Code format received.',
        '550' => 'Country is missing.',
        '600' => 'The Bank name was blank (ECP Only).',
        '610' => 'The Routing Number is blank (ECP Only).',
        '620' => 'The Checking Account number was blank (ECP Only).',
        '630' => 'The Routing Number is invalid (ECP Only).',
        '640' => 'The Routing Number is invalid (ECP Only).'
    );

    function __construct()
    {
        add_action('ibhpp_do_test_chase_payment', array($this, 'ibhpp_do_test_chase_payment'));

        add_filter('ibhpp_generate_uID_for_chase', array($this, 'ibhpp_generate_uID_for_chase'), 10, 1);
        add_filter('ibhpp_genrate_hpp_link', array($this, 'ibhpp_genrate_hpp_link'));

        add_action('ibhpp_send_details_to_hpp', array($this, 'ibhpp_send_details_to_hpp'), 10, 1);
        add_action('ibhpp_receive_payment_transaction', array($this, 'ibhpp_receive_payment_transaction'));

        /* add chase config in setting menu */
        add_action('admin_menu', 'ibhpp_chase_config_settings_menu');
        add_shortcode('ibhpp_receive_payment_transaction', array($this, 'ibhpp_receive_payment_transaction'));

        /* Added method to update payment data via cron */
        add_action('ibhpp_get_failed_chase_payment_status', array($this, 'ibhpp_get_failed_chase_payment_status'), 10, 1);

        add_filter('ibhpp_get_chase_payment_transaction_response', array($this, 'ibhpp_get_chase_payment_transaction_response'), 10, 1);

        add_filter('ibhpp_goto_chase_payment_form', array($this, 'ibhpp_goto_chase_payment_form'), 10, 1);

        add_shortcode('ibhpp_get_sample_payment_form', array($this, 'ibhpp_get_sample_payment_form'));
    }

    /**
     * Return an instance of this class.
     * @return type object
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     *
     * @throws WP_Error
     */
    function ibhpp_do_test_chase_payment()
    {
        $options = array(
            'sessionId' => time() . rand(),
            'orderId' => '1234',
            'totalAmount' => '10.50'
        );
        $uidData = apply_filters('ibhpp_generate_uID_for_chase', $options);
        if ($uidData['status'] == 'success' && !empty($uidData['data']['uID'])) {
            do_action('ibhpp_send_details_to_hpp', $uidData['data']['uID']);
        } else {
            return new WP_Error(403, $uidData['message']);
        }
    }

    /**
     *
     * @param type $options
     * @return \WP_Error
     */
    function ibhpp_goto_chase_payment_form($options)
    {
        $uidData = apply_filters('ibhpp_generate_uID_for_chase', $options);
        if ($uidData['status'] == 'success' && !empty($uidData['data']['uID'])) {
            do_action('ibhpp_send_details_to_hpp', $uidData['data']['uID']);
        } else {
            return new WP_Error(403, $uidData['message']);
        }
    }

    /**
     *
     * @param type $uID
     */
    function ibhpp_send_details_to_hpp($uID)
    {
        $config = json_decode(get_option('ibhpp_chase_payment_api_settings'), true);
        wp_redirect($config['hpp_url'] . "/?uID=" . $uID);
        exit;
    }

    function ibhpp_receive_payment_transaction()
    {
        /* code to process received payment data */
        if (!empty($_REQUEST['txnCancel'])) {
            $response = http_build_query($_REQUEST);
            $response.= '&transaction_status=Cancel&transaction_message=Payment Cancelled.&' . md5('payment_id') . '=' . $_REQUEST['txnCancel'];
            do_action('ibhpp_update_chase_payment_transaction', $response);
        } elseif (isset($_REQUEST['uID'])) {
            $response = apply_filters('ibhpp_get_chase_payment_transaction_response', $_REQUEST['uID']);
            $response.= (!empty($_REQUEST['code']) && $_REQUEST['code'] === '000') ? '&transaction_status=Success' : '&transaction_status=Fail';
            $response.=!empty($this->response_code[$_REQUEST['code']]) ? '&transaction_message=' . $this->response_code[$_REQUEST['code']] : '&transaction_message=' . $_REQUEST['message'];
            do_action('ibhpp_update_chase_payment_transaction', $response);
        } elseif (!empty($_REQUEST['error'])) {
            $response = http_build_query($_REQUEST);
            $response.= '&transaction_status=Fail&transaction_message=' . $_REQUEST['message'];
            do_action('ibhpp_update_chase_payment_transaction', $response);
        }
    }

    /**
     *
     * @param type $options
     * @return type
     */
    function ibhpp_generate_uID_for_chase($options)
    {
        $result = array();
        $this->referer = wp_get_referer() ? wp_get_referer() : get_home_url();

        $config = json_decode(get_option('ibhpp_chase_payment_api_settings'), true);
        if (!empty($config['return_url']) && !empty($config['content_template_url']) && !empty($config['hostedSecureID'])) {
            $cURLHandle = curl_init();
            $para = "hostedSecureID=" . $config['hostedSecureID'] . "&hostedSecureAPIToken=" . $config['hostedSecureAPIToken'] . "&return_url=" . $config['return_url'] . "&cancel_url=" . $config['return_url'] . "?txnCancel=" . $options['sessionId'] . "&content_template_url=" . $config['content_template_url'] . "&action=buildForm&sess_id=" . $options['sessionId'] . "&sess_name=" . md5('payment_id') . "&payment_type=" . $config['payment_type'] . "&formType=" . $config['formType'] . "&trans_type=" . $config['trans_type'] . "&allowed_types=" . $config['allowed_type'] . "&collectAddress=0&total_amt=" . sprintf("%.2f", $options['totalAmount']) . "&orderId=" . $options['orderId'] . "&max_user_retries=" . $config['max_retries']/* ."&processing_overlay=1" */;
            $soapUrl = $config['init_url'] . "?$para";
            curl_setopt($cURLHandle, CURLOPT_URL, $soapUrl);
            curl_setopt($cURLHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($cURLHandle, CURLOPT_HEADER, false);
            /* Execute the cURL request, get the XML response */
            $response = curl_exec($cURLHandle);
            curl_close($cURLHandle);
            if (strpos($response, 'uID') !== false) {
                $result = array(
                    'status' => 'success',
                    'message' => 'Generated UID for this action',
                    'data' => array('uID' => str_replace("uID=", "", $response))
                );
            } else {
                $result = array(
                    'status' => 'error',
                    'message' => 'Unable to process, please try after sometime.',
                    'data' => null
                );
            }
        } else {
            $result = array(
                'status' => 'error',
                'message' => 'Required fields are mandatory.',
                'data' => null
            );
        }
        return $result;
    }

    /**
     *
     * @global type $wpdb
     * @param type $uID
     * @return type
     */
    function ibhpp_get_failed_chase_payment_status($uID)
    {
        global $wpdb;
        if ($uID) {
            $response = apply_filters('ibhpp_get_chase_payment_transaction_response', $uID);
            parse_str($response, $result);
            if (!empty($result)) {
                $data = array();
                if (isset($result['code']) && $result['code'] == '000') {
                    $data = array(
                        'status' => 'Success',
                        'tx_message' => 'Successfully done.',
                        'modified_on' => date("Y-m-d H:i:s"),
                        'chase_response' => serialize(
                            array(
                                'ip_address' => !empty($result['ip_address']) ? $result['ip_address'] : '',
                                'user_agent' => !empty($result['user_agent']) ? $result['user_agent'] : '',
                            )
                        ),
                        'transaction_id' => $result['TxnGUID'],
                        'session_id' => $result['sess_id'],
                        'card_number' => $result['mPAN'],
                        'card_type' => $result['type'],
                        'auth_number' => $result['ApprovalCode']
                    );
                } elseif (!empty($result['code'])) {
                    $data = array(
                        'status' => 'Fail',
                        'tx_message' => $result['message'],
                        'modified_on' => date("Y-m-d H:i:s"),
                        'chase_response' => serialize(
                            array(
                                'ip_address' => !empty($result['ip_address']) ? $result['ip_address'] : '',
                                'user_agent' => !empty($result['user_agent']) ? $result['user_agent'] : '',
                            )
                        ),
                        'transaction_id' => !empty($result['TxnGUID']) ? $result['TxnGUID'] : NULL,
                        'card_number' => !empty($result['mPAN']) ? $result['mPAN'] : '',
                        'card_type' => !empty($result['type']) ? $result['type'] : '',
                        'auth_number' => !empty($result['ApprovalCode']) ? $result['ApprovalCode'] : '',
                    );
                } elseif (!empty($result['error'])) {
                    $data = array(
                        'status' => 'Fail',
                        'tx_message' => $result['message'],
                        'modified_on' => date("Y-m-d H:i:s"),
                        //'chase_response' => serialize($result),
                        'transaction_id' => !empty($result['TxnGUID']) ? $result['TxnGUID'] : NULL,
                        'card_number' => !empty($result['mPAN']) ? $result['mPAN'] : '',
                        'card_type' => !empty($result['type']) ? $result['type'] : '',
                        'auth_number' => !empty($result['ApprovalCode']) ? $result['ApprovalCode'] : '',
                    );
                }
                $response = array(
                    'status' => 'success',
                    'message' => 'Transaction details found!',
                    'data' => $data
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => "Transaction data for the given uID: {$uID} not found",
                    'data' => null
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "uID: {$uID} not found",
                'data' => null
            );
        }
        return $response;
    }

    /**
     *
     * @param type $uID
     * @return type
     * @description function to verify chase payment status
     */
    function ibhpp_get_chase_payment_transaction_response($uID)
    {
        $config = json_decode(get_option('ibhpp_chase_payment_api_settings'), true);
        $cURLHandle = curl_init();
        $QueryPara = "hostedSecureID=" . $config['hostedSecureID'] . "&hostedSecureAPIToken=" . $config['hostedSecureAPIToken'] . "&uID=" . $uID;
        $UidQueryURL = $config['service_query_url'] . "?" . $QueryPara;
        curl_setopt($cURLHandle, CURLOPT_URL, $UidQueryURL);
        curl_setopt($cURLHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($cURLHandle, CURLOPT_HEADER, false);
        $response = curl_exec($cURLHandle);
        curl_close($cURLHandle);
        return $response;
    }

    function ibhpp_get_sample_payment_form()
    {
        include('templates/sample-payment-test-page.php');
    }
}

add_action('plugins_loaded', array('Ibhpp_Process_Chase_Payment', 'get_instance'), 0);

/* Add custom template to the payment page */
add_filter('page_template', 'ibhpp_chase_payment_page_template');

function ibhpp_chase_payment_page_template($page_template)
{
    if (is_page('ibhpp_chase_payment_page') or is_page('ibhpp-chase-payment-page')) {
        $page_template = plugin_dir_path(__FILE__) . 'templates/chase-payment-form.php';
    }
    return $page_template;
}

/**
 * Display a chase custom config page
 */
function ibhpp_chase_payment_api_settings_menu_page()
{
    include('chase_api_settings.php');
}

function ibhpp_chase_config_settings_menu()
{
    add_submenu_page('options-general.php', "Chase API Settings", "Chase API Settings", 'manage_options', 'chase-api-settings', 'ibhpp_chase_payment_api_settings_menu_page');
}
add_action('admin_post_nopriv_ibhpp_call_sample_action', 'ibhpp_call_sample_action');

function ibhpp_call_sample_action()
{
    if (!empty($_POST)) {
        $options = array(
            'sessionId' => time() . rand(),
            'orderId' => $_POST['orderId'],
            'totalAmount' => $_POST['totalAmount']
        );
        apply_filters('ibhpp_goto_chase_payment_form', $options);
    }
}
