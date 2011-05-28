{capture 'content'}

	{* content *}
	<div id="content">

		{* confirmation *}
		<div class="item">
			<h2>404 Not Found</h2>
			<div class="markup">
				<p>Helaas pindakaas, deze pagina bestaat niet (meer).</p>
				<p>Tip: ga naar onze <a href="{$__.config.siteUrl}">homepage</a> en probeer opnieuw!</p>
			</div>
		</div>
		{* confirmation *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}