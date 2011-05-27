<?php

class HomeAdminController extends AdminController {
	
	/**
	 * Main admin page
	 *
	 */
	public function index($param = null){
		$this->display('home/index.tpl');
	}
	
}