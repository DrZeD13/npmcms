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
			<div class="tab-content">
				<form action="" method="post" enctype="multipart/form-data">
				<table class="table-main">
				<tbody>
					<tr>
						<td class="lable">
							Название:
						</td>
						<td>
							<input type="text" name="name" value="<?=$data['name']?>" required> <span class="error"><?=$data["name_error"]?></span>
						</td>
					</tr>
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
							Категория:
						</td>
						<td>
							<?=$data['fields']?>
						</td>
					</tr>	
					<tr>
						<td class="lable">
							Единица измерений:
						</td>
						<td>
							<input type="text" name="unit" value="<?=$data['unit']?>">
						</td>
					</tr>
					<tr>
						<td class="lable">
							Код:
						</td>
						<td>
							<input type="text" name="url" value="<?=$data['url']?>">
						</td>
					</tr>
					<tr>
						<td class="lable">
							Показывать в фильтре:
						</td>
						<td>
							<input type="checkbox" class="checkbox" id="is_filter" name="is_filter" value="1" <?echo ($data['is_filter'] == 0)?"":"checked";?>>
							<label for="is_filter"></label>						
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
					<?if (isset ($data["update"])) {?>
					<tr>
						<td class="lable">
							Дата редактирования:
						</td>
						<td>
							<?=$data["update"]["update_date"]?>
						</td>
					</tr>
					<tr>
						<td class="lable">
							Пользователь:
						</td>
						<td>
							<?=$data["update"]["update_user"]?>
						</td>
					</tr>
					<?}?>
				</tbody>
			</table>
			</form>
			</div>
			</div>
		</div>
	</div>
</section>
