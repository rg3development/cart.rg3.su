<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;charset=utf-8" />
	<title>RG3 CMS</title>
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
		<form action="<?=base_url();?>admin/auth/login/" method="post" class="admin_auth">
			<table>
				<tr><td></td><td><h2>Авторизуйтесь</h2></td><td></td></tr>
				<tr>
					<td></td>
					<td id="warning-box">
						<?=!empty($auth_fail) ? $auth_fail : '';?>
						<?=validation_errors();?>
					</td>
					<td></td>
				</tr>
				<tr class="auth_inputs_tr"><td>имя пользователя</td><td><input tabindex="1" name="login" type="text" class="textfield login" value="логин" onclick="if (this.value=='логин') this.value='';" /></td><td><input type="submit" class="admin_auth_button" value="авторизоваться" /></td></tr>
				<tr class="auth_inputs_tr"><td>пароль</td><td><input tabindex="2" name="password" type="password" class="textfield password" value="пароль" onclick="if (this.value == 'пароль') this.value='';" /></td><td><input type="button" class="admin_auth_button" value="забыли пароль?" onclick="location.href='/admin/auth/forgetpass'" /></td></tr>
			</table>
		</form>
		<div class="clear"></div>
	</div>
	<div id="footer"><img src="/www_admin/img/footer_1.5.png" alt="RG3 Development" title="RG3 Development" /></div>
</body>
</html>