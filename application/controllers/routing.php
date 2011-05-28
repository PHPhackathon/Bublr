<?php
/****************************************************
 * Lean mean web machine
 *
 * URL routing for frontcontrollers
 * Determine frontcontroller, function and parameters to use
 *
 * Format of each route array:
 *	0			=> Route regex. Please note that closing slashed are not allowed
 *	1			=> FrontController class
 *	2			=> FrontController method
 *	3 etc		=> Method argument (use Url::getSegment)
 *
 * Wildcards for route regex:
 *	':string:'	=> Alphanumeric + dash + underscore + dot
 *	':number:'	=> Signed integer *
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-06
 *
 ****************************************************/

return array(

	// Home
	array('/', 'HomeFrontController', 'index'),
	
	// Test
	array('/test/bubl_tweets', 'TestFrontController', 'bublTweets'),
	array('/test/twitter_query', 'TestFrontController', 'twitterQuery'),
	
	// Themes / categorie uploader
	// array('/themes/coolblue', 'ThemesFrontController', 'coolblue'),
	// array('/themes/update', 'ThemesFrontController', 'update'),
	
	// Bubls
	array('/bubls/:number:', 'BublFrontController', 'products', Url::getSegment(1)),
	array('/bubls/:number:/:number:', 'BublFrontController', 'products', Url::getSegment(1), Url::getSegment(2)),
	array('/bubls/price_range/:number:', 'BublFrontController', 'priceRange', Url::getSegment(2)),
	array('/bubls/mobile_list/:number:/:string:', 'BublFrontController', 'mobileList', Url::getSegment(2), Url::getSegment(3)),
	array('/category/all', 'CategoryFrontController', 'all' ),
	array('/bubls/tweets/:number:', 'BublTweetFrontController', 'tweets' ),
	
	// Mobile
	array('/mobile', 'MobileFrontController', 'index'),
	
	// Cronjobs
	array('/system/cron/update/tweets', 'CronFrontController', 'updateTweets' ),
	array('/system/cron/update/themes', 'CronFrontController', 'updateThemes' ),
	
	// Articles
	array('/artikel/:string:', 'ArticlesFrontController', 'article', Url::getSegment(1)),

	// Files
	array('/files/download/:string:/:string:', 'FilesFrontController', 'download', Url::getSegment(2), Url::getSegment(3)),

	// Images
	array('/images/:string:', 'ImagesFrontController', underscore_to_camelcase(Url::getSegment(1))),
	array('/images/:string:/:string:', 'ImagesFrontController', underscore_to_camelcase(Url::getSegment(1)), Url::getSegment(2)),
	array('/images/:string:/:string:/:string:', 'ImagesFrontController', underscore_to_camelcase(Url::getSegment(1)), Url::getSegment(2), Url::getSegment(3)),
	array('/images/:string:/:string:/:string:/:string:', 'ImagesFrontController', underscore_to_camelcase(Url::getSegment(1)), Url::getSegment(2), Url::getSegment(3), Url::getSegment(4)),

);
