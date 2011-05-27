<?php
/****************************************************
 * Lean mean web machine
 *
 * Vardump function
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function vardump($var, $exit = false, $max_depth = 8){
	
	echo '<pre style="margin:0 0 5px; display:block; padding:5px; background:#FFF; color:#333; border:1px dotted #555; clear:both;">';
	var_dump($var);
	echo '</pre>';
	if($exit) exit;
			
}