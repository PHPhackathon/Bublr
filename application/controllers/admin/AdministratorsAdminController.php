<?php

class AdministratorsAdminController extends AdminController {

	/**
	 * Get all administrators for use in AdministratorsGrid
	 */
	public function administratorsGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'firstname');
		$direction		= Input::post('dir', 'ASC');

		// Search query
		$search			= Input::post('search', null);

		// Get records
		$records	= model('AdministratorModel')->adminGetAdministratorsGrid($start, $limit, $order, $direction, $search);
		$total		= model('AdministratorModel')->adminCountAdministratorsGrid($search);

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Delete administrator from AdministratorsGrid
	 */
	public function administratorsGridDelete(){

		// Get record
		$record = model('AdministratorModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}
		
		// Validate that record is not logged in administrator
		if($record['id'] === $_SESSION['admin_id']){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Je kan je eigen account niet verwijderen'
			));
		}

		// Delete record
		model('AdministratorModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get administrator details for use in AdministratorsFormWindow
	 */
	public function administratorsFormWindowLoad(){

		// Get record
		$record = model('AdministratorModel')->adminGetAdministratorForForm(Input::postInt('id'));
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
	 * Save administrator details from AdministratorsFormWindow
	 */
	public function administratorsFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('firstname')->required()->maxLength(50);
		$validator->registerPost('lastname')->required()->maxLength(50);
		$validator->registerPost('email')->required()->maxLength(50)->email();
		$validator->registerPost('password')->minLength(4);
		$validator->registerPost('password_check')->matchField('password', 'Wachtwoord');
		$validator->registerPost('online')->number();
		if(!Input::post('id')){
			$validator->registerPost('password')->required()->minLength(4);
			$validator->registerPost('password_check')->required()->matchField('password', 'Wachtwoord');
		}	
		
		if($validator->validate()){

			// Save form
			$data = array(
				'firstname'		=> Input::post('firstname'),
				'lastname'		=> Input::post('lastname'),
				'email'			=> Input::post('email'),
				'online'		=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
			}
			if(Input::post('password')){
				$data['password']	= sha1(ApplicationConfig::$hashSalt.Input::post('password'));
			}
			$id = model('AdministratorModel')->save($data);

			// Output record
			$record = model('AdministratorModel')->adminGetAdministratorForGrid($id);
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