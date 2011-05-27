<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="nl">
<head>

	{* meta data *}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$config.siteName|escape} - Admin</title>
	<link rel="shortcut icon" href="{$config.siteUrl}images/admin/general/favicon.ico" />

	{* css styles *}
	<link rel="stylesheet" type="text/css" href="{$config.siteUrl}javascript/libraries/ext-3.3.0/resources/css/ext-all.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$config.siteUrl}css/admin/screen.css" media="screen" />

	{* scripts *}
	<script type="text/javascript">var ApplicationConfig = {$config|json_encode};</script>
	<script type="text/javascript">var PHPSessionId = '{session_id()}';</script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/ext-3.3.0/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/ext-3.3.0/ext-all-debug.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/ext-3.3.0/locale/ext-lang-nl.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/ckeditor-3.4.2/ckeditor.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/admin/adminmanager.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/admin/index.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}admin/javascript"></script>

	{* awesome uploader *}
	<link rel="stylesheet" type="text/css" href="{$config.siteUrl}javascript/libraries/awesome-uploader-1/AwesomeUploader.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$config.siteUrl}javascript/libraries/awesome-uploader-1/Ext.ux.form.FileUploadField.css" media="screen" />
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/awesome-uploader-1/Ext.ux.form.FileUploadField.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/awesome-uploader-1/Ext.ux.XHRUpload.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/awesome-uploader-1/swfupload.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/awesome-uploader-1/swfupload.swfobject.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/awesome-uploader-1/AwesomeUploader.js"></script>

</head>
<body>

	{* header *}
	<div id="header" class="x-hidden">
		<h1>
			<a href="{$config.siteUrl}">
				<img src="{$config.siteUrl}images/admin/general/logo.png" alt="{$config.siteName|escape}" />
			</a>
		</h1>

		<a href="http://www.bytelogic.be" target="_blank" class="logo_right">
			<img src="{$config.siteUrl}images/admin/general/bytelogic.png" alt="Bytelogic" />
		</a>
	</div>
	{* header *}

</body>
</html>