<?php

class ContactFrontController extends FrontController {

	/**
	 * Contact info and form
	 */
	public function index(){

		// Validate input
		$validator = library('Validator');
		$validator->reset()
			->registerPost('firstname')->required()->maxLength(50)
			->registerPost('lastname')->required()->maxLength(50)
			->registerPost('email')->required()->maxLength(50)->email()
			->registerPost('phone')->maxLength(20)
			->registerPost('message')->required();
		if($validator->validate()){

			// Save form
			$data = array(
				'firstname'		=> Input::post('firstname'),
				'lastname'		=> Input::post('lastname'),
				'email'			=> Input::post('email'),
				'phone'			=> Input::post('phone'),
				'message'		=> Input::post('message'),
				'ip'			=> UserAgent::ip(),
				'created'		=> date('Y-m-d H:i:s')
			);
			model('ContactModel')->save($data);

			// Send mail to administrator
			$message = Mailer::prepareMessage(array(
				'to'		=> array(ApplicationConfig::$mailSenderEmail => ApplicationConfig::$mailSenderName),
				'subject'	=> 'Contactformulier op ' . ApplicationConfig::$siteName,
				'template'	=> 'contact/mail.admin.tpl',
				'data'		=> $data,
				'replyTo'	=> array(Input::post('email') => Input::post('firstname').' '.Input::post('lastname'))
			));
			Mailer::sendMessage($message);

			// Redirect to success page
			redirect(ApplicationConfig::$siteUrl.'contact/succes');
			exit;
		}

		// Get calendar articles
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'contact');
		$articles = model('ArticleModel')->frontGetChildren($articlesCategory['id']);
		$this->assign('articles', $articles);

		// Meta data
		$this->setCurrentPage('contact');
		$this->setPageTitle($articlesCategory['title']);
		$this->setMetaDescription($articlesCategory['description']);

		// Output template
		$this->assign('errors', $validator->getErrors());
		$this->display('contact/index.tpl');
	}

	/**
	 * Contact form success page
	 */
	public function success(){

		// Meta data
		$this->setCurrentPage('contact');
		$this->setPageTitle('Contacteer ons');

		// Output template
		$this->display('contact/success.tpl');
	}

}