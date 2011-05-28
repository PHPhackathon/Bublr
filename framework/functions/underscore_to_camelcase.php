<?php
/****************************************************
 * Lean mean web machine
 *
 * Helper function to transform underscore string to camelcase
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function underscore_to_camelcase($string){
	$cameled = '';
	for($i=0; $i < strlen($string); $i++){
		if($string[$i] == '_'){
			$cameled .= strtoupper($string[$i+1]);
			$i++;
		}else{
			$cameled .= $string[$i];
		}
	}
	return $cameled;
}