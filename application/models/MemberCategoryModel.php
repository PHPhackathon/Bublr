<?php

class MemberCategoryModel extends Model {

	protected $table = 'members_categories';

	/**
	 * ADMIN
	 * Get categories for MemberFormWindow combobox
	 *
	 * @return array
	 */
	public function adminGetCombobox(){
		$query = "
			SELECT mc.id, mc.title
			FROM members_categories mc
			ORDER BY sequence
		";

		return $this->getAll($query);
	}

	/**
	 * ADMIN
	 * Get all categories for MembersCategoriesGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @return array
	 */
	public function adminGetMembersCategoriesGrid($start = 0, $limit = 9999, $order = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT mc.id, mc.title, mc.online
			FROM members_categories mc
			ORDER BY {$order} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':start', abs($start), Database::PARAM_INT),
			array(':limit', abs($limit), Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Count all categories for MembersCategoriesGrid
	 *
	 * @return int
	 */
	public function adminCountMembersCategoriesGrid(){
		$query = "SELECT COUNT(id) FROM members_categories";
		return $this->getField($query);
	}

	/**
	 * ADMIN
	 * Get category for MembersCategoriesGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetMemberCategoryForGrid($id){
		$query = "
			SELECT mc.id, mc.title, mc.online
			FROM members_categories mc
			WHERE mc.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get category for MembersCategoriesFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetMemberCategoryForForm($id){
		$query = "
			SELECT mc.id, mc.title, mc.online, mc.sidebar
			FROM members_categories mc
			WHERE mc.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}
	
	/**
	 * ADMIN
	 * Get categories for CSV export
	 *
	 * @return array
	 */
	public function adminGetCategoriesForExport(){
		$query = "
			SELECT mc.id, mc.title, mc.quicklink
			FROM members_categories mc
			ORDER BY mc.sequence
		";
		
		return $this->getAll($query);
	}

	/**
	 * ADMIN
	 * Delete category and all related data
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){
		model('MemberModel')->deleteByFieldValue('category_id', $id);
		return parent::delete($id);
	}

	/**
	 * FRONT
	 * Get categories for overview
	 *
	 * @param int $start optional.
	 * @param int $limit optional
	 * @param string $sort optional. Assuming field is valid.
	 * @param string $direction optional. Assuming value is valid.
	 * @return array
	 */
	public function frontGetOverview($start = 0, $limit = 9999, $sort = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT mc.id, mc.title
			FROM members_categories mc
			WHERE mc.online = 1
			ORDER BY {$sort} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':start', $start, Database::PARAM_INT),
			array(':limit', $limit, Database::PARAM_INT)
		);
	}
}