<?php
	interface Scrapper {
		
		/**
		 * 
		 * Open scraper on specific url.
		 * @param String $url
		 */
		function open( $url );
		
		/**
		 * 
		 * Start scraping the url.
		 */
		function scrape();
		
	}