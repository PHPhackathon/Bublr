<?php

class BublModel extends Model {

	protected $table = 'bubls';


	/**
	 * LIBRARY
	 * Get bubles with last tweet id
	 *
	 * @param string $ids
	 * @return array
	 */
	public function getAllForTwitterByIds($ids){
		if($ids){
			$ids = implode(',', array_map('intval', $ids));
			$query = "
				SELECT 
					b.id, b.title,
					MAX(bt.tweet_id) AS last_tweet_id
					
				FROM bubls b
				
				LEFT JOIN bubls_tweets bt
				ON b.id = bt.bubl_id
				
				WHERE b.id IN ({$ids})
				
				GROUP BY b.id
			";
			return $this->getAll($query);
		}
		return array();
	}

}