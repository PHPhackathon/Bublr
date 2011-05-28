<?php
/****************************************************
 * Lean mean web machine
 *
 * Helper function to output encoded JSON data
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function output_json_encode($data){	
	exit(json_encode($data));
}