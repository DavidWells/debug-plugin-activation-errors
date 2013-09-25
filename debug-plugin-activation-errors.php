<?php
/*
Plugin Name: Debug "unexpected output" During Plugin Activation
Plugin URI: http://www.inboundnow.com/
Description: This developer plugin allows for easy debugging of those ambiguous "The plugin generated ### characters of unexpected output during activation. If you notice “headers already sent” messages, problems with syndication feeds or other issues, try deactivating or removing this plugin." Hope it helps you solve your headaches!
Version: 1.0.1
Author: David Wells
Author URI: http://www.inboudnow.com/
*/

if (is_admin()) {
	
/* Debug Plugin Activation errors */
add_action('activated_plugin','save_error');
function save_error(){
    update_option('plugin_error',  ob_get_contents());
}

add_action('admin_notices', 'plugin_activation_error_notice');
function plugin_activation_error_notice() {
    global $current_user ;
    $error = get_option('plugin_error');
   	if ($error === ""){
   		$error = 'Error Log Empty. Run your plugin activation';
   	} 
        echo '<div class="updated"><h2>The Issues Causing "unexpected output" are:</h2><p>';
        echo "<pre>";
        echo $error;
        echo "</pre>";
        echo "<h2><a href='?plugin_activation_message_reset=1'>Clear Warnings and Try Plugin Install Again</a></h2>";
        echo "</p></div>";
}
add_action('admin_init', 'plugin_activation_message_reset');
function plugin_activation_message_reset() {
    global $current_user;
        if ( isset($_GET['plugin_activation_message_reset']) && '1' == $_GET['plugin_activation_message_reset'] ) {
         update_option('plugin_error',  'Activation Error Cleared. Run your plugin activation again from plugins admin page. Turn off this plugin to get rid of these messages'); // clear errors
    }
}

}
?>