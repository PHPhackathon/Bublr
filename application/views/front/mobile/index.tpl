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
	<script type="text/javascript">
		Cufon.replace('.vervang');
		function changePage() {
			$.mobile.changePage($('#resultspage'), 'slideup');
		}
		function changePage2() {
			$.mobile.changePage($('#searchpage'), 'slideup');
		}
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
			<p class="vervang">kies een categorie</p>
				<select data-theme="c">
					<option value="" style="font-size:14px;">Categorie</option>
					<option value="" style="font-size:14px;">iPhone</option>
					<option value="" style="font-size:14px;">Samsung Galaxy S</option>
					<option value="" style="font-size:14px;">HTC Hero</option>
					<option value="" style="font-size:14px;">HTC Desire HD</option>
				</select>
				<p class="vervang">en een prijsklasse</p>
				<select data-theme="c" style="">
					<option value="">Prijsklasse</option>
					<option value="">iPhone</option>
					<option value="">Samsung Galaxy S</option>
					<option value="">HTC Hero</option>
					<option value="">HTC Desire HD</option>
				</select>
				<img src="{$__.config.siteUrl}images/mobile/search_button.png" alt="SEARCH" style="margin-top:10px;" onclick="changePage();" />
			</div>
		</div>
	</div>
	{* search page *}

	{* result page *}
	<div data-role="page" data-theme="b" id="resultspage" >
		<div class="centered">
			<img src="{$__.config.siteUrl}images/mobile/arrow.png" alt="UP" style="margin-left:140px;" onclick="changePage2();" />
			<div id="results">
				<div id="productselect">
					<select data-theme="c" style="padding-left:13px;">
						<option value="">Product</option>
						<option value="">iPhone</option>
						<option value="">Samsung Galaxy S</option>
						<option value="">HTC Hero</option>
						<option value="">HTC Desire HD</option>
					</select>
				</div>
				<div class="blokje">
					<img src="{$__.config.siteUrl}images/mobile/statusbar.png"/>
					<div id="indicatie">82%</div>
				</div>
				<div class="blokje">
					<img style="float:left;margin-right:5px;" src="{$__.config.siteUrl}images/mobile/avatar.jpg"/><div><b>dennisjanssen</b><p style="font-size:14px;">Stevig doorwerken op de PHPHackathon! Dit is een lange tweet van meerdere regels.</p></div>
				</div>
				<div class="blokje">
					<img style="float:left;margin-right:5px;" src="{$__.config.siteUrl}images/mobile/avatar.jpg"/><div><p><b>dennisjanssen</b></p><p style="font-size:14px;">Stevig doorwerken op de PHPHackathon!</p></div>
				</div>
				<div class="blokje">
					<img style="float:left;margin-right:5px;" src="{$__.config.siteUrl}images/mobile/avatar.jpg"/><div><p><b>dennisjanssen</b></p><p style="font-size:14px;">Stevig doorwerken op de PHPHackathon!</p></div>
				</div>
				<div class="blokje">
					<img style="float:left;margin-right:5px;" src="{$__.config.siteUrl}images/mobile/avatar.jpg"/><div><p><b>dennisjanssen</b></p><p style="font-size:14px;">Stevig doorwerken op de PHPHackathon!</p></div>
				</div>
			</div>
		</div>
	</div>
	{* result page *}

</body>
</html>