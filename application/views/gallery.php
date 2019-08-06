<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<h1><? echo $data['title']; ?></h1>
<?
if (isset($data['article_row']))
{
?>
				<div class="news-bg">
<?
	foreach($data['article_row'] as $row) 
	{?>
				<div class="col-3">
					<div class="work-post">
						<div class="work-post-gal">
							<a href="<?=$row['link']?>"><img alt="" src="<?=$row['filename']?>"></a>
						</div>
						<div class="work-post-content">
							<a href="<?=$row['link']?>"><?=$row['title']?></a>
						</div>
					</div>
				</div>
	

	<?}?>
				</div>
<?}
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