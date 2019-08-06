<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><ol class="breadcrumb"><? echo $data['nav']; ?></ol></div>
<h1><? echo $data['title']; ?></h1>
<div class="row">
<div class="col-lg-3">
	<?include "left_menu_login.php"?>
</div>
<div class="col-lg-9">
	<?if (isset($data['row'])) {?>
	<?foreach($data['row'] as $row) 
		{?>
		<div class="orders">
			<div class="order-status"><i class="fa fa-circle fa-status-color<?=$row["status-number"]?>"></i> <?echo $row["status"];?></div>
			<div class="order-number">Заказ № <?=$row["id"]?></div>
			<div class="order-date">Дата заказа: <?=$row["date"]?></div>
			<div class="order-address">Адрес доставки: <?=$row["address"]?></div>
			<div class="order-total">Стоимость заказа: <span><?echo number_format($row["sum"],0,' ',' ');?></span> <span class="rur">₽</span> 
			<form action="/bill" method="POST">
			<input type="hidden" name="order" value="<?=$row["id"]?>">
			<button type="submit">
				<svg style="enable-background:new 0 0 128 128;" version="1.1" viewBox="0 0 128 128" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="12px" height="12px">
				<g>
				<path d="M95.21,80.32c-0.07-0.51-0.48-1.15-0.92-1.58c-1.26-1.24-4.03-1.89-8.25-1.95     c-2.86-0.03-6.3,0.22-9.92,0.73c-1.62-0.93-3.29-1.95-4.6-3.18c-3.53-3.29-6.47-7.86-8.31-12.89c0.12-0.47,0.22-0.88,0.32-1.3     c0,0,1.98-11.28,1.46-15.1c-0.07-0.52-0.12-0.67-0.26-1.08l-0.17-0.44c-0.54-1.25-1.6-2.57-3.26-2.5L60.32,41H60.3     c-1.86,0-3.36,0.95-3.76,2.36c-1.2,4.44,0.04,11.09,2.29,19.69l-0.58,1.4c-1.61,3.94-3.63,7.9-5.41,11.39l-0.23,0.45     c-1.88,3.67-3.58,6.79-5.13,9.43l-1.59,0.84c-0.12,0.06-2.85,1.51-3.49,1.89c-5.43,3.25-9.03,6.93-9.63,9.85     c-0.19,0.94-0.05,2.13,0.92,2.68l1.54,0.78c0.67,0.33,1.38,0.5,2.1,0.5c3.87,0,8.36-4.82,14.55-15.62     c7.14-2.32,15.28-4.26,22.41-5.32c5.43,3.05,12.11,5.18,16.33,5.18c0.75,0,1.4-0.07,1.92-0.21c0.81-0.22,1.49-0.68,1.91-1.3     C95.27,83.76,95.43,82.06,95.21,80.32z M36.49,99.33c0.7-1.93,3.5-5.75,7.63-9.13c0.26-0.21,0.9-0.81,1.48-1.37     C41.28,95.72,38.39,98.46,36.49,99.33z M60.95,43c1.24,0,1.95,3.13,2.01,6.07c0.06,2.94-0.63,5-1.48,6.53     c-0.71-2.26-1.05-5.82-1.05-8.15C60.43,47.45,60.38,43,60.95,43z M53.65,83.14c0.87-1.55,1.77-3.19,2.69-4.92     c2.25-4.25,3.67-7.57,4.72-10.3c2.1,3.82,4.72,7.07,7.79,9.67c0.39,0.32,0.8,0.65,1.22,0.98C63.82,79.8,58.41,81.31,53.65,83.14z      M93.08,82.79c-0.38,0.23-1.47,0.37-2.17,0.37c-2.26,0-5.07-1.03-9-2.72c1.51-0.11,2.9-0.17,4.14-0.17     c2.27,0,2.94-0.01,5.17,0.56C93.44,81.4,93.47,82.55,93.08,82.79z" style="fill:#ff0004"/>
				<path d="M104,80c-13.255,0-24,10.745-24,24s10.745,24,24,24s24-10.745,24-24S117.255,80,104,80z      M114.882,96.988l-0.113,0.176l-8.232,11.438C105.989,109.468,105.029,110,104,110s-1.989-0.532-2.536-1.397l-8.346-11.614     c-0.529-0.926-0.524-2.073,0.01-2.994c0.535-0.922,1.53-1.494,2.596-1.494H100V86c0-1.654,1.346-3,3-3h2c1.654,0,3,1.346,3,3v6.5     h4.276c1.065,0,2.061,0.572,2.596,1.494C115.406,94.915,115.411,96.063,114.882,96.988z" style="fill:#ff0004"/>
				<polygon points="84,125.95 83.95,126 84,126     " style="fill:#FF9A30;"/>
				<polygon points="114,77 114,76.95 113.95,77     " style="fill:#FF9A30;"/>
				<path d="M111.071,44.243L71.757,4.929C69.869,3.041,67.357,2,64.687,2H24c-5.514,0-10,4.486-10,10v104      c0,5.514,4.486,10,10,10h59.95l-4-4H24c-3.309,0-6-2.691-6-6V12c0-3.309,2.691-6,6-6h40.687c1.603,0,3.109,0.624,4.242,1.757      l39.314,39.314c1.116,1.117,1.757,2.663,1.757,4.242V72.95l4,4V51.313C114,48.643,112.96,46.132,111.071,44.243z" style="fill:#ff0004"/>
				<polyline points="113.95,77 114,76.95 110,72.95     " style="fill:#FFFFFF;"/>
				</g>
				</svg>
				Скачать счет
			</button>
			</form></div>
			<div class="order-readmore">
				<a href="#">Посмотреть заказ</a>				
			</div>
			<div class="order-item table-responsive">
				<table class="zebra">
					<thead>
					<tr>
						<th>Наименование</th>
						<th>Количетсво</th>
						<th>Цена</th>
						<th width="110px">В корзину</th>
					</tr>
					</thead>
					<tbody>
				<?foreach ($row["order_item"] as $key) {?>
					<tr>
						<td><a href="<?=$key["link"]?>" target="_blank"><?=$key["name"]?></a></td>
						<td><?=$key["quantity"]?> шт.</td>
						<td><?echo number_format($key["price"],0,' ',' ');?>&nbsp;<span class="rur">₽</span></td>
						<td>
						<form class="form_shop_el">	
							<input name="count" type="hidden" value="<?=$key["quantity"]?>">
							<input type="hidden" name="id" value="<?=$key["shop_id"]?>">
							<button type="submit" class="form-item buy" name="addCart" value="cart/add">в корзину</button>
						</form>
					</td>
					</tr>										
				<?}?>
				</tbody>
				</table>
			</div>
		</div>
		<?}?>
	<?}
	else
	{?>
	У вас еще нет ни одного заказа
	<?}?>
<!--

	<?if (isset($data['row'])) {?>
	<div class="cart-table">
	<div class="cart-table-row">
	<div class="cart-table-cell th">
		Дата
	</div>
	<div class="cart-table-cell th">
		Адрес
	</div>
	<div class="cart-table-cell th">
		Сумма
	</div>
	<div class="cart-table-cell th">
		Комментарий
	</div>
	<div class="cart-table-cell th">
		Статус
	</div>
	</div>
		<?foreach($data['row'] as $row) 
		{?>
			<div class="cart-table-row">
				<div class="cart-table-cell">
					<?echo $row["date"];?>
				</div>
				<div class="cart-table-cell">
					<?echo $row["address"];?>
				</div>
				<div class="cart-table-cell">
					<span class="j-price-by-item"><?echo number_format($row["sum"],0,' ',' ');?></span> <span class="rur">₽</span>
				</div>			
				<div class="cart-table-cell">
					<?echo $row["comment"];?>
				</div>
				<div class="cart-table-cell">
					<?echo $row["status"];?>
				</div>
				</div>
		<?}?>
	</div>
	<?}
	else
	{?>
	У вас еще нет ни одного заказа
	<?}?>
-->
	<? if (isset($data['pages'])) 
	{?>
		<? echo $data["pages"];?>
	<?}?>
</div>
</div>