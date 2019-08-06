<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<h1><? echo $data['title']; ?></h1>
<p><span class="date"><?=$data['news_date']?></span></p>
<p>
<? echo $data['text'];?>
</p>