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
						<br>
						<form action="" method="post">	
							<div class="input-group">
							<input type="text" name="value" class="search" value="<?=$data['value']?>" required autofocus> 
							<div class="input-group-addon">
								<button type="submit" class="savenew">
										<i class="fa fa-plus-square"></i>  Добавить
								</button>
								</div>
							</div>
							<span class="error"><?=$data['value_error']?></span>
							<input type="hidden" name="action" value="insert" />	
						</form>	
					</div>
				</div>
			</div>
			<div class="table-responsive">														
				<table class="table">
				<thead>
					<td width="5%"><?=$data['id']?></td>
					<td width="15%">Дополнительное поле</td>		   
				   <td width="15">&nbsp;</td>
				</thead>
				<tbody>
				<?
				if (isset($data['row']))
				{
					foreach($data['row'] as $row) 
					{?>
					<tr>
						<td>
							<?=$row['id']?>
						</td>
						<td>
							<form action="" method="post">				
								<div class="input-group">
								<input type="text" name="value" value="<?=$row['value']?>" required style="min-width:500px;">
								<div class="input-group-addon">
								  <button type="submit" class="savenew">
									<i class="fa fa-floppy-o"></i> Сохранить
								</button>
								</div>
							  </div>
								<input type="hidden" name="action" value="<?=$row["action"]?>" />
								<input type="hidden" name="id" value="<?=$row['id']?>" />
								<input type="hidden" name="field_id" value="<?=$row['field_id']?>" />
							</form>				
						</td>						
						<td>
							<a class="delete" href="<?=$row['del']?>" onClick="return confirm ('Вы действительно хотите удалить данный элемент?');" title="Удалить"><i class="fa fa-minus-circle"></i></a>
						</td>
					</tr>
					<?}
				}
				else
				{?>
					<tr><td colspan="3" align="center"><?=$data['empty_row']?></td></tr>
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