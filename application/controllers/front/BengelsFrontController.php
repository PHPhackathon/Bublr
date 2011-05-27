<?php

class BengelsFrontController extends FrontController {

	/**
	 * Show overview of articles
	 */
	public function index(){
		
		// Get bengels articles
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'bengels');
		$articles = model('ArticleModel')->frontGetChildren($articlesCategory['id']);
		$this->assign('articles', $articles);

		// Meta data
		$this->setCurrentPage('bengels');
		$this->setPageTitle($articlesCategory['title']);
		$this->setMetaDescription($articlesCategory['description']);

		// Output template
		$this->display('bengels/index.tpl');
	}

	/**
	 * Show article details
	 */
	public function article($articleQuicklink){

		// Get article
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'bengels');
		$article = model('ArticleModel')->frontGetByCategoryIdAndQuicklink($articlesCategory['id'], $articleQuicklink);
		if(!$article){
			frontcontroller('ErrorFrontController')->error404();
		}
		
		// Get images
		$article['images'] = model('ImageModel')->getAllByRelatedTableId('articles', $article['id']);
		
		// Meta data
		$this->setCurrentPage('bengels');
		$this->setPageTitle($articlesCategory['title']);
		$this->setMetaDescription($article['description']);

		// Output template
		$this->assign('article', $article);
		$this->display('bengels/article.tpl');
	}

}