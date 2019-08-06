<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><? echo $data['nav']; ?></div>
<h1><? echo $data['title']; ?></h1>
<ul>
<?echo $data['descr'];?>
</ul>
