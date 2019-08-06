<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="<?=$data['description']?>" />
<meta name="keywords" content="<?=$data['keywords']?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=$data['head_title']?></title>
<link rel="icon" href="/favicon.png" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" media="screen">
<link rel="stylesheet" type="text/css" href="/css/owl.carousel.css" media="screen">
<link rel="stylesheet" type="text/css" href="/css/styles.css" media="screen">
</head>
<body>
<div class="topline">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="mainWrap">
				<a id="touch-menu" class="mobile-menu" href="#">Меню</a>	
				<nav>
					<ul class="menu">
						<?echo $head['main_top_menu'];?>
				  </ul>
				</nav>      
			</div>
			</div>
		</div>
	</div>
</div>
<div class="headlogo">
	<div class="container">
		<div class="row">
			<div class="col-md-8">				
				<a href="/" class="logo" title="<?=$data['head_title']?>"><img src="/img/logo.png" alt="<?=$data['head_title']?>" /></a>
			</div>
			<div class="col-md-4">
				<div class="searchbox">
					<div class="searchtitle">ПОИСК</div>
					<div class="searchblock">
						<form method="post" action="/search/">						
							<input id="search" name="search" type="search" value="" placeholder="Что ищем?" class="searchform" required />
							<input class="searchbt" type="submit" value="" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="bgcolor">	
	<div class="row">
		<div class="col-md-12">						
			<div class="col-md-9 floatright">
				<h1><?=$data['title']?></h1>
				<div class="full-top">
					<?=$data['text']?>
				</div>					
				<div class="hblock">
					<div class="dayhit">
					   <div class="cheader" style="margin-left:0px;">Рецепт дня:</div>						
						<div class="hitimage">		
							<a href="<?=$data['recipeday_link']?>"><img src="<?=$data['recipeday_img']?>" title="<?=$data['recipeday_tile']?>" alt="<?=$data['recipeday_tile']?>"></a>		
						</div>
						<div class="hit-title">
							<h2><a href="<?=$data['recipeday_link']?>"><?=$data['recipeday_tile']?></a></h2>
						</div>
						<div class="customdata">
							<div class="views" title="Просмотры"><?=$data['recipeday_views']?></div>
							<div class="comms" title="Комментарии"><?=$data['recipeday_comments']?></div>
					   </div>
						<?=$data['recipeday_short_text']?>		
						<a class="cook" href="<?=$data['recipeday_link']?>">Приготовить!</a>
					</div>
					<div class="clear"></div>
				</div>	
				
				<?echo $head['code4'];?>			
				
				<?if (isset($data['recipelast']))
				{?>
				<div class="cblock">
					<div class="cheader">Последние добавления:</div>
					<div id="owl-demo" class="owl-carousel owl-theme">
					<?foreach($data['recipelast'] as $row) 
					{?>
						<div class="item news-item">
							<div class="inner-item">
								<a href="<?=$row['link']?>"><img alt="<?=$row['title']?>" src="<?=$row['filename']?>"></a>
							</div>
							<div class="custom-title"><a href="<?=$row['link']?>"><?=$row['title']?></a></div>
							<div class="customdata">
								<div class="views" title="Просмотры"><?=$row['views']?></div>
								<div class="comms" title="Комментарии"><?=$row['comments']?></div>
								</div>
							</div>
							<?}?>											
					</div>			  		
				</div>	
					<?}?>
					<?if (isset($data['recipetop']))
					{?>
					<div class="cblock">
						<div class="cheader">Популярные рецепты:</div>
						<div id="owl-demo1" class="owl-carousel owl-theme">
						<?foreach($data['recipetop'] as $row) 
						{?>
							<div class="item news-item">
								<div class="inner-item">
									<a href="<?=$row['link']?>"><img alt="<?=$row['title']?>" src="<?=$row['filename']?>"></a>
								</div>
								<div class="custom-title"><a href="<?=$row['link']?>"><?=$row['title']?></a></div>
								<div class="customdata">
									<div class="views" title="Просмотры"><?=$row['views']?></div>
									<div class="comms" title="Комментарии"><?=$row['comments']?></div>
									</div>
								</div>
								<?}?>											
						</div>			  		
					</div>	
			<?}?>
			<?if (isset($data['article_row'])) 
			{?>
			<div class="hblock">
				<div class="dayhit-main">
					<div class="cheader" style="margin-left:0px;">Последние записи в блоге:</div>	
					<?foreach($data['article_row'] as $row) 
					{?>				
					<div class="news-item-main">
						<h5><?=$row['title']?></h5>
						<?=$row['short_descr']?>
						<a class="cook" href="<?=$row['link']?>">подробнее</a>
					</div>
					<div class="clear"></div>
					<?}?>
				</div>
			</div>
			<?}?>
			
			</div>
			<div class="col-md-3">
				<?if (isset($head['cat_link_product']))
				{?>
				<div class="sideblock">
					<div class="categoryheader"><a href="/recipes/">Категории</a></div>
					<div class="category">
						<ul>						
							<?foreach($head['cat_link_product'] as $row) 
							{?>
							<li><a href="<?=$row['link']?>" title="<?=$row['title']?>"><?=$row['title']?></a></li>
							<?}?>						
						</ul>
					</div>
				</div>
				 <?}?>
				 <?if (isset($head['cat_link_article']))
				{?>
				<div class="sideblock">
				  <div class="categoryheader"><a href="/blog/">Блог</a></div>
					<div class="category">
						<ul>						
							<?foreach($head['cat_link_article'] as $row) 
							{?>
							<li><a href="<?=$row['link']?>" title="<?=$row['title']?>"><?=$row['title']?></a></li>
							<?}?>						
						</ul>
					</div>
				</div>
				 <?}?>				 				 
				<!--div class="sideblock">
					<div class="wideheader">
						<div class="widetitle">
							<div class="wtitle">
								Присоединяйся
							</div>
						</div>
					</div>
				<div id="ok_group_widget"></div>
				</div-->
				<?if (isset($head['comments']))
				{?>				
				<div class="sideblock">
					<div class="wideheader">
						<div class="widetitle">
							<div class="wtitle">
								Комментарии
							</div>
						</div>
					</div>	
					<?if (isset($head['comments']))
					{			
					foreach($head['comments'] as $row)
					{?>
					<div class="comment">
							<span style="color: rgb(231, 66, 53);font-size:125%;"><i><?echo $row["name"];?></i></span> / <span style="color: rgb(136, 136, 136);"><?echo $row["date"];?></span>
							<br/><?echo $row["comment"];?> <a href="<?echo $row["link"];?>">перейти</a>
					</div>
					<?}
					}?>
				</div>
				<?}?>	
				<?if (isset($head['recomend']))
				{?>				
				<div class="sideblock">
					<div class="wideheader">
						<div class="widetitle">
							<div class="wtitle">
								Рекомендуем
							</div>
						</div>
					</div>	
					<?foreach($head['recomend'] as $row) 
					{?>
					<div class="custom-2">
						<div class="imgrecomend">
							<a href="<?=$row['link']?>" title="<?=$row['title']?>"><img src="<?=$row['filename']?>" alt="<?=$row['title']?>"></a>
						</div>
						<div class="custom-title">
							<a href="<?=$row['link']?>" title="<?=$row['title']?>"><?=$row['title']?></a>
						</div>
						<div class="customdata" style="margin: 0 auto; width: 50%;">
							<div class="views" title="Просмотров: <?=$row['views']?>"><?=$row['views']?></div>
							<div class="comms" title="Комментариев: <?=$row['comments']?>"><?=$row['comments']?></div>
						</div>
					</div>	
					<?}?>
				</div>
				<?}?>			 
			</div>				
		</div>			
	</div>		
	<div class="row">
		<div class="col-md-12">
		<div class="seotext">Использование любых материалов, размещённых на сайте, разрешается при условии ссылки на сайт. </div>
		</div>
	</div>
	</div>
</div>
<div class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-4"><?echo $head['copy'];?></div>	
			<div class="col-md-4">
				
			</div>
			<div class="col-md-3 align-r">
			<div class="fsocial">
				 <ul>
				  <li><a href="#" class="twitter" title="Twitter" target="_blank"></a></li>
				  <li><a href="#" class="facebook" title="Facebook" target="_blank"></a></li>
				  <li><a href="#" class="vkontakte" title="ВКонтакте" target="_blank"></a></li>
				  <li><a href="http://ok.ru/cookjoy" class="gplus" title="Одноклассники" target="_blank"></a></li>
				  <li><a href="http://cookjoy.ru/rss/" class="rss" title="RSS лента" target="_blank"></a></li>
				 </ul>
				</div>
			</div>
			<div class="col-md-1"><a class="go-top" href="#"></a></div>		
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="/js/scripts.js"></script>
<!--script>
!function (d, id, did, st) {
  var js = d.createElement("script");
  js.src = "https://connect.ok.ru/connect.js";
  js.onload = js.onreadystatechange = function () {
  if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
	if (!this.executed) {
	  this.executed = true;
	  setTimeout(function () {
		OK.CONNECT.insertGroupWidget(id,did,st);
	  }, 0);
	}
  }}
  d.documentElement.appendChild(js);
}(document,"ok_group_widget","53168857940054","{width:200,height:335}");
</script-->
<?echo $head['code1'];?> <?echo $head['code2'];?> <?echo $head['code3'];?>
</body>
</html>