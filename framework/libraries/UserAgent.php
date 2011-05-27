<?php
/****************************************************
 * Lean mean web machine
 *
 * Library to access client and user agent data
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-11
 *
 ****************************************************/

class UserAgent
{

	/**
	 * Client IP address
	 *
	 * @return string
	 */
	public static function ip(){
		return $_SERVER['REMOTE_ADDR'];
	}
}
