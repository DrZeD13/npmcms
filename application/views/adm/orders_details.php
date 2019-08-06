<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<section class="content-header">
      <h1><? echo $data['title']; ?></h1>
</section>
<section class="content">
<div class="row">
	<div class="col-md-12">
		<div class="main-content">	
			<div class="tab-content">
				<form action="" method="post" enctype="multipart/form-data">
				<table class="table-main">
					<tbody>
						<tr>
							<td class="lable">
								Дата:
							</td>
							<td>
								<input name="news_date" type="datetime" value="<? echo date("d.m.Y H:i:s", $data['news_date'])?>" class="datepickerTimeField">						
							</td>
						</tr>	
						<tr>
							<td class="lable">
								Имя:
							</td>
							<td>
								<input type="text" name="name" value='<?=$data['name']?>' required> <span class="error"><?=$data["name_error"]?></span>
							</td>
						</tr>
						<!--tr>
							<td class="lable">
								e-mail:
							</td>
							<td>
								<input type="text" name="email" value="<?=$data['email']?>">
							</td>
						</tr-->
						<tr>
							<td class="lable">
								Телефон:
							</td>
							<td>
								<input type="text" name="phone" value="<?=$data['phone']?>">
							</td>
						</tr>
						<tr>
							<td class="lable">
								Стутус:
							</td>
							<td>
								<?=$data['status']?>
							</td>
						</tr>
						<tr>
							<td class="lable">
								Город:
							</td>
							<td>
								<input type="text" name="city" value="<?=$data['city']?>">
							</td>
						</tr>
						<tr>
							<td class="lable">
								Улица:
							</td>
							<td>
								<input type="text" name="street" value="<?=$data['street']?>">
							</td>
						</tr>
						<tr>
							<td class="lable">
								Дом:
							</td>
							<td>
								<input type="text" name="dom" value="<?=$data['dom']?>">
							</td>
						</tr>
						<tr>
							<td class="lable">
								Офис:
							</td>
							<td>
								<input type="text" name="office" value="<?=$data['office']?>">
							</td>
						</tr>
						<tr>
							<td class="lable">
								Комментарий:
							</td>
							<td>
								<textarea name="comment"><?=$data['comment']?></textarea>
							</td>
						</tr>
						<tr>			
							<td colspan="2">
								<button type="submit" class="savenew">
									<i class="fa fa-floppy-o"></i> Сохранить
								</button>
								<button type="button" class="cancel" onClick="window.location.href='/adm/<?=$data["table_name"]?>/'">
									<i class="fa fa-ban"></i> Отмена
								</button>
								<input type="hidden" name="action" value="<?=$data["action"]?>" />
								<input type="hidden" name="token" value="<?=$data["token"]?>" />
							</td>
						</tr>				
					</tbody>
				</table>
				</form>
			</div>
			<div class="table-responsive">														
				<table class="table">
				<thead>
				   <td>ID</td>
				   <td>Название</td>
				   <td>Цена</td>
				   <td>Количество</td>
				   <td>Сумма</td>
				   <td width="15">&nbsp;</td>
				</thead>
				
				<?
				if (isset($data['row']))
				{
					$sum = 0;?>
					<tbody>
					<?foreach($data['row'] as $row) 
					{?>
					<tr>
						<td>
							<?=$row['shop_id']?>
						</td>
						<td>
							<a href="/adm/shop/edit?id=<?=$row['shop_id']?>" title="Редактировать"><?=$row['name']?></a>
						</td>
						<td>
							<?=$row['price']?>
						</td>
						<td>
							<?=$row['quantity']?>
						</td>
						<td>
							<? $sum +=  $row['price']*$row['quantity'];
							echo $row['price']*$row['quantity'];?>
						</td>
						<td>							
							<a class="delete del_tr_order_item" data-id="<?=$row['id']?>" href="#" title="Удалить"><i class="fa fa-minus-circle"></i></a>
						</td>
						
					</tr>
					<?}?>
					</tbody>
					<thead>
						<td colspan="4">
							Итого
						</td>
						<td>
							<?=$sum?>
						</td>
						<td>
						</td>
					</thead>
				<?}
				else
				{?>
					<tbody>
					<tr><td colspan="4" align="center"><?=$data['empty_row']?></td></tr>
					</tbody>
				<?}?>
			</tbody>
			</table>
			</div>
		</div>
	</div>
</div>
</section>
<? 
if (isset($data["pages"])) 
{?>
<div class="content pages">
<div class="main-content">
<div class="row">
	<?=$data["pages"]?>
</div>
</div>
</div>
<?}
?>