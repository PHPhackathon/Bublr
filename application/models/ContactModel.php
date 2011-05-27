<?php

class ContactModel extends Model {

	protected $table = 'contact';

	/**
	 * ADMIN
	 * Get all messages for ContactGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @param string $search optional
	 * @return array
	 */
	public function adminGetContactGrid($start = 0, $limit = 9999, $order = 'created', $direction = 'DESC', $search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE email LIKE :search
				OR firstname LIKE :search
				OR lastname LIKE :search
				OR message LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT id, firstname, lastname, email, phone, message, created

			FROM contact

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
	 * Count all messages for ContactGrid
	 *
	 * @param string $search optional
	 * @return int
	 */
	public function adminCountContactGrid($search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE email LIKE :search
				OR firstname LIKE :search
				OR lastname LIKE :search
				OR message LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT COUNT(id)
			FROM contact
			{$whereSearch}
		";

		return $this->getField($query,
			$search? array(':search', '%'.$search.'%', Database::PARAM_STR) : null
		);
	}
}