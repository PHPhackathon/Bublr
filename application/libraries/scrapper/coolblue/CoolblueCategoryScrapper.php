<?php
	require_once( 'scrapper/Scrapper.php' );
	require_once( 'scrapper/ScrapeResult.php' );

	class CoolblueCategoryScrapper implements Scrapper {
		
		protected static $_patterns = array(
			'title' => '#<h2><span class="fn">(.+)</span>#Uims',
			'image' => '#src="(http://img.cbcdn.net/products/\d+/.+)"#Uims',
			'rating' => '#class="stars(\d)"#Uims',
			'price' => '#class="price">.*(\d+,\d*)-#Uims',
			'description' => '#<div class="description">(.*)</div>#Uims',
			'summary' => '#<h4 class="summary\s?">(.+)</h4>#Uims'
		);
		
		private $_url;
		
		/**
		 * 
		 * Constructs a new CoolblueCategoryScrapper.
		 * @param String $url
		 */
		public function __construct( $url ){
			$this->open( $url );
		}
		
		/**
		 * Open the url to scrape.
		 * @see Scrapper::open()
		 */
		public function open( $url ){
			$this->_url = $url;
		}
		
		/**
		 * Scrape a category page.
		 * @see Scrapper::scrape()
		 */
		public function scrape(){
			
			$page = file_get_contents( $this->_url );
			
			// Find the amount of items in this category.
			$pattern = '#<option value="(\d+)">Alle</option>#U';
			
			preg_match( $pattern, $page, $match );
			
			// The amount of products is gonna sit on position 1
			$page = file_get_contents( $this->_url . '?items=' . $match[1] );
			
			$pattern = '#<a class="name fn url" href="(.+)"#U';
			
			preg_match_all( $pattern, $page, $matches );
			$matches = $matches[1];
			
			$results = array();
			// TODO: remove
			$i = 0;
			foreach( $matches as $value ){
				$results[] = $this->_parse_product( $value );
				if( ++$i == 4 )
					break;
			}
			
			return $results;
		}
		
		/**
		 * 
		 * Load page of the product and scrape it for title, image and rating.
		 * @param String $product_url
		 */
		private function _parse_product( $product_url ){
			// Get host adres
			$url = parse_url( $this->_url );
			$url = $url['scheme'] . '://' . $url['host'] . ( isset( $url['port'] ) ? ':' . $url['port'] : '' );
			
			
			echo "Scraping " . $url . $product_url . "\n";
			$page = file_get_contents( $url . $product_url );

			
			
			preg_match( self::$_patterns['title'], $page, $title );
			$title = isset( $title[1] ) ? $title[1] : '';
			
			preg_match( self::$_patterns['image'], $page, $image );
			$image = isset( $image[1] ) ? $image[1] : '';
			
			preg_match( self::$_patterns['rating'], $page, $rating );
			$rating = isset( $rating[1] ) ? $rating[1] : 0;
			
			preg_match( self::$_patterns['price'], $page, $price );
			$price = isset( $price[1] ) ? $price[1] : 0;
			
			preg_match( self::$_patterns['summary'], $page, $summary );
			$summary = isset( $summary[1] ) ? trim( $summary[1] ) : 'no summary';
			
			preg_match( self::$_patterns['description'], $page, $description );
			$description = isset( $description[1] ) ? trim( $description[1] ) : 0;

			echo $title . "\t::\t" . $image . "\t::\t" . $rating . "\t::\t" . $price . "\t::\t" . $summary . "\t::\t" . $description . "\n\n";
			
			return new ScrapeResult( $title, $url . $product_url, $rating, $price, $summary, $description, array( $image ) );
		}
		
	}
