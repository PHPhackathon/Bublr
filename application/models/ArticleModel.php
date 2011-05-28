<?php

class ArticleModel extends Model {

	protected $table = 'articles';

	/**
	 * ADMIN
	 * Get all articles for ArticlesGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @return array
	 */
	public function adminGetArticlesGrid($start = 0, $limit = 9999, $order = 'title', $direction = 'ASC'){
		$query = "
			SELECT
				a.id, a.title, a.quicklink, a.description, a.online, a.sequence, a.created, a.updated, a.sequence,
				ac.id category_id, ac.title category_title, ac.sequence category_sequence

			FROM articles a

			INNER JOIN articles_categories ac
			ON a.category_id = ac.id

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
	 * Count all articles for ArticlesGrid
	 *
	 * @return int
	 */
	public function adminCountArticlesGrid(){
		$query = "SELECT COUNT(id) FROM articles";
		return $this->getField($query);
	}

	/**
	 * ADMIN
	 * Get article for ArticlesGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetArticleForGrid($id){
		$query = "
			SELECT
				a.id, a.title, a.quicklink, a.description, a.online, a.sequence, a.created, a.updated, a.sequence,
				ac.id category_id, ac.title category_title, ac.sequence category_sequence

			FROM articles a

			INNER JOIN articles_categories ac
			ON a.category_id = ac.id

			WHERE a.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get article for ArticlesFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetArticleForForm($id){
		$query = "
			SELECT a.id, a.category_id, a.title, a.description, a.content, a.online
			FROM articles a
			WHERE a.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Delete article and all related data
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){
		model('ImageModel')->deleteAllByRelatedTableId('articles', $id);
		return parent::delete($id);
	}

	/**
	 * FRONT
	 * Get all articles by category_id
	 *
	 * @param int $categoryId
	 * @return array
	 */
	public function frontGetChildren($categoryId){
		$query = "
			SELECT
				a.id, a.title, a.quicklink, a.description, a.content,
				img.alt image_alt, img.filename image_filename

			FROM articles a

			LEFT JOIN images img
			ON img.related_table = 'articles'
			AND img.related_id = a.id
			AND img.sequence = 1

			WHERE a.online = 1
			AND a.category_id = :category_id

			ORDER BY a.sequence
		";

		return $this->getAll($query,
			array(':category_id', $categoryId, Database::PARAM_INT)
		);
	}
	
	/**
	 * FRONT
	 * Get article by category_id and quicklink
	 *
	 * @param int $categoryId
	 * @param string $quicklink
	 * @return array
	 */
	public function frontGetByCategoryIdAndQuicklink($categoryId, $quicklink){
		$query = "
			SELECT a.id, a.title, a.quicklink, a.description, a.content
			FROM articles a
			WHERE a.online = 1
			AND a.category_id = :category_id
			AND a.quicklink = :quicklink
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':category_id', $categoryId, Database::PARAM_INT),
			array(':quicklink', $quicklink, Database::PARAM_STR)
		);
	}
}