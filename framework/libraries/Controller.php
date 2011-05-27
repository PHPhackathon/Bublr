<?php
/****************************************************
 * Lean mean web machine
 *
 * Base controller library
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-14
 *
 ****************************************************/

class Controller
{

	protected $view;
	protected $templatePath;
	protected $templateData;

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
	
		// Load and configure view
		$this->view = library('View');
		$this->templateData = array();
	
	}
	
	/**
	 * Assign or append page title
	 *
	 * @param string $title
	 * @param boolean $append optional default false
	 * @return void
	 */
	public function setPageTitle($title, $append = false){
		if(!isset($this->templateData['pageTitle']) || !$append){
			$this->templateData['pageTitle'] = array();
		}
		$this->assign('pageTitle', $title, true);
	}
	
	/**
	 * Assign meta description text
	 *
	 * @param string $description
	 * @return void
	 */
	public function setMetaDescription($description){
		$this->assign('metaDescription', $description);
	}
	
	/**
	 * Assign current page
	 * Used in template to determine active menu items etc
	 *
	 * @param string $page
	 * @return void
	 */
	public function setCurrentPage($page){
		$this->assign('currentPage', $page);
	}
	
	/**
	 * Assign or append data to $this->templateData
	 *
	 * @param string $dataKey
	 * @param mixed $data
	 * @param boolean $append optional default false
	 * @return void
	 */
	public function assign($dataKey, $data, $append = false){
		if($append){
			if(!isset($this->templateData[$dataKey]) || !is_array($this->templateData[$dataKey])){
				$this->templateData[$dataKey] = array();
			}
			array_push($this->templateData[$dataKey], $data);
		}else{
			$this->templateData[$dataKey] = $data;
		}
	}
	
	/**
	 * Generate template with $this->templateData
	 *
	 * @param string $template
	 * @param mixed $data optional
	 * @return string
	 */
	public function generateTemplate($template, $data = array()){
		
		// Lower error reporting
		error_reporting(E_ERROR | E_WARNING | E_USER_ERROR | E_USER_WARNING);
		
		// Assign data to view and output template
		$this->assign('templatePath', $this->templatePath);
		$this->assign('config', get_class_vars('ApplicationConfig'));
		$templateData = array_merge($this->templateData, $data);
		return $this->view->get($this->templatePath.$template, $templateData);
	}
	
	/**
	 * Output template with $this->templateData
	 *
	 * @param string $template
	 * @param mixed $data optional
	 * @return void
	 */
	public function display($template, $data = array()){
		echo $this->generateTemplate($template, $data);
	}
	
}
