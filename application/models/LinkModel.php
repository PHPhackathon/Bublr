<?php

class LinkModel extends Model {

	protected $table = 'links';

	/**
	 * ADMIN
	 * Get all links for LinksGrid
	 *
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @return array
	 */
	public function adminGetLinksGrid($start = 0, $limit = 9999, $order = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT
				l.id, l.title, l.url, l.online, l.sequence,
				lc.id category_id, lc.title category_title, lc.sequence category_sequence

			FROM links l

			INNER JOIN links_categories lc
			ON l.category_id = lc.id

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
	 * Count all links for LinksGrid
	 *
	 * @return int
	 */
	public function adminCountLinksGrid(){
		$query = "SELECT COUNT(id) FROM links";
		return $this->getField($query);
	}

	/**
	 * ADMIN
	 * Get link for LinksGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetLinkForGrid($id){
		$query = "
			SELECT
				l.id, l.title, l.url, l.online, l.sequence,
				lc.id category_id, lc.title category_title, lc.sequence category_sequence

			FROM links l

			INNER JOIN links_categories lc
			ON l.category_id = lc.id

			WHERE l.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get link for LinksFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetLinkForForm($id){
		$query = "
			SELECT
				l.id, l.category_id, l.title, l.url, l.online,
				img.id image_id, img.filename image_filename

			FROM links l

			LEFT JOIN images img
			ON l.id = img.related_id
			AND img.related_table = 'links'
			AND img.sequence = 1

			WHERE l.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Delete link and all related data
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){
		model('ImageModel')->deleteAllByRelatedTableId('links', $id);
		return parent::delete($id);
	}

	/**
	 * FRONT
	 * Get links for overview by category id
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
				l.title, l.url, l.sequence,
				img.id image_id, img.filename image_filename

			FROM links l

			LEFT JOIN images img
			ON l.id = img.related_id
			AND img.related_table = 'links'
			AND img.sequence = 1

			WHERE l.online = 1
			AND l.category_id = :category_id

			ORDER BY {$sort} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':category_id', $categoryId, Database::PARAM_INT),
			array(':start', $start, Database::PARAM_INT),
			array(':limit', $limit, Database::PARAM_INT)
		);
	}
}