<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<ul class="none" itemscope itemtype="http://schema.org/Recipe">
	<li class="nav"><?echo $data["nav"];?></li>
	<li class="page-title">
		<h1 itemprop="name"><?echo $data["title"];?></h1>
		<div class="full-top">
			<div class="info-field"><b>Категория:</b> <a href="<?echo $data["link_cat"];?>"><span itemprop="recipeCategory"><?echo $data["name_cat"];?></span></a></div>
			<div class="info-field"><b>Опубликовано:</b><meta itemprop="datePublished" content="<?echo $data["date_year"];?>-<?echo $data["date_month"];?>-<?echo $data["date_day"];?>"> <?echo $data["date_day"];?>-<?echo $data["date_month"];?>-<?echo $data["date_year"];?></div>
			<div class="info-field"><b>Просмотров:</b> <meta itemprop="interactionCount" content="UserPageVisits:<?echo $data["views"];?>"><?echo $data["views"];?></div>
			<div class="info-rating"><?echo $data["rating"];?></div>			
		</div>
	</li>	
	<li>
		<img src="<?echo $data["filename"];?>" itemprop="image" alt="<?echo $data["title"];?>" title="<?echo $data["title"];?>" class="mainimg">
		<div style="clear: both;"></div>
		<br>
		<div itemprop="description"><p><?echo $data["short_text"];?></p></div>
		
		
		<?echo $head['code4'];?>
		
		
		<div class="indigrienty">
			<h2>Ингредиенты рецепта:</h2>
			<ul class="none">
			<?if (isset($data['ingridients']))
			{			
				foreach($data['ingridients'] as $row) 
				{?>
				<li itemprop="ingredients"><?echo $row["row_ingridient"];?></li>
			<?}
			}?>
			</ul>
		</div>
		<br>
		<h2 class="italic">Способ приготовления:</h2>
		<div itemprop="recipeInstructions"><?echo $data["text"];?></div>
		<h3 class="center italic">Приятного аппетита!</h3>
		<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
		<script src="//yastatic.net/share2/share.js"></script>
		<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter" data-counter=""></div>
		<div class="print-botton">
			<a href="<?echo $data["print"];?>" class="cook margin5" target="_blank">Распечатать рецепт</a>
		</div>
    </li>
	<?if (isset($data['tags']))
			{?>
	<li>
	<div class="tags">Метки:		
				<?foreach($data['tags'] as $row)
				{?>
				<a href="<?echo $row["row_link"];?>"><?echo $row["row_name"];?></a> 
	<?}?>			
		</div>
	</li>
	<?}?>
	<li>
		<div class="related-block">
			<div class="related-title">Другие рецепты:</div>
			<div class="clear"></div>
			<?if (isset($data['similar']))
			{			
				foreach($data['similar'] as $row) 
				{?>
			<div class="related">
				<a href="<?echo $row["link"];?>" title="<?echo $row["title"];?>">
				<img src="<?echo $row["filename"];?>" alt="<?echo $row["title"];?>">
				</a>
				<div class="reltitle"><a href="<?echo $row["link"];?>" title="<?echo $row["title"];?>"><?echo $row["title"];?></a></div>
			</div>
			<?}
			}?>
		</div>
	</li>
	 <li><div class="wideheader">
       <div class="widetitle">
        <div class="wtitle">
         Комментарии
        </div>
       </div>
      </div></li>
	 <li>
	 <?if (isset($data['table_comment']))
			{			
				foreach($data['table_comment'] as $row)
				{?>
		<div class="comment">
				<span style="color: rgb(231, 66, 53);font-size:125%;"><i><?echo $row["name"];?></i></span> / <span style="color: rgb(136, 136, 136);"><?echo $row["date"];?></span>
				<br/><?echo $row["comment"];?>
		</div>
	<?}
			}?>
	 </li>
	 <li>
		<form action='<?echo $data["main_link"];?>' method='post' id='inputtext'>
			<b>Ваше имя: *</b><br/>
            <?echo $data["name"];?><span class='error'><?echo $data["name_error"];?></span><br />
            <b>Комментарий: *</b><br />
            <?echo $data["comment"];?><span class='error'><?echo $data["comment_error"];?></span><br /><br />
			<span class='error'><?echo $data["capcha_error"];?></span>
            <? if (!empty($data["capcha"]))
			{?>			
				<?echo $data["capcha"];?>
			<?}?>
			<br/>* - поля обязательные для заполнения<br/>
			<input type='submit' name='btn' value="Добавить" class="cbutton">
			<input type='hidden' name='action' value='<?echo $data["action"];?>'>
			<input type='hidden' name='item_id' value='<?echo $data["item_id"];?>'>
			<input type='hidden' name='hash' value='<?echo $data["hash"];?>'>
		</form>
	</li>           
</ul>