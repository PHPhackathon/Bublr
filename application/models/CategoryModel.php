<?php

class CategoryModel extends Model {
	
	protected $table = 'categories';
	
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
	
}