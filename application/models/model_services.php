<?php
/*
структура таблицы
service_id	Идентификатор 
news_date	Дата создания 
title Название
head_title Название <title>
url  Адрес
description	Описание 
keywords Ключевые
filename картинка
short_text кртакое
text подробное
is_active	Флаг активности.
author автор
update_date дата обновления
update_user id пользователя который обновил
*/
class Model_Services extends Model 
{

	private $table_name = '`services`';
	var $rowsPerPage = 12; //выводить на страницу по умолчанию
    var $rowsOptions = array (12, 24, 48); //количество записей на страницу
	
	public function get_data() 
	{
		$mainlink = $this->siteUrl.SERVICES_LINK."/";
		if (!$this->Valid_Url_Short(SERVICES_LINK)) 		
		{
			$this->error404();
		}
		
		// запрос для получения шапки
		$sql = "SELECT title, head_title, description, keywords, text FROM `menus` WHERE url = '".SERVICES_LINK."/' and is_active='1'";
		$data = $this->Get_Header($sql);
		$data["nav"] = MAIN_NAV.GetNav($this->menu, $this->cid);
		// запрос получения списка статей
		$fromwhere = "FROM ".$this->table_name." WHERE is_active='1' AND news_date < ".time()." ORDER BY news_date desc";
		$sql="SELECT Count(*) ".$fromwhere;
		$total = $this->db->GetOne ($sql, 0);		
		if ($total > 0) 
		{
			$sql="SELECT name, short_text, news_date, url, filename ".$fromwhere;
			$result=$this->db->ExecuteSql($sql, $this->Pages_GetLimits());
			while ($row = $this->db->FetchArray ($result))	
			{				
				$url = $row['url'];
				$title = $this->dec($row['name']);
				$short_text = $this->dec($row['short_text']);
				if (substr_count($url, "/") == 0)
				{
					$link = $mainlink.$url;
				}
				else
				{
					$link = $url;
				}
				
				if ($row['filename'] != "") {
				$extension = substr($row['filename'], -3);
				$filename = substr($row['filename'], 0, -4)."_small.".$extension;
				$filename = $this->siteUrl."media/services/".$filename;
				} 
				else {
					$filename = "";
				}
				$data ["article_row"][] = array (
					"link" => $link,
					"title" => $title,
					"news_date" => date("d-m-Y", $row['news_date']),
					"short_descr" => $short_text,
					"filename" => $filename,
				);
				/*$genre = $row['genre'];				  
				$row1 = $this->db->GetEntry ("Select url, name From category Where id = '$genre'");
				$link_cat = $this->siteUrl.ARTICLES_LINK.$row1['url'];
			*/								
			}
			$this->db->FreeResult ($result);
			$data['pages'] = $this->Pages_GetLinks_Site($total, $mainlink."?");
		}
		else
		{
			$data['empty_row'] = "Нет записей в базе данных";
		}
		
		return $data;
	}
	
	public function get_view() 
	{
		if (!$this->Valid_Url(SERVICES_LINK)) 		
		{
			$this->error404();
		}
		$routes = parse_url($_SERVER['REQUEST_URI']);
		$temp = explode('/', $routes['path']);
		$url = $this->db->RealEscapeString($temp[count($temp)-1]);	
		$sql = "SELECT title, head_title, description, keywords, text, news_date, filename FROM ".$this->table_name." WHERE url = '".$url."' AND is_active='1'";
		$row = $this->db->GetEntry($sql);
		if (!$row)
		{
			$this->error404();
		}
		$data = array(
			"title" => $row["title"], 
			"news_date" => date("d-m-Y", $row['news_date']),
			"descr"=> $this->dec($row["text"]),
			"head_title" =>$row["head_title"],
			"description" =>$row["description"],
			"keywords" =>$row["keywords"],
			"filename" => ($row["filename"] != "")?$this->siteUrl."media/services/".$row["filename"]:"",
		);		
		$data["nav"] = MAIN_NAV.GetNav($this->menu, $this->cid, false).$row["title"];
		return $data;		
	}

}
