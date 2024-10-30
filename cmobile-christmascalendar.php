<?php
/**
* @package Cmobile Christmas Calendar
*/
/*
Plugin Name: CMobile Christmas Calendar
Plugin URI: http://christmas.cmobile.se
Description:: Christmas calendar for businesses or just for fun. 
Choose between 24 or 25 days in calendar and watch the countdown to Dec 1st.
Version: 1.0.0
Author: CMobile AB
Author URI: https://christmas.cmobile.se
License: GPLv2 or later
Text Domain: cmobile-christmascalendar
Domain Path: /languages
*/


defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );

define( 'CMOB_CC_VERSION', '1.0.0' );
define( 'CMOB_CC_DIR',plugin_dir_path( __FILE__ ));

include_once( CMOB_CC_DIR . '/admin/cmobile-options.php' );
/**
* Main plugin class
*/
class Cmobilechristmascalendar 
{
    function __construct(){
        add_action('init', array($this,'custom_post_type'));
        add_action( 'plugins_loaded', array($this,'load_plugin_textdomain' ));
        
    }
    
    /**
	 * Holder of the christmas door
	 */
    public static function cmob_ccholder_shortcode()
	{
		return '<div id="doorhandler" class="doorsettings"></div>';
    }
    /**
	 * Shortcode to generate christmas calenadr
	 */
    public static function cmob_cc_shortcode()
	{   
        //wp_register_script( 'wp-weglot-admin-js', WEGLOT_RESURL . 'wp-weglot-admin-js.js', array( 'jquery' ),WEGLOT_VERSION, true );
        wp_register_script ( 'cmob-christmascalendar-js', plugins_url ( 'assets/cmob-christmascalendar-js.js', __FILE__ ));
        wp_register_style ( 'cmob-christmascalendar-css', plugins_url ( 'assets/cmob-christmascalendar-css.css', __FILE__ ) );
		//echo '<div id="doorhandler" class="doorsettings"></div>';
        $options = get_option('cmob_cc_option', 'default text');
		wp_enqueue_script('cmob-christmascalendar-js');
        wp_enqueue_style('cmob-christmascalendar-css');
        wp_localize_script('cmob-christmascalendar-js', 'php_vars', $options);
	}
    function load_plugin_textdomain() {
        load_plugin_textdomain( 'cmobile-christmascalendar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
    
    function activate(){
        $this->custom_post_type();
        flush_rewrite_rules();
    }

    function deactivate(){
        flush_rewrite_rules();
    }

    function custom_post_type(){
        register_post_type('book',['public' => true, 'label' => 'Books']);
    }
}

if ( class_exists('Cmobilechristmascalendar')) {
    $Cmobilechristmascalendar = new Cmobilechristmascalendar();
    
}
// add shortcode
add_shortcode('cmob_ccholder',array('Cmobilechristmascalendar','cmob_ccholder_shortcode'));
add_shortcode('cmob_cc',array('Cmobilechristmascalendar','cmob_cc_shortcode'));

// activation
register_activation_hook( __FILE__, array( $Cmobilechristmascalendar,'activate' ));

// deactivation
register_deactivation_hook( __FILE__, array( $Cmobilechristmascalendar,'deactivate' ));

// uninstall

