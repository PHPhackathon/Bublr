<?php

class NewsletterSubscriberModel extends Model {

	protected $table = 'newsletters_subscribers';

	/**
	 * ADMIN
	 * Get all subscribers for NewslettersSubscribersGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @param string $search optional
	 * @return array
	 */
	public function adminGetSubscribersGrid($start = 0, $limit = 9999, $order = 'created', $direction = 'DESC', $search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE email LIKE :search
				OR name LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT id, name, email, created, blacklisted

			FROM newsletters_subscribers

			{$whereSearch}

			ORDER BY {$order} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':start', abs($start), Database::PARAM_INT),
			array(':limit', abs($limit), Database::PARAM_INT),
			$search? array(':search', '%'.$search.'%', Database::PARAM_STR) : null
		);
	}

	/**
	 * ADMIN
	 * Count all subscribers for NewslettersSubscribersGrid
	 *
	 * @param string $search optional
	 * @return int
	 */
	public function adminCountSubscribersGrid($search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE email LIKE :search
				OR name LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT COUNT(id)
			FROM newsletters_subscribers
			{$whereSearch}
		";

		return $this->getField($query,
			$search? array(':search', '%'.$search.'%', Database::PARAM_STR) : null
		);
	}
	
	/**
	 * ADMIN
	 * Get all subscribers for export
	 *
	 * @return array
	 */
	public function adminGetForExport(){
		$query = "SELECT name, email, created, blacklisted FROM {$this->table} ORDER BY created DESC";
		return $this->getAll($query);
	}
}