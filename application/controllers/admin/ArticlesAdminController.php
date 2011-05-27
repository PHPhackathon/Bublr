<?php

class ArticlesAdminController extends AdminController {

	/**
	 * Get all articles for use in ArticlesGrid
	 */
	public function articlesGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'sequence');
		$direction		= Input::post('dir', 'ASC');

		// Get records
		$records	= model('ArticleModel')->adminGetArticlesGrid($start, $limit, $order, $direction);
		$total		= model('ArticleModel')->adminCountArticlesGrid();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Save sequences in ArticlesGrid
	 */
	public function articlesGridOrder(){

		// Update order
		$ids = json_decode(Input::post('ids'));
		model('ArticleModel')->updateSequence($ids);

		// Output success
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get article details for use in ArticlesFormWindow
	 */
	public function articlesFormWindowLoad(){

		// Get record
		$record = model('ArticleModel')->adminGetArticleForForm(Input::postInt('id'));
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
	 * Save article details from ArticlesFormWindow
	 */
	public function articlesFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('title')->required()->maxLength(255);
		$validator->registerPost('category_id')->required()->number();
		$validator->registerPost('content')->required();
		$validator->registerPost('online')->number();
		$validator->registerFile('image')->image();
		if($validator->validate()){

			// Get existing record
			$existingRecord = Input::post('id')? model('ArticleModel')->get(Input::postInt('id')) : null;

			// Save form
			$data = array(
				'category_id'	=> Input::postInt('category_id'),
				'title'			=> Input::post('title'),
				'description'	=> Input::post('description'),
				'content'		=> Input::post('content'),
				'online'		=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']							= Input::postInt('id');
				$data['updated']					= date('Y-m-d H:i:s');
				$data['updated_administrator_id']	= AdminAuthorizator::getAdministratorId();
				if(!$existingRecord['quicklink_readonly']){
					$data['quicklink'] = quicklink(Input::post('title'));
				}
			}else{
				$data['quicklink']					= quicklink(Input::post('title'));
				$data['created']					= date('Y-m-d H:i:s');
				$data['created_administrator_id']	= AdminAuthorizator::getAdministratorId();
				$data['sequence']					= model('ArticleModel')->getNextSequence();
			}
			$id = model('ArticleModel')->save($data);

			// Delete and save new image
			if(Input::file('image')){
				model('ImageModel')->deleteAllByRelatedTableId('articles', $id);
				$filename = Image::saveUpload('articles', Input::file('image'));
				model('ImageModel')->save(array(
					'filename'		=> $filename,
					'related_table'	=> 'articles',
					'related_id'	=> $id,
					'sequence'		=> 1
				));
			}

			// Output record
			$record = model('ArticleModel')->adminGetArticleForGrid($id);
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
	 * Delete article from ArticlesGrid
	 */
	public function articlesGridDelete(){

		// Get record
		$record = model('ArticleModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}

		// Delete record
		model('ArticleModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get all article categories for use in ArticlesFormWindow
	 */
	public function articlesCategoriesCombobox(){

		// Get records
		$records	= model('ArticleCategoryModel')->adminGetCombobox();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> count($records)
		));
	}

	/**
	 * Get all article categories for use in ArticlesCategoriesGrid
	 */
	public function articlesCategoriesGrid(){

		// Define range and order
		$start			= abs(Input::postInt('start'));
		$limit			= abs(Input::postInt('limit'));
		$order			= Input::post('sort', 'sequence');
		$direction		= Input::post('dir', 'ASC');

		// Get records
		$records	= model('ArticleCategoryModel')->adminGetArticlesCategoriesGrid($start, $limit, $order, $direction);
		$total		= model('ArticleCategoryModel')->adminCountArticlesCategoriesGrid();

		// Output data
		output_json_encode(array(
			'records'	=> $records,
			'total'		=> $total
		));
	}

	/**
	 * Save sequences in ArticlesCategoriesGrid
	 */
	public function articlesCategoriesGridOrder(){

		// Update order
		$ids = json_decode(Input::post('ids'));
		model('ArticleCategoryModel')->updateSequence($ids);

		// Output success
		output_json_encode(array(
			'success'	=> true
		));
	}

	/**
	 * Get article category details for use in ArticlesCategoriesFormWindow
	 */
	public function articlesCategoriesFormWindowLoad(){

		// Get record
		$record = model('ArticleCategoryModel')->adminGetArticleCategoryForForm(Input::postInt('id'));
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
	 * Save article category details from ArticlesCategoriesFormWindow
	 */
	public function articlesCategoriesFormWindowSave(){

		// Validate input
		$validator = library('Validator');
		$validator->registerPost('id')->number();
		$validator->registerPost('title')->required()->maxLength(255);
		$validator->registerPost('online')->number();
		if($validator->validate()){

			// Save form
			$data = array(
				'title'				=> Input::post('title'),
//				'quicklink'			=> quicklink(Input::post('title')),
				'description'		=> Input::post('description'),
				'online'			=> Input::postInt('online')
			);
			if(Input::post('id')){
				$data['id']			= Input::postInt('id');
				$data['updated']	= date('Y-m-d H:i:s');
			}else{
				$data['created']	= date('Y-m-d H:i:s');
				$data['sequence']	= model('ArticleCategoryModel')->getNextSequence();
			}
			$id = model('ArticleCategoryModel')->save($data);

			// Output record
			$record = model('ArticleCategoryModel')->adminGetArticleCategoryForGrid($id);
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
	 * Delete article category from ArticlesCategoriesGrid
	 */
	public function articlesCategoriesGridDelete(){

		// Get record
		$record = model('ArticleCategoryModel')->get(Input::postInt('id'));
		if(!$record){
			output_json_encode(array(
				'success'	=> false,
				'message'	=> 'Record bestaat niet (meer)'
			));
		}

		// Delete record
		model('ArticleCategoryModel')->delete($record['id']);
		output_json_encode(array(
			'success'	=> true
		));
	}

}