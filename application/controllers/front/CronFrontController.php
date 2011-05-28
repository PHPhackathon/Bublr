<?php

class CronFrontController extends FrontController {

	/**
	 * Constructor
	 */
	public function __construct(){
	
		// Validate authentication key
		if(Input::get('key') != ApplicationConfig::$cronjobKey){
			die('-- wrong or missing key --');
		}
		
		parent::__construct();
	}
	
	/**
	 * Perform an update of the oldest theme/categories.
	 */
	public function updateThemes(){
		
		$source = model('ThemeSourceModel')->frontGetOldestThemeSource();
		
		// Request might take some time.
		set_time_limit( 0 );
		
		//$coolblue_scraper = library( 'scrapper/coolblue/CoolblueScrapper');
		$coolblue_scraper = library( 'CoolblueScrapperLib' );
		$coolblue_scraper->open( $source['url'] );
		
		$results = $coolblue_scraper->scrape();
		
		foreach( $results as $key => $result ){
			
			$themeId = model('ThemeModel')->frontGetIdFor( $key, $source['id'] );
			
			$products = $result->getResults();
			
			foreach( $products as $p ){
				$images = $p->getImages();
				model('BublModel')->frontAddBubl(
					$p->getName(),
					$images[0],
					$p->getUrl(),
					$p->getRating(),
					$p->getPrice(),
					$p->getSummary(),
					$p->getDescription(),
					$source['id'],
					$themeId
				);
			}
		}
		
		model('ThemeSourceModel')->frontMarkUpdated( $source['id'] );
	}
	
	/**
	 * Update oldest tweets.
	 */
	public function updateTweets(){
		
		$ids = model('BublModel')->frontGetOudatedBublIds();
		
		library('TwitterBubls')->processBubls( $ids );
	}
}
















