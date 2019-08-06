<?php

class Model_Shop extends Model 
{

	private $table_name = '`shops`';
	var $rowsPerPage = 12; //выводить на страницу по умолчанию
    var $rowsOptions = array (12, 24, 36); //количество записей на страницу
	var $path_img = "media/shop/";
	var $array_filter = array ();
	var $fromwhere = "";
	var $fromwhere_count = "";
	
	function __construct()
	{
		parent::__construct();
		$this->menuarr = $this->get_array_catalog(false, "shops", "shop_id");
		$this->menuarrtree = GetTreeFromArray($this->menuarr);
	}
	
	public function get_data() 
	{	
		$mainroutes = explode('/', $_SERVER['REQUEST_URI']);		
		//print_arr($mainroutes);
		$routes = explode('/', $_SERVER['REQUEST_URI']);
		$routes = explode('?', $routes[count($routes)-2]);
		// флаг для подкаталога (выводить продукцию всю или для конкретного каталога)
		$flag_tamplate = $flag = false;	
		$titlecatalog = "";
		if (($routes[0] == SHOP_LINK) && (count($mainroutes) <= 3))
		{
			$sql="SELECT name, news_date, title, head_title, text, description, keywords FROM menus WHERE url = '".SHOP_LINK."/' and is_active='1'";	
		}
		elseif ($mainroutes[2] == "category")
		{			
			$sql="SELECT name, category_id, news_date, title, head_title, text, description, keywords FROM category WHERE url = '".$routes[0]."/'";		
			$flag_tamplate = true;	
		}
		elseif ($mainroutes[2] == "tags")
		{			
			$sql="SELECT name, tag_id, news_date, title, head_title, text, description, keywords FROM tags WHERE url = '".$routes[0]."/' and module = 'shop' and is_active='1'";		
			$flag_tamplate = true;	
		}
		else
		{
			$sql="SELECT name, shop_id, news_date, title, head_title, text, description, keywords FROM ".$this->table_name." WHERE url = '".$routes[0]."/' and is_active='1'";	
			$flag = true;			
		}
// menu tree получить
		$row = $this->db->GetEntry($sql);
		if (!$row)
		{
			$this->error404();
		}
		$data = array(	
			"title" => (!empty($row["title"]))?$this->dec($row["title"]):$this->dec($row["name"]),
			"descr" => $this->dec($row["text"]),
			"head_title" => (!empty($row["head_title"]))?$this->dec($row["head_title"]):$this->dec($row["name"]),
			"description" => $this->dec($row["description"]),
			"keywords" => $this->dec($row["keywords"]),
			"catalog_ul" => GetUlMenu($this->siteUrl.SHOP_LINK."/", $this->menuarrtree, (isset($row["shop_id"]))?$row["shop_id"]:0, 3),
		);
		
		if ($flag)
		{
			$parent = "and parent_id = '".$row["shop_id"]."'";
			$data["nav"] = MAIN_NAV.GetNavUl($this->menu, $this->cid, false).GetNavCatUl($this->menuarr, $row["shop_id"], true, SHOP_LINK);
			// проверяем на правильность передачи ссылки
			$fullurl = explode('?', $_SERVER['REQUEST_URI']);
			$mainlink = "/".SHOP_LINK."/".GetLinkCat($this->menuarr, $row["shop_id"]);
			if ($mainlink != $fullurl[0])
			{
				$this->Redirect($mainlink);
			}
			// проверяем количество потомков у каталога и если они есть будем обрабатывать шаблоном каталога $flag=false
			$sql = "SELECT Count(*) FROM ".$this->table_name." WHERE parent_id = '".$row["shop_id"]."'";
			$total = $this->db->GetOne($sql);
			if ($total == 0)
			{
				$flag_tamplate=true;
			}
		}
		elseif ($mainroutes[2] == "category")
		{			
			$parent = "and category = '".$row["category_id"]."'";
			$fullurl = explode('?', $_SERVER['REQUEST_URI']);
			$mainlink = "/".SHOP_LINK."/category/".$routes[0]."/";
			if ($mainlink != $fullurl[0])
			{
				$this->Redirect($mainlink);
			}
			
			$data["nav"] = MAIN_NAV.GetNavUl($this->menu, $this->cid, false)."<li>Категории</li><li>".$this->dec($row["name"])."</li>";
			
			
		}
		else
		{
			$mainlink = "/".SHOP_LINK."/";
			$parent = "and parent_id = '0'";
			$data["nav"] = MAIN_NAV.GetNavUl($this->menu, $this->cid);
		}

		// для тегов запрос не мнго другой
		if ($mainroutes[2] == "tags")
		{			
			$fullurl = explode('?', $_SERVER['REQUEST_URI']);
			$mainlink = "/".SHOP_LINK."/tags/".$routes[0]."/";
			if ($mainlink != $fullurl[0])
			{
				$this->Redirect($mainlink);
			}
			$data["nav"] = MAIN_NAV.GetNav($this->menu, $this->cid, false)."Теги / ".$this->dec($row["name"]);
			$fromwhere = " FROM `shop`, `tags`, `tags_value` WHERE shop.is_active='1' and tags_value.tag_id = '".$row["tag_id"]."' and tags.module = 'shop' and item_id = shop.shop_id GROUP BY shop.shop_id Order By shop.news_date desc";
			$fromwhere_count = " FROM `shop`, `tags`, `tags_value` WHERE shop.is_active='1' and tags_value.tag_id = '".$row["tag_id"]."' and tags.module = 'shop' and item_id = shop.shop_id GROUP BY tags.tag_id Order By shop.news_date desc";
			/*$fromwhere = " FROM `shop` INNER JOIN tags on tags.module = 'shop' 
			INNER JOIN tags_value on tags_value.tag_id = '".$row["tag_id"]."' and tags_value.item_id = shop.shop_id WHERE shop.is_active='1'  GROUP BY shop.shop_id Order By shop.news_date desc";*/
		}
		else
		{
			$fromwhere_count = $fromwhere = " FROM `shop` WHERE is_active='1' $parent Order By news_date desc";
		}
		
		if ($flag_tamplate)
		{
			$this->Get_Filter($row["shop_id"]);
			$sql = "Select Count(*)".$this->fromwhere_count;	
			$total = $this->db->GetOne ($sql, 0);
			if ($total > 0)	
			{			
				$this->Get_Valid_Page($total);
				$sql = "Select shop.name, shop.shop_id, shop.url, shop.recomend, shop.title, shop.short_text, shop.parent_id, shop.filename, shop.category, shop.price".$this->fromwhere;			
				//echo $sql;
				$result=$this->db->ExecuteSql($sql, $this->Pages_GetLimits());
				while ($row = $this->db->FetchArray ($result))
				{
					$id = $row['shop_id'];
					$p_url = $row['url'];
					$title = $this->dec($row['name']);				
					$short_text = $this->dec($row['short_text']);							
					$fullurl1 = GetLinkCat($this->menuarr, $row["parent_id"]);
					$link = $this->siteUrl.SHOP_LINK."/".$fullurl1.$p_url;				
					if ($row['filename'] != "") {
						$extension = substr($row['filename'], -3);
						$filename = substr($row['filename'], 0, -4)."_small.".$extension;
						$filename = $this->siteUrl."/media/shop/".$filename;							
					}
					else {
						$filename = $this->siteUrl."img/noimg.jpg";
					}   
					
					
					$data ['product_row'][] = array (
						"id" => $id,
						"title" => $title,
						"short_text" => $short_text,
						"filename" => $filename,										
						"link" => $link,
						"price" => $row["price"],
					);
				}
				$this->db->FreeResult ($result);
				$data['pages'] = $this->Pages_GetLinks_Site($total, $mainlink.$this->Get_All_Prameters());
				$data['filter'] = $this->array_filter;
			}	
			else		
			{
				$data['empty'] = "Нет записей в базе данных";
			}
		}
		else
		{
		$fromwhere = " FROM ".$this->table_name."WHERE is_active='1' $parent ORDER BY order_index asc";
		$sql = "Select Count(*)".$fromwhere;	
		//echo $sql;
		$total = $this->db->GetOne ($sql, 0);		
		if ($total > 0)	
		{			
			$this->Get_Valid_Page($total);
			$sql = "SELECT *".$fromwhere;			
			$result=$this->db->ExecuteSql($sql, $this->Pages_GetLimits());
			while ($row = $this->db->FetchArray ($result))
			{
				$id = $row['shop_id'];
				$p_url = $row['url'];
				$title = $this->dec($row['name']);				
				$short_text = $this->dec($row['short_text']);							
				$fullurl1 = GetLinkCat($this->menuarr, $row["parent_id"]);
				$link = $this->siteUrl.SHOP_LINK."/".$fullurl1.$p_url;				
				if ($row['filename'] != "") {
					$extension = substr($row['filename'], -3);
					$filename = substr($row['filename'], 0, -4)."_small.".$extension;
					$filename = $this->siteUrl."/media/shops/".$filename;								
				}
				else {
					$filename = $this->siteUrl."img/noimg.jpg";
				}    
				
				$data ['table_row'][] = array (
					"id" => $id,
					"title" => $title,
					"short_text" => $short_text,
					"filename" => $filename,										
					"link" => $link,
				);
			}
			$this->db->FreeResult ($result);
			$data['pages'] = $this->Pages_GetLinks_Site($total, $mainlink."?");
		}	
		else		
		{
			$data['empty'] = "Нет записей в базе данных";
		}
		}
		
		
		//$data['tags_ul'] = $this->Ul_tags($routes[0]);
		$data['tags_ul'] = array();
		return $data;
		
	}
	
	function Get_Filter($catalog_id)
	{
		/*$fromwhere = "FROM fields,  field_category WHERE field_category.category_id = '$catalog_id' and fields.field_id = field_category.field_id and fields.is_filter = '1' ORDER BY order_index";	
		$sql="SELECT fields.field_id, name ".$fromwhere;*/
		
		$array_key_filter = array();
		$sql = "SELECT category.category_id, category.name, Count(shop.name) as count FROM `category`
		INNER JOIN `shop` on category.category_id = shop.category and shop.parent_id = '$catalog_id' GROUP BY category.category_id ORDER BY name asc";
		$category_sql = "";
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{	
			$category_sql = " and category.category_id IN (";
			while ($row = $this->db->FetchArray ($result))
			{
				$this->array_filter["Бренд"]['url'] = "category";
				$this->array_filter["Бренд"]['item'][$row['name']] = array (
					"count" => $row['count'],
					"unit" => "",
					"id" => $row['category_id'],
					"active" => false,
				);
				$category_sql .= $row['category_id'].",";
			}
			$this->db->FreeResult ($result);
			$category_sql = rtrim($category_sql, ",");
			$category_sql .= ")";
			$array_key_filter["category"] = "Бренд";
		}	
		// собираем по каждому дополнительному полю значения, а тк же подсчитываем их количество
		/*$sql = "SELECT fields.field_id, unit, fields.name, fields.url, value, Count(value) as count FROM `fields` 
		INNER JOIN `field_category` on field_category.category_id = '$catalog_id' and fields.field_id = field_category.field_id and fields.field_id IN (1, 18) and fields.is_filter = '1'
		INNER JOIN `fields_value` on fields_value.field_id = fields.field_id
		INNER JOIN `shop` on fields_value.parent_id = shop.shop_id and shop.parent_id = '$catalog_id'
		 GROUP BY value Order By count desc";*/
		 
		 // собираем по каждому дополнительному полю значения, а тк же подсчитываем их количество
		 $sql = "SELECT fields.field_id, unit, fields.name, fields.url, value, Count(value) as count FROM `fields` 
		INNER JOIN `field_category` on field_category.category_id = '$catalog_id' and fields.field_id = field_category.field_id and fields.is_filter = '1'
		INNER JOIN `fields_value` on fields_value.field_id = fields.field_id
		INNER JOIN `shop` on fields_value.parent_id = shop.shop_id and shop.parent_id = '$catalog_id'
		 GROUP BY fields.field_id, value Order By fields.order_index asc, value=0, -value DESC, value";
	
		$result=$this->db->ExecuteSql($sql);
		if ($result)
		{			
			while ($row = $this->db->FetchArray ($result))
			{
				$this->array_filter[$row['name']]['url'] = $row['url'];
				$this->array_filter[$row['name']]['item'][$row['value']] = array (
					"count" => $row['count'],
					"unit" => $row['unit'],
					"active" => false,
				);			
				
				/*Array
				(
					[Бренд] => Array
						(
							[url] => category
							[item] => Array
								(
									[IQ] => Array
										(
											[count] => 4
											[unit] => 
											[id] => 1
											[active] => 
										)

								)

						)

					[Формат] => Array
						(
							[url] => format
							[item] => Array
								(
									[A3] => Array
										(
											[count] => 2
											[unit] => 
											[active] => 
										)

									[A4] => Array
										(
											[count] => 2
											[unit] => 
											[active] => 
										)

								)

						)

				)*/
				
				$array_key_filter[$row['url']] = $row['name'];
				
				/*Array
				(
					[format] => Формат
					[color] => Оттенок
				)*/				 

			}
			$this->db->FreeResult ($result);
			// массив под запросов параметров
			$array_sql = array ();
			foreach ($array_key_filter as $key => $value)
			{				
				if (isset($_GET[$key]))
				{
					$temparray = explode (";", $_GET[$key]);
					
					if ($key == "category")
					{
						$array_sql[$key] = " and shop.category IN (";
						foreach ($temparray as $tempkey)
						{
							foreach ($this->array_filter[$value]['item'] as $key_1 => $value_1)
							{
								if ($value_1["id"] == $tempkey)
									if (isset($this->array_filter[$value]['item'][$key_1]['active']))
										$this->array_filter[$value]['item'][$key_1]['active'] = true;
							}							
							$array_sql[$key] .= $this->db->RealEscapeString($tempkey).",";
						}
						$array_sql[$key] = rtrim($array_sql[$key], ",");
						$array_sql[$key] .= ")";
						$array_sql_main[$key] = $array_sql[$key];
					}
					else
					{					
						$array_sql[$key] = " and (SELECT Count(*) FROM `fields_value`, `fields` WHERE (";						
						$i = 0;
						$or = "";
						foreach ($temparray as $tempkey)
						{
							if (isset($this->array_filter[$value]['item'][$tempkey]['active']))
							{
								$this->array_filter[$value]['item'][$tempkey]['active'] = true;
								if ($i != 0)
								{
									$or = " or ";
								}
								$array_sql[$key] .= $or."value='".$this->db->RealEscapeString($tempkey)."'";
							}
							$i++;
						}
						$array_sql[$key] .= ") and parent_id = shop.shop_id and fields.field_id = fields_value.field_id and fields.url='".$this->array_filter[$value]['url']."') > 0";
					}					
				}
			}
			//print_arr ($array_sql);			
			
			foreach ($array_key_filter as $key => $value)
			{
				$tempsql = "";
				foreach ($array_sql as $key_sql => $value_sql)	
				{
					if ($key != $key_sql)
					{
						$tempsql .= $value_sql;
					}
				}
				// подсчет количества продукции по параметрам 
				if ($key == "category")
				{
					if (isset($this->array_filter["Бренд"]['item']))
					{
						// делаем пересчет продукции по заданным параметрам к категории продукции
						foreach ($this->array_filter["Бренд"]['item'] as $key_category => $value_category)
						{						
							if (isset($this->array_filter["Бренд"]['item'][$key_category]['count']))
							{
								$sql = "SELECT Count(*) FROM `fields_value`, `shop`, `fields` WHERE fields.field_id = fields_value.field_id and fields.is_filter = '1' and fields_value.parent_id = shop.shop_id and shop.parent_id = '$catalog_id' and shop.category = '".$value_category["id"]."' ".$tempsql." Group by fields.field_id";
								$this->array_filter["Бренд"]['item'][$key_category]['count'] = $this->db->GetOne($sql, 0);
							}
						}			
					}
				}
				else
				{
					$sql = "SELECT fields.field_id, unit, fields.name, fields.url, value, Count(value) as count FROM `fields` 
					LEFT JOIN `fields_value` on fields.field_id = fields_value.field_id and fields.is_filter = '1'
					INNER JOIN `shop` on fields_value.parent_id = shop.shop_id and shop.parent_id = '$catalog_id'".$tempsql." Group by fields.field_id, value";	
					$result=$this->db->ExecuteSql($sql);
					if (($result) and ($this->db->Num_Rows($result) > 0))
					{
						$array_field_count_new = array();
						// делаем пересчет продукции по заданным параметрам
						while ($row = $this->db->FetchArray ($result))
						{
							if ($key == $row['url'])		
							{
								$key_name = $row['name'];
								$array_field_count_new[$row['name']][$row['value']] = $row['count'];
							}
						}
						$this->db->FreeResult ($result);
						//print_arr ($array_field_count_new);	
						// вносим изменения в количество продукции для массива фильтра
						foreach ($this->array_filter[$key_name]['item'] as $key_count => $value_count)
						{						
							if (isset($this->array_filter[$key_name]['item'][$key_count]['count']))
							{
								if (isset($array_field_count_new[$key_name][$key_count]))					
								{									
									$this->array_filter[$key_name]['item'][$key_count]['count'] = $array_field_count_new[$key_name][$key_count];							
								}
								else
									if ($key_count != "url")
										$this->array_filter[$key_name]['item'][$key_count]['count'] = 0;
							}
						}
					}
					else
					{
						// обнулить массив
					}
					
				}	
				
			}			
			// вся продукция удовлетворяющая условиям выбора
			$this->fromwhere = " FROM `fields_value`, `shop`, `fields` WHERE fields.field_id = fields_value.field_id and fields.is_filter = '1' and fields_value.parent_id = shop.shop_id and shop.parent_id = '$catalog_id' ".implode (" ", $array_sql)." Group by shop.shop_id ORDER BY shop.".$this->orderBy." ".$this->orderDir;			
			
			$this->fromwhere_count = " FROM `fields_value`, `shop`, `fields` WHERE fields.field_id = fields_value.field_id and fields.is_filter = '1' and fields_value.parent_id = shop.shop_id and shop.parent_id = '$catalog_id' ".implode (" ", $array_sql)." Group by fields.field_id";
		}		
	}
	
	public function get_view() 
	{
		/*if (!$this->Valid_Url(CATALOG_LINK)) 		
		{
			$this->error404();
		}*/		
		$routes = parse_url($_SERVER['REQUEST_URI']);
		$temp = explode('/', $routes['path']);
		$url = $temp[count($temp)-1];	
		$sql = "SELECT * FROM `shop` WHERE is_active='1' and url = '".$url."'";
		$row = $this->db->GetEntry($sql, "/404");
		
		// проверяем на правильность передачи ссылки
		$fullurl = explode('?', $_SERVER['REQUEST_URI']);
		$link = "/".SHOP_LINK."/".GetLinkCat($this->menuarr, $row["parent_id"]).$row["url"];
		if ($link != $fullurl[0])
		{
			$this->Redirect($link);
		}
		$id = $row["shop_id"];		
		$nav = MAIN_NAV.GetNavUl($this->menu, $this->cid, false).GetNavCatUl($this->menuarr, $row["parent_id"], false, SHOP_LINK);//."<li>".dec($row["name"])."</li>";
		
		if ($row['filename'] != "") {				
			$filename = $row['filename'];
			$filename = $this->siteUrl.$this->path_img.$filename;
		}
		else	{
			$filename = $this->siteUrl."img/noimg.jpg";
		}
// получить menu tree		
		$data = array (
			"id" => $id,
			"price" => $row["price"],
			"nav" =>$nav, 
			"title" => $this->dec($row["title"]),
			"text" => $this->dec($row["text"]),
			"short_text" => $this->dec($row["short_text"]),
			"head_title" =>dec($row["head_title"]),
			"description" =>dec($row["description"]),
			"keywords" =>dec($row["keywords"]),
			"filename" => $filename,
			"catalog_ul" => GetUlMenu($this->siteUrl.SHOP_LINK."/", $this->menuarrtree, $row["parent_id"], 3),
		);
		
		if ($row['filename'] != "") {
			for ($j=0;$j<5;$j++)
			{
				if ($j == 0)
				{
					if (isset($row['filename']) and ($row['filename'] != "")) {
						$extension = substr($row['filename'], -3);
						$filename = substr($row['filename'], 0, -4)."_small.".$extension;
						$filename = $this->siteUrl.$this->path_img.$filename;	
						$data ['file'][] = array (
							'big' => $this->siteUrl.$this->path_img.$row['filename'],
							'small' => $filename,
						);
					}
				}
				else
				{
					if (isset($row['filename']) and ($row['filename'.$j] != "")) {
						$extension = substr($row['filename'.$j], -3);
						$filename = substr($row['filename'.$j], 0, -4)."_small.".$extension;
						$filename = $this->siteUrl.$this->path_img.$filename;	
						$data ['file'][] = array (
							'big' => $this->siteUrl.$this->path_img.$row['filename'.$j],
							'small' => $filename,
						);
					}
				}
			}					
		}
		
		/*----------------Дополнительные поля-------------*/		
		$sql="SELECT name, value, unit FROM fields_value, fields WHERE fields_value.field_id = fields.field_id AND parent_id = '$id' ORDER BY order_index";
		$result = $this->db->ExecuteSql ($sql);
		if ($result) {						
			while ($row = $this->db->FetchArray ($result)) 
			{								
				$data['fields'][] = array (
					"name" => $this->dec($row['name']),
					"value" => $row['value']." ".$row['unit'],
				);
			}
			$this->db->FreeResult ($result);			
		}	
		
		return $data;	
	}
	
	function Ul_tags ($url_tags = "")
	{
		$result = $this->db->ExecuteSql ("Select title, url From `tags` Where is_active='1' and module='shop' ORDER BY order_index asc");
		 
		if ($result)
		{			
			while ($row = $this->db->FetchArray($result)) 
			{   
				$name = dec($row['title']);
				$url = dec($row['url']);
				$link = $this->siteUrl.SHOP_LINK."/tags/".$url;
				if ($url_tags."/" == $url){
					$active = "class='active'";
				}
				else
				{
					$active="";
				}
				$data[] = array (
						"title" => $name,
						"link" => $link,
						"active" => $active,
				 );              
			}
			$this->db->FreeResult($result);
		}
		return $data;		
	}

}
