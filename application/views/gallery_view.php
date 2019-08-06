<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<h1><? echo $data['title']; ?></h1>
<?
if (isset($data['photos_row']))
{
	foreach($data['photos_row'] as $row) 
	{?>
		<a href="<?=$row['filenamebig']?>" rel="lightbox-one" title="<?=$row['title']?>"><img alt="<?=$row['title']?>" src="<?=$row['filename']?>"class="galleryphoto"></a>
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
