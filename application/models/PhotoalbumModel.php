<?php

class PhotoalbumModel extends Model {

	protected $table = 'photoalbums';

	/**
	 * ADMIN
	 * Get all photoalbums for PhotoalbumsGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @param string $search optional
	 * @return array
	 */
	public function adminGetPhotoalbumsGrid($start = 0, $limit = 9999, $order = 'date', $direction = 'DESC', $search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE pa.title LIKE :search
				OR pa.description LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT
				pa.id, pa.title, pa.date, pa.online, pa.created, pa.updated,
				COUNT(img.id) AS images_count

			FROM photoalbums pa

			LEFT JOIN images img
			ON pa.id = img.related_id
			AND img.related_table = 'photoalbums'

			{$whereSearch}

			GROUP BY pa.id

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
	 * Count all photoalbums for PhotoalbumsGrid
	 *
	 * @param string $search optional
	 * @return int
	 */
	public function adminCountPhotoalbumsGrid($search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE pa.title LIKE :search
				OR pa.description LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT COUNT(pa.id)
			FROM photoalbums pa
			{$whereSearch}
		";

		return $this->getField($query,
			$search? array(':search', '%'.$search.'%', Database::PARAM_STR) : null
		);
	}

	/**
	 * ADMIN
	 * Get photoalbum for PhotoalbumsGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetPhotoalbumForGrid($id){
		$query = "
			SELECT
				pa.id, pa.title, pa.date, pa.online, pa.created, pa.updated,
				COUNT(img.id) AS images_count

			FROM photoalbums pa

			LEFT JOIN images img
			ON pa.id = img.related_id
			AND img.related_table = 'photoalbums'

			WHERE pa.id = :id

			GROUP BY pa.id

			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get photoalbum for PhotoalbumsFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetPhotoalbumForForm($id){
		$query = "
			SELECT pa.id, pa.title, pa.date, pa.description, pa.online
			FROM photoalbums pa
			WHERE pa.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Delete photoalbum and all related data
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){
		model('ImageModel')->deleteAllByRelatedTableId('photoalbums', $id);
		return parent::delete($id);
	}
	
	/**
	 * FRONT
	 * Get latest photoalbum
	 *
	 * @return array
	 */
	public function frontGetLatestPhotoalbum(){
		$query = "
			SELECT pa.id, pa.title, pa.quicklink, pa.date, pa.description

			FROM photoalbums pa

			INNER JOIN images img
			ON pa.id = img.related_id
			AND img.related_table = 'photoalbums'

			WHERE pa.online = 1

			GROUP BY pa.id
			ORDER BY pa.date DESC
			LIMIT 1
		";

		return $this->getRecord($query);
	}
	
	/**
	 * FRONT
	 * Get photoalbums by year
	 *
	 * @param int $year
	 * @return array
	 */
	public function frontGetPhotoalbumsByYear($year){
		$query = "
			SELECT pa.id, pa.title, pa.quicklink, pa.date, pa.description
			FROM photoalbums pa
			WHERE pa.online = 1
			AND DATE_FORMAT(pa.date, '%Y') = :year
			ORDER BY pa.date DESC
		";

		return $this->getAll($query,
			array(':year', $year, Database::PARAM_INT)
		);
	}
	
	/**
	 * FRONT
	 * Get photoalbum by year and quicklink
	 *
	 * @param int $year
	 * @param string $quicklink
	 * @return array
	 */
	public function frontGetPhotoalbumByYearQuicklink($year, $quicklink){
		$query = "
			SELECT pa.id, pa.title, pa.quicklink, pa.date, pa.description
			FROM photoalbums pa
			WHERE pa.online = 1
			AND DATE_FORMAT(pa.date, '%Y') = :year
			AND pa.quicklink = :quicklink
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':year', $year, Database::PARAM_INT),
			array(':quicklink', $quicklink, Database::PARAM_STR)
		);
	}
	
	/**
	 * FRONT
	 * Get years with available photoalbums
	 *
	 * @return array
	 */
	public function frontGetPhotoalbumYears(){
		$query = "
			SELECT DATE_FORMAT(pa.date, '%Y') AS year
			FROM photoalbums pa
			WHERE pa.online = 1
			GROUP BY year
			ORDER BY year DESC
		";

		return $this->getAll($query);
	}
}