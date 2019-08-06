<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="<?=$data['description']?>" />
<title><?=$data['head_title']?></title>
<style>
	@media print {
		.print{display:none;}
	}
</style>
</head>
<body onload="window.print();">
<ul style="margin: 0px; padding: 0px; list-style-type: none;text-align: justify;">
	<li style="margin-bottom: 0px;">
		<h1 itemprop="name"><?echo $data["title"];?></h1>
		<div>
			<b>Категория:</b> <a href="<?echo $data["link_cat"];?>"><span itemprop="recipeCategory"><?echo $data["name_cat"];?><span></a>	| <b>Опубликовано:</b> <?echo $data["date_day"];?>-<?echo $data["date_month"];?>-<?echo $data["date_year"];?> | <b>Просмотров:</b> <?echo $data["views"];?>	
		</div>
	</li>	
	<li style="padding: 0;">
		<img src="<?echo $data["filename"];?>" alt="<?echo $data["title"];?>" title="<?echo $data["title"];?>" width="300px">
		<p><?echo $data["short_text"];?></p>
			<i><b>Ингредиенты рецепта:</b></i>
			<ul style="padding-bottom: 0;">
				<ul class="none">
				<?if (isset($data['ingridients']))
				{			
				foreach($data['ingridients'] as $row) 
				{?>
					<li itemprop="ingredients"><?echo $row["row_ingridient"];?></li>
				<?}
				}?>
			</ul>
			</ul>
			<br>
		<i><b>Способ приготовления:</b></i>
		<? preg_match_all("~<p class=\"print\">(.*?)<\/p>~",$data["text"],$temp);
		if (isset($temp[0][0]))
		{
			echo str_replace($temp[0][0], "", $data["text"]);
		}
		else
		{
			echo $data["text"];	
		}?>
		<center><i><b>Приятного аппетита!</b></i></center>
    </li>   
		<li>Это и другие рецепты найдёте на сайте <a href="/">CookJoy.ru</a></li>
</ul>

</boby>
</html>