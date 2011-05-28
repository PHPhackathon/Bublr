<?php
/****************************************************
 * Lean mean web machine
 *
 * Basic validator rules
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-09
 *
 ****************************************************/

class ValidatorRulesBase
{

	/**
	 * Required value
	 * Everything except NULL
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function required($value){
		if($value !== null) return true;
		return 'Dit veld is verplicht';
	}

	/**
	 * E-mail address
	 *
	 * @param string $email
	 * @return mixed
	 */
	public function email($email){
		if($email === null) return true;
		if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false) return true;
		return 'Gelieve een geldig e-mailadres op te geven';
	}

	/**
	 * Url
	 *
	 * @param string $url
	 * @return mixed
	 */
	public function url($url){
		if($url === null) return true;
		if(filter_var($url, FILTER_VALIDATE_URL) !== false) return true;
		return 'Gelieve een geldige url op te geven';
	}

	/**
	 * IP (IPV4 and IPV6)
	 *
	 * @param string $ip
	 * @return mixed
	 */
	public function ip($ip){
		if($ip === null) return true;
		if(filter_var($ip, FILTER_VALIDATE_IP) !== false) return true;
		return 'Gelieve een geldige IP adres op te geven';
	}

	/**
	 * Credit card number
	 *
	 * @param int $number
	 * @return mixed
	 */
	public function creditCard($number){
		if($number === null) return true;
		if(preg_match('/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})$/', $number) === 1) return true;
		return 'Gelieve een geldig credit card nummer op te geven';
	}

	/**
	 * Number
	 *
	 * @param mixed $number
	 * @return mixed
	 */
	public function number($number){
		if($number === null || (is_numeric($number))) return true;
		return 'Gelieve een geldig getal op te geven';
	}

	/**
	 * Default date format
	 * Format: Y-m-d
	 *
	 * @param string $date
	 * @return mixed
	 */
	public function date($date){
		if($date === null) return true;
		if(date('Y-m-d', strtotime($date)) == $date) return true;
		return 'Gelieve een geldige datum op te geven. Formaat: JJJJ-MM-DD';
	}

	/**
	 * Minimum numeric value
	 *
	 * @param mixed $number
	 * @param mixed $min
	 * @return mixed
	 */
	public function minValue($number, $min){
		if($number === null || ($number >= $min)) return true;
		return sprintf('Gelieve een waarde van minstens %1$s in te geven', $min);
	}

	/**
	 * Maximum numeric value
	 *
	 * @param mixed $number
	 * @param mixed $max
	 * @return mixed
	 */
	public function maxValue($number, $max){
		if($number === null || ($number <= $max)) return true;
		return sprintf('Gelieve een waarde van maximum %1$s in te geven', $max);
	}

	/**
	 * Input between two numeric values
	 *
	 * @param mixed $number
	 * @param mixed $min
	 * @param mixed $max
	 * @return mixed
	 */
	public function betweenValues($number, $min, $max){
		if($number === null || ($number >= $min && $number <= $max)) return true;
		return sprintf('Gelieve een waarde tussen %1$s en %2$s in te geven', $min, $max);
	}

	/**
	 * Minimum string length
	 *
	 * @param string $value
	 * @param int $min
	 * @return mixed
	 */
	public function minLength($value, $min){
		if($value === null || (strlen($value) >= $min)) return true;
		return sprintf('Gelieve een waarde van minstens %1$s tekens in te geven', $min);
	}

	/**
	 * Maximum string length
	 *
	 * @param string $value
	 * @param int $max
	 * @return mixed
	 */
	public function maxLength($value, $max){
		if($value === null || (strlen($value) <= $max)) return true;
		return sprintf('Gelieve een waarde van maximum %1$s tekens in te geven', $max);
	}

	/**
	 * Exact string length
	 *
	 * @param string $value
	 * @param int $length
	 * @return mixed
	 */
	public function exactLength($value, $length){
		if($value === null || (strlen($value) == $length)) return true;
		return sprintf('Gelieve een waarde van precies %1$s tekens in te geven', $length);
	}

	/**
	 * Match field with other field
	 *
	 * @param string $value
	 * @param string $field
	 * @param string $label optional
	 * @return mixed
	 */
	public function matchField($value, $field, $label = null){
		if($value === Input::post($field)) return true;
		return sprintf('Veld komt niet overeen met "%1$s"', $label? $label : $field);
	}

	/**
	 * Valid image
	 *
	 * @param array $file
	 * @return mixed
	 */
	public function image($file){
		if(empty($file)) return true;
		$imageInfo = @getimagesize($file['tmp_name']);
		if(empty($imageInfo[0])){
			return 'Dit is geen geldige afbeelding';
		}
		return true;
	}

	/**
	 * Valid file with on of the given mime types
	 *
	 * @param array $file
	 * @param string $type1 ...
	 * @return mixed
	 */
	public function fileType($file, $type1){
		if(empty($file)) return true;
		
		// Validate each mime type
		$arguments = func_get_args();
		$matchedExtensions = array();
		for($i = 1; $i < count($arguments); $i++){
			if($file['type'] == $arguments[$i]){
				return true;
			}else{
				$mimeTypeParts = explode('/', $arguments[$i]);
				$matchedExtensions[] = array_pop($mimeTypeParts);
			}
		}
		
		// Return error message with checked types
		return sprintf(
			'Gelieve een bestand van volgend type te uploaden: %1$s',
			strtolower(implode(', ', array_unique($matchedExtensions)))
		);
	}
}
