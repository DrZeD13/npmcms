<?php
/*
структура таблицы
field_value_id	Идентификатор 
parent_id	Идентификатор родителя
value значение 
*/
class Model_Adm_Fields_item extends Model 
{
	
	// имя таблицы для модуля
	var $table_name = 'fields_item';
	// название id-поля таблицы для модуля
	var $primary_key = 'field_item_id';
	// по каким полям можно осуществлять сортировку
	var $orderType = array ("field_item_id"=>"", "field_id" =>"", "value"=>"");
	// поле по которуому осуществляется сортировка по умолчанию
	var $orderDefault = "value";
	// путь к папке с картинками
	// количество записей выводимых на страницу по умолчанию
	var $rowsPerPage = 20;
	var $orderDirDefault = "asc";
	
	
	public function get_data() 
	{			
		$field_id = $this->GetGP("field_id", -1);
        if ($field_id == -1)
        {
            $field_id = $this->GetSession("FIELDID", 0);
        }
        else {
            $_SESSION['FIELDID'] = $field_id;
        }
		$sql = "SELECT Count(*) FROM `fields` WHERE field_id='".$field_id."'";
		$total = $this->db->GetOne($sql);
		// если нет такого доп. поля к кторому добавлять параметры переадресуем на доп. поля
		if ($total == 0) 
		{
			$this->Redirect ("/adm/fields/");
		}
		
		$serch = $this->GetGP ("search", "");
		$mainlink = "/adm/".$this->table_name."/?field_id=".$field_id."&";
		// поиск по статьям
		if ($serch == "") 
		{
			$fromwhere = "FROM ".$this->table_name." WHERE field_id = '$field_id' ORDER BY ".$this->orderBy." ".$this->orderDir;	
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
			$fromwhere = "FROM ".$this->table_name." WHERE $type LIKE  '%$serch%' AND field_id=".$field_id." ORDER BY ".$this->orderBy." ".$this->orderDir;
		}
		$nav = $this->GetAdminTitle("fields");
		$sql = "SELECT name FROM `fields` WHERE field_id='".$field_id."'";
		$navtitle = $this->db->GetOne($sql);
		$navigation = "<div class='breadcrumb'><a href='".$this->siteUrl."adm/fields/'>".$nav."</a> &raquo; ".$navtitle."</div>";
		// запрос для получения шапки таблицы
		//$title = $this->db->GetAdminTitle($this->table_name);
		$data = array (
			'title' => "Значения дополнительных полей $navtitle",
			'main_title' => "Значения дополнительных полей $navtitle",
			'navigation' => $navigation,
			'id' => "ID",
			't_title' => "Значение",
			"value" => $this->GetGP("value"),
			"value_error" => $this->GetError("value"),
		);
		
		// запрос получения списка статей
		$sql="SELECT Count(*) ".$fromwhere;
		
		$total = $this->db->GetOne ($sql, 0);		
		if ($total > 0) 
		{			
			$sql="SELECT * ".$fromwhere;	
			$result=$this->db->ExecuteSql($sql);
			while ($row = $this->db->FetchArray ($result))	
			{				
				$id = $row[$this->primary_key];				
				$value = $this->dec($row['value']);															
				
				$editLink = "/adm/".$this->table_name."/edit?id=".$id;
				$delLink = "/adm/".$this->table_name."/del?id=".$id;
				
				$data ["row"][] = array (
					"id" => $id,
					"value" => $value,					
					"field_id" => $field_id,
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
	
	function Delete()
	{
		$id = $this->GetGP ("id");		
		$sql = "SELECT value FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
		$name = $this->db->GetOne($sql);
		$this->history("Удаление", $this->table_name, $name, $id);
		
		$sql = "DELETE FROM fields_value WHERE {$this->primary_key}='$id'";
		$this->db->ExecuteSql($sql);
		$this->delElement($this->primary_key);
	}
	
	function Insert()
	{
		$data = $this->Form_Valid();
		$data["field_id"] = $this->GetSession("FIELDID", 0);
		if ($this->errors['err_count'] > 0) 
		{
			return false;
		}
		else
		{
			$sql = "Insert Into ".$this->table_name." ".ArrayInInsertSQL ($data);
			$this->db->ExecuteSql($sql);
			$id = $this->db->GetInsertID ();
			$this->history("Добавление", $this->table_name, "", $id);	
			return true;
		}
		
	}
	
	function Update()
	{
		$id = $this->GetID("id", 0);
		$data = $this->Form_Valid();	
		if ($this->errors['err_count'] > 0) 
		{
			return false;
		}
		else
		{
			$sql = "UPDATE ".$this->table_name." SET ".ArrayInUpdateSQL ($data)." WHERE {$this->primary_key}='$id'";
			$this->db->ExecuteSql($sql);		
			$sql = "SELECT value FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
			$name = $this->db->GetOne($sql);
			$this->history("Изменение", $this->table_name, $name, $id);
			
			return true;
		}
	}		
	
	function Form_Valid($edit = false)
	{
		$id = $this->GetID("id", 0);
		$data["value"] = $this->enc($this->GetGP("value"));
		
		$where =  ($edit)?" and {$this->primary_key} <> '$id'":"";
		$total = $this->db->GetOne ("Select Count(*) From ".$this->table_name." Where value='".$data["value"]."' $where", 0);		
		if ($total > 0)
			$this->SetError("value", "Такое значение уже существует");
		
		return $data;
	}	

}
