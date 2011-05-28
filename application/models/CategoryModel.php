<?php

class CategoryModel extends Model {
	
	protected $table = 'themes';
	
	/**
	 * LIBRARY
	 * Get all category id's. Array key is feed hash
	 *
	 * @return array
	 */
	public function libraryGetAllAssocQuicklink(){
		$query = "SELECT quicklink, id FROM {$this->table}";
		return $this->getAssoc($query);
	}
	
	/**
	 * Get all categories by name and id for front.
	 * 
	 * @return array
	 */
	public function frontGetCategory(){
		$query = "SELECT id, title FROM {$this->table}";
		
		return $this->getAll( $query );
	}
	
	/**
	 * Returns the price range in a category.
	 * 
	 * @param int $category
	 * @return array
	 */
	public function frontGetPriceRangeInCategory( $category ){
		
		$query = "
			SELECT MAX(average_price) max, MIN(average_price) min
			
			FROM bubls
			
			WHERE theme_id = :category
		";
		
		return $this->getRecord( $query,
			array( ':category', $category, Database::PARAM_INT ) );
	}
	
}