<?php
include "application/models/model_bill.php";
class Controller_Bill
{

	function __construct() 
	{	
		$this->model = new Model_Bill();
		//$this->view = new View();
		//$this->model->data = $this->model->GetFixedSite();
	}
	
	function action_index()	
	{									
		$data = $this->model->get_data();
	}
	
	function action_act()	
	{									
		$data = $this->model->get_data();
	}
	
}