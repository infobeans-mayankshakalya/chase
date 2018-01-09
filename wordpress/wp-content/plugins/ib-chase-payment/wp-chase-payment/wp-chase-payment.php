<?php
/*
  Plugin Name: WP Chase Payment
  Description: Chase payment Plugin for wordpress currently supports HPP payment, Faciliate user with form to capture card details and process the payment.
  Author: Rohit Shrivastava
  Version: 1.0
  Author URI: http://www.infobeans.com
 */
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly.

/* Actions to be fired on activation of this plugin */
register_activation_hook(__FILE__, 'ibhpp_chase_payment_plugin_active');

function ibhpp_chase_payment_plugin_active()
{
    /* Create payment page */
    ibhpp_create_plugin_pages('IBHPP Chase Payment Page', 'ibhpp_chase_payment_page', '[FORM INSERT]');
    
    /* Create payment receive page */
    ibhpp_create_plugin_pages('IBHPP Chase Payment Receive', 'ibhpp_chase_payment_receive', 'ibhpp_receive_payment_transaction');
    
    /* Create payment flow test page */
    ibhpp_create_plugin_pages('IBHPP Chase Payment Test Page', 'ibhpp_get_sample_payment_form', 'ibhpp_get_sample_payment_form');
}
/* Actions to be fired on deactivation of this plugin */
register_deactivation_hook(__FILE__, 'ibhpp_chase_payment_plugin_deactive');

function ibhpp_chase_payment_plugin_deactive()
{
    /* Remove payment page */
    ibhpp_remove_plugin_pages('ibhpp_chase_payment_page');
    ibhpp_remove_plugin_pages('ibhpp_chase_payment_receive');
    ibhpp_remove_plugin_pages('ibhpp_get_sample_payment_form');
    delete_option('ibhpp_chase_payment_api_settings');
}
require_once(plugin_dir_path( __FILE__ ).'ibhpp-common-functions.php');
require_once(plugin_dir_path( __FILE__ ).'class.ibhpp-process-chase-payment.php');