<?php

class NewslettersFrontController extends FrontController {

	/**
	 * Subscribe to newsletters
	 * @notice called by routing
	 */
	public function subscribe(){

		// Validate input
		$validator = library('Validator');
		$validator->reset()
			->registerPost('name')->required()->maxLength(255)
			->registerPost('email')->required()->email()->maxLength(255);
		if($validator->validate()){

			// Insert if email does not already exist
			if(!model('NewsletterSubscriberModel')->getRecordByFieldValue('email', Input::post('email'))){
				model('NewsletterSubscriberModel')->save(array(
					'name'		=> Input::post('name'),
					'email'		=> Input::post('email'),
					'created'	=> date('Y-m-d H:i:s'),
					'ip'		=> UserAgent::ip()
				));
			}

			// Show success page
			$this->display('newsletters/subscribe.success.tpl');
			exit;
		}
		
		// Meta data
		$this->setPageTitle('Nieuwsbrief');
		$this->setMetaDescription('Vul hier je e-mailadres in om maandelijks onze kalender te ontvangen!');

		// Output template
		$this->assign('errors', $validator->getErrors());
		$this->display('newsletters/subscribe.tpl');
	}

}