<?php
/**
 * This file assumes that the vendors is installed
 */

//autoload the composer files
$loader = require_once __DIR__.'/vendor/autoload.php';

//enable annontation
use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));


use Happyr\ApiClient\HappyrApi;
use Happyr\ApiClient\Configuration;

/**
 * Get an instance of the happyr api class
 * @return \Happyr\ApiClient\HappyrApi
 */
function happyr_getApi(){
	$config=new Configuration();

	//add values form the database into the api
	$config->username=get_option('happyr-api-username');
	$config->token=get_option('happyr-api-token');
	$config->baseUrl=get_option('happyr-api-endpoint');

	//check if proper version number
	$version=get_option('happyr-api-version');
	if(preg_match('|^([0-9]+\.?)+$|', $version)){
		$config->version=$version;
	}
		
	return new HappyrApi($config);
}
