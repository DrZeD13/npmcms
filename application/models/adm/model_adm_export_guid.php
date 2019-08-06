<?php
/*
*/
class Model_Adm_Export extends Model 
{

	var $table_name = 'export';
	
	public function get_data() 
	{
		$title = $this->GetAdminTitle($this->table_name);
		$data = array (
			'title' => $title,
			'main_title' => $title,
			'token' => $this->GetSession("token", false)
		);				
		return $data;
	}
	
	public function ExportOrders() 
	{
		$start = strtotime ($this->GetGP("start", ""));;
		$end = strtotime ($this->GetGP("end", ""));
		$sql = "select (SELECT sum(price*quantity) FROM order_product WHERE order_product.order_id = orders.order_id) as sum, orders.order_id, orders.news_date, orders.comment, orders.user_id, users.company, users.inn, users.kpp, orders.name, orders.city, orders.street, orders.dom, orders.office, orders.status from orders JOIN users ON orders.user_id=users.user_id WHERE orders.news_date > $start and orders.news_date < $end and status='0'";
		$res ='<КоммерческаяИнформация ВерсияСхемы="2.03" ДатаФормирования="'.date("Y-m-d").'">';
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{
$res .= '
<Документ>
	<Ид>'.$row["order_id"].'</Ид>
	<Номер>'.$row["order_id"].'</Номер>
	<Дата>'.date("Y-m-d", $row["news_date"]).'</Дата>
	<ХозОперация>Заказ товара</ХозОперация>
	<Роль>Продавец</Роль>
	<Валюта>руб</Валюта>
	<Курс>1</Курс>
	<Сумма>'.$row["sum"].'</Сумма>
	<Время>'.date("H:i:s", $row["news_date"]).'</Время>
	<Комментарий>'.$this->dec(htmlspecialchars($row["comment"])).'</Комментарий>
	<Контрагенты>
	<Контрагент>
		<Наименование>'.$this->dec(htmlspecialchars($row["company"])).'</Наименование>
		<Роль>Покупатель</Роль>
		<ПолноеНаименование>'.$this->dec(htmlspecialchars($row["company"])).'</ПолноеНаименование>
		<Тип>Юридическое лицо</Тип>
		<ИНН>'.$row["inn"].'</ИНН>
		<КПП>'.$row["kpp"].'</КПП>
		<Фамилия>'.$row["name"].'</Фамилия>
		<Имя>'.$row["name"].'</Имя>
		<АдресРегистрации>
		<Представление>г. '.$row["city"].', ул. '.$row["street"].', д. '.$row["dom"].' '.$row["office"].'</Представление>
		<АдресноеПоле>
			<Тип>Почтовый индекс</Тип>
			<Значение></Значение>
		</АдресноеПоле>
		<АдресноеПоле>
			<Тип>Регион</Тип>
			<Значение>'.$row["city"].'</Значение>
		</АдресноеПоле>
		<АдресноеПоле>
			<Тип>Населенный пункт</Тип>
			<Значение>'.$row["city"].'</Значение>
		</АдресноеПоле>
		<АдресноеПоле>
			<Тип>Улица</Тип>
			<Значение>'.$row["street"].'</Значение>
		</АдресноеПоле>
		<АдресноеПоле>
			<Тип>Дом</Тип>
			<Значение>'.$row["dom"].'</Значение>
		</АдресноеПоле>
		<АдресноеПоле>
			<Тип>Корпус</Тип>
			<Значение>'.$row["office"].'</Значение>
		</АдресноеПоле>
		</АдресРегистрации>
		<Контакты/>
	</Контрагент>
	</Контрагенты>
	<Товары>';
					$sql = "SELECT * FROM order_product WHERE order_id = '".$row["order_id"]."'";
					$result1 = $this->db->ExecuteSql($sql);
					if ($result1)
					{
						while ($row1 = $this->db->FetchArray ($result1))	
						{	
		$res .= '
		<Товар>
			<Ид>'.$row1['shop_id'].'</Ид>
			<ИдКаталога/>
			<Наименование>'.$this->dec(htmlspecialchars($row1['name'])).'</Наименование>
			<БазоваяЕдиница Код="796" НаименованиеПолное="Штука" МеждународноеСокращение="PCE">шт</БазоваяЕдиница>
			<ЦенаЗаЕдиницу>'.$row1['price'].'</ЦенаЗаЕдиницу>
			<Количество>'.$row1['quantity'].'</Количество>
			<Сумма>'.$row1['price']*$row1['quantity'].'</Сумма>		
			<ЗначенияРеквизитов>
				<ЗначениеРеквизита>
					<Наименование>ВидНоменклатуры</Наименование>
					<Значение>Товар</Значение>
				</ЗначениеРеквизита>
				<ЗначениеРеквизита>
					<Наименование>ТипНоменклатуры</Наименование>
					<Значение>Товар</Значение>
				</ЗначениеРеквизита>
			</ЗначенияРеквизитов>
		</Товар>';
						}
						$this->db->FreeResult ($result1);
					}
	$res .= '
	</Товары>
	<ЗначенияРеквизитов>
		<ЗначениеРеквизита>
			<Наименование>Метод оплаты</Наименование>
			<Значение>Безналичный расчет</Значение>
		</ЗначениеРеквизита>
		<ЗначениеРеквизита>
			<Наименование>Заказ оплачен</Наименование>
			<Значение>'.((($row["status"] == "1") || ($row["status"] == "2"))?'true':'false').'</Значение>
		</ЗначениеРеквизита>
		<ЗначениеРеквизита>
			<Наименование>Доставка разрешена</Наименование>
			<Значение>true</Значение>
		</ЗначениеРеквизита>
		<ЗначениеРеквизита>
			<Наименование>Отменен</Наименование>
			<Значение>'.((($row["status"] == "3") || ($row["status"] == "4"))?'true':'false').'</Значение>
		</ЗначениеРеквизита>
		<ЗначениеРеквизита>
			<Наименование>Финальный статус</Наименование>
			<Значение>'.((($row["status"] == "1") || ($row["status"] == "2"))?'true':'false').'</Значение>
		</ЗначениеРеквизита>
		<ЗначениеРеквизита>
			<Наименование>Статус заказа</Наименование>
			<Значение>'.(($row["status"] == "0")?'На согласовании':'Согласован').'</Значение>
		</ЗначениеРеквизита>
		<ЗначениеРеквизита>
			<Наименование>Дата изменения статуса</Наименование>
			<Значение>'.date("Y-m-d H:i:s", $row["news_date"]).'</Значение>
		</ЗначениеРеквизита>
	</ЗначенияРеквизитов>
</Документ>';
			}
			$this->db->FreeResult ($result);
		}
		$res .='
</КоммерческаяИнформация>';
		header ("Content-Type: application/octet-stream");
		header ("Accept-Ranges: bytes");
		header('Content-Length: '.strlen($res));
		header('Content-disposition: inline; filename=orders_1C.xml');
		echo $res;
		
		/*$sql = "select category_id, name from category";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{			
			$item = '<?xml version="1.0" encoding="UTF-8"?>
			';
			while ($row = $this->db->FetchArray ($result))	
			{					
				$item .= "<item category_id='".$row["category_id"]."' name='".$this->dec($row["name"])."'></item>
				";
			}
			$this->db->FreeResult ($result);
						
			header ("Content-Type: application/octet-stream");
			header ("Accept-Ranges: bytes");
			header('Content-Length: '.strlen($item));
			header('Content-disposition: inline; filename=category.xml');
			echo $item;
			
		}
		else
		{
			echo "категории пустые";
		}*/
	}
	
	function GetOwner()
	{
		$res = '
		<Владелец>
			<Ид>00000002-01'.$this->db->GetSetting("GUID").'</Ид>
			<Наименование>КанцКурьер ООО</Наименование>
			<ОфициальноеНаименование>Общество с ограниченной ответственностью "КанцКурьер"</ОфициальноеНаименование>
			<ИНН>3123439594</ИНН>
			<КПП>312301001</КПП>
			<ОКПО/>
		</Владелец>';
		return $res;
	}
	
	function GetXmlHeadFor1C()
	{
		$res = '<?xml version="1.0" encoding="UTF-8"?>
<КоммерческаяИнформация xmlns="urn:1C.ru:commerceml_2" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ВерсияСхемы="2.07" ДатаФормирования="'.date("Y-m-dTH:i:s").'">';
		return $res;
	}
	
	function XMLCatalogTreeExport($parent = 0, $indent = "")
	{
		$res = "";
		
		if ($this->db->GetOne("select Count(*) from shops where is_active='1' and parent_id='".$parent."'", 0) > 0)
		{			
			$res .='
		'.$indent.'<Группы>';
			$sql = "select shop_id, name, parent_id, guid from shops where is_active='1' and parent_id='".$parent."'";
			$result=$this->db->ExecuteSql($sql);
			while ($row = $this->db->FetchArray ($result))	
			{					
				$res .='
			'.$indent.'<Группа>
			'.$indent.'	<Ид>'.$row["guid"].'</Ид>
			'.$indent.'	<Наименование>'.$this->dec($row["name"]).'</Наименование>';
				$res .= $this->XMLCatalogTreeExport($row["shop_id"], $indent."		");
				$res .= '
			'.$indent.'</Группа>';
					
			}
			$this->db->FreeResult ($result);
			$res .='
		'.$indent.'</Группы>';
		}
		return $res;		
	}
	
	public function ExportCatalog() 
	{
		$res = $this->GetXmlHeadFor1C();
		$res .= '
	<Классификатор>
		<Ид>00000001-01'.$this->db->GetSetting("GUID").'</Ид>
		<Наименование>Классификатор (Каталог товаров)</Наименование>';
		$res .= $this->GetOwner();
		
		
		$res .= $this->XMLCatalogTreeExport(0, "");
		/*$sql = "select shop_id, name, parent_id from shops where is_active='1'";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{			
			while ($row = $this->db->FetchArray ($result))	
			{					
				$res .='	<Группа>
				<Ид>'.$row["shop_id"].'</Ид>
				<Наименование>'.$this->dec($row["name"]).'</Наименование>
			</Группа>';
			}
			$this->db->FreeResult ($result);
		}		
		$res .='	</Группы>';*/
		$res .='
		<Свойства>';
		$sql = "select field_id, name, guid from fields where is_active='1'";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{			
			while ($row = $this->db->FetchArray ($result))	
			{					
				$res .='
			<Свойство>
				<Ид>'.$row["guid"].'</Ид>
				<Наименование>'.$this->dec(htmlspecialchars($row["name"])).'</Наименование>
				<ВариантыЗначений>';
					$sql = "select field_item_id, value, guid from fields_item where field_id='".$row["field_id"]."'";
					$result1=$this->db->ExecuteSql($sql);
					if ($result1)
					{			
						while ($row1 = $this->db->FetchArray ($result1))	
						{
							$res .='
					<Справочник>
						<ИдЗначения>'.$row1["guid"].'</ИдЗначения>
						<Значение>'.$row1["value"].'</Значение>
					</Справочник>';
						}
						$this->db->FreeResult ($result1);
					}
					
				$res .='
				</ВариантыЗначений>
			</Свойство>';
			}
			$this->db->FreeResult ($result);
		}	
		$res .='	</Свойства>
	</Классификатор>
	<Каталог СодержитТолькоИзменения="false">
	<Ид>00000001-01'.$this->db->GetSetting("GUID").'</Ид>
		<ИдКлассификатора>00000001-01'.$this->db->GetSetting("GUID").'</ИдКлассификатора>
		<Наименование>Каталог товаров</Наименование>
		<Владелец>
			<Ид>00000002-01'.$this->db->GetSetting("GUID").'</Ид>
			<Наименование>КанцКурьер ООО</Наименование>
			<ОфициальноеНаименование>Общество с ограниченной ответственностью "КанцКурьер"</ОфициальноеНаименование>
			<ИНН>3123439594</ИНН>
			<КПП>312301001</КПП>
			<ОКПО/>
		</Владелец>
		<Товары>';
		
		$sql = "select shop.shop_id, shop.guid, shop.name, shop.parent_id, shop.count, shop.category, category.name as category_name, category.guid as category_guid, shop.short_text from shop join category on category.category_id = shop.category where shop.is_active='1'";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{			
			while ($row = $this->db->FetchArray ($result))	
			{	
				$name = $this->dec(htmlspecialchars($row["name"])); 

				$res .='	
			<Товар>
				<Ид>'.$row["guid"].'</Ид>
				<Артикул/>
				<Наименование>'.$name.'</Наименование>
				<БазоваяЕдиница Код="796 " НаименованиеПолное="Штука" МеждународноеСокращение="PCE">
					<Пересчет>
						<Единица>796</Единица>
						<Коэффициент>1</Коэффициент>
					</Пересчет>
				</БазоваяЕдиница>
				<Группы>
					<Ид>'.$this->db->GetOne("SELECT guid FROM shops WHERE shop_id '".$row["parent_id"]."'").'</Ид>
				</Группы>
				<Описание>
					'.$this->dec(htmlspecialchars($row["short_text"])).'
				</Описание>
				<Изготовитель>
					<Ид>'.$row["category_guid"].'</Ид>
					<Наименование>'.$row["category_name"].'</Наименование>
				</Изготовитель>
				<ЗначенияСвойств>';
				
				$sql = "SELECT fields_value.field_id, fields_value.field_item_id, fields.guid as fguid, fields_item.guid as figuid from fields_value 
				INNER JOIN `fields` on fields.field_id = fields_value.field_id
				INNER JOIN `fields_item` on fields_item.field_item_id = fields_value.field_item_id
				WHERE fields_value.parent_id = '".$row["shop_id"]."'";
				$result1 = $this->db->ExecuteSql($sql);
				if ($result1)
				{
					while ($row1 = $this->db->FetchArray ($result1))	
					{						
						$res .='	
					<ЗначенияСвойства>
						<Ид>'.$row1["fguid"].'</Ид>
						<Значение>'.$row1["figuid"].'</Значение>
					</ЗначенияСвойства>';						
					}
					$this->db->FreeResult ($result1);
				}
				$res .='	
				</ЗначенияСвойств>
				<СтавкиНалогов>
					<СтавкаНалога>
						<Наименование>НДС</Наименование>
						<Ставка>Без НДС</Ставка>
					</СтавкаНалога>
				</СтавкиНалогов>
				<ЗначенияРеквизитов>
					<ЗначениеРеквизита>
						<Наименование>ВидНоменклатуры</Наименование>
						<Значение>Товар</Значение>
					</ЗначениеРеквизита>
					<ЗначениеРеквизита>
						<Наименование>ТипНоменклатуры</Наименование>
						<Значение>Товар</Значение>
					</ЗначениеРеквизита>
					<ЗначениеРеквизита>
						<Наименование>Полное наименование</Наименование>
						<Значение>'.$name.'</Значение>
					</ЗначениеРеквизита>
				</ЗначенияРеквизитов>
			</Товар>';				
			}
			$this->db->FreeResult ($result);
			
		}
		$res .= '
		</Товары>
	</Каталог>
</КоммерческаяИнформация>
		';
		header ("Content-Type: application/octet-stream");
		header ("Accept-Ranges: bytes");
		header('Content-Length: '.strlen($res));
		header('Content-disposition: inline; filename=import.xml');
		echo $res;
		
		/*$sql = "select shop_id, name, parent_id from shops";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{			
			$item = '<?xml version="1.0" encoding="UTF-8"?>
			';
			while ($row = $this->db->FetchArray ($result))	
			{					
				$item .= "<item catalog_id='".$row["shop_id"]."' parent_id='".$row["parent_id"]."' name='".$this->dec($row["name"])."'></item>
				";
			}
			$this->db->FreeResult ($result);
						
			header ("Content-Type: application/octet-stream");
			header ("Accept-Ranges: bytes");
			header('Content-Length: '.strlen($item));
			header('Content-disposition: inline; filename=catalog.xml');
			echo $item;
			
		}
		else
		{
			echo "каталог пустой";
		}*/
	}
	
	public function ExportProduct() 
	{
		$res = $this->GetXmlHeadFor1C();
		$res .= '<Классификатор>
		<Ид>1</Ид>
		<Наименование>Классификатор (Каталог товаров)</Наименование>';
		$res .= $this->GetOwner();
		$res .= '</Классификатор>
		<ПакетПредложений СодержитТолькоИзменения="false">
		<Ид>1</Ид>
		<Наименование>Пакет предложений (Каталог товаров)</Наименование>
		<ИдКаталога>1</ИдКаталога>
		<ИдКлассификатора>1</ИдКлассификатора>';
		$res .= $this->GetOwner();
		$res .= '<ТипыЦен>
			<ТипЦены>
				<Ид>1</Ид>
				<Наименование>Розничное соглашение</Наименование>
				<Валюта>RUB</Валюта>
				<Налог>
					<Наименование>НДС</Наименование>
					<УчтеноВСумме>true</УчтеноВСумме>
					<Акциз>false</Акциз>
				</Налог>
			</ТипЦены>
		</ТипыЦен>
		<Склады>
			<Склад>
				<Ид>1</Ид>
				<Наименование>Основной склад</Наименование>
			</Склад>
		</Склады>
		<Предложения>';
		$sql = "select shop_id, name, count, price from shop WHERE is_active='1'";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{
			$res .= '<Предложение>
				<Ид>'.$row["shop_id"].'</Ид>
				<Артикул/>
				<Наименование>'.$this->dec(htmlspecialchars($row["name"])).'</Наименование>
				<БазоваяЕдиница Код="796 " НаименованиеПолное="Штука" МеждународноеСокращение="PCE">
					<Пересчет>
						<Единица>796</Единица>
						<Коэффициент>1</Коэффициент>
					</Пересчет>
				</БазоваяЕдиница>
				<Цены>
					<Цена>
						<Представление> '.$row["price"].' RUB за PCE</Представление>
						<ИдТипаЦены>1</ИдТипаЦены>
						<ЦенаЗаЕдиницу>'.$row["price"].'</ЦенаЗаЕдиницу>
						<Валюта>RUB</Валюта>
						<Единица>PCE</Единица>
						<Коэффициент>1</Коэффициент>
					</Цена>
				</Цены>
				<Количество>'.$row["count"].'</Количество>
				<Склад ИдСклада="1" КоличествоНаСкладе="'.$row["count"].'"/>
			</Предложение>';
			}
			$this->db->FreeResult ($result);
		}
		$res .= '</Предложения>
	</ПакетПредложений>
</КоммерческаяИнформация>';
		header ("Content-Type: application/octet-stream");
		header ("Accept-Ranges: bytes");
		header('Content-Length: '.strlen($res));
		header('Content-disposition: inline; filename=offers.xml');
		echo $res;
		/*$sql = "select shop_id, name, parent_id, count from shop";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{			
			$item = '<?xml version="1.0" encoding="UTF-8"?>
			';
			while ($row = $this->db->FetchArray ($result))	
			{					
				$item .= "<item product_id='".$row["shop_id"]."' parent_id='".$row["parent_id"]."' name='".$this->dec($row["name"])."' count='".$row["count"]."'>
					";
				$sql = "SELECT fields.field_id, fields.name, fields_value.value from fields JOIN fields_value ON fields_value.field_id = fields.field_id JOIN shop ON shop.shop_id = fields_value.parent_id and shop.shop_id = '".$row["shop_id"]."'";
				$result1 = $this->db->ExecuteSql($sql);
				if ($result1)
				{
					$item .= "	<fields>
					";
					while ($row1 = $this->db->FetchArray ($result1))	
					{	
						$item .= "		<field field_id='".$row1["field_id"]."' field_name='".$row1["name"]."' field_value='".$row1["value"]."'></field>
						";
					}
					$item .= "	</fields>
					";
					$this->db->FreeResult ($result1);
				}
				$item .="</item>
				";
			}
			$this->db->FreeResult ($result);
						
			header ("Content-Type: application/octet-stream");
			header ("Accept-Ranges: bytes");
			header('Content-Length: '.strlen($item));
			header('Content-disposition: inline; filename=product.xml');
			echo $item;
			
		}
		else
		{
			echo "Нет товавров";
		}*/
	}
	
	

}
