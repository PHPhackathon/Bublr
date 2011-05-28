<?php

class CronFrontController extends FrontController {

	/**
	 * Constructor
	 */
	public function __construct(){
	
		// Validate authentication key
		if(Input::get('key') != ApplicationConfig::$cronjobKey){
			die('-- wrong or missing key --');
		}
		
		parent::__construct();
	}
}