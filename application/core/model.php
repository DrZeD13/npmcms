<?php
include_once('model_default.php');
use Dompdf\Dompdf;
class Model extends Model_Default
{	
	private $nds = 1.2;
	function __construct()	
	{		
       parent::__construct();				
	}
	
	function main_head ()
	{
		$parse = parse_url($_SERVER['REQUEST_URI']);
		$routes = explode('/', $parse['path']);		
		$main = "";
		// получаем счетчики
		$data = $this->GetCounters();
		// залогинился?
		$data["is_user"] = $this->is_user;
		// главное меню
		$data["main_top_menu"] = $main.GetUlMenu($this->siteUrl, $this->menutree, $this->cid, 1);
		$data["catalog_ul"] = GetUlMenu($this->siteUrl.SHOP_LINK."/", $this->menuarrtree, (isset($row["shop_id"]))?$row["shop_id"]:0, 3);
		$data["foot_catalog"] = GetUlMenu($this->siteUrl.SHOP_LINK."/", $this->menuarrtree, (isset($row["shop_id"]))?$row["shop_id"]:0, 0);
		// навигация нужно сделать исключение для главной страницы
		//$data["nav"] = "<a href='".$this->siteUrl."'>Главная</a> / ".GetNav($menu, $this->cid);

		$data["address"] = $this->db->GetSetting ("ContactAddress");
		$data["phone"] = $this->db->GetSetting ("ContactPhone");
		$data["email"] = $this->db->GetSetting ("ContactEmail");		
		$data["slogan"] = $this->db->GetSetting ("Slogan");
		$data["copy"] = $this->db->GetSetting ("Copyright");
		$data["sitetitle"] = $this->db->GetSetting ("SiteTitle");
		
		$data["cart_total_count"]=0;
		$data["cart_cost"] = 0;
		if (isset($_SESSION['cart_order']))
		{			
			foreach($_SESSION['cart_order'] as $k => $v){
				$data["cart_total_count"] += $v["count"]; 
				$data["cart_cost"] += $v["price"]*$v["count"];
			}
		}
		if ($data["cart_total_count"] > 0)
		{
			$data["cart_total_text"] = pluralForm($data["cart_total_count"]);
		}
		else
		{
			$data["cart_total_count"] = "Корзина пуста";
			$data["cart_total_text"] = "";
		}
		if ($data["cart_cost"] > 0)
		{
			$data["cart_cost"] = "на сумму ".number_format($data['cart_cost'],0,' ',' ').' <span class="rur">₽</span>';
		}
		else
			$data["cart_cost"] = "";
		/*$result = $this->db->ExecuteSql ("Select * From `category` Where is_active='1' and module='products' ORDER BY order_index");
		if ($result)
		{			
			while ($row = $this->db->FetchArray($result)) 
			{   
				$name = dec($row['title']);
				$url = dec($row['url']);
				$link = $this->siteUrl.CATALOG_LINK."/category/".$url;
				$data['cat_link_product'][] = array (
						"title" => $name,
						"link" => $link,
				 );              
			}
			$this->db->FreeResult($result);
			 foreach ($this->menuarrtree as $row)
			{		
				$link = $this->siteUrl.CATALOG_LINK."/".$row['url'];			
				$data['cat_link_product'][] = array (
						"title" => $row['title'],
						"link" => $link,
				 );
			}
		}
		$result = $this->db->ExecuteSql ("Select * From `category` Where is_active='1' and module='articles' ORDER BY order_index");
		if ($result)
		{
			while ($row = $this->db->FetchArray($result)) 
			{   
				$name = dec($row['title']);
				$url = dec($row['url']);
				$link = $this->siteUrl.ARTICLES_LINK."/category/".$url;
				$data['cat_link_article'][] = array (
						"title" => $name,
						"link" => $link,
				   );              
			}
			$this->db->FreeResult($result);
		}*/
		//----рекомендуем-------------------
		// подзапрос для получания количества комментариев для каждой записи
		$countcommet = "(Select count(*) From `comments` Where is_active='1' and module='products' and comments.parent_id = products.product_id) as totalcomments, ";
		$result = $this->db->ExecuteSql("Select ".$countcommet."title, filename, parent_id, views, url From `products` Where is_active='1' and recomend='1' ORDER BY RAND () Limit 2");
		if ($result)
		{	
			while ($row = $this->db->FetchArray($result))  
			{
				$reciperecomendname = $row['title'];
				if ($row['filename'] != "") {          
					$extension = substr($row['filename'], -3);
					$reciperecomendimg = $this->siteUrl."media/products/".substr($row['filename'], 0, -4)."_small.".$extension;
				}
				else {
					$reciperecomendimg = $this->siteUrl."img/noimg.jpg";
				}			
				$fullurl = GetLinkCat($this->menuarrtree, $row["parent_id"]);		
				$reciperecomendlink = $this->siteUrl.CATALOG_LINK."/".$fullurl.$row['url'];
				$data['recomend'][] = array (
					"title" => $reciperecomendname,
					"filename" => $reciperecomendimg,				
					"link" => $reciperecomendlink,
					"views" => $row['views'],
					"comments" => $row['totalcomments'],
				);		
			}
			$this->db->FreeResult($result);
		}
		//-----------------------   
		/*Комментарии*/
		$result = $this->db->ExecuteSql ("Select * From `comments` Where is_active='1' and new='0' Order By news_date desc LIMIT 2", false);
		if ($result) {			
			while ($row = $this->db->FetchArray ($result))  
			{
				$comment = dec($row['comment']);
				$comment1 = mb_substr($comment, 0, 130);
				if ($comment != $comment1) 
					$comment = mb_substr($comment, 0, 129)."...";    
				$date_added = date("d-m-Y", $row['news_date']);
				$name = dec($row["name"]);	
				$module = $row["module"];
				$parent_id = $row["parent_id"];
				switch ($module)
				{
					case "products":
						$sql = "SELECT parent_id, url FROM products WHERE product_id = '$parent_id'";
						$row1 = $this->db->GetEntry($sql);
						$fullurl = GetLinkCat($this->menuarrtree, $row1["parent_id"]);
						$link = "/".CATALOG_LINK."/".$fullurl.$row1['url'];
					break;
					case "articles":
						$sql = "SELECT url FROM articles WHERE article_id = '$parent_id'";
						$url = $this->db->GetOne($sql);
						$link = "/".ARTICLES_LINK."/".$url;
					break;
				}
				
				$data['comments'][] = array (
					"comment" => $comment,
					"date" => $date_added,
					"name" => $name,
					"link" => $link,
				);
			}
			$this->db->FreeResult ($result);
		}		
		return $data;
	}
	
	public function GetStatusName ($field)
	{
		switch ($field)
		{
			case "1": 
				$status="Отгружен";
			break;
			case "2": 
				$status="Выполнен";
			break;
			case "3": 
				$status="Отменен";
			break;
			case "4": 
				$status="Возврат";
			break;
			default:
				$status="Новый";
		}
		return $status;
	}
	
	public function get_bill_pdg ($order_id, $file = false)
	{		
		if (is_numeric($order_id) && $order_id > 0)
		{
			$sql = "SELECT order_id, news_date FROM orders WHERE order_id = '".$order_id."' and user_id = '".$this->GetCookie("id", -1)."'";
			$row = $this->db->GetEntry($sql);
			if (isset($row["order_id"]))
			{
				$user = $this->db->GetEntry("SELECT * FROM users WHERE user_id = '".$this->GetCookie("id", -1)."'");
				$date_bill = str_date($row["news_date"]);
				$sql = "SELECT shop_id, name, price, quantity FROM order_product WHERE order_id = '".$order_id."'";
				$result=$this->db->ExecuteSql($sql);		
				if ($result)
				{
					$prods_count = $total = 0;
					$product = "";
					$i = 0;
					while ($row = $this->db->FetchArray ($result))	
					{										 						
						$total +=  $this->dec($row['price'])*$this->dec($row['quantity']);
						$prods_count += $this->dec($row['quantity']);
						$product .= '
							<tr>
								<td align="center">' . (++$i) . '</td>
								<td align="left">' . $this->dec($row['name']) . '</td>
								<td align="right">' . $row['quantity'] . '</td>
								<td align="left">шт.</td>
								<td align="right">' .number_format($row['price'], 2, ',', ' ') . '</td>
								<td align="right">' .number_format($row['price'] * $row['quantity'], 2, ',', ' ') . '</td>
							</tr>';
					}
				}
				$this->db->FreeResult ($result);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		require_once(dirname(dirname(__FILE__)) .'/plagins/dompdf/autoload.inc.php');
		
		$html = '
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			
			<style type="text/css">
				* { 
					font-family: arial;
					font-size: 14px;
					line-height: 14px;
				}
				body {width: 700px; margin: 0 auto;}
				table {
					margin: 0 0 15px 0;
					width: 100%;
					border-collapse: collapse; 
					border-spacing: 0;
				}        
				table td {
					padding: 5px;
				}    
				table th {
					padding: 5px;
					font-weight: bold;
				}

				.header {
					margin: 0 0 0 0;
					padding: 0 0 15px 0;
					font-size: 12px;
					line-height: 12px;
					text-align: center;
				}
				
				/* Реквизиты банка */
				.details td {
					padding: 3px 2px;
					border: 1px solid #000000;
					font-size: 12px;
					line-height: 12px;
					vertical-align: top;
				}
				sup {font-size: 10px;}
				h1 {
					margin: 0 0 10px 0;
					padding: 10px 0 10px 0;
					border-bottom: 2px solid #000;
					font-weight: bold;
					font-size: 20px;
				}

				/* Поставщик/Покупатель */
				.contract th {
					padding: 3px 0;
					vertical-align: top;
					text-align: left;
					font-size: 13px;
					line-height: 15px;
				}    
				.contract td {
					padding: 3px 0;
				}        

				/* Наименование товара, работ, услуг */
				.list thead, .list tbody  {
					border: 2px solid #000;
				}
				.list thead th {
					padding: 4px 0;
					border: 1px solid #000;
					vertical-align: middle;
					text-align: center;
				}    
				.list tbody td {
					padding: 0 2px;
					border: 1px solid #000;
					vertical-align: middle;
					font-size: 11px;
					line-height: 13px;
				}    
				.list tfoot th {
					padding: 3px 2px;
					border: none;
					text-align: right;
				}    

				/* Сумма */
				.total {
					margin: 0 0 20px 0;
					padding: 0 0 10px 0;
					border-bottom: 2px solid #000;
				}    
				.total p {
					margin: 0;
					padding: 0;
				}
				
				/* Руководитель, бухгалтер */
				.sign {
					position: relative;
				}
				.sign table {
					width: 60%;
				}
				.sign th {
					padding: 40px 0 0 0;
					text-align: left;
				}
				.sign td {
					padding: 40px 0 0 0;
					border-bottom: 1px solid #000;
					text-align: right;
					font-size: 12px;
				}
				
				.sign-1 {
					position: absolute;
					left: 120px;
					top: 12px;
				}    
				.sign-2 {
					position: absolute;
					left: 120px;
					top: 68px;
				}    
				.printing {
					position: absolute;
					left: 200px;
					top: -5px;
				}
			</style>
		</head>
		<body>
			<p class="header">
				Внимание! Оплата данного счета означает согласие с условиями поставки товара.
				Уведомление об оплате обязательно, в противном случае не гарантируется наличие
				товара на складе. Товар отпускается по факту прихода денег на р/с Поставщика,
				самовывозом, при наличии доверенности и паспорта.
			</p>

			<table class="details">
				<tbody>
					<tr>
						<td colspan="2" style="border-bottom: none;">'.$this->db->GetSetting ("Ybank").'</td>
						<td>БИК</td>
						<td style="border-bottom: none;">'.$this->db->GetSetting ("Ybik").'</td>
					</tr>
					<tr>
						<td colspan="2" style="border-top: none; font-size: 10px;">Банк получателя</td>
						<td>Сч. №</td>
						<td style="border-top: none;">'.$this->db->GetSetting ("Yks").'</td>
					</tr>
					<tr>
						<td width="25%">ИНН '.$this->db->GetSetting ("Yinn").'</td>
						<td width="30%">КПП '.$this->db->GetSetting ("Ykpp").'</td>
						<td width="10%" rowspan="3">Сч. №</td>
						<td width="35%" rowspan="3">'.$this->db->GetSetting ("Yrs").'</td>
					</tr>
					<tr>
						<td colspan="2" style="border-bottom: none;">'.$this->db->GetSetting ("Ycompany").'</td>
					</tr>
					<tr>
						<td colspan="2" style="border-top: none; font-size: 10px;">Получатель</td>
					</tr>
				</tbody>
			</table>

			<h1>Счет на оплату № '.$order_id.' от '.$date_bill.'</h1>

			<table class="contract">
				<tbody>
					<tr>
						<td width="15%">Поставщик:</td>
						<th width="85%">
							'.$this->db->GetSetting ("Ycompany").', ИНН '.$this->db->GetSetting ("Yinn").', КПП '.$this->db->GetSetting ("Ykpp").', '.$this->db->GetSetting ("Yaddress").'
						</th>
					</tr>
					<tr>
						<td>Покупатель:</td>
						<th>
							'.$this->dec($user["company"]).', ИНН '.$user["inn"].', КПП '.$user["kpp"].', '.$user["yaddress"].'
						</th>
					</tr>
				</tbody>
			</table>

			<table class="list">
				<thead>
					<tr>
						<th width="5%">№</th>
						<th width="54%">Наименование товара, работ, услуг</th>
						<th width="8%">Коли-<br>чество</th>
						<th width="5%">Ед.<br>изм.</th>
						<th width="14%">Цена</th>
						<th width="14%">Сумма</th>
					</tr>
				</thead>
				<tbody>'.$product.'
				</tbody>
			   <tfoot>
					<tr>
						<th colspan="5">Итого:</th>
						<th>' . number_format($total, 2, ',', ' ') . '</th>
					</tr>
					<tr>
						<th colspan="5">В том числе НДС:</th>
						<th>' . number_format($total - ($total / $this->nds), 2, ',', ' ') . '</th>
					</tr>
					<tr>
						<th colspan="5">Всего к оплате:</th>
						<th>' . number_format($total, 2, ',', ' ') . '</th>
					</tr>  
				</tfoot>
			</table>
			
			<div class="total">
				<p>Всего наименований ' . $prods_count . ', на сумму ' . number_format($total, 2, ',', ' ') . ' руб.</p>
				<p><strong>' . str_price($total) . '</strong></p>
			</div>
			
			<div class="sign">
				<img class="sign-1" src="'.$_SERVER['DOCUMENT_ROOT'].'/img/sign-1.png">
				<img class="sign-2" src="'.$_SERVER['DOCUMENT_ROOT'].'/img/sign-2.png">
				<img class="printing" src="'.$_SERVER['DOCUMENT_ROOT'].'/img/printing.png">
				<table>
					<tbody>
						<tr>
							<th width="30%">Руководитель</th>
							<td width="70%">'.$this->db->GetSetting ("Ydirector").'</td>
						</tr>
						<tr>
							<th>Бухгалтер</th>
							<td>'.$this->db->GetSetting ("Yaccountant").'</td>
						</tr>
					</tbody>
				</table>
			</div>
		</body>
		</html>';
		/*echo $html;
		exit;*/
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		 
		if ($file)
		{
			// сохранение на сервере:
			$pdf = $dompdf->output();	
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/temp/bill-'.$order_id.'.pdf', $pdf);
		}
		else
		{
			// Вывод файла в браузер:
			$dompdf->stream('bill-'.$order_id); 
		}
		return $_SERVER['DOCUMENT_ROOT'] . '/temp/bill-'.$order_id.'.pdf';

	}
}