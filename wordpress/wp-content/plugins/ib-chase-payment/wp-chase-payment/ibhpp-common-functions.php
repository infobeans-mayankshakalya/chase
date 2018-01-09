<?php
/**
 * 
 * @global type $wpdb
 * @param type $page_title
 * @param type $page_code
 * @param type $shortcodes
 * @param type $template
 */
function ibhpp_create_plugin_pages($page_title, $page_code, $shortcodes = NULL, $template = 'default')
{
    global $wpdb;

    $the_page_title = $page_title;
    $the_page_name = $page_title;

    // the menu entry...
    delete_option($page_code . "_title");
    add_option($page_code . "_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option($page_code . "_name");
    add_option($page_code . "_name", $the_page_name, '', 'yes');
    // the id...
    delete_option($page_code . "_id");
    add_option($page_code . "_id", '0', '', 'yes');

    $the_page = get_page_by_title($the_page_title);

    if (!$the_page) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $atr = '';
        if (!empty($shortcodes)) {
            $rt_shortcode = '';
            if (is_array($shortcodes)) {
                foreach ($shortcodes as $shortcode) {
                    $rt_shortcode .= '[' . $shortcode['name'];
                    if (isset($shortcode['attr'])) {
                        foreach ($shortcode['attr'] as $k => $v) {
                            $rt_shortcode .= ' ' . $k . '=' . $v;
                        }
                    }
                    $rt_shortcode .= ']';
                }
            } else {
                $rt_shortcode = '[' . $shortcodes . ']';
            }
        } else {
            $rt_shortcode = '[' . $page_code . ']';
        }
        $_p['post_content'] = $rt_shortcode;
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        // the default 'Uncatrgorised'
        $_p['post_category'] = array(1);
        // Insert the post into the database
        $the_page_id = wp_insert_post($_p);
    } else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post($the_page);
    }

    if (isset($the_page_id) && !empty($the_page_id)) {
        update_post_meta($the_page_id, '_wp_page_template', $template);
    }

    delete_option($page_code . "_id");
    add_option($page_code . "_id", $the_page_id);
}

/**
 * 
 * @global type $wpdb
 * @param type $page_code
 * 
 */
function ibhpp_remove_plugin_pages($page_code)
{
    global $wpdb;
    $the_page_title = get_option($page_code . "_title");
    $the_page_name = get_option($page_code . "_name");
    //  the id of our page...
    /* get the page id */
    $the_page_id = get_option($page_code . '_id');
    if ($the_page_id) {
        // this will trash, not delete
        wp_delete_post($the_page_id);
    }
    delete_option($page_code . "_title");
    delete_option($page_code . "_name");
    delete_option($page_code . "_id");
}