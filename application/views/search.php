<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="breadcrumb-block"><ol class="breadcrumb"><?echo $data["nav"];?></ol></div>
<h1><? echo $data['title']; ?></h1>

<?if (isset($data['row']))
{?>
<h3>По вашему запросу '<?=$data['search']?>' найдено <?=$data['total']?> товар(ов)</h3>
<div class="row row-product">	
	<?	
	$i=1;
	foreach($data['row'] as $row) 
	{?>
		<div class="col-lg-3 col-md-4 col-sm-6 col-product">
			<div class="product_el">
				<? if(!empty($row['filename'])):?>
				<a href="<?=$row['link']?>" class="product-img">
					<img alt='<?=$row['title']?>' src='<?=$row['filename']?>'>
				</a>
				<?endif?>
				<a href="<?=$row['link']?>" class="product-title"><?=$row['title']?></a>
				<?if (!empty($row['price'])):?>
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
				<?endif?>
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
	<?=$data['empty']?>
<?}?>


<? if (isset($data['pages'])) 
{?>
<p>
	<? echo $data["pages"];?>
</p>
<?}?>