<?php
/*
Plugin Name: Debug "unexpected output" During Plugin Activation
Plugin URI: http://www.inboundnow.com/
Description: This developer plugin allows for easy debugging of those ambiguous "The plugin generated ### characters of unexpected output during activation. If you notice “headers already sent” messages, problems with syndication feeds or other issues, try deactivating or removing this plugin." Hope it helps you solve your headaches!
Version: 1.9.1
Author: David Wells
Author URI: http://www.inboudnow.com/
*/

if (is_admin()) {

/* Debug Plugin Activation errors */
add_action('activated_plugin','plugin_activation_save_error');
function plugin_activation_save_error(){
    update_option('plugin_activation_error_message',  ob_get_contents());
}

/* Display Admin Notices */
add_action('admin_notices', 'plugin_activation_error_notice');
function plugin_activation_error_notice() {

        $error = get_option('plugin_activation_error_message');
        if ($error !== ""){
        echo '<div class="updated"><h2>The Issues Causing "unexpected output" are:</h2>';
        echo "<pre>";
        echo $error;
        echo "</pre>";
        echo "<h2><a href='?plugin_activation_message_reset=1'>Clear Warnings and Try Plugin Install Again</a></h2>";
        echo "</div>";
        } else {
        echo '<div class="updated"><h2>Run Your Plugin Activation to Show Error Log</h2></div>';
        }
}

/* Reset Error Message */
add_action('admin_init', 'plugin_activation_message_reset');
function plugin_activation_message_reset() {
    global $current_user;
        if ( isset($_GET['plugin_activation_message_reset']) && '1' == $_GET['plugin_activation_message_reset'] ) {
         update_option('plugin_activation_error_message',  'Activation Error Cleared. Run your plugin activation again from plugins admin page. Turn off this plugin to get rid of these messages'); // clear errors
    }
}

/* Activation Method  */
register_activation_hook( __FILE__, 'plugin_activation_error_setup' );
function plugin_activation_error_setup() {
    add_option('plugin_activation_error_message',  'Error Log Empty. Run your plugin activation');
}

/* Delete on deactivation */
register_deactivation_hook( __FILE__, 'plugin_activation_error_delete' );
function plugin_activation_error_delete() {
    delete_option( 'plugin_activation_error_message' );
}

}
?>