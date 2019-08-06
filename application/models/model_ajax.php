<?php
//include_once('../core/model_default.php');
//include "application/core/model_default.php";
class Model_Ajax extends Model
{	
	function __construct()	
	{		
		global $db;
        $this->db = $db;
		$this->is_user = $this->CheckLogin();			
	}
	
	function index() 
	{
		$parse = parse_url($_SERVER['REQUEST_URI']);
		$routes = explode('/', $parse['path']);
		if (isset($routes[2]))
		{
			switch ($routes[2])
			{
				case "rating":
					$this->get_rating();
				break;
				case "send_mail":
					$this->get_send_mail();
				break;
				case "send_order":
					$this->get_send_order();
				break;
				case "import":
					$this->import();
				break;
				case "importproduct":
					$this->import_product();
				break;
				case "delitemorder":
					$this->del_item_order();
				break;
				default: 
					$this->error404();
			}			
		}
		else
		{
			$this->error404();
		}
	}
	
	// удаление элемента из заказа
	function del_item_order ()
	{
		if ($this->Get_Access('orders'))
		{
			$order_product_id = $this->GetID("order_product_id", 0);		
			$id = $this->GetID("id", 0);		
			$this->history("Удаление элемента", "orders", "", $id);
			$this->db->ExecuteSql ("DELETE FROM order_product WHERE order_product_id=$order_product_id");
			$data = array (				
				"success" => true,
			);
			echo json_encode($data);
			exit;
		}
		else
		{
			$data = array (
				"message" => "У вас нет доступа к удалению",
				"success" => false,
			);
			echo json_encode($data);
			exit;
		}
	}
	
	// отправка заказа
	function get_send_order()
	{
		if (isset($_SESSION['cart_order']))
		{
					if (isset($_POST["phone"]))
					{
							if (isset($_POST["city"]))
							{		
								if (isset($_POST["street"]))
								{	
									$res = array (
										"news_date" => time(),
										"name" => $this->GetGP("login"),
										"phone" => $this->GetGP("phone"),
										"city" => $this->GetGP("city"),
										"street" => $this->GetGP("street"),
										"dom" => $this->GetGP("dom"),
										"office" => $this->GetGP("office"),
										"comment" => $this->GetGP("comment"),
									);
									
									/*if (!$this->is_user)
									{
										$user = $this->db->GetOne("SELECT user_id FROM users WHERE inn = '".$res["inn"]."' or email = '".$res["email"]."'");
										if ($user > 0)
										{
											$data = array (
												"message" => "Данная компания уже зарегистрирована, войдите в свой аккаунт перед отправкой заказа",
												"success" => false,
											);
											echo json_encode($data);
											exit;
										}
										// регистрация нового пользователя и сразу вход в его аккаунт
										$this->RegistationNewUser();
									}*/
									$message = "";
									$error = false;
									foreach($_SESSION['cart_order'] as $k => $v){
										$max_count = $this->db->GetOne("SELECT count FROM `shop` WHERE is_active='1' and shop_id = '".$v["id"]."'", 0);
										if ($v["count"] > $max_count)
										{
											$error = true;
											$message .= "Изменилось доступное количество товара ".$this->dec($v["title"])."<br>";
										}
									}
									if ($error)
									{
										$data = array (
											"message" => $message,
											"success" => false,
										);
										echo json_encode($data);
										exit;
									}
									
									
									$res["user_id"] = $this->GetCookie("id", NULL);
									$sql = "Insert Into orders ".ArrayInInsertSQL ($res);
									$this->db->ExecuteSql($sql);
									$order_id = $this->db->GetInsertID ();
									
											

									$message = '
									<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">
									<head>
									<title>Заказ № '.$order_id.'</title>
									</head>
									<body>
										<div style="background: #ccc; font-family: Arial; padding: 15px 0">
										<div style="background: #fff; padding: 15px; margin: 0 auto; width: 600px;">
										<table border="0" style="margin-bottom: 20px;width:100%" cellspacing="0" cellpadding="0">
										<tr>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											Фото
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											Нименование
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											Цена
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											Количество
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											Стоимость
										</td>
										</tr>';
									
									$total_count = $coast = 0;
									foreach($_SESSION['cart_order'] as $k => $v){
										$order_product = array (
											"shop_id" => $v["id"],
											"order_id" => $order_id,
											"quantity" => $v["count"],
											"price" => $v["price"],
											"name" => $this->dec($v["title"]),
										);
										$total_count += $v["count"]; 
										$coast += $v["price"]*$v["count"];
										
										$sql = "UPDATE shop SET count = count-".$v["count"]." WHERE shop_id = '".$v["id"]."'";		
										$this->db->ExecuteSql($sql);
										$sql = "Insert Into order_product ".ArrayInInsertSQL ($order_product);		
										$this->db->ExecuteSql($sql);	
										
										$message .='<tr>
													<td style="padding:5px 10px;border-bottom: 1px solid #ccc;">
														<img src="'.$v["img"].'" style="width: 100px;" alt="">
													</td>
													<td style="padding:5px 10px;border-bottom: 1px solid #ccc;">
													'.$order_product["name"].'
													</td>
													<td style="padding:5px 10px;border-bottom: 1px solid #ccc;">
														<span class="j-price-by-item">'.number_format($v["price"],2,',',' ').'</span> <span class="rur">&#8381;</span>
													</td>
													<td style="padding:5px 10px;border-bottom: 1px solid #ccc;">
														'.$v["count"].' шт.
													</td>		
													<td style="padding:5px 10px;border-bottom: 1px solid #ccc;">
														<span class="cost-right"><span class="j-cost">'.number_format($v["price"]*$v["count"],2,',',' ').'</span>&nbsp;<span class="rur">&#8381;</span>
													</td>
													</tr>';
										
									}
									$message .='<tr>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											Итого
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											<span class="ms2_total_count">'.number_format($total_count,0,' ',' ').'</span> шт.
										</td>
										<td style="font-size: 16px;background: #61766c;color: #fff;padding:5px 10px">
											<span class="ms2_total_cost">'.number_format($coast,0,' ',' ').'</span>&nbsp;<span class="rur">&#8381;</span>
										</td>
										</tr>
										</table>
										<b>Имя:</b> '.$this->GetGP("login").'<br>
										<b>Телефон:</b> '.$this->GetGP("phone").'<br>
										<b>Адрес доставки:</b><br>
										<b>Город:</b> '.$this->GetGP("city").'<br>
										<b>Улица:</b> '.$this->GetGP("street").'<br>
										<b>Дом:</b> '.$this->GetGP("dom").'<br>
										<b>Офис:</b> '.$this->GetGP("office").'<br>
										<b>Доп. инфо:</b> '.$this->GetGP("comment").'<br>
										</div>
										</div>
										</body>
										</html>';
									
									
									$this->send_order($order_id, $message);
									unset($_SESSION['cart_order']);
									
									
									
									$data = array (
										"success" => true,
									);
								}
								else
								{
									$data = array (
										"message" => "Введите улицу",
										"success" => false,
									);
								}
							}
							else
							{
								$data = array (
									"message" => "Введите город",
									"success" => false,
								);
							}
					}
					else
					{
						$data = array (
							"message" => "Введите корректно телефон",
							"success" => false,
						);
					}
			echo json_encode($data);
		}
		else
		{
			$this->error404();
		}
	}
	
	function send_order($order_id, $message)
	{		
		$file = $this->get_bill_pdg($order_id, true);
		if (!$file)
			$file = "";
		$email = $this->db->GetOne("select email from users where user_id='".$this->GetCookie("id", NULL)."'", 0);
		$subject = "Ваш заказ №".$order_id;
		$this->SendMailSMTP ($email, $subject, $message, $file);		
		$contactEmail = $this->db->GetSetting ("ContactEmail");
		$subject = "Поступил новый заказ №".$order_id;
		$this->SendMailSMTP ($contactEmail, $subject, $message);
		if (!empty($file))
			unlink ($file);
	}
	
	function RegistationNewUser ()
	{
	}
	
	function get_rating()
	{
		$id = $this->GetGP("productID", 0);
		$value = $this->GetGP("value", 0);
		if ($value > 0)
		{
			$row = $this->db->GetEntry("Select rating, respondents from `products` Where product_id ='$id'");
			/*$res = $this->db->GetOne("Select respondents from `products` Where product_id ='$id'", 0);
			$rating = $this->db->GetOne("Select rating from `products` Where product_id ='$id'", 0);*/
			if ($row)
			{
				$res = $row['respondents'];
				$rating = $row['rating'];
				$res1=($res==0)?1:$res+1;
				$result = (($value - $rating)/$res1) + $rating;

				$res++;
				$this->db->ExecuteSql ("Update `products` Set rating='$result', respondents='$res' Where product_id='$id'");				
			}
		}
	}
	
	function get_send_mail()
	{
		if (isset($_POST['tel']))
		{
			$contactEmail = $this->db->GetSetting ("ContactEmail");
			$contactEmail = "naumov.p.m@gmail.com";
			$message = "<div><b>Имя:</b> ".$this->GetGP("name")."</div>".
								"<div><b>Телефон:</b> ".$this->GetGP("tel")."</div>".
								"<div><b>Отправлен:</b> ".$_SERVER["HTTP_REFERER"]."</div>";
			$this->SendMail ($contactEmail, $this->GetGP("title", "Обратный звонок"), $message);
			$data = array (
				"ip" => $_SERVER['REMOTE_ADDR'], 
				"name" => $this->GetGP("name"),
				"email" => $this->GetGP("tel"),
				"subject" => $this->GetGP("title", "Обратный звонок"),
				"news_date" => time(),
				"message" => $message,
				"new" => 1,
				"is_active" => ($this->Get_Spam($_SERVER['REMOTE_ADDR']))?0:1,
				"module" => $this->GetGP("title", "Запись на диагонстику"),
			   );		   
			$sql = "Insert Into message ".ArrayInInsertSQL ($data);
			$this->db->ExecuteSql($sql);
			exit('200');
		}
		else
		{
			exit('300');
		}
	}
	
	function import ()
	{
		$curl = curl_init('http://api.samsonopt.ru/v1/category/?api_key=419988cf1fd2d07993d956bc43155b09&pagination_count=60&pagination_page='.$_GET['string']);
	$arHeaderList = array();
	$arHeaderList[] = 'Accept: application/json';
	$arHeaderList[] = 'User-Agent: 419988cf1fd2d07993d956bc43155b09';
	curl_setopt($curl, CURLOPT_HTTPHEADER, $arHeaderList);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	$result = curl_exec($curl);

	if (curl_errno($curl)) { 
		print "Error: " . curl_error($curl); 
	} else { 
		// Show me the result 
		//echo iconv("windows-1251","utf-8",$result);
		$arr = json_decode($result);
		$i = 0;
		$sql = "";
		foreach ($arr->data as $element)
		{
			$data = array
			(
				"name" => $element->name,
				"url" => TransUrl($element->name)."/",
				"parent_id" => $element->parent_id,
				"news_date" => time(),
				"order_index" => $_GET['string']*($i+1),
			);
			$sql = "SELECT Count(*) FROM shops WHERE shop_id ='".$element->id."'";
			if ($this->db->GetOne($sql) > 0)
			{
				$sql = "UPDATE shops SET ".ArrayInUpdateSQL ($data)." WHERE shop_id='".$element->id."'";
				//$this->db->ExecuteSql($sql);
				$arr->data[$i]->status = "Обновлено";
			}
			else
			{
				$data["shop_id"] = $element->id;
				$sql = "Insert Into shops ".ArrayInInsertSQL ($data);
				//$this->db->ExecuteSql($sql);
				$arr->data[$i]->status = "Добавлено";
			}
			$i++;			
		}
		if (!isset($arr->meta->pagination->next))
		{
			$arr->end = "Завершено";
		}
		else
		{
			$arr->end = "Следующая";
		}
		$result = json_encode($arr);
		//print_r ($arr);
		echo ($result);
		/*echo "<br><br>id - ".$arr->data[0]->id;
		echo "<br>name - ".$arr->data[0]->name;
		echo "<br>parent_id - ".$arr->data[0]->parent_id;
		echo "<br>depth_level - ".$arr->data[0]->depth_level;
		echo "<br> pagination - ".$arr->meta->pagination->next;
		echo "<br><br>".$result;*/
		curl_close($curl); 
	}
	
	}
	
	
	
	function import_product ()
	{
		$curl = curl_init('http://api.samsonopt.ru/v1/assortment/?api_key=419988cf1fd2d07993d956bc43155b09&pagination_count=600&pagination_page='.$_GET['string']);
	$arHeaderList = array();
	$arHeaderList[] = 'Accept: application/json';
	$arHeaderList[] = 'User-Agent: 419988cf1fd2d07993d956bc43155b09';
	curl_setopt($curl, CURLOPT_HTTPHEADER, $arHeaderList);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	$result = curl_exec($curl);

	if (curl_errno($curl)) { 
		print "Error: " . curl_error($curl); 
	} else { 
		// Show me the result 
		//echo iconv("windows-1251","utf-8",$result);
		$arr = json_decode($result);
		$i = 0;
		$sql = "";
		foreach ($arr->data as $element)
		{
			$data = array
			(
				"name" => $element->name,
				"url" => $element->sku.".html",
				"parent_id" => $element->category_list[0],
				"news_date" => time(),
				"filename" => isset($element->photo_list[0])?$element->photo_list[0]:"",
				"order_index" => $_GET['string']*($i+1),
			);
			$sql = "SELECT Count(*) FROM shop WHERE shop_id ='".$element->sku."'";
			if ($this->db->GetOne($sql) > 0)
			{
				$sql = "UPDATE shop SET ".ArrayInUpdateSQL ($data)." WHERE shop_id='".$element->sku."'";
				//$this->db->ExecuteSql($sql);
				$arr->data[$i]->status = "Обновлено";
			}
			else
			{
				$data["shop_id"] = $element->sku;
				$sql = "Insert Into shop ".ArrayInInsertSQL ($data);
				//$this->db->ExecuteSql($sql);
				$arr->data[$i]->status = "Добавлено";
			}
			$i++;			
		}
		if (!isset($arr->meta->pagination->next))
		{
			$arr->end = "Завершено";
		}
		else
		{
			$arr->end = "Следующая";
		}
		$result = json_encode($arr);
		//print_r ($arr);
		echo ($result);
		/*echo "<br><br>id - ".$arr->data[0]->id;
		echo "<br>name - ".$arr->data[0]->name;
		echo "<br>parent_id - ".$arr->data[0]->parent_id;
		echo "<br>depth_level - ".$arr->data[0]->depth_level;
		echo "<br> pagination - ".$arr->meta->pagination->next;
		echo "<br><br>".$result;*/
		curl_close($curl); 
	}
	
	}


}