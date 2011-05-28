<?php
/****************************************************
 * Lean mean web machine
 *
 * Input library to fetch GPC data
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class Input
{

	/**
	 * POST data
	 *
	 * @param string $key
	 * @param string $default optional
	 * @return string | null
	 */
	public static function post($key, $default = null){
		if(empty($_POST[$key])){
			return $default;
		}else{
			return $_POST[$key];
		}
	}
	
	/**
	 * POST data casted to integer
	 *
	 * @param string $key
	 * @param boolean $falseToNull optional. Return NULL if value validates false
	 * @return int | null
	 */
	public static function postInt($key, $falseToNull = false){
		if(empty($_POST[$key])){
			return $falseToNull? null : 0;
		}else{
			return intVal($_POST[$key]);
		}
	}
	
	/**
	 * POST data casted to double
	 *
	 * @param string $key
	 * @param boolean $falseToNull optional. Return NULL if value validates false
	 * @return double | null
	 */
	public static function postDouble($key, $falseToNull = false){
		if(empty($_POST[$key])){
			return $falseToNull? null : 0;
		}else{
			return doubleval($_POST[$key]);
		}
	}
	
	/**
	 * POST data that is filtered for empty html
	 *
	 * @param string $key
	 * @param string $default optional
	 * @return string | null
	 */
	public static function postHtml($key, $default = null){
		$value = trim(strip_tags(html_entity_decode(self::post($key))));
		if(empty($value)){
			return $default;
		}else{
			return $_POST[$key];
		}
	}
	
	/**
	 * GET data
	 *
	 * @param string $key
	 * @param string $default optional
	 * @return string | null
	 */
	public static function get($key, $default = null){
		if(empty($_GET[$key])){
			return $default;
		}else{
			return $_GET[$key];
		}
	}
	
	/**
	 * GET data casted to integer
	 *
	 * @param string $key
	 * @param boolean $falseToNull optional. Return NULL if value validates false
	 * @return int | null
	 */
	public static function getInt($key, $falseToNull = false){
		if(empty($_GET[$key])){
			return $falseToNull? null : 0;
		}else{
			return intVal($_GET[$key]);
		}
	}
	
	/**
	 * GET data casted to double
	 *
	 * @param string $key
	 * @param boolean $falseToNull optional. Return NULL if value validates false
	 * @return double | null
	 */
	public static function getDouble($key, $falseToNull = false){
		if(empty($_GET[$key])){
			return $falseToNull? null : 0;
		}else{
			return doubleval($_GET[$key]);
		}
	}
	
	/**
	 * COOKIE data
	 *
	 * @param string $key
	 * @param string $default optional
	 * @return string | null
	 */
	public static function cookie($key, $default = null){
		if(empty($_COOKIE[$key])){
			return $default;
		}else{
			return $_COOKIE[$key];
		}
	}
	
	/**
	 * FILE data
	 *
	 * @param string $key
	 * @return array | null
	 */
	public static function file($key){
		if(empty($_FILES[$key]) || $_FILES[$key]['error']){
			return null;
		}else{
			return $_FILES[$key];
		}
	}
	
	/**
	 * Determine if POST has been made
	 *
	 * @return boolean
	 */
	public static function posted(){
		return !empty($_POST);
	}

}
