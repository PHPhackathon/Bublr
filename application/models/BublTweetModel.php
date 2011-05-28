<?php

class BublTweetModel extends Model {

	protected $table = 'bubls_tweets';

	/**
	 * Retrieve all tweets for a buble.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function frontGetTweetsForBubl( $id ){
		$query = "
			SELECT
				id, bubl_id, tweet_id, text, to_user_id, from_user_id, profile_image_url, date
			FROM
				{$this->table}
			WHERE
				bubl_id = :bubl_id
		";
				
		return $this->getAll( $query,
			array( ':bubl_id', $id, Database::PARAM_INT ) );
	}
	
}
