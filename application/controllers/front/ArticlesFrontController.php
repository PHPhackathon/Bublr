<?php

class ArticlesFrontController extends FrontController {

	/**
	 * Show article details
	 */
	public function article($articleQuicklink){

		// Get article
		$article = model('ArticleModel')->getRecordByFieldValue('quicklink', $articleQuicklink);
		if(!$article){
			frontcontroller('ErrorFrontController')->error404();
		}
		
		// Get images
		$article['images'] = model('ImageModel')->getAllByRelatedTableId('articles', $article['id']);
		
		// Meta data
		$this->setPageTitle($article['title']);
		$this->setMetaDescription($article['description']);

		// Output template
		$this->assign('article', $article);
		$this->display('articles/article.tpl');
	}

}