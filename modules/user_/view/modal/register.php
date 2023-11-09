<?
include_once($_SERVER["DOCUMENT_ROOT"].'/modules/Connection.php');
?>
<div class="modal" tabindex="-1" id="register-modal" role="dialog">
    <div class="modal-dialog "  role="document" style="max-width: 550px;">
        <div class="modal-content">
            <form id="register-form" action="" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?=A::$app->t('RegisterTitle', 'shop')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="register-result"></div>
                    <input type="hidden" name="do" value="register">

                    <div class="step1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterPhone', 'shop')?></label>
                                    <input type="text" name="phone" class="form-control" placeholder="" required="" /> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step2" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <label>Код подтверждения из SMS</label>
                                    <input type="text" name="code" class="form-control" placeholder=""  /> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterFam', 'shop')?></label>
                                    <input type="text"  name="fam" class="form-control" placeholder=""  /> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterName', 'shop')?></label>
                                    <input type="text"  name="name" class="form-control" placeholder=""  /> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterOtch', 'shop')?></label>
                                    <input type="text"  name="otch" class="form-control" placeholder=""  /> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterMale', 'shop')?></label>
                                    <select name="male" class="form-control" >
                                        <option value="1">Мужской</option>
                                        <option value="2">Женский</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterEmail', 'shop')?></label>
                                    <input type="email" name="email" class="form-control" placeholder="" /> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterCity', 'shop')?></label>
                                    <select name="city" class="form-control" >
                                        <?
                                        $result = Connection::init()
                                        ->setParams(
                                            [],
                                            Connection::CITY_REQUEST
                                        )
                                        ->getArray();
                                        $name = array_column($result, 'name');
                                        array_multisort($name, SORT_ASC, $result);
                                        foreach ($result as $key => $value) {
                                            ?>
                                            <option value="<?=$value["id"]?>"><?=$value["name"]?></option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('RegisterYear', 'shop')?></label>
                                    <input type="date" name="data" class="form-control" placeholder="" /> 
                                </div>
                            </div>
                            <div class="col-md-6">
                             <div class="form-group"> 
                                <label><?=A::$app->t('RegisterPromo', 'shop')?></label>
                                <input type="text" name="promo" class="form-control" placeholder="" /> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label><?=A::$app->t('LoginPassword', 'shop')?></label>
                                    <input type="password" name="password" class="form-control" placeholder="" /> 
                                </div>
                            </div>
                            <div class="col-md-6">
                             <div class="form-group"> 
                                <label><?=A::$app->t('LoginPassword1', 'shop')?></label>
                                <input type="password" name="password1" class="form-control" placeholder="" /> 
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="modal-footer" style=""> 
                <button class="btn btn-primary btn-lg btn-block">Зарегистрироваться</button> 
            </div>
        </form>
    </div>
</div>
</div>