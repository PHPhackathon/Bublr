<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$subject|escape}</title>
</head>

<body font-size="11px" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
	{literal}
	<style>
		body { padding:0; margin: 0;}
		body,td { color: #242424; font-size: 12px; line-height: 17px; font-family: Arial,Helvetica,sans-serif }
		p { font-size: 12px; line-height: 17px; }
		h1 { color:#56034D; font-size: 20px; margin: 0; font-weight: bold; }
		a { text-decoration: underline; color: #EF7216; }
		a:hover { text-decoration: none; }

		.header { border-bottom: 1px solid #555; }
		.header div.title{ font-size:28px; color:#42B4E3; font-weight:15%; }
		.footer a { color:#56034D; }
	</style>
	{/literal}

	<table width="550" cellpadding="5" cellspacing="0" align="center" >
		<tr>
			<td height="50" class="header" valign="bottom">
				<h1>{$config.siteName|escape}</h1>
			</td>
		</tr>
		<tr>
			<td>
				{$.capture.content}
			</td>
		</tr>
		<tr>
			<td height="50" class="footer">
				Met vriendelijke groeten,<br />
				<a href='{$config.siteUrl}'>{$config.siteName|escape}</a>
			</td>
		</tr>
	</table>

</body>
</html>