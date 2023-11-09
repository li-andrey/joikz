<div class="section-page login register">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <form id="register-form" action="" method="post" data-success="<?=LINK?>my-ads/">

                    <input type="hidden" name="do" value="register">
                    <? if (isset($_SESSION["user_activation_email"])){ ?>
                        <input type="hidden" name="email" value="<?=$_SESSION["user_activation_email"]?>">
                        <input type="hidden" name="step" value="2">
                    <? }elseif (isset($_SESSION["user_activation_phone"])){ ?>
                        <input type="hidden" name="phone" value="<?=$_SESSION["user_activation_phone"]?>">
                        <input type="hidden" name="step" value="2">
                    <? }else{ ?>
                        <input type="hidden" name="step" value="1">
                    <? } ?>

                    <div class="card text-center">
                        <div class="card-header">
                            <h4><?=t('register-title', 'user')?></h4>
                        </div>
                        <? if (isset($_SESSION["user_activation_email"])){ ?>
                            <div class="card-body">
                                <div id="register-result" class="alert alert-success"><?=t('actiovation-user-info', 'user')?></div>
                                <div class="form-group" > 
                                    <input type="password" id="password" name="password" autocomplete="off" placeholder="<?=t('password', 'user')?>" required> 
                                    <i class="fa fa-lock"></i>
                                </div>
                                <div class="form-group" > 
                                    <input type="password" id="password1" name="password1" autocomplete="off" placeholder="<?=t('password-repeat', 'user')?>" required> 
                                    <i class="fa fa-lock"></i>
                                </div>
                                
                                <div class="btn-group" style=""> 
                                    <button class="btn btn-secondary btn-lg btn-block" style="max-width: 100%"><?=t('register-btn', 'user')?></button> 
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div style="font-size: 12px;">
                                    <?=t('password-min-info', 'user')?>
                                </div>
                            </div> 
                        <? }else if (isset($_SESSION["user_activation_phone"])){ ?>
                            <div class="card-body">
                                <div id="register-result" class="alert alert-success"><?=t('actiovation-user-info-phone', 'user')?></div>
                                <div class="form-group" > 
                                    <input type="password" id="password" name="password" autocomplete="off" placeholder="<?=t('password', 'user')?>" required> 
                                    <i class="fa fa-lock"></i>
                                </div>
                                <div class="form-group" > 
                                    <input type="password" id="password1" name="password1" autocomplete="off" placeholder="<?=t('password-repeat', 'user')?>" required> 
                                    <i class="fa fa-lock"></i>
                                </div>
                                
                                <div class="btn-group" style=""> 
                                    <button class="btn btn-secondary btn-lg btn-block" style="max-width: 100%"><?=t('register-btn', 'user')?></button> 
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div style="font-size: 12px;">
                                    <?=t('password-min-info', 'user')?>
                                </div>
                            </div> 
                        <? }else{ ?>
                            <div class="card-body">
                                <div id="register-result"></div>
                                <div class="phone-block">
                                    <div class="form-group" > 
                                        <input type="text" id="email" name="email" autocomplete="off" placeholder="<?=t('register-email', 'user')?>" required> 
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <div class="row">
                                        <div class="col-1" style="text-align: center;">
                                            <input type="checkbox" name="rules" required="" value="1"> 
                                        </div>
                                        <div class="col-11">
                                            <div class="rules">
                                                <?=t('register-rules', 'user')?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-group" style=""> 
                                        <button class="btn btn-secondary btn-lg btn-block" style="max-width: 100%"><?=t('register-btn', 'user')?></button> 
                                    </div>
                                </div>
                                <div class="code-block">
                                    <div class="form-group" > 
                                        <input type="text" id="code" name="code" autocomplete="off" placeholder="<?=t('register-code', 'user')?>" > 
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-12">
                                            <button href="javascript:;" class="btn btn-link send-replay disabled" disabled="" style=""><?=t('activation-user-send-replay', 'user')?><span>()</span></button>
                                        </div>
                                    </div>
                                    <div class="btn-group" style=""> 
                                        <button class="btn btn-secondary btn-lg btn-block" style="max-width: 100%"><?=t('register-send', 'user')?></button> 
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div style="font-size: 12px;">
                                    <?=t('register-sms', 'user')?>
                                </div>
                            </div> 
                        <? } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
