<?php

	class BublFrontController extends FrontController {

		public function __construct(){
			header( 'Content-Type: application/json');
		}
		
		/**
		 * Retrieve all tweets for a buble.
		 * 
		 * @param int $buble_id
		 * @return array
		 */
		public function tweets( $buble_id ){
			echo json_encode(array(
				'tweets' => model('BublTweetModel')->frontGetTweetsForBubl( $buble_id )
			));
		}
	
	
}
	
	
	
