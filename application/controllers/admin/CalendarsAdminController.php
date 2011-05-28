<?php

class CalendarsAdminController extends AdminController {

	/**
	 * Get all calendars for use in CalendarsGrid
	 */
	public function calendarsGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'month');
		$direction		= Input::post('dir', 'DESC');

		// Search query
		$search			= Input::post('search', null);

		// Get records
		$records	= model('CalendarModel')->adminGetCalendarsGrid($start, $limit, $order, $direction, $search);
		$total		= model('CalendarModel')->adminCountCalendarsGrid($search);

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Delete calendar from CalendarsGrid
	 */
	public function calendarsGridDelete(){

		// Get record
		$record = model('CalendarModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}

		// Delete record
		model('CalendarModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get calendar details for use in CalendarsFormWindow
	 */
	public function calendarsFormWindowLoad(){

		// Get record
		$record = model('CalendarModel')->adminGetCalendarForForm(Input::postInt('id'));
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
	 * Save calendar details from CalendarsFormWindow
	 */
	public function calendarsFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('month')->required()->date();
		$validator->registerPost('online')->number();
		$validator->registerFile('file')->fileType('application/pdf');
		if($validator->validate()){

			// Save form
			$data = array(
				'month'			=> Input::post('month'),
				'description'	=> Input::postHtml('description'),
				'online'		=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
			}
			$id = model('CalendarModel')->save($data);

			// Delete and save new file
			if($file = Input::file('file')){
				model('FileModel')->deleteAllByRelatedTableId('calendars', $id);
				$filename	= FileUpload::saveUpload('calendars', $file);
				$extension	= FileUpload::getExtension($filename);
				model('FileModel')->save(array(
					'filename'		=> $filename,
					'mimetype'		=> $file['type'],
					'extension'		=> $extension,
					'size'			=> $file['size'],
					'related_table'	=> 'calendars',
					'related_id'	=> $id,
					'sequence'		=> 1
				));
			}

			// Output record
			$record = model('CalendarModel')->adminGetCalendarForGrid($id);
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