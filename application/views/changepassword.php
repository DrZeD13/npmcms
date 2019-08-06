<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1><? echo $data['title']; ?></h1>
<div class="row">
<div class="col-lg-3">
	<?include "left_menu_login.php"?>
</div>
<div class="col-lg-9">
<? echo $data['message']; ?>
<form action='' method='post' class='form_order'>
	<div class="lableform">Старый пароль:<span class='error'>*</span></div>
	<input type="password" name="psw" class="form" value="" placeholder="Введите пароль" required> 
	<span class='error'><? echo $data['psw_error']; ?></span><br>
	
	<div class="lableform">Новый пароль:<span class='error'>*</span></div>
	<input type="password" name="psw1" class="form" value="" placeholder="Введите новый пароль" required> 
	<span class='error'><? echo $data['psw1_error']; ?></span><br>
	
	<div class="lableform">Еще раз пароль:<span class='error'>*</span></div>
	<input type="password" name="psw2" class="form" value="" placeholder="Введите новый пароль еще раз" required> 
	<span class='error'><? echo $data['psw2_error']; ?></span><br>

	<div class="lableform"><span class='error'>*</span> - поля обязательные для заполнения</div>
	<input type='submit' name='btn' class="cbutton" value='Отправить' />	
	<input type='hidden' name='action' value='changepassword' />	
</form>
</div>
</div>