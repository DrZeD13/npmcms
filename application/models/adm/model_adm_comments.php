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
class Model_Adm_Comments extends Model 
{

	var $table_name = 'comments';
	var $primary_key = 'comment_id';
	var $orderType = array ("comment_id"=>"", "name" =>"", "ip" =>"", "news_date"=>"", "is_active" => "", "user_id" => "", "module" => "", "comment" => "");
	var $orderDefault = "news_date";
	var $rowsPerPage = 20;
	
	public function get_data() 
	{					
		$mainlink = "/adm/".$this->table_name."/?";
		
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
			$whereuser = "user_id = '".$getuser."'";
		}
		
		$search = $this->GetGP ("search", "");
		// поиск по статьям
		if ($search == "") 
		{
			$fromwhere = "FROM ".$this->table_name." WHERE 1 and $whereuser ORDER BY ".$this->orderBy." ".$this->orderDir;			
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
			$fromwhere = "FROM ".$this->table_name." WHERE $type LIKE  '%$search%' and $whereuser ORDER BY ".$this->orderBy." ".$this->orderDir;
		}
		
		$sql="SELECT Count(*) ".$fromwhere;
		$total = $this->db->GetOne ($sql, 0);	
		
		// запрос для получения шапки таблицы
		$title = $this->GetAdminTitle($this->table_name);
		$token = $this->GetSession("token", false);
		$data = array (
			'title' => $title." ($total)",
			'main_title' => $title." ($total)",
			'search' => $search,
			'table_name' => $this->table_name,
			'getUserSelect' => $this->GetUserSelect($getuser, true),
			'token' => $token,
			'user_id' => $this->Header_GetSortLink($mainlink, $this->primary_key, "<i class='fa fa-users'></i>"),
			'id' => $this->Header_GetSortLink($mainlink, $this->primary_key, "ID"),
			't_title' => $this->Header_GetSortLink($mainlink, "name", "Имя"),
			'date' => $this->Header_GetSortLink($mainlink, "news_date", "Дата"),
			'comment' => $this->Header_GetSortLink($mainlink, "comment", "Комментарий"),
			'ip' => $this->Header_GetSortLink($mainlink, "ip", "IP"),
			'link' => $this->Header_GetSortLink($mainlink, "module", "Модуль"),
			'is_active' => $this->Header_GetSortLink($mainlink, "is_active"),
		);
		
		
	
		if ($total > 0) 
		{			
			$this->Get_Valid_Page($total);
			// запрос получения списка записей
			$sql="SELECT {$this->primary_key}, news_date, name, comment, ip, user_id, is_active, new, module, parent_id ".$fromwhere;			
			$result=$this->db->ExecuteSql($sql, $this->Pages_GetLimits());
			while ($row = $this->db->FetchArray ($result))	
			{				
				$id = $row[$this->primary_key];
				$module = $row["module"];
				$parent_id = $row["parent_id"];
				$primary = str_replace("s", "_id", $module);
				switch ($module)
				{
					case "products":
						$sql = "SELECT parent_id, url FROM products WHERE product_id = '$parent_id'";
						$row1 = $this->db->GetEntry($sql);
						$fullurl = GetLinkCat($this->menuarrtree, $row1["parent_id"]);
						$link = $this->siteUrl.CATALOG_LINK."/".$fullurl.$row1['url'];
						$linktitle = $this->GetAdminTitle("products");
					break;
					case "articles":
						$sql = "SELECT url FROM articles WHERE article_id = '$parent_id'";
						$url = $this->db->GetOne($sql);
						$link = "/".ARTICLES_LINK."/".$url;
						$linktitle = $this->GetAdminTitle("articles");
					break;
				}
				$data ["row"][] = array (
					"id" => $id,
					"name" => $this->dec($row['name']),
					"comment" => mb_substr(strip_tags($this->dec($row['comment'])), 0, 70),
					"ip" => $this->dec($row['ip']),
					"date" => date("d-m-Y", $this->dec($row['news_date'])),
					"new" => ($row['new']=="0")?"":"<span class='t_new'>new</span>",
					"link" => $link,
					"linktitle" => $linktitle,
					"user_id" => ($row['user_id'] > 0)?"<a href='/adm/users/edit?id=".$row['user_id']."'><i class='fa fa-user'></i></a>":"<i class='fa fa-user-times gray' title='Не зарегистрированный пользователь'></i>",
					"active" => "/adm/".$this->table_name."/activate?id=".$id."&token=".$token,
					"active_img" => ($row['is_active'] == 0)?"times":"check",
                    "edit" => "/adm/".$this->table_name."/edit?id=".$id,
                    "del" => "/adm/".$this->table_name."/del?id=".$id."&token=".$token,
                    "spam" => "/adm/".$this->table_name."/spam?id=".$id."&token=".$token,
					"status" => ($row['is_active'] == 1)?"success":"danger",
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
		$sql = "SELECT name FROM ".$this->table_name." WHERE ".$this->primary_key." = '".$id."'";
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
			"name" => $this->dec($row["name"]),
			"name_error" => $this->GetError("name"),
			"news_date" => $row["news_date"],
			"email" => $this->dec($row["email"]),
			"comment" => $this->dec($row["comment"]),
			"user_id" => $this->GetUserSelect($row["user_id"]),
			"main_title" => "Редактирование пункта",
			'table_name' => $this->table_name,
			"action" => "update",
			"token" => $this->GetSession("token", false),
		);
		
		return $data;
	}
	
	function Add()
	{		
		$data = $this->Form_Valid();
		$data["news_date"] = time();
		$data["main_title"] = "Добавление пункта";
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
			$data["main_title"] = "Редактирование пункта";
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
		$data["comment"] = $this->enc($this->GetGP ("comment"));		
		$data["user_id"] = $this->enc($this->GetGP ("user_id"));		
		$data["news_date"] = strtotime ($this->GetGP("news_date", ""));
		
		return $data;
	}
	
	function DelNotActivate()
	{
		$sql = "DELETE FROM ".$this->table_name." WHERE is_active='0'";
		$this->db->ExecuteSql ($sql);
		$this->Redirect ("/adm/".$this->table_name);
	}
	
}