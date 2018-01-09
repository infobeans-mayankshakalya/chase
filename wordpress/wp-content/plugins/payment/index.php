<?php
/*
  Plugin Name: Payment
  Description: Chase payment Plugin for wordpress currently supports HPP payment, Faciliate user with form to capture card details and process the payment.
  Author: Rohit Shrivastava
  Version: 1.1
  Author URI: http://www.infobeans.com
 */
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly.
add_shortcode('get_sample_payment_form', 'get_sample_payment_form');
function get_sample_payment_form()
    {
		if (!empty($_POST)) {
			wp_safe_redirect('http://www.google.com');
			exit;
			$options = array(
				'sessionId' => time() . rand(),
				'orderId' => $_POST['orderId'],
				'totalAmount' => $_POST['totalAmount']
			);

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
					$config = json_decode(get_option('ibhpp_chase_payment_api_settings'), true);
					wp_redirect($config['hpp_url'] . "/?uID=" . $result['data']['uID']);
					exit;
				} 
			} 
		}else{
			include('templates/sample-payment-test-page.php');
		}
    }
