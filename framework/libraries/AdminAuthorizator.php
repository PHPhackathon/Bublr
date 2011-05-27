<?php
/****************************************************
 * Lean mean web machine
 *
 * HTTP authorizator for Admin
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-22
 *
 ****************************************************/

class AdminAuthorizator
{

	/**
	 * Require login
	 * Validate session or show 401 HTTP login
	 *
	 * @return true on success
	 */
	public static function requireLogin(){

		// Validate existing login by either user agent hash or session id and ip
		$hash = md5(UserAgent::ip().$_SERVER['HTTP_USER_AGENT']);
		if(isset($_SESSION['adminLogin'])){
			if($hash === $_SESSION['adminLogin']) return true;
			if(Input::get('session_id') && UserAgent::Ip() === $_SESSION['adminIp']) return true;
		}

		// Validate login headers and redirect to admin home
		if(isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])){
			if($id = model('AdministratorModel')->adminValidateLogin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])){
				$_SESSION['adminLogin']	= $hash;
				$_SESSION['adminId']	= $id;
				$_SESSION['adminIp']	= UserAgent::ip(); 
				model('AdministratorModel')->save(array(
					'id'			=> $id,
					'last_login'	=> date('Y-m-d H:i:s')
				));
				redirect(ApplicationConfig::$adminUrl);
			}
		}
		
		// Show HTTP login
		header(sprintf('WWW-Authenticate: Basic realm="%1$s CMS"', ApplicationConfig::$siteName));
		header('HTTP/1.0 401 Unauthorized');
		die('Inloggen vereist');	
	}
	
	/**
	 * Get id of logged in admin
	 *
	 * @return int
	 */
	public static function getAdministratorId(){
		if(isset($_SESSION['adminId'])){
			return $_SESSION['adminId'];
		}
		return null;
	}
	
}
