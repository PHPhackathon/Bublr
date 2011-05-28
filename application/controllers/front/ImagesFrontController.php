<?php

class ImagesFrontController extends Controller {

	/**
	 * Admin: default imagefield thumbnail
	 */
	public function adminImagefieldThumbnail($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath);
		$image = Image::load($localPath);
		$backgroundColor = $image->allocateColor(Image::parseHexToRGB('#FFFFFF'));
		$image = $image
			->resize(50, 50, 'inside')
			->resizeCanvas(50, 50, 'center', 'center', $backgroundColor);
		$image->output('jpg', 90);
		Image::saveCache($localPath, $image);
	}

	/**
	 * Admin: default imagefield preview
	 */
	public function adminImagefieldPreview($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath);
		$image = Image::load($localPath);
		$backgroundColor = $image->allocateColor(Image::parseHexToRGB('#FFFFFF'));
		$image = $image
			->resize(150, 200, 'inside')
			->resizeCanvas(150, 200, 'center', 'center', $backgroundColor);
		$image->output('jpg', 90);
		Image::saveCache($localPath, $image);
	}

	/**
	 * Admin: default grid thumbnails
	 */
	public function adminGridThumbnail($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath, 'png');
		$image = Image::load($localPath);
		$image = $image
			->autoCrop()
			->resize(50, 50, 'outside')
			->crop('center', 'center', 50, 50);
		$image->output('png');
		Image::saveCache($localPath, $image);
	}

	/**
	 * Admin: default grid preview
	 */
	public function adminGridPreview($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath);
		$image = Image::load($localPath);
		$backgroundColor = $image->allocateColor(Image::parseHexToRGB('#FFFFFF'));
		$image = $image
			->autoCrop()
			->resize(150, 200, 'inside')
			->resizeCanvas(150, 200, 'center', 'center', $backgroundColor);
		$image->output('jpg', 90);
		Image::saveCache($localPath, $image);
	}

	/**
	 * Front: fancybox
	 */
	public function fancybox($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath);
		$image = Image::load($localPath);
		$image = $image
			->resize(800, 800, 'inside', 'down');
		$image->output('jpg', 90);
		Image::saveCache($localPath, $image);
	}

	/**
	 * Front: rotated thumbnail
	 */
	public function thumbnailRotated($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath);
		$image = Image::load($localPath);
		$borderColor = $image->allocateColor(Image::parseHexToRGB('#F7B134'));
		$backgroundColor = $image->allocateColor(Image::parseHexToRGB('#FFFFFF'));
		$image = $image
			->autoCrop()
			->resize(240, 240, 'outside', 'down')
			->crop('center', 'center', 240, 240)
			->resizeCanvas(258, 258, 'center', 'center', $borderColor)
			->rotate(rand(-15, 15), $backgroundColor, false)
			->resize(120, 120, 'inside')
			->resizeCanvas(120, 120, 'center', 'center', $backgroundColor);
		$image->output('jpg', 90);
		Image::saveCache($localPath, $image);
	}
	
	/**
	 * Front: rotated sidebar thumbnail
	 */
	public function thumbnailSidebarRotated($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath);
		$image = Image::load($localPath);
		$borderColor = $image->allocateColor(Image::parseHexToRGB('#F7B134'));
		$backgroundColor = $image->allocateColor(Image::parseHexToRGB('#00887B'));
		$image = $image
			->autoCrop()
			->resize(240, 240, 'outside', 'down')
			->crop('center', 'center', 240, 240)
			->resizeCanvas(258, 258, 'center', 'center', $borderColor)
			->rotate(rand(-15, 15), $backgroundColor, false)
			->resize(60, 60, 'inside')
			->resizeCanvas(60, 60, 'center', 'center', $backgroundColor);
		$image->output('jpg', 90);
		Image::saveCache($localPath, $image);
	}

	/**
	 * Front: medium thumbnail
	 */
	public function thumbnailMedium($relatedTable = null, $filename = null){
		$localPath = RESOURCES_PATH.'images/'.basename($relatedTable).'/'.basename($filename);
		Image::outputCache($localPath);
		$image = Image::load($localPath);
		$image = $image
			->autoCrop()
			->resize(200);
		$image->output('jpg', 90);
		Image::saveCache($localPath, $image);
	}

}