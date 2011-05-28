{capture 'content'}

	{* content *}
	<div id="content">
	
		{* calendar years *}
		<ul class="tabs">
			{loop $calendarYears}
				{if $__.year == $year}
					<li class="active">{$year}</li>
				{else}
					<li><a href="{$__.config.siteUrl}activiteiten/{$year}">{$year}</a></li>
				{/if}
			{/loop}
		</ul>
		{* calendar years *}

		{* articles *}
		{if $year == date('Y')}
			{loop $articles}
				<div class="item">
					<h2>{$title|escape}</h2>
					<div class="markup">
						{if $image_filename}
							<a href="{$__.config.siteUrl}images/fancybox/articles/{$image_filename}" class="fancybox" rel="article_{$id}" title="{$image_alt|escape}">
								<img class="thumbnail_medium" src="{$__.config.siteUrl}images/thumbnail_medium/articles/{$image_filename}" width="200" alt="{$image_alt|escape}" />
							</a>
						{/if}
						{$content}
					</div>
				</div>
			{/loop}
		{/if}
		{* articles *}

		{* calendars *}
		{loop $calendars}
			<div class="item">
				<h2>
					{if $file_filename}
						<a href="{$__.config.siteUrl}files/download/calendars/{$file_filename}">
							<em>{$month|date_format:"%B %Y"|ucfirst}</em>
						</a>
					{else}
						<em>{$month|date_format:"%B %Y"|ucfirst}</em>
					{/if}
				</h2>
				<div class="markup">
					{$description}
				</div>
				{if $file_filename}
					<a class="button right" href="{$__.config.siteUrl}files/download/calendars/{$file_filename}">Download kalender <small>(.pdf)</small></a>
				{/if}
			</div>
		{/loop}
		{* calendars *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}