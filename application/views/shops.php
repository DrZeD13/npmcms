<div class="row">
<div class="col-md-12">

<div class="nav"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1><? echo $data['title']; ?></h1>
<?
if (isset($data['table_row']))
{?>
<div class="row">
	<?
	$i=1;
	foreach($data['table_row'] as $row) 
	{?>
		<div class="col-lg-3 col-md-6 col-sm-6 col-product1">
			<div class="product_el">
			<? if(!empty($row['filename'])):?>
			<a href="<?=$row['link']?>">
				<img alt="<?=$row['title']?>" src="<?=$row['filename']?>">
			</a><br>
			<?endif?>
			<a href="<?=$row['link']?>" class="catalog-title"><?=$row['title']?></a>
			<div class="description"><?=$row['short_text']?></div>
		</div>
		</div>
	<? if (($i%3) == 0)
	{?>
		<div class="delimiter space"></div>
	<?}
	$i++;
	}?>	
</div>
<?}?>

		<?
if ((isset($data['product_row'])) or (isset($data['product_empty'])))
{?>
<div class="row">
<div id="catalog-overlay"></div>
<?
	 if (isset($data['filter']) && count($data['filter']) > 0)
	 {?>
<div class="col-lg-3 filter">	
		 <?foreach ($data['filter'] as $key => $value)
		 {?>
			 <div class="filter-block" data-fitlerurl="<?=$value['url']?>">
			 <h4><?=$key?></h4>
			 <?
				// ширина для блока с продукцией
				$div_col_lg = 9;
				// ширина для одной продукции
				$div_col_lg_grid = 4;
				$i = 1;
				foreach ($value['item'] as $key_item => $value_item)
				{
					if (($value_item["count"] > 0) or ($value_item["active"]))
					{
					?>
					<div class="filter-item <?=($value_item["count"] > 0)?"":"filter-disabled"?>">
					<input name="<?=$value['url'].$i?>" type="checkbox" <?=($value_item["active"])?"checked":""?> id="<?=$value['url'].$key_item?>" data-fitlervalue="<?=(isset($value_item["id"]))?$value_item["id"]:$key_item?>"	<?=(($value_item["count"] > 0) or ($value_item["active"]))?"":"disabled"?>>
					<label for="<?=$value['url'].$key_item?>"><?=$value_item["name"]?> (<?=$value_item["count"]?>)</label>
					</div>
				<?$i++;
					}
				}?>
				</div>			 
		 <?}?>
		<button id="filter_view" class="form-item buy">Показать</button>
		<a id="filter_clear" href="#">Очистить фильтр</a>	 
</div>
<?}
else{$div_col_lg = 12;$div_col_lg_grid = 3;}	?>
<div class="col-lg-<?=$div_col_lg?>">
<? if (isset($data['sort_row']))
{?>
<div class="block-sort">
	Сортировать: 
	<?foreach($data['sort_row'] as $row) 
	{?>
		<a href="<?=$row["sort_link"]?>" class="sort-link <?=$row["sort_active"]?>"><?=$row["sort_name"]?> <?=$row["sort_dir"]?></a>
	<?}?>
</div>
<?}?>
<?if (isset($data['product_row']))
{?>
<div class="row row-product">	
	<?	
	$i=1;
	foreach($data['product_row'] as $row) 
	{?>
		<div class="col-lg-<?=$div_col_lg_grid?> col-md-4 col-sm-6 col-product">
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
				<div class="description"><?=$row['short_text']?></div>										
			</div>							
		</div>
	<? if (($i%2) == 0)
	{?>
		<!--<div class="delimiter space"></div>-->			
	<?}
	$i++;
	}?>
	
</div>
<?}
else
{?>
	По вашему запросу ни чего не найдено
<?}?>

<? if (isset($data['pages'])) 
{?>
	<? echo $data["pages"];?>
<?}?>
</div>
</div>
<?}?>

<? echo $data['descr'];?>
</div>
</div>