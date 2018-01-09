<?php
if (!empty($_POST) && check_admin_referer('name_of_my_action', 'name_of_nonce_field')) {
    // process form data
    $_POST['chase_config']['allowed_type'] = implode('|', $_POST['chase_config']['allowed_type']);
    update_option('ibhpp_chase_payment_api_settings', json_encode($_POST['chase_config']));
    show_message('Chase API Settings updated successfully.');
}
$config = json_decode(get_option('ibhpp_chase_payment_api_settings'), true);
?>
<div class="col-md-12 padding-left-0 padding-right-0">
    <div class="col-md-8 padding-left-0 padding-right-0">
        <h2 class="entry-title post-title"><span class="heading-line-height">Chase API Settings</span><div class="clearfix"></div></h2>
    </div>
    <form method="post">
        <?php wp_nonce_field('name_of_my_action', 'name_of_nonce_field'); ?>
        <table class="form-table">
            <tr>
                <th colspan="2" style="text-decoration:underline">
                    <h4> Chase Configuration</h4>
                </th>
            </tr>
            <tr valign="top">
                <th scope="row">Return Url<span class="description">(required)</span></th>
                <td>
                    <input type="url" name="chase_config[return_url]" value="<?php _e($config['return_url']) ?>" required=""/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Content Template Url<span class="description">(required)</span></th>
                <td>
                    <input type="url" name="chase_config[content_template_url]" value="<?php _e($config['content_template_url']) ?>" required="" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Cancel Url</th>
                <td>
                    <input type="url" name="chase_config[cancel_url]" value="<?php _e($config['cancel_url']) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Callback Url</th>
                <td>
                    <input type="url" name="chase_config[callback_url]" value="<?php _e($config['callback_url']) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Css Url</th>
                <td>
                    <input type="url" name="chase_config[css_url]" value="<?php _e($config['css_url']) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Hosted Secure ID<span class="description">(required)</span></th>
                <td>
                    <input type="text" name="chase_config[hostedSecureID]" value="<?php _e($config['hostedSecureID']) ?>" required=""/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Hosted Secure API Token<span class="description">(required)</span></th>
                <td>
                    <input type="text" name="chase_config[hostedSecureAPIToken]" value="<?php _e($config['hostedSecureAPIToken']) ?>" required/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Init URL<span class="description">(required)</span></th>
                <td>
                    <input type="url" name="chase_config[init_url]" value="<?php echo (isset($config['init_url']) and ! empty($config['init_url'])) ? $config['init_url'] : 'https://www.chasepaymentechhostedpay-var.com/direct/services/request/init/'; ?>" required/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">HPP URL<span class="description">(required)</span></th>
                <td>
                    <input type="url" name="chase_config[hpp_url]" value="<?php echo (isset($config['hpp_url']) and ! empty($config['hpp_url'])) ? $config['hpp_url'] : 'https://www.chasepaymentechhostedpay-var.com/securepayments/a1/cc_collection.php'; ?>" required/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Service Query URL<span class="description">(required)</span></th>
                <td>
                    <input type="url" name="chase_config[service_query_url]" value="<?php echo (isset($config['service_query_url']) and ! empty($config['service_query_url'])) ? $config['service_query_url'] : 'https://www.chasepaymentechhostedpay-var.com/direct/services/request/query/'; ?>" required/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Max user retries</th>
                <td>
                    <input type="number" name="chase_config[max_retries]" value="<?php echo!empty($config['max_retries']) ? $config['max_retries'] : 2; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Allowed Card Types</th>
                <?php $allowed_card = explode('|', $config['allowed_type']); ?>
                <td>
                    <label><input type="checkbox" name="chase_config[allowed_type][]" value="Visa" <?php echo in_array('Visa', $allowed_card) ? "checked" : ''; ?>  required="" /> Visa</label><br/>
                    <label><input type="checkbox" name="chase_config[allowed_type][]" value="MasterCard" <?php echo in_array('MasterCard', $allowed_card) ? "checked" : ''; ?>  /> MasterCard</label><br/>
                    <label><input type="checkbox" name="chase_config[allowed_type][]" value="AmericanExpress" <?php echo in_array('AmericanExpress', $allowed_card) ? "checked" : ''; ?> /> AmericanExpress</label><br/>
                    <label><input type="checkbox" name="chase_config[allowed_type][]" value="Discover" <?php echo in_array('Discover', $allowed_card) ? "checked" : ''; ?> /> Discover</label><br/>
                    <label><input type="checkbox" name="chase_config[allowed_type][]" value="JCB" <?php echo in_array('JCB', $allowed_card) ? "checked" : ''; ?> /> JCB</label><br/>
                    <label><input type="checkbox" name="chase_config[allowed_type][]" value="DinersClub" <?php echo in_array('DinersClub', $allowed_card) ? "checked" : ''; ?> /> DinersClub</label><br/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Partial Payment Enable</th>
                <?php $chase_enable_conf = (isset($config['partial_enable_conf'])) ? $config['partial_enable_conf'] : 'false'; ?>
                <td>
                    <label><input type="radio" name="chase_config[partial_enable_conf]" value="true" <?php echo ($chase_enable_conf == 'true') ? "checked" : ''; ?> /> Yes</label><br/>
                    <label><input type="radio" name="chase_config[partial_enable_conf]" value="false" <?php echo ($chase_enable_conf == 'false') ? "checked" : ''; ?> /> No</label><br/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Enable Partial Payment For<span class="description"> (Comma Separated Email ID)</span></th>
                <td>
                    <input type="text" name="chase_config[enable_chase_for]" value="<?php echo (isset($config['enable_chase_for']) and ! empty($config['enable_chase_for'])) ? $config['enable_chase_for'] : ''; ?>" />
                </td>
            </tr>
            <input type="hidden" name="chase_config[payment_type]" value="Credit_Card"/>
            <input type="hidden" name="chase_config[formType]" value="5"/>
            <input type="hidden" name="chase_config[trans_type]" value="auth_capture"/>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
