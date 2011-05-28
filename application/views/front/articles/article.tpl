{capture 'content'}

	{* content *}
	<div id="content">

		{* article *}
		<div class="item">
			<h2>{$article.title|escape}</h2>
			<div class="markup">
				{foreach item="image" from=$article.images}
					<a href="{$__.config.siteUrl}images/fancybox/articles/{$image.filename}" class="fancybox" rel="article_{$article.id}" title="{$image.alt|escape}">
						<img class="thumbnail_medium" src="{$__.config.siteUrl}images/thumbnail_medium/articles/{$image.filename}" width="200" alt="{$image.alt|escape}" />
					</a>
				{/foreach}
				{$article.content}
			</div>
		</div>
		{* article *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl' pageTitle=''}