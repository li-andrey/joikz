<div class="modal" tabindex="-1" id="login-modal" role="dialog">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <form id="login-form" action="" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?=A::$app->t('LoginTitle', 'shop')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="login-result"></div>
                    <input type="hidden" name="do" value="login">
                    <div class="form-group"> 
                        <div class="row aling-items-center justify-content-between">
                            <div class="col-auto">
                                <label>Телефон</label>
                            </div>
                            <div class="col-auto">
                                <a href="" data-toggle="modal" data-target="#forgot-modal" onclick="jQuery('#login-modal').modal('toggle');">Забыли пароль?</a>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="phone" placeholder="" required> 
                    </div>
                    <div class="form-group"> 
                        <label>Пароль</label>
                        <input type="password" class="form-control" name="password" placeholder="" required> 
                        <i class="fa fa-eye"></i>
                    </div>
                </div>
                <div class="modal-footer" style=""> 
                    <button class="btn btn-primary btn-lg btn-block">Вход</button> 

                    <h5>Еще нет аккаунта?</h5>
                    <button type="button" onclick="jQuery('#login-modal').modal('toggle');" data-toggle="modal" data-target="#register-modal" class="btn btn-primary btn-lg btn-block">Регистрация</button> 
                </div>
            </form>
        </div>
    </div>
</div>
<style type="text/css">
    .modal-content{
        border-radius: 0px;
    }
    .modal-header{
        border: 0px;
        padding: 25px 50px 0px;
        
    }
    .modal-header h5{
        font-size: 25px;
    }
    .modal-header .close{
        outline: 0px;
        position: absolute;
        right: 10px;
        top: 10px;
    }

    .modal-body{
        padding: 15px 50px 15px;
    }
    .modal-footer{
        padding: 0px 50px 2rem;   
        flex-direction: column;
        align-items: flex-start;
    }

    .modal-footer h5{
        font-size: 25px;
        margin-top: 2rem;
    }

    .modal-body label{
        color: #999;
        font-size: 15px;
    }

    .modal-body a{
        font-size: 15px;   
        text-decoration: underline;
    }

    .modal-body a:hover{
        text-decoration: none;
    }

    .form-group i{
        position: absolute;
        right: 15px;
        bottom: 15px;
        color: #999;
    }

    .modal-footer{
        border: 0px;
    }

    @media (min-width: 576px){
        .modal-dialog {
            max-width: 440px;
            margin: 1.75rem auto;
        }
        .modal-dialog.modal-lg {
            max-width: 880px;
            margin: 1.75rem auto;
        }
    }
    @media screen and (max-width: 767px) {
        .modal-header {
            border: 0px;
            padding: 25px 15px 0px;
        }
        .modal-body {
            padding: 15px 15px 15px;
        }
        .modal-footer {
            padding: 0px 15px 2rem;
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>