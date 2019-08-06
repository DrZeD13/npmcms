<?
if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}

// в случае если кеширование отключено или не возможно создать файл выводим из массива
if (!$head['cache'])
{
	$delim = "\r\n";
	print("<?xml version=\"1.0\" encoding=\"utf-8\"?>".$delim);
	print("<?xml-stylesheet type=\"text/xsl\" href=\"/js/adm/sitemap.xsl\"?>".$delim);
	print("<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".$delim);
	foreach($head['row'] as $row)
	{
		print("  <url>".$delim);
		print("    <loc>".$row['link']."</loc>".$delim);
		print("    <lastmod>".$row['date']."</lastmod>".$delim);
		print("    <changefreq>".$row['changefreq']."</changefreq>".$delim);
		print("    <priority>".$row['priority']."</priority>".$delim);
		print("  </url>".$delim);
	}
	print("</urlset>");
}
else
{
	$handle = fopen($head['cachefile'], "rb");
	$contents = fread($handle, filesize($head['cachefile']));
	fclose($handle);
	echo $contents;
}
?>