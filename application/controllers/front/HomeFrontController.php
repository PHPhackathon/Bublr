<?php

class HomeFrontController extends FrontController {

	/**
	 * Homepage with article, latest calendar and latest photoalbum
	 */
	public function index(){
		$this->display( 'home/index.tpl' );
		//die('-- homepage ok --');
	}

}
