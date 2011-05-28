<?php 
	class ThemeSourceModel extends Model {
		
		protected $table = 'themes_sources';
		protected $primaryKey = 'id';
		
		/**
		 * Add a theme source to the database.
		 * 
		 * @param string $name
		 * @param string $url
		 * @param string $type
		 */
		public function frontAddThemeSource( $name, $url, $type='coolblue' ) {
			$this->insert( array(
				'title' => $name,
				'quicklink' => '',
				'url' => $url,
				'type' => $type
			));
		}
		
		/**
		 * Return the theme source that is the oldest.
		 * 
		 * @return array
		 */
		public function frontGetOldestThemeSource(){
			
			$query = '
				SELECT
					id, title, quicklink, url, type, online, deleted, last_import
				FROM
					themes_sources
				WHERE
					last_import IN ( SELECT MIN( last_import ) FROM themes_sources )
					OR last_import IS NULL
				ORDER BY last_import
				LIMIT 1
			';
			
			return $this->getRecord($query);
		}
		
		public function frontMarkUpdated( $source_id ){
			$this->update(array(
				'id' => $source_id,
				'last_import' => date( 'Y-m-d H:i' )
			));
		}
		
	}