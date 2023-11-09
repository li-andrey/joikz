<style>
    header .navbar.fixed-top{
        border-bottom: 1px solid #000;
    }
</style>
       <div class="content">
            <form method="get" action="" class="filter-form">
                <ul class="filter">
                    <li>
                        <a href="">
                            <span>Фильтры</span>
                        </a>
                        <div class="info">
                            <div class="filter-content row">
                                <div class="filter-box col-lg col-12">
                                    <div class="filter-subject">
                                        <div class="filter-name">Цвет</div>
                                        <i class="fa fa-angle-down d-lg-none"></i>
                                    </div>
                                    <div class="">
                                        <ul>
                                            <? foreach ($color as $key => $r) { ?>
                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="color[]" <?=@in_array($r["color"], @A::$url->get["color"])?'checked':''?> class="filter-checkbox" value="<?=$r["color"]?>">
                                                        <?=$r["color"]?>
                                                    </label>
                                                </li>
                                            <? } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="filter-box col-lg col-12 relative">
                                    <div class="filter-subject">
                                        <div class="filter-name ">Бренд</div>
                                        <i class="fa fa-angle-down d-lg-none"></i>
                                    </div>
                                    <div class="">
                                        <ul>
                                            <? foreach ($brands as $key => $r) { ?>

                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="brand[]" <?=@in_array($r["id"], @A::$url->get["brand"])?'checked':''?> class="filter-checkbox" value="<?=$r["id"]?>">
                                                        <?=$r["name"]?>
                                                    </label>
                                                </li>
                                            <? } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="filter-box col-lg col-12 relative">
                                    <div class="filter-subject">
                                        <div class="filter-name ">Материал</div>
                                        <i class="fa fa-angle-down d-lg-none"></i>
                                    </div>
                                    <div class="">
                                        <ul>
                                            <? foreach ($material as $key => $r) { ?>

                                              <li>
                                                  <label>
                                                      <input type="checkbox" name="material[]" <?=@in_array($r["id"], @A::$url->get["material"])?'checked':''?> class="filter-checkbox" value="<?=$r["id"]?>">
                                                      <?=$r["name"]?>
                                                  </label>
                                              </li>
                                          <? } ?>
                                      </ul>
                                  </div>
                              </div>
                              <div class="filter-box col-lg col-12 relative">
                                <div class="filter-subject">
                                    <div class="filter-name ">Размер</div>
                                    <i class="fa fa-angle-down d-lg-none"></i>
                                </div>
                                <div class="">
                                    <ul>
                                        <? foreach ($size as $key => $r) { ?>

                                           <li>
                                               <label>
                                                <input type="checkbox" name="size[]" <?=@in_array($r["size"], @A::$url->get["size"])?'checked':''?> class="filter-checkbox" value="<?=$r["size"]?>">
                                                <?=$r["size"]?>
                                            </label>
                                        </li>
                                    <? } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="filter-box col-lg col-12 relative">
                            <div class="filter-subject">
                                <div class="filter-name ">Сортировка</div>
                                <i class="fa fa-angle-down d-lg-none"></i>
                            </div>
                            <div class="">
                                <ul>
                                    <li>
                                        <label>
                                            <input type="radio" name="order" class="filter-checkbox" <?=A::$app->get('order')=='' || A::$app->get('order')=='id_sort asc'?'checked':'' ?> value="id_sort asc">
                                            По популярности
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="order" class="filter-checkbox" value="price asc" <?=A::$app->get('order')=='price asc'?'checked':''?>>
                                            По возрастанию цены
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="order" class="filter-checkbox" value="price desc" <?=A::$app->get('order')=='price desc'?'checked':''?>>
                                            По убыванию цены
                                        </label>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="col-md  text-center">

                            <button type="reset" onclick="$('input[type=checkbox]').removeAttr('checked')" class="btn btn-outline-primary">Сбросить</button>
                            <button type="submit" class="btn btn-primary">Применить</button>

                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </form>


    <div class="product-grid py-5">
        <div class="container">
            <? if (sizeof($elements)>0){ ?>
                <div class="row justify-content-start">
                    <? foreach ($elements as $key => $r) { ?>
                        <?
                        $sql = "select * from i_block_elements where id='".$r["brand"]."'";
                        $brand = A::$db->get($sql, 1);
                        ?>
                        <div class="col-md-4 col-lg-3 col-6 col-xl">

                            <div class="product-item">
                                <a href="<?=LINK?>catalog/<?=$r["url"]?>/">
                                    <div class="image">
                                        <img src="<?=$r["image"]["s"]?>" alt="<?=$r["name"]?>" />
                                        <div class="badges">
                                            <? if ($r["new"]==1){ ?>
                                                <div class="badge-dark new">
                                                    Новинка
                                                </div>
                                            <? } ?>
                                            <? if ($r["spec"]==1){ ?>
                                                <div class="badge-dark">
                                                    sale
                                                </div>

                                            <? } ?>
                                            <? if ($r["price_old"]>0){ ?>
                                                <div class="badge-light">
                                                    <?=round(($r["price_old"]-$r["price"])/$r["price_old"], 2)*100?>%
                                                </div>
                                            <? } ?>

                                        </div>
                                    </div>
                                </a>
                                <div class="info mt-3">
                                    <div class="row no-gutters flex-nowrap align-items-center justify-content-between">
                                        <div class="col-auto">
                                            <div class="price" style="<?=($r["price_old"]>0?'color: #ff2a00;':'color: #000;')?>">
                                                <? if ($r["price_old"]>0){ ?>
                                                    <s><?=A::$api->price($r["price_old"], 1)?> ₸</s>
                                                <? } ?>
                                                <?=A::$api->price($r["price"], 1)?> ₸
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="" class="favorite like-icon <?= A::$app->wishlist->check($r["id"]) ? 'active' : '' ?>" data-id="<?=$r["id"]?>">
                                                <img src="/upload/bookmark.svg">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="brand">
                                        <?=@$brand["name"]?>
                                    </div>
                                    <div class="name">
                                        <?=$r["name"]?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? } ?>

                </div>

        <? }else{ ?>
            <p class="text-center">Ничего не найдено</p>
        <? } ?>
    </div>
</div>
</div>
