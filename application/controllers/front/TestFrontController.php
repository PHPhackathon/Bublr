<?php

class TestFrontController extends FrontController {
	
	/**
	 * Test routing. Print second url segment (number)
	 */
	public function foo($number, $string = null){
		vardump($number);
		vardump($string);
	}
	
	/**
	 * Fetch tweets for bubls
	 *
	 */
	public function bublTweets(){
	
		library('TwitterBubls')->processBubls(array(1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12));
		
	}

}