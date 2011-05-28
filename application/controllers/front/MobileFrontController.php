<?php

class MobileFrontController extends FrontController {

	/**
	 * Mobile homepage
	 */
	public function index(){
	
		// Get available themes
		$themes = model('ThemeModel')->frontGetActiveForDropdown();
	
		// Output template
		$this->assign('themes', $themes);
		$this->display( 'mobile/index.tpl' );
	}

}
