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
						<li class="active"><a href="#catalog" data-toggle="tab" aria-expanded="true">Экспорт каталога</a></li>
						<li><a href="#product" data-toggle="tab" aria-expanded="false">Экспорт остатков</a></li>
						<li><a href="#orders" data-toggle="tab" aria-expanded="false">Экспорт заказов</a></li>
					</ul>
			
			<div class="tab-content">
			<div class="tab-pane active" id="catalog">
				<table class="table-main">
				<tbody>
					<tr>
						<td>							
							<a href="/adm/export/catalog" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-cloud-download"></i> Экспорт каталога
							</a>
						</td>
					</tr>
				</tbody>
				</table>				
			</div>
			<div class="tab-pane" id="product">	
			<table class="table-main">
				<tbody>
					<tr>
						<td>
							<a href="/adm/export/product" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-cloud-download"></i> Экспорт остатков
							</a>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div class="tab-pane" id="orders">	
			<table class="table-main">
				<tbody>
					<tr>
						<td>
							<form action="/adm/export/orders" target="_blank" method="post">
							<div class="row">
							<div class="col-md-2">
							<input name="start" type="datetime" value="<? echo date("d.m.Y H:i:s", time()-3600*24*30)?>" class="datepickerTimeField"> 
							</div>
							<div class="col-md-2">
							<input name="end" type="datetime" value="<? echo date("d.m.Y H:i:s")?>" class="datepickerTimeField">
							</div>
							<div class="col-md-2">
							<button type="submit" class="savenew">
								<i class="fa fa-cloud-download"></i> Экспорт заказов
							</button>
							</div>
							</form>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			</div>
		</div>
	</div>
	</div>
	</div>
</section>