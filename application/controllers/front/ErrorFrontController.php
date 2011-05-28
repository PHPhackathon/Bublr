<?php

class ErrorFrontController extends FrontController {

	/**
	 * Not found: 404
	 */
	public function error404(){
		header('HTTP/1.0 404 Not Found');
		$this->display('error/error404.tpl');
	}
	
	/**
	 * Forbidden: 403
	 */
	public function error403(){
		header('HTTP/1.0 403 Forbidden');
		die('403 forbidden');
	}
	
	/**
	 * Internal server error: 500
	 */
	public function error500(){
		header('HTTP/1.0 500 Internal Server Error');
		die('500 internal server error');
	}

}