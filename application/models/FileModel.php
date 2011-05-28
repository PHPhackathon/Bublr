<?php

class FileModel extends Model {

	protected $table = 'files';

	/**
	 * Get all records that match related table and id
	 *
	 * @param string $relatedTable
	 * @param int $relatedId
	 * @return array
	 */
	public function getAllByRelatedTableId($relatedTable, $relatedId){
		$query = "
			SELECT f.id, f.filename, f.mimetype, f.extension, f.size, f.title
			FROM files f
			WHERE f.related_table = :related_table
			AND f.related_id = :related_id
			ORDER BY f.sequence
		";

		return $this->getAll($query,
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
			SELECT f.id, f.filename, f.mimetype, f.extension, f.size, f.title
			FROM files f
			WHERE f.related_table = :related_table
			AND f.related_id = :related_id
			ORDER BY f.sequence
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':related_id', $relatedId, Database::PARAM_INT)
		);
	}
	
	/**
	 * Get first record that matches related table and filename
	 *
	 * @param string $relatedTable
	 * @param string $filename
	 * @return array
	 */
	public function getRecordByRelatedTableFilename($relatedTable, $filename){
		$query = "
			SELECT f.id, f.filename, f.mimetype, f.extension, f.size, f.title
			FROM files f
			WHERE f.related_table = :related_table
			AND f.filename = :filename
			ORDER BY f.sequence
			LIMIT 1
		";

		return $this->getRecord($query,
			array(':related_table', $relatedTable, Database::PARAM_STR),
			array(':filename', $filename, Database::PARAM_STR)
		);
	}

	/**
	 * Delete file from database and filesystem
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id){

		// Delete from database
		$file = $this->get($id);
		if(!$file) return;
		parent::delete($file['id']);

		// Delete from filesystem
		FileUpload::deleteUpload($file['related_table'], $file['filename']);

		// Update sequence
		$this->execute('UPDATE files SET sequence = sequence - 1 WHERE related_table = :related_table AND related_id = :related_id AND sequence > :sequence',
			array(':related_table', $file['related_table'], Database::PARAM_STR),
			array(':related_id', $file['related_id'], Database::PARAM_INT),
			array(':sequence', $file['sequence'], Database::PARAM_INT)
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
		$files = $this->getAllByRelatedTableId($relatedTable, $relatedId);
		foreach($files as &$file){
			$this->delete($file['id']);
		}
		unset($file);
	}

}