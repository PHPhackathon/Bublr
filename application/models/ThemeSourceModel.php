<?php 
	class ThemeSourceModel extends Model {
		
		protected $table = 'themes_sources';
		
		/**
		 * Add a theme source to the database.
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
		 */
		public function frontGetOldestThemeSource(){
			
			$query = '
				SELECT
					id, title, quicklink, url, type, online, deleted, last_import
				WHERE
					last_import = MIN( last_import )
					OR last_import IS NULL
				LIMIT 1
			';
			
			return $this->getRecord($query);
		}
		
	}