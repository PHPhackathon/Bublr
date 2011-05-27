<?php

class CalendarModel extends Model {

	protected $table = 'calendars';

	/**
	 * ADMIN
	 * Get all calendars for CalendarsGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @param string $search optional
	 * @return array
	 */
	public function adminGetCalendarsGrid($start = 0, $limit = 9999, $order = 'month', $direction = 'DESC', $search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE c.month LIKE :search
				OR c.description LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT
				c.id, c.month, c.online, c.created, c.updated,
				f.filename file_filename

			FROM calendars c

			LEFT JOIN files f
			ON c.id = f.related_id
			AND f.related_table = 'calendars'
			AND f.sequence = 1

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
	 * Count all calendars for CalendarsGrid
	 *
	 * @param string $search optional
	 * @return int
	 */
	public function adminCountCalendarsGrid($search = null){

		// Generate search filter
		if($search){
			$whereSearch = "
				WHERE c.month LIKE :search
				OR c.description LIKE :search
			";
		}else{
			$whereSearch = '';
		}

		// Generate query
		$query = "
			SELECT COUNT(c.id)
			FROM calendars c
			{$whereSearch}
		";

		return $this->getField($query,
			$search? array(':search', '%'.$search.'%', Database::PARAM_STR) : null
		);
	}

	/**
	 * ADMIN
	 * Get calendar for CalendarsGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetCalendarForGrid($id){
		$query = "
			SELECT
				c.id, c.month, c.online, c.created, c.updated,
				f.filename file_filename

			FROM calendars c

			LEFT JOIN files f
			ON c.id = f.related_id
			AND f.related_table = 'calendars'
			AND f.sequence = 1

			WHERE c.id = :id

			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get calendar for CalendarsFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetCalendarForForm($id){
		$query = "
			SELECT c.id, c.month, c.description, c.online, c.created, c.updated
			FROM calendars c
			WHERE c.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}
	
	/**
	 * ADMIN
	 * Delete calendar and all related data
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){
		model('FileModel')->deleteAllByRelatedTableId('calendars', $id);
		return parent::delete($id);
	}
	
	/**
	 * FRONT
	 * Get latest calendar
	 *
	 * @return array
	 */
	public function frontGetLatestCalendar(){
		$query = "
			SELECT
				c.id, c.month, c.description,
				f.filename file_filename

			FROM calendars c

			LEFT JOIN files f
			ON c.id = f.related_id
			AND f.related_table = 'calendars'
			AND f.sequence = 1

			WHERE c.online = 1
			ORDER BY c.month DESC
			LIMIT 1
		";

		return $this->getRecord($query);
	}
	
	/**
	 * FRONT
	 * Get calendars by year
	 *
	 * @param int $year
	 * @return array
	 */
	public function frontGetCalendarsByYear($year){
		$query = "
			SELECT
				c.id, c.month, c.description,
				f.filename file_filename

			FROM calendars c

			LEFT JOIN files f
			ON c.id = f.related_id
			AND f.related_table = 'calendars'
			AND f.sequence = 1

			WHERE c.online = 1
			AND DATE_FORMAT(c.month, '%Y') = :year
			ORDER BY c.month DESC
		";

		return $this->getAll($query,
			array(':year', $year, Database::PARAM_INT)
		);
	}
	
	/**
	 * FRONT
	 * Get years with available calendars
	 *
	 * @return array
	 */
	public function frontGetCalendarYears(){
		$query = "
			SELECT DATE_FORMAT(c.month, '%Y') AS year
			FROM calendars c
			WHERE c.online = 1
			GROUP BY year
			ORDER BY year DESC
		";

		return $this->getAll($query);
	}
}