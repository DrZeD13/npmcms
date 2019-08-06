<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<?if (isset($data['partners']))
{
foreach($data['partners'] as $row) 
{?>
	<div class="work">
		<a href="<?=$row['link']?>"><img alt="<?=$row['title']?>" src="<?=$row['filename']?>">
		</a>
	</div>						
<?}
}?>		