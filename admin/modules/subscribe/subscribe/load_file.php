<?
@session_start();
if (@!$_SESSION['user_id']) {
    exit("Go away :-)");
}

Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
Header("Content-type: text/html; charset=utf-8");
?>
<html>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" bgcolor="#f9f8e8">
<?
if (@!$_FILES['f_file']['name']) {
    echo "<font color=\"#CC0000\" style=\"font:12px Tahoma;\">Файл не задан.</font>";
} else {
    if (!preg_match("/^.+?\.([^.]+?)$/", $_FILES['f_file']['name'], $ok)) {
        $type = "";
    } else {
        $type = $ok[1];
    }

    do {
        $new_name = "file_" . rand(0, 9) . "_" . time() . ($type ? "." . $type : "");
    } while (file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/subscribe/" . $new_name));
    if (move_uploaded_file($_FILES['f_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/subscribe/" . $new_name)) {
        echo "<font color=\"#009900\" style=\"font:12px Tahoma;\">Файл загружен!</font>";
        ?>
         <script type="text/javascript">
     top.uploaded_files.push({file: '<?=$new_name?>', size: <?=$_FILES['f_file']['size']?>});
     top.replainFiles();
         </script>
         <?
    } else {
        echo "<font color=\"#CC0000\" style=\"font:12px Tahoma;\">Ошибка загрузки файла! Видимо нет прав на папку /upload/subscribe/</font>";
    }

}
?>
</body>
</html>