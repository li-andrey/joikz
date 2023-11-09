<? 
if (strstr($_SERVER["REQUEST_URI"], '/sm/')){
    require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/authorisation/index.php");
}else{
    header("Location: /");
}
?>