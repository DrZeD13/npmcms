<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<?if ($data['slider'])
{?>
<div class="slider">
<div id="owl-slider" class="owl-carousel owl-theme">
<?foreach($data['slider'] as $row) 
{?>
	<div class="item-slide" style="background: url(<?=$row['filename']?>) no-repeat;">
	<div class="slide-text">
		<span><?=$row['title']?></span>
		<p><?=$row['short_text']?></p>
	</div>
	</div>
<?}?>
</div>
</div>
<?}?>