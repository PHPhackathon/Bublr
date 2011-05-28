<?php 
	class ThemeModel extends Model {
		
		protected $table = 'themes_sources';
		
		public function frontAddThemeSource( $name, $url, $type='coolblue' ) {
			$this->insert( array(
				'title' => $name,
				'quicklink' => '',
				'url' => $url,
				'type' => $type
			));
		}
		
	}