<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<h1><? echo $data['title']; ?></h1>
<p><? echo $data['message']; ?></p>
<?
if (isset($data['article_row']))
{
?>
<?
	foreach($data['article_row'] as $row) 
	{?>
		<div class="testimonials">
		<img src="<?=$row['filename']?>">
		<h3><?=$row['name']?>, <?=$row['company']?></h3>
		<p><?=$row['comment']?></p>
		</div>
		<div style="clear: both;"></div>
	<?}?>
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

<h2>Добавить отзыв</h2>
<div class="faq">
	<form action='/testimonials/' method='post'>
		<div class="lableform">Введите ваше имя:*</div>
		<input type='text' name='first_name' class="form" value='<?=$data["first_name"]?>' placeholder="Представтесь" required pattern="[а-яА-Яa-zA-Z0-9_-]{3,255}">
		<span class='error'><?=$data["error_first_name"]?></span><br/>
		<div class="lableform">E-mail адрес:*</div>
		<input type='email' name='email' class="form" value='<?=$data["email"]?>' placeholder="Как с Вами связаться?"  required pattern="^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" />
		<span class='error'><?=$data["error_email"]?></span><br/>
		<div class="lableform">Отзыв:*</div><span class='error'><?=$data["error_question"]?></span>
		<textarea name='question' rows='6' cols='30' class="form" placeholder="Ваш отзыв" required><?=$data["question"]?></textarea><br/><br />
		<script type='text/javascript'>
			function refreshcapcha() {
				document.getElementById('capcha-image').src='/capcha/capcha.php?rid=' + Math.random();
			}
		</script>				
		<a href='javascript:void(0);' onclick='refreshcapcha();'><img title='нажмите чтобы изменить изображение' src='/capcha/capcha.php' id='capcha-image' alt='нажмите чтобы изменить изображение'></a><br />
		<input type='text' name='keystring' class="form" value='' placeholder="Вы не робот?" required><span class='error'><?=$data["error_captcha"]?></span>
		<div class="lableform">* - поля обязательные для заполнения</div>					
		<input type='submit' name='btn' class="button" value='Отправить' />
		<input type='hidden' name='action' value='<?=$data["action"]?>' />
	</form>
</div>
