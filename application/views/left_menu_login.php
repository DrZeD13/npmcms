<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="filter-block">
	<h4>Управление</h4>
	<div class="filter-item ">
		<a href="/login/orders"><i class="fa fa-history"></i> История заказов</a>
	</div>
	<div class="filter-item ">
		<a href="/login/cabinet"><i class="fa fa-user"></i> Настроки профиля</a>
	</div>
</div>