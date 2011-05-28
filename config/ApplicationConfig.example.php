<?php
/****************************************************
 * Lean mean web machine
 *
 * Application config class
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/
class ApplicationConfig
{

	// Informational settings
	public static $domain					= 'bublr.bytelogic.be'; // bublr.net
	public static $subdomain				= null;
	public static $siteUrl					= 'http://bublr.bytelogic.be/';
	public static $assets1Url				= 'http://bublr.bytelogic.be/';
	public static $assets2Url				= 'http://bublr.bytelogic.be/';
	public static $assets3Url				= 'http://bublr.bytelogic.be/';
	public static $adminUrl					= 'http://bublr.bytelogic.be/admin/';

	// SEO and meta data
	public static $siteName					= 'Bublr';
	public static $metaKeywords				= '';
	public static $metaDescription			= '';

	// Mail settings
	public static $mailSenderName			= 'Bublr';
	public static $mailSenderEmail			= 'dirk@bytelogic.be';

	// Templates and caching
	public static $forceCompile				= true;
	public static $enableCache				= true;
	public static $minifyCss				= false;
	public static $minifyJavascript			= false;

	// Security
	public static $hashSalt					= 'Efsdf1231fgdfgdfg';
	public static $cronjobKey				= 'aaabbbbcccc';

	// Cookies
	public static $cookieLifetime			= 86400;

	// Error handling
	public static $errorController			= 'ErrorFrontController';
	public static $error404Function			= 'error404';
	public static $error403Function			= 'error403';
	public static $error500Function			= 'error500';

}
