<?php

class LinkCategoryModel extends Model {

	protected $table = 'links_categories';

	/**
	 * ADMIN
	 * Get categories for LinkFormWindow combobox
	 *
	 * @return array
	 */
	public function adminGetCombobox(){
		$query = "
			SELECT lc.id, lc.title
			FROM links_categories lc
			ORDER BY sequence
		";

		return $this->getAll($query);
	}

	/**
	 * ADMIN
	 * Get all categories for LinksCategoriesGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @return array
	 */
	public function adminGetLinksCategoriesGrid($start = 0, $limit = 9999, $order = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT lc.id, lc.title, lc.online
			FROM links_categories lc
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
	 * Count all categories for LinksCategoriesGrid
	 *
	 * @return int
	 */
	public function adminCountLinksCategoriesGrid(){
		$query = "SELECT COUNT(id) FROM links_categories";
		return $this->getField($query);
	}

	/**
	 * ADMIN
	 * Get category for LinksCategoriesGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetLinkCategoryForGrid($id){
		$query = "
			SELECT lc.id, lc.title, lc.online
			FROM links_categories lc
			WHERE lc.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get category for LinksCategoriesFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetLinkCategoryForForm($id){
		$query = "
			SELECT lc.id, lc.title, lc.online
			FROM links_categories lc
			WHERE lc.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Delete category and all related data
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){
		model('LinkModel')->deleteByFieldValue('category_id', $id);
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
			SELECT lc.id, lc.title
			FROM links_categories lc
			WHERE lc.online = 1
			ORDER BY {$sort} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':start', $start, Database::PARAM_INT),
			array(':limit', $limit, Database::PARAM_INT)
		);
	}
}