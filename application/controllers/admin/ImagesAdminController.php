<?php

class ImagesAdminController extends AdminController {

	/**
	 * Get all images for use in ImagesGrid
	 */
	public function imagesGrid(){
	
		// Get related data
		$relatedTable	= Input::post('relatedTable');
		$relatedId		= Input::postInt('relatedId');

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'sequence');
		$direction		= Input::post('dir', 'ASC');

		// Get records
		$records	= model('ImageModel')->adminGetImagesGrid($relatedTable, $relatedId, $start, $limit, $order, $direction);
		$total		= model('ImageModel')->adminCountImagesGrid($relatedTable, $relatedId);

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Save sequences in ImagesGrid
	 */
	public function imagesGridOrder(){

		// Update order
		$ids = json_decode(Input::post('ids'));
		model('ImageModel')->updateSequence($ids);

		// Output success
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get image details for use in ImagesFormWindow
	 */
	public function imagesFormWindowLoad(){

		// Get record
		$record = model('ImageModel')->adminGetImageForForm(Input::postInt('id'));
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
	 * Save image details from ImagesFormWindow
	 */
	public function imagesFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number()->required();
		$validator->registerPost('alt')->maxLength(255);
		if($validator->validate()){

			// Save form
			$data = array(
				'id'		=> Input::postInt('id'),
				'alt'		=> Input::post('alt')
			);
			$id = model('ImageModel')->save($data);

			// Output record
			$record = model('ImageModel')->adminGetImageForGrid($id);
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
	
	/**
	 * Save image file from AwesomeUploader
	 */
	public function imagesAwesomeUploaderSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('relatedTable')->required();
		$validator->registerPost('relatedId')->number()->required();
		$validator->registerFile('image')->required()->image();
		if($validator->validate()){

			// Upload and save image
			$filename = Image::saveUpload(Input::post('relatedTable'), Input::file('image'));
			model('ImageModel')->save(array(
				'filename'		=> $filename,
				'related_table'	=> Input::post('relatedTable'),
				'related_id'	=> Input::post('relatedId'),
				'sequence'		=> model('ImageModel')->getNextSequenceByRelatedTableId(Input::post('relatedTable'), Input::post('relatedId'))
			));

			// Output record
			output_json_encode(array(
				'success'	=> true
			));
		}

		// Output validation errors
		output_json_encode(array(
			'success'	=> false,
			'error'		=> 'Er ging iets fout bij het uploaden. Gelieve opnieuw te proberen'
		));
	}
	
	/**
	 * Delete image from ImagesGrid
	 */
	public function imagesGridDelete(){

		// Get record
		$record = model('ImageModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}
		
		// Delete record
		model('ImageModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}
	
	/**
	 * Delete image from ImageField
	 */
	public function imageFieldDelete(){

		// Get record
		$record = model('ImageModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}
		
		// Delete record
		model('ImageModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Clear cache
	 */
	public function clearCache(){
		$path = CACHE_PATH.'images/*';
		exec("rm -Rf {$path}");
		exit('-- done --');
	}
}