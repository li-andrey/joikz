<div class="section-page login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <form id="login-form" action="" method="post">
                    <input type="hidden" name="do" value="login">
                    <div class="card text-center">
                        <div class="card-header">
                            <h4>Авторизация</h4>
                        </div>
                        <div class="card-body">
                            <div id="login-result"></div>
                            
                            <div class="form-group"> 
                                <input type="text" id="email" name="email" placeholder="E-mail или номер телефона" required> 
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="form-group"> 
                                <input type="password" id="password" name="password" placeholder="Пароль" required> 
                                <i class="fa fa-lock"></i>
                            </div>
                            <div class="btn-group" style=""> 
                                <button class="btn btn-secondary">Войти</button> 
                                <a href="<?=LINK?>forgot/" class="forgot">
                                    Забыли пароль?
                                </a>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <div class="text-muted text-center gray-line clearfix">
                                <h4>У вас нет аккаунта?</h4>
                            </div>
                            <p class=" text-center" style="margin-top: 14px;">
                                <a class="btn btn-outline-primary btn-lg " href="<?=LINK?>register/">Создать аккаунт</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>