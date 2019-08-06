<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1><? echo $data['title']; ?></h1>
<span class="error"><?=$data['error']?></span>
<form action="" method="post" class="form_order">
<div class="lableform">Ваш логин:</div>
<input type='email' name='email' class="form" value="" placeholder="Введите e-mail" />
<div class="lableform">Ваш пароль:</div>
<input type='password' name='pwd' class="form" value="" placeholder="Введите пароль" /><br>
<div class="lableform"><a href="/login/registration">Регистрация</a> &nbsp;&nbsp;&nbsp; <a href="/login/lostpassword">Забыли пароль?</a></div>
<input type="submit" class="cbutton" value="Войти">
<input type="hidden" name="action" value="login">
</form>