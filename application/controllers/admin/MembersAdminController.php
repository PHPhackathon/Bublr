<?php

class MembersAdminController extends AdminController {

	/**
	 * Get all members for use in MembersGrid
	 */
	public function membersGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'sequence');
		$direction		= Input::post('dir', 'ASC');

		// Get records
		$records	= model('MemberModel')->adminGetMembersGrid($start, $limit, $order, $direction);
		$total		= model('MemberModel')->adminCountMembersGrid();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Save sequences in MembersGrid
	 */
	public function membersGridOrder(){

		// Update order
		$ids = json_decode(Input::post('ids'));
		model('MemberModel')->updateSequence($ids);

		// Output success
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Export all members to CSV
	 */
	public function membersGridExport(){

		// Get categories and members
		$categories = model('MemberCategoryModel')->adminGetCategoriesForExport();
		foreach($categories as &$category){
			$category['members'] = model('MemberModel')->adminGetMembersForExport($category['id']);
		}
		unset($category);


		// Create CSV
		$rows = array(array('Naam', 'M/V', 'Adres', 'Postcode', 'Gemeente', 'Geboortedatum', 'Telefoonnr', 'Email', 'Betaald'));
		foreach($categories as &$category){
			if($category['members']){
				$rows[] = array();
				$rows[] = array(strtoupper($category['title']));
				foreach($category['members'] as &$member){
					$rows[] = array(
						$member['firstname'].' '.$member['lastname'],
						$member['gender']? ($member['gender'] == 'm')? 'M' : 'V' : '',
						$member['street'],
						$member['postal_code'],
						$member['city'],
						$member['birthdate']? date('d/m/Y', strtotime($member['birthdate'])) : '',
						$member['phone'],
						$member['email'],
						$member['payed']? 'JA' : 'NEE'
					);
				}
			}
		}
		unset($member);
		unset($category);

		// Output data
		$csv = '';
		foreach($rows as &$row){
			$csv .= implode(';', $row) . "\n";
		}
		unset($row);

		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="ledenlijst_'.date('Y_m_d').'.csv"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.strlen($csv));
		exit($csv);
	}

	/**
	 * Get member details for use in MembersFormWindow
	 */
	public function membersFormWindowLoad(){

		// Get record
		$record = model('MemberModel')->adminGetMemberForForm(Input::postInt('id'));
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
	 * Save member details from MembersFormWindow
	 */
	public function membersFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('category_id')->required()->number();
		$validator->registerPost('firstname')->required()->maxLength(50);
		$validator->registerPost('lastname')->required()->maxLength(50);
		$validator->registerPost('gender')->required();
		$validator->registerPost('street')->maxLength(50);
		$validator->registerPost('postal_code')->maxLength(10);
		$validator->registerPost('city')->maxLength(50);
		$validator->registerPost('birthdate')->date();
		$validator->registerPost('phone')->maxLength(50);
		$validator->registerPost('email')->maxLength(50)->email();
		$validator->registerPost('about')->maxLength(50);
		$validator->registerPost('online')->number();
		$validator->registerPost('online')->number();
		$validator->registerFile('image')->image();
		if($validator->validate()){

			// Save form
			$data = array(
				'category_id'		=> Input::postInt('category_id'),
				'firstname'			=> Input::post('firstname'),
				'lastname'			=> Input::post('lastname'),
				'quicklink'			=> quicklink(Input::post('firstname').' '.Input::post('lastname')),
				'gender'			=> Input::post('gender'),
				'street'			=> Input::post('street'),
				'postal_code'		=> Input::post('postal_code'),
				'city'				=> Input::post('city'),
				'birthdate'			=> Input::post('birthdate'),
				'phone'				=> Input::post('phone'),
				'email'				=> Input::post('email'),
				'about'				=> Input::post('about'),
				'payed'				=> Input::postInt('payed'),
				'online'			=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
				$data['sequence']	= model('MemberModel')->getNextSequence();
			}
			$id = model('MemberModel')->save($data);

			// Delete and save new image
			if(Input::file('image')){
				model('ImageModel')->deleteAllByRelatedTableId('members', $id);
				$filename = Image::saveUpload('members', Input::file('image'));
				model('ImageModel')->save(array(
					'filename'		=> $filename,
					'related_table'	=> 'members',
					'related_id'	=> $id,
					'sequence'		=> 1
				));
			}

			// Output record
			$record = model('MemberModel')->adminGetMemberForGrid($id);
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
	 * Delete member from MembersGrid
	 */
	public function membersGridDelete(){

		// Get record
		$record = model('MemberModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}

		// Delete record
		model('MemberModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get all member categories for use in MembersFormWindow
	 */
	public function membersCategoriesCombobox(){

		// Get records
		$records	= model('MemberCategoryModel')->adminGetCombobox();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> count($records)
		));
	}

	/**
	 * Get all member categories for use in MembersCategoriesGrid
	 */
	public function membersCategoriesGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'sequence');
		$direction		= Input::post('dir', 'ASC');

		// Get records
		$records	= model('MemberCategoryModel')->adminGetMembersCategoriesGrid($start, $limit, $order, $direction);
		$total		= model('MemberCategoryModel')->adminCountMembersCategoriesGrid();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Save sequences in MembersCategoriesGrid
	 */
	public function membersCategoriesGridOrder(){

		// Update order
		$ids = json_decode(Input::post('ids'));
		model('MemberCategoryModel')->updateSequence($ids);

		// Output success
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get member category details for use in MembersCategoriesFormWindow
	 */
	public function membersCategoriesFormWindowLoad(){

		// Get record
		$record = model('MemberCategoryModel')->adminGetMemberCategoryForForm(Input::postInt('id'));
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
	 * Save member category details from MembersFormWindow
	 */
	public function membersCategoriesFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('title')->required()->maxLength(255);
		$validator->registerPost('online')->number();
		$validator->registerPost('sidebar')->number();
		if($validator->validate()){

			// Save form
			$data = array(
				'title'				=> Input::post('title'),
				'quicklink'			=> quicklink(Input::post('title')),
				'online'			=> Input::postInt('online'),
				'sidebar'			=> Input::postInt('sidebar')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
				$data['sequence']	= model('MemberCategoryModel')->getNextSequence();
			}
			$id = model('MemberCategoryModel')->save($data);

			// Output record
			$record = model('MemberCategoryModel')->adminGetMemberCategoryForGrid($id);
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
	 * Delete member category from MembersCategoriesGrid
	 */
	public function membersCategoriesGridDelete(){

		// Get record
		$record = model('MemberCategoryModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}

		// Delete record
		model('MemberCategoryModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}
}