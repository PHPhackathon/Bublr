<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	{* meta data *}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if $pageTitle}{' - '|implode:$pageTitle} - {/if}{$config.siteName|escape}</title>
	<meta name="keywords" content="{$config.metaKeywords|escape}" />
	<meta name="description" content="{$metaDescription|strip_tags|truncate:200|default:$config.metaDescription|escape}" />
	<meta name="author" content="Bytelogic.be" />
	<meta name="language" content="nl" />
	<link rel="shortcut icon" href="{$config.siteUrl}images/front/favicon.ico?v2" />
	{* meta data *}

	{* stylesheets *}
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="{$config.siteUrl}css/mobile/screen.css" />
	{* stylesheets *}

	{* scripts *}
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>
	<script type="text/javascript" src="http://cufon.shoqolate.com/js/cufon-yui.js?v=1.09i"></script>
	<script type="text/javascript" src="{$__.config.siteUrl}fonts/journal_400.font.js"></script>
	<script type="text/javascript" src="{$__.config.siteUrl}javascript/mobile/general.js"></script>
	<script type="text/javascript">
		Cufon.replace('.vervang');
	</script>
	{* scripts *}

	{* google analytics *}
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '{$googleAnalyticsAccount}']);
		_gaq.push(['_setDomainName', location.hostname]);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	{* google analytics *}

</head>
<body>

	{* search page *}
	<div data-role="page" data-theme="b" id="searchpage" >
		<div class="centered">
			<div>
				<img src="{$__.config.siteUrl}images/mobile/bublr.png" alt="Bubler" style="margin-left:50px;" />
			</div>
			<div id="formdiv">

				{* category select *}
				<p class="vervang">kies een categorie</p>
				<select name="category_id" data-theme="c">
					<option value="">Categorie</option>
					{loop $themes}
						<option value="{$id}">{$title|escape}</option>
					{/loop}
				</select>
				{* category select *}

				{* price select *}
				<p class="vervang">en een prijsklasse</p>
				<select name="price_range" data-theme="c" style="">
					<option value="">Prijsklasse</option>
				</select>
				{* price select *}

				{* search button *}
				<img src="{$__.config.siteUrl}images/mobile/search_button.png" alt="SEARCH" style="margin-top:10px;" id="searchbutton" />
				{* search button *}

			</div>
		</div>
	</div>
	{* search page *}

	{* result page *}
	<div data-role="page" data-theme="b" id="resultspage" >
		<div class="centered">
			<img src="{$__.config.siteUrl}images/mobile/arrow.png" alt="UP" style="margin-left:140px;" id="returnbutton" />
			<div id="results">

				{* bubl select *}
				<div id="productselect">
					<select data-theme="c" name="bubl_id" style="padding-left:13px;">
						<option value="">Product</option>
					</select>
				</div>
				{* bubl select *}

				{* score *}
				<div class="blokje">
					<img src="{$__.config.siteUrl}images/mobile/statusbar.png"/>
					<div id="indicatie">82%</div>
				</div>
				{* score *}

				{* tweets *}
				<div id="tweets">
				</div>
				{* tweets *}

			</div>
		</div>
	</div>
	{* result page *}

</body>
</html>