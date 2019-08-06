<?php

class Model_Search extends Model 
{	
	var $rowsPerPage = 12; //выводить на страницу по умолчанию
    var $rowsOptions = array (12, 24, 36); //количество записей на страницу
	public function get_data() 
	{
		$search = $this->enc_search($this->GetGP_SQL("search", ""));
		$SiteName = $this->db->GetSetting ("SiteName");
		//echo($this->enc_search('<script src="script.js"></script>'));
		//exit ();
		if ($search=="")
		{
			$this->error404();
		}
		$data['head_title'] = $data['title'] = "Поиск";
		$data['keywords'] = "поиск, сайт, ".$SiteName;
		$data['description'] = "Результат поиска о сайту ".$SiteName." по запросу ".$search;
		$data['search'] = $search;
		
		$data["nav"] = MAIN_NAV."<li>Поиск</li>";
		
		$fulltotal =0;
		if (strlen($search) > 2) 
		{
			//по меню сайта
			/*$fromwhere=" FROM menus WHERE (text LIKE '%$search%' OR title LIKE '%$search%') AND is_active='1' ORDER BY news_date desc";
			$total = $this->db->GetOne("SELECT Count(*)".$fromwhere, 0);
			$fulltotal +=$total;
			if ($total > 0) 
			{
				$result = $this->db->ExecuteSql("SELECT title, text, url".$fromwhere);			
				while ($row = $this->db->FetchArray ($result))	
				{					
					$data["row"][] = array (
						"title" => $row["title"],
						"link" => "/".$row["url"],
						"text" => $this->Search($this->dec($row ['title']).".".$this->dec($row ['text']), $search),
						"category" => "Страницы сайта",
						"filename" => "",
					);
				}
				$this->db->FreeResult ($result);
			}
			
			// по новостям
			$fromwhere=" FROM news WHERE (short_text LIKE '%$search%' OR text LIKE '%$search%' OR title LIKE '%$search%') AND is_active='1' ORDER BY news_date desc";
			$total = $this->db->GetOne("SELECT Count(*)".$fromwhere, 0);
			$fulltotal +=$total;
			if ($total > 0) 
			{
				$result = $this->db->ExecuteSql("SELECT title, short_text, text, url, filename".$fromwhere);			
				while ($row = $this->db->FetchArray ($result))	
				{					
					if ($row['filename'] != "") 
					{
						$extension = substr($row['filename'], -3);
						$filename = substr($row['filename'], 0, -4)."_small.".$extension;
						$filename = $this->siteUrl."/media/news/".$filename;
					}
					else
					{
						$filename = "";
					}
					$data["row"][] = array (
						"title" => $row["title"],
						"link" => "/".NEWS_LINK."/".$row["url"],
						"text" => $this->Search($this->dec($row ['title']).".".$this->dec($row ['short_text']).".".$this->dec($row ['text']), $search),
						"category" => "Новости",
						"filename" => $filename,
					);
				}
				$this->db->FreeResult ($result);
			}
			
			// по статьям
			$fromwhere=" FROM articles WHERE (short_text LIKE '%$search%' OR text LIKE '%$search%' OR title LIKE '%$search%') AND is_active='1' ORDER BY news_date desc";
			$total = $this->db->GetOne("SELECT Count(*)".$fromwhere, 0);
			$fulltotal +=$total;
			if ($total > 0) 
			{
				$result = $this->db->ExecuteSql("SELECT title, short_text, text, url, filename".$fromwhere);			
				while ($row = $this->db->FetchArray ($result))	
				{					
					if ($row['filename'] != "") 
					{
						$extension = substr($row['filename'], -3);
						$filename = substr($row['filename'], 0, -4)."_small.".$extension;
						$filename = $this->siteUrl."/media/articles/".$filename;
					}
					else
					{
						$filename = "";
					}
					$data["row"][] = array (
						"title" => $row["title"],
						"link" => "/".ARTICLES_LINK."/".$row["url"],
						"text" => $this->Search($this->dec($row ['title']).".".$this->dec($row ['short_text']).".".$this->dec($row ['text']), $search),
						"category" => "Блог",
						"filename" => $filename,
					);
				}
				$this->db->FreeResult ($result);
			}
			
			// по объявления
			$fromwhere=" FROM actions WHERE (short_text LIKE '%$search%' OR text LIKE '%$search%' OR title LIKE '%$search%') AND is_active='1' ORDER BY news_date desc";
			$total = $this->db->GetOne("SELECT Count(*)".$fromwhere, 0);
			$fulltotal +=$total;
			if ($total > 0) 
			{
				$result = $this->db->ExecuteSql("SELECT title, short_text, text, url, filename".$fromwhere);			
				while ($row = $this->db->FetchArray ($result))	
				{					
					if ($row['filename'] != "") 
					{
						$extension = substr($row['filename'], -3);
						$filename = substr($row['filename'], 0, -4)."_small.".$extension;
						$filename = $this->siteUrl."/media/actions/".$filename;
					}
					else
					{
						$filename = "";
					}
					$data["row"][] = array (
						"title" => $row["title"],
						"link" => "/".ACTIONS_LINK."/".$row["url"],
						"text" => $this->Search($this->dec($row ['title']).".".$this->dec($row ['short_text']).".".$this->dec($row ['text']), $search),
						"category" => "Объявления",
						"filename" => $filename,
					);
				}
				$this->db->FreeResult ($result);
			}
			
			// по категориям
			$fromwhere=" FROM category WHERE (short_text LIKE '%$search%' OR text LIKE '%$search%' OR title LIKE '%$search%') AND is_active='1' ORDER BY news_date desc";
			$total = $this->db->GetOne("SELECT Count(*)".$fromwhere, 0);
			$fulltotal +=$total;
			if ($total > 0) 
			{
				$result = $this->db->ExecuteSql("SELECT title, short_text, text, url, module".$fromwhere);			
				while ($row = $this->db->FetchArray ($result))	
				{					
					switch ($row["module"])
					{
						case "products": $temp = CATALOG_LINK."/";break;
						case "articles": $temp = ARTICLES_LINK."/";break;
						default: $temp = "";
					}					
					$filename = "";
					$data["row"][] = array (
						"title" => $row["title"],
						"link" => $this->siteUrl.$temp."category/".$row['url'],
						"text" => $this->Search($this->dec($row ['title']).".".$this->dec($row ['short_text']).".".$this->dec($row ['text']), $search),
						"category" => "Категории",
						"filename" => $filename,
					);
				}
				$this->db->FreeResult ($result);
			}
			
			// по тегам
			$fromwhere=" FROM tags WHERE (short_text LIKE '%$search%' OR text LIKE '%$search%' OR title LIKE '%$search%') AND is_active='1' ORDER BY news_date desc";
			$total = $this->db->GetOne("SELECT Count(*)".$fromwhere, 0);
			$fulltotal +=$total;
			if ($total > 0) 
			{
				$result = $this->db->ExecuteSql("SELECT title, short_text, text, url, module".$fromwhere);			
				while ($row = $this->db->FetchArray ($result))	
				{					
					switch ($row["module"])
					{
						case "products": $temp = CATALOG_LINK."/";break;
						case "articles": $temp = ARTICLES_LINK."/";break;
						default: $temp = "";
					}					
					$filename = "";
					$data["row"][] = array (
						"title" => $row["title"],
						"link" => $this->siteUrl.$temp."tags/".$row['url'],
						"text" => $this->Search($this->dec($row ['title']).".".$this->dec($row ['short_text']).".".$this->dec($row ['text']), $search),
						"category" => "Теги",
						"filename" => $filename,
					);
				}
				$this->db->FreeResult ($result);
			}*/
			
			// по продукции
			$fromwhere=" FROM shop WHERE (short_text LIKE '%$search%' OR text LIKE '%$search%' OR title LIKE '%$search%') AND is_active='1' ORDER BY news_date desc";
			$total = $this->db->GetOne("SELECT Count(*)".$fromwhere, 0);
			$fulltotal +=$total;
			if ($total > 0) 
			{
				//$result = $this->db->ExecuteSql("SELECT shop_id, parent_id, name, short_text, price, url, filename, recomend, promotion, best_price, novelty".$fromwhere);						
				$result = $this->db->ExecuteSql("SELECT shop_id, parent_id, name, short_text, price, url, filename".$fromwhere, $this->Pages_GetLimits());			
				while ($row = $this->db->FetchArray ($result))	
				{					
					$id = $row['shop_id'];
					$short_text = $this->dec($row['short_text']);		
					$fullurl = GetLinkCat($this->menuarr, $row["parent_id"]);
					$link =$this->siteUrl.SHOP_LINK."/".$fullurl.$row['url'];
					$title = $this->dec($row['name']);	
					if ($row['filename'] != "") 
					{
						$extension = substr($row['filename'], -3);
						$filename = substr($row['filename'], 0, -4)."_small.".$extension;
						$filename = $this->siteUrl."/media/shop/".$filename;
					}
					else
					{
						$filename = $this->siteUrl."img/noimg.jpg";
					}
					$data["row"][] = array (
						"id" => $id,
						"title" => $title,
						"short_text" => $short_text,
						"filename" => $filename,										
						"link" => $link,
						"price" => $row["price"],
						/*"recomend" => $row["recomend"],
						"promotion" => $row["promotion"],
						"best_price" => $row["best_price"],
						"novelty" => $row["novelty"],*/
					);					
				}
				$this->db->FreeResult ($result);
				$mainlink = $this->siteUrl.SEARCH_LINK."/?search=".$search."&";
				$data['pages'] = $this->Pages_GetLinks_Site($total, $mainlink);
			}
			
			if ($fulltotal == 0) 
			{
				$data["empty"] = "По вашему запросу '$search' ни чего не найдено";
			}
		}
		else
		{
			$data["empty"] = "По вашему запросу '$search' ни чего не найдено";
		}
		$data["total"] = $fulltotal;
		return $data;
	}

}
