<?php
/****************************************************
 * Lean mean web machine
 *
 * Wrapper for GAPI, the Google Analytics API
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-29
 *
 ****************************************************/

require_once FRAMEWORK_PATH.'libraries/GAPI_1.3/gapi.class.php';
class GoogleAnalytics extends gapi
{

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		Loader::loadConfig('GoogleConfig');
		parent::__construct(GoogleConfig::$analyticsEmail, base64_decode(GoogleConfig::$analyticsPassword));
	}

	/**
	 * Get visits, visitors and pageviews for specific day
	 *
	 * @param string $day format: Y-m-d
	 * @param int $limit optional
	 * @return array
	 */
	public function getVisitsForDay($day, $limit = 9999){

		// Request stats
		$this->requestReportData(
			GoogleConfig::$analyticsId,
			array('date'),
			array('visits', 'visitors', 'pageviews'),
			'-visits',
			null,
			$day,
			$day,
			1,
			$limit
		);

		// Generate results
		return array(
			'visits'		=> $this->getVisits(),
			'visitors'		=> $this->getVisitors(),
			'pageviews'		=> $this->getPageviews()
		);
	}

	/**
	 * Get top keywords with pageviews, visits for specific day
	 *
	 * @param string $day format: Y-m-d
	 * @param int $limit optional
	 * @return array
	 */
	public function getKeywordsForDay($day, $limit = 9999){

		// Request stats
		$this->requestReportData(
			GoogleConfig::$analyticsId,
			array('keyword'),
			array('visits', 'pageviews'),
			'-visits',
			null,
			$day,
			$day,
			1,
			$limit
		);

		// Generate results
		$results = array();
		foreach($this->getResults() as $result){
			if($result->getKeyword() == '(not set)') continue;
			array_push($results, array(
				'title'		=> $result->getKeyword(),
				'visits'	=> $result->getVisits(),
				'pageviews'	=> $result->getPageviews()
			));
		}
		return $results;
	}

	/**
	 * Get top referrals with pageviews, visits for specific day
	 *
	 * @param string $day format: Y-m-d
	 * @param int $limit optional
	 * @return array
	 */
	public function getReferralsForDay($day, $limit = 9999){

		// Request stats
		$this->requestReportData(
			GoogleConfig::$analyticsId,
			array('source', 'referralPath'),
			array('visits', 'pageviews'),
			'-visits',
			null,
			$day,
			$day,
			1,
			$limit
		);

		// Generate results
		$results = array();
		foreach($this->getResults() as $result){
			$dimensions = $result->getDimensions();
			$domain = strstr($dimensions['source'], '.')? $dimensions['source'] : null;
			if($domain){
				$path = ($dimensions['referralPath'] == '(not set)')? '' : $dimensions['referralPath'];
				array_push($results, array(
					'url'		=> 'http://'.$domain.$path,
					'visits'	=> $result->getVisits(),
					'pageviews'	=> $result->getPageviews()
				));
			}
		}
		return $results;
	}

	/**
	 * Get top domains with pageviews, visits for specific day
	 *
	 * @param string $day format: Y-m-d
	 * @param int $limit optional
	 * @return array
	 */
	public function getDomainsForDay($day, $limit = 9999){

		// Request stats
		$this->requestReportData(
			GoogleConfig::$analyticsId,
			array('source'),
			array('visits', 'pageviews'),
			'-visits',
			null,
			$day,
			$day,
			1,
			$limit
		);

		// Generate results
		$results = array();
		foreach($this->getResults() as $result){
			$dimensions = $result->getDimensions();
			$domain = strstr($dimensions['source'], '.')? $dimensions['source'] : null;
			if($domain){
				array_push($results, array(
					'url'		=> 'http://'.$domain,
					'visits'	=> $result->getVisits(),
					'pageviews'	=> $result->getPageviews()
				));
			}
		}
		return $results;
	}
	
	/**
	 * Get top visited pages with pageviews, visits for specific day
	 *
	 * @param string $day format: Y-m-d
	 * @param int $limit optional
	 * @return array
	 */
	public function getPagesForDay($day, $limit = 9999){

		// Request stats
		$this->requestReportData(
			GoogleConfig::$analyticsId,
			array('pagePath', 'pageTitle'),
			array('visits', 'pageviews'),
			'-pageviews',
			null,
			$day,
			$day,
			1,
			$limit
		);

		// Generate results
		$results = array();
		foreach($this->getResults() as $result){
			array_push($results, array(
				'title'		=> $result->getPageTitle(),
				'path'		=> $result->getPagePath(),
				'visits'	=> $result->getVisits(),
				'pageviews'	=> $result->getPageviews()
			));
		}
		return $results;
	}

}
