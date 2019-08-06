<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<div class="nav"><?echo $data["nav"];?></div>
<h1><? echo $data['title']; ?></h1>
<div class="full-top-blog">
	<div class="info-field"><b>Категория:</b> <a href="<?echo $data["cat_link"];?>"><?echo $data["cat_name"];?></a></div>
	<!--div class="info-field"><b>Опубликовано:</b> <?//echo $data["news_date"];?></div-->
	<div class="info-field"><b>Просмотров:</b> <?echo $data["views"];?></div>			
	<div class="info-field"><b>Комментариев:</b> <?echo $data["comments"];?></div>
	</div>

<?echo $head['code4'];?>
	
<? echo $data['descr'];?>
<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter" data-counter=""></div>

<div class="wideheader">
	<div class="widetitle">
		<div class="wtitle">
			Комментарии
        </div>
	</div>
</div>	 
<?if (isset($data['table_comment']))
{			
foreach($data['table_comment'] as $row)
{?>
<section class="comment">
		<span style="color: rgb(231, 66, 53);font-size:125%;"><i><?echo $row["name"];?></i></span> / <span style="color: rgb(136, 136, 136);"><?echo $row["date"];?></span>
		<br/><?echo $row["comment"];?>
</section>
<?}
}?>
<form action='' method='post' id='inputtext'>
	<b>Ваше имя: *</b><br/>
	<?echo $data["name"];?><span class='error'><?echo $data["name_error"];?></span><br />
	<b>Комментарий: *</b><br />
	<?echo $data["comment"];?><span class='error'><?echo $data["comment_error"];?></span><br /><br />
	<? if (!empty($data["capcha"]))
	{?>
	<?echo $data["capcha"];?> <span class='error'><?echo $data["capcha_error"];?></span>
	<?}?>
	<br/>* - поля обязательные для заполнения<br/>
	<input type='submit' name='btn' value="Добавить" class="cbutton">
	<input type='hidden' name='action' value='<?echo $data["action"];?>'>
	<input type='hidden' name='item_id' value='<?echo $data["item_id"];?>'>	
</form>