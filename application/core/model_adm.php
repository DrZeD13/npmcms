<?php
class Model_Adm
{	
	var $db = null; //для работы с базой данных
	
	function __construct()	
	{		
       	global $db;
        $this->db = $db;		
	}
	
	// записывает изменения в логи
	//$status - какое событие изменение, удаление и т. д.
	//$admin_pages - id модуля в админке
	//$name - используется при удалении записи, и авторизации записывается какой логин был введен при авторизации
	//$item_id - id записи с которой были манипуляции
	function history($status, $admin_pages, $name, $item_id)
	{
		$sql = "INSERT INTO log (admin_id, name, ip, news_date, status, admin_pages, item_id) VALUE ('".$_SESSION["A_ID"]."', '$name', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '$status', '$admin_pages', '$item_id')";
		$this->db->ExecuteSql($sql);
	}
	
}