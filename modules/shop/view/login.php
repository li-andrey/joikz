<h3><?=A::$app->t('LoginTitle', 'shop')?></h3>
<form id="login-form" action="" method="post">
    <div id="login-result"></div>
    <input type="hidden" name="do" value="login">
    <div class="input-dec3"> 
        <input type="email" id="username" name="email" placeholder="<?=A::$app->t('LoginEmail', 'shop')?>" required> 
        <i class="fa fa-user"></i>
    </div>
    <div class="input-dec3"> 
        <input type="password" id="password" name="password" placeholder="<?=A::$app->t('LoginPassword', 'shop')?>" required> 
        <i class="fa fa-lock"></i>
    </div>
    <div class="dialog-footer"> 
        <button class="btn btn-primary"><?=A::$app->t('BtnLogin', 'shop')?></button> 
        <a href="#" data-toggle="modal" data-target="#forgot-box" onclick="jQuery('#signin-box').modal('toggle');">
            <?=A::$app->t('LinkForgot', 'shop')?><i class="fa fa-question-circle"></i>
        </a>
    </div>
</form>