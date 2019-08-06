<?php
/*
структура таблицы
field_id	Идентификатор меню
news_date	 Дата создания
name Название
is_active	Флаг активности
order_index	порядок
author автор
update_date дата обновления
update_user id пользователя который обновил
*/
class Model_Adm_Fields extends Model 
{

	var $table_name = 'fields';
	var $primary_key = 'field_id';
	var $orderType = array ("field_id"=>"", "name" =>"", "news_date"=>"", "order_index" => "", "is_active" => "");
	var $orderDefault = "order_index";
	var $orderDirDefault = "asc";
	var $path_img = "/media/fields/";
	var $rowsPerPage = 15;
	
	public function get_data() 
	{							
		$parent_id = $this->GetGP("parent_id", -1);
        if ($parent_id == -1)
        {
            $parent_id = $this->GetSession("PARENTFIELDID", 0);
        }
        else {
            $_SESSION['PARENTFIELDID'] = $parent_id;
        }
		
		
		
		$mainlink = "/adm/".$this->table_name."/?";
		$search = $this->GetGP ("search", "");		
		// поиск по статьям
		if ($search == "") 
		{			
			if ($parent_id == 0)
				$fromwhere = "FROM ".$this->table_name." WHERE 1 ORDER BY ".$this->orderBy." ".$this->orderDir;
			else
			{
				$fromwhere = "FROM ".$this->table_name.", field_category WHERE category_id = '$parent_id' and field_category.field_id = fields.field_id ORDER BY ".$this->orderBy." ".$this->orderDir;
			}	
		}
		else
		{
			// предусмотрен поиск по любому полю
			$mainlink .= "search=".$search."&";
			$type = $this->GetGP ("type", "");
			if ($type == "")
			{ 
				$type = "name";
			}
			else
			{
				$mainlink .= "type=".$type."&";
			}
			$fromwhere = "FROM ".$this->table_name." WHERE $type LIKE '%$search%' ORDER BY ".$this->orderBy." ".$this->orderDir;
		}
		// запрос для получения шапки таблицы
		$title = $this->GetAdminTitle($this->table_name);
		$data = array (
			'title' => $title,
			'main_title' => $title,
			'search' => $search,
			'table_name' => $this->table_name,
			'catalogtree' => getMenusSelectLink($this->siteUrl."adm/".$this->table_name."/", $this->menuarrtree, $parent_id, 0),
			'id' => $this->Header_GetSortLink($mainlink, $this->primary_key, "ID"),
			't_title' => $this->Header_GetSortLink($mainlink, "name", "Название"),
			'date' => $this->Header_GetSortLink($mainlink, "news_date", "Дата"),
			'order_index' => $this->Header_GetSortLink($mainlink, "order_index"),
			'is_active' => $this->Header_GetSortLink($mainlink, "is_active"),
		);
		
		// запрос получения списка статей
		$sql="SELECT Count(*) ".$fromwhere;		
		$total = $this->db->GetOne ($sql, 0);		
		if ($total > 0) 
		{			
			$this->Get_Valid_Page($total);
			$maxIndex = $this->db->GetOne ("SELECT MAX(order_index) ".$fromwhere, 0);
            $minIndex = $this->db->GetOne ("SELECT MIN(order_index) ".$fromwhere, 0);
			$sql="SELECT ".$this->table_name.".".$this->primary_key.", news_date, name, order_index, is_active, is_filter ".$fromwhere;		
			$result=$this->db->ExecuteSql($sql, $this->Pages_GetLimits());
			$token = $this->GetSession("token", false);
			while ($row = $this->db->FetchArray ($result))	
			{				
				$id = $row[$this->primary_key];			
				$name = $this->dec($row['name']);								
				$news_date = date("d-m-Y", $this->dec($row['news_date']));
				
				$activeLink = "/adm/".$this->table_name."/activate?id=".$id."&token=".$token;
				$activeImg = ($row['is_active'] == 0)?"times":"check";
				$editLink = "/adm/".$this->table_name."/edit?id=".$id;
				
				$delLink = "/adm/".$this->table_name."/del?id=".$id."&token=".$token;	
				 if ($parent_id == 0)
				{
					$orderLink	= $this->OrderLink($row["order_index"], $minIndex, $maxIndex, $id);				
				}
				else
				{
					$orderLink	= "";
				}
				
				$data ["article_row"][] = array (
					"id" => $id,
					"name" => $name,
					"date" => $news_date,
					
					"order_index" => $orderLink,
					"active" => $activeLink,
					"active_img" => $activeImg,
                    "edit" => $editLink,
                    "del" => $delLink,
					"status" => ($row['is_active'] == 1)?"success":"danger",
					"is_filter" => ($row['is_filter'] == 1)?"edit":"gray",
				);						
			}
			$this->db->FreeResult ($result);
			$data['pages'] = $this->Pages_GetLinks($total, $mainlink);
		}
		else
		{
			$data['empty_row'] = "Нет записей в базе данных";
		}
		
		return $data;
	}
	
	function GetActivate()
	{
		$this->GetToken();
		$id = $this->GetGP ("id");
		$this->history("Изменение статуса", $this->table_name, "", $id);
		$this->Activate($this->primary_key);
	}
	
	function Delete()
	{
		$this->GetToken();
		$id = $this->GetGP ("id");
		$sql = "SELECT title FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
		$name = $this->db->GetOne($sql);
		$this->history("Удаление", $this->table_name, $name, $id);
		$sql = "DELETE FROM fields_value WHERE field_id='$id'";
		$this->db->ExecuteSql($sql);
		$sql = "DELETE FROM field_category WHERE field_id='$id'";
		$this->db->ExecuteSql($sql);
		$sql = "DELETE FROM fields_item WHERE field_id='$id'";
		$this->db->ExecuteSql($sql);
		$this->delElement($this->primary_key);				
	}
	
	function GetFieldsArray($id) {	
		$sql= "Select field_category.category_id From `fields`, `field_category` Where fields.field_id = field_category.field_id and field_category.field_id = '$id' Order by order_index asc";
		$array = array();
		$result = $this->db->ExecuteSql ($sql);
		if ($result)
		{			
			while ($row = $this->db->FetchArray ($result)) {
				$array[$row['category_id']] = "";
			}
			$this->db->FreeResult  ($result);
		}
		
		return $array;		
	}
	
	// список тегов для продукции, статей и т. д., в виде select
	// $value - массив текущих значений, что бы сделать их активными
	// $module - модуль для которого выводить список категорий
	function GetFields($value)
	{						
		$toRet = "<select data-placeholder='Выберите категории' name='fields[]' id='fields' class='chosen-select' multiple> \r\n
		<option value=''></option> \r\n";		
		foreach ($this->menuarr as $row)
		{												
			$selected = (array_key_exists ($row["menu_id"], $value)) ? "selected" : "";
			$toRet .= "<option value='".$row["menu_id"]."' ".$selected.">".$row['title']."</option>\r\n";
		}		
		return $toRet."</select>\r\n";;	
	}
	
	function Edit()
	{
		$id = $this->GetID("id");
		
		$array_fields = $this->GetFieldsArray($id);
		
		$row = $this->db->GetEntry ("SELECT * FROM ".$this->table_name." WHERE ".$this->primary_key."='$id'");	   
		
		$data = array (
			"name" => $this->dec($row["name"]),
			"name_error" => $this->GetError("name"),
			"news_date" => $row["news_date"],
			"unit" => $this->dec($row["unit"]),
			"fields" => $this->GetFields($array_fields),
			"url" => $row["url"],
			"is_filter" => $row["is_filter"],
			"main_title" => "Редактирование пункта",
			'table_name' => $this->table_name,
			"action" => "update",
			"token" => $this->GetSession("token", false),
			"update" => array (
				"update_date" => date ("H:i:s d-m-Y", $row["update_date"]),
				"update_user" =>$this->db->GetOne ("SELECT username FROM `admins` WHERE admin_id='".$row["update_user"]."'"),	  
			),
		);
				
		return $data;
	}
	
	function Add()
	{		
		$data = $this->Form_Valid();
		$data["news_date"] = time();
		$data["fields"] = $this->GetFields(array());
		$data["main_title"] = "Добавление пункта";
		$data["table_name"] = $this->table_name;
		$data["name_error"] = "";
		$data["action"] = "insert";	
		$data["token"] = $this->GetSession("token", false);
		return $data;
	}
	
	function Insert()
	{
		$this->GetToken();
		$id = $this->GetID("id", 0);
		$data = $this->Form_Valid($id);
		if ($this->errors['err_count'] > 0) 
		{
			return false;
		}
		else
		{			
			$data["is_active"] = '0';
			$data["author"] = $_SESSION['A_ID'];
			$data["update_date"] = time();
			$data["update_user"] = $_SESSION['A_ID'];	
			$data["order_index"] = $this->db->GetOne ("SELECT MAX(order_index) FROM ".$this->table_name, 0)+1;	
			
			$sql = "Insert Into ".$this->table_name." ".ArrayInInsertSQL ($data);					
			$this->db->ExecuteSql($sql);
			$id = $this->db->GetInsertID ();
			$i=0;
			while (isset($_POST['fields'][$i]))
			{				
				$sql = "Insert Into field_category (category_id, field_id) VALUES  ('".$_POST['fields'][$i]."', '$id')";
				$this->db->ExecuteSql($sql);
				$i++;
			}
			if ($i==0)
			{
				$sql = "Insert Into field_category (category_id, field_id) VALUES  (NULL, '$id')";
				$this->db->ExecuteSql($sql);
			}
			$this->history("Добавление", $this->table_name, "", $id);
			return true;
		}
	}
	
	function Update()
	{
		$this->GetToken();
		$id = $this->GetID("id", 0);
		$data = $this->Form_Valid($id);
		
		if ($this->errors['err_count'] > 0) 
		{
			return false;
		}
		else
		{						
			$data["update_date"] = time();
			$data["update_user"] = $_SESSION['A_ID'];			
			$sql = "UPDATE ".$this->table_name." SET ".ArrayInUpdateSQL ($data)." WHERE {$this->primary_key}='$id'";
			$this->db->ExecuteSql($sql);
			
			
			$sql = "DELETE field_category FROM field_category, fields WHERE field_category.field_id = '$id' and fields.field_id = field_category.field_id";
			$this->db->ExecuteSql($sql);
			$i=0;
			while (isset($_POST['fields'][$i]))
			{				
				$sql = "Insert Into field_category (category_id, field_id) VALUES  ('".$_POST['fields'][$i]."', '$id')";
				$this->db->ExecuteSql($sql);
				$i++;
			}
			
			if ($i==0)
			{
				$sql = "Insert Into field_category (category_id, field_id) VALUES  (NULL, '$id')";
				$this->db->ExecuteSql($sql);
			}
			
			$this->history("Изменение", $this->table_name, "", $id);
			return true;
		}
	}
	
	function Edit_error($edit = "update")
	{
		$id = $this->GetID("id", 0);
		$data = $this->Form_Valid($id);
		$data["fields"] = $this->GetFields(array());
		$data["name_error"] = $this->GetError("name");
		$data["table_name"] = $this->table_name;
		if ($edit == "update") 
		{
			$row = $this->db->GetEntry ("SELECT * FROM ".$this->table_name." WHERE ".$this->primary_key."='$id'");	   
			$data["main_title"] = "Редактирование пункта";
			$data["action"] = "update";
			
			$data["update"] = array (
				"update_date" => date ("H:i:s d-m-Y", $row["update_date"]),
				"update_user" =>$this->db->GetOne ("SELECT username FROM `admins` WHERE admin_id='".$_SESSION["A_ID"]."'"),	  
			);				
		}
		else
		{						
			$data["main_title"] = "Добавление пункта";
			$data["action"] = "insert";							
		}
		return $data;
	}
	
	function Form_Valid($edit = false)
	{
		$data["name"] = $this->enc($this->GetValidGP ("name", "Название", VALIDATE_NOT_EMPTY));		
		$data["unit"] = $this->enc($this->GetGP ("unit", ""));		
		$data["url"] = $this->GetGP("url", "");
		$id = $this->GetID("id");
		if ($data["url"] == "")
		{
			$data["url"] = TransUrl($data["name"]);
		}
		else
		{
			$data["url"] = TransUrl($data["url"]);
		}
		if (preg_match ("/^[a-z0-9-_]+$/", $data["url"]))
		{
			$url = $data["url"];
			$where = ($edit)?" and ".$this->primary_key." <> '$id'":"";
			$i=0;	
			do {
				if ($i != 0)
				{								
					$data["url"] = $url.$i;
				}
				$total = $this->db->GetOne ("Select Count(*) From ".$this->table_name." Where url='".$data["url"]."' $where", 0);			
				$i++;
			} while ($total > 0);	

		}
	    $data["is_filter"] = $this->GetGP("is_filter", 0);   	
		$data["news_date"] = strtotime ($this->GetGP("news_date", ""));	
		
		return $data;
	}
	
	
	function Up()
	{
		$id = $this->GetGP ("id");        
        $curIndex = $this->db->GetOne ("SELECT order_index FROM ".$this->table_name." WHERE ".$this->primary_key."='$id'", 0);
        $nextID = $this->db->GetOne ("SELECT ".$this->primary_key." FROM ".$this->table_name." WHERE order_index<$curIndex Order By order_index Desc Limit 1", 0);
        $nextIndex = $this->db->GetOne ("SELECT order_index FROM ".$this->table_name." WHERE ".$this->primary_key."='$nextID'", 0);

		if ($nextID != 0)
        {       
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$nextIndex' WHERE ".$this->primary_key."='$id'");
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$curIndex' WHERE ".$this->primary_key."='$nextID'");
			$this->history("Изменение позиции up", $this->table_name, "", $id);
        }

        //$this->Redirect ("/adm/".$this->table_name);
	}
	
	function Down ()
    {
        $id = $this->GetGP ("id");        
       
        $curIndex = $this->db->GetOne ("SELECT order_index From ".$this->table_name." WHERE ".$this->primary_key."='$id'", 0);
        $nextID = $this->db->GetOne ("SELECT ".$this->primary_key." From ".$this->table_name." WHERE order_index>$curIndex Order By order_index Asc Limit 1", 0);
        $nextIndex = $this->db->GetOne ("SELECT order_index From ".$this->table_name." WHERE ".$this->primary_key."='$nextID'", 0);

        if ($nextID != 0)
        {
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$nextIndex' WHERE ".$this->primary_key."='$id'");
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$curIndex' WHERE ".$this->primary_key."='$nextID'");
			$this->history("Изменение позиции down", $this->table_name, "", $id);
        }

         //$this->Redirect ("/adm/".$this->table_name);
    }
	
	
	/*function Up()
	{
		$this->GetToken();
		$id = $this->GetGP ("id");   
		
		
		$parent_id = $this->GetSession("PARENTFIELDID", 0);
		if ($parent_id == 0)
			$fromwhere = "FROM ".$this->table_name." WHERE 1";
		else
		{
			$fromwhere = "FROM ".$this->table_name.", field_category WHERE category_id = '$parent_id' and field_category.field_id = fields.field_id";
		}		
        $curIndex = $this->db->GetOne ("SELECT order_index FROM ".$this->table_name." WHERE ".$this->primary_key."='$id'", 0);
        $nextID = $this->db->GetOne ("SELECT ".$this->table_name.".".$this->primary_key." ".$fromwhere." and order_index<$curIndex Order By order_index Desc Limit 1", 0);
        $nextIndex = $this->db->GetOne ("SELECT order_index FROM ".$this->table_name." WHERE ".$this->primary_key."='$nextID'", 0);
		if ($nextID != 0)
        {       
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$nextIndex' WHERE ".$this->primary_key."='$id'");
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$curIndex' WHERE ".$this->primary_key."='$nextID'");
			$this->history("Изменение позиции up", $this->table_name, "", $id);
        }

        //$this->Redirect ("/adm/".$this->table_name);
	}
	
	function Down ()
    {
        $this->GetToken();
		$id = $this->GetGP ("id");        
       
	   
	   $parent_id = $this->GetSession("PARENTFIELDID", 0);
		if ($parent_id == 0)
			$fromwhere = "FROM ".$this->table_name." WHERE 1";
		else
		{
			$fromwhere = "FROM ".$this->table_name.", field_category WHERE category_id = '$parent_id' and field_category.field_id = fields.field_id";
		}
	   
        $curIndex = $this->db->GetOne ("SELECT order_index From ".$this->table_name." WHERE ".$this->primary_key."='$id'", 0);
		$nextID = $this->db->GetOne ("SELECT ".$this->table_name.".".$this->primary_key." ".$fromwhere." and order_index>$curIndex Order By order_index asc Limit 1", 0);
		
		$nextIndex = $this->db->GetOne ("SELECT order_index FROM ".$this->table_name." WHERE ".$this->primary_key."='$nextID'", 0);
		
        if ($nextID != 0)
        {
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$nextIndex' WHERE ".$this->primary_key."='$id'");
            $this->db->ExecuteSql ("UPDATE ".$this->table_name." Set order_index='$curIndex' WHERE ".$this->primary_key."='$nextID'");
			$this->history("Изменение позиции down", $this->table_name, "", $id);
        }

         //$this->Redirect ("/adm/".$this->table_name);
    }*/
}