<?php
/****************************************************
 * Lean mean web machine
 *
 * Router library to determine controller, function and parameters
 *
 * Admin uses automatic routing based on URL segments:
 *	0		=> fixed value of "admin"
 *	1		=> AdminController name in lowercase minus "AdminController" suffix
 *	2		=> Method name in lowercase underscores
 *	3 etc	=> Method arguments
 *
 *
 * Front makes use of config from application/controllers/routing.php
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class Router
{

	protected $isAdmin;
	protected $controllerClass;
	protected $function;
	protected $parameters;

	/**
	 * Find route based url (admin) or routing.php config (front)
	 *
	 * @return bool
	 */
	public function findRoute(){

		// Determine if admin or front
		$this->isAdmin = !!Url::getAdmin();

		// Find matching controller, function and parameters
		if($this->isAdmin){
			return $this->findAdminRoute();
		}else{
			return $this->findFrontRoute();
		}
	}

	/**
	 * Find admin route based on url
	 * Set protected vars $controllerClass, $function and $parameters on success
	 *
	 * @return bool
	 */
	public function findAdminRoute(){

		// Fetch url segments
		$controller	= ucfirst(underscore_to_camelcase(Url::getController()? Url::getController() : 'home'));
		$function	= underscore_to_camelcase(Url::getFunction()? Url::getFunction() : 'index');
		$parameters	= Url::getParameters();

		// Load controller and find out if function exists
		try{
			Loader::loadLibrary('Controller');
			$controllerClass = $controller.'AdminController';
			Loader::loadLibrary('AdminController');
			Loader::loadAdminController($controllerClass);
			if(!method_exists($controllerClass, $function)){
				throw new RouterException(sprintf('Function "%1$s" does not exist for admin controller "%2$s"', $function, $controllerClass));
			}
		}catch(LoaderException $e){
			return false;
		}catch(RouterException $e){
			return false;
		}

		// Set protected vars and return success
		$this->controllerClass	= $controllerClass;
		$this->function			= $function;
		$this->parameters		= $parameters;
		return true;
	}

	/**
	 * Find front route based on route config
	 * Set protected vars $controllerClass, $function and $parameters on success
	 *
	 * @return bool
	 */
	public function findFrontRoute(){

		// Load route config
		$routing = require APPLICATION_PATH.'/controllers/routing.php';

		// Fetch called URL
		$url = '/'.Url::getSegmentsUrl();

		// Match each route with URL and return on match
		foreach($routing as $route){

			// Build regular expression to match url against
			$routeRegex = sprintf(
				'/^%1$s$/',
				str_replace(
					array('/', ':number:', ':string:'),
					array('\\/', '[0-9]+', '[a-zA-Z0-9_\-.]+'),
					array_shift($route)
				)
			);

			// Match regular expression against url
			if(preg_match($routeRegex, $url) === 1){

				// Load controller and find out if function exists
				$controllerClass	= array_shift($route);
				$function			= array_shift($route);
				$parameters			= $route;
				try{
					Loader::loadLibrary('Controller');
					Loader::loadLibrary('FrontController');
					Loader::loadFrontController($controllerClass);
					if(!method_exists($controllerClass, $function)){
						throw new RouterException(sprintf('Function "%1$s" does not exist for front controller "%2$s"', $function, $controllerClass));
					}
				}catch(LoaderException $e){
					throw new RouterException(sprintf('Front controller "%1$s" does not exist', $controllerClass));
				}

				// Set protected vars and return success
				$this->controllerClass	= $controllerClass;
				$this->function			= $function;
				$this->parameters		= $parameters;
				return true;
			}
		}

		// No matching route found
		return false;
	}

	/**
	 * Execute found route
	 * Make sure findRoute returned true
	 *
	 * @return void
	 */
	public function executeRoute(){
		$instance = $this->isAdmin? Loader::getAdminController($this->controllerClass) : Loader::getFrontController($this->controllerClass);
		call_user_func_array(array($instance, $this->function), $this->parameters);
	}

	/**
	 * Execute "404 not found" route
	 * Make sure that settings in ApplicationConfig are correct
	 *
	 * @return void
	 */
	public function execute404(){
		$controller = Loader::getFrontController(ApplicationConfig::$errorController);
		$function = ApplicationConfig::$error404Function;
		$controller->$function();
	}

	/**
	 * Execute "500 internal server error" route
	 * Make sure that settings in ApplicationConfig are correct
	 *
	 * @return void
	 */
	public function execute500(){
		$controller = Loader::getFrontController(ApplicationConfig::$errorController);
		$function = ApplicationConfig::$error500Function;
		$controller->$function();
	}

}
