<?php
/****************************************************
 * Lean mean web machine
 *
 * Input validator for POST requests
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-09
 *
 ****************************************************/

class Validator
{

	protected $rules			= array('post' => array(), 'get' => array(), 'file' => array());
	protected $errors			= array();
	protected $currentSource	= null;
	protected $currentKey		= null;

	/**
	 * Magic function to attach ValidatorRules function to list of rules
	 * Make sure that called function exists in either ValidatorRulesBase or ValidatorRules
	 *
	 * @param mixed zero or more parameters
	 * @return $this
	 */
	public function __call($function, $parameters){
		$this->rules[$this->currentSource][$this->currentKey][$function] = $parameters;
		return $this;
	}

	/**
	 * Start adding rules for POST key
	 * Reset key rules if rules already exist
	 *
	 * @param string $key
	 * @return $this
	 */
	public function registerPost($key){
		$this->currentSource = 'post';
		$this->currentKey = $key;
		$this->rules['post'][$key] = array();
		return $this;
	}
	
	/**
	 * Start adding rules for GET key
	 * Reset key rules if rules already exist
	 *
	 * @param string $key
	 * @return $this
	 */
	public function registerGet($key){
		$this->currentSource = 'get';
		$this->currentKey = $key;
		$this->rules['get'][$key] = array();
		return $this;
	}
	
	/**
	 * Start adding rules for FILES key
	 * Reset key rules if rules already exist
	 *
	 * @param string $key
	 * @return $this
	 */
	public function registerFile($key){
		$this->currentSource = 'file';
		$this->currentKey = $key;
		$this->rules['file'][$key] = array();
		return $this;
	}

	/**
	 * Validate registered rules
	 *
	 * @return bool
	 */
	public function validate(){

		// Return false if POST is empty
		if(!Input::posted()) return false;

		// Loop over each source and keys and execute rules
		$validatorRules = library('ValidatorRules');
		foreach($this->rules as $source => $sourceRules){
			foreach($sourceRules as $key => $rules){
				foreach($rules as $function => $parameters){
					switch($source){
						case 'post':	$value = Input::post($key); break;
						case 'get':		$value = Input::get($key); break;
						case 'file':	$value = Input::file($key); break;
					}				
					array_unshift($parameters, $value);
					$result = call_user_func_array(array($validatorRules, $function), $parameters);
					if($result !== true){
						$this->errors[$key] = $result;
						break;
					}
				}
			}
		}
		
		// Return result
		return empty($this->errors);
	}

	/**
	 * Get collected errors
	 *
	 * @return array
	 */
	public function getErrors(){
		return $this->errors;
	}
	
	/**
	 * Get collected errors for use in admin
	 *
	 * @return array
	 */
	public function getAdminErrors(){
		$data = array();
		foreach($this->errors as $key => $message){
			array_push($data, array(
				'id'	=> $key,
				'msg'	=> $message
			));
		}
		unset($error);
		return $data;
	}

	/**
	 * Reset errors and registered rules
	 *
	 * @return $this
	 */
	public function reset(){
		$this->rules = array('post' => array(), 'get' => array(), 'file' => array());
		$this->errors = array();
		$this->currentSource = null;
		$this->currentKey = null;
		return $this;
	}
}
