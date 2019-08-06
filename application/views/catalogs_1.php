<?foreach($data['tags_ul'] as $row) 
	{?>
		<div class="tags borber-r10 bg-transparent bg-transparent-gray margin_bottom15">
			<div class="borber-r8 bg-grad-blue">
				<a href="<?=$row["link"]?>" <?=$row["active"]?>><?=$row["title"]?></a>
			</div>
		</div>
	<?}?>
<div class="delimiter"></div>

<div class="nav"><?echo $data["nav"];?></div>


<div class="row">
<div class="col-md-3">
	<h2>Каталог</h2>
	<ul class="catalog_menu">
	<?echo $data["catalog_ul"];?>
	</ul>
</div>
<div class="col-md-9">
<h1><? echo $data['title']; ?></h1>
<?
if (isset($data['table_row']))
{?>
<div class="row">
	<?
	$i=1;
	foreach($data['table_row'] as $row) 
	{?>
		<div class="col-sm-4 align_center">
			<div class="catalog_el">
				<? if(!empty($row['filename'])):?>
				<a href="<?=$row['link']?>">
					<img alt="<?=$row['title']?>" src="<?=$row['filename']?>">
				</a><br>
				<?endif?>
				<a href="<?=$row['link']?>" class="link-title-cat"><?=$row['title']?></a>
				<div class="description"><?=$row['short_text']?></div>
			</div>
		</div>
	<? if (($i%3) == 0)
	{?>
		<div class="delimiter"></div>
	<?}
	$i++;
	}?>	
</div>
<?}?>

		<?
if (isset($data['product_row']))
{?>
<div class="row">
	<?
	$i=1;
	foreach($data['product_row'] as $row) 
	{?>
		<div class="col-sm-6">
			<div class="product_el">
			<div class="row">				
				<div class="col-md-12"><a href="<?=$row['link']?>" class="link-title-cat"><?=$row['title']?></a></div>
				<div class="col-xs-5">
					<? if(!empty($row['filename'])):?>
					<a href="<?=$row['link']?>">
						<img alt="<?=$row['title']?>" src="<?=$row['filename']?>">
					</a>
					<?endif?>
					<br>
					<br>
					<div class="tags borber-r10 bg-transparent bg-transparent-gray margin_bottom15">
					<div class="borber-r8 bg-grad-blue">
						<a href="<?=$row['link']?>">Подробнее</a>
					</div>
				</div>
				</div>
				<div class="col-xs-7">					
					<div class="description"><?=$row['short_text']?></div>
					<?if (!empty($row['price'])):?>
						<span class="price-cat">от <?=$row['price']?> руб.</span>
					<?endif?>
				</div>
			</div>							
			</div>							
		</div>
	<? if (($i%2) == 0)
	{?>
		<!--<div class="delimiter space"></div>-->			
	<?}
	$i++;
	}?>
	
</div>
<?}?>


<? if (isset($data['pages'])) 
{?>
	<? echo $data["pages"];?>
<?}?>
<? echo $data['descr'];?>
</div>
</div>