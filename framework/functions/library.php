<?php
/****************************************************
 * Lean mean web machine
 *
 * Helper function to load libraries
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function library($library, $newInstance = false){	
	return Loader::getLibrary($library, $newInstance);
}