<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><?echo $data["nav"];?></div>
<h1><? echo $data['title']; ?></h1>
<? echo $data['text'];?>
<?
if (isset($data['row']))
{?>
	<div class="row">
	<?foreach($data['row'] as $row) 
	{?>
	<div class="col-md-4">
	<div class="action">
		<a href="<?=$row['link']?>">
			<img alt="<?=$row['title']?>" src="<?=$row['filename']?>">
		</a>
		<div class="view">
			<div class="vertical-align-table">
				<div class="vertical-align-cell">
					<p class="description"><?=$row['title']?></p>
					<div class="readmore borber-r10 bg-transparent bg-transparent-gray margin_bottom5">
						<div class="borber-r8 bg-grad-blue">
							<a href="<?=$row['link']?>">Подробнее</a>
						</div>
					</div>
				</div>
			</div>
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