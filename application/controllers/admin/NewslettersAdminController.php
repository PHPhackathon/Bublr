<?php

class NewslettersAdminController extends AdminController {

	/**
	 * Get all subscribers for use in NewslettersSubscribersGrid
	 */
	public function subscribersGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'created');
		$direction		= Input::post('dir', 'DESC');

		// Search query
		$search			= Input::post('search', null);

		// Get records
		$records	= model('NewsletterSubscriberModel')->adminGetSubscribersGrid($start, $limit, $order, $direction, $search);
		$total		= model('NewsletterSubscriberModel')->adminCountSubscribersGrid($search);

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}
	
	/**
	 * Delete subscriber from NewslettersSubscribersGrid
	 */
	public function subscribersGridDelete(){

		// Get record
		$record = model('NewsletterSubscriberModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}
		
		// Delete record
		model('NewsletterSubscriberModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Create CSV with all subscribers
	 */
	public function exportSubscribers(){

		// Get all records
		$list = model('NewsletterSubscriberModel')->adminGetForExport();

		// Generate CSV data
		$counter = 1;
		$csv = 'Nr;Naam;Email;Ingeschreven;Blacklisted';
		foreach($list as &$row){
			$csv .= "\n" . implode(';', array(
				$counter++,
				str_replace(';', ' ', $row['name']),
				str_replace(';', ' ', $row['email']),
				date('d/m/Y', strtotime($row['created'])),
				$row['blacklisted']
			));
		}
		unset($row);

		// Output data
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="inschrijvingen.csv"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.strlen($csv));
		exit($csv);
	}
}