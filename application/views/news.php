<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><?echo $data["nav"];?></div>
<h1><? echo $data['title']; ?></h1>
<?
if (isset($data['article_row']))
{
	foreach($data['article_row'] as $row) 
	{?>
				<div class="row">
				<div class="col-md-4">
					<div class="work-post-gal">
						<?if (!empty($row['filename'])):?>
						<a href="<?=$row['link']?>"><img alt="" src="<?=$row['filename']?>" alt="<?=$row['title']?>"></a>
						<?endif;?>
					</div>
					<div class="work-post-content">
						<h5><?=$row['title']?></h5>
						<span><?=$row['news_date']?></span>
					</div>
					<a href="<?=$row['link']?>">Подробнее...</a>
				</div>
				</div>
	<?}
}
else
{?>
	<p><?=$data['empty_row']?></p>
<?}?>

<? if (isset($data['pages'])) 
{?>
<p>
	<? echo $data["pages"];?>
</p>
<?}?>