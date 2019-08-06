<div class="row">
<div class="col-md-12">
<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1><?echo $data["title"];?></h1>
<div class="row">
<div class="col-md-6">	
	<? if (isset($data['file']) and (count($data['file']) > 1))
	{?>
		<div id="product-slider">			
		<?foreach ($data['file'] as $file)	
		{?>
			<div class="sp-slide"><a href="<?=$file['big']?>"><img src="<?=$file['big']?>"></a></div>
		<?}?>		
		</div>		
	<?}
	else
	{?>
		<a href="<?=$data['filename']?>"><img src="<?echo $data["filename"];?>" alt='<?echo $data["title"];?>'></a>
	<?}
	?>
			
</div>	
<div class="col-md-6">	
		<?if (($data["price"] > 0) &&  ($data["count"] > 0)) {?>
		<form class="form_shop_el">			
			<div class="order">
				<div class="cost-block">
					<span id="price" class="cost"><?echo number_format($data["price"],0,' ',' ');?></span><span class="cost-ruble"> <span class="rur">₽</span>
				</span></div>
				<div class="count-view-block">
				<button class="form-item btn j-minus minus" type="button">&ndash;</button>
				<input name="count" type="text" class="form-item j-result result" value="1" pattern="[1-9][0-9]*" min="1" maxlength="2" autocomplete="off" title="Число больше 0"> шт. <button class="form-item btn j-plus plus" type="button">+</button>		
				</div>
				<select name="options[size]" class="input-sm form-control" id="option_size" style="display:none;">
					<option value="100">100</option>
					<option value="50">50</option>
				</select><br>		
				
				<input type="hidden" name="id" value="<? echo $data["id"];?>">
				<br>
				<button type="submit" class="form-item buy" name="addCart" value="cart/add">в корзину</button>
			</div>
			Количество на складе: <?echo $data["count"];?>
		</form>
		<?} else {?>
			Нет в наличии
		<?}?>
		<?echo $data["text"];?>
		<div class="additions">
		<?if (isset($data['fields']))
		{?>
			<h3>Характеристики</h3>
			<?
			foreach($data['fields'] as $row)
			{?>
				<dl class="tech-feature">
				<dt>
					<span><?=$row['name']?></span>
				</dt>
				<dd>
					<span><?=$row['value']?></span>
				</dd>	
			</dl>
			<?}
		}?>	
		</div>
</div>
</div>		
	
</div>
</div>

<?if (isset($data['popular']))
{?>
<div class="row">
	<div class="container">
		<h2><?=$data['related_title']?></h2>
		<div class="row row-product">				
	<?foreach($data['popular'] as $row) 
	{?>
		<div class="col-lg-3 col-md-6 col-sm-6 col-product1">
			<div class="product_el">
				<? if(!empty($row['filename'])):?>
				<a href="<?=$row['link']?>" class="product-img">
					<img alt='<?=$row['title']?>' src='<?=$row['filename']?>'>
				</a>
				<?endif?>
				<a href="<?=$row['link']?>" class="product-title"><?=$row['title']?></a>
				<?if (!empty($row['price']) && !empty($row['count'])){?>
					<span id="price" class="cost"><?echo number_format($row['price'],0,' ',' ');?></span> <span class="rur cost-rur">₽</span>
					<form class="form_shop_el">	
					<input name="count" type="hidden" value="1">
					<input type="hidden" name="id" value="<? echo $row["id"];?>">
					<!--select name="options[size]" class="input-sm form-control" id="option_size" style="display:none;">
						<option value="100">100</option>
						<option value="50">50</option>
					</select-->
					<button type="submit" class="form-item buy" name="addCart" value="cart/add">в корзину</button>
					</form>
				<?}else{?>
					Нет в наличии
				<?}?>
				<?/*<div class="description"><?=$row['short_text']?></div>*/?>
			</div>							
		</div>
	<?}?>
	</div>
	</div>
</div>
<?}?>