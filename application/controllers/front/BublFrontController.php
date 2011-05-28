<?php

	class BublFrontController extends FrontController {

		public function __construct(){
			header( 'Content-Type: application/json');
		}
		
		/**
		 * Retrieve all categories.
		 * 
		 * @param int $category
		 */
		public function products( $category, $steps = 10, $minimum_step_size=20 ){
			$bubls = model('BublModel')->frontGetBublsInCategoy( $category );
			$price_range = model('CategoryModel')->frontGetPriceRangeInCategory( $category );
			
			$max = null;
			$min = null;
			
			foreach( $bubls as $bubl ){
				if( $max === null || $max < $bubl['score'] )
					$max = $bubl['score'];
					
				if( $min === null || $min > $bubl['score'] )
					$min = $bubl['score'];
			}
			
			if( empty( $max ) )
				$max = 0;
				
			if( empty( $min ) )
				$min = 0;
			
			if( empty( $price_range['max'] ) )
				$price_range['max'] = 0;
				
			if( empty( $price_range['min'] ) )
				$price_range['min'] = 0;
			
			$step_size = ( $price_range['max'] - $price_range['min'] ) / $steps;
			if( $step_size < $minimum_step_size )
				$step_size = $minimum_step_size;
			
			$all_steps = array();
			$prev = $price_range['min'];
			while( $prev < $price_range['max'] ) {
				$all_steps[] = $prev . ' - ' . ( $prev + $step_size );
				$prev += $step_size;
			}
			
			$price_range[ 'steps' ] = $all_steps;
			
			$bubls = array_map( array( $this, '_parse_entities' ), $bubls );
			
			echo json_encode(array(
				'products' => $bubls,
				'score_range' => array( 'max' => $max, 'min' => $min ),
				'price_range' => $price_range
			));
		}
		
		private function _parse_entities( $value ){
			return array_map( 'htmlentities', $value );
		}
	
	
}
	
	
	
