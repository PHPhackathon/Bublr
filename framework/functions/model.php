<?php
/****************************************************
 * Lean mean web machine
 *
 * Helper function to load models
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function model($model, $newInstance = false){	
	return Loader::getModel($model, $newInstance);
}