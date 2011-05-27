<?php

class MembersFrontController extends FrontController {

	/**
	 * Overview of photoalbums
	 *
	 * @param string $year optional
	 */
	public function index($year = null){

		// Get members articles
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'kern');
		$articles = model('ArticleModel')->frontGetChildren($articlesCategory['id']);
		$this->assign('articles', $articles);

		// Get categories + members
		$categories = model('MemberCategoryModel')->frontGetOverview();
		foreach($categories as &$category){
			$category['members'] = model('MemberModel')->frontGetOverviewByCategoryId($category['id']);
		}
		unset($category);
		$this->assign('categories', $categories);
		
		// Meta data
		$this->setCurrentPage('members');
		$this->setPageTitle($articlesCategory['title']);
		$this->setMetaDescription($articlesCategory['description']);

		// Output template
		$this->display('members/index.tpl');
	}

}