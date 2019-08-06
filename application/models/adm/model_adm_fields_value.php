<?php
/*
структура таблицы
field_value_id	Идентификатор 
parent_id	Идентификатор родителя
value значение 
*/
class Model_Adm_Fields_value extends Model 
{
	
	// имя таблицы для модуля
	var $table_name = 'fields_value';
	// название id-поля таблицы для модуля
	var $primary_key = 'field_value_id';
	// по каким полям можно осуществлять сортировку
	var $orderType = array ("field_value_id"=>"", "parent_id" =>"", "value"=>"");
	// поле по которуому осуществляется сортировка по умолчанию
	var $orderDefault = "field_value_id";
	// путь к папке с картинками
	// количество записей выводимых на страницу по умолчанию
	var $rowsPerPage = 20;
	var $orderDirDefault = "asc";
	
	
	public function get_data() 
	{			
		$parent_id = $this->GetGP("parent_id", -1);
        if ($parent_id == -1)
        {
            $parent_id = $this->GetSession("PRODUCTID", 0);
        }
        else {
            $_SESSION['PRODUCTID'] = $parent_id;
        }
		$sql = "SELECT Count(*) FROM `shop` WHERE shop_id='".$parent_id."'";
		$total = $this->db->GetOne($sql);
		// если нет такой продукции к кторой добавлять параметры переадресуем на продукцию
		if ($total == 0) 
		{
			$this->Redirect ("/adm/shop/");
		}
		
		$serch = $this->GetGP ("search", "");
		$mainlink = "/adm/".$this->table_name."/?parent_id=".$parent_id."&";
		// поиск по статьям
		if ($serch == "") 
		{
			$fromwhere = "FROM ".$this->table_name." WHERE parent_id = '$parent_id' ORDER BY ".$this->orderBy." ".$this->orderDir;	
		}
		else
		{
			// предусмотрен поиск по любому полю
			$mainlink .= "search=".$serch."&";
			$type = $this->GetGP ("type", "");
			if ($type == "")
			{ 
				$type = "value";
			}
			else
			{
				$mainlink .= "type=".$type."&";
			}
			$fromwhere = "FROM ".$this->table_name." WHERE $type LIKE  '%$serch%' AND parent_id=".$parent_id." ORDER BY ".$this->orderBy." ".$this->orderDir;
		}
		$nav = $this->GetAdminTitle("shop");
		$sql = "SELECT name FROM `shop` WHERE shop_id='".$parent_id."'";
		$navtitle = $this->db->GetOne($sql);
		$navigation = "<div class='breadcrumb'><a href='".$this->siteUrl."adm/shop/'>".$nav."</a> &raquo; ".$navtitle."</div>";
		// запрос для получения шапки таблицы
		//$title = $this->db->GetAdminTitle($this->table_name);
		$data = array (
			'title' => "Значения дополнительных параметров $navtitle",
			'main_title' => "Значения дополнительных параметров $navtitle",
			'navigation' => $navigation,
			'id' => "ID",
			't_title' => "Значение",
		);
		
		// запрос получения списка статей
		$sql = "SELECT parent_id FROM `shop` WHERE shop_id='".$parent_id."'";
		$catalog_id = $this->db->GetOne($sql);
		if (!empty($catalog_id))
			$fromwhere = "FROM fields,  field_category WHERE field_category.category_id = '$catalog_id' and fields.field_id = field_category.field_id ORDER BY order_index";
		else
			$fromwhere = "FROM fields ORDER BY order_index";
			
		$sql="SELECT Count(*) ".$fromwhere;
		
		$total = $this->db->GetOne ($sql, 0);		
		if ($total > 0) 
		{			
			$sql="SELECT fields.field_id, name, unit ".$fromwhere;	
			$result=$this->db->ExecuteSql($sql);
			while ($row = $this->db->FetchArray ($result))	
			{				
				$field_id = $row['field_id'];				
				$name = $this->dec($row['name']);								
				$unit = $this->dec($row['unit']);								
				
				$sql="SELECT field_value_id, field_item_id FROM ".$this->table_name." WHERE field_id = '$field_id' AND parent_id = '$parent_id'";
				$row1 = $this->db->GetEntry ($sql);
				$id = $row1[$this->primary_key];
				
				$editLink = "/adm/".$this->table_name."/edit?id=".$id;
				$delLink = "/adm/".$this->table_name."/del?id=".$id;				
				
				$value = $this->GetSelectFieldItem($field_id, $row1['field_item_id']);
				
				$data ["row"][] = array (
					"id" => $id,
					"field_id" => $field_id,
					"value" => $value,
					"name" => $name,
					"unit" => $unit,
					"action" => "update",
                    "edit" => $editLink,
                    "del" => $delLink,
				);		
			}
			$this->db->FreeResult ($result);
		}
		else
		{
			$data['empty_row'] = "Нет дополнительных полей";
		}
		
		return $data;
	}
	
	function GetSelectFieldItem($field_id, $field_item_id)
	{		
		$toRet = "<select data-placeholder='Выберите значение' name='field_item_id' id='field_item_id' class='chosen-select'> \r\n";
		$result = $this->db->ExecuteSql ("Select * From `fields_item` Where field_id='$field_id' Order by value asc");
		if ($result)
		{			
			while ($row = $this->db->FetchArray ($result)) {
				$selected = ($row['field_item_id'] == $field_item_id) ? "selected" : "";
				$toRet .= "<option value='".$row['field_item_id']."' $selected>".$row['value']."</option> \r\n";
			}
			$this->db->FreeResult  ($result);
		}
		return $toRet."</select>\r\n";
	}
	
	
	function Delete()
	{
		$id = $this->GetGP ("id");		
		$sql = "SELECT parent_id FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
		$name = $this->db->GetOne($sql);
		$this->history("Удаление", $this->table_name, $name, $id);
		$this->delElement($this->primary_key);
	}
	
	function Insert()
	{
		$data = $this->Form_Valid();		
		$sql = "Insert Into ".$this->table_name." ".ArrayInInsertSQL ($data);
		$this->db->ExecuteSql($sql);
		$id = $this->db->GetInsertID ();
		$this->history("Добавление", $this->table_name, "", $id);	
		return true;
		
	}
	
	function Update()
	{
		$id = $this->GetID("id", 0);
		$data = $this->Form_Valid();	
		if ($id == 0)
		{
			$id = $this->db->GetOne("SELECT {$this->primary_key} FROM ".$this->table_name." WHERE field_id = '".$data["field_id"]."' and parent_id = '".$data["parent_id"]."'");
			if ($id == 0)
			{
				$sql = "Insert Into ".$this->table_name." ".ArrayInInsertSQL ($data);
				$this->db->ExecuteSql($sql);		
				$id = $this->db->GetInsertID ();
				$sql = "SELECT parent_id FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
				$id_parent = $this->db->GetOne($sql);
				$this->history("Добавление", $this->table_name, $id, $id_parent);	
			}
			else
			{
				$sql = "UPDATE ".$this->table_name." SET ".ArrayInUpdateSQL ($data)." WHERE {$this->primary_key}='$id'";
				$this->db->ExecuteSql($sql);		
				$sql = "SELECT parent_id FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
				$id_parent = $this->db->GetOne($sql);
				$this->history("Изменение", $this->table_name, $id, $id_parent);
			}
		}
		else
		{
			$sql = "UPDATE ".$this->table_name." SET ".ArrayInUpdateSQL ($data)." WHERE {$this->primary_key}='$id'";
			$this->db->ExecuteSql($sql);		
			$sql = "SELECT parent_id FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
			$id_parent = $this->db->GetOne($sql);
			$this->history("Изменение", $this->table_name, $id, $id_parent);
		}
		
		return true;
	}	
	
	function Form_Valid($edit = false)
	{
		$data["parent_id"] = $this->GetSession("PRODUCTID", 0);
		$data["field_id"] = $this->GetGP("field_id");
		$data["field_item_id"] = $this->enc($this->GetGP("field_item_id"));
		
		return $data;
	}	

}
