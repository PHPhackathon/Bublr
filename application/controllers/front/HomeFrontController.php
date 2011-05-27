<?php

class HomeFrontController extends FrontController {

	/**
	 * Homepage with article, latest calendar and latest photoalbum
	 */
	public function index(){

		// Get homepage articles
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'home');
		$articles = model('ArticleModel')->frontGetChildren($articlesCategory['id']);
		$this->assign('articles', $articles);

		// Get latest calendar
		//$latestCalendar = model('CalendarModel')->frontGetLatestCalendar();
		//$this->assign('latestCalendar', $latestCalendar);

		// Get latest photoalbum with images
		$latestPhotoalbum = model('PhotoalbumModel')->frontGetLatestPhotoalbum();
		if($latestPhotoalbum){
			$latestPhotoalbum['images'] = model('ImageModel')->getAllByRelatedTableId('photoalbums', $latestPhotoalbum['id'], 0, 5);
		}
		$this->assign('latestPhotoalbum', $latestPhotoalbum);

		// Meta data
		$this->setCurrentPage('home');
		$this->setPageTitle($articlesCategory['title']);
		$this->setMetaDescription($articlesCategory['description']);

		// Output template
		$this->display('home/index.tpl');
	}

}