<?php

/**
 * WordPress settings API class
 *
 * @author Tareq Hasan
 */

class SatSMS_Setting_Options {

    private $settings_api;

    function __construct() {

        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') ); 
    }

    /**
     * Admin init hook
     * @return void 
     */
    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Admin Menu CB
     * @return void 
     */
    function admin_menu() {
        add_menu_page( __( 'SMS Settings', 'satosms' ), __( 'SMS Settings', 'satosms' ), 'manage_options', 'sat-order-sms-notification-settings', array( $this, 'plugin_page' ), 'dashicons-email-alt' );
    }

    /**
     * Get All settings Field
     * @return array 
     */
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'satosms_general',
                'title' => __( 'General Settings', 'satosms' )
            ),
            array(
                'id' => 'satosms_gateway',
                'title' => __( 'SMS Gateway Settings', 'satosms' )
            ),

            array(
                'id' => 'satosms_message',
                'title' => __( 'SMS Settings', 'satosms' )
            )
        );
        return apply_filters( 'satosms_settings_sections' , $sections );
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {

        
        $buyer_message = "Thanks for purchasing\nYour [order_id] is now [order_status]\nThank you"; 
        $admin_message = "You have a new Order\nThe [order_id] is now [order_status]\n";    
        $settings_fields = array(

            'satosms_general' => apply_filters( 'satosms_general_settings', array(
                array(
                    'name' => 'enable_notification',
                    'label' => __( 'Enable SMS Notifications', 'satosms' ),
                    'desc' => __( 'If checked then enable your sms notification for new order', 'satosms' ),
                    'type' => 'checkbox',
                ),

                array(
                    'name' => 'admin_notification',
                    'label' => __( 'Enable Admin Notifications', 'satosms' ),
                    'desc' => __( 'If checked then enable admin sms notification for new order', 'satosms' ),
                    'type' => 'checkbox',
                    'default' => 'on'
                ),

                array(
                    'name' => 'buyer_notification',
                    'label' => __( 'Enable buyer Notification', 'satosms' ),
                    'desc' => __( 'If checked then buyer can get notification options in checkout page', 'satosms' ),
                    'type' => 'checkbox',
                ),

                array(
                    'name' => 'force_buyer_notification',
                    'label' => __( 'Force buyer notification', 'satosms' ),
                    'desc' => __( 'If select yes then buyer notification option must be required in checkout page', 'satosms' ),
                    'type' => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'   => 'No'
                    )
                ),

                array(
                    'name' => 'buyer_notification_text',
                    'label' => __( 'Buyer Notification Text', 'satosms' ),
                    'desc' => __( 'Enter your text which is appeared in checkout page for buyer as a checkbox', 'satosms' ),
                    'type' => 'textarea',
                    'default' => 'Send me order status notifications via sms (N.B.: Your SMS will be send in your billing email. Make sere phone number must have an extension)'
                ),
                array(
                    'name' => 'order_status',
                    'label' => __( 'Check Order Status ', 'satosms' ),
                    'desc' => __( 'In which status you will send notifications', 'satosms' ),
                    'type' => 'multicheck',
                    'options' => array(
                        'on-hold' => __( 'On Hold', 'satosms' ),
                        'pending'  => __( 'Pending', 'satosms' ),
                        'processing'  => __( 'Processing', 'satosms' ),
                        'completed'  => __( 'Completed', 'satosms' ),
                    )
                )
            ) ),

            'satosms_gateway' => apply_filters( 'satosms_gateway_settings',  array(
                array(
                    'name' => 'sms_gateway',
                    'label' => __( 'Select your Gateway', 'satosms' ),
                    'desc' => __( 'Select your sms gateway', 'satosms' ),
                    'type' => 'select',
                    'default' => '-1',
                    'options' => $this->get_sms_gateway()
                ),
            ) ),

            'satosms_message' => apply_filters( 'satosms_message_settings',  array(
                array(
                    'name' => 'sms_admin_phone',
                    'label' => __( 'Enter your Phone Number with extension', 'satosms' ),
                    'desc' => __( '<br>Admin order sms notifications will be send in this number. Please make sure that the number must have a extension (e.g.: +8801626265565 where +88 will be extension)', 'satosms' ),
                    'type' => 'text'
                ),
                array(
                    'name' => 'admin_sms_body',
                    'label' => __( 'Enter your SMS body', 'satosms' ),
                    'desc' => __( ' Write your custom message. When an order is create then you get this type of format message. For order id just insert <code>[order_id]</code> and for order status insert <code>[order_status]</code>', 'satosms' ),
                    'type' => 'textarea',
                    'default' => __( $admin_message, 'satosms' )
                ),

                array(
                    'name' => 'sms_body',
                    'label' => __( 'Enter buyer SMS body', 'satosms' ),
                    'desc' => __( ' Write your custom message. If enbale buyer notification options then buyer can get this message in this format. For order id just insert <code>[order_id]</code> and for order status insert <code>[order_status]</code>', 'satosms' ),
                    'type' => 'textarea',
                    'default' => __( $buyer_message, 'satosms' )
                ),
            ) ),
        );

        return apply_filters( 'satosms_settings_
            section_content', $settings_fields );
    }

    /**
     * Loaded Plugin page
     * @return void
     */
    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

    /**
     * Get sms Gateway settings
     * @return array 
     */
    function get_sms_gateway() {
        $gateway = array( 
            'none'      => __( '--select--', 'satosms' ),
            'talkwithtext' => __( 'Talk With Text', 'satosms' ),
            'clickatell' => __( 'Clickatell', 'satosms' ),
        );

        return apply_filters( 'satosms_sms_gateway', $gateway );
    }

} // End of SatSMS_Setting_Options Class

/**
 * SMS Gateway Settings Extra panel options
 * @return void 
 */
function satosms_settings_field_gateway() {

    $talkwithtext_username   = satosms_get_option( 'talkwithtext_username', 'satosms_gateway', '' ); 
    $talkwithtext_password   = satosms_get_option( 'talkwithtext_password', 'satosms_gateway', '' ); 
    $talkwithtext_originator = satosms_get_option( 'talkwithtext_originator', 'satosms_gateway', '' ); 
    $clickatell_name         = satosms_get_option( 'clickatell_name', 'satosms_gateway', '' ); 
    $clickatell_password     = satosms_get_option( 'clickatell_password', 'satosms_gateway', '' ); 
    $clickatell_api          = satosms_get_option( 'clickatell_api', 'satosms_gateway', '' ); 

    $twt_helper        = sprintf( 'Please fill talk with text username and password. If not then visit <a href="%s" target="_blank">%s</a>', 'http://my.talkwithtext.com/', 'Talk With Text' );
    $clickatell_helper = sprintf( 'Please fill Clickatell informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'https://www.clickatell.com/login/', 'Clickatell');
    ?>
    
    <?php do_action( 'satosms_gateway_settings_options_before' ); ?>

    <div class="talkwithtext_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $twt_helper, 'satosms' ); ?></strong>
        </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text Username', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_username]" id="satosms_gateway[talkwithtext_username]" value="<?php echo $talkwithtext_username; ?>">
                    <span><?php _e( 'The HTTP API username that is supplied to your account (most of the times it is your email)', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text Password', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_password]" id="satosms_gateway[talkwithtext_password]" value="<?php echo $talkwithtext_password; ?>">
                    <span><?php _e( 'The HTTP API password of your account', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text Originator', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_originator]" id="satosms_gateway[talkwithtext_originator]" value="<?php echo $talkwithtext_originator; ?>">
                    <span><?php _e( 'The originator of your message (11 alphanumeric or 14 numeric values)', 'satosms' ); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="clickatell_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $clickatell_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Clickatell name', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[clickatell_name]" id="satosms_gateway[clickatell_name]" value="<?php echo $clickatell_name; ?>">
                    <span><?php _e( 'Clickatell Username', 'satosms' ); ?></span> 
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Clickatell Password', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[clickatell_password]" id="satosms_gateway[clickatell_password]" value="<?php echo $clickatell_password; ?>">
                    <span><?php _e( 'Clickatell password', 'satosms' ); ?></span> 
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Clickatell api', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[clickatell_api]" id="satosms_gateway[clickatell_api]" value="<?php echo $clickatell_api; ?>">
                    <span><?php _e( 'Clickatell API id', 'satosms' ); ?></span> 
                </td>
            </tr>
        </table>
    </div>

    <?php do_action( 'satosms_gateway_settings_options_after' ) ?>
    <?php
}

// hook for Settings API for adding extra sections
add_action( 'wsa_form_bottom_satosms_gateway', 'satosms_settings_field_gateway' );

