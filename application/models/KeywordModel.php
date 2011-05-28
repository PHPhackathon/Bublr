<?php

class KeywordModel extends Model {

	protected $table = 'keywords';


	/**
	 * LIBRARY
	 * Get keywords for matching
	 *
	 * @return array
	 */
	public function getAllForMatching(){
		$query = "
			SELECT k.id, k.keyword, k.score
			FROM keywords k
		";
		return $this->getAll($query);
	}

}