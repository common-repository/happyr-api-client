<?php
/*
 * Plugin Name: Happyr Api client
 * Plugin URI: 
 * Version: 1.1.0
 * Description: Imports a library to integrate with the happy recruiting api
 * Author: Tobias Nyholm
 * Author URI: http://www.tnyholm.se
 * License: GPLv3
 * Copyright: Tobias Nyholm 2013
 */


//load the amin files
require_once __DIR__.'/admin.php';

//If vendors is installed
if(file_exists(__DIR__.'/vendor/autoload.php')){
	require_once __DIR__.'/vendors-installed.php';
}
else{
	require_once __DIR__.'/vendors-not-installed.php';
}

/**
 * An installation hook
 */
function happyr_install(){
	add_option('happyr-api-username','');
	add_option('happyr-api-token','');
	add_option('happyr-api-endpoint','http://happyrecruiting.se/api/');
	add_option('happyr-api-version','');
}
register_activation_hook(__FILE__,'happyr_install');

