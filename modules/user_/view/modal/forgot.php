<div class="modal" tabindex="-1" id="forgot-modal" role="dialog">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <form id="forgot-form" action="" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?=A::$app->t('ForgotTitle', 'shop')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="forgot-result"></div>
                    <input type="hidden" name="do" value="forgot">
                    <div class="fstep1">
                        <div class="form-group"> 
                            <div class="row aling-items-center justify-content-between">
                                <div class="col-auto">
                                    <label>Телефон</label>
                                </div>
                            </div>
                            <input type="phone" class="form-control" name="phone" placeholder="" required> 
                        </div>
                    </div>
                    <div class="fstep2" style="display: none;">
                        <div class="form-group"> 
                            <div class="row aling-items-center justify-content-between">
                                <div class="col-auto">
                                    <label>Код подтверждения из SMS</label>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="code" placeholder="" > 
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('LoginPassword', 'shop')?></label>
                                    <input type="password" name="password" class="form-control" placeholder="" /> 
                                </div>
                            </div>
                            <div class="col-md-12">
                               <div class="form-group"> 
                                <label><?=A::$app->t('LoginPassword1', 'shop')?></label>
                                <input type="password" name="password1" class="form-control" placeholder="" /> 
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style=""> 
                <button class="btn btn-primary btn-lg btn-block"><?=A::$app->t('BtnForgot', 'shop')?></button> 
            </div>
        </form>
    </div>
</div>
</div>


