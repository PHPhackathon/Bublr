<?php

class FilesFrontController extends Controller {

	/**
	 * Download file by related table and filename
	 */
	public function download($relatedTable = null, $filename = null){
		$file = model('FileModel')->getRecordByRelatedTableFilename($relatedTable, $filename);
		if($file){
			$localPath = RESOURCES_PATH.'files/'.basename($relatedTable).'/'.basename($filename);
			header('Content-Type: ' . $file['mimetype']);
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.$file['size']);
			readfile($localPath);
			exit;
		}
		frontcontroller('ErrorFrontController')->error404();
	}
}