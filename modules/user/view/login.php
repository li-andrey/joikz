<style type="text/css">
    .code-form .form-control {
        height: 45px;
    }

    .register-form .form-control {
        height: 45px;
    }

    .input-group {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: stretch;
        align-items: stretch;
        width: 100%
    }

    .input-group-addon {
        width: 40px;
        height: 45px;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 45px;
        color: #b7bec5;
        text-align: center;
        border: 1px solid #000;
        border-right: 0;
        border-radius: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    hr {
        border-color: #000 !important;
    }

    .input-group-addon svg {
        width: 20px;
        height: 20px;
        fill: #000;
    }

    .btn-link {
        color: #000 !important;
    }
</style>
<ul class="filter">
    <li>
        <a href="">
            <span class="text-uppercase">Авторизация</span>
        </a>

    </li>
</ul>
<div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col mx-auto">
                    <div class="row justify-content-center">
                        <div class="col-md-7 col-lg-7">
                            <div class=" p-4">
                                <div class=" pt-0">


                                    <form class="code-form">
                                        <div class="row py-5">
                                            <div class="col-md-2">
                                            </div>
                                            <div class="col-md-8">
                                                <p class="text-center">Введите номер телефона, на который будет отправлен код для авторзиации</p>
                                                <div id="register-result-code"></div>
                                                <div class="row align-items-center justify-content-center" style=" margin-left: -15px; margin-right: -15px;">
                                                    <div class="col-12 d-flex flex-nowrap">
                                                        <div class="input-group ">
                                                            <span class="input-group-addon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
                                                                    <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z" />
                                                                    <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                                                </svg>

                                                            </span>
                                                            <input type="text" name="phone" class="form-control phone-code" required placeholder="Телефон">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="mt-3 btn btn-lg btn-primary btn-block btn-send-code" disabled>Отправить SMS код</button>
                                                <button type="button" class="btn btn-link btn-lg btn-block send-replay disabled" disabled="" style="display: none">
                                                    Отправить код повторно<span>(60)</span>
                                                </button>
                                                <div class="row mt-3 align-items-center justify-content-center" style=" margin-left: -15px; margin-right: -15px;">
                                                    <div class="col-12 ">
                                                        <input type="text" name="code" autocomplete="off" class="form-control text-center" maxlength="4" placeholder="Введите код из SMS" style="display: none">
                                                        <button type="button" class="btn btn-primary btn-lg  mt-3 mb-3  btn-block btn-check-code" disabled style="display: none">Отправить
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-12 py-3 text-center">
                                                       <a href="<?= LINK ?>user/register/" class="btn btn-link">Нет аккаунта?</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                            </div>
                                        </div>
                                    </form>

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-sms-code">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Подтверждение регистрации</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" class="sms-form" id="sms-form">
                    <div id="sms-result"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control text-center" maxlength="4" name="code" required="" placeholder="Введите код из SMS">
                            </div>
                            <button type="btn" class="btn btn-primary mt-3 btn-block btn-check-code" disabled="">Отправить</button>
                            <button type="button" class="btn btn-link btn-block send-replay disabled" disabled="">
                                Отправить код повторно <span>(60)</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->