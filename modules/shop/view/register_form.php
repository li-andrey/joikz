<h3><?=A::$app->t('RegisterTitle', 'shop')?></h3>
<form id="register-form" action="" method="post">
    <div id="register-result"></div>
    <input type="hidden" name="do" value="register">
    <div class="input-dec3"> 
        <input type="text" id="user_nickname" name="name" placeholder="<?=A::$app->t('RegisterName', 'shop')?>" required /> 
        <i class="fa fa-user"></i>
    </div>
    <div class="input-dec3"> 
        <input type="text" id="user_phone" name="phone" placeholder="<?=A::$app->t('RegisterPhone', 'shop')?>" required /> 
        <i class="fa fa-phone"></i>
    </div>
    <div class="input-dec3"> 
        <input type="email" id="user_email" name="email" placeholder="<?=A::$app->t('RegisterEmail', 'shop')?>" required> 
        <i class="fa fa-envelope-o"></i>
    </div>
    <div class="input-dec3"> 
        <input type="password" id="user_pass" name="password" placeholder="<?=A::$app->t('RegisterPassword', 'shop')?>" required> 
        <i class="fa fa-lock"></i>
    </div>
    <div class="dialog-footer"> <button class="btn btn-primary"><?=A::$app->t('BtnRegister', 'shop')?></button></div> 
</form>