<?
// дерево каталога в option
function getTree ($array, $parent_id = 0, $current_id = 0, $lvl = "")
{	
	$res = "";
	foreach ($array as $row)
	{						
		$selected = ($row["menu_id"] == $parent_id)?"selected":"";
		if ($current_id != $row["menu_id"])
		{
			$res .= "	<option value='".$row["menu_id"]."' ".$selected.">".$lvl.$row['title']."</option>\r\n";
		}
		if (isset($row['childs']) && $current_id != $row["menu_id"]) // наличие детей, а если это активные элемент, то он не может стать своим наследником
		{						
			$res.=getTree ($row['childs'], $parent_id, $current_id, $lvl."-- ");			
		}
	}
	return $res;		
}
	
// дерево меню (параметры: массив, активный элемент, имя select)
// module - для публикации модуля
// если еть дети то не публиковать не выводить
function getMenusSelect ($array, $parent_id, $current_id, $names = "menu_id", $module = false, $child = false)
{
	$toRet = "<select name='$names' id='$names' class='chosen-select'>\r\n";
	if (($module) && (!$child))
	{
		$selected = (-1 == $parent_id) ? "selected" : "";
		$toRet .= "	<option value='-1' $selected>Не публиковать</option>\r\n";	
	}
	$selected = ($current_id != 0) ? "selected" : "";
	$toRet .= "	<option value='0' $selected>1-й уровень</option>\r\n";	
	$toRet .= getTree ($array, $parent_id, $current_id, "");
	
	return $toRet."</select>\r\n";
}

// дерево каталога в option с сылками перехода
function getTreeLink ($siteUrl, $array, $parent_id = 0, $current_id = 0, $lvl = "")
{	
	$res = "";
	foreach ($array as $row)
	{						
		$selected = ($row["menu_id"] == $parent_id)?"selected":"";
		if ($current_id != $row["menu_id"])
		{
			$res .= "	<option value='$siteUrl?parent_id=".$row["menu_id"]."' ".$selected.">".$lvl.$row['title']."</option>\r\n";
		}
		if (isset($row['childs'])) // наличие детей
		{						
			$res.=getTreeLink ($siteUrl, $row['childs'], $parent_id, $current_id, $lvl."-- -- ");			
		}
	}
	return $res;		
}

// дерево меню с сылками (параметры: массив, активный элемент, имя select)
function getMenusSelectLink ($siteUrl, $array, $parent_id, $current_id, $names = "menu_id")
{
	$toRet = "<select name='$names' id='$names' onchange=\"top.location=this.value\" class='select-search'>\r\n";
	$selected = (0 == $current_id) ? "selected" : "";
	$toRet .= "	<option value='$siteUrl?parent_id=0' $selected>Нет</option>\r\n";
	$toRet .= getTreeLink ($siteUrl, $array, $parent_id, $current_id, "");
	
	return $toRet."</select>\r\n";
}

// строит навигацию $array - массив из элеметов меню, $cid - текущий элемент меню $last - не делать последний элемент ссылкой, $delimeter - разделитель навигации $adm - если навигция для админки
function GetNavUl($array, $cid, $last = true)
{
	$breadcrumbs = array();
	$bread = "";
	while ($cid > 0)
	{		
		$breadcrumbs[$cid] = $array[$cid];
		$cid = $array[$cid]['parent_id'];
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	foreach ($breadcrumbs as $row)
	{
		$link = "/".$row['url'];
		$bread.="<li><a href='".$link."'>".$row['title']."</a></li>";
	}
	if ($last)
	{
		$bread = preg_replace("#(.+)?<a.+>(.+)</a>?(.+)$#", "$1$2", $bread);
	}
	return $bread;
}

// строит навигацию $array - массив из элеметов меню, $cid - текущий элемент меню $last - не делать последний элемент ссылкой, $delimeter - разделитель навигации $adm - если навигция для админки
function GetNav($array, $cid, $last = true, $delimiter = "/", $adm = false)
{
	$breadcrumbs = array();
	$bread = "";
	while ($cid > 0)
	{		
		if (isset($array[$cid]))
		{
			$breadcrumbs[$cid] = $array[$cid];
			$cid = $array[$cid]['parent_id'];
		}
		else
		{
			return false;
		}
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	foreach ($breadcrumbs as $row)
	{
		$link = ($adm)?"?parent_id=".$row['menu_id']:"/".$row['url'];
		$bread.="<a href='".$link."'>".$row['title']."</a> $delimiter ";
	}
	if ($last)
	{
		$bread = rtrim($bread, " $delimiter ");
		$bread = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $bread);
	}
	return $bread;
}

// строит навигацию для каталога $array - массив из элеметов меню, $cid - текущий элемент меню $last - не делать последний элемент ссылкой, $delimeter - разделитель навигации
function GetNavCatUl($array, $cid, $last = true, $module_link = CATALOG_LINK)
{
	$breadcrumbs = array();
	$bread = "";
	$i= 1;
	while ($cid > 0)
	{		
		$breadcrumbs[$cid] = $array[$cid];
		$cid = $array[$cid]['parent_id'];
		$i++;
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	$j = 1;
	foreach ($breadcrumbs as $row)
	{
		$link = "/".$module_link."/".GetLinkCat($array, $row["menu_id"]);	
		$j++;
		if (($last) && ($j == $i))
			$bread.="<li>".$row['title']."</li>";
		else
			$bread.="<li><a href='".$link."'>".$row['title']."</a></li>";
	}
	return $bread;
}

// строит навигацию для каталога $array - массив из элеметов меню, $cid - текущий элемент меню $last - не делать последний элемент ссылкой, $delimeter - разделитель навигации
function GetNavCat($array, $cid, $last = true, $module_link = CATALOG_LINK, $delimiter = "/")
{
	$breadcrumbs = array();
	$bread = "";
	while ($cid > 0)
	{		
		$breadcrumbs[$cid] = $array[$cid];
		$cid = $array[$cid]['parent_id'];
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	foreach ($breadcrumbs as $row)
	{
		$link = "/".$module_link."/".GetLinkCat($array, $row["menu_id"]);		
		$bread.="<a href='".$link."'>".$row['title']."</a> $delimiter ";
	}
	if ($last)
	{
		$bread = rtrim($bread, " $delimiter ");
		$bread = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $bread);
	}
	return $bread;
}

// получает полную ссылку для каталога $cid - от чего строим ссылку
function GetLinkCat($array, $cid)
{
	$breadcrumbs = array();
	$link = "";
	while ($cid > 0)
	{		
		$breadcrumbs[$cid] = $array[$cid];
		$cid = $array[$cid]['parent_id'];
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	foreach ($breadcrumbs as $row)
	{
		$link.=$row['url'];		
	}
	return $link;
}
// Построение списка меню $lvl - уровень вложенности
function GetUlMenu ($siteUrl, $array, $cid, $lvl = 0)
{		
	$res="";
	foreach ($array as $row)
	{
		$class=($cid == $row['menu_id'])?"class='active'":"";		
		if (substr_count($row['url'], "http") > 0)
		{
			$link = $row['url'];
		}
		else
		{
			$link = $siteUrl.$row['url'];
		}
		if (isset($row['childs']) && ($lvl > 0))
		{	
			$res.= "	<li class='dropsub'><a href='".$link."' $class ".$row['target'].">".$row['title']."</a>";		
				
			$res .= "<ul class='drop-down'>\r\n";
			$lvl1 = $lvl - 1;
			$res.=GetUlMenu ($siteUrl, $row['childs'], $cid, $lvl1);
			$res.= "</ul>\r\n";
		}
		else
		{
			$res.= "	<li><a href='".$link."' $class ".$row['target'].">".$row['title']."</a>";		
		}
		$res.="</li>\r\n";
	}	
	return $res;
}

// Построение списка каталога $lvl - уровень вложенности
function GetUlCatalog ($siteUrl, $array, $cid, $lvl = 0)
{		
	$res="";
	foreach ($array as $row)
	{
		$class=($cid == $row['menu_id'])?"class='active'":"";		
		$link = $siteUrl.$row['url'];
		if (isset($row['childs']) && ($lvl > 0))
		{	
			$res.= "	<li class='dropsub'><a href='".$link."' $class>".$row['title']."</a>";		
				
			$res .= "<ul class='drop-down'>\r\n";
			$lvl1 = $lvl - 1;			
			$res.=GetUlCatalog ($link, $row['childs'], $cid, $lvl1);
			$res.= "</ul>\r\n";
		}
		else
		{
			$res.= "	<li><a href='".$link."' $class>".$row['title']."</a>";		
		}
		$res.="</li>\r\n";
	}	
	return $res;
}

// Построение дерево меню из массива
function GetTreeFromArray ($array)
{
	$tree = array ();
	foreach ($array as $id=>&$node)
	{
		if (!$node['parent_id'])
		{
			$tree[$id] = &$node;
		}
		else
		{
			$array[$node['parent_id']]['childs'][$id] = &$node;
		}
	}	
	return $tree;
}

// получает все дочерние id 
function get_childs_id ($array, $id)
{
	$res = $id.",";
	foreach ($array as $row)
	{
		if ($row['parent_id'] == $id)
		{			
			$res .= get_childs_id ($array, $row['menu_id']);
		}
	}
	return $res;
	// SELECT * FROM product WHERE parent_id IN ($res)
}

//--Добавляет водяной знак на картинку
function watermark ($image, $watermark) {

	if (list ($width, $height, $type, $attr) = getimagesize($image))
    {
		$image_create = "";
              switch ($type)
              {
                  case 1:     // GIF
                      $image_create = "imagecreatefromgif";
                      $image_save = "imagegif";
                      break;
                  case 2:     // JPEG
                      $image_create = "imagecreatefromjpeg";
                      $image_save = "imagejpeg";
                      break;
                  case 3:     // PNG
                      $image_create = "imagecreatefrompng";
                      $image_save = "imagepng";
                      break;
              }
      
            if ($image_create != "") {
				$water_img = imagecreatefrompng($watermark);
				$img = $image_create($image);
				imagecopy ($img, $water_img, 0, 0, 0, 0, imagesx($water_img), imagesy($water_img));
      
                switch ($type) {
					case 1:     // GIF
						$image_save ($img, $image);
                        break;
                    case 2:     // JPEG
                        $image_save ($img, $image, 95);
                        break;
                    case 3:     // PNG
						$image_save ($img, $image);
                        break;
                 }
				imagedestroy ($water_img);
                imagedestroy ($img);
				
				return true;
            }
    }

	return false;
	
}

// преобразование ЧПУ транлитерация
function TransUrl($str)
{
	$tr = array(
		"А"=>"a",
		"Б"=>"b",
		"В"=>"v",
		"Г"=>"g",
		"Д"=>"d",
		"Е"=>"e",
		"Ё"=>"e",
		"Ж"=>"zh",
		"З"=>"z",
		"И"=>"i",
		"Й"=>"y",
		"К"=>"k",
		"Л"=>"l",
		"М"=>"m",
		"Н"=>"n",
		"О"=>"o",
		"П"=>"p",
		"Р"=>"r",
		"С"=>"s",
		"Т"=>"t",
		"У"=>"u",
		"Ф"=>"f",
		"Х"=>"h",
		"Ц"=>"ts",
		"Ч"=>"ch",
		"Ш"=>"sh",
		"Щ"=>"sch",
		"Ъ"=>"",
		"Ы"=>"i",
		"Ь"=>"",
		"Э"=>"e",
		"Ю"=>"yu",
		"Я"=>"ya",
		"а"=>"a",
		"б"=>"b",
		"в"=>"v",
		"г"=>"g",
		"д"=>"d",
		"е"=>"e",
		"ё"=>"e",
		"ж"=>"zh",
		"з"=>"z",
		"и"=>"i",
		"й"=>"y",
		"к"=>"k",
		"л"=>"l",
		"м"=>"m",
		"н"=>"n",
		"о"=>"o",
		"п"=>"p",
		"р"=>"r",
		"с"=>"s",
		"т"=>"t",
		"у"=>"u",
		"ф"=>"f",
		"х"=>"h",
		"ц"=>"ts",
		"ч"=>"ch",
		"ш"=>"sh",
		"щ"=>"sch",
		"ъ"=>"y",
		"ы"=>"i",
		"ь"=>"j",
		"э"=>"e",
		"ю"=>"yu",
		"я"=>"ya",
		"A"=>"a",
		"B"=>"b",
		"C"=>"c",
		"D"=>"d",
		"E"=>"e",
		"F"=>"f",
		"G"=>"g",
		"H"=>"h",
		"I"=>"i",
		"J"=>"j",
		"K"=>"k",
		"L"=>"l",
		"M"=>"m",
		"N"=>"n",
		"O"=>"o",
		"P"=>"p",
		"Q"=>"q",
		"R"=>"r",
		"S"=>"s",
		"T"=>"t",
		"U"=>"u",
		"V"=>"v",
		"W"=>"w",
		"X"=>"x",
		"Y"=>"y",
		"Z"=>"z",
		" "=> "_",
		"."=> "",
		","=>"",
		"-"=>"-",
		"("=>"",
		")"=>"",
		"["=>"",
		"]"=>"",
		"="=>"_",
		"+"=>"_",
		"*"=>"",
		"?"=>"",
		"/"=>"",
		"\""=>"",
		"'"=>"",
		"&"=>"",
		"%"=>"",
		"#"=>"",
		"@"=>"",
		"!"=>"",
		";"=>"",
		"№"=>"",
		"^"=>"",
		":"=>"",
		"~"=>"",
		"\\"=>"",
		"«" =>"",
		"»" =>"",
		"“" =>"",
		"”" =>"",
		"–" =>"",
		"…" =>"",
	);
	return strtr($str,$tr);
}

function validUrl($str, $title)
{
	$prefix = ".html";
	if ($str == "") 
	{			
		$str = TransUrl($title);
	}
	else
	{
		$url = explode('.', $str);	
		$str = TransUrl($url[0]);			
	}
	if (preg_match ("/^[a-z0-9-_]+$/", $str))
	{
		return $str.$prefix;
	}
	else
	{
		return false;
	}
}

//формирует из массива строку для SQL запроса UPDATE, только параметры
function ArrayInUpdateSQL ($array) 
{
	$res = "";
	foreach ($array as $key => $val) {
		$res .= $key."='".$val."',";
	}
	return $res = rtrim($res, ",");
}

//формирует из массива строку для SQL запроса INSERT, только параметры
function ArrayInInsertSQL ($array) 
{
	$res = "";
	$res1 = "";
	foreach ($array as $key => $val) {
		$res .= $key.",";
		if ($val === NULL)
		{
			$res1 .= "NULL,";
		}
		else
		{
			$res1 .= "'".$val."',";
		}
	}
	$res = "(".rtrim($res, ",").")";
	$res1 = "(".rtrim($res1, ",").")";
	return $res." VALUES ".$res1;
}

// преобразовывает теги в текст (переделаная обезопасыает данные для БД)
function enc ($value)
{
	/*$search = array ("/&/", "/</", "/>/", "/'/");
	$replace = array ("&amp;", "&lt;", "&gt;", "&#039;");
	return preg_replace ($search, $replace, $value);*/
	return mysql_real_escape_string($value);
}

// преоброзовывает текст в теги
function dec ($value) 
{
	$search = array ("/&amp;/", "/&lt;/", "/&gt;/", "/&#039;/");
	$replace = array ("&", "<", ">", "'");
	$value = preg_replace ($search, $replace, $value);
	return stripcslashes($value);
}

// Возвращает из числового символьное обозначение пола
function GetSex($sex)
{
	switch($sex)
	{
		case '1':
			return "Женский";
		break;
		case '2':
			return "Мужской";
		break;
		default: 
			return "Не определен";
	}
}

// возвращает на именование в зависимости от числа например 1 "товар", 2 "товара", 5 "товаров"
// n - текущее число 
/*function pluralForm($n, $form1 = "товар", $form2 = "товара", $form5 = "товаров") 
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $form5;
	if ($n1 > 1 && $n1 < 5) return $form2;
	if ($n1 == 1) return $form1;
	return $form5;
}*/

function pluralForm($n, $titles = array("товар", "товара", "товаров")) {
  $cases = array(2, 0, 1, 1, 1, 2);
  return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

// Сумма прописью.
function str_price($value)
{
    $value = explode('.', number_format($value, 2, '.', ''));

    $f = new NumberFormatter('ru', NumberFormatter::SPELLOUT);
    $str = $f->format($value[0]);

    // Первую букву в верхний регистр.
    $str = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1, mb_strlen($str));

    // Склонение слова "рубль".
    /*$num = $value[0] % 100;
    if ($num > 19) { 
        $num = $num % 10; 
    }    
    switch ($num) {
        case 1: $rub = 'рубль'; break;
        case 2: 
        case 3: 
        case 4: $rub = 'рубля'; break;
        default: $rub = 'рублей';
    } */   
	
	$rub = pluralForm($value[0], array("рубль", "рубля", "рублей"));
    
    return $str . ' ' . $rub . ' ' . $value[1] . ' копеек.';
}

// дата прописью
function str_date($date)
{
	$months = array (1=>"января", 2=>"февраля", 3=>"марта", 4=>"апреля", 5=>"мая", 6=>"июня", 7=>"июля", 8=>"августа", 9=>"сентября", 10=>"октября", 11=>"ноября", 12=>"декабря"); 
	$day = date("d", $date);
	$month = $months[(int)date("m", $date)];
	$year = date("Y", $date);
	return $day." ".$month." ".$year."г";
}

// распечатка масива с форматированнием
function print_arr ($array)
{
	echo "<pre>".print_r($array, true)."</pre>";
}
// распечатка масива с типами данных и форматированнием
function dump ($array)
{
	echo '<pre>'.var_dump($array).'</pre>';
}
?>