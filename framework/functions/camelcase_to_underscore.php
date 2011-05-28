<?php
/****************************************************
 * Lean mean web machine
 *
 * Helper function to transform camelcase string to underscores
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function camelcase_to_underscore($string){
	$string = strtolower(ereg_replace('([A-Z])', '_\1', $string));
	if(substr($string, 0, 1) == '_') $string = substr($string, 1);
	return $string;
}