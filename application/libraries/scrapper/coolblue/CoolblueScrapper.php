<?php
	require_once( APPLICATION_PATH.'libraries/scrapper/Scrapper.php' );
	require_once( APPLICATION_PATH.'libraries/scrapper/ScrapeResult.php' );
	require_once( APPLICATION_PATH.'libraries/scrapper/ScrapeTheme.php' );
	require_once( 'CoolblueCategoryScrapper.php' );
	
	define( 'COOLBLUE_CATEGORY', '/category/' );
	
	class CoolblueScrapper implements Scrapper {
		
		private $_url;
		
		public function __construct( $url=null ){
			$this->open( $url );
		}
		
		/**
		 * 
		 * Open scraper on specific url.
		 * @param String $url
		 */
		public function open( $url ){
			$this->_url = $url;
		}
		
		/**
		 * 
		 * Start scraping the url.
		 */
		public function scrape(){
			
			//ini_set( 'display_errors', false );
			$page = file_get_contents( $this->_url );
			
			$pattern = '#<option value="mainsectionid:(\d+)">([a-zA-Z\d\s]+)</option>#U';
			
			$matches = array();
			preg_match_all( $pattern, $page, $matches );
			
			// Take out category ids
			$ids = $matches[1];
			$names = $matches[2];
			
			$results = array();
			for( $i=0; $i<count( $ids ); $i++ ){
				$category_id = $ids[ $i ];
				$category_url = $this->_url . COOLBLUE_CATEGORY . $category_id;
				$category_name = trim( $names[ $i ] );
				
				$scraper = new CoolblueCategoryScrapper( $category_url );

				$results[ $category_name ] = new ScrapeTheme( $category_id, $category_name, $category_url, $scraper->scrape() );
			}
			
			return $results;
		}
		
	}
