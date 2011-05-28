<?php
/****************************************************
 * Lean mean web machine
 *
 * Loader for libraries, helpers, controllers and models
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class Loader
{

	protected static $libraryInstances		= array();
	protected static $controllerInstances	= array();
	protected static $modelInstances		= array();
		
	/**
	 * Autoloader for commont base classed
	 *
	 * @param string $className
	 * @return void
	 */
	public static function autoloadClass($className){
		try{
			self::loadLibrary($className);
		}catch(LoaderException $e){}
	}

	/**
	 * Load helper functions
	 *
	 * @param string $function1
	 * @param string $function2 optional
	 * @param string ...
	 * @return void
	 */
	public static function loadFunctions($function1){
		$functions = func_get_args();
		foreach($functions as $function){
			if(function_exists($function)) continue;
			if(file_exists(APPLICATION_PATH.'functions/'.$function.'.php')){
				require_once APPLICATION_PATH.'functions/'.$function.'.php';
			}elseif(file_exists(FRAMEWORK_PATH.'functions/'.$function.'.php')){
				require_once FRAMEWORK_PATH.'functions/'.$function.'.php';
			}else{
				throw new LoaderException(sprintf('Loading of function "%1$s" failed', $function));
			}
		}
	}
	
	/**
	 * Load config class
	 *
	 * @param string $className
	 * @return void
	 */
	public static function loadConfig($className){
		if(class_exists($className, false)) return;
		if(file_exists(CONFIG_PATH.$className.'.php')){
			require_once CONFIG_PATH.$className.'.php';
		}else{
			throw new LoaderException(sprintf('Loading of config "%1$s" failed', $className));
		}
	}
	
	/**
	 * Load library class
	 *
	 * @param string $libraryPath
	 * @return void
	 */
	public static function loadLibrary($libraryPath){
		$className = basename($libraryPath);
		if(class_exists($className, false)) return;
		$path = ($className == $libraryPath)? 'libraries/'.$className.'.php' : 'libraries/'.$libraryPath.'/'.$className.'.php';
		if(file_exists(APPLICATION_PATH.$path)){
			require_once APPLICATION_PATH.$path;
		}elseif(file_exists(FRAMEWORK_PATH.$path)){
			require_once FRAMEWORK_PATH.$path;
		}else{
			throw new LoaderException(sprintf('Loading of library "%1$s" failed', $libraryPath));
		}
	}
	
	/**
	 * Load front controller class
	 *
	 * @param string $className
	 * @return void
	 */
	public static function loadFrontController($className){
		if(class_exists($className, false)) return;
		if(file_exists(APPLICATION_PATH.'controllers/front/'.$className.'.php')){
			require_once APPLICATION_PATH.'controllers/front/'.$className.'.php';
		}else{
			throw new LoaderException(sprintf('Loading of front controller "%1$s" failed', $className));
		}
	}
	
	/**
	 * Load admin controller class
	 *
	 * @param string $className
	 * @return void
	 */
	public static function loadAdminController($className){
		if(class_exists($className, false)) return;
		if(file_exists(APPLICATION_PATH.'controllers/admin/'.$className.'.php')){
			require_once APPLICATION_PATH.'controllers/admin/'.$className.'.php';
		}else{
			throw new LoaderException(sprintf('Loading of admin controller "%1$s" failed', $className));
		}
	}
	
	/**
	 * Load model class
	 *
	 * @param string $className
	 * @return void
	 */
	public static function loadModel($className){
		if(class_exists($className, false)) return;
		if(file_exists(APPLICATION_PATH.'models/'.$className.'.php')){
			require_once APPLICATION_PATH.'models/'.$className.'.php';
		}else{
			throw new LoaderException(sprintf('Loading of model "%1$s" failed', $className));
		}
	}
	
	/**
	 * Get library class instance and store instance to self::$libraryInstances
	 *
	 * @param string $libraryPath
	 * @param boolean $newInstance optional default false
	 * @return object
	 */
	public static function getLibrary($libraryPath, $newInstance = false){
		self::loadLibrary($libraryPath);
		$className = basename($libraryPath);
		if(!$newInstance && isset(self::$libraryInstances[$libraryPath])){
			$instance = self::$libraryInstances[$libraryPath];
		}else{
			$instance = new $className;
			self::$libraryInstances[$libraryPath] = $instance;
		}
		return $instance;
	}
	
	/**
	 * Get front controller class instance and store instance to self::$controllerInstances
	 *
	 * @param string $className
	 * @param boolean $newInstance optional default false
	 * @return object
	 */
	public static function getFrontController($className, $newInstance = false){
		self::loadFrontController($className);
		if(!$newInstance && isset(self::$controllerInstances[$className])){
			$instance = self::$controllerInstances[$className];
		}else{
			$instance = new $className;
			self::$controllerInstances[$className] = $instance;
		}
		return $instance;
	}
	
	/**
	 * Get admin controller class instance and store instance to self::$controllerInstances
	 *
	 * @param string $className
	 * @param boolean $newInstance optional default false
	 * @return object
	 */
	public static function getAdminController($className, $newInstance = false){
		self::loadAdminController($className);
		if(!$newInstance && isset(self::$controllerInstances[$className])){
			$instance = self::$controllerInstances[$className];
		}else{
			$instance = new $className;
			self::$controllerInstances[$className] = $instance;
		}
		return $instance;
	}
	
	/**
	 * Get model class instance and store instance to self::$modelInstances
	 *
	 * @param string $className
	 * @param boolean $newInstance optional default false
	 * @return object
	 */
	public static function getModel($className, $newInstance = false){
		self::loadModel($className);
		if(!$newInstance && isset(self::$modelInstances[$className])){
			$instance = self::$modelInstances[$className];
		}else{
			$instance = new $className;
			self::$modelInstances[$className] = $instance;
		}
		return $instance;
	}

}
