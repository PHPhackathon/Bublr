<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="nl">
<head>

	{* meta data *}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if $pageTitle}{' - '|implode:$pageTitle} - {/if}{$config.siteName|escape}</title>
	<meta name="keywords" content="{$config.metaKeywords|escape}" />
	<meta name="description" content="{$metaDescription|strip_tags|truncate:200|default:$config.metaDescription|escape}" />
	<meta name="author" content="Bytelogic.be" />
	<meta name="language" content="nl" />
	<link rel="shortcut icon" href="{$config.siteUrl}images/front/favicon.ico" />
	{* meta data *}

	{* stylesheets *}
	<link rel="stylesheet" type="text/css" media="screen" href="{$config.siteUrl}css/front/screen.css" />
	{* stylesheets *}

	{* scripts *}
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="{$config.siteUrl}javascript/libraries/jquery-1.4.4/jquery-1.4.4.min.js"%3E%3C/script%3E'))</script>
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

	{* container *}
	<div id="container">

		{* navigation *}
		<div id="navigation">
			<ul>
				<li {if $currentPage == 'home'}class="active"{/if}>
					<a href="{$config.siteUrl}"><span>Home</span></a>
				</li>
				<li {if $currentPage == 'calendars'}class="active"{/if}>
					<a href="{$config.siteUrl}activiteiten"><span>Activiteiten</span></a>
				</li>
				<li {if $currentPage == 'about'}class="active"{/if}>
					<a href="{$config.siteUrl}over-ons"><span>Wie zijn wij?</span></a>
				</li>
				<li {if $currentPage == 'bengels'}class="active"{/if}>
					<a href="{$config.siteUrl}bengels"><span>(B)engels</span></a>
				</li>
				<li {if $currentPage == 'photoalbums'}class="active"{/if}>
					<a href="{$config.siteUrl}fotos"><span>Foto's</span></a>
				</li>
				<li {if $currentPage == 'members'}class="active"{/if}>
					<a href="{$config.siteUrl}kern"><span>Kern</span></a>
				</li>
				<li {if $currentPage == 'links'}class="active"{/if}>
					<a href="{$config.siteUrl}links"><span>Links</span></a>
				</li>
				<li {if $currentPage == 'contact'}class="active"{/if}>
					<a href="{$config.siteUrl}contact"><span>Contact</span></a>
				</li>
			</ul>
		</div>
		{* navigation *}

		{* main *}
		<div id="main">

			{* title *}
			<div class="title">
				<span class="header">KAJ<span>Vostert</span></span>
				{if $pageTitle}<h1>{' - '|implode:$pageTitle}</h1>{/if}
			</div>
			{* title *}

			{* content *}
			{$.capture.content}
			{* content *}

		</div>
		{* main *}

		{* sidebar *}
		<div id="sidebar">

			{* logo *}
			<div class="logo">
				<a href="{$config.siteUrl}" title="Terug naar homepage">
					<img src="{$config.siteUrl}images/front/logo.png" width="279" height="379" alt="KAJ Logo" />
				</a>
			</div>
			{* logo *}

			{* newsletter subscribe *}
			<form action="{$config.siteUrl}nieuwsbrief" method="post" class="newsletters">
				<fieldset>
					<p>Schrijf je in om onze activiteitenkalender ook iedere maand per e-mail te ontvangen!</p>
					<input type="text" name="name" value="Naam" onfocus="this.value='';" />
					<input type="text" name="email" value="E-mail" onfocus="this.value='';" />
					<button type="submit">Verzend</button>
				</fieldset>
			</form>
			{* newsletter subscribe *}

			{* latest calendar *}
			{if $latestCalendarSidebar}
				<div class="item">
					<strong class="title">
						Activiteiten <em>{$latestCalendarSidebar.month|date_format:"%B"}</em>
					</strong>
					<div class="markup">
						{$latestCalendarSidebar.description}
					</div>
					<a class="button right" href="{$__.config.siteUrl}activiteiten">Meer info &raquo;</a>
				</div>
			{/if}
			{* latest calendar *}

			{* latest photoalbum *}
			{if $latestPhotoalbumSidebar}
				<div class="item">
					<strong class="title">
						{$latestPhotoalbumSidebar.title|escape}
					</strong>
					<div class="images">
						<ul>
							{loop $latestPhotoalbumSidebar.images}
								<li>
									<a href="{$__.config.siteUrl}images/fancybox/photoalbums/{$filename}" class="fancybox" rel="latestphotoalbum" title="{$alt|escape}">
										<img src="{$__.config.siteUrl}images/thumbnail_sidebar_rotated/photoalbums/{$filename}" width="60" height="60" alt="{$alt|escape}" />
									</a>
								</li>
							{/loop}
						</ul>
					</div>
					<a class="button right clear" href="{$__.config.siteUrl}fotos/{$latestPhotoalbumSidebar.date|date_format:'%Y'}/{$latestPhotoalbumSidebar.quicklink}">Alle foto's &raquo;</a>
				</div>
			{/if}
			{* latest photoalbum *}

			{* random member *}
			{if $memberSidebar}
				<div class="item">
					<strong class="title">
						Dit is leider <em>{$memberSidebar.firstname|escape}</em>
					</strong>
					<div class="member">
						{if $memberSidebar.image_filename}
							<a href="{$__.config.siteUrl}images/fancybox/members/{$memberSidebar.image_filename}" class="fancybox" title="{$memberSidebar.firstname|escape} {$memberSidebar.lastname|escape}">
								<img src="{$__.config.siteUrl}images/thumbnail_sidebar_rotated/members/{$memberSidebar.image_filename}" width="60" height="60" alt="{$memberSidebar.firstname|escape} {$memberSidebar.lastname|escape}" />
							</a>
						{else}
							<img src="{$__.config.siteUrl}images/thumbnail_sidebar_rotated/members/{$memberSidebar.image_filename}" width="60" height="60" alt="" />
						{/if}
						<address>
							{if $memberSidebar.street}{$memberSidebar.street|escape}<br />{/if}
							{if $memberSidebar.postal_code}{$memberSidebar.postal_code|escape} {$memberSidebar.city|escape}<br />{/if}
						</address>
						{if $memberSidebar.about}
							<p>{$memberSidebar.about|escape}</p>
						{/if}
					</div>
					<a class="button right clear" href="{$__.config.siteUrl}kern">Onze kernleden &raquo;</a>
				</div>
			{/if}
			{* random member *}

			{* our initatives *}
			<div class="item">
				<strong class="title">Onze initatieven</strong>
				<ul class="initiatives">
					<li><a href="http://www.vospop.be" rel="external">Vospop</a></li>
					<li><a href="http://www.refresh-lan.be" rel="external">Refresh-lan</a></li>
					<li><a href="http://www.pinksterfeesten.be" rel="external">Pinksterfeesten</a></li>
				</ul>
			</div>
			{* our initatives *}

		</div>
		{* sidebar *}

	<div class="container_clear"></div></div>
	{* container *}

	{* footer *}
	<div id="footer">
		<p>&copy; Copyright KAJ Vostert {date('Y')}. <a href="{$config.siteUrl}contact">Contacteer ons</a></p>
		<img src="{$config.siteUrl}images/front/ik_ben_uniek_small.png" width="115" height="159" alt="Ik ben uniek" />
	</div>
	{* footer *}

	{* scripts *}
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/front/general.js"></script>
	{* scripts *}

</body>
</html>