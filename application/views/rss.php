<?if(!defined("CMS_BASTION") || CMS_BASTION!==true) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	die();
}
// в случае если кеширование отключено или не возможно создать файл выводим из массива
if (!$head['cache'])
{
	$delim = "\r\n";
	print("<?xml version=\"1.0\" encoding=\"utf-8\"?>".$delim);
	print("<rss xmlns:dc=\"http://purl.org/dc/elements/1.1/\" version=\"2.0\">".$delim);
	print("<channel>".$delim);
	print("  <title>".$head['title']."</title>".$delim);
	print("  <link>".$head['link']."</link>".$delim);
	print("  <description>".$head['description']."</description>".$delim);
	print("  <language>ru</language>".$delim);
	foreach($head['row'] as $row)
	{
		print("  <item>".$delim);
		print("    <title>".$row['title']."</title>".$delim);
		print("    <link>".$row['link']."</link>".$delim);
		print("    <description>".$row['description']."</description>".$delim);
		print("    <pubDate>".$row['date']."</pubDate>".$delim);
		print("  </item>".$delim);
	}
	print("</channel>".$delim);
	print("</rss>");
}
else
{
	$handle = fopen($head['cachefile'], "rb");
	$contents = fread($handle, filesize($head['cachefile']));
	fclose($handle);
	echo $contents;
}
?>