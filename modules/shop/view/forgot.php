<h3><?=A::$app->t('ForgotTitle', 'shop')?></h3>
<form id="forgot-form" action="" method="post">
    <div id="forgot-result"></div>
    <input type="hidden" name="do" value="forgot">
    <div class="input-dec3"> 
        <input type="email" id="username" name="email" placeholder="<?=A::$app->t('LoginEmail', 'shop')?>" required> 
        <i class="fa fa-user"></i>
    </div>
    <div class="dialog-footer"> 
        <button class="btn btn-primary"><?=A::$app->t('BtnForgot', 'shop')?></button> 
    </div>
</form>