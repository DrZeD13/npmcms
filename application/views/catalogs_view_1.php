<div class="row">
<div class="col-md-3">
	<h2>Каталог</h2>
	<ul class="catalog_menu">
	<?echo $data["catalog_ul"];?>
	</ul>
</div>
<div class="col-md-9">
<div class="nav"><?echo $data["nav"];?></div>
<h1><?echo $data["title"];?></h1>
		<img src="<?echo $data["filename"];?>" alt="<?echo $data["title"];?>" title="<?echo $data["title"];?>">
		<div style="clear: both;"></div>
		<br>
		<?echo $data["text"];?>
</div>
</div>