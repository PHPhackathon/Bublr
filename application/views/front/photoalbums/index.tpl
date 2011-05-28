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

		{* articles *}
		{if $year == date('Y')}
			{loop $articles}
				<div class="item">
					<h2>{$title|escape}</h2>
					<div class="markup">
						{if $image_filename}
							<a href="{$__.config.siteUrl}images/fancybox/photoalbums/{$image_filename}" class="fancybox" rel="articles_{$id}" title="{$image_alt|escape}">
								<img class="thumbnail_medium" src="{$__.config.siteUrl}images/thumbnail_medium/articles/{$image_filename}" width="200" alt="{$image_alt|escape}" />
							</a>
						{/if}
						{$content}
					</div>
				</div>
			{/loop}
		{/if}
		{* articles *}

		{* photoalbums *}
		{loop $photoalbums}
			<div class="item">
				<h2 id="album_{$id}">{$title|escape} - <em>{$date|date_format:"%d/%m/%Y"}</em></h2>
				{if $description}
					<div class="markup">
						<p>{$description|escape|nl2br}</p>
					</div>
				{/if}
				<div class="images">
					<ul>
						{loop $images}
							<li>
								<a href="{$__.config.siteUrl}images/fancybox/photoalbums/{$filename}" class="fancybox" rel="photoalbum_{$_.id}" title="{$alt|escape}">
									<img src="{$__.config.siteUrl}images/thumbnail_rotated/photoalbums/{$filename}" width="120" height="120" alt="{$alt|escape}" />
								</a>
							</li>
						{/loop}
					</ul>
					{if $images_count > 10}
						<a class="button right clear" href="{$__.config.siteUrl}fotos/{$date|date_format:'%Y'}/{$quicklink}">Bekijk alle foto's &raquo;</a>
					{/if}
				</div>
			</div>
		{/loop}
		{* photoalbums *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}