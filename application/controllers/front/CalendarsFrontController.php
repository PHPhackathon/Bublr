<?php

class CalendarsFrontController extends FrontController {

	/**
	 * Overview of calendars
	 *
	 * @param string $year optional
	 */
	public function index($year = null){

		// Get calendar articles
		$articlesCategory = model('ArticleCategoryModel')->getRecordByFieldValue('quicklink', 'activiteiten');
		$articles = model('ArticleModel')->frontGetChildren($articlesCategory['id']);
		$this->assign('articles', $articles);

		// Get calendars by year
		$year = min(date('Y'), max(1900, $year? $year : date('Y')));
		$calendars = model('CalendarModel')->frontGetCalendarsByYear($year);
		$this->assign('calendars', $calendars);
		$this->assign('year', $year);
		
		// Get calendar years
		$calendarYears = model('CalendarModel')->frontGetCalendarYears();
		$this->assign('calendarYears', $calendarYears);

		// Meta data
		$this->setCurrentPage('calendars');
		$this->setPageTitle('Activiteiten van ' . $year);
		$this->setMetaDescription($articlesCategory['description']);

		// Output template
		$this->display('calendars/index.tpl');
	}

}