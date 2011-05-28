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
	
	/**
	 * Figure out best twitter search query
	 *
	 */
	public function twitterQuery(){
		$bubls = model('BublModel')->getAll("SELECT title FROM bubls");
		foreach($bubls as $bubl){
			vardump('Original title: ' . $bubl['title']);
			
			$titleParts = explode(' ', $bubl['title']);
			$searchTitle =  implode(' ', array_slice($titleParts, 0, 3));
			
			vardump('Query title: ' . $searchTitle);
			vardump('Search query: http://search.twitter.com/search.json?q=' . urlencode($searchTitle));
			vardump('---------------------------------------------');
		}
	}

}