<?php
/****************************************************
 * Lean mean web machine
 *
 * Front controller library
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class FrontController extends Controller
{

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		
		// Start session
		if(!session_id()){
			session_start();
		}
		
		// Call parent constructor
		parent::__construct();
		
		// Set google analytics account
		Loader::loadConfig('GoogleConfig');
		$this->assign('googleAnalyticsAccount', GoogleConfig::$analyticsAccount);

		// Set template path
		$this->templatePath = APPLICATION_PATH.'views/front/';
		
		// Get latest calendar for sidebar
		$latestCalendar = model('CalendarModel')->frontGetLatestCalendar();
		$this->assign('latestCalendarSidebar', $latestCalendar);
		
		// Get latest photoalbum for sidebar
		$latestPhotoalbum = model('PhotoalbumModel')->frontGetLatestPhotoalbum();
		if($latestPhotoalbum){
			$latestPhotoalbum['images'] = model('ImageModel')->getAllByRelatedTableId('photoalbums', $latestPhotoalbum['id'], 0, 4);
		}
		$this->assign('latestPhotoalbumSidebar', $latestPhotoalbum);
		
		// Get random member for sidebar
		$member = model('MemberModel')->frontGetRandom();
		$this->assign('memberSidebar', $member);		
	}
	
}
