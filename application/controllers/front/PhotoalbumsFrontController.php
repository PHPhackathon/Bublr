<?php

class PhotoalbumsFrontController extends FrontController {

	/**
	 * Overview of photoalbums
	 *
	 * @param string $year optional
	 */
	public function index($year = null){

		// Get photoalbums articles
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'fotos');
		$articles = model('ArticleModel')->frontGetChildren($articlesCategory['id']);
		$this->assign('articles', $articles);

		// Get photoalbums by year
		$year = min(date('Y'), max(1900, $year? $year : date('Y')));
		$photoalbums = model('PhotoalbumModel')->frontGetPhotoalbumsByYear($year);
		foreach($photoalbums as &$photoalbum){
			$photoalbum['images'] = model('ImageModel')->getAllByRelatedTableId('photoalbums', $photoalbum['id'], 0, 10);
			$photoalbum['images_count'] = model('ImageModel')->getCountByRelatedTableId('photoalbums', $photoalbum['id']);
		}
		unset($photoalbum);
		$this->assign('photoalbums', $photoalbums);
		$this->assign('year', $year);

		// Get photoalbum years
		$photoalbumYears = model('PhotoalbumModel')->frontGetPhotoalbumYears();
		$this->assign('photoalbumYears', $photoalbumYears);

		// Meta data
		$this->setCurrentPage('photoalbums');
		$this->setPageTitle('Foto\'s van ' . $year);
		$this->setMetaDescription($articlesCategory['description']);

		// Output template
		$this->display('photoalbums/index.tpl');
	}

	/**
	 * Details of photoalbum with photos
	 *
	 * @param string $year optional
	 */
	public function photoalbum($year, $photoalbumQuicklink){

		// Get and validate photoalbum
		$year = min(date('Y'), max(1900, $year? $year : date('Y')));
		$photoalbum = model('PhotoalbumModel')->frontGetPhotoalbumByYearQuicklink($year, $photoalbumQuicklink);
		if(!$photoalbum){
			frontcontroller('ErrorFrontController')->error404();
		}
		$photoalbum['images'] = model('ImageModel')->getAllByRelatedTableId('photoalbums', $photoalbum['id']);
		$this->assign('photoalbum', $photoalbum);
		$this->assign('year', $year);

		// Get photoalbum years
		$photoalbumYears = model('PhotoalbumModel')->frontGetPhotoalbumYears();
		$this->assign('photoalbumYears', $photoalbumYears);

		// Meta data
		$this->setCurrentPage('photoalbums');
		$this->setPageTitle('Foto\'s van ' . $year);
		$this->setMetaDescription($photoalbum['description']);

		// Output template
		$this->display('photoalbums/photoalbum.tpl');
	}

}