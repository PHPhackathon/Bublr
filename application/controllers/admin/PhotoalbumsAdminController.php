<?php

class PhotoalbumsAdminController extends AdminController {

	/**
	 * Get all photoalbums for use in PhotoalbumsGrid
	 */
	public function photoalbumsGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'date');
		$direction		= Input::post('dir', 'DESC');

		// Search query
		$search			= Input::post('search', null);

		// Get records
		$records	= model('PhotoalbumModel')->adminGetPhotoalbumsGrid($start, $limit, $order, $direction, $search);
		$total		= model('PhotoalbumModel')->adminCountPhotoalbumsGrid($search);

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Delete photoalbum from PhotoalbumsGrid
	 */
	public function photoalbumsGridDelete(){

		// Get record
		$record = model('PhotoalbumModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}

		// Delete record
		model('PhotoalbumModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get photoalbum details for use in PhotoalbumsFormWindow
	 */
	public function photoalbumsFormWindowLoad(){

		// Get record
		$record = model('PhotoalbumModel')->adminGetPhotoalbumForForm(Input::postInt('id'));
		if($record){
			output_json_encode(array(
				'success'	=> true,
				'record'	=> $record
			));
		}else{
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Gegevens konden niet geladen worden'
			));
		}
	}

	/**
	 * Save photoalbum details from PhotoalbumsFormWindow
	 */
	public function photoalbumsFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('title')->required()->maxLength(255);
		$validator->registerPost('date')->required()->date();
		$validator->registerPost('online')->number();

		if($validator->validate()){

			// Save form
			$data = array(
				'title'			=> Input::post('title'),
				'quicklink'		=> quicklink(Input::post('title')),
				'date'			=> Input::post('date'),
				'description'	=> Input::post('description'),
				'online'		=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
			}
			$id = model('PhotoalbumModel')->save($data);

			// Output record
			$record = model('PhotoalbumModel')->adminGetPhotoalbumForGrid($id);
			output_json_encode(array(
				'success'	=> true,
				'record'	=> $record
			));
		}

		// Output validation errors
		output_json_encode(array(
			'success'	=> false,
			'msg'		=> 'Gelieve alle aangeduide velden te controleren en opnieuw te proberen',
			'errors'	=> $validator->getAdminErrors()
		));
	}

}