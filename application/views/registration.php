<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<?/*$form = $data["form_send"];
	if (!empty($form["message"]))
	{
		echo $form["message"]; //сообщение об отправки письма
		$action = $form["action"];
		$form = array ("action" => $action, "error_captcha" =>"");//обнуляем формы оставля только действие
	}*/?>
<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1>Регистрация</h1>
<form action='' method='post' class="form_order">
	
	<div class="lableform">Название организации:<span class='error'>*</span></div>
	<input type='text' name='company' class="form" value='<? echo $data['company']; ?>' placeholder="Введите название организации"  required /> 
	<span class='error'><? echo $data['company_error']; ?></span>	
	
	<div class="lableform">E-mail адрес:<span class='error'>*</span></div>
	<input type='email' name='email' class="form" value='<? echo $data['email']; ?>' placeholder="Введите e-mail"  required pattern="^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" /> 
	<span class='error'><? echo $data['email_error']; ?></span>
	
	<div class="lableform">ИНН организации:<span class='error'>*</span></div>
	<input type='text' name='inn' class="form" value='<? echo $data['inn']; ?>' placeholder="Введите ИНН организации"  required /> 
	<span class='error'><? echo $data['inn_error']; ?></span>
	
	<div class="lableform">КПП организации:</div>
	<input type='text' name='kpp' class="form" value='<? echo $data['kpp']; ?>' placeholder="Введите КПП организации" /> 
	<span class='error'><? echo $data['kpp_error']; ?></span>
	
	<div class="yaddress" style="margin-top: 10px;">
		<h6>Юридический адрес:</h6>
		<div class="lableform">Город:<span class='error'>*</span></div>
		<select name="ycity" class="form">
			<option value="Белгород">Белгород</option>
		</select>
		<span class='error'><? echo $data['ycity_error']; ?></span>
		
		<div class="lableform">Улица:<span class='error'>*</span></div>
		<input type='text' name='ystreet' class="form" value='<? echo $data['ystreet']; ?>' placeholder="Введите улицу" required /> 
		<span class='error'><? echo $data['ystreet_error']; ?></span>
		
		<div class="dom-office">
		<div class="dom-office-item">
			<div class="lableform">Дом:</div>
			<input type='text' name='ydom' class="form" value='<? echo $data['ydom']; ?>' placeholder="Введите номер дома" /> 
		</div>
		
		<div class="dom-office-item">
			<div class="lableform">Офис:</div>
			<input type='text' name='yoffice' class="form" value='<? echo $data['yoffice']; ?>' placeholder="Введите номер офиса" /> 
		</div>
		</div>
		
	</div>
	
	<div class="filter-item">
	<input type='checkbox' class="form form_checkbox" name='y_eqv_f' id="y_eqv_f" value='1' /> <label for="y_eqv_f">- Юридический адрес совпадает с фактическим</label>
	</div>
	<div id="faddress">
	<span class='error'><? echo $data['faddress_error']; ?></span>	
	<div class="yaddress">
		<h6>Фактический адрес:</h6>
		<div class="lableform">Город:<span class='error'>*</span></div>
		<select name="fcity" class="form">
			<option value="Белгород">Белгород</option>
		</select>
		<span class='error'><? echo $data['fcity_error']; ?></span>
		
		<div class="lableform">Улица:<span class='error'>*</span></div>
		<input type='text' name='fstreet' class="form" value='<? echo $data['fstreet']; ?>' placeholder="Введите улицу"/> 
		<span class='error'><? echo $data['fstreet_error']; ?></span>
		
		<div class="dom-office">
		<div class="dom-office-item">
			<div class="lableform">Дом:</div>
			<input type='text' name='fdom' class="form" value='<? echo $data['fdom']; ?>' placeholder="Введите номер дома" /> 
		</div>
		
		<div class="dom-office-item">
			<div class="lableform">Офис:</div>
			<input type='text' name='foffice' class="form" value='<? echo $data['foffice']; ?>' placeholder="Введите номер офиса" /> 
		</div>
		</div>
	</div>
	
	
	</div>
	<div class="lableform">Расчетный счет:<span class='error'>*</span></div>
	<input type='text' name='rs' class="form" value='<? echo $data['rs']; ?>' placeholder="Введите расчетный счет"  required /> 
	<span class='error'><? echo $data['rs_error']; ?></span>
	
	<div class="">
	<div class="lableform">БИК:<span class='error'>*</span></div>
	<input type='text' name='bik' class="form" value='<? echo $data['bik']; ?>' placeholder="Введите БИК"  required /> <button id="buttonbik">Получить</button>
	<span class='error'><? echo $data['bik_error']; ?></span>
	</div>
	
	<div id="bank">
	<div class="lableform">Наименование банка, корр.счет, расположение банка:<span class='error'>*</span></div>	
	<textarea name='bank' rows='6' cols='30' class="form" placeholder="Введите наименование банка, корр.счет, расположение банка" required><? echo $data['bank']; ?></textarea>
	<span class='error'><? echo $data['bank_error']; ?></span>
	</div>
	
	<div class="lableform">Контактное лицо:<span class='error'>*</span></div>
	<input type="text" name="login" class="form" value="<? echo $data['login']; ?>" placeholder="Введите контактное лицо" required pattern="[а-яА-Яa-zA-Z0-9_-]{3,32}"> 
	<span class='error'><? echo $data['login_error']; ?></span><br>
	
	<div class="lableform">Телефон:<span class='error'>*</span></div>
	<input type='tel' name='tel' class="form" value='<? echo $data['tel']; ?>' placeholder="Введите телефон"  required /> 
	<span class='error'><? echo $data['tel_error']; ?></span>
	
	<div class="lableform">Пароль:<span class='error'>*</span></div>
	<input type="password" name="psw" class="form" value="" placeholder="Введите пароль" required> <span class='error'><? echo $data['psw_error']; ?></span><br>
	<div class="lableform">Пароль еще раз:<span class='error'>*</span></div>
	<input type="password" name="psw1" class="form" value="" placeholder="Введите пароль еще раз" required> <span class='error'><? echo $data['psw1_error']; ?></span><br><br>
	<span class='error'><?=$data["error_captcha"]?></span>
	<div class="g-recaptcha" data-sitekey="<?=reCAPTCHA_KEY?>"></div>
	<div class="lableform"><span class='error'>*</span> - поля обязательные для заполнения</div>
	<input type='submit' name='btn' class="cbutton" value='Отправить' />	
	<input type='hidden' name='action' value='registrationOn' />	
	<br>
	<br>
	<div class="alert alert-warning">
	Введенные Вами данные будут использоваться для заполнения счетов и платежных документов. В случае некорректного заполнения реквизитов организации, счета и другие документы будут выписаны неверно и не имеют юридической силы
	</div>
</form>