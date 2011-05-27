<?php
/****************************************************
 * Lean mean web machine
 *
 * Admin controller library
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class AdminController extends Controller
{

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		if(Input::get('session_id')){
			session_id(Input::get('session_id'));
		}
		session_start();
		parent::__construct();

		// Require login
		AdminAuthorizator::requireLogin();

		// Set template path
		$this->templatePath = APPLICATION_PATH.'views/admin/';

	}

}
