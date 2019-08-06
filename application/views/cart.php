<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1><? echo $data['title']; ?></h1>
<?
if (isset($data['cart']))
{?>
<div class="row">
<div class="col-md-9">
<div class="cart-table">
<!--заголовки -->
<div class="cart-table-row">
<div class="cart-table-cell cart-item-img th">
	Фото
</div>
<div class="cart-table-cell cart-item-title th">
	Нименование
</div>
<div class="cart-table-cell cart-item-price th">
	Цена
</div>
<div class="cart-table-cell cart-item-count th">
	Количество
</div>
<div class="cart-table-cell cart-item-coast th">
	Стоимость
</div>
<div class="cart-table-cell cart-item-delete th">
	Удалить
</div>
</div>
	<?foreach($data['cart'] as $row) 
	{?>
		<div id="<?echo $row["key"];?>" class="cart-table-row j-minicart-item j-minicart-item-<?echo $row["id"];?>">
			<div class="cart-table-cell cart-item-img">
				<img src="<?echo $row["img"];?>" alt='<?echo $row["title"];?>'>
			</div>
			<div class="cart-table-cell cart-item-title">
				<a href="<?echo $row["url"];?>"><?echo $row["title"];?></a>
				<span class="addition"><?echo $row["options_name"];?></span>
			</div>
			<div class="cart-table-cell cart-item-price">
				<span class="j-price-by-item"><?echo number_format($row["price"],0,' ',' ');?></span> <span class="rur">₽</span>
			</div>
			<div class="cart-table-cell cart-item-count">
				<form method="post" class="form_shop_el form-inline cart-order order" role="form">
					<input type="hidden" name="key" value="<?echo $row["key"];?>">

					<div class="form-group">
						<div class="count-view-block">
						<button class="form-item btn j-minus js-mcart-minus minus" type="button" name="addCart" value="cart/change">&ndash;</button><input type="text" name="count" value="<?echo $row["count"];?>" class="form-item j-result result" pattern="[0-9]+" maxlength="2" autocomplete="off" title="Введите число больше 0"> шт. <button class="form-item btn j-plus js-mcart-plus plus" type="button" name="addCart" value="cart/change">+</button>
						</div>
						<button id="refresh" class="btn btn-default btn-change" type="submit" name="addCart" value="cart/change">
						</button>
					</div>
				</form>
			</div>			
			<div class="cart-table-cell cart-item-coast">
				<span class="cost-right"><span class="j-cost"><?echo number_format($row["coast"],0,' ',' ');?></span>&nbsp;<span class="rur">₽</span>
			</div>
			<div class="cart-table-cell cart-item-delete">
				<form method="post" class="form_shop_el b-close-wrapper">
					<input type="hidden" name="key" value="<?echo $row["key"];?>">
					<button id="delete_item" class="close1" data-item="<?echo $row["key"];?>" type="submit" name="addCart" value="cart/remove" title="Удалить">&times;</button>
				</form>
			</div>
			</div>
	<?}?>
<!-- итого-->
<div class="cart-table-row">
<div class="cart-table-cell cart-item-img th">

</div>
<div class="cart-table-cell cart-item-title th">
	Итого
</div>
<div class="cart-table-cell cart-item-price th">
</div>
<div class="cart-table-cell cart-item-count th">
	<span class="ms2_total_count"><?echo $data["total_count"];?></span> шт.
</div>
<div class="cart-table-cell cart-item-coast th">
	<span class="ms2_total_cost"><?echo number_format($data["total_coast"],0,' ',' ');?></span>&nbsp;<span class="rur">₽</span>
</div>
<div class="cart-table-cell cart-item-delete th">
</div>
</div>
</div>

	<!--form method="post" class="form_shop_el">
		<div class="text-right">
			<button class="btn btn-default" type="submit" name="addCart" value="cart/clean" title="Очистить корзину"><i class=" -remove"></i> Очистить корзину</button>
		</div>
	</form-->
</div>
<div class="col-md-3">
<div class="form_order">
	<h3>Офорление заказа</h3>
	<?if ($head["is_user"])
	{?>			
	<form method="post" id="form_order_ajax">
		<label>
			<!--span>Телефон <span class="error">*</span></span-->
			<input type="text" name="login" class="form" value='<?=(isset($data["user"]["login"]))?$data["user"]["login"]:""?>' required placeholder="* Контактное лицо">
		</label>
		<label>
			<!--span>Телефон <span class="error">*</span></span-->
			<input type="tel" name="phone" class="form" value='<?=(isset($data["user"]["tel"]))?$data["user"]["tel"]:""?>' required placeholder="* Телефон">
		</label>
		
		<label>
		<select name="city" class="form">
			<option value="Белгород">Белгород</option>
		</select>
		</label>
		<label>
		<input type='text' name='street' class="form" value='<?=(isset($data["user"]["fstreet"]))?$data["user"]["fstreet"]:""?>' placeholder="* Введите улицу" required /> 		
		</label>
		<label>
		<input type='text' name='dom' class="form" value='<?=(isset($data["user"]["fdom"]))?$data["user"]["fdom"]:""?>' placeholder="Введите номер дома" />
		</label>
		<label>
		<input type='text' name='office' class="form" value='<?=(isset($data["user"]["foffice"]))?$data["user"]["foffice"]:""?>' placeholder="Введите номер офиса" /> 
		</label>
		<label>
			<!--span>Дополнительная информация к заказу</span-->
			<textarea name="comment" class="form" value="" placeholder="Дополнительная информация к заказу"></textarea>
		</label>
		<div id="result_order" class="error"></div>
		<button type="submit">Оформить заказ</button>
	</form>
	<?}else
	{?>
		<p>Что бы оформить заказ</p>
		<a href="/login/">Войдите</a> или <a href="/login/registration">Зарегистрируйтесь</a>
	<?}?>
</div>
</div>
</div>
<?}
else
{?>
	<p>Ваша корзина пуста</p>
<?}
?>
