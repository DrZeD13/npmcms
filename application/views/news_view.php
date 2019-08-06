<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><?echo $data["nav"];?></div>
<h1><? echo $data['title']; ?></h1>
<p><span class="date"><?=$data['news_date']?></span></p>
<?if (!empty($data['filename'])) {?>
<p><img src="<?echo $data['filename'];?>" alt="<? echo $data['title']; ?>"></p>
<?} echo $data['descr'];?>
