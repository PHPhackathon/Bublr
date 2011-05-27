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

		{* error *}
		{if $errors}
			<p class="error">Het formulier is niet correct ingevuld. Gelieve alle waarden te controleren.</p>
		{/if}
		{* error *}

		{* form *}
		{assign var="prefix" value="field"}
		<form action="" method="post">
			<fieldset>

				{* firstname *}
				{assign var="field" value="firstname"}
				{assign var="label" value="Voornaam"}
				{assign var="required" value=true}
				<div class="field {if $errors.$field}error{/if}">
					<label for="{$prefix}_{$field}">{$label|escape}{if $required}*{/if}:</label>
					<input id="{$prefix}_{$field}" type="text" name="{$field}" value="{$.post[$field]|escape}" />
					{if $errors.$field}<span class="error">{$errors.$field}</span>{/if}
				</div>
				{* firstname *}
				
				{* lastname *}
				{assign var="field" value="lastname"}
				{assign var="label" value="Achternaam"}
				{assign var="required" value=true}
				<div class="field {if $errors.$field}error{/if}">
					<label for="{$prefix}_{$field}">{$label|escape}{if $required}*{/if}:</label>
					<input id="{$prefix}_{$field}" type="text" name="{$field}" value="{$.post[$field]|escape}" />
					{if $errors.$field}<span class="error">{$errors.$field}</span>{/if}
				</div>
				{* lastname *}
		
				{* email *}
				{assign var="field" value="email"}
				{assign var="label" value="E-mail adres"}
				{assign var="required" value=true}
				<div class="field {if $errors.$field}error{/if}">
					<label for="{$prefix}_{$field}">{$label|escape}{if $required}*{/if}:</label>
					<input id="{$prefix}_{$field}" type="text" name="{$field}" value="{$.post[$field]|escape}" />
					{if $errors.$field}<span class="error">{$errors.$field}</span>{/if}
				</div>
				{* email *}
		
				{* phone *}
				{assign var="field" value="phone"}
				{assign var="label" value="Tel / GSM"}
				{assign var="required" value=false}
				<div class="field {if $errors.$field}error{/if}">
					<label for="{$prefix}_{$field}">{$label|escape}{if $required}*{/if}:</label>
					<input id="{$prefix}_{$field}" type="text" name="{$field}" value="{$.post[$field]|escape}" />
					{if $errors.$field}<span class="error">{$errors.$field}</span>{/if}
				</div>
				{* phone *}
		
			</fieldset>
		
			<fieldset class="right">
		
				{* message *}
				{assign var="field" value="message"}
				{assign var="label" value="Bericht"}
				{assign var="required" value=true}
				<div class="field {if $errors.$field}error{/if}">
					<label for="{$prefix}_{$field}">{$label|escape}{if $required}*{/if}:</label>
					<textarea id="{$prefix}_{$field}" name="{$field}" cols="30" rows="5">{$.post[$field]|escape}</textarea>
					{if $errors.$field}<span class="error">{$errors.$field}</span>{/if}
				</div>
				{* message *}
		
				{* submit *}
				<div class="button">
					<button type="submit">Verzenden</button>
				</div>
				{* submit *}
		
			</fieldset>
		</form>
		{* form *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}