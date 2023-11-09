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
if ($_GET['file']) {
    $file = preg_replace("/[^\w\d\._-]/", "", $_GET['file']);
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/subscribe/" . $file)) {
        //удаление файла
        if (unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/subscribe/" . $file)) {
            //удаление прошло успешно
            ?>
    <script type="text/javascript">
  //alert("Файл успешно удален!");
  top.uploaded_files.splice(<?=$_GET['f_id']?>,1);
  top.replainFiles();
    </script>
    <?
        } else {
            //ошибка удаления файла
            ?>
    <script type="text/javascript">
  alert("Ошибка удаления файла, возможно он защищен от записи и удаления!");
    </script>
    <?
        }
    } else {
        //файла не существует
        ?>
    <script type="text/javascript">
  alert("Файла не существует!");
    </script>
    <?
    }
} else {
    //не задан файл для удаления
    ?>
    <script type="text/javascript">
  alert("Не задан файл для удаления!");
    </script>
    <?
}
?>
</body>
</html>