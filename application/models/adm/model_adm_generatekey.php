<?php
/*
*/
class Model_Adm_Generatekey extends Model 
{

	var $table_name = 'generatekey';
	
	/*
00000001-01bc-11d9-848a-ff112f43529a

bc-11d9-848a-ff112f43529a – просто константа (можно любую поставить)
01 – признак к чему принадлежит это guid
01 – Классификатор, Владелец
02 - Каталог
03 - Товары
04 - Заказы
05 - Доп. поля
06 - Варианты доп. Полей
00000001 – ID записи в мой базе данных в шестнадцатеричном представлении, плюс в начале добавлены 0, до 8 символов

	*/
	
	// "01" - Главный
	// "02" - Каталог
	// "03" - Товары
	// "04" - Заказы
	// "05" - Доп. поля
	// "06" - Варианты доп. полей
	
	public function get_data() 
	{
		$title = $this->GetAdminTitle($this->table_name);
		$data = array (
			'title' => $title,
			'main_title' => $title,
			'token' => $this->GetSession("token", false)
		);				
		return $data;
	}
	
	public function GeneratekeyCategory() 
	{
		$mask = "-06".$this->db->GetSetting("GUID");
		$sql = "SELECT category_id FROM category";
		$result = $this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{	
				$guid =  $this->GenerateGUID($row["category_id"], $mask);
				$sql = "UPDATE category SET guid = '".$guid."' WHERE category_id = '".$row["category_id"]."'";
				$this->db->ExecuteSql($sql);
				echo $row["category_id"].' = '.$guid.'<br>';
			}
			$this->db->FreeResult ($result);
		}
	}
	
	public function GeneratekeyFieldsItem() 
	{
		$mask = "-06".$this->db->GetSetting("GUID");
		$sql = "SELECT field_item_id FROM fields_item";
		$result = $this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{	
				$guid =  $this->GenerateGUID($row["field_item_id"], $mask);
				$sql = "UPDATE fields_item SET guid = '".$guid."' WHERE field_item_id = '".$row["field_item_id"]."'";
				$this->db->ExecuteSql($sql);
				echo $row["field_item_id"].' = '.$guid.'<br>';
			}
			$this->db->FreeResult ($result);
		}
	}
	
	public function GeneratekeyFields() 
	{
		$mask = "-05".$this->db->GetSetting("GUID");
		$sql = "SELECT field_id FROM fields";
		$result = $this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{	
				$guid =  $this->GenerateGUID($row["field_id"], $mask);
				$sql = "UPDATE fields SET guid = '".$guid."' WHERE field_id = '".$row["field_id"]."'";
				$this->db->ExecuteSql($sql);
				echo $row["field_id"].' = '.$guid.'<br>';
			}
			$this->db->FreeResult ($result);
		}
	}
	
	public function GeneratekeyOrders() 
	{
		$mask = "-04".$this->db->GetSetting("GUID");
		$sql = "SELECT order_id FROM orders";
		$result = $this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{	
				$guid =  $this->GenerateGUID($row["order_id"], $mask);
				$sql = "UPDATE orders SET guid = '".$guid."' WHERE order_id = '".$row["order_id"]."'";
				$this->db->ExecuteSql($sql);
				echo $row["order_id"].' = '.$guid.'<br>';
			}
			$this->db->FreeResult ($result);
		}
	}
	
	public function GeneratekeyCatalog() 
	{
		$mask = "-02".$this->db->GetSetting("GUID");
		$sql = "SELECT shop_id FROM shops";
		$result = $this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{	
				$guid =  $this->GenerateGUID($row["shop_id"], $mask);
				$sql = "UPDATE shops SET guid = '".$guid."' WHERE shop_id = '".$row["shop_id"]."'";
				$this->db->ExecuteSql($sql);
				echo $row["shop_id"].' = '.$guid.'<br>';
			}
			$this->db->FreeResult ($result);
		}
	}
	
	public function GeneratekeyProduct() 
	{
		$mask = "-03".$this->db->GetSetting("GUID");
		$sql = "SELECT shop_id FROM shop";
		$result = $this->db->ExecuteSql($sql);
		if ($result)
		{
			while ($row = $this->db->FetchArray ($result))	
			{	
				$guid =  $this->GenerateGUID($row["shop_id"], $mask);
				$sql = "UPDATE shop SET guid = '".$guid."' WHERE shop_id = '".$row["shop_id"]."'";
				$this->db->ExecuteSql($sql);
				echo $row["shop_id"].' = '.$guid.'<br>';
			}
			$this->db->FreeResult ($result);
		}
	}
	
	public function GenerateGUID($id, $mask) 
	{
		$id = dechex($id);
		 while (strlen($id) < 8)
		 {
			 $id = "0".$id;
		 }
		 return $id.$mask;
	}

}
