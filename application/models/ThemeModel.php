<?php
class ThemeModel extends Model {
	protected $table = 'themes';
	
	/**
	 * Get the id for a theme, if it does not exist and s, create it
	 * 
	 * @param string $name
	 * @param int $sourceId
	 * @return int
	 */
	public function frontGetIdFor( $name, $sourceId=null ){
		$query = '
			SELECT id FROM themes WHERE title=:title
		';
		
		$result = $this->getAll($query,
			array(':title', $name, Database::PARAM_STR)
		);
		
		vardump( $result );
		if( count( $result ) > 0 )
			return $result[0]['id'];
		
		if( !empty( $sourceId ) )
			return $this->frontAddTheme( $name, $sourceId );
			
		return null;
	}
	
	/**
	 * Create the theme and return it's id.
	 * 
	 * @param string $name
	 * @param int $sourceId
	 * @return int
	 */
	public function frontAddTheme( $name, $sourceId ){
		return $this->insert(array(
			'source_id' => $sourceId,
			'title' => $name,
			'quicklink' => ''
		));
	}
	
	/**
	 * Get all active themes for dropdown list
	 *
	 * @return array
	 */
	public function frontGetActiveForDropdown(){
		$query = "
			SELECT id, title, quicklink 
			FROM themes
			WHERE online = 1
			AND deleted = 0
			ORDER BY title
		";
		return $this->getAll($query);
	}
}
