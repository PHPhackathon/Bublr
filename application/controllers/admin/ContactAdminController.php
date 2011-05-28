<?php

class ContactAdminController extends AdminController {

	/**
	 * Get all messages for use in ContactGrid
	 */
	public function contactGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'created');
		$direction		= Input::post('dir', 'DESC');

		// Search query
		$search			= Input::post('search', null);

		// Get records
		$records	= model('ContactModel')->adminGetContactGrid($start, $limit, $order, $direction, $search);
		$total		= model('ContactModel')->adminCountContactGrid($search);

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}
	
	/**
	 * Delete message from ContactGrid
	 */
	public function contactGridDelete(){

		// Get record
		$record = model('ContactModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}
		
		// Delete record
		model('ContactModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

}