=== WP Chase Payment ===
Contributors: Rohit Shrivastava
Tags: chase payment, chase hpp plugin
Requires at least: 4.7.2
Tested up to: 4.8.3

Form to capture card details and process the payment.

== Description ==

This plugin currently supports HPP payment and facilitate user to capture card information and process payment for CHASE payment gateway. 

Following are the hooks defined for this plugin:
1) Hook Name: ibhpp_update_chase_payment_transaction
   Description: User need to write his payment response and update logic 
                as per chase guidelines, under ibhpp_update_chase_payment_transaction hook.
                Calling of this hook is already handled by this plugin.


Following are the methods defined for this plugin:

1) Method Name: ibhpp_generate_uID_for_chase()
   Description: This method takes the required parameter and read chase_settings set by Admin Panel.
                And call Chase payment gateway url to get uID in response.
   Required Parameter: 1
   Parameter type: array(
            'sessionId' => 'k8oyh21wowb9dr1cv93bboqq391w',
            'orderId' => '1234',
            'totalAmount' => '10.50'
        );
   Returns: uID
   Return type: string

2) Method Name: ibhpp_send_details_to_hpp()
   Description: This method takes the uID and redirect it to hpp_url set by Admin Panel.
   Required Parameter: 1
   Parameter type: string
   Returns: redirect to hpp url using uID

3) Method Name: ibhpp_receive_payment_transaction()
   Description: User need to call ibhpp_receive_payment_transaction hook after ibhpp_send_details_to_hpp() method,  
                ibhpp_receive_payment_transaction() will internally call ibhpp_update_chase_payment_transaction 
                to update the record in db.
   Required Parameter: none
   Returns: None
   

4) ibhpp_do_test_chase_payment()
   Description: This is sample method for demo purpose, in order to call this method
                Hit http://<your_domain.com>/ibhpp-chase-payment-test-page/
                It will send sample data
                and read admin defined chase_settings to generate payment form.
                
 
3) Method Name: ibhpp_get_failed_chase_payment_status()
   Description: User need to call ibhpp_get_failed_chase_payment_status action to update payments status in db, for those
                payments whose status(Online and Pending) not updated(Success/Fail) since 15 mins due to 
                any break in network connectivity. 
                
   Required Parameter: none
   Returns: Display output


== Installation ==

1. Upload the `wp-chase-payment-hpp-plugin` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Settings menu to access 'Chase API Settings' page, edit the chase configuration as per your requirement and click on save to store it in db.
4. We have provided hooks to directly access the chase payment page, get the transaction status, update the transaction in db.
5. Update the 'Return Url' & 'Content Template Url' from pages menus.

== Frequently Asked Questions == 
None

== Changelog ==

1.0 - Initial release
1.1 - Tested on latest wordpress version 4.8.3 and updated the readme

== Upgrade Notice == 
None

== Screenshots ==

1. Here's a screenshot of it in action
