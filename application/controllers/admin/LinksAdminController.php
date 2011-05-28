<?php

class LinksAdminController extends AdminController {

	/**
	 * Get all links for use in LinksGrid
	 */
	public function linksGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'sequence');
		$direction		= Input::post('dir', 'ASC');

		// Get records
		$records	= model('LinkModel')->adminGetLinksGrid($start, $limit, $order, $direction);
		$total		= model('LinkModel')->adminCountLinksGrid();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Save sequences in LinksGrid
	 */
	public function linksGridOrder(){

		// Update order
		$ids = json_decode(Input::post('ids'));
		model('LinkModel')->updateSequence($ids);

		// Output success
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get link details for use in LinksFormWindow
	 */
	public function linksFormWindowLoad(){

		// Get record
		$record = model('LinkModel')->adminGetLinkForForm(Input::postInt('id'));
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
	 * Save link details from LinksFormWindow
	 */
	public function linksFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('category_id')->required()->number();
		$validator->registerPost('title')->required()->maxLength(255);
		$validator->registerPost('url')->required()->maxLength(255)->url();
		$validator->registerPost('online')->number();
		$validator->registerFile('image')->image();
		if($validator->validate()){

			// Save form
			$data = array(
				'category_id'		=> Input::postInt('category_id'),
				'title'				=> Input::post('title'),
				'quicklink'			=> quicklink(Input::post('title')),
				'url'				=> Input::post('url'),
				'online'			=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
				$data['sequence']	= model('LinkModel')->getNextSequence();
			}
			$id = model('LinkModel')->save($data);
			
			// Delete and save new image
			if(Input::file('image')){
				model('ImageModel')->deleteAllByRelatedTableId('links', $id);
				$filename = Image::saveUpload('links', Input::file('image'));
				model('ImageModel')->save(array(
					'filename'		=> $filename,
					'related_table'	=> 'links',
					'related_id'	=> $id,
					'sequence'		=> 1
				));
			}

			// Output record
			$record = model('LinkModel')->adminGetLinkForGrid($id);
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
	 * Delete link from LinksGrid
	 */
	public function linksGridDelete(){

		// Get record
		$record = model('LinkModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}
		
		// Delete record
		model('LinkModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get all link categories for use in LinksFormWindow
	 */
	public function linksCategoriesCombobox(){

		// Get records
		$records	= model('LinkCategoryModel')->adminGetCombobox();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> count($records)
		));
	}

	/**
	 * Get all link categories for use in LinksCategoriesGrid
	 */
	public function linksCategoriesGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'sequence');
		$direction		= Input::post('dir', 'ASC');

		// Get records
		$records	= model('LinkCategoryModel')->adminGetLinksCategoriesGrid($start, $limit, $order, $direction);
		$total		= model('LinkCategoryModel')->adminCountLinksCategoriesGrid();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Save sequences in LinksCategoriesGrid
	 */
	public function linksCategoriesGridOrder(){

		// Update order
		$ids = json_decode(Input::post('ids'));
		model('LinkCategoryModel')->updateSequence($ids);

		// Output success
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get link category details for use in LinksCategoriesFormWindow
	 */
	public function linksCategoriesFormWindowLoad(){

		// Get record
		$record = model('LinkCategoryModel')->adminGetLinkCategoryForForm(Input::postInt('id'));
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
	 * Save link category details from LinksFormWindow
	 */
	public function linksCategoriesFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('title')->required()->maxLength(255);
		$validator->registerPost('online')->number();
		if($validator->validate()){

			// Save form
			$data = array(
				'title'				=> Input::post('title'),
				'quicklink'			=> quicklink(Input::post('title')),
				'online'			=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
				$data['sequence']	= model('LinkCategoryModel')->getNextSequence();
			}
			$id = model('LinkCategoryModel')->save($data);

			// Output record
			$record = model('LinkCategoryModel')->adminGetLinkCategoryForGrid($id);
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
	 * Delete link category from LinksCategoriesGrid
	 */
	public function linksCategoriesGridDelete(){

		// Get record
		$record = model('LinkCategoryModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}
		
		// Delete record
		model('LinkCategoryModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}
}