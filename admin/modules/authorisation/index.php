<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/general/mysql.php");
$ob->pr_cookie();
if ($ob->check_admin()){
	$ob->go("/admin/modules/desktop.php");
}
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')){
  include('ajax.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Site Title-->
    <title>Admin CMS</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <!-- Stylesheets-->
    <link rel="stylesheet" type="text/css" href="/admin/modules/authorisation/css/admin-forms.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open Sans:300,400,600,700">
    <link rel="stylesheet" type="text/css" href="/admin/modules/authorisation/css/theme.css">
  </head>
  <body class="external-page sb-l-c sb-r-c">
    <!-- Start: Main-->
    <div id="main" class="animated fadeIn">
      <!-- Start: Content-Wrapper-->
      <section id="content_wrapper">
        <!-- begin canvas animation bg-->
        <div id="canvas-wrapper">
          <canvas id="demo-canvas"></canvas>
        </div>
        <!-- Begin: Content-->
        <section id="content">
          <div id="login1" class="admin-form theme-info">
            
            <div class="panel panel-info mt10 br-n">
              
              <form method="post" action="" id="login-form" data-toggle="validator">
                <div class="panel-body bg-light p30">
                  <div class="row">
                    <div class="col-sm-12 pr30">
                      <div class="login-result" id="login-top"></div>
                      <div class="section">
                        <label for="username" class="field-label text-muted fs18 mb10">Логин</label>
                        <label for="username" class="field form-group prepend-icon">
                          <input id="username" type="text" name="username" placeholder="Введите ваш логин" class="gui-input form-control" required="">
                          <label for="username" class="field-icon"><i class="fa fa-user"></i></label>
                        </label>
                      </div>
                      <div class="section">
                        <label for="username" class="field-label text-muted fs18 mb10">Пароль</label>
                        <label for="password" class="field form-group prepend-icon">
                          <input id="password" type="password" name="password" placeholder="Введите ваш пароль" class="gui-input form-control" required="">
                          <label for="password" class="field-icon"><i class="fa fa-lock"></i></label>
                        </label>
                      </div>
                      
                    </div>
                    
                  </div>
                </div>

                <div class="panel-footer clearfix p10 ph15">
                  <button type="submit" class="button btn-primary mr10 pull-right">Войти</button>
                  <label class="switch ib switch-primary pull-left input-align mt10">
                    <input id="remember" type="checkbox" name="remember" value="1" checked="">
                    <label for="remember" data-on="Да" data-off="Нет"></label> <span>Запомнить</span>
                  </label>
                </div>
              </form>
            </div>
          </div>
        </section>
      </section>
    </div>
    <!-- core scripts-->
    <script src="/admin/modules/authorisation/js/core.min.js"></script>
    <!-- Theme Javascript-->
    <script src="/admin/modules/authorisation/js/utility.js"></script>
    <script src="../upload/js/jquery.form.min.js"></script>
    <script src="../upload/js/validator.min.js"></script>
    <script src="/admin/modules/authorisation/js/demo.js"></script>
    <script src="/admin/modules/authorisation/js/main.js"></script>

    <!-- Page Javascript-->
    <script type="text/javascript">
      jQuery(document).ready(function () {
        "use strict";
        // Init Theme Core
        Core.init();
        // Init CanvasBG and pass target starting location
        CanvasBG.init({
          Loc: {
            x: window.innerWidth / 2,
            y: window.innerHeight / 3.3
          },
        });
      });
    </script>
  </body>
</html>