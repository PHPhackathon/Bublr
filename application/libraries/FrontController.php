<?php
/****************************************************
 * Lean mean web machine
 *
 * Front controller library
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class FrontController extends Controller
{

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		
		// Start session
		if(!session_id()){
			session_start();
		}
		
		// Call parent constructor
		parent::__construct();
		
		// Set google analytics account
		Loader::loadConfig('GoogleConfig');
		$this->assign('googleAnalyticsAccount', GoogleConfig::$analyticsAccount);

		// Set template path
		$this->templatePath = APPLICATION_PATH.'views/front/';
	}
	
}
