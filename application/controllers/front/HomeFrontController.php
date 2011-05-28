<?php

class HomeFrontController extends FrontController {

	/**
	 * Homepage with article, latest calendar and latest photoalbum
	 */
	public function index(){
	
        // Get available themes
		$themes = model('ThemeModel')->frontGetActiveForDropdown();
	
		// Output template
		$this->assign('themes', $themes);
		$this->display( 'home/index.tpl' );
	}

}
