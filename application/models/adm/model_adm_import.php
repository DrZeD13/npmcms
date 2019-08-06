<?php
/*
*/
class Model_Adm_Import extends Model 
{

	var $table_name = 'import';
	
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
	
	public function UpdateOrders() 
	{
		$physical_path = dirname(dirname(dirname(__DIR__)));	
		$i = 0;
		// проверяем преден ли файл
		if (array_key_exists ("file", $_FILES) and $_FILES["file"]['error'] < 3)
		{            
			$tmp_name = $_FILES["file"]['tmp_name'];			
			// проверяем загружен ли файл
			if (is_uploaded_file ($tmp_name))
			{
				// проверяем тип переданного файла
				if (mime_content_type($tmp_name) == "application/xml")
				{
					$new_file = $physical_path."/temp/temp.xml";
					move_uploaded_file ($tmp_name, $new_file);
					
					$xml = simplexml_load_file($new_file);
					// проверяем удалось ли распарсить xml файл
					if ($xml)
					{
						//dump($xml);
						foreach ($xml->Документы->Документ as $value)
						{
							$status = 0;
							foreach ($value->ЗначенияРеквизитов->ЗначениеРеквизита as $rekvisit)
							{
								switch ($rekvisit->Наименование)
								{
									case "Заказ оплачен";
										if ($rekvisit->Значение == "true")
											$status = 1;
									break;
									case "Отменен";
										if ($rekvisit->Значение == "true")
											$status = 4;
									break;
									case "Финальный статус";
										if ($rekvisit->Значение == "true")
											$status = 3;
									break;
								}
								echo $rekvisit->Наименование." ".$rekvisit->Значение;
								echo "<br>";
							}
							echo "ID: ".$value->Ид;
							echo "<br>";
							echo "Сумма: ".$value->Сумма;
							echo "<br>";
							echo "Статус: ".$status;
							//echo "<br>";
							
							/*$sql = "UPDATE shop SET count = '".$value->Количество."', price='".$value->Цены->Цена->ЦенаЗаЕдиницу."' WHERE shop_id = '".$value->Ид."'";
							echo "<br>";
							echo "sql: ".$sql;*/
							echo "<br><br>";
							++$i;
						}
					}
					else
					{
						echo "Не удалось прочитать файл";
					}
					echo "Обновленно всего товаров ".$i;
					unlink ($new_file);			
				}
				else		
				{
					echo "Загрузите xml файл";
				}
			}
			else
			{
				echo "Не удалось загрузить файл";
			}
		}
		else
		{
			echo "Выберите файл для загрузки";
		}
	}
	public function UpdatePrice() 
	{
		$physical_path = dirname(dirname(dirname(__DIR__)));	
		$i = 0;
		// проверяем преден ли файл
		if (array_key_exists ("file", $_FILES) and $_FILES["file"]['error'] < 3)
		{            
			$tmp_name = $_FILES["file"]['tmp_name'];			
			// проверяем загружен ли файл
			if (is_uploaded_file ($tmp_name))
			{
				// проверяем тип переданного файла
				if (mime_content_type($tmp_name) == "application/xml")
				{
					$new_file = $physical_path."/temp/temp.xml";
					move_uploaded_file ($tmp_name, $new_file);
					
					$xml = simplexml_load_file($new_file);
					// проверяем удалось ли распарсить xml файл
					if ($xml)
					{
						//dump($xml);
						foreach ($xml->ПакетПредложений->Предложения->Предложение as $value)
						{
							echo "ID: ".$value->Ид;
							echo "<br>";
							echo "Цена: ".$value->Цены->Цена->ЦенаЗаЕдиницу;
							echo "<br>";
							echo "Название: ".$value->Наименование;
							echo "<br>";
							echo "Количество: ".$value->Количество;
							
							$sql = "UPDATE shop SET count = '".$value->Количество."', price='".$value->Цены->Цена->ЦенаЗаЕдиницу."' WHERE shop_id = '".$value->Ид."'";
							$this->E($sql);
							echo "<br>";
							echo "sql: ".$sql;
							echo "<br><br>";
							++$i;
						}
					}
					else
					{
						echo "Не удалось прочитать файл";
					}
					echo "Обновленно всего товаров ".$i;
					$this->E("Обновленно всего товаров ".$i);
					unlink ($new_file);			
				}
				else		
				{
					echo "Загрузите xml файл";
				}
			}
			else
			{
				echo "Не удалось загрузить файл";
			}
		}
		else
		{
			echo "Выберите файл для загрузки";
		}
	}
	
	public function UpdateShop() 
	{
		$physical_path = dirname(dirname(dirname(__DIR__)));	
		$i = 0;
		// проверяем преден ли файл
		if (array_key_exists ("file", $_FILES) and $_FILES["file"]['error'] < 3)
		{            
			$tmp_name = $_FILES["file"]['tmp_name'];			
			// проверяем загружен ли файл
			if (is_uploaded_file ($tmp_name))
			{
				// проверяем тип переданного файла
				if (mime_content_type($tmp_name) == "application/xml")
				{
					$new_file = $physical_path."/temp/temp.xml";
					move_uploaded_file ($tmp_name, $new_file);
					
					$xml = simplexml_load_file($new_file);
					// проверяем удалось ли распарсить xml файл
					if ($xml)
					{
						echo "<h2>Добавляем каталог</h2>";
						$this->XMLCatalogTreeImport($xml->Классификатор, 0, "	");
						echo "<h2>Добавляем доп. поля</h2>";
						$this->XMLFieldsImport($xml->Классификатор);
						echo "<h2>Добавляем товары</h2>";
						//dump($xml);
						foreach ($xml->Каталог->Товары->Товар as $value)
						{
							++$i;
							echo "<h3>Товар $i</h3>";
							echo "ID: ".$value->Ид;
							echo "<br>";
							echo "Каталог: ".$value->Группы->Ид;
							echo "<br>";
							echo "Название: ".$value->Наименование;
							echo "<br>";
							echo "Бренд: ".$value->Изготовитель->Ид;
							// тут нужно делать дополнительные проверки если нет категории
							// и проверять есть ли такая категория в таблице категории
							$sql = "UPDATE shop SET category = '".$value->Изготовитель->Ид."', parent_id='".$value->Группы->Ид."' WHERE shop_id = '".$value->Ид."'";
							echo "<br>";
							echo "sql: ".$sql;
							echo "<br><br>";
							if (isset($value->ЗначенияСвойств->ЗначенияСвойства))
							{
								echo "<h4>Добавляем доп. поля</h4>";
								// возможно нужно будет предварительно удалять все существующие доп. поля и продукции что бы не было дубликатов	
								foreach ($value->ЗначенияСвойств->ЗначенияСвойства as $property)
								{
									echo "ID свойства: ".$property->Ид;
									echo "<br>";
									echo "Значения свойства: ".$property->Значение;
									echo "<br>";
									$data = array(
										"parent_id" => $value->Ид,
										"field_id" => $property->Ид,
										"field_item_id" => $property->Значение,
									);
									echo $sql = "Insert Into fields_value ".ArrayInInsertSQL ($data);
									echo "<br>";
									echo "<br>";
									
								}
							}
							
						}
					}
					else
					{
						echo "Не удалось прочитать файл";
					}
					echo "Обновленно всего товаров ".$i;
					unlink ($new_file);			
				}
				else		
				{
					echo "Загрузите xml файл";
				}
			}
			else
			{
				echo "Не удалось загрузить файл";
			}
		}
		else
		{
			echo "Выберите файл для загрузки";
		}
	}
	
	function XMLFieldsImport($array)
	{
		if (isset($array->Свойства->Свойство))
		{
			foreach ($array->Свойства->Свойство as $value)
			{
				echo $sql = "INSERT INTO fields SET field_id='".$value->Ид."', name='".$value->Наименование . "' ON DUPLICATE KEY UPDATE name='".$value->Наименование."'";
				echo "<br>";
				if (isset($value->ВариантыЗначений->Справочник))
				{
					foreach ($value->ВариантыЗначений->Справочник as $property)
					{
						echo $sql = "INSERT INTO fields_item SET field_id='".$value->Ид."', field_item_id='".$property->ИдЗначения."', value='".$property->Значение . "' ON DUPLICATE KEY UPDATE value='".$property->Значение."', field_id='".$value->Ид."'";
						echo "<br>";
					}
				}
				echo "<br>";
			}
		}
	}
	function XMLCatalogTreeImport($array, $parent = 0, $indent = "	")
	{
		if (isset($array->Группы->Группа))
		{
			foreach ($array->Группы->Группа as $value)
			{
				$id = is_numeric($value->Ид)?$value->Ид:"0";
				if ($this->db->GetOne("SELECT count(*) FROM shops WHERE shop_id = '".$id."'", 0) > 0)
				{
					$sql = "UPDATE shops SET parent_id='".$parent."', name='".$value->Наименование."'  WHERE shop_id = '".$value->Ид."'";
				}
				else
				{
					$data = array(
						"shop_id" => $value->Ид,
						"name" => $value->Наименование,
						"title" => $value->Наименование,
						"head_title" => $value->Наименование,
						"parent_id" => $parent,
						"url" => $this->GetUrl($value->Наименование, "shops", "/")
					);

						
					$sql = "Insert Into shops ".ArrayInInsertSQL ($data);
				}
				echo $indent.$sql."<br>";
				if (isset($value->Группы))
				{
					$this->XMLCatalogTreeImport($value, $value->Ид, "	".$indent);
				}
			}
		}
	}
	
	function GetUrl($url, $table_name, $delim = "/")
	{
		$url = TransUrl($url);
		if (!preg_match ("/^[a-z0-9-_]+$/", $url))
		{
			// рандомное значение для каталога
			$url = "name_catalog";
		}
		$url1 = $url;
		$i=0;	
		do {
			if ($i != 0)
			{								
				$url1 = $url.$i;
			}
			$total = $this->db->GetOne ("Select Count(*) From ".$table_name." Where url='".$url1.$delim."'", 0);			
			$i++;
		} while ($total > 0);	
		return $url1.$delim;
	}
}
