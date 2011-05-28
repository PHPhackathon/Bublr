<?php

class ImageModel extends Model {

	protected $table = 'images';

	/**
	 * ADMIN
	 * Get all images for ImagesGrid
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @param int $start optional
	 * @param int $limit optional
	 * @param string $order optional
	 * @param string $direction optional
	 * @return array
	 */
	public function adminGetImagesGrid($relatedTable, $relatedId, $start = 0, $limit = 9999, $order = 'sequence', $direction = 'ASC'){
		$query = "
			SELECT img.id, img.filename, img.related_table, img.related_id, img.alt
			FROM images img
			WHERE img.related_table = :related_table
			AND img.related_id = :related_id
			ORDER BY {$order} {$direction}
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':related_id', $relatedId, Database::PARAM_INT),
			array(':start', abs($start), Database::PARAM_INT),
			array(':limit', abs($limit), Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Count all images for ImagesGrid
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @return int
	 */
	public function adminCountImagesGrid($relatedTable, $relatedId){
		$query = "
			SELECT COUNT(img.id)
			FROM images img
			WHERE img.related_table = :related_table
			AND img.related_id = :related_id
		";
		return $this->getField($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':related_id', $relatedId, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get image for ImagesGrid
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetImageForGrid($id){
		$query = "
			SELECT img.id, img.filename, img.related_table, img.related_id, img.alt
			FROM images img
			WHERE img.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * ADMIN
	 * Get image for ImagesFormWindow
	 *
	 * @param int $id
	 * @return array
	 */
	public function adminGetImageForForm($id){
		$query = "
			SELECT img.id, img.alt
			FROM images img
			WHERE img.id = :id
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':id', $id, Database::PARAM_INT)
		);
	}

	/**
	 * Get all records that match related table and id
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @param int $start optional
	 * @param int $limit optional
	 * @return array
	 */
	public function getAllByRelatedTableId($relatedTable, $relatedId, $start = 0, $limit = 9999){
		$query = "
			SELECT img.id, img.filename, img.alt
			FROM images img
			WHERE img.related_table = :related_table
			AND img.related_id = :related_id
			ORDER BY sequence
			LIMIT :start, :limit
		";

		return $this->getAll($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':related_id', $relatedId, Database::PARAM_INT),
			array(':start', abs($start), Database::PARAM_INT),
			array(':limit', abs($limit), Database::PARAM_INT)
		);
	}
	
	/**
	 * Get count for records that match related table and id
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @return int
	 */
	public function getCountByRelatedTableId($relatedTable, $relatedId, $start = 0, $limit = 9999){
		$query = "
			SELECT COUNT(img.id)
			FROM images img
			WHERE img.related_table = :related_table
			AND img.related_id = :related_id
		";

		return $this->getField($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':related_id', $relatedId, Database::PARAM_INT)
		);
	}

	/**
	 * Get first record that matches related table and id
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @return array
	 */
	public function getRecordByRelatedTableId($relatedTable, $relatedId){
		$query = "
			SELECT img.id, img.filename, img.alt
			FROM images img
			WHERE img.related_table = :related_table
			AND img.related_id = :related_id
			ORDER BY img.sequence
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':related_id', $relatedId, Database::PARAM_INT)
		);
	}

	/**
	 * Get highest sequence + 1 by related table and id
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @param string $field optional
	 * @return int
	 */
	public function getNextSequenceByRelatedTableId($relatedTable, $relatedId, $field = 'sequence'){
		$query = "
			SELECT MAX(img.`{$field}`)
			FROM images img
			WHERE img.related_table = :related_table
			AND img.related_id = :related_id
			LIMIT 1
		";
		$sequence = $this->getField($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':related_id', $relatedId, Database::PARAM_INT)
		);
		return (intval($sequence) + 1);
	}

	/**
	 * Delete image from database and filesystem
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){

		// Delete from database
		$image = $this->get($id);
		if(!$image) return;
		parent::delete($image['id']);

		// Delete from filesystem
		Image::deleteUpload($image['related_table'], $image['filename']);

		// Update sequence
		$this->execute('UPDATE images SET sequence = sequence - 1 WHERE related_table = :related_table AND related_id = :related_id AND sequence > :sequence',
			array(':related_table', $image['related_table'], Database::PARAM_STR),
			array(':related_id', $image['related_id'], Database::PARAM_INT),
			array(':sequence', $image['sequence'], Database::PARAM_INT)
		);
	}

	/**
	 * Delete all records that match related table and id
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @return void
	 */
	public function deleteAllByRelatedTableId($relatedTable, $relatedId){
		$images = $this->getAllByRelatedTableId($relatedTable, $relatedId);
		foreach($images as &$image){
			$this->delete($image['id']);
		}
		unset($image);
	}

}