<?php

class BublModel extends Model {

	protected $table = 'bubls';

	protected $primaryKey = 'id';

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

	/**
	 * Add a bubl to the database if it does not yet exist.
	 * Otherwise update it.
	 * 
	 * @param string $name
	 * @param string $images
	 * @param string $url
	 * @param int	 $rating
	 * @param string $price
	 * @param string $summary
	 * @param string $description
	 * @param int 	 $source_id
	 * @param int	 $theme_id
	 */
	public function frontAddBubl( $name, $image, $url, $rating, $price, $summary, $description, $source_id, $theme_id ){
		
		$query = '
			SELECT id
			
			FROM bubls
			
			WHERE
				title = :title
				AND source_id = :source_id
				AND theme_id = :theme_id
		';
		
		$result = $this->getAll($query, 
			array( ':title', $name, Database::PARAM_STR ),
			array( ':source_id', $source_id, Database::PARAM_INT ),
			array( ':theme_id', $theme_id, Database::PARAM_INT ) );
		
		$data = array(
			'source_id' => $source_id,
			'theme_id' => $theme_id,
			'title' => $name,
			'quicklink' => '',
			'summary' => $summary,
			'description' => $description,
			'average_price' => $price,
			'average_score' => 0,
			'created' => date( 'd-m-Y H:i'),
			'image_url' => $image
		);
		
		if( count( $result ) > 0 ) {
			$data['id'] = $result[0]['id'];
			unset( $data['created'] );
			
			$this->update($data);
		} else {
			$this->insert($data);
		}
	}

}
