<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="main-content">
	<div class="container">
	<div class="row">
	<div class="col-md-12">
	<!--div class="nav"><?echo $data["nav"];?></div-->
	<h1><? echo $data['title']; ?></h1>
	</div>
	</div>
<?
if (isset($data['article_row']))
{?>

		<div class="row">
		<?
		$delay=0;
		foreach($data['article_row'] as $row) 
		{?>
		<a href="<?=$row['link']?>" class="col-sm-3 service-item onscroll-animate" data-animation="zoomIn" data-delay="<?=$delay?>">
			<img src="<?=$row['filename']?>" alt="<?=$row['title']?>" />
			<div class="service-text"><?=$row['title']?></div>
		</a>
		<?
		$delay+=100;
		}?>
		</div>
<?}?>
	</div>
</div>
<? echo $data['text'];?>