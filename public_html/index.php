<?php
/****************************************************
 * Lean mean web machine
 *
 * Webroot index file
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

// Configure environment
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
setlocale(LC_TIME, 'nl_NL');
date_default_timezone_set('Europe/Brussels'); 
header('Content-Type: text/html; charset=utf-8');

// Define path constants
$currentPath = dirname(__FILE__);
define('WEBROOT_PATH', $currentPath.'/');
define('ROOT_PATH', realpath($currentPath.'/..').'/');
define('APPLICATION_PATH', ROOT_PATH.'application/');
define('CACHE_PATH', ROOT_PATH.'cache/');
define('CONFIG_PATH', ROOT_PATH.'config/');
define('FRAMEWORK_PATH', ROOT_PATH.'framework/');
define('LOGS_PATH', ROOT_PATH.'logs/');
define('RESOURCES_PATH', ROOT_PATH.'resources/');

// Define basic exceptions
class CacheException extends Exception{}
class LoaderException extends Exception{}
class FrameworkException extends Exception{}
class ModelException extends Exception{}
class DatabaseException extends Exception{}
class RouterException extends Exception{}

// Load basic config file
require_once CONFIG_PATH.'ApplicationConfig.php';

// Initialize loader
require_once FRAMEWORK_PATH.'libraries/Loader.php';
spl_autoload_register(array('Loader', 'autoloadClass'));

// Load basic helper functions
Loader::loadFunctions(
	'frontcontroller', 'admincontroller', 'library', 'model', 'quicklink', 'redirect', 'vardump', 
	'camelcase_to_underscore', 'underscore_to_camelcase', 'output_json_encode'
);

// Find route to controller and function
$router = library('Router');
try{
	if(!$router->findRoute()){
		$router->execute404();
	}
}catch(RouterException $e){
	$router->execute500();
}

// Execute found route
$router->executeRoute();
