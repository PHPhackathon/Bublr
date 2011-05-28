<?php

class MobileFrontController extends FrontController {

	/**
	 * Mobile homepage
	 */
	public function index(){
		$this->display( 'mobile/index.tpl' );
	}

}
