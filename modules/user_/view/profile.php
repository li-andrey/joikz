
<?=A::$app->user->viewJSON('blocks/top', array())?>
<style type="text/css">
    .filter .cabinet-menu{
        height: 85px;
        margin-bottom: 3rem;
    }
</style>
<form method="post" id="profile-form" enctype="multipart/form-data">
    
    <input type="hidden" name="do" value="profile">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="profile-result"></div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <fieldset >        
                            <legend>Основная информация</legend>
                            
                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Фамилия <span style="color: red">*</span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="fam" placeholder="" value="<?=$this->info->fam?>" required="" >
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Имя <span style="color: red">*</span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="" value="<?=$this->info->name?>" required="" >
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Отчество <span style="color: red"></span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="otch" placeholder="" value="<?=$this->info->otch?>"  >
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> E-mail <span style="color: red">*</span></label>

                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="" value="<?=$this->info->mail?>" required="" >
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Телефон <span style="color: red">*</span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control phone-mask" name="phone" placeholder="" value="<?=$this->info->phone?>"  required="">
                                    </div>
                                </div>
                            </div>



                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset >        
                            <legend>Реквизиты</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Регион<span></span></label>
                                        <select class="form-control" name="region" id="region" >
                                            <option value=""></option>
                                            <? foreach($region as $k=>$r){ ?>
                                                <option value="<?=$r["id"]?>" <?=$this->info->region==$r["id"]?'selected':''?>><?=$r["name"]?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="sub-city" data-city='<?=$this->info->city?>'>
                                            <label>&nbsp;</label>
                                            <select class="form-control" name="city" id="city"  >
                                                <option value=""></option>
                                                <? foreach($city as $k=>$r){ ?>
                                                    <option value="<?=$r["id"]?>" <?=$this->info->city==$r["id"]?'selected':''?>><?=$r["name"]?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> ИИН/БИН <span style="color: red"></span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="bin" placeholder="" value="<?=$this->info->bin?>" >
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Наименование компании <span style="color: red"></span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="company" placeholder="" value="<?=$this->info->company?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Номер расчетного счета и банк <span style="color: red"></span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="bank" placeholder="" value="<?=$this->info->bank?>"  >
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-12" >
                                    <label> Юридический адрес <span style="color: red"></span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="address" placeholder="" value="<?=$this->info->address?>" >
                                    </div>
                                </div>
                            </div>

                            




                        </fieldset>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <fieldset >        
                            <legend style="width: 100%">
                                Отметьте месторасположение на карте 
                                <a href="javascript:;" class="clear_map" style="<?=$this->info->map!=''?'display: inline-block;':''?>">Удалить метку</a>
                            </legend>

                            <div class="panel panel-default">
                                <div class="panel-body " data-caption="">
                                    <div class="form-group">
                                        <div id="map"> </div>
                                        <input type="hidden" class="f-map" name="map" id="f-map" value="<?=$this->info->map?>">
                                    </div>
                                </div>

                            </fieldset> 
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

                </div>
            </div>
        </div>
    </form>
    <br><br>