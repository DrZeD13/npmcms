<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}?>
<section class="content-header">
      <h1><? echo $data['title']; ?></h1>
	  <? if (!empty($data['navigation']))
	  {
		  echo $data['navigation'];
	  }?>
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
								<div class="input-group-addon">
								  <button type="submit" class="btn btn-primary btn-flat">
										<i class="fa fa-search"></i>
									</button>
								</div>
							  </div>				
						</form>
					</div>
					<div class="col-md-8 text-right">
						<br>
						<a href="/adm/<?=$data["table_name"]?>/add" class="add" title="Добавить"><i class="fa fa-plus-square"></i></a>
					</div>
				</div>
			</div>
			<div class="table-responsive">														
				<table width="100%" class="table">
					<thead>
						<td width="5%"><?=$data['id']?></td>
					   <td><?=$data['t_title']?></td>
					   <td width="100px"><?=$data['date']?></td>
					   <td><?=$data['link']?></td>
					   
					   <td width="15"><?=$data['order_index']?></td>
					   <td width="15"><?=$data['is_active']?></td>
					   <td width="15">&nbsp;</td>
					   <td width="15">&nbsp;</td>
					</thead>
					<tbody>
					<?
					if (isset($data['article_row']))
					{
						foreach($data['article_row'] as $row) 
						{?>
						<tr class="<?=$row['status']?>">
							<td>
								<?=$row['id']?>
							</td>
							<td>
								<a href="/adm/<?=$data["table_name"]?>/?parent_id=<?=$row['id']?>"><?=$row['title']?></a>
							</td>
							<td>
								<?=$row['date']?>
							</td>
							<td>
								<a href="<?=$row['link']?>" target="_blank"><?=$row['linktitle']?></a>
							</td>
							<td>
								<?=$row['order_index']?>
							</td>
							<td>
								<?if (!empty($row['active_img'])) {?>
								<a class="<?=$row['active_img']?>" href="<?=$row['active']?>" title="Изменить статус"><i class="fa fa-<?=$row['active_img']?>"></i></a>
								<?}?>
							</td>
							<td>
								<a class="edit" href="<?=$row['edit']?>" title="Редактировать"><i class="fa fa-pencil-square-o"></i></a>
							</td>
							<td>
								<? if (!empty($row['del'])) {?>
								<a class="delete" href="<?=$row['del']?>" onClick="return confirm ('Вы действительно хотите удалить данную запись?');" title="Удалить"><i class="fa fa-minus-circle"></i></a>
								<?}?>
							</td>
						</tr>
						<?}
					}
					else
					{?>
						<tr><td colspan="8" align="center"><?=$data['empty_row']?></td></tr>
					<?}?>
					</tbody>
				</table>
			</div>
			<div class="row mas-action">
				<div class="col-md-12">
				<div class="col-md-3">
				</div>

				<div class="col-md-9 text-right">
									<br>
									<a href="/adm/<?=$data["table_name"]?>/add" class="add" title="Добавить"><i class="fa fa-plus-square"></i> Добавить</a>
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