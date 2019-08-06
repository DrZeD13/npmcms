<?php
/*
структура таблицы
comment_id	 Идентификатор
news_date	Дата создания
title Название
head_title Название <title>
url  Адрес блога
description	Описание блога
keywords Ключевые слова
first_name Имя создателя
email E-mail адрес
short_text кртакое описание
text подробное описание
is_active	Флаг активности
new Флаг нового сообщения
*/
class Model_Adm_Orders extends Model 
{

	var $table_name = 'orders';
	var $primary_key = 'order_id';
	var $orderType = array ("order_id"=>"", "company" =>"", "name" =>"", "inn" =>"", "sum" =>"", "phone" =>"", "email" =>"", "address" =>"", "news_date"=>"", "user_id" => "", "comment" => "", "status" =>"");
	var $orderDefault = "news_date";
	var $rowsPerPage = 20;
	
	public function get_data() 
	{					
		$mainlink = "/adm/".$this->table_name."/?";
		
		$getstatus = $this->GetGP("status", -1);
        if ($getstatus == -1)
        {
            $getstatus = $this->GetSession("GetStatus", "0");
        }
        else {
            $_SESSION['GetStatus'] = $getstatus;
        }
		if ($getstatus == "all")
		{
			$wherestatus = "";
		}
		else
		{
			$wherestatus = "and status = '".$getstatus."'";
		}
		$getuser = $this->GetGP("user_id", -1);
        if ($getuser == -1)
        {
            $getuser = $this->GetSession("GetUser", "all");
        }
        else {
            $_SESSION['GetUser'] = $getuser;
        }
		if ($getuser == "all")
		{
			$whereuser = "1";
		}
		else
		{
			if ($getuser == 0)
			{
				$whereuser = "orders.user_id is NULL";
			}
			else
			{
				$whereuser = "orders.user_id = '".$getuser."'";
			}
		}
		$datewhere = "";
		$date_start = $this->GetGP("date_start", "");		
		if (!empty($date_start))
		{
			$date_start = strtotime($date_start);
			$datewhere = " and orders.news_date >= ".$date_start;			
		}
		else
		{
			$date_start = time() - 3600*24;
		}
		$date_end = $this->GetGP("date_end", "");
		if (!empty($date_end))
		{
			$date_end = strtotime($date_end);
			$datewhere .= " and orders.news_date <= ".$date_end;			
		}
		else
		{
			$date_end = time();
		}
		$search = $this->GetGP ("search", "");
		$type = $this->GetGP_SQL ("field", "orders.name");
		// поиск по статьям
		if ($search == "") 
		{
			$fromwhere = "FROM ".$this->table_name." LEFT JOIN users on orders.user_id = users.user_id WHERE 1 and $whereuser $datewhere $wherestatus ORDER BY ".$this->orderBy." ".$this->orderDir;			
			$fromwheretotal = "FROM ".$this->table_name." LEFT JOIN users on orders.user_id = users.user_id WHERE 1 and $whereuser $datewhere $wherestatus";			
		}
		else
		{
			// предусмотрен поиск по любому полю
			$mainlink .= "search=".$search."&";			
			$mainlink .= "type=".$type."&";
			$fromwhere = "FROM ".$this->table_name." LEFT JOIN users on orders.user_id = users.user_id WHERE $type LIKE  '%$search%' ORDER BY ".$this->orderBy." ".$this->orderDir;
			$fromwheretotal = "FROM ".$this->table_name." LEFT JOIN users on orders.user_id = users.user_id WHERE $type LIKE  '%$search%'";
		}
		
		$sql="SELECT Count(*) ".$fromwheretotal;
		$total = $this->db->GetOne ($sql, 0);	
		
		// запрос для получения шапки таблицы
		$title = $this->GetAdminTitle($this->table_name);
		$token = $this->GetSession("token", false);
		$data = array (
			'title' => $title." ($total)",
			'main_title' => $title." ($total)",
			'search' => $search,
			'date_start' => $date_start,
			'date_end' => $date_end,
			'table_name' => $this->table_name,
			'getStatus' => $this->GetStatusLink($getstatus),
			'field' => $this->GetSearch ($type),
			'getUserSelect' => $this->GetUserSelect($getuser, true, "orders"),
			'token' => $token,
			'user_id' => $this->Header_GetSortLink($mainlink, $this->primary_key, "<i class='fa fa-users'></i>"),
			'id' => $this->Header_GetSortLink($mainlink, $this->primary_key, "ID"),
			'company' => $this->Header_GetSortLink($mainlink, "company", "Компания"),
			'name' => $this->Header_GetSortLink($mainlink, "name", "Имя"),
			'date' => $this->Header_GetSortLink($mainlink, "news_date", "Дата"),
			'sum' => $this->Header_GetSortLink($mainlink, "sum", "Сумма"),
			'phone' => $this->Header_GetSortLink($mainlink, "phone", "Телефон"),
			'address' => $this->Header_GetSortLink($mainlink, "address", "Адрес"),
			'comment' => $this->Header_GetSortLink($mainlink, "comment", "Комментарий"),
			'status' => $this->Header_GetSortLink($mainlink, "status", "Статус"),
		);
		
		
	
		if ($total > 0) 
		{			
			$this->Get_Valid_Page($total);
			// запрос получения списка записей			
			$sql="SELECT users.company, orders.*, (SELECT sum(price*quantity) FROM order_product WHERE order_product.order_id = orders.order_id) as sum, concat('г. ', orders.city, ', ул. ', orders.street, ', д. ', orders.dom, ', оф. ', orders.office) as adr ".$fromwhere;	
			/*echo $sql;
			exit;	*/
			$result=$this->db->ExecuteSql($sql, $this->Pages_GetLimits());
			while ($row = $this->db->FetchArray ($result))	
			{				
				$id = $row[$this->primary_key];
				$data ["row"][] = array (
					"id" => $id,
					"company" => $this->dec($row['company']),
					"name" => $this->dec($row['name']),
					"phone" => $this->dec($row['phone']),
					"address" => $this->dec($row['adr']),
					"sum" => $this->dec($row['sum']),
					"comment" => mb_substr(strip_tags($this->dec($row['comment'])), 0, 70),
					"date" => date("d-m-Y H:i", $this->dec($row['news_date'])),
					"new" => ($row['new']=="0")?"":"<span class='t_new'>new</span>",
					"user_id" => ($row['user_id'] > 0)?"<a href='/adm/users/edit?id=".$row['user_id']."'><i class='fa fa-user'></i></a>":"<i class='fa fa-user-times gray' title='Не зарегистрированный пользователь'></i>",
                    "edit" => "/adm/".$this->table_name."/edit?id=".$id,
                    "del" => "/adm/".$this->table_name."/del?id=".$id."&token=".$token,
					"status" => $this->GetStatusName($row['status']),
					"status_number" => $row['status'],
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
		// удаляем все товары принадлежащие этому заказу
		$this->db->ExecuteSql ("Delete From `order_product` Where order_id='$id'");
		$sql = "SELECT ".$this->primary_key." FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
		$name = $this->db->GetOne($sql);
		$this->history("Удаление", $this->table_name, $name, $id);
		$this->delElement($this->primary_key);
		$this->Redirect ("/adm/".$this->table_name);
	}
	
	function Edit()
	{
		$id = $this->GetID("id");
		$this->FlagNewFalse($id);
		$row = $this->db->GetEntry ("SELECT * FROM ".$this->table_name." WHERE {$this->primary_key}='$id'");	   
		
		$data = array (
			'title' => "Заказ №".$row[$this->primary_key],
			'main_title' => "Заказ №".$row[$this->primary_key],

			
			'sum' => "",									
						
			"status" => $this->GetStatus($row['status']),
			"name" => $this->dec($row["name"]),
			"name_error" => $this->GetError("name"),
			"news_date" => $row["news_date"],
			"phone" => $this->dec($row["phone"]),
			"email" => $this->dec($row["email"]),
			"city" => $this->dec($row["city"]),
			"street" => $this->dec($row["street"]),
			"dom" => $this->dec($row["dom"]),
			"office" => $this->dec($row["office"]),
			"comment" => $this->dec($row["comment"]),
			"user_id" => $this->GetUserSelect($row["user_id"]),
			'table_name' => $this->table_name,
			"action" => "update",
			"token" => $this->GetSession("token", false),
		);
		
		$sql = "SELECT order_product_id, shop_id, name, price, quantity FROM order_product WHERE order_id = '".$row[$this->primary_key]."'";
		$result=$this->db->ExecuteSql($sql);		
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{				
				$data ["row"][] = array (
					"id" => $row['order_product_id'],
					"shop_id" => $row['shop_id'],
					"name" => $this->dec($row['name']),
					"price" => $this->dec($row['price']),
					"quantity" => $this->dec($row['quantity']),		
				);						
			}
			$this->db->FreeResult ($result);
		}
		else
		{
			$data['empty_row'] = "Нет товаров в заказе";
		}
		return $data;
	}
	
	function Add()
	{		
		$data = $this->Form_Valid();
		$data["news_date"] = time();
		$data["main_title"] = "Добавление заказа";
		$data["title"] = "Добавление заказа";		
		$data['empty_row'] = "Нет товаров в заказе";		
		$data["table_name"] = $this->table_name;
		$data["name_error"] = "";
		$data["action"] = "insert";
		$data["token"] = $this->GetSession("token", false);
		$data["user_id"] = $this->GetUserSelect();
		$data["editor"] = $this->editor();

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
			
			$sql = "Insert Into ".$this->table_name." ".ArrayInInsertSQL ($data);
			$this->db->ExecuteSql($sql);
			$id = $this->db->GetInsertID ();
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
			$sql = "UPDATE ".$this->table_name." SET ".ArrayInUpdateSQL ($data)." WHERE {$this->primary_key}='$id'";
			
			$this->db->ExecuteSql($sql);
			$this->history("Изменение", $this->table_name, "", $id);
			$this->Redirect ("/adm/".$this->table_name);
		}
	}
	
	function Edit_error($edit = "update")
	{
		$id = $this->GetID("id", 0);
		$data = $this->Form_Valid($id);
		$data["table_name"] = $this->table_name;
		$data["name_error"] = $this->GetError("name");
		$data["user_id"] = $this->GetUserSelect($data["user_id"]);
		if ($edit == "update") 
		{
			$data["main_title"] = "Редактирование заказа №".$id;
			$data["action"] = "update";
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
		$data["name"] = $this->enc($this->GetValidGP ("name", "Имя", VALIDATE_NOT_EMPTY));		
		$data["email"] = $this->enc($this->GetGP ("email", ""));	
		$data["phone"] = $this->enc($this->GetGP ("phone", ""));	
		$data["status"] = $this->enc($this->GetGP ("status", 0));
		
		$data["city"] = $this->enc($this->GetGP ("city", 0));	
		$data["street"] = $this->enc($this->GetGP ("street", 0));	
		$data["dom"] = $this->enc($this->GetGP ("dom", 0));	
		$data["office"] = $this->enc($this->GetGP ("office", 0));	

		$data["comment"] = $this->enc($this->GetGP ("comment"));		
		//$data["user_id"] = $this->enc($this->GetGP ("user_id"));		
		$data["news_date"] = strtotime ($this->GetGP("news_date", ""));
		
		return $data;
	}
	
	function GetSearch ($field)
	{
		$res = "<select name='field' class='select-search'>";	
		$selected = ($field == "users.company")?"selected":"";
		$res .= "<option value='users.company' $selected>Компания</option>";
		$selected = ($field == $this->primary_key)?"selected":"";
		$res .= "<option value='".$this->primary_key."' $selected>Номер</option>";
		$selected = ($field == "users.inn")?"selected":"";
		$res .= "<option value='users.inn' $selected>ИНН</option>";
		return $res."</select>";
	}
	
	function GetStatus ($field)
	{
		$res = "<select name='status' class='select-search'>";	
		$selected = ($field == "0")?"selected":"";
		$res .= "<option value='0' $selected>Новый</option>";
		$selected = ($field == "1")?"selected":"";
		$res .= "<option value='1' $selected>Отгружен</option>";
		$selected = ($field == "2")?"selected":"";
		$res .= "<option value='2' $selected>Выполнен</option>";
		$selected = ($field == "3")?"selected":"";
		$res .= "<option value='3' $selected>Отменен</option>";
		$selected = ($field == "4")?"selected":"";
		$res .= "<option value='4' $selected>Возврат</option>";
		return $res."</select>";
	}
	
	function GetStatusLink ($status = 0)
	{
		$res = "<select name='status' id='status' onchange=\"top.location=this.value\" class='select-search'>\r\n";
		$selected = ($status == "all")?"selected":"";	
		$res .= "<option value='".$this->siteUrl."adm/".$this->table_name."/?status=all' $selected>Все</option>";
		$selected = ($status == "0")?"selected":"";	
		$res .= "<option value='".$this->siteUrl."adm/".$this->table_name."/?status=0' $selected>Новый</option>";
		$selected = ($status == "1")?"selected":"";
		$res .= "<option value='".$this->siteUrl."adm/".$this->table_name."/?status=1' $selected>Отгружен</option>";
		$selected = ($status == "2")?"selected":"";
		$res .= "<option value='".$this->siteUrl."adm/".$this->table_name."/?status=2' $selected>Выполнен</option>";
		$selected = ($status == "3")?"selected":"";
		$res .= "<option value='".$this->siteUrl."adm/".$this->table_name."/?status=3' $selected>Отменен</option>";
		$selected = ($status == "4")?"selected":"";
		$res .= "<option value='".$this->siteUrl."adm/".$this->table_name."/?status=4' $selected>Возврат</option>";
		return $res."</select>";
	}
	
}