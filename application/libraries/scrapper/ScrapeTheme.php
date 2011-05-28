<?php
	class ScrapeTheme {
		
		protected $_id;
		protected $_title;
		protected $_link;
		protected $_results;
		
		
		/**
		 * 
		 * Create new scrapped Theme
		 * @param number $id
		 * @param String $title
		 * @param String $link
		 */
		public function __construct( $id, $title, $link, $results=array() ){
			$this->_id = $id;
			$this->_title = $title;
			$this->_link = $link;
			$this->_results = $results;
		}
		
		/**
		 * 
		 * Get the id of the current theme on the website specified.
		 */
		public function getId(){
			return $this->_link;
		}
		
		/**
		 * 
		 * Get the title of the current theme.
		 */
		public function getTitle(){
			return $this->_title;
		}
		
		/**
		 * 
		 * Return the URL to this theme.
		 */
		public function getLink(){
			return $this->_link;
		}
		
		/**
		 * 
		 * Add a result to this theme.
		 * @param ScrapeResult $result
		 */
		public function addResult( ScrapeResult $result ){
			$this->_results[] = $result;
		}
		
		/**
		 * 
		 * Get all the object under this category.
		 */
		public function getResults(){
			return $this->_results;
		}
		
	}