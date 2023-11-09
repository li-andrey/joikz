<? 
$uri = preg_replace("/\?.*/i",'', $_SERVER['REQUEST_URI']);
if (strlen($uri)>1) {
  if (rtrim($uri,'/')."/"!=$uri) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://'.$_SERVER['SERVER_NAME'].str_replace($uri, $uri.'/', $_SERVER['REQUEST_URI']));
    exit();    
}
}
include_once(dirName(__FILE__)."/admin/modules/general/mysql.php");
// ajax
if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && $_SERVER["REQUEST_URI"]!='/handle-payment/'){
    include_once('ajax.php');
}else{
    if (A::$app->redirect->config["active"]==1){
        A::$app->redirect->init();
    }
    if (A::$app->settings->v(1)==1){
        die("Сайт находится в режиме обслуживания");
    }else{
        A::$app->show(); 
    }
}