{capture 'content'}

	{* content *}
	<div id="content">

		{* error *}
		{if $errors}
			<p class="error">Het formulier is niet correct ingevuld. Gelieve alle waarden te controleren.</p>
		{/if}
		{* error *}

		{* form *}
		{assign var="prefix" value="field"}
		<form action="" method="post">
			<fieldset>

				{* name *}
				{assign var="field" value="name"}
				{assign var="label" value="Naam"}
				{assign var="required" value=true}
				<div class="field {if $errors.$field}error{/if}">
					<label for="{$prefix}_{$field}">{$label|escape}{if $required}*{/if}:</label>
					<input id="{$prefix}_{$field}" type="text" name="{$field}" value="{$.post[$field]|escape}" />
					{if $errors.$field}<span class="error">{$errors.$field}</span>{/if}
				</div>
				{* name *}

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
		
				{* submit *}
				<div class="button">
					<button type="submit">Inschrijven</button>
				</div>
				{* submit *}
		
			</fieldset>
		</form>
		{* form *}

	</div>
	{* content *}

{/capture}
{include file=$templatePath|cat:'default.tpl'}
