<?php
/*

*/
class Model_Bill extends Model 
{
	private $table_name = '`bill`';
	
	public function get_data() 
	{				
		$order_id = $this->GetGP("order", 0);
		if ($this->get_bill_pdg($order_id))
		{
			
		}
		else
		{
			$this->error404();
		};
		exit;		
	}
}