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
						<li class="active"><a href="#catalog" data-toggle="tab" aria-expanded="true">Ключи каталога</a></li>
						<li><a href="#product" data-toggle="tab" aria-expanded="false">Ключи товаров</a></li>
						<li><a href="#orders" data-toggle="tab" aria-expanded="false">Ключи заказов</a></li>
						<li><a href="#fields" data-toggle="tab" aria-expanded="false">Ключи доп. полей</a></li>
						<li><a href="#fieldsitem" data-toggle="tab" aria-expanded="false">Ключи вариантов доп. полей</a></li>
						<li><a href="#category" data-toggle="tab" aria-expanded="false">Ключи категорий</a></li>
					</ul>
			
			<div class="tab-content">
			<div class="tab-pane active" id="catalog">
				<table class="table-main">
				<tbody>
					<tr>
						<td>							
							<a href="/adm/generatekey/catalog" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-key"></i> Сгенерировать ключи для каталога
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
							<a href="/adm/generatekey/product" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-key"></i> Сгенерировать ключи для товаров
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
							<a href="/adm/generatekey/orders" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-key"></i> Сгенерировать ключи для заказов
							</a>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div class="tab-pane" id="fields">	
			<table class="table-main">
				<tbody>
					<tr>
						<td>
							<a href="/adm/generatekey/fields" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-key"></i> Сгенерировать ключи для доп. полей
							</a>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div class="tab-pane" id="fieldsitem">	
			<table class="table-main">
				<tbody>
					<tr>
						<td>
							<a href="/adm/generatekey/fieldsitem" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-key"></i> Сгенерировать ключи для вариантов доп. полей
							</a>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<div class="tab-pane" id="category">	
			<table class="table-main">
				<tbody>
					<tr>
						<td>
							<a href="/adm/generatekey/category" target="_blank" class="savenew" data-token="<?=$data["token"]?>">
								<i class="fa fa-key"></i> Сгенерировать ключи для категорий
							</a>
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