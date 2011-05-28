<?php
	class ScrapeResult {
		
		protected $_name;
		protected $_images;
		protected $_url;
		protected $_rating;
		protected $_price;
		protected $_summary;
		protected $_description;
		
		/**
		 * 
		 * Creates a new instance of ScrapperResult
		 * @param String $name
		 * @param String $url
		 * @param array $images
		 */
		public function __construct( $name, $url, $rating, $price, $summary, $description, $images=array() ){
			$this->_name = $name;
			$this->_images = $images;
			$this->_url = $url;
			$this->_price = $price;
			$this->_rating = $rating;
			$this->_summary = $summary;
			$this->_description = $description;
		}
		
		/**
		 * 
		 * Return the name of the scraped product.
		 */
		public function getName(){
			return $this->_name;
		}
		
		/**
		 * 
		 * Return all images for this product.
		 */
		public function getImages(){
			return $this->_images;
		}
		
		/**
		 * 
		 * Return the url of this product as used by the scraper.
		 */
		public function getUrl(){
			return $this->_url;
		}
		
		/**
		 * 
		 * Returns the rating this object received
		 */
		public function getRating(){
			return $this->_rating;
		}
		
		/**
		 * 
		 * Returns the price of this object
		 */
		public function getPrice(){
			return $this->_price;
		}
		
		/**
		 * 
		 * Returns the description of this object.
		 */
		public function getDescription(){
			return $this->_description;
		}
		
		/**
		 *
		 * Returns the summary of this object.
		 */
		public function getSummamry(){
			return $this->_summary;
		}
		
	}