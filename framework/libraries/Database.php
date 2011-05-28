<?php
/****************************************************
 * Lean mean web machine
 *
 * Database PDO layer
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-18
 *
 ****************************************************/

class Database extends PDO
{
	
	protected static $instance = null;
	
	/**
	 * Get existing instance or create new instance
	 *
	 * @return Database instance
	 */
	public static function instance(){
		if(self::$instance === null){
		
			// Connect to server
			Loader::loadConfig('DatabaseConfig');
			$dsn = sprintf(
				'%1$s:host=%2$s;port=%3$s;dbname=%4$s;charset=UTF-8',
				DatabaseConfig::$type,
				DatabaseConfig::$server,
				DatabaseConfig::$port,
				DatabaseConfig::$name
			);
			
			try{
				self::$instance = new Database(
					$dsn, 
					DatabaseConfig::$user, 
					DatabaseConfig::$password,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
				);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
				vardump($e);
				die('Database connect failed');
			}
		}
		return self::$instance;
	}
	
	/**
	 * Close existing connection and destroy instance
	 *
	 * @return void
	 */
	public static function close(){
		self::$instance = null;
	}
	
}
