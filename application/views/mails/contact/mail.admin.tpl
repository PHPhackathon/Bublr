{capture 'content'}

<h2>{$subject|escape}</h2>

<p>Beste,</p>
<p>Het contactformulier op {$config.siteName|escape} werd ingevuld:</p>

<table>
	<tr>
		<th align="left">Voornaam:</th>
		<td>{$firstname|escape}</td>
	</tr>
	<tr>
		<th align="left">Achternaam:</th>
		<td>{$lasstname|escape}</td>
	</tr>
	<tr>
		<th align="left">E-mail:</th>
		<td>{$email|escape}</td>
	</tr>
	<tr>
		<th align="left">Telefoon:</th>
		<td>{$phone|escape}</td>
	</tr>
	<tr>
		<th align="left" valign="top">Bericht:</th>
		<td>{$message|strip_tags|escape|nl2br}</td>
	</tr>
</table>
		
{/capture}
{include file=$templatePath|cat:'default.tpl'}