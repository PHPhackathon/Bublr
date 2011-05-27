<?php

class LinksFrontController extends FrontController {

	/**
	 * Show overview of categories and links
	 */
	public function index(){
	
		// Get links articles
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'links');
		$articles = model('ArticleModel')->frontGetChildren($articlesCategory['id']);
		$this->assign('articles', $articles);
	
		// Get categories and links
		$categories = model('LinkCategoryModel')->frontGetOverview();
		foreach($categories as &$category){
			$category['links'] = model('LinkModel')->frontGetOverviewByCategoryId($category['id']);
		}
		unset($category);
		$this->assign('categories', $categories);

		// Meta data
		$this->setCurrentPage('links');
		$this->setPageTitle($articlesCategory['title']);
		$this->setMetaDescription($articlesCategory['description']);

		// Output template
		$this->display('links/index.tpl');
	}

}