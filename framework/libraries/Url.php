<?php
/****************************************************
 * Lean mean web machine
 *
 * Url library to fetch url segments
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class Url
{

	protected static $urlSegments = null;
	
	/**
	 * Parse request url and assign to self::$urlSegments
	 *
	 * @return void
	 */
	protected static function parseUrl(){
		if(self::$urlSegments === null){
			$url = preg_replace('/^(.*)\?.*$/', '\1', $_SERVER['REQUEST_URI']);
			$url = trim($url, '/');
			$urlSegmentsRaw = explode('/', $url);
			self::$urlSegments = array();
			foreach($urlSegmentsRaw as $urlSegment){
				if($urlSegment !== '') array_push(self::$urlSegments, urldecode($urlSegment));
			}
		}
	}
	
	/**
	 * Get admin segment from url
	 *
	 * @return string | null
	 */
	public static function getAdmin(){
		self::parseUrl();
		if(isset(self::$urlSegments[0]) && self::$urlSegments[0] == 'admin'){
			return 'admin';
		}
		return null;
	}
	
	/**
	 * Get controller segment from url
	 *
	 * @return string
	 */
	public static function getController(){
		self::parseUrl();
		$index = (isset(self::$urlSegments[0]) && self::$urlSegments[0] == 'admin')? 1 : 0;
		return self::getSegment($index);
	}
	
	/**
	 * Get function segment from url
	 *
	 * @return string
	 */
	public static function getFunction(){
		self::parseUrl();
		$index = (isset(self::$urlSegments[0]) && self::$urlSegments[0] == 'admin')? 2 : 1;
		return self::getSegment($index);
	}
	
	/**
	 * Get parameter segments from url
	 *
	 * @return array
	 */
	public static function getParameters(){
		self::parseUrl();
		$index = (isset(self::$urlSegments[0]) && self::$urlSegments[0] == 'admin')? 3 : 2;
		return array_slice(self::$urlSegments, $index);
	}
	
	/**
	 * Get specific segment at index
	 *
	 * @param int $index
	 * @return string | null
	 */
	public static function getSegment($index){
		self::parseUrl();
		return isset(self::$urlSegments[$index])? self::$urlSegments[$index] : null;
	}
	
	/**
	 * Get cleaned segments as url string
	 *
	 * @return string e.g. "products/details/product_quicklink"
	 */
	public static function getSegmentsUrl(){
		self::parseUrl();
		return implode('/', self::$urlSegments);
	}

}
