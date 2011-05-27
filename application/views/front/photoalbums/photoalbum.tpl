{capture 'content'}

	{* content *}
	<div id="content">
	
		{* photoalbum years *}
		<ul class="tabs">
			{loop $photoalbumYears}
				{if $__.year == $year}
					<li class="active">{$year}</li>
				{else}
					<li><a href="{$__.config.siteUrl}fotos/{$year}">{$year}</a></li>
				{/if}
			{/loop}
		</ul>
		{* photoalbum years *}

		{* photoalbum *}
		<div class="item">
			<h2>{$photoalbum.title|escape} - <em>{$photoalbum.date|date_format:"%d/%m/%Y"}</em></h2>
			{if $photoalbum.description}
				<div class="markup">
					<p>{$photoalbum.description|escape|nl2br}</p>
				</div>
			{/if}
			<div class="images">
				<ul>
					{loop $photoalbum.images}
						<li>
							<a href="{$__.config.siteUrl}images/fancybox/photoalbums/{$filename}" class="fancybox" rel="photoalbum_{$photoalbum.id}" title="{$alt|escape}">
								<img src="{$__.config.siteUrl}images/thumbnail_rotated/photoalbums/{$filename}" width="120" height="120" alt="{$alt|escape}" />
							</a>
						</li>
					{/loop}
				</ul>
			</div>
			<a class="button clear" href="{$__.config.siteUrl}fotos/{$photoalbum.date|date_format:'%Y'}">&laquo; Terug naar overzicht</a>
		</div>
		{* photoalbums *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}