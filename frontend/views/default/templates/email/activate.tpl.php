<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Sikeres regisztráció</title>
</head>
<body style="margin: 0;padding: 0;" bgcolor="#FFFFFF">
<table width="650" border="0" cellspacing="0" cellpadding="0" bgcolor="#f0f0f0">
	<tr>
		<td width="650" align="center">
			<br><br>
			<span style="font-family:Helvetica, sans-serif; font-size: 26px; color: #414141;">
				<b>Kedves <?php echo $name; ?></b>
			</span>
			<br><br>
			<span style="font-family:Helvetica, sans-serif; font-size: 16px; color: #414141;">
				Sikeresen regisztráltál az oldalon.<br>
				Az alábbi linkre kattintva aktiválhatod a fiókodat:
			</span>
			<br>
			<br>
			<a style="font-family:Helvetica, sans-serif; font-size: 16px; color: #414141; text-decoration: none;" href="<?php echo site_url('email-activate/'. $id .'/'. $activation) ?>" target="_blank">AKTIVÁCIÓ</a>
			<br><br>
		</td>
	</tr>
</table>
</body>
</html>