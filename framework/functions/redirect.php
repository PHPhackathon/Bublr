<?php
/****************************************************
 * Lean mean web machine
 *
 * Redirect function
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function redirect($destination, $statusCode = 302, $customHeaders = null){
	
	// Set statuscode
	switch($statusCode){
		case 301:
			header("HTTP/1.0 301 Moved Permanently");
			break;
		case 302:
			header("HTTP/1.0 302 Found");
			break;
		case 403:
			header("HTTP/1.0 403 Forbidden");
			break;
		case 404:
			header("HTTP/1.0 404 Not Found");
			break;
	}
	
	// Set custom headers
	if(is_array($customHeaders)){
		foreach($customHeaders as &$customHeader){
			header($customHeader);
		}
	}
	
	// Send redirect header
	header("Location: {$destination}");
	exit;
}