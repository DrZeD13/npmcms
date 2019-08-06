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
			<div class="row head-search">
				<div class="col-sm-12">
					<div class="col-md-4">						
						<form action="/adm/<?=$data["table_name"]?>/" method="GET">				
							<span class="lable">Поиск:</span>
							<div class="input-group">
								<input type='text' name='search' class="search" value="<?=$data['search']?>" placeholder="Что ищем?">
								<div class="input-group-middle">
									<?=$data["field"]?>
								</div>
								<div class="input-group-addon">
								  <button type="submit" class="btn btn-primary btn-flat">
										<i class="fa fa-search"></i>
									</button>
								</div>
							  </div>				
						</form>
					</div>
					<div class="col-md-4">
						<div style="max-width:359px">
						<span class="lable">Поиск по дате:</span>
						<form action="/adm/<?=$data["table_name"]?>/" method="GET">
						<div class="input-group">
							<input name="date_start" type="datetime" value="<? echo date("d.m.Y H:i:s", $data["date_start"])?>" class="datepickerTimeField">
							<div class="input-group-middle">
								<input name="date_end" type="datetime" value="<? echo date("d.m.Y H:i:s", $data["date_end"])?>" class="datepickerTimeField">
							</div>
							<div class="input-group-addon">
							  <button type="submit" class="btn btn-primary btn-flat">
									<i class="fa fa-search"></i>
								</button>
							</div>
						  </div>
						  </form>
					  </div>
					</div>
					<div class="col-md-2">					
						<span class="lable">Статус:</span><br>
						 <?=$data['getStatus']?>											
					</div>
					<div class="col-md-2">					
						<span class="lable">Пользователь:</span><br>
						 <?=$data['getUserSelect']?>											
					</div>
					<!--div class="col-md-1 text-right">
						<br>
						<a href="/adm/<?=$data["table_name"]?>/add" class="add" title="Добавить"><i class="fa fa-plus-square"></i></a>
					</div-->
				</div>
			</div>
			<div class="table-responsive">														
				<table class="table">
				<thead>
					<td width="23px"><input type="checkbox" id="checkbox_all" title="Выбрать все"></td>
					<td width="5%"><?=$data['id']?></td>
				   <td><?=$data['company']?></td>
				   <td><?=$data['name']?></td>
				   <td width="140px"><?=$data['date']?></td>
				   <td><?=$data['sum']?></td>
				   <td><?=$data['phone']?></td>
				   <td><?=$data['address']?></td>
				   <td><?=$data['comment']?></td>
				   <td><?=$data['status']?></td>
				   <td width="15">&nbsp;</td>
				   <td width="15">&nbsp;</td>
				   <td width="15">&nbsp;</td>
				</thead>
				<tbody>
				<?
				if (isset($data['row']))
				{
					foreach($data['row'] as $row) 
					{?>
					<tr class="status<?=$row['status_number']?>">
						<td><input type="checkbox" name="itemid[]" value="<?=$row['id']?>" form="masform"></td>
						<td>
							<?=$row['id']?>
						</td>
						<td>
							<a href="<?=$row['edit']?>" title="Редактировать"><?=$row['company']?> <?=$row['new']?></a>
						</td>
						<td>
							<?=$row['name']?>
						</td>
						<td>
							<?=$row['date']?>
						</td>
						<td>
							<?=$row['sum']?>
						</td>
						<td>
							<?=$row['phone']?>
						</td>
						<td>
							<?=$row['address']?>
						</td>
						<td>
							<?=$row['comment']?>
						</td>
						<td>
							<?=$row['status']?>
						</td>
						<td>
							<?=$row['user_id']?>
						</td>
						<td>
							<a class="edit" href="<?=$row['edit']?>" title="Редактировать"><i class="fa fa-pencil-square-o"></i></a>
						</td>
						<td>
							<? if (!empty($row['del'])) {?>
							<a class="delete" href="<?=$row['del']?>" onClick="return confirm ('Вы действительно хотите удалить данный комментарий?');" title="Удалить"><i class="fa fa-minus-circle"></i></a>
							<?}?>			
						</td>
					</tr>
					<?}
				}
				else
				{?>
					<tr><td colspan="13" align="center"><?=$data['empty_row']?></td></tr>
				<?}?>
			</tbody>
			</table>
			</div>
			<div class="row mas-action">
				<div class="col-md-12">
				<div class="col-md-3">
				<!--form action="" method="POST" id="masform">
				<span class="lable">Выполнить:</span>
				<div class="input-group">
				<select name="mas_action">
					<option value="active" selected="">Активный</option>
					<option value="notactive">Неактивный</option>
					<option value="spam">Спам</option>
					<option value="del">Удалить</option>
				</select>
				<input type="hidden" name="action" value="mas" />
				<input type="hidden" name="token" value="<?=$data["token"]?>" />
				<div class="input-group-addon">
					<button class="btn btn-success btn-flat"><i class="fa fa-check"></i></button>
				</div>
				</div>
				</form-->
				</div>

				<div class="col-md-9 text-right">
									<!--br>
									<a href="/adm/<?=$data["table_name"]?>/add" class="add" title="Добавить"><i class="fa fa-plus-square"></i> Добавить</a-->
								</div>

				</div>
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