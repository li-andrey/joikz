<? 
$this->meta(
    array(
        'h1' => 'Личный кабинет',
        'title' => 'Личный кабинет',
        'descr' => '',
        'keyw' => '',
    )
);
$this->header();
?>
    <style>
   
        .col-main{
            width: 100%;
        }
        .main-container #main-menu {
            top: -24px;
        }
    </style>
    <div class="std">
    <div class="cart">
    <div class="page-title">
        <h2>Редактировать профиль</h2>
    </div>
    <div class="std">
<?
if (A::$app->user->check_auth()){
    ?>
    
    <form method="post" id="profile-form" enctype="multipart/form-data">
        <div id="profile-result"></div>
        <input type="hidden" name="do" value="profile">
        <div class="row ">
            <div class="col-md-4" style="text-align: right;">
                <label><span style="color: red">*</span> <?=A::$app->shop->t('Фамилия')?></label>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <input type="text" class="form-control" name="fam" value="<?=A::$app->user->info["fam"]?>" placeholder="" required="">
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-4" style="text-align: right;">
                <label><span style="color: red">*</span> <?=A::$app->shop->t('RegisterName')?></label>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" value="<?=A::$app->user->info["name"]?>" placeholder="" required="">
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-4" style="text-align: right;">
                <label><span style="color: red">*</span> <?=A::$app->shop->t('RegisterPhone')?></label>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" value="<?=A::$app->user->info["phone"]?>" placeholder="" required="">
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-4" style="text-align: right;">
                <label><span style="color: red">*</span> <?=A::$app->shop->t('RegisterEmail')?></label>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" value="<?=A::$app->user->info["mail"]?>" placeholder="" required="">
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-4" style="text-align: right;">
                <label><span style="color: red"></span> Изменить пароль</label>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="" >
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-4" style="text-align: right;">
                <label><span style="color: red"></span> <?=A::$app->shop->t('password_repeat')?></label>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <input type="password" class="form-control" name="password1" placeholder="" >
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-5">
                <div class="form-group" style="padding-top: 1rem">
                    <button type="submit" class="btn btn-primary" >
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </form>
<? }else{ ?>
    <br>
    <br>
    <br>
    <br>
    <p align="center">Необходимо авторизоваться</p>
    <br>
    <br>
    <br>
<? } ?>
    </div>
    </div>
    </div>
<? $this->footer() ?>