{capture 'content'}

	{* content *}
	<div id="content">

		{* articles *}
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
		{* articles *}

		{* link categories *}
		{loop $categories}
			{if $links}
				<div class="item">
					<h2>{$title|escape}</h2>
					<div class="links">
						<ul>
							{loop $links}
								<li>
									<a href="{$url}" rel="external" title="Ga naar {$url|escape}">
										<img src="{$__.config.siteUrl}images/thumbnail_rotated/links/{$image_filename}" width="120" height="120" alt="{$title|escape}" />
										<span>{$title|escape}</span>
										<em>{$url|parse_url:1|escape}</em>
									</a>
								</li>
							{/loop}
						</ul>
					</div>
				</div>
			{/if}
		{/loop}
		{* link categories *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}