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
            <span class="text-uppercase"><?= $this->h1 ?></span>
        </a>

    </li>
</ul>
<form method="post" id="profile-form" enctype="multipart/form-data">
    <input type="hidden" name="do" value="profile">
    <div class="container">
        <? if (A::$app->user->auth()) { ?>
            <div class="row justify-content-center">
                <div class="col-md-6 py-4">
                    <div id="profile-result"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">


                            <div class="row ">
                                <div class="col-md-6">
                                    <label> Фамилия <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="fam" placeholder="" value="<?= $this->fam ?>" required="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label> Имя <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="" value="<?= $this->name ?>" required="">
                                    </div>
                                </div>


                            </div>
                            <div class="row ">


                                <div class="col-md-6">
                                    <label> Телефон <span style="color: red">*</span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control phone-mask" name="phone" placeholder="" value="<?= $this->phone ?>" required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label> E-mail <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="" value="<?= $this->mail ?>" required="">
                                    </div>
                                </div>


                            </div>

                            
                            <div class="row mt-3 mb-4">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary">Сохранить</button>
                                </div>
                            </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        <? } else {
            echo '
            <br/><br/><br/>
            <p align="center">
            <b>Ошибка авторизации! Пожалуйста авторизуйтесь.</b>
            </p>
            <p align="center">Данная страница доступна только зарегистрированным пользователям</p>
            <br/><br/><br/>
            ';
        } ?>
    </div>
</form>