<?php
/*
Plugin Name: Website Trial Version
Description: Do you want to get paid every time, and in time? Install this simple plugin that puts the website offline until you receive the payment. The website will be in Trial mode. Every day a notification box appears on the screen reminding the customer how many days are left before the website goes offline. On the last day the website goes offline and a warning message appears, prompting to contact the administrator. Once you get the payment, you deactivate the plugin and the website is again online.
Version: 1.0
Author: Marco Brandino
Author URI: https://www.marco-brandino.com
 */

function wbst_tvs_set_current_date() {
    $blogtime = current_time( 'mysql' ); 
    list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
    $currentDate = $today_year.'-'.$today_month.'-'.$today_day;
    return $currentDate;
}

function wbst_tvs_set_end_date() 
{
    $endDate = date('Y-m-d',strtotime('+15 day',strtotime(wbst_tvs_set_current_date())));
    return $endDate;
}

function wbst_tvs_activate_set_default_options()
{
    add_option('wbst_tvs_box_end_date', wbst_tvs_set_end_date());
    add_option('wbst_tvs_box_position', 'right, bottom');
    add_option('wbst_tvs_box_bg_color', 'ADD8E6');
    add_option('wbst_tvs_box_color', '000000');
    add_option('wbst_tvs_box_text', 'Website Trial Version');
    add_option('wbst_tvs_box_hide', 'true');
    add_option('wbst_tvs_box_once_time', 'yes');
    add_option('wbst_tvs_box_message', '<h2>WEBSITE OFFLINE</h2>Contact the Administrator');
}

register_activation_hook( __FILE__, 'wbst_tvs_activate_set_default_options');

function wbst_tvs_register_options_group()
{
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_end_date');
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_position');
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_bg_color');
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_color');
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_text');
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_hide');
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_once_time');
    register_setting('wbst_tvs_options_group', 'wbst_tvs_box_message');
}

add_action ('admin_init', 'wbst_tvs_register_options_group');

function wbst_tvs_front_end_loading_js()
{
    if( !is_admin() ){
        wp_register_script('wbst-tvs-notify', plugins_url( 'js/notify.js', __FILE__ ));
        wp_enqueue_script('wbst-tvs-script', plugins_url( 'js/wtv.js', __FILE__ ), array( 'jquery','wbst-tvs-notify' ));
        wp_localize_script('wbst-tvs-script', 'wbst_tvs_script_vars', array(
                'wbst_tvs_fit_end_time' => __(get_option('wbst_tvs_box_end_date'), 'default'),
                'wbst_tvs_today' => __(wbst_tvs_set_current_date(), 'default'),
                'wbst_tvs_messageNotify' => __(get_option('wbst_tvs_box_message'), 'default'),
                'wbst_tvs_bgNotify' => __('#'.get_option('wbst_tvs_box_bg_color'), 'default'),
                'wbst_tvs_colorNotify' => __('#'.get_option('wbst_tvs_box_color'), 'default'),
                'wbst_tvs_textNotify1' => __(get_option('wbst_tvs_box_text'), 'default'),
                'wbst_tvs_textNotify2' => __('You have', 'default'),
                'wbst_tvs_textNotify3' => __('Days Left', 'default'),
                'wbst_tvs_positionNotify' => __(get_option('wbst_tvs_box_position'), 'default'),
                'wbst_tvs_hideNotify' => __(get_option('wbst_tvs_box_hide'), 'default'),
                'wbst_tvs_onceNotify' => __(get_option('wbst_tvs_box_once_time'), 'default')
            )
        );
    }
}

add_action('wp_enqueue_scripts','wbst_tvs_front_end_loading_js');

function wbst_tvs_update_options_form()
{
    ?>
    <div class="wrap">
        <div class="icon32" id="icon-options-general"><br /></div>
        <h2>Website Trial Version settings</h2>
        <p>&nbsp;</p>
        <form method="post" action="options.php">
            <?php settings_fields('wbst_tvs_options_group'); ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                    <th scope="row"><label for="wbst_tvs_box_end_date">End Date:</label></th>
                        <td>
                            <input type="text" id="wbst_tvs_box_end_date" value="<?php echo get_option('wbst_tvs_box_end_date'); ?>" name="wbst_tvs_box_end_date" />
                            <span class="description">yyyy-mm-dd</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="wbst_tvs_box_position">Box Position:</label>
                        </th>
                        <td>
                            <select id="wbst_tvs_box_position" name="wbst_tvs_box_position">
                                <option value="right, top" <?php selected(get_option('wbst_tvs_box_position'), "right, top"); ?>>right, top </option>
                                <option value="left, top" <?php selected(get_option('wbst_tvs_box_position'), "left, top"); ?>>left, top </option>
                                <option value="right, bottom" <?php selected(get_option('wbst_tvs_box_position'), "right, bottom"); ?>>right, bottom </option>
                                <option value="left, bottom" <?php selected(get_option('wbst_tvs_box_position'), "left, bottom"); ?>>left, bottom </option>
                            </select>
                            <span class="description"></span>
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><label for="wbst_tvs_box_bg_color">Box Background Colour:</label></th>
                        <td>
                            #<input type="text" id="wbst_tvs_box_bg_color" value="<?php echo get_option('wbst_tvs_box_bg_color'); ?>" name="wbst_tvs_box_bg_color" />
                            <span class="description"></span>
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><label for="wbst_tvs_box_color">Text Colour:</label></th>
                        <td>
                            #<input type="text" id="wbst_tvs_box_color" value="<?php echo get_option('wbst_tvs_box_color'); ?>" name="wbst_tvs_box_color" />
                            <span class="description"></span>
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><label for="wbst_tvs_box_text">Text:</label></th>
                        <td>
                            <input type="text" id="wbst_tvs_box_text" value="<?php echo get_option('wbst_tvs_box_text'); ?>" name="wbst_tvs_box_text" class="regular-text"/>
                            <span class="description"></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="wbst_tvs_box_hide">Auto Hide Box:</label>
                        </th>
                        <td>
                            <select id="wbst_tvs_box_hide" name="wbst_tvs_box_hide">
                                <option value="false" <?php selected(get_option('wbst_tvs_box_hide'), "false"); ?>>False </option>
                                <option value="true" <?php selected(get_option('wbst_tvs_box_hide'), "true"); ?>>True </option>
                            </select>
                            <span class="description"></span>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row">
                            <label for="wbst_tvs_box_once_time">One-Time Box:</label>
                        </th>
                        <td>
                            <select id="wbst_tvs_box_once_time" name="wbst_tvs_box_once_time">
                                <option value="yes" <?php selected(get_option('wbst_tvs_box_once_time'), "yes"); ?>>Yes </option>
                                <option value="no" <?php selected(get_option('wbst_tvs_box_once_time'), "no"); ?>>No </option>
                            </select>
                            <span class="description">Shows the notification only once per session</span>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row">
                            <label for="wbst_tvs_box_message">Website Offline Message:</label>
                        </th>
                        <td>
                            <input type="text" id="wbst_tvs_box_message" value="<?php echo get_option('wbst_tvs_box_message'); ?>" name="wbst_tvs_box_message" class="regular-text"/>
                            <span class="description"></span>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"></th>
                        <td>
                            <p>
                                <input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Save Changes') ?>"/>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
        </form>
    </div>
    <?php
}

function wbst_tvs_add_option_page()
{
    add_options_page('WTV Options', 'WTV Options', 'administrator', 'wbst-tvs-options-page', 'wbst_tvs_update_options_form');
}

add_action('admin_menu', 'wbst_tvs_add_option_page');
?>
