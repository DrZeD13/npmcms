<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>

<h1>Личный кабинет</h1>
<div class="row">
<div class="col-lg-3">
	<?include "left_menu_login.php"?>
</div>
<div class="col-lg-9">
<? echo $data["linkpassword"];?>
<? echo $data["linkuseredit"];?><br>
Контактное лицо: <? echo $data["login"];?><br>
Телефон: <? echo $data["tel"];?><br>
E-mail: <? echo $data["email"];?><br>
Компания: <? echo $data["company"];?><br>
ИНН: <? echo $data["inn"];?><br>
КПП: <? echo $data["kpp"];?><br>
Юридический адрес: <? echo $data["yaddress"];?><br>
Фактический адрес: <? echo $data["faddress"];?><br>
Расчетный счет: <? echo $data["rs"];?><br>
БИК: <? echo $data["bik"];?><br>
Наименование банка, корр.счет, расположение банка: <? echo $data["bank"];?><br>
Договор: <?=($data["dogovor"])?"<span class='success'>подписан</span>":"<span class='error'>не подписан</span>"?><br>


<a href="/login/logout">Выход</a>
</div>
</div>