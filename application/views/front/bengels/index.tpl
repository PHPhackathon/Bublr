{capture 'content'}

	{* content *}
	<div id="content">
	
		{* articles *}
		{loop $articles}
			<div class="item">
				<h2><a href="{$__.config.siteUrl}bengels/{$quicklink}">{$title|escape}</a></h2>
				<div class="markup">
					{if $image_filename}
						<a href="{$__.config.siteUrl}bengels/{$quicklink}">
							<img class="thumbnail" src="{$__.config.siteUrl}images/thumbnail_rotated/articles/{$image_filename}" width="120" height="120" alt="{$image_alt|escape}" />
						</a>
					{/if}
					<p>{if $description}{$description|escape|nl2br}{else}{$content|strip_tags|truncate:300}{/if}</p>
				</div>
				<a class="button right" href="{$__.config.siteUrl}bengels/{$quicklink}">Lees verder &raquo;</a>
			</div>
		{/loop}
		{* articles *}

	</div>
	{* content *}
	
{/capture}
{include file=$templatePath|cat:'default.tpl'}