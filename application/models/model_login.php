<?php
include "application/plagins/model_vk.php";
/*
структура таблицы
users_id идентификатор
news_date дата регистрации
login логин
pwd пароль
name имя
email 
is_active флаг активности
activate код активации или восстановления пароля
*/
class Model_Login extends Model 
{

	private $table_name = 'users';
	private $primary_key = 'user_id';
	
	
	public function get_orders() 
	{
		$temp = "Ваши заказы";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		$total = $this->db->GetOne ("SELECT Count(*) FROM orders WHERE user_id = '".$_COOKIE['id']."'", 0);
		if ($total > 0)
		{
			$sql="SELECT orders.*, concat('г. ', orders.city, ', ул. ', orders.street, ', ', orders.dom, ' ', orders.office) as adr, (SELECT sum(price*quantity) FROM order_product WHERE order_product.order_id = orders.order_id) as sum FROM orders WHERE user_id = '".$_COOKIE['id']."' ORDER BY news_date desc";	
			$result=$this->db->ExecuteSql($sql, $this->Pages_GetLimits());
			while ($row = $this->db->FetchArray ($result))	
			{
				$id = $row["order_id"];
				
				$sql = "SELECT shop_id, name, price, quantity FROM order_product WHERE order_id = '".$id."'";
				$result1=$this->db->ExecuteSql($sql);		
				$arr_order_item = array();
				if ($result1)
				{
					while ($row1 = $this->db->FetchArray ($result1))	
					{				
						$product = $this->db->GetEntry("SELECT parent_id, url FROM shop WHERE shop_id='".$row1['shop_id']."'");
						$fullurl1 = GetLinkCat($this->menuarr, $product["parent_id"]);
						$link = $this->siteUrl.SHOP_LINK."/".$fullurl1.$product["url"];	
						
						$arr_order_item[] = array (
							"shop_id" => $row1['shop_id'],
							"name" => $this->dec($row1['name']),
							"price" => $this->dec($row1['price']),
							"quantity" => $this->dec($row1['quantity']),	
							"link" => $link
						);						
					}
					$this->db->FreeResult ($result1);
				}			
				
				$data ["row"][] = array (
					"id" => $id,
					"name" => $this->dec($row['name']),
					"phone" => $this->dec($row['phone']),
					"email" => $this->dec($row['email']),
					"address" => $this->dec($row['adr']),
					"sum" => $this->dec($row['sum']),
					"comment" => mb_substr(strip_tags($this->dec($row['comment'])), 0, 70),
					"date" => date("d-m-Y H:i", $this->dec($row['news_date'])),
					"status" => $this->GetStatusName($row['status']),
					"status-number" => $row['status'],
					"order_item" => $arr_order_item,
				);						
			}
			$this->db->FreeResult ($result);
			$data['pages'] = $this->Pages_GetLinks_Site($total, "/login/orders?");
		}
		else
		{
			$data ["empty_row"] = "У вас еще не было ни одного заказа";
		}
		return $data;
	}
	
	public function get_data() 
	{
		$temp = "Авторизация";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		$data["error"] = $this->GetError("error");
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		
		
		$redirect_uri = $this->siteUrl.'login/authVK'; // Адрес сайта		
		$vk = new Model_Vk();
		$data["vklink"] = $vk->get_url_autorize($redirect_uri);
		
		return $data;
	}
	
	public function get_cabinet() 
	{
		$temp = "Личный кабинет";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		if (isset($_COOKIE['social']))
		{
			$data["linkpassword"] = "";
		}
		else
		{			
			$data["linkpassword"] = "<a href='/login/changepassword'>Изменить пароль</a><br>";
		}
		$data["linkuseredit"] = "<a href='/login/user_edit'>Изменить профиль</a><br>";
		
		$sql = "SELECT *, concat('г. ', ycity, ', ул. ', ystreet, ', ', ydom, ' ', yoffice) as yadr, concat('г. ', fcity, ', ул. ', fstreet, ', ', fdom, ' ', foffice) as fadr FROM ".$this->table_name." WHERE user_id = '".$_COOKIE['id']."'";
		$row = $this->db->GetEntry($sql);
		$data["login"] = $row["login"];
		$data["email"] = $row["email"];
		$data["company"] = $this->dec($row["company"]);
		$data["inn"] = $row["inn"];
		$data["kpp"] = $row["kpp"];
		$data["yaddress"] = $row["yadr"];
		$data["faddress"] = $row["fadr"];
		$data["rs"] = $row["rs"];
		$data["bik"] = $row["bik"];
		$data["bank"] = $row["bank"];
		$data["tel"] = $row["tel"];
		$data["dogovor"] = $row["dogovor"];

		return $data;
	}
	
	public function get_authVK ()
	{
		$redirect_uri = $this->siteUrl.'login/authVK'; // Адрес сайта		
		$vk = new Model_Vk();
		$userInfo = $vk->get_authVK($redirect_uri);
		if ($userInfo)
		{
			$login = $userInfo["response"][0]["last_name"]." ".$userInfo["response"][0]["first_name"];
			$sql = "SELECT user_id FROM ".$this->table_name." WHERE social_id = '".$userInfo["response"][0]["id"]."' and social = 'vk'";
			$user = $this->db->GetOne($sql, 0);
			$hash = md5($this->getUnID(16));
			$pwd =  md5($this->getUnID(16));
			$email = $userInfo["response"][0]['email'];
			if ($user == 0)
			{
				$sql = "SELECT user_id, login FROM ".$this->table_name." WHERE email = '".$email."'";
				$user_old = $this->db->GetEntry($sql);
				if ($user_old)
				{					
					$user = $user_old['user_id'];
					$login = $user_old['login'];
					$sql="UPDATE users SET hash = '$hash', social_id = '".$userInfo["response"][0]["id"]."', social = 'vk' WHERE user_id='$user'";
					$this->db->ExecuteSql($sql);					
				}
				else
				{
					$sql = "Insert Into `users` (news_date, login, pwd, email, hash, social_id, social, is_active) Values ('".time()."', '$login', '$pwd', '$email', '$hash', '".$userInfo["response"][0]["id"]."', 'vk', '1')";
					
					$data = array(
						"news_date" =>time(),
						"login" =>$login,
						"pwd" =>$pwd,
						"email" =>$email,
						"hash" =>$hash,
						"social_id" =>$userInfo["response"][0]["id"],
						"social" =>'vk',
						"is_active" =>'1',
						"sex" =>$userInfo["response"][0]["sex"],
						"avatar" =>$userInfo["response"][0]["photo_big"],
						"birthday" =>date('Y-m-d', strtotime($userInfo["response"][0]["bdate"])),					
						"first_name" =>$userInfo["response"][0]["first_name"],					
						"last_name" =>$userInfo["response"][0]["last_name"],					
					);					
					$sql = "Insert Into ".$this->table_name." ".ArrayInInsertSQL ($data);
					$this->db->ExecuteSql($sql);
					$user = $this->db->GetInsertID ();
				}				
			}
			else
			{
				$sql="UPDATE users SET hash = '$hash' WHERE user_id='$user'";
				$this->db->ExecuteSql($sql);
				$sql = "SELECT login FROM ".$this->table_name." WHERE user_id='$user'";
				$login = $this->db->GetOne($sql, $login);
			}			
			setcookie("id", $user, time()+60*60*24*30, "/");
			setcookie("hash", $hash, time()+60*60*24*30, "/");
			setcookie("U_LOGIN", $login, time()+60*60*24*30, "/");			
			setcookie("social", "vk", time()+60*60*24*30, "/");			
			//echo $token["email"];
			$this->Redirect($this->siteUrl."login/cabinet");
		}
		else
		{
			$this->Redirect($this->siteUrl."login/");
		}
	}	
	
	public function get_changepassword() 
	{		
		if (isset($_COOKIE['social']))
		{
			$this->Redirect($this->siteUrl."login/cabinet");
		}
		$temp = "Изменение пароля";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		if (isset($_POST["psw"]))
		{		
			$psw = $this->enc ($this->GetValidGP ("psw", "Пароль", VALIDATE_PASSWORD));
			$psw1 = $this->enc ($this->GetValidGP ("psw1", "Пароль", VALIDATE_PASSWORD));
			$psw2 = $this->enc ($this->GetGP("psw2"));
			if ($psw2 != $psw1) {$this->SetError("psw2", "Пароли не совпадают");}
			
			if ($this->errors['err_count'] > 0) {
				$data["message"] = "";
			}
			else
			{
				$sql = "SELECT Count(*) FROM users WHERE user_id = '".$_COOKIE["id"]."' and pwd = '".md5($psw)."'";
				$total = $this->db->GetOne ($sql);
				if ($total > 0)
				{
					$sql = "UPDATE users SET pwd='".md5($psw1)."' WHERE user_id = '".$_COOKIE["id"]."'";
					$this->db->ExecuteSql($sql);
					$data["message"] = "Пароль успешно изменен";
				}
				else
				{
					$data["message"] = "Не верно введен старый пароль";
				}
				
			}
		}
		else
		{
			$data["message"] = "";			
		}
		
		$data["psw_error"] = $this->GetError("psw");
		$data["psw1_error"] = $this->GetError("psw1");
		$data["psw2_error"] = $this->GetError("psw2");
		return $data;
	}
	
	public function get_lostpassword() 
	{		
		$temp = "Восстановление пароля";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		if (isset($_GET["hash"]))
		{
			$email = $this->GetGP_SQL("email", "");
			$hash = $this->GetGP_SQL("hash", "");
			$sql = "SELECT Count(*) FROM users WHERE email='$email' and hash='$hash'";
			if ($this->db->GetOne($sql, 0) > 0)
			{
				$psw = $this->getUnID(16);
				//$login = $this->db->GetOne("SELECT login FROM users WHERE email = '$email'", "");
				$sql = "UPDATE users SET pwd='".md5($psw)."', hash='".md5(time())."' WHERE email = '$email'";
				$this->db->ExecuteSql($sql);
				$SiteName = $this->db->GetSetting ("SiteName");
				$subject = "Восстановление пароля на сайте ".$SiteName;
				$message = "Добрый день!<br><br>Логин: $email<br>Пароль: $psw<br><br>Обязательно измените пароль!<br><br>С уважением, Администрация ".$SiteName;
				$this->SendMailSMTP ($email, $subject, $message);
				$data["message"] = "Данные для <a href='/login/'>входа</a> отправлены на e-mail";
			}
			else
			{
				$data["message"] = "Данный email не зарестрирован или истек срок жизни ссылки, <a href='/login/registration'>зарегстрируйте</a> или <a href='/login/lostpassword'>восстановите пароль</a> повторно";
			}			
		}
		else
		{
			$data["message"] = $this->GetError("message");
		}		
		$data["email"] = $this->GetGP("email", "");
		$data["email_error"] = $this->GetError("email");
		$data["error_captcha"] = $this->GetError("capcha");
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		return $data;
	}
	
	public function get_lostpasswordOn() 
	{
		$email = $this->enc ($this->GetValidGP ("email", "Email адрес", VALIDATE_EMAIL));	
		 /*@@@@@@@@@@@@@@@@@@-- Begin: kcaptcha --@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
       /* $code = $this->GetGP("keystring");
		$flag = $this->ChecCode($code);
		if (!$flag) {$this->SetError("capcha", "Не верная последовательность");}      	*/
      	/*@@@@@@@@@@@@@@@@@@-- END: kcaptcha --@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        if ($this->errors['err_count'] > 0) {
			return false;
        }
		 else 
		 {
			$hash = md5(mt_rand(1, 10000));
			$sql = "UPDATE users SET hash='$hash' WHERE email = '$email'";
			$this->db->ExecuteSql($sql);
			$SiteName = $this->db->GetSetting ("SiteName");
			$subject = "Восстановление пароля на сайте ".$SiteName;
			$link = $this->siteUrl."login/lostpassword?email=".$email."&hash=".$hash;
			$message = "Добрый день!<br><br>Вы запрашивали восстановление пароля на сайте ".$this->siteUrl.", для восстановления пароля перейдите по ссылке ниже, если Вы не создавали запрос, то просто проигнорируйте это письмо.<br><br>$link<br><br>С уважением, Администрация ".$SiteName;
			$this->SendMailSMTP ($email, $subject, $message);
			$this->SetError("message", "Инструкции по восстановлению пароля отправлены на e-mail");
			return true;
		 }
	}
	
	public function get_registration() 
	{		
		$temp = "Регистрация";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		$data["company"] = $this->dec($this->GetGP("company", ""));
		$data["company_error"] = $this->GetError("company");
		
		$data["email"] = $this->GetGP("email", "");
		$data["email_error"] = $this->GetError("email");
		
		$data["inn"] = $this->GetGP("inn", "");
		$data["inn_error"] = $this->GetError("inn");
		
		$data["kpp"] = $this->GetGP("kpp", "");
		$data["kpp_error"] = $this->GetError("kpp");
		
		$data["yaddress"] = $this->GetGP("yaddress", "");
		$data["yaddress_error"] = $this->GetError("yaddress");
		
		$data["ycity"] = $this->GetGP("ycity", "");
		$data["ycity_error"] = $this->GetError("ycity");
		
		$data["ystreet"] = $this->GetGP("ystreet", "");
		$data["ystreet_error"] = $this->GetError("ystreet");
		
		$data["ydom"] = $this->GetGP("ydom", "");
		$data["ydom_error"] = $this->GetError("ydom");
		
		$data["yoffice"] = $this->GetGP("yoffice", "");
		$data["yoffice_error"] = $this->GetError("yoffice");
		
		$data["faddress"] = $this->GetGP("faddress", "");
		$data["faddress_error"] = $this->GetError("faddress");
		
		$data["fcity"] = $this->GetGP("fcity", "");
		$data["fcity_error"] = $this->GetError("fcity");
		
		$data["fstreet"] = $this->GetGP("fstreet", "");
		$data["fstreet_error"] = $this->GetError("fstreet");
		
		$data["fdom"] = $this->GetGP("fdom", "");
		$data["fdom_error"] = $this->GetError("fdom");
		
		$data["foffice"] = $this->GetGP("foffice", "");
		$data["foffice_error"] = $this->GetError("foffice");
				
		$data["rs"] = $this->GetGP("rs", "");
		$data["rs_error"] = $this->GetError("rs");
		
		$data["bik"] = $this->GetGP("bik", "");
		$data["bik_error"] = $this->GetError("bik");
		
		$data["bank"] = $this->GetGP("bank", "");
		$data["bank_error"] = $this->GetError("bank");		
		
		$data["login"] = $this->GetGP("login", "");
		$data["login_error"] = $this->GetError("login");
		
		$data["tel"] = $this->GetGP("tel", "");
		$data["tel_error"] = $this->GetError("tel");
		
		$data["psw_error"] = $this->GetError("psw");
		$data["psw1_error"] = $this->GetError("psw1");
		$data["error_captcha"] = $this->GetError("capcha");
		
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		return $data;
	}
	
	public function get_user_edit() 
	{		
		$temp = "Изменения личных данных";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		
		$flag = 0;
		
		$user = $this->db->GetEntry("SELECT * FROM users WHERE user_id = '".$this->GetCookie("id", -1)."'");

		$data["company"] = $this->dec($this->GetValidGP ("company", "Название компании", VALIDATE_NOT_EMPTY, $this->dec($user["company"])));
		$flag = ($this->dec($data["company"]) == $this->dec($user["company"]))?$flag:++$flag;
		$data["company_error"] = $this->GetError("company");
		
		$data["inn"] = $this->dec ($this->GetValidGP ("inn", "ИНН", VALIDATE_INN, $this->dec($user["inn"])));
		$flag = ($this->dec($data["inn"]) == $this->dec($user["inn"]))?$flag:++$flag;
		$data["inn_error"] = $this->GetError("inn");
		
		$data["kpp"] = $this->GetGP("kpp", $user["kpp"]);
		if (!empty($data["kpp"]))
		{
			$data["kpp"] = $this->dec ($this->GetValidGP ("kpp", "КПП", VALIDATE_KPP, $user["kpp"]));
			$flag = ($this->dec($data["kpp"]) == $this->dec($user["kpp"]))?$flag:++$flag;
		}
		$data["kpp_error"] = $this->GetError("kpp");
		
		/*$data["yaddress"] = $this->dec ($this->GetValidGP ("yaddress", "Юридический адрес", VALIDATE_NOT_EMPTY, $this->dec($user["yaddress"])));
		$flag = ($this->dec($data["yaddress"]) == $this->dec($user["yaddress"]))?$flag:++$flag;*/
		//$data["yaddress_error"] = $this->GetError("yaddress");
		
		$data["ycity"] = $this->dec ($this->GetValidGP ("ycity", "Город", VALIDATE_NOT_EMPTY, $this->dec($user["ycity"])));
		$flag = ($this->dec($data["ycity"]) == $this->dec($user["ycity"]))?$flag:++$flag;
		$data["ycity_error"] = $this->GetError("ycity");
		
		$data["ystreet"] = $this->dec ($this->GetValidGP ("ystreet", "Улица", VALIDATE_NOT_EMPTY, $this->dec($user["ystreet"])));
		$flag = ($this->dec($data["ystreet"]) == $this->dec($user["ystreet"]))?$flag:++$flag;
		$data["ystreet_error"] = $this->GetError("ystreet");
		
		$data["ydom"] = $this->GetGP("ydom", $user["ydom"]);
		$flag = ($this->dec($data["ydom"]) == $this->dec($user["ydom"]))?$flag:++$flag;
		
		$data["yoffice"] = $this->GetGP("yoffice", $user["yoffice"]);
		$flag = ($this->dec($data["yoffice"]) == $this->dec($user["yoffice"]))?$flag:++$flag;

		
		if ($this->GetGP("y_eqv_f", 0) == 1)
		{
			$data["fcity"] = $data["ycity"];
			$data["fstreet"] = $data["ystreet"];
			$data["fdom"] = $data["ydom"];
			$data["foffice"] = $data["yoffice"];
		}
		else
		{
			$data["fcity"] = $this->dec ($this->GetValidGP ("fcity", "Город", VALIDATE_NOT_EMPTY, $this->dec($user["fcity"])));
			$data["fstreet"] = $this->dec ($this->GetValidGP ("fstreet", "Улица",VALIDATE_NOT_EMPTY, $this->dec($user["fstreet"])));
			$data["fdom"]  = $this->dec ($this->GetGP("fdom", $this->dec($user["fdom"])));
			$data["foffice"] = $this->dec ($this->GetGP("foffice", $this->dec($user["foffice"])));
		}
		$data["fcity_error"] = $this->GetError("fcity");
		$data["fstreet_error"] = $this->GetError("fstreet");
						
		$data["rs"] = $this->enc ($this->GetValidGP ("rs", "Расчетный счет", VALIDATE_RS, $user["rs"]));
		$data["rs_error"] = $this->GetError("rs");
		
		$data["bik"] =  $this->enc ($this->GetValidGP ("bik", "БИК", VALIDATE_BIK, $user["bik"]));
		$data["bik_error"] = $this->GetError("bik");
		
		$data["bank"] = $this->dec ($this->GetValidGP ("bank", "Наименование банка", VALIDATE_NOT_EMPTY, $this->dec($user["bank"])));
		$data["bank_error"] = $this->GetError("bank");		
		
		$data["login"] = $this->dec ($this->GetValidGP ("login", "Ваш логин", VALIDATE_NOT_EMPTY, $this->dec($user["login"])));
		$data["login_error"] = $this->GetError("login");
		
		$data["tel"] = $this->enc ($this->GetValidGP ("tel", "Телефон", VALIDATE_TEL, $user["tel"]));
		$data["tel_error"] = $this->GetError("tel");
			
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		
		if (($this->GetGP("edit", "") == 1) && ($this->errors['err_count'] == 0)){
			$temp_arr = array(
				"company" => $this->enc ($data["company"]),
				"inn" => $this->enc ($data["inn"]),
				"kpp" => $this->enc ($data["kpp"]),
				"ycity" => $this->enc ($data["ycity"]),
				"ystreet" => $this->enc ($data["ystreet"]),
				"ydom" => $this->enc ($data["ydom"]),
				"yoffice" => $this->enc ($data["yoffice"]),
				"fcity" => $this->enc ($data["fcity"]),
				"fstreet" => $this->enc ($data["fstreet"]),
				"fdom" => $this->enc ($data["fdom"]),
				"foffice" => $this->enc ($data["foffice"]),
				"rs" => $this->enc ($data["rs"]),
				"bik" => $this->enc ($data["bik"]),
				"bank" => $this->enc ($data["bank"]),
				"login" => $this->enc ($data["login"]),
				"tel" => $this->enc ($data["tel"]),
				"dogovor" => ($flag)?"0":"1"
			);			
			$sql = "UPDATE ".$this->table_name." SET ".ArrayInUpdateSQL ($temp_arr)." WHERE {$this->primary_key}='".$this->GetCookie("id", -1)."'";
			$this->db->ExecuteSql ($sql);			
		}
		return $data;
	}	
	
	public function get_registrationOn() 
	{
		$company = $this->enc ($this->GetValidGP ("company", "Название компании", VALIDATE_NOT_EMPTY));
		$email = $this->enc ($this->GetValidGP ("email", "Email адрес", VALIDATE_EMAIL));
		$inn = $this->enc ($this->GetValidGP ("inn", "ИНН", VALIDATE_INN));
		$kpp = $this->GetGP("kpp", "");
		if (!empty($kpp))
		{
			$kpp = $this->enc ($this->GetValidGP ("kpp", "КПП", VALIDATE_KPP));
		}
		//$yaddress = $this->enc ($this->GetValidGP ("yaddress", "Юридический адрес", VALIDATE_NOT_EMPTY));
		$ycity = $this->enc ($this->GetValidGP ("ycity", "Город", VALIDATE_NOT_EMPTY));
		$ystreet = $this->enc ($this->GetValidGP ("ystreet", "Улица", VALIDATE_NOT_EMPTY));
		$ydom = $this->enc ($this->GetGP("ydom", ""));
		$yoffice = $this->enc ($this->GetGP("yoffice", ""));
		if ($this->GetGP("y_eqv_f", 0) == 1)
		{
			$fcity = $ycity;
			$fstreet = $ystreet;
			$fdom = $ydom;
			$foffice = $yoffice;
		}
		else
		{
			$fcity = $this->enc ($this->GetValidGP ("fcity", "Город", VALIDATE_NOT_EMPTY));
			$fstreet = $this->enc ($this->GetValidGP ("fstreet", "Улица", VALIDATE_NOT_EMPTY));
			$fdom = $this->enc ($this->GetGP("fdom", ""));
			$foffice = $this->enc ($this->GetGP("foffice", ""));
		}
		$rs = $this->enc ($this->GetValidGP ("rs", "Расчетный счет", VALIDATE_RS));
		$bik = $this->enc ($this->GetValidGP ("bik", "БИК", VALIDATE_BIK));
		$bank = $this->enc ($this->GetValidGP ("bank", "Наименование банка", VALIDATE_NOT_EMPTY));
		$login = $this->enc ($this->GetValidGP ("login", "Ваш логин", VALIDATE_NOT_EMPTY));
		$tel = $this->enc ($this->GetValidGP ("tel", "Телефон", VALIDATE_TEL));
		
		$psw = $this->enc ($this->GetValidGP ("psw", "Пароль", VALIDATE_PASSWORD));
        $psw1 = $this->enc ($this->GetGP("psw1"));
		if ($psw != $psw1) {$this->SetError("psw1", "Пароли не совпадают");}
         /*@@@@@@@@@@@@@@@@@@-- Begin: kcaptcha --@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        /*$code = $this->GetGP("g-recaptcha-response", "хрен");
		$flag = $this->ChecCode($code);		
		if (!$flag) {$this->SetError("captcha", "Отметьте что вы не робот");}	   	*/
      	/*@@@@@@@@@@@@@@@@@@-- END: kcaptcha --@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        if ($this->errors['err_count'] > 0) {
			return false;
        }
        else 
		{
			$sql = "SELECT inn, email FROM users WHERE inn = '$inn' or email = '$email'";
			$row = $this->db->GetEntry($sql);
			if ($row["inn"] == $inn)
			{
				$this->SetError("inn", "Такой ИНН уже зарегистрирован");
				return false;
			}
			elseif ($row["email"] == $email)
			{
				$this->SetError("email", "Такой e-mail уже зарегистрирован");
				return false;
			}
			else
			{					
				$hash = md5(mt_rand(1, 10000));
				
				$temp_arr = array (
					"news_date" => time(),
					"login" => $login,
					"pwd" => md5($psw),
					"email" => $email,
					"hash" => $hash,
					"company" => $company,
					"inn" => $inn,
					"kpp" => $kpp,
					"ycity" => $ycity,
					"ystreet" => $ystreet,
					"ydom" => $ydom,
					"yoffice" => $yoffice,
					"fcity" => $fcity,
					"fstreet" => $fstreet,
					"fdom" => $fdom,
					"foffice" => $foffice,
					"rs" => $rs,
					"bik" => $bik,
					"bank" => $bank,
					"tel" => $tel,
				);
											
				$sql = "Insert Into ".$this->table_name." ".ArrayInInsertSQL ($temp_arr);
				$this->db->ExecuteSql ($sql);
				$SiteName = $this->db->GetSetting ("SiteName");
				$subject = "Регистрация на сайте ".$SiteName;
				$link = $this->siteUrl."login/activate?login=".$email."&hash=".$hash;
				$message = "Добрый день!<br><br>Вы только что зарегистрировались на сайте ".$this->siteUrl.", для активации вашего аккаунта перейдите по ссылке ниже, если Вы не регистрировались, то просто проигнорируйте это письмо.<br><br>$link<br><br>С уважением, Администрация ".$SiteName;
				$this->SendMailSMTP ($email, $subject, $message);
				$this->SetError("message", "Регистраниця прошла успешно на Ваш e-mail выслано письмо с активацией аккаунта");
				return true;
			}
        }

	}
	
	public function get_activate()
	{
		$temp = "Активация пользователя";
		$data = array(
			"title" => $temp, 		
			"text" => $temp,
			"head_title" =>	$temp,
			"description" => $temp,
			"keywords" => $temp,			
		);
		$data["nav"] = MAIN_NAV."<li>".$temp."</li>";
		$login = $this->GetGP_SQL("login", "");
		$hash = $this->GetGP_SQL("hash", "");
		$sql = "SELECT Count(*) FROM users WHERE email='$login' and hash='$hash'";
		if ($this->db->GetOne($sql, 0) > 0)
		{
			$sql = "UPDATE users SET is_active='1', hash='".md5(time())."' WHERE email = '$login'";
			//echo $sql;
			$this->db->ExecuteSql($sql);
			$data["message"] = "Ваш акаунт активирован воспользуйтесь формой <a href='/login/'>входа</a> чтобы авторизоваться на сайте";
		}
		else
		{
			$data["message"] = "Данного пользователя не существует или истек срок жизни ссылки, <a href='/login/registration'>зарегстрируйте</a> или <a href='/login/'>войдите</a> чтобы выслать ссылку повторно";
		}
		return $data;
	}
	
	public function avtorized() 
	{
		if (isset($_SESSION[$_SERVER['REMOTE_ADDR']]['ip']))
		{			
			if (($_SESSION[$_SERVER['REMOTE_ADDR']]['time']) > time())
			{
				$this->SetError("error", "По пробуйте чуть позже");				
				return false;
			}
			else
			{				
				$this->SetError("error", "По пробуйте чуть позже");
				unset($_SESSION[$_SERVER['REMOTE_ADDR']]['ip']);
				return false;
			}
		}
		else
		{
			$email = $this->GetGP_SQL("email", "");
			$pwd = md5($this->GetGP_SQL("pwd", ""));
			$sql = "SELECT user_id, company, is_active FROM ".$this->table_name." WHERE email = '".$email."' and pwd = '".$pwd."'";		
			
			$user = $this->db->GetEntry($sql);
			if (isset($user["is_active"]) && $user["is_active"] == '1') 
			{
				$hash = md5($this->getUnID(16));
				setcookie("id", $user["user_id"], time()+60*60*24*30, "/");
				setcookie("hash", $hash, time()+60*60*24*30, "/");
				setcookie("U_LOGIN", $this->dec($user["company"]), time()+60*60*24*30, "/");
				$sql="UPDATE users SET hash = '$hash' WHERE user_id='".$user["user_id"]."'";
				$this->db->ExecuteSql($sql);
				return true;
			}
			elseif(isset($user["is_active"]))
			{
				$hash = md5(mt_rand(1, 10000));
				$sql="UPDATE users SET hash = '$hash' WHERE user_id='".$user["user_id"]."'";
				$this->db->ExecuteSql($sql);
				$SiteName = $this->db->GetSetting ("SiteName");
				$subject = "Активация аккаунта на сайте ".$SiteName;
				$link = $this->siteUrl."login/activate?login=".$email."&hash=".$hash;
				$message = "Добрый день!<br><br>Вы недавно зарегистрировались на сайте ".$this->siteUrl.", для активации вашего аккаунта перейдите по ссылке ниже, если Вы не регистрировались, то просто проигнорируйте это письмо.<br><br>$link<br><br>С уважением, Администрация ".$SiteName;
				$this->SendMailSMTP ($email, $subject, $message);
				$this->SetError("error", "Ваш аккаунт еще не активирован");
			}
			else
			{
				$this->SetError("error", "Неверный логин или пароль");
				
				$this->history($email, "off");
				$time1 = time()-300;
				$time = $time1+600;
				$sql = "SELECT Count(*) FROM log WHERE news_date < '$time' and news_date > '$time1' and status='off' and ip='".$_SERVER['REMOTE_ADDR']."'";
				$total = $this->db->GetOne($sql, 0);				
				if ($total > 3)
				{
					$_SESSION[$_SERVER['REMOTE_ADDR']]['ip'] = $_SERVER['REMOTE_ADDR'];				
					$_SESSION[$_SERVER['REMOTE_ADDR']]['time'] = $time;
				}
				return false;
			}
		}
		
	}
	
	function history($name, $status)
	{
		$sql = "INSERT INTO log (admin_pages, name, ip, news_date, status) VALUE ('Авторизация сайт', '$name', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '$status')";
		$this->db->ExecuteSql($sql);
	}

}
