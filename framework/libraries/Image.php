<?php
/****************************************************
 * Lean mean web machine
 *
 * Image library to convert and cache images
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-15
 *
 ****************************************************/

require_once FRAMEWORK_PATH.'libraries/WideImage_10.07.31/lib/WideImage.php';
class Image extends WideImage
{

	/**
	 * Convert HEX color code to RGB array
	 *		- array('red' => 255, 'green' => 255, 'blue' => 255)
	 *
	 * @param string $hex
	 *
	 * @return array RGB values
	 *
	 * @notice We assume that $hex is in valid format: #RRGGBB
	 */
	public static function parseHexToRGB($hex){
		return array(
			'red'	=> hexdec(substr($hex, 1, 2)),
			'green'	=> hexdec(substr($hex, 3, 2)),
			'blue'	=> hexdec(substr($hex, 5, 2))
		);
	}

	/**
	 * Load image from by local path. Load error image on error.
	 *
	 * @param string $localPath
	 * @param string $format optional
	 */
	public static function load($localPath, $format = null){
		if(!file_exists($localPath) || is_dir($localPath)){
			$localPath = WEBROOT_PATH.'images/front/error.jpg';
		}
		return parent::loadFromFile($localPath);
	}

	/**
	 * Output saved cache and exit
	 *
	 * @param string $localPath
	 * @param string $type optional
	 * @param int $quality optional
	 * @return void
	 */
	public static function outputCache($localPath, $type = 'jpg'){

		// Get cached file
		$hash = str_split(md5(Url::getSegmentsUrl()), 8);
		$cachedPath = CACHE_PATH.'images/'.$hash[0].'/'.$hash[1].'/'.$hash[2].'/'.$hash[3].'/'.basename($localPath);
		if(file_exists($cachedPath)){

			// Set cache headers
			header('Expires: ' . gmdate('D, d M Y H:i:s e', time() + 2592000));
			header('Cache-Control: max-age=' . 2592000);
			header('Pragma: cache');

			// Output image
			self::load($cachedPath)->output($type);
			exit;
		}
	}

	/**
	 * Save generated image and return cache patch
	 *
	 * @param string $localPath
	 * @param object $image
	 * @return void
	 */
	public static function saveCache($localPath, $image){
		$hash = str_split(md5(Url::getSegmentsUrl()), 8);
		$cachedPath = CACHE_PATH.'images/'.$hash[0].'/'.$hash[1].'/'.$hash[2].'/'.$hash[3].'/'.basename($localPath);
		if(!is_dir(dirname($cachedPath))){
			mkdir(dirname($cachedPath), 0777, true);
		}
		$image->saveToFile($cachedPath);
		return $cachedPath;
	}

	/**
	 * Save uploaded image to new file
	 *
	 * @param string $directory
	 * @param array $image
	 * @return string $filename
	 */
	public static function saveUpload($directory, $image){

		// Create path with directory
		$path = RESOURCES_PATH.'images/'.basename($directory).'/';
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		
		// Generate unique filename
		$filename = self::generateUniqueFilename($path, self::sanitizeFilename($image['name']));
		
		// Move uploaded file
		move_uploaded_file($image['tmp_name'], $path.$filename);
		
		// Resize if larger than allowed
		Loader::loadConfig('ImagesConfig');
		list($width, $height,,) = getimagesize($path.$filename);
		if($width > ImagesConfig::$maxWidth || $height > ImagesConfig::$maxHeight){
			$image = self::load($path.$filename);
			$image->resize(ImagesConfig::$maxWidth, ImagesConfig::$maxHeight)->saveToFile($path.$filename);
		}
		
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
		@unlink(RESOURCES_PATH.'images/'.basename($directory).'/'.$filename);
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
}
