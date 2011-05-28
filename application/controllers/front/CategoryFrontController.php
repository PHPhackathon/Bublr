<?php

	class CategoryFrontController extends FrontController {

		public function __construct(){
			header( 'Content-Type: application/json');
		}
		
		/**
		 * Retrieve all categories.
		 */
		public function all(){
			echo json_encode(array(
				'categories' => model('CategoryModel')->frontGetCategory()
			));
		}
	
	
}
	
	
	
