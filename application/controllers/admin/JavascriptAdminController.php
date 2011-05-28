<?php

class JavascriptAdminController extends AdminController {
	
	/**
	 * Fetch all admin javascript files and output them
	 *
	 */
	public function index(){
		$scriptData = $this->_readDir(WEBROOT_PATH.'javascript/admin');
		exit($scriptData);
	}
	
	protected function _readDir($basePath, $level = 1){
		$dirHandle = opendir($basePath);
		$scriptData = '';
		while($path = readdir($dirHandle)){
			$fullPath = $basePath.'/'.$path;
			if(!in_array($path, array('.', '..'))){
				if(is_dir($fullPath)){
					$scriptData .= $this->_readDir($fullPath, $level+1);
				}elseif(fnmatch('*.js', $path) && $level > 1){
					$scriptData .= file_get_contents($fullPath);
					$scriptData .= "\n\n\n";
				}
			}
		}
		return $scriptData;	
	}
	
}