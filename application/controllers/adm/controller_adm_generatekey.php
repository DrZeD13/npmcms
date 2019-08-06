<?php
include "application/models/adm/model_adm_generatekey.php";
class Controller_Adm_Generatekey extends Controller 
{

	function __construct() 
	{	
		$this->model = new Model_Adm_Generatekey();
		$this->view = new View();
		//проверяем есть ли доступ к этому разделу
		$this->model->Get_Access($this->model->table_name);
		// получаем шапку
		$this->model->data = $this->model->GetFixed();
	}
	
	function action_index()	
	{									
		$this->view->generate_adm('template_view.php', 'generatekey.php',  $this->model->data, $this->model->get_data());
	}	
	
	function action_orders()	
	{									
		$this->model->GeneratekeyOrders();
	}
	
	function action_catalog()	
	{									
		$this->model->GeneratekeyCatalog();
	}	
	
	function action_product()	
	{									
		$this->model->GeneratekeyProduct();
	}
	
	function action_fieldsitem()	
	{									
		$this->model->GeneratekeyFieldsItem();
	}
	
	function action_fields()	
	{									
		$this->model->GeneratekeyFields();
	}
	
	function action_category()	
	{									
		$this->model->GeneratekeyCategory();
	}

}