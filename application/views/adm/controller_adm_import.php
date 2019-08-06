<?php
include "application/models/adm/model_adm_import.php";
class Controller_Adm_Import extends Controller 
{

	function __construct() 
	{	
		$this->model = new Model_Adm_Import();
		$this->view = new View();
		//проверяем есть ли доступ к этому разделу
		$this->model->Get_Access($this->model->table_name);
		// получаем шапку
		$this->model->data = $this->model->GetFixed();
	}
	
	function action_index()	
	{									
		$this->view->generate_adm('template_view.php', 'import.php',  $this->model->data, $this->model->get_data());
	}	

}