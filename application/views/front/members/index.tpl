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

		{* member categories *}
		{loop $categories}
			{if $members}
				<div class="item">
					<h2>{$title|escape}</h2>
					<div class="members">
						<ul>
							{loop $members}
								<li>
									{if $image_filename}
										<a href="{$__.config.siteUrl}images/fancybox/members/{$image_filename}" class="fancybox" rel="members" title="{$firstname|escape} {$lastname|escape}">
											<img src="{$__.config.siteUrl}images/thumbnail_rotated/members/{$image_filename}" width="120" height="120" alt="{$firstname|escape} {$lastname|escape}" />
										</a>
									{else}
										<img src="{$__.config.siteUrl}images/thumbnail_rotated/members/{$image_filename}" width="120" height="120" alt="" />
									{/if}
									<h3>
										{if $email}
											{mailto address=$email encode="javascript" text="`$firstname` `$lastname`"}
										{else}
											{$firstname|escape} {$lastname|escape}
										{/if}
									</h3>
									<address>
										{if $street}{$street|escape}<br />{/if}
										{if $postal_code}{$postal_code|escape} {$city|escape}<br />{/if}
										{if $phone}{$phone|escape}<br />{/if}
									</address>
									{if $about}
										<p>{$about|escape}</p>
									{/if}
								</li>
							{/loop}
						</ul>
					</div>
				</div>
			{/if}
		{/loop}
		{* member categories *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}