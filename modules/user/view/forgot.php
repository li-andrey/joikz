<style type="text/css">
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
        height: calc(1.5em + .75rem + 2px);
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: calc(1.5em + .75rem + 2px);
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
            <span class="text-uppercase">Восстановление пароля</span>
        </a>

    </li>
</ul>
<div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col mx-auto">
                    <div class="row justify-content-center">
                        <div class="col-md-7 col-lg-5">
                            <div class=" p-4">
                                <div class="card-body pt-0">
                                    <p class="text-center">Для восстановелния пароля введите <br>ваш E-mail</p>
                                    <form id="forgot-form" action="" method="post">
                                        <input type="hidden" name="do" value="forgot">


                                        <div id="forgot-result"></div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-addon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                                                </svg>
                                            </span>
                                            <input type="text" name="email" class="form-control " required placeholder="E-mail">
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-lg btn-primary btn-block"><?= t('Отправить') ?></button>
                                            </div>

                                        </div>
                                    </form>

                                    <div class="text-center mt-3">
                                        <div class="font-weight-normal fs-16">
                                            <a class="btn-link btn font-weight-normal" href="<?= LINK ?>user/login/" style="color: #015ba8;">
                                                <?= t('Вернуться к авторизации') ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>