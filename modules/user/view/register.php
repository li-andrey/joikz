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
            <span class="text-uppercase">Регистрация</span>
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
                                                <p class="text-center">Для регистрации необходимо подтвердить номер телефона</p>
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
                                                <button type="button" class="mt-3 btn btn-lg btn-primary btn-block btn-send-code-register" disabled>Отправить SMS код</button>
                                                <button type="button" class="btn btn-link btn-lg btn-block send-replay-register disabled" disabled="" style="display: none">
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
                                                        <a href="<?= LINK ?>user/login/" class="btn btn-link"> Уже зарегистрированы?</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                            </div>
                                        </div>
                                    </form>

                                    <form id="register-form" class="register-form py-5" action="" method="post" style="display: none">
                                        <input type="hidden" name="do" value="register">
                                        <input type="hidden" name="phone" class="form-control phone-code" required placeholder="Телефон">
                                        <div class="row py-5">
                                            <div class="col-md-2">
                                            </div>
                                            <div class="col-md-8">
                                                <p class="text-center">Для продолжения регистрации заполните поля ниже</p>
                                                <div id="register-result"></div>
                                                <div class="row">

                                                    <div class="col-md-12">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-addon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="name" required class="form-control" placeholder="Имя">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-md-12">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-addon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                                                                </svg>
                                                            </span>
                                                            <input type="email" name="email" class="form-control" placeholder="E-mail" >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" required name="rules">
                                                        <span class="custom-control-label">Согласен (-сна) с <a href="" data-bs-toggle="modal" data-bs-target="#modal-oferta" class="btn-link">договором оферты</a></span>
                                                    </label>
                                                </div>

                                                <div class="row justify-content-center">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Сохранить</button>
                                                    </div>
                                                </div>
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