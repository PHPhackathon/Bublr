<?php
/****************************************************
 * Lean mean web machine
 *
 * Helper function to load front controllers
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function frontcontroller($controller, $newInstance = false){	
	return Loader::getFrontController($controller, $newInstance);
}