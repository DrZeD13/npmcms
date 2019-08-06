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
if (isset($data['table_row']))
{
	foreach($data['table_row'] as $row) 
	{?>
		<div class="shortstory">
		<a href="<?=$row["link"]?>" title="<?=$row["title"]?>"><img src="<?=$row["filename"]?>" alt="<?=$row["title"]?>"></a>
				<a href="<?=$row["link"]?>" title="<?=$row["title"]?>"><?=$row["title"]?></a>
				<div class="customdata">
				<div class="views" title="Просмотров <?=$row["views"]?>"><?=$row["views"]?></div>
				<div class="comms" title="Комментариев <?=$row["comment"]?>"><?=$row["comment"]?></div>
				<div style="float:left; margin-top: -5px;">
					<?=$row["rating"]?>				
				</div>	
				</div>				
				<?=$row["short_text"]?>						
		</div>
		<?=$row["delimiter"]?>
	<?}
}
else
{?>
	<p><? if (isset($data['empty']))
{echo $data['empty'];} ?></p>
<?}


 if (isset($data['pages'])) 
{?>
	<? echo $data["pages"];?>
<?}?>


