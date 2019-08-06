<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=$data['head_title']?></title>
<meta name="description" content="<?=$data['description']?>" />
<meta name="keywords" content="<?=$data['keywords']?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?=$data['head_title']?>" />
<meta property="og:description" content="<?=$data['description']?>" />
<meta property="og:url" content="<?echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>" />
<? if (isset($data["filename"])):?>
<meta property="og:image" content="<?=$data["filename"]?>" />
<?endif;?>
<? if (isset($data["canonical"])):?>
<link rel="canonical" href="<?=$data["canonical"]?>">
<?endif;?>
<link rel="icon" href="/favicon.png" type="image/x-icon">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" type="text/css" href="/css/owl.carousel.css" media="screen"/>
</head>
<body>
<div class="headline">
	<div class="tline"></div>
    <div class="tpanel">	
		<div class="container">
		<div class="row">
		<div class="col-md-9 col-sm-6">
			<div class="headmenu">
				<ul>
						<?echo $head['main_top_menu'];?>
				  </ul>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 text-right login">
			<?if ($head["is_user"])
			{?>
				<div class="drop-down"><svg width="12px" height="12px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<g id="Rounded" transform="translate(-238.000000, -156.000000)">
							<g id="Action" transform="translate(100.000000, 100.000000)">
								<g id="-Round-/-Action-/-account_circle" transform="translate(136.000000, 54.000000)">
									<g>
										<polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
										<path d="M12,2 C6.48,2 2,6.48 2,12 C2,17.52 6.48,22 12,22 C17.52,22 22,17.52 22,12 C22,6.48 17.52,2 12,2 Z M12,5 C13.66,5 15,6.34 15,8 C15,9.66 13.66,11 12,11 C10.34,11 9,9.66 9,8 C9,6.34 10.34,5 12,5 Z M12,19.2 C9.5,19.2 7.29,17.92 6,15.98 C6.03,13.99 10,12.9 12,12.9 C13.99,12.9 17.97,13.99 18,15.98 C16.71,17.92 14.5,19.2 12,19.2 Z" id="🔹Icon-Color" fill="#ff0004"></path>
									</g>
								</g>
							</g>
						</g>
					</g>
				</svg> <?=$_COOKIE['U_LOGIN']?> 
				<span class="arrow"></span></div>
 
				  <div class="drop-menu-main-sub">
					   <a href="/login/orders">История заказов</a>
					  <a href="/login/cabinet">Профиль</a>
					  <a href="/login/user_edit">Изменить профиль</a>
					  <a href="/login/changepassword">Изменить пароль</a>
					  <a href="/login/logout">Выйти</a>
				  </div>
			<?}else
			{?>
			<div class="head-login">
			<svg viewBox="0 0 535.5 535.5" width="12px" height="12px">
				<path d="M420.75,178.5h-25.5v-51c0-71.4-56.1-127.5-127.5-127.5c-71.4,0-127.5,56.1-127.5,127.5v51h-25.5c-28.05,0-51,22.95-51,51
						v255c0,28.05,22.95,51,51,51h306c28.05,0,51-22.95,51-51v-255C471.75,201.45,448.8,178.5,420.75,178.5z M267.75,408
						c-28.05,0-51-22.95-51-51s22.95-51,51-51s51,22.95,51,51S295.8,408,267.75,408z M346.8,178.5H188.7v-51
						c0-43.35,35.7-79.05,79.05-79.05c43.35,0,79.05,35.7,79.05,79.05V178.5z" fill="#ff0004"></path>
			</svg> 
				<a href="/login/">Вход</a> | <a href="/login/registration">Регистрация</a>
			</div>
			<?}?>
		</div>
		</div>
		</div>
	</div>
</div>
<div class="header">
	<div class="container">
		<div class="row">
		<div class="col-sm-4 logo">
			<img src="/img/logo.png" alt="">
		</div>
		<div class="col-sm-4">
			<div class="head-phone">
				8 (800) 000-00-00
			</div>
		</div>
		<div class="col-sm-4 cart-block">
			<div class="head-cart">
			<a href="/cart/" title="Перейти в корзину">
			<span class="title-cart">Корзина</span>
			<span class="head-cart-el">
			<span class="ms2_total_count"><?=$head['cart_total_count']?></span> <span id="cart-count-text"><?=$head['cart_total_text']?></span>
			<br>
			<span id="cart-cost-text"><?=$head['cart_cost']?></span>
			</span>
			</a>
			</div>
			<svg viewBox="0 0 64 64" class="cart-svg">
        <path d="M62.534359,20.2339001c-0.4531021-0.6221008-1.180584-0.9937-1.9462013-0.9937H23.5138588c-0.5527,0-1,0.4477997-1,1
		c0,0.5522995,0.4473,1,1,1h37.0742989c0.1298027,0,0.25,0.0620003,0.3300018,0.1709003
		c0.0800972,0.1103992,0.1035004,0.2544003,0.0625,0.3876991l-5.5615005,18.0000019
		C55.1965599,40.517601,54.5509605,41,53.8126602,41H21.6662598c-0.8213005,0-1.5293007-0.5684013-1.7227001-1.3809013
		L12.6056595,8.6968002C12.3703594,7.6978002,11.4924593,7,10.4709597,7H2.0002599C1.4474601,7,1.00026,7.4478002,1.00026,8
		s0.4472001,1,0.9999999,1h8.4706993c0.0879002,0,0.1669998,0.0654001,0.1884003,0.1571999l7.3389006,30.9229012
		C18.4054604,41.7998009,19.914259,43,21.6662598,43h32.1464005c1.6211967,0,3.0341988-1.0488014,3.5166969-2.6104012
		l5.5615005-17.9994984C63.1183586,21.6581993,62.9845581,20.8516006,62.534359,20.2339001z"></path>
	<path d="M29.5793591,33c-0.5527992,0-1,0.4473-1,1s0.4472008,1,1,1h20.8417988c0.5527,0,1-0.4473,1-1s-0.4473-1-1-1H29.5793591z"></path>
	<path d="M53.5050583,28c0-0.5522003-0.4472008-1-1-1H27.4953594c-0.5527992,0-1,0.4477997-1,1s0.4472008,1,1,1h25.0096989
		C53.0578575,29,53.5050583,28.5522003,53.5050583,28z"></path>
	<path d="M30.0002594,47c-2.7569008,0-5,2.2431984-5,5s2.2430992,5,5,5c2.7568016,0,5-2.2431984,5-5S32.757061,47,30.0002594,47z
		 M30.0002594,55c-1.6543007,0-3-1.3456993-3-3s1.3456993-3,3-3s3,1.3456993,3,3S31.6545601,55,30.0002594,55z"></path>
	<path d="M46.0002594,47c-2.7569008,0-5,2.2431984-5,5s2.2430992,5,5,5c2.7567978,0,5-2.2431984,5-5S48.7570572,47,46.0002594,47z
		 M46.0002594,55c-1.6543007,0-3-1.3456993-3-3s1.3456993-3,3-3s3,1.3456993,3,3S47.6545601,55,46.0002594,55z"></path>
	</svg>
			
		</div>
		</div>
	</div>
</div>
<div class="head-menu">
	<div class="container">
		<div class="row">
		<div class="col-sm-3 menu-col">
			<ul href="#" class="menu"><li><a href="#">Каталог товаров</a>
				<ul style="visibility: hidden;">
					<?echo $head["catalog_ul"];?>
				</ul>
			<div class="menu-bars">
				<svg aria-hidden="true" data-prefix="fal" data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-bars fa-w-14 fa-2x"><path fill="currentColor" d="M442 114H6a6 6 0 0 1-6-6V84a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6z" class=""></path></svg>
			</div><li></ul>			
		</div>
		<div class="col-sm-9 search-col">
			<form action="/search/">
				<label class="label-search">
					<svg aria-hidden="true" data-prefix="far" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-search fa-w-16 fa-2x"><path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z" class=""></path></svg>
				</label>
				<input type="text" name="search" class="input-search" placeholder="Введите название товара или артикул" size="40" maxlength="50" required>
				<button class="btn-search" type="submit" value="">
					<svg aria-hidden="true" data-prefix="far" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-search fa-w-16 fa-2x"><path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z" class=""></path></svg>
				</button>
			</form>			
		</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container">
		<div class="row">
		<div class="col">
			  <?php include 'application/views/'.$content_view; ?>
		</div>
		</div>
	</div>
</div>

<div class="footer">
	<div class="container">
		<div class="row">
		<div class="col-md-3">
			<h5>Контакты</h5>
		</div>
		<div class="col-md-9">
			<h5>Каталог</h5>
			<ul class="foot_catalog">
				<?echo $head['foot_catalog'];?>
			</ul>
		</div>
		</div>
	</div>
</div>
<div class="footer-mini">
	<div class="container">
		Разработано в Бастион дизайн
	</div>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!--script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script-->
    <script src="/js/jquery-1.11.2.min.js"></script>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script-->
    <!--script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"--></script>
	<script type="text/javascript" src="/js/owl.carousel.min.js"></script>
	<script src="/js/jquery.maskedinput.js"></script>
	<script src="/js/shop.js"></script>
    
 </body>
</html>