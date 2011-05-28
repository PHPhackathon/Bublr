<?php
/****************************************************
 * Lean mean web machine
 *
 * Library to safely handle uploaded files
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-01
 *
 ****************************************************/

class FileUpload
{

	/**
	 * Save uploaded file to new location
	 *
	 * @param string $directory
	 * @param array $file
	 * @return string $filename
	 */
	public static function saveUpload($directory, $file){

		// Create path with directory
		$path = RESOURCES_PATH.'files/'.basename($directory).'/';
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		
		// Generate unique filename
		$filename = self::generateUniqueFilename($path, self::sanitizeFilename($file['name']));
		
		// Move uploaded file
		move_uploaded_file($file['tmp_name'], $path.$filename);
		return $filename;
	}
	
	/**
	 * Delete uploaded file from filesystem
	 *
	 * @param string $directory
	 * @param array $filename
	 * @return void
	 */
	public static function deleteUpload($directory, $filename){
		@unlink(RESOURCES_PATH.'files/'.basename($directory).'/'.$filename);
	}

	/**
	 * Return unique filename
	 *
	 * @param string $path
	 * @param string $file
	 * @param int $suffix
	 * @return string Unique filtered filename
	 *
	 * @notice We assume that $file has already been sanitized. See sanitizeFilename for sanitizing $file.
	 * @notice Recursive function
	 */
	public static function generateUniqueFilename($path, $file, $suffix = null){

		// Check if current file does not exist
		if(!is_file($path.$file)){
			return $file;
		}

		// Find file extension and base name
		$extensionPos = strrpos($file, '.');
		if($extensionPos === false){
			$fileExtension = '';
			$fileBase = $file;
		}else{
			$fileExtension = substr($file, $extensionPos);
			$fileBase = substr($file, 0, $extensionPos);
		}

		// Add or increment suffix
		if($suffix === null){
			$uniqueFile = $fileBase . '-1' . $fileExtension;
			$suffix = 1;
		}else{
			$suffixPos = strrpos($fileBase, '-');
			$uniqueFile = substr($fileBase, 0, $suffixPos) . '-' . (int)++$suffix . $fileExtension;
		}
		return self::generateUniqueFilename($path, $uniqueFile, $suffix);
	}

	/**
	 * Return sanitized filename. If sanitizing is impossible, return timestamp hash as last resort
	 *
	 * @param string $filename
	 * @return string
	 */
	public static function sanitizeFilename($filename){

		// Create quicklink from each filename part
		$filenamePartsSanitized = array();
		$filenameParts = explode('.', $filename);
		foreach($filenameParts as $filenamePart){
			$filenamePart = quicklink($filenamePart);
			if(!empty($filenamePart)){
				array_push($filenamePartsSanitized, $filenamePart);
			}
		}

		// Return sanitized string or timestamp hash
		if(empty($filenamePartsSanitized)){
			return md5(time());
		}
		return implode('.', $filenamePartsSanitized);
	}
	
	/**
	 * Determine file extension
	 *
	 * @param string $filename
	 * @return string | null
	 */
	public static function getExtension($filename){
		$extension = null;
		$parts = explode('.', $filename);
		if(count($parts) > 1){
			$extension = array_pop($parts);
			if(strlen($extension) === 0 || strlen($extension) > 7){
				$extension = null;
			}
		}
		return $extension;		
	}
}
