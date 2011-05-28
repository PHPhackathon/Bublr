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
			SELECT 
				t.id, t.title, t.quicklink,
				COUNT(b.id) AS bubl_count
			
			FROM themes t
			
			INNER JOIN bubls b
			ON t.id = b.theme_id
			
			WHERE t.online = 1
			AND t.deleted = 0
			AND b.online = 1
			AND b.deleted = 0
			
			GROUP BY t.id
			
			HAVING bubl_count > 0
			
			ORDER BY t.title
		";
		return $this->getAll($query);
	}
}
