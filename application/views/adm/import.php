<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<section class="content-header">
    <h1><?echo $data['main_title'];?></h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="main-content">
			
			<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#updateprice" data-toggle="tab" aria-expanded="true">Обновление цен</a></li>
						<li><a href="#updateshop" data-toggle="tab" aria-expanded="true">Обновление товара</a></li>
						<li><a href="#orders" data-toggle="tab" aria-expanded="true">Обновление заказов</a></li>
						<!--li><a href="#product" data-toggle="tab" aria-expanded="false">Импорт продукции</a></li-->
					</ul>
			
			<div class="tab-content">
			<div class="tab-pane active" id="updateprice">
				<form action="/adm/import/updateprice/" method="post" enctype="multipart/form-data">
				<table class="table-main">
				<tbody>
					<tr>
						<td class="lable" style="padding-right: 15px;">						
							<input type='file' name='file' size='30' />
						</td>
						<td>
							<button type="submit" class="savenew">
								<i class="fa fa-cloud-upload"></i> Обновить цены
							</button>
							<input type="hidden" value="<?=$data["token"]?>">						
						</td>
					</tr>
				</tbody>
				</table>
				</form>
				<div id="result"></div>
				
			</div>
			
			<div class="tab-pane" id="updateshop">
			<form action="/adm/import/updateshop/" method="post" enctype="multipart/form-data">
				<table class="table-main">
				<tbody>
					<tr>
						<td class="lable" style="padding-right: 15px;">						
							<input type='file' name='file' size='30' />
						</td>
						<td>
							<button type="submit" class="savenew">
								<i class="fa fa-cloud-upload"></i> Обновить товары
							</button>
							<input type="hidden" value="<?=$data["token"]?>">
						</td>
					</tr>
				</tbody>
				</table>
				</form>

				<div id="result"></div>
				
			</div>
			
			<div class="tab-pane" id="orders">
			<form action="/adm/import/updateorders/" method="post" enctype="multipart/form-data">
				<table class="table-main">
				<tbody>
					<tr>
						<td class="lable" style="padding-right: 15px;">						
							<input type='file' name='file' size='30' />
						</td>
						<td>
							<button type="submit" class="savenew">
								<i class="fa fa-cloud-upload"></i> Обновить заказы
							</button>
							<input type="hidden" value="<?=$data["token"]?>">
						</td>
					</tr>
				</tbody>
				</table>
				</form>

				<div id="result"></div>
				
			</div>
			
		
			</div>
		</div>
	</div>
	</div>
	</div>
</section>