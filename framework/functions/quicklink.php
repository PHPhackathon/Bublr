<?php
/****************************************************
 * Lean mean web machine
 *
 * Quicklink function
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

function quicklink($str){

	$oStr = $str;
	$sep = '-';
	$utf8 = (mb_detect_encoding($str) === 'UTF-8') ? true : false;
	$enc = $utf8 ? 'u' : '';

	$trans = array(
		'[ÀÁÂÃÄÅàáâãäåĂăĄąāĀ]'		=> 'a',
		'[Ææ]'						=> 'ae',
		'[çĆćĈĉĊċČč]'				=> 'c',
		'[ďĐđ]'						=> 'd',
		'[ÈÉÊËèéêëĒēĔĕĖėĘęĚě]'		=> 'e',
		'[ĜĝĞğĠġĢģ]'				=> 'g',
		'[ĤĥĦħ]'					=> 'h',
		'[ìíîïÏÍÌÎĨĩĪīĬĭĮįİı]'		=> 'i',
		'[Ĵĵ]'						=> 'j',
		'[Ĳĳ]'						=> 'ij',
		'[Ķķĸ]'						=> 'k',
		'[ĹĺĻļĽľĿŀŁł]'				=> 'l',
		'[ñÑŃńŅņŇňŉŊŋ]'				=> 'n',
		'[ÒÓÔÕÖØòóôõöøŌōŎŏŐő]'		=> 'o',
		'[Œœ]'						=> 'oe',
		'[ŔŕŖŗŘř]'					=> 'r',
		'[ŚśŜŝŞşŠš]'				=> 's',
		'[ŢţŤťŦŧ]'					=> 't',
		'[ÙÚÛÜùúûüŨũŪūŬŭŮůŰűŲų]'	=> 'u',
		'[Ŵŵ]'						=> 'w',
		'[ÿýŶŷŸ]'					=> 'y',
		'[ŹźŻżŽž]'					=> 'z',
		'[€]'						=> 'euro',
		'[£]'						=> 'pound',
		'[$]'						=> 'dollar',
		'\s+'						=> $sep,
		'[^a-z0-9'.$sep.']'			=> '',
		$sep.'+'					=> $sep,
		$sep.'$'					=> '',
		'^'.$sep					=> ''
	);

	$str = utf8_encode(strtolower(utf8_decode($str)));

	foreach ($trans as $key => $val){
		$key = utf8_decode(utf8_encode($key));
		$str = preg_replace("#".$key."#".$enc, $val, $str);
	}

	return $str;
}