<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;charset=utf-8" />
	<title>RG3 Development. Панель администрирования сайтом</title>
	<link rel="stylesheet" href="<?= base_url('www_admin/css/style.css'); ?>" type="text/css" media="screen" />
	<!-- [favicon] begin -->
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon0.ico" />
	<link rel="icon" type="image/x-icon" href="img/favicon0.ico" />
	<!-- [favicon] end -->
</head>

<body>
	<div id="top-space"></div>
	<div id="header" style="text-align: center;"><div id="logo" style="float:none!important; margin-left: auto; margin-right: auto;"><a href="/" title=""></a></div></div>
	<div id="content" style="text-align: center;">
		<form action="<?=base_url();?>admin/auth/newpass/" method="post" class="admin_auth">
			<table style="margin-left: auto; margin-right: auto;">
				<tr><td colspan="3" style="text-align: center;"><h2>Cброс пароля</h2></td></tr>
				<tr>
					<td id="warning-box" colspan="3">
						<?=!empty($auth_fail) ? $auth_fail : '';?>
						<?=validation_errors();?>
					</td>
				</tr>
				<tr class="auth_inputs_tr">
					<td>новый пароль</td>
					<td><input name="newpass" type="password" class="textfield" value="" onclick="if (this.value == 'новый пароль') this.value='';" /></td>
					<td><input id="forget-pass" type="submit" class="admin_auth_button button" value="сменить пароль"/></td>
				</tr>
				<tr><td><input name="login" type="hidden" value="<?=$login;?>"/></td></tr>
				<tr><td><input name="hash" type="hidden" value="<?=$hash;?>"/></td></tr>
			</table>
		</form>
		<div class="clear"></div>
	</div>
	<div id="footer"><img src="/www_admin/img/footer_1.5.png" alt="RG3 Development" title="RG3 Development" /></div>
</body>
</html>