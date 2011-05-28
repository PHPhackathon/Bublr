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
	
	// Themes / categorie uploader
	array('/themes/coolblue', 'ThemesFrontController', 'coolblue'),
	array('/themes/update', 'ThemesFrontController', 'update'),
	
	array('/products/:number:', 'BublFrontController', 'products', Url::getSegment(1)),
	array('/products/:number:/:number:', 'BublFrontController', 'products', Url::getSegment(1), Url::getSegment(2)),
	array('/category/all', 'CategoryFrontController', 'all' ),
	

	// Calendars
	array('/activiteiten', 'CalendarsFrontController', 'index'),
	array('/activiteiten/:number:', 'CalendarsFrontController', 'index', Url::getSegment(1)),

	// About
	array('/over-ons', 'AboutFrontController', 'index'),
	array('/over-ons/:string:', 'AboutFrontController', 'article', Url::getSegment(1)),

	// Bengels
	array('/bengels', 'BengelsFrontController', 'index'),
	array('/bengels/:string:', 'BengelsFrontController', 'article', Url::getSegment(1)),

	// Photoalbums
	array('/fotos', 'PhotoalbumsFrontController', 'index'),
	array('/fotos/:number:', 'PhotoalbumsFrontController', 'index', Url::getSegment(1)),
	array('/fotos/:number:/:string:', 'PhotoalbumsFrontController', 'photoalbum', Url::getSegment(1), Url::getSegment(2)),

	// Members
	array('/kern', 'MembersFrontController', 'index'),

	// Links
	array('/links', 'LinksFrontController', 'index'),

	// Contact
	array('/contact', 'ContactFrontController', 'index'),
	array('/contact/succes', 'ContactFrontController', 'success'),

	// Newsletter subscribe
	array('/nieuwsbrief', 'NewslettersFrontController', 'subscribe'),

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
