<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><?echo $data["nav"];?></div>
<h1><? echo $data['title']; ?></h1>
<div class="full-top"><? echo $data['text']; ?></div>

<?echo $head['code4'];?>

<?
if (isset($data['article_row']))
{
	foreach($data['article_row'] as $row) 
	{?>				
<div class="item news-item1">
	<h5><?=$row['title']?></h5>
	<div class="full-top-blog">
		<div class="info-field"><b>Категория:</b> <a href="<?echo $row["cat_link"];?>"><?echo $row["cat_name"];?></a></div>
		<div class="info-field"><b>Опубликовано:</b> <?echo $row["news_date"];?></div>
		<div class="info-field"><b>Просмотров:</b> <?echo $row["views"];?></div>			
		<div class="info-field"><b>Комментариев:</b> <?echo $row["comments"];?></div>
	</div>
	<?=$row['short_descr']?>
	<a class="cook" href="<?=$row['link']?>">подробнее</a>
</div>
<div class="clear"></div>
	<?}
}
else
{?>
<? if (isset($data['empty_row'])) 
{?>
	<p><? echo $data['empty_row'];?></p>
<?}}?>
<? if (isset($data['pages'])) 
{?>
	<? echo $data["pages"];?>
<?}?>