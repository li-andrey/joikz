<?require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php"?>
<?
$nameRazdel = 'subscribes';
$nameElement = 'subscribes_elements';
$nameModule = 'subscribe';
if (@$_POST['hidden']) {
  $mysql->query("UPDATE i_subscribes SET `version`='" . $_SESSION['version'] . "', `act`='" . (@$_POST['act'] ? 1 : 0) . "', `mail_from`='" . $_POST['mail_from'] . "', `name`='" . $_POST['name'] . "', `theme`='" . $_POST['theme'] . "', `message`='" . $_POST['full_text'] . "', `files`='" . $_POST['upl_files'] . "' WHERE `id`='" . $_GET['id'] . "'");
  if ($_POST['hidden'] == 'save') {$page = "index.php";} else { $page = "";}
  $ob->go($page);
}
$mysql->query("insert into i_logs values(0, CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','edit','subscribes', '".@$_POST["name"]."', '', '')");
$select   = $mysql->query("SELECT * FROM i_subscribes WHERE `id`='" . $_GET['id'] . "' AND `version`='" . $_SESSION['version'] . "' LIMIT 1");
$rassilka = $select->fetch_object();
?>
<?
$sql = "select name from i_modules where folders='$nameModule' limit 1";
$res = $mysql->get($sql, 1);
?>
<script>
  function pr(hidden_val) {
    var fr = document.form;
    if (fr.name.value == "") {
      alert("Заполните название рассылки!");
      return false;
    } else if (fr.theme.value == "") {
      alert("Заполните тему рассылки!");
      return false;
    }
    fr.hidden.value = hidden_val;
    if (uploaded_files) {
      for (var i in uploaded_files) {
        if (uploaded_files[i].file) fr.upl_files.value += "|" + uploaded_files[i].file;
      }
      fr.upl_files.value += "|";
    }
    fr.submit();
  }
</script>
<iframe name="hidden_frame" id="hidden_frame" style="display:none; position:absolute;"></iframe>
<form action="<?=$_SERVER['PHP_SELF']?>?id=<?=$_GET['id']?>" method="post" enctype="multipart/form-data" name="form" id="form" >
  <!-- Start: Topbar-->
  <header id="topbar" class="alt" style="padding-bottom: 0px;">
    <div class="topbar-left pull-left">
      <ol class="breadcrumb">
        <li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li class="crumb-active"><a href="index.php"><?=$res["name"]?></a></li>
        <?
        admin_print_dir(@$_GET["module"], @$_GET["sub_module"]);
        ?>
        <li class="crumb-active"> Редактирование рассылки</li>
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
                          <label class="field-label col-md-3 text-right">Активна:</label>
                          <div class="col-md-9">
                            <label style="line-height: 42px;"><input name="act" type="checkbox" id="act" value="1" <?=($rassilka->act ? "checked" : "")?> /></label>
                          </div>
                        </div>
                        <div class="section row mb10">
                          <label class="field-label col-md-3 text-right">Отсылать с e-mail:</label>
                          <div class="col-md-9">
                            <input name="mail_from" class="form-control" type="text" id="mail_from" value="<?=$rassilka->mail_from?>" size="35" />
                          </div>
                        </div>
                        <div class="section row mb10">
                          <label class="field-label col-md-3 text-right">Название рассылки:</label>
                          <div class="col-md-9">
                            <input name="name" class="form-control" type="text" id="name" value="<?=$rassilka->name?>" size="35" />
                          </div>
                        </div>
                        <div class="section row mb10">
                          <label class="field-label col-md-3 text-right">Тема рассылки:</label>
                          <div class="col-md-9">
                            <input name="theme" class="form-control" type="text" id="theme" value="<?=$rassilka->theme?>" size="35" />
                          </div>
                        </div>
                        <div class="section row mb10">
                          <label class="field-label col-md-3 text-right">Файлы:</label>
                          <div class="col-md-9">
                            <div id="uploaders-container">
                            </div>
                            <div id="uploaded-files-container">
                            </div>
                          </div>
                        </div>
                        <div class="section row mb10">
                          <label class="field-label col-md-3 text-right">Текст:</label>
                          <div class="col-md-9">
                            <textarea id="full_text" name="full_text" value=""><?=htmlspecialchars($rassilka->message)?></textarea>

                            <script>
                              var edit_full_text = CKEDITOR.replace( 'full_text',{
                                filebrowserBrowseUrl : '/admin/modules/manager/ckfinder.html',
                                filebrowserImageBrowseUrl : '/admin/modules/manager/ckfinder.html?type=Images',
                                filebrowserFlashBrowseUrl : '/admin/modules/manager/ckfinder.html?type=Flash',
                                filebrowserUploadUrl : '/admin/modules/manager/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                filebrowserImageUploadUrl : '/admin/modules/manager/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                filebrowserFlashUploadUrl : '/admin/modules/manager/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                              } );
                            </script>
                          </div>
                        </div>
                        <?
                        $option = $ob->select("i_option", array("category" => "user"), "id_sort");

                        while ($option_res = $option->fetch_array()) {
                          if ($option_res['required_fields'] == 1) {$star = '<span class="small_red_text">*</span>';} else { $star = '';}

                          echo '<div class="section row mb10">
                          <label class="field-label col-md-3 text-right">
                          ' . $star . '&nbsp;' . $option_res['name_ru'] . ':
                          </label>
                          <div class="col-md-9">
                          ' . $ob->input_view($option_res['id'], "", "", "", "") . '
                          </div>
                          </div>';
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </ul>
        </div>
        <div style="padding: 0px 20px 20px; margin-top: -70px; position: relative;">
          <input type="button" class="btn btn-system" name="button" id="button" value="Сохранить" onClick=" pr('save')" />
          <input type="button" class="btn btn-success" name="button2" id="button2" value="Применить"  onclick=" pr('apply')" />
          <input type="reset"  class="btn btn-warning"  name="button3" id="button3" value="Отменить"  />
          <input type="hidden" name="hidden" id="hidden" />
          <input type="hidden" name="upl_files" id="upl_files" />
        </div>
      </div>

    </div>
  </div>
</div>
</div>
</section>


</form>
<script type="text/javascript">
 var template =
  '<div style="margin-top:5px; border:1px dotted #C0C0C0;">' +
  '<table border="0"  style="width:100%;">' +
  '<tr>' +
  '<td><div id="screen_frame_O_O" style="display:none;">' +
  '<iframe name="iframe_O_O" width="100%" height="20" frameborder="0" scrolling="no"></iframe>' +
  '</div></td><td align="right"><button id="closer_O_O" class="btn btn-danger" onclick="removeUploader(O_O)" style="display:none; padding:4px 10px; margin:10px 0px; margin-bottom: -42px;">&nbsp;x&nbsp;</button></td>' +
  '</tr>' +
  '<tr>' +
  '<td align="left" colspan="2">' +
  '<div id="screen_form_O_O">' +
  '<form name="load_form_O_O" id="load_form_O_O" enctype="multipart/form-data" method="POST" action="load_file.php" target="iframe_O_O" onsubmit="">' +
  '<table border="0" style="width:100%;">' +
  '<tr>' +
  '<td class="small_text">O_O.</td><td class="small_text"> <input type="file" name="f_file" id="file_O_O" onchange="showCloser(O_O); if( O_O == index-1) createUploader()"></td>' +
  '<td><input type="button" onclick="checkLoad(O_O); " class="btn btn-primary" style="padding:4px 10px; margin:10px 0px; " value="Прикрепить"></td>' +
  '</tr>' +
  '</table>' +
  '</form>' +
  '</div>' +
  '</td>' +
  '</tr>' +
  '</table>' +
  '</div>';

  var index = 1;
  var uploaded_files = new Array();

  function createUploader() {
    var div = document.createElement('div');
    div.innerHTML = template.replace(new RegExp('O_O', 'g'), index);
    div.id = 'sp_' + index;
    document.getElementById('uploaders-container').appendChild(div);
    index++;
  }

  function showCloser(i) {
    document.getElementById('closer_' + i).style.display = "block";
  }

  function removeUploader(index) {
    document.getElementById('uploaders-container').removeChild(document.getElementById('sp_' + index));
  }

  function showFrame(i) {
     document.getElementById('screen_form_' + i).style.display = 'none';
    //document.getElementById( 'screen_frame_'+i ).style.display='block';
    document.getElementById('sp_' + i).style.display = 'none';
  }

 function checkLoad(i) {
    if (!document.getElementById('file_' + i).value) {
      alert('Файл не задан!');
      return false;
    } else {
      document.getElementById('load_form_' + i).submit(); showFrame(i);
    }
  }

  function replainFiles() {
    if (uploaded_files) {
      var div = document.createElement('div');
      document.getElementById('uploaded-files-container').innerHTML = "";
      var str = '<table style="margin-top:30px;" width="100%" border="1" class="table table-bordered"><tbody><tr class="small_text" align="center" style="color:#572a00; background-color:#ecebcf;"><td style="border:1px solid #545644;" width="50%">Файл</td><td style="border:1px solid #545644;" width="25%">Размер</td><td style="border:1px solid #545644;" width="25%">Действие</td></tr>';
      var check = 0;
      for (var i in uploaded_files) {
        if (uploaded_files[i].file) check++;
        str += '<tr class="small_text" align="center"><td>' + uploaded_files[i].file + '</td><td>' + (uploaded_files[i].size / 1024).toFixed(2) + ' KB </td><td><a href="javascript:void(0);" onClick="if(confirm(\'Вы уверены что хотите удалить этот файл?\')) deleteUploadedFile(' + (i) + ');">Удалить</a></td></tr>';
      }
      str += '</tbody></table>';
      if (check) document.getElementById('uploaded-files-container').innerHTML = str;
      else document.getElementById('uploaded-files-container').innerHTML = '';
    } else {
      document.getElementById('uploaded-files-container').innerHTML = '';
    }
    return true;
  }

  function deleteUploadedFile(i) {
    if (uploaded_files[i]) {
      document.getElementById('hidden_frame').src = "delete_file.php?file=" + uploaded_files[i].file + "&f_id=" + i;
    } else alert("Элемента не существует.");
  }
  createUploader();
  <?
  $files_arr = explode("|", $rassilka->files);
  if ($files_arr) {
    foreach ($files_arr as $f) {
      if ($f && file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/subscribe/" . $f)) {
        echo "uploaded_files.push({file: '" . $f . "', size:'" . filesize($_SERVER['DOCUMENT_ROOT'] . "/upload/subscribe/" . $f) . "'});\n";
      }

    }
    echo "replainFiles();";
  }
  ?>
</script>
<?require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/footer.php"?>
