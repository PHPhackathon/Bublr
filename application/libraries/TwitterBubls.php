<?php
/****************************************************
 * Bublr
 *
 * Search and fetch bubl mentions on Twitter
 *
 * @author Dirk Bonhomme <dirk@inventis.be>
 * @created 2011-05-28
 *
 ****************************************************/

class TwitterBubls extends Controller
{

	protected $keywords;
	
	/**
	 * Constructor
	 */
	public function __construct(){
		
		// Get all keywords to match against
		$this->keywords = model('KeywordModel')->getAllForMatching();
		
	}

	/**
	 * Fetch and parse tweets for bubls
	 *
	 * @param array $ids Ids of bubl tweets to fetch
	 */
	public function processBubls($ids){
		
		// Get bubls to search for
		$bubls = model('BublModel')->getAllForTwitterByIds($ids);
		
		// Process each bubl
		foreach($bubls as &$bubl){
		
			// Log status
			vardump('Processing bubl: ' . $bubl['title']);
		
			// Get tweets and matched keywords
			$matchedKeywords	= array();
			$tweets				= $this->fetchTweets($bubl);
			foreach($tweets as &$tweet){
				if($matches = $this->matchKeywords($tweet)){
					$matchedKeywords = array_merge($matchedKeywords, $matches);
				}
			}
			unset($tweet);
			
			// Save tweets
			foreach($tweets as &$tweet){
				model('BublTweetModel')->save(array(
					'bubl_id'			=> $bubl['id'],
					'tweet_id'			=> $tweet['tweet_id'],
					'text'				=> $tweet['text'],
					'to_user_id'		=> $tweet['to_user_id'],
					'from_user'			=> $tweet['from_user'],
					'from_user_id'		=> $tweet['from_user_id'],
					'profile_image_url'	=> $tweet['profile_image_url'],
					'date'				=> $tweet['date']
				));
			}
			unset($tweet);
			unset($tweets);
			
			// Count matches and save matched keywords
			$keywords = array();
			foreach($matchedKeywords as $keywordId){
				if(isset($keywords[$keywordId])){
					$keywords[$keywordId]++;
				}else{
					$keywords[$keywordId] = 1;
				}
			}
			unset($matchedKeywords);
			
			foreach($keywords as $keywordId => $matches){
				model('BublKeywordModel')->save(array(
					'buble_id'		=> $bubl['id'],
					'keyword_id'	=> $keywordId,
					'matches'		=> $matches,
					'date'			=> date('Y-m-d H:i:s')
				));
			}
			unset($keywords);
		}
		unset($bubl);		
	}
	
	/**
	 * Find new tweets and attach to bubl
	 *
	 * @param array $bubl
	 * @return array
	 */
	protected function fetchTweets($bubl){

		// Build URL
		$url = sprintf(
			'http://search.twitter.com/search.json?result=recent&q=%1$s&rpp=100&since_id=%2$s&lang=%3$s',
			urlencode($bubl['title']),
			$bubl['last_tweet_id'],
			'en'
		);

		// Get JSON results
		$result = json_decode(file_get_contents($url));
		if($result){
			$tweets = array();
			foreach($result->results as &$result){
				$tweets[] = array(
					'tweet_id'			=> $result->id_str,
					'text'				=> $result->text,
					'to_user_id'		=> $result->to_user_id,
					'from_user'			=> $result->from_user,
					'from_user_id'		=> $result->from_user_id,
					'profile_image_url'	=> $result->profile_image_url,
					'date'				=> date('Y-m-d H:i:s', strtotime($result->created_at))				
				);
			}
			unset($result);
			return $tweets;
		}
		return null;		
	}
	
	/**
	 * Match tweet against keywords and return matched keywords
	 *
	 * @param array $tweet
	 * @return array
	 */
	protected function matchKeywords($tweet){
		$matchedKeywords = array();
		foreach($this->keywords as &$keyword){
			if(stripos($tweet['text'], $keyword['keyword']) !== false){
				$matchedKeywords[] = $keyword['id'];
			}
		}
		unset($keyword);
		return $matchedKeywords;					
	}
	
}
