<?php
/****************************************************
 * Lean mean web machine
 *
 * Helper function to load admin controllers
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function admincontroller($controller, $newInstance = false){	
	return Loader::getAdminController($controller, $newInstance);
}