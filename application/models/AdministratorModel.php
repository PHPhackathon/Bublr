<?php

class AdministratorModel extends Model {

	protected $table = 'administrators';

	/**
	 * ADMIN
	 * Get all administrators for AdministratorsGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @param string $search optional
	 * @return array
	 */
	public function adminGetAdministratorsGrid($start = 0, $limit = 9999, $order = 'firstname', $direction = 'ASC', $search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE email LIKE :search
				OR firstname LIKE :search
				OR lastname LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT id, firstname, lastname, email, online, created, updated, last_login

			FROM administrators

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
	 * Count all administrators for AdministratorsGrid
	 *
	 * @param string $search optional
	 * @return int
	 */
	public function adminCountAdministratorsGrid($search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE email LIKE :search
				OR firstname LIKE :search
				OR lastname LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT COUNT(id)
			FROM administrators
			{$whereSearch}
		";

		return $this->getField($query,
			$search? array(':search', '%'.$search.'%', Database::PARAM_STR) : null
		);
	}
	
	/**
	 * ADMIN
	 * Get administrator for AdministratorsGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetAdministratorForGrid($id){
		$query = "
			SELECT id, firstname, lastname, email, online, created, updated, last_login
			FROM administrators
			WHERE id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}
	
	/**
	 * ADMIN
	 * Get administrator for AdministratorsFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetAdministratorForForm($id){
		$query = "
			SELECT id, firstname, lastname, email, online
			FROM administrators
			WHERE id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}
	
	/**
	 * ADMIN
	 * Validate login
	 *
	 * @param string $email
	 * @param string $password
	 * @return int administrator id
	 */
	public function adminValidateLogin($email, $password){
		$passwordHash = sha1(ApplicationConfig::$hashSalt.$password);
		$query = "SELECT id FROM administrators WHERE email = :email AND password = :password";
		return $this->getField($query,
			array(':email', $email, Database::PARAM_STR),
			array(':password', $passwordHash, Database::PARAM_STR)
		);
	}
}