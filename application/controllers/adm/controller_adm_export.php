<?php
include "application/models/adm/model_adm_export.php";
class Controller_Adm_Export extends Controller 
{

	function __construct() 
	{	
		$this->model = new Model_Adm_Export();
		$this->view = new View();
		//проверяем есть ли доступ к этому разделу
		$this->model->Get_Access($this->model->table_name);
		// получаем шапку
		$this->model->data = $this->model->GetFixed();
	}
	
	function action_index()	
	{									
		$this->view->generate_adm('template_view.php', 'export.php',  $this->model->data, $this->model->get_data());
	}	
	
	function action_orders()	
	{									
		$this->model->ExportOrders();
	}
	
	function action_catalog()	
	{									
		$this->model->ExportCatalog();
	}	
	
	function action_product()	
	{									
		$this->model->ExportProduct();
	}

}