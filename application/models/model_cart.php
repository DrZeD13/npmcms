<?php
/*

*/
class Model_Cart extends Model 
{

	private $table_name = '`order`';
	
	public function get_data() 
	{
		
		$data['head_title'] = $data['title'] = "Корзина";
		$data['keywords'] = "";
		$data['description'] = "";
		$data["nav"] = MAIN_NAV."<li>Корзина</li>";	
		if (isset($_SESSION['cart_order']))
		{
			//print_r($_SESSION['cart_order']);
			foreach($_SESSION['cart_order'] as $k => $v){
				$data["cart"][] = array (
					"id" => $v["id"],
					"count" => $v["count"],
					"price" => $v["price"],
					"img" => $v["img"],
					"options_name" => $v["options_name"],
					"url" => $v["url"],
					"title" => $this->dec($v["title"]),
					"coast" => $v["count"]*$v["price"],
					"key" => $k,
					"options" => "",
				);
			}
			$total_count = 0;
			$coast = 0;
			foreach($_SESSION['cart_order'] as $k => $v){
				$total_count += $v["count"]; 
				$coast += $v["price"]*$v["count"];
			}
			
			
			$data['total_count'] = $total_count;
			$data['total_coast'] = $coast;
			
			if ($this->is_user)
			{
				$row = $this->db->GetEntry("SELECT * FROM users WHERE user_id = '".$this->GetCookie("id", 0)."'");
				if ($row)
				{
					$data['user'] = array (
						"login" => $row["login"],
						"tel" => $row["tel"],
						"fcity" => $this->dec($row["fcity"]),
						"fstreet" => $this->dec($row["fstreet"]),
						"fdom" => $this->dec($row["fdom"]),
						"foffice" => $this->dec($row["foffice"]),
					);
				}
			}			
		}
		
		return $data;
	}
	
	public function get_view() 
	{		
		$data["nav"] = MAIN_NAV.GetNav($this->menu, $this->cid);		
		
		return $data;		
	}
	
	public function cart_actions()
	{
		if (isset($_POST["addCart"]))
		{			
			switch ($_POST["addCart"])
			{
				case "cart/add":
					$data = $this->add_cart();
				break;
				case "cart/change":					
					$data = $this->change_cart();
				break;
				case "cart/remove":
					$data = $this->remove_cart();				
				break;
				case "cart/clean":
					$data = $this->clean_cart();
				break;
			}
		}
		else
		{
			$data = array (
				"data" => array (),
				"message" => "что-то пошло не так",
				"success" => false,
			);
			$this->error404();
		}		
		
		echo json_encode($data);
		//return $data;
	}
	
	function add_cart()
	{
		if (isset($_POST["count"]))
			{
				if (is_numeric($_POST["count"]))
				{
					$key = $_POST["id"];
					$result_options = $this->db->ExecuteSql ("Select additions.addition_id, additions.name, additions.price From `additions`, `additions_value` Where additions_value.item_id='".$_POST["id"]."' and additions.addition_id = additions_value.addition_id GROUP BY additions.addition_id");
					// сразу посчитаем стоимость дополнительных опций, а так же сформируем их названия
					$options_price = 0;
					$options_name = "";
					if ($result_options)
					{
						while ($row = $this->db->FetchArray ($result_options))				
						{
							if (isset($_POST["options"][$row["addition_id"]]))
							{
								$key .= $_POST["options"][$row["addition_id"]];
								$options_price += $row["price"];
								$options_name .= "<br>".$row["name"];
							}
						}
					}
					$key = md5($key);
										
					if (isset($_SESSION['cart_order'][$key]))
					{
						$max_count = $this->db->GetOne("SELECT count FROM `shop` WHERE is_active='1' and shop_id = '".$_SESSION['cart_order'][$key]["id"]."'", 0);
						// проверяем максимальное количество товаров добавляемых в корзину
						if ((($_SESSION['cart_order'][$key]["count"] + $_POST["count"]) > 99) || ((($_SESSION['cart_order'][$key]["count"] + $_POST["count"]) >$max_count)))
						{
							$data = array (
								"data" => array (),
								"message" => (min(99, $max_count))?"Максимальное количество одного товара ".min(99, $max_count)." штук":"Данного товара нет в наличии",
								"success" => false,
							);
							return $data;
						}
						
						$count_item = $_SESSION['cart_order'][$key]["count"] = $_SESSION['cart_order'][$key]["count"] + $_POST["count"];						
					}
					else
					{						
						$row = $this->db->GetEntry("SELECT * FROM `shop` WHERE is_active='1' and shop_id = '".$_POST["id"]."'");
						// проверяем максимальное количество товаров добавляемых в корзину
						if (($_POST["count"] > 99) || (($_POST["count"] > $row["count"])))
						{
							$data = array (
								"data" => array (),
								"message" => (min(99, $row["count"]))?"Максимальное количество одного товара ".min(99, $row["count"])." штук":"Данного товара нет в наличии",
								"success" => false,
							);
							return $data;
						}
						
						//$sql = "SELECT title, url, filename, price FROM shop WHERE product_id='".$_POST["id"]."'";
						//$row = $this->db->GetEntry($sql);
						// дополнительная проверка на существование в таблице подукции с заданным id
						$_SESSION['cart_order'][$key]["id"]=$_POST["id"];
						$count_item = $_SESSION['cart_order'][$key]["count"]=$_POST["count"];
						
						if ($row)
						{
							if ($row["price"] > 0)
							{
						
							$_SESSION['cart_order'][$key]["price"] = $row["price"]+$options_price;
							$_SESSION['cart_order'][$key]["coast"] = $_SESSION['cart_order'][$key]["price"]*$count_item;
							$_SESSION['cart_order'][$key]["title"] = $row["name"];
							$fullurl = GetLinkCat($this->menuarr, $row["parent_id"]);
							$_SESSION['cart_order'][$key]["url"] = $this->siteUrl.SHOP_LINK."/".$fullurl.$row["url"];
							
							if ($row['filename'] != "") {				
								$extension = substr($row['filename'], -3);
								$filename = substr($row['filename'], 0, -4)."_small.".$extension;
								$filename = $this->siteUrl."media/shop/".$filename;		
								$_SESSION['cart_order'][$key]["img"] = $filename;
							}
							else	{
								$_SESSION['cart_order'][$key]["img"] = $this->siteUrl."img/noimg.jpg";
							}	
							$_SESSION['cart_order'][$key]["options_name"] = $options_name;								
							
							//$_SESSION['cart_order'][$key]["options"]=$_POST["options"];	
							}
							else
							{
								unset($_SESSION['cart_order'][$key]);							
								$data = array (
									"data" => array (),
									"message" => "Данного товара нет в наличии",
									"success" => false,
								);
								return $data;
							}
						}
						else
						{
							unset($_SESSION['cart_order'][$key]);							
							$data = array (
								"data" => array (),
								"message" => "Данного товара не существует",
								"success" => false,
							);
							return $data;
						}
					}
					
					$total_count = 0;
					$coast = 0;
					foreach($_SESSION['cart_order'] as $k => $v){
						$total_count += $v["count"]; 
						$coast += $v["price"]*$v["count"];
					}
					
					$data = array (
						"data" => array ("key"=>$key, "total_cost" => $coast, "total_count" => $total_count, "total_weight" => "0"),
						"message" => "Товар успешно добавлен в корзину: ".$count_item,
						"success" => true,
					);
					return $data;
				}
				else
				{
					$data = array (
						"data" => array (),
						"message" => "Введите число в поле колличество",
						"success" => false,
					);
					
				}
			}
		else
		{
			$data = array (
				"data" => array (),
				"message" => "Введите в поле колличество число",
				"success" => false,
			);
		}
		return $data;
	}
	
	function change_cart()
	{
		if (isset($_POST["key"]) && isset($_POST["count"]))
		{
			if (is_numeric($_POST["count"]))
			{
				
				if (isset($_SESSION['cart_order'][$_POST["key"]]))
				{
					$max_count = $this->db->GetOne("SELECT count FROM `shop` WHERE is_active='1' and shop_id = '".$_SESSION['cart_order'][$_POST["key"]]["id"]."'", 0);
					if (($_POST["count"] <= $max_count) && (($_POST["count"] <= 99)))
					{
						$count_item = $_SESSION['cart_order'][$_POST["key"]]["count"] = $_POST["count"];
						
						
						$total_count = 0;
						$coast = 0;
						foreach($_SESSION['cart_order'] as $k => $v){
							$total_count += $v["count"]; 
							$coast += $v["price"]*$v["count"];
						}
						
						$data = array (
							"data" => array ("key"=>$_POST["key"], "total_cost" => $coast, "total_count" => $total_count, "total_weight" => "0"),
							"message" => "Количество товара в корзине ".$_POST["count"]." изменено",
							"success" => true,
						);
					}
					else
					{
						$data = array (
							"data" => array (),
							"message" => "Максимальное количество одного товара ".min(99, $max_count)." штук",
							"success" => false,
						);	
					}
				}
				else
				{
					$data = array (
						"data" => array (),
						"message" => "Данного товара нет в корзине",
						"success" => false,
					);					
				}		
			}
			else
			{
				$data = array (
					"data" => array (),
					"message" => "Введите число в поле колличество",
					"success" => false,
				);
				
			}
		}
		else
		{
			$data = array (
				"data" => array (),
				"message" => "Введите в поле колличество число",
				"success" => false,
			);
		}
		return $data;
	}
	
	function remove_cart()
	{
		if (isset($_POST["key"]))
		{
			if (isset($_SESSION['cart_order'][$_POST["key"]]))
			{
				unset($_SESSION['cart_order'][$_POST["key"]]);
			}
		}					
					
		$total_count = 0;
		$coast = 0;
		if (isset($_SESSION['cart_order']))
		{
			foreach($_SESSION['cart_order'] as $k => $v){
				$total_count += $v["count"]; 
				$coast += $v["price"]*$v["count"];
			}
		}
		
		$data = array (
			"data" => array ("key"=>$_POST["key"], "total_cost" => $coast, "total_count" => $total_count, "total_weight" => "0"),
			"message" => "Товар удален из корзины",
			"success" => true,
		);
		
		return $data;
	}
	
	function clean_cart()
	{
		unset($_SESSION['cart_order']);
		$data = array (
			"data" => array ("key"=>"", "total_cost" =>0, "total_count" => 0, "total_weight" => 0),
			"message" => "Корзина очищена",
			"success" => true,
		);
		
		return $data;
	}
	
	function send()
	{
		$fio = $this->GetValidGP ("fio", "Ваше имя", VALIDATE_NOT_EMPTY);
        $subject = $this->GetValidGP ("subject", "Тема сообщения", VALIDATE_NOT_EMPTY);
        $email = $this->enc ($this->GetValidGP ("email", "Email адрес", VALIDATE_EMAIL));
        $mes_content = $this->GetValidGP ("mes_content", "Текст вашего сообщения", VALIDATE_NOT_EMPTY);
        /*@@@-- Begin: kcaptcha --@@@@@@@@@@@*/
        $code = $this->GetGP("keystring", "хрен");
		$flag = $this->ChecCode($code);		
		if (!$flag) {$this->SetError("captcha", "Неверная последовательность");}	
      	/*@@@-- END: kcaptcha --@@@@@@@@@@@@*/        
        
        if ($this->errors['err_count'] > 0) {
            return false;
        }
        else {
			$copy = $this->GetGP("copy", 0);
			$contactEmail = $this->db->GetSetting ("ContactEmail");
			$SiteName = $this->db->GetSetting ("SiteName");
			$message1 = "Добрый день,<br><br>$mes_content<br><br>С уважением, Администрация ".$SiteName;
			$message2 = "Добрый день,<br><br>$mes_content<br><br>С уважением, $fio.<br><br> Email: $email<br><br>Собщение отправлено с сайта {$this->siteUrl}";									
		   $this->SendMail ($contactEmail, $subject, $message2);

           if ($copy == 1) {               
               $this->SendMail ($email, $subject, $message1);
           }
		   $this->SetError("message", "Ваше письмо успешно отправлено");
           return true;
        }
	}
}