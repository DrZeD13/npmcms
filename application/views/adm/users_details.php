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
							Дата:
						</td>
						<td>
							<input name="news_date" type="text" value="<? echo date("d.m.Y H:i:s", $data['news_date'])?>" class="datepickerTimeField">
						</td>
					</tr>
					<tr>
						<td class="lable">
							email:
						</td>
						<td>
							<input type="text" name="email" value="<?=$data['email']?>" required> 
						</td>
					</tr>
					<tr>
						<td class="lable">
							Пароль:
						</td>
						<td>
							<input type="text" name="pwd" value="<?=$data['pwd']?>" <?=$data["required"]?>> <span class="error"><?=$data["pwd_error"]?></span>
						</td>
					</tr>					
					<tr>
						<td class="lable">
							Компания:
						</td>
						<td>
							<input type="text" name="company" value='<?=$data['company']?>' required> 
						</td>
					</tr>
					<tr>
						<td class="lable">
							ИНН:
						</td>
						<td>
							<input type="text" name="inn" value="<?=$data['inn']?>" required> 
						</td>
					</tr>
					<tr>
						<td class="lable">
							КПП:
						</td>
						<td>
							<input type="text" name="kpp" value="<?=$data['kpp']?>"> 
						</td>
					</tr>
					<tr>
						<td class="lable">
							Юридический адрес:
						</td>
						<td>
							<textarea name="yaddress" required><?=$data['yaddress']?></textarea>
						</td>
					</tr>
					<tr>
						<td class="lable">
							Фактический адрес:
						</td>
						<td>
							<textarea name="faddress" required><?=$data['faddress']?></textarea>
						</td>
					</tr>
					<tr>
						<td class="lable">
							БИК:
						</td>
						<td>
							<input type="text" name="bik" value="<?=$data['bik']?>" required> 
						</td>
					</tr>
					<tr>
						<td class="lable">
							Расчетный счет:
						</td>
						<td>
							<input type="text" name="rs" value="<?=$data['rs']?>" required> 
						</td>
					</tr>
					<tr>
						<td class="lable">
							Наименование банка, корр.счет, расположение банка::
						</td>
						<td>
							<textarea name="bank" required><?=$data['bank']?></textarea>
						</td>
					</tr>
					<tr>
						<td class="lable">
							Контактное лицо:
						</td>
						<td>
							<input type="text" name="login" value="<?=$data['login']?>" required> <span class="error"><?=$data["login_error"]?></span>
						</td>
					</tr>
					<tr>
						<td class="lable">
							Телефон:
						</td>
						<td>
							<input type="text" name="tel" value="<?=$data['tel']?>" required> 
						</td>
					</tr>
					<tr>
						<td class="lable">
							Договор:
						</td>
						<td>
							<input type="checkbox" class="checkbox" id="dogovor" name="dogovor" value="1" <?echo ($data['dogovor'] == 0)?"":"checked";?>>
							<label for="dogovor"></label>						
						</td>
					</tr>				
					<tr>
						<td></td>
						<td>
							<button type="submit" class="savenew">
								<i class="fa fa-floppy-o"></i> Сохранить
							</button>
							<button type="button" class="cancel" onClick="window.location.href='/adm/<?=$data["table_name"]?>/'">
								<i class="fa fa-ban"></i> Отмена
							</button>
							<input type="hidden" name="action" value="<?=$data["action"]?>" />
						</td>
					</tr>
				</tbody>
			</table>
			</form>
			</div>
			</div>
		</div>
	</div>
</section>
