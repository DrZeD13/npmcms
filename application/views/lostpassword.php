<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1><? echo $data['title']; ?></h1>
<p><? echo $data['message']; ?></p>
<form action="/login/lostpassword" method="post" class="form_order">
	<div class="lableform">E-mail адрес:<span class='error'>*</span></div>
	<input type='email' name='email' class="form" value='<? echo $data['email']; ?>' placeholder="Введите e-mail"  required pattern="^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" /> 
	<span class='error'><? echo $data['email_error']; ?></span><br><br>	
	<input type='submit' name='btn' class="cbutton" value='Отправить' />
	<input type='hidden' name='action' value='lostpasswordOn' />
</from>
