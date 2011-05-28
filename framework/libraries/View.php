<?php
/****************************************************
 * Lean mean web machine
 *
 * View library. Subclass for Dwoo
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-17
 *
 ****************************************************/

require_once FRAMEWORK_PATH.'libraries/Dwoo_1.1.1/dwooAutoload.php';
class View extends Dwoo
{
	
	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		
		// Configure Dwoo
		$this->cacheDir		= CACHE_PATH.'templates/cached/';
		$this->compileDir	= CACHE_PATH.'templates/compiled/';
		
	}

}
