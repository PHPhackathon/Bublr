<?php

class ArticleCategoryModel extends Model {

	protected $table = 'articles_categories';

	/**
	 * ADMIN
	 * Get categories for ArticleFormWindow combobox
	 *
	 * @return array
	 */
	public function adminGetCombobox(){
		$query = "
			SELECT ac.id, ac.title
			FROM articles_categories ac
			ORDER BY sequence
		";

		return $this->getAll($query);
	}

	/**
	 * ADMIN
	 * Get all categories for ArticlesCategoriesGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @return array
	 */
	public function adminGetArticlesCategoriesGrid($start = 0, $limit = 9999, $order = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT ac.id, ac.title, ac.online
			FROM articles_categories ac
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
	 * Count all categories for ArticlesCategoriesGrid
	 *
	 * @return int
	 */
	public function adminCountArticlesCategoriesGrid(){
		$query = "SELECT COUNT(id) FROM articles_categories";
		return $this->getField($query);
	}

	/**
	 * ADMIN
	 * Get category for ArticlesCategoriesGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetArticleCategoryForGrid($id){
		$query = "
			SELECT ac.id, ac.title, ac.online
			FROM articles_categories ac
			WHERE ac.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get category for ArticlesCategoriesFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetArticleCategoryForForm($id){
		$query = "
			SELECT ac.id, ac.title, ac.description, ac.online
			FROM articles_categories ac
			WHERE ac.id = :id
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
		model('ArticleModel')->deleteByFieldValue('category_id', $id);
		return parent::delete($id);
	}

}