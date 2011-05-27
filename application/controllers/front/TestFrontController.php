<?php

class TestFrontController extends FrontController {
	
	/**
	 * Test routing. Print second url segment (number)
	 */
	public function foo($number, $string = null){
		vardump($number);
		vardump($string);
	}

}