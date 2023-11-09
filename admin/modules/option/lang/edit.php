  <?
  require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php";

  $nameRazdel = 'lang';
  $nameElement = 'lang';
  $nameModule = 'lang';

  if (@$_POST['hidden']) {

    if (@$_POST['hidden'] == 'save') {
      $page = "index.php?id_section=" . @$_GET['id_section'] . "&module=" . @$_GET['module'];
    } else { 
      $page = "";
    }



    if (!@$erorr) {

      $sql = "update i_" . $ob->pr($_GET['module']) . " set  active='" . @$_POST['active'] . "', name='" . @$_POST['name'] . "', name_reduction='" . @$_POST['name_reduction'] . "', `default`='" . @$_POST['default'] . "' where id='".intval($_GET["id"])."'";

      $i = $mysql->query($sql);

      if (!$i) {
        $ob->alert("Ошибка добавления поля! ".$mysql->error);
      }else{
        $id = intval($_GET["id"]);
      }
      if ($i) { 
        $ob->go($page);
      }
    } else {
      $ob->alert($erorr);
    }
  }


  $sql = "select name from i_modules where folders='$nameModule' limit 1";
  $res = $mysql->get($sql, 1);

  if (isset($_GET["id"])){
    $sql = "select * from i_lang where id='".intval($_GET["id"])."'";
    $resU = $mysql->get($sql, 1);
  }

  ?>
  <script>
    function pr(hidden_val){
      var msg;
      var fr;
      msg = '';
      fr = document.form;
      if (fr.name.value==''){
        msg=msg+'* Имя \n';
      }

      if(msg==''){
        fr.hidden.value=hidden_val;
        fr.submit();
      }else{
        msg='Необходимо заполнить обязательные поля:\n'+msg;
        alert(msg);
      }
    }

  </script>
  <form action="" method="post" enctype="multipart/form-data" name="form" id="form" >
    <!-- Start: Topbar-->
    <header id="topbar" class="alt" style="padding-bottom: 0px;">
      <div class="topbar-left pull-left">
        <ol class="breadcrumb">
          <li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
          <li class="crumb-active"><a href="index.php"><?=@$res["name"]?></a></li>
          <li class="crumb-active"> Редактирование языковой версии</li>
        </ol>
      </div>
    </header>
    <section id="content" class="table-layout">
      <div class="tray tray-center" style="padding-top: 0px;">
        <div class="tray-inner">
          <div class="row flex-column-reverse-before-md">
            <div class="col-sm-12">
             <div class="panel">
              <div class="br-b panel-heading">
                <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
                  <li class="active">
                    <a href="#tab2_1" data-toggle="tab" aria-expanded="true">Основная информация</a>
                  </li>

                </ul>
              </div>
              <div class="panel-body">
                <div class="tab-content pn br-n">
                  <div id="tab2_1" class="tab-pane active">
                    <div class="panel  top mb35">
                      <div class="panel-body bg-white">
                        <div class="admin-form">
                          <div class="section row mb10">
                            <label class="field-label col-md-3 text-right">
                             Активность:
                           </label>
                           <div class="col-md-9">
                            <label style="line-height: 42px;"><input name="active" type="checkbox" id="active" <?=@$resU["active"]==1?'checked':''?>  value="1" /></label>
                          </div>
                        </div>
                        <div class="section row mb10">
                          <label class="field-label col-md-3 text-right">
                           Язык по умолчанию:
                         </label>
                         <div class="col-md-9">
                          <label style="line-height: 42px;"><input name="default" type="checkbox" id="default" <?=@$resU["default"]==1?'checked':''?>  value="1" /></label>
                        </div>
                      </div>

                      <div class="section row mb10">
                        <label class="field-label col-md-3 text-right">
                          <span class="small_red_text">*</span> Наименование:
                        </label>
                        <div class="col-md-9">
                          <input name="name" type="text" id="name" required="" size="35" class="form-control" value="<?=@$resU["name"]?>" />
                        </div>
                      </div>
                      <div class="section row mb10">
                        <label class="field-label col-md-3 text-right">
                         <span class="small_red_text"></span> Сокращение (translate):
                       </label>
                       <div class="col-md-9">
                        <input name="name_reduction" type="text" id="name_reduction" size="35" class="form-control" value="<?=@$resU["name_reduction"]?>" />
                      </div>
                    </div>


                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div style="padding: 30px 20px 20px; margin-top: -70px; position: relative;">
          <input type="button" class="btn btn-system" name="button" id="button" value="Сохранить" onClick=" pr('save')" />
          <input type="button" class="btn btn-success" name="button2" id="button2" value="Применить"  onclick=" pr('apply')" />
          <input type="reset"  class="btn btn-warning"  name="button3" id="button3" value="Отменить"  />
          <input type="hidden" name="hidden" id="hidden" />
        </div>
      </div>

    </div>
  </div>
</div>

</section>


</form>

<?require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/footer.php"?>

