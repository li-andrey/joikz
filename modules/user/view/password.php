
<?=A::$app->user->viewJSON('blocks/top', array())?>
<style type="text/css">
    .filter .cabinet-menu{
        height: 85px;
        margin-bottom: 3rem;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <fieldset >        
                        <legend>Изменение пароля</legend>
                        <form method="post" id="password-form" enctype="multipart/form-data">
                            <div id="password-result"></div>
                            <input type="hidden" name="do" value="password">


                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Новый пароль <span style="color: red">*</span></label>

                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" placeholder="" required="" >
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Повторите пароль <span style="color: red">*</span></label>

                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password1" placeholder=""  required="">
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group" style="padding-top: 1rem; text-align: center;">
                                        <button type="submit" class="btn btn-primary btn-lg" >
                                            Сохранить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
