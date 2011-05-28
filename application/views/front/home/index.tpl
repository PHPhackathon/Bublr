{capture 'content'}

	{* content *}
	<div id="content">
	
		{* latest calendar *}
		{if $latestCalendar}
			<div class="item">
				<h2>
					{if $latestCalendar.file_filename}
						<a href="{$__.config.siteUrl}files/download/calendars/{$latestCalendar.file_filename}">
							Activiteiten voor <em>{$latestCalendar.month|date_format:"%B"}</em>
						</a>
					{else}
						Activiteiten voor <em>{$latestCalendar.month|date_format:"%B"}</em>
					{/if}
				</h2>
				<div class="markup">
					{$latestCalendar.description}
				</div>
				{if $latestCalendar.file_filename}
					<a class="button right" href="{$__.config.siteUrl}files/download/calendars/{$latestCalendar.file_filename}">Download kalender <small>(.pdf)</small></a>
				{/if}
			</div>
		{/if}
		{* latest calendar *}
		
		{* articles *}
		{loop $articles}
			<div class="item">
				<h2><a href="{$__.config.siteUrl}artikel/{$quicklink}">{$title|escape}</a></h2>
				<div class="markup">
					{if $image_filename}
						<a href="{$__.config.siteUrl}artikel/{$quicklink}">
							<img class="thumbnail" src="{$__.config.siteUrl}images/thumbnail_rotated/articles/{$image_filename}" width="120" height="120" alt="{$image_alt|escape}" />
						</a>
					{/if}
					<p>{if $description}{$description|escape|nl2br}{else}{$content|strip_tags|truncate:300}{/if}</p>
				</div>
				<a class="button right" href="{$__.config.siteUrl}artikel/{$quicklink}">Lees verder &raquo;</a>
			</div>
		{/loop}
		{* articles *}
		
		{* latest photoalbum *}
		{if $latestPhotoalbum}
			<div class="item">
				<h2>
					<a href="{$__.config.siteUrl}fotos/{$latestPhotoalbum.date|date_format:'%Y'}#album_{$latestPhotoalbum.id}">
						{$latestPhotoalbum.title|escape} - <em>{$latestPhotoalbum.date|date_format:"%d/%m/%Y"}</em>
					</a>
				</h2>
				<div class="images">
					<ul>
						{loop $latestPhotoalbum.images}
							<li>
								<a href="{$__.config.siteUrl}images/fancybox/photoalbums/{$filename}" class="fancybox" rel="photoalbum_{$latestPhotoalbum.id}" title="{$alt|escape}">
									<img src="{$__.config.siteUrl}images/thumbnail_rotated/photoalbums/{$filename}" width="120" height="120" alt="{$alt|escape}" />
								</a>
							</li>
						{/loop}
					</ul>
				</div>
				<a class="button right clear" href="{$__.config.siteUrl}fotos/{$latestPhotoalbum.date|date_format:'%Y'}/{$latestPhotoalbum.quicklink}">Bekijk alle foto's &raquo;</a>
			</div>
		{/if}
		{* latest photoalbum *}
	
	</div>
	{* content *}
	
{/capture}
{include file=$templatePath|cat:'default.tpl'}