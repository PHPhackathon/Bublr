<?php

class MemberModel extends Model {

	protected $table = 'members';

	/**
	 * ADMIN
	 * Get all members for MembersGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @return array
	 */
	public function adminGetMembersGrid($start = 0, $limit = 9999, $order = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT
				m.id, m.firstname, m.lastname, m.phone, m.email, m.about, m.payed, m.online, m.sequence,
				mc.id category_id, mc.title category_title, mc.online category_online, mc.sequence category_sequence

			FROM members m

			INNER JOIN members_categories mc
			ON m.category_id = mc.id

			ORDER BY category_sequence, {$order} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':start', abs($start), Database::PARAM_INT),
			array(':limit', abs($limit), Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Count all members for MembersGrid
	 *
	 * @return int
	 */
	public function adminCountMembersGrid(){
		$query = "SELECT COUNT(id) FROM members";
		return $this->getField($query);
	}

	/**
	 * ADMIN
	 * Get member for MembersGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetMemberForGrid($id){
		$query = "
			SELECT
				m.id, m.firstname, m.lastname, m.phone, m.email, m.about, m.payed, m.online, m.sequence,
				mc.id category_id, mc.title category_title, mc.online category_online, mc.sequence category_sequence

			FROM members m

			INNER JOIN members_categories mc
			ON m.category_id = mc.id

			WHERE m.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get member for MembersFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetMemberForForm($id){
		$query = "
			SELECT
				m.id, m.category_id, m.firstname, m.lastname, m.gender, m.street, m.postal_code, m.city, m.birthdate, m.phone, m.email, m.about, m.payed, m.online,
				img.id image_id, img.filename image_filename

			FROM members m

			LEFT JOIN images img
			ON m.id = img.related_id
			AND img.related_table = 'members'
			AND img.sequence = 1

			WHERE m.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}
	
	/**
	 * ADMIN
	 * Get all members for CSV export
	 *
	 * @param int $categoryId
	 * @return array
	 */
	public function adminGetMembersForExport($categoryId){
		$query = "
			SELECT m.id, m.firstname, m.lastname, m.quicklink, m.gender, m.street, m.postal_code, m.city, m.birthdate, m.phone, m.email, m.about, m.payed, m.online
			FROM members m
			WHERE m.category_id = :category_id
			ORDER BY m.firstname, m.lastname
		";

		return $this->getAll($query,
			array(':category_id', $categoryId, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Delete member and all related data
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){
		model('ImageModel')->deleteAllByRelatedTableId('members', $id);
		return parent::delete($id);
	}

	/**
	 * FRONT
	 * Get members for overview by category id
	 *
	 * @param int $categoryId
	 * @param int $start optional.
	 * @param int $limit optional
	 * @param string $sort optional. Assuming field is valid.
	 * @param string $direction optional. Assuming value is valid.
	 * @return array
	 */
	public function frontGetOverviewByCategoryId($categoryId, $start = 0, $limit = 9999, $sort = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT
				m.id, m.firstname, m.lastname, m.street, m.postal_code, m.city, m.phone, m.email, m.about, m.sequence,
				img.id image_id, img.filename image_filename

			FROM members m

			LEFT JOIN images img
			ON m.id = img.related_id
			AND img.related_table = 'members'
			AND img.sequence = 1

			WHERE m.online = 1
			AND m.category_id = :category_id

			ORDER BY {$sort} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':category_id', $categoryId, Database::PARAM_INT),
			array(':start', $start, Database::PARAM_INT),
			array(':limit', $limit, Database::PARAM_INT)
		);
	}
	
	/**
	 * FRONT
	 * Get random member
	 *
	 * @return array
	 */
	public function frontGetRandom(){
		$query = "
			SELECT
				m.id, m.firstname, m.lastname, m.street, m.postal_code, m.city, m.phone, m.email, m.about, m.sequence,
				img.id image_id, img.filename image_filename

			FROM members m
			
			INNER JOIN members_categories mc
			ON m.category_id = mc.id

			LEFT JOIN images img
			ON m.id = img.related_id
			AND img.related_table = 'members'
			AND img.sequence = 1

			WHERE m.online = 1
			AND mc.sidebar = 1

			ORDER BY RAND()
			LIMIT 1
		";

		return $this->getRecord($query);
	}
}