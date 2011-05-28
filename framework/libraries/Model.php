<?php
/****************************************************
 * Lean mean web machine
 *
 * Base model library
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-18
 *
 ****************************************************/

class Model
{

	protected $table		= null;
	protected $primaryKey	= 'id';

	/**
	 * Get all records by query and optional params
	 *
	 * @param string $query
	 * @param array $param1 optional. Values [$parameter, $value, $data_type]
	 * @param array $param2 optional
	 * @param ...
	 * @return array
	 */
	public function getAll($query, $param1 = null){
	
		// Execute statement
		$parameters = func_get_args();
		$parameters = array_filter($parameters);
		$parametersCount = count($parameters);
		if($parametersCount > 1){
			$statement = Database::instance()->prepare($query);
			for($i = 1; $i < $parametersCount; $i++){
				$statement->bindValue(
					$parameters[$i][0],
					$parameters[$i][1],
					$parameters[$i][2]
				);
			}
			$statement->execute();
		}else{
			$statement = Database::instance()->query($query);
		}
		
		// Return array with results
		$results = array();
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while($result = $statement->fetch()){
			array_push($results, $result);
		}
		return $results;
	}
	
	/**
	 * Get one record by query and optional params
	 *
	 * @param string $query
	 * @param array $param1 optional. Values [$parameter, $value, $data_type]
	 * @param array $param2 optional
	 * @param ...
	 * @return array
	 */
	public function getRecord($query, $param1 = null){
		$parameters = func_get_args();
		$records = call_user_func_array(array($this, 'getAll'), $parameters);
		return array_shift($records);
	}
	
	/**
	 * Get one field by query and optional params
	 *
	 * @param string $query
	 * @param array $param1 optional. Values [$parameter, $value, $data_type]
	 * @param array $param2 optional
	 * @param ...
	 * @return mixed
	 */
	public function getField($query, $param1 = null){
		$parameters = func_get_args();
		$records = call_user_func_array(array($this, 'getAll'), $parameters);
		$record = array_shift($records);
		return $record? array_shift($record) : null;
	}
	
	/**
	 * Get records by query and optional params
	 * Use first key as array key. If there are only two elements for each record, 
	 * return one-dimensional array as result
	 *
	 * @param string $query
	 * @param array $param1 optional. Values [$parameter, $value, $data_type]
	 * @param array $param2 optional
	 * @param ...
	 * @return array
	 */
	public function getAssoc($query, $param1 = null){
		$parameters = func_get_args();
		$recordsRaw = call_user_func_array(array($this, 'getAll'), $parameters);
		$records = array();
		if(count($recordsRaw) > 0){
			$keys = array_keys($recordsRaw[0]);
			$shiftResult = (count($keys) == 2);
			$key = $keys[0];
			foreach($recordsRaw as &$record){
				if($shiftResult){
					$records[$record[$key]] = $record[$keys[1]];
				}else{
					$records[$record[$key]] = $record;
				}
			}
			unset($record);
		}
		return $records;
	}
	
	/**
	 * Get record by field name and value
	 *
	 * @param string $field
	 * @param string $value
	 * @notice Make sure $field is valid
	 * @return array
	 */
	public function getRecordByFieldValue($field, $value){
		$query = "SELECT * FROM `{$this->table}` WHERE `{$field}` = :value";
		return $this->getRecord($query,
			array(':value', $value, Database::PARAM_STR)
		);
	}
	
	/**
	 * Get record by primary key value
	 *
	 * @param int $value
	 * @return array
	 */
	public function get($value){
		$query = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :value";
		return $this->getRecord($query,
			array(':value', $value, Database::PARAM_INT)
		);
	}
	
	/**
	 * Insert or update record
	 *
	 * @param array $data
	 * @return int $recordId
	 */
	public function save($data){
		if(isset($data[$this->primaryKey])){
			return $this->update($data);
		}else{
			return $this->insert($data);
		}
	}
	
	/**
	 * Update existing record
	 *
	 * @param array $data
	 * @return int $recordId
	 */
	public function update($data){
		
		// Gather fields and data to update
		$updateValues = array();
		foreach(array_keys($data) as $field){
			array_push($updateValues, sprintf('`%1$s` = ?', $field));
		}
			
		// Execute update statement
		$query = sprintf(
			'UPDATE `%1$s` SET %2$s  WHERE `%3$s` = ?',
			$this->table,
			implode(',', $updateValues),
			$this->primaryKey
		);
		array_push($data, $data[$this->primaryKey]);
		Database::instance()->prepare($query)->execute(array_values($data));
		
		// Return updated id
		return $data[$this->primaryKey];
	}
	
	/**
	 * Insert new record
	 *
	 * @param array $data
	 * @return int $recordId
	 */
	public function insert($data){
		
		// Execute insert statement
		$query = sprintf(
			'INSERT INTO `%1$s` (`%2$s`) VALUES (%3$s)',
			$this->table,
			implode('`,`', array_keys($data)),
			implode(',', array_fill(0, count($data), '?'))
		);
		Database::instance()->prepare($query)->execute(array_values($data));
		
		// Return new id
		return Database::instance()->lastInsertId();	
	}
	
	/**
	 * Update sequence for given primary keys
	 *
	 * @param array $ids
	 * @param string $field optional
	 * @return void
	 */
	public function updateSequence($ids, $field = 'sequence'){
		$query = "UPDATE `{$this->table}` SET `{$field}` = ? WHERE `{$this->primaryKey}` = ?";
		$statement = Database::instance()->prepare($query);
		for($i = 1; $i <= count($ids); $i++){
			$statement->execute(array($i, $ids[$i-1]));
		}
		unset($statement);
	}
	
	/**
	 * Get highest sequence + 1
	 *
	 * @param string $field optional
	 * @return int
	 */
	public function getNextSequence($field = 'sequence'){
		$query = "SELECT MAX(`{$field}`) FROM {$this->table} LIMIT 1";
		return (intval($this->getField($query)) + 1);
	}
	
	/**
	 * Execute query and optional params. Return affected rows.
	 *
	 * @param string $query
	 * @param array $param1 optional. Values [$parameter, $value, $data_type]
	 * @param array $param2 optional
	 * @param ...
	 * @return int
	 */
	public function execute($query, $param1 = null){
	
		// Execute statement
		$parameters = func_get_args();
		$parametersCount = count($parameters);
		if($parametersCount > 1){
			$statement = Database::instance()->prepare($query);
			for($i = 1; $i < $parametersCount; $i++){
				$statement->bindValue(
					$parameters[$i][0],
					$parameters[$i][1],
					$parameters[$i][2]
				);
			}
			$statement->execute();
			return $statement->rowCount();
		}else{
			return Database::instance()->exec($query);
		}
	}	
	
	/**
	 * Delete record
	 *
	 * @param int $primaryKeyValue
	 * @return void
	 */
	public function delete($primaryKeyValue){
		$this->execute(
			sprintf('DELETE FROM `%1$s` WHERE `%2$s` = :value', $this->table, $this->primaryKey),
			array(':value', $primaryKeyValue, Database::PARAM_STR)	
		);		
	}
	
	/**
	 * Delete records by field name and value
	 *
	 * @param string $field
	 * @param string $value
	 * @notice Make sure $field is valid
	 * @return void
	 */
	public function deleteByFieldValue($field, $value){
		$records = $this->getAll(
			sprintf('SELECT `%1$s` FROM `%2$s` WHERE `%3$s` = :value', $this->primaryKey, $this->table, $field),
			array(':value', $value, Database::PARAM_STR)
		);
		foreach($records as $record){
			$this->delete($record[$this->primaryKey]);
		}
	}
}
