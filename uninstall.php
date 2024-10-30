<?php

/**
* Trigger this file on Plugin uninstall
*@package Cmobilechristmascalendar
**/

if ( ! defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$option_name = 'cmob_cc_option';
 
delete_option($option_name);
