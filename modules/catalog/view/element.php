<style>
.product-item {
    margin-bottom: 0rem;
    min-width: unset;
}
.badges {
    position: absolute;
    left: 0px;
    bottom: 0px;
    width: 100%;
    font-size: 10px;
}

.slick-track {
  display: flex;
}
.slick-track .slick-slide {
  display: flex;
  height: auto;
}
.product .gallery img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
</style>
<div class="content">
    <div class="product py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="gallery">
                        <?
                        $sql = "select * from i_cat where id_section='".$e["id"]."' and active=1 group by color order by color asc limit 1";
                        $color = A::$db->get($sql, 1);

                        if(isset($color["id"]) and $color["image"]!=''){
                            ?>
                            <div class="slider slider-for">
                                <? if (@$color["image"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i1">
                                            <img src="/upload/images/<?=$color["image"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image1"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i2">
                                            <img src="/upload/images/<?=$color["image1"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image2"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i3">
                                            <img src="/upload/images/<?=$color["image2"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image3"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i4">
                                            <img src="/upload/images/<?=$color["image3"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image4"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i5">
                                            <img src="/upload/images/<?=$color["image4"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image5"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i6">
                                            <img src="/upload/images/<?=$color["image5"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image6"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i7">
                                            <img src="/upload/images/<?=$color["image6"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image7"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i8">
                                            <img src="/upload/images/<?=$color["image7"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image8"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i9">
                                            <img src="/upload/images/<?=$color["image8"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image9"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i10">
                                            <img src="/upload/images/<?=$color["image9"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                                <? if (@$color["image10"]!=''){ ?>
                                    <div>
                                        <a href="" class="fancybox-wrapper" data-id="i11">
                                            <img src="/upload/images/<?=$color["image10"]?>" alt="" />
                                        </a>
                                    </div>
                                <? } ?>
                            </div>
                            <? if (@$color["image"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i1">
                               </a>
                           <? } ?>
                           <? if (@$color["image1"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image1"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i2">
                               </a>
                           <? } ?>
                           <? if (@$color["image2"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image2"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i3">
                               </a>
                           <? } ?>
                           <? if (@$color["image3"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image3"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i4">
                               </a>
                           <? } ?>
                           <? if (@$color["image4"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image4"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i5">
                               </a>
                           <? } ?>
                           <? if (@$color["image5"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image5"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i6">
                               </a>
                           <? } ?>
                           <? if (@$color["image6"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image6"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i7">
                               </a>
                           <? } ?>
                           <? if (@$color["image7"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image7"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i8">
                               </a>
                           <? } ?>
                           <? if (@$color["image8"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image8"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i9">
                               </a>
                           <? } ?>
                           <? if (@$color["image9"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image9"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i10">
                               </a>
                           <? } ?>
                           <? if (@$color["image10"]!=''){ ?>
                               <a href="/upload/images/<?=$color["image10"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i11">
                               </a>
                           <? } ?>

                           <div class="slider slider-nav d-none">
                            <? if (@$color["image"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image1"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image1"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image2"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image2"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image3"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image3"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image4"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image4"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image5"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image5"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image6"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image6"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image7"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image7"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image8"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image8"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image9"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image9"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                            <? if (@$color["image10"]!=''){ ?>
                                <div>
                                    <div class="image">
                                        <img src="/upload/images/<?=$color["image10"]?>" alt="" />
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                        <div class="row align-items-center justify-content-center my-dots no-gutters">
                            <div class="col-auto">
                                <a href="" class="prev-slide"><img src="/upload/arr.svg" alt=""></a>
                            </div>
                            <div class="col-auto">
                                <div class="appendDots"></div>
                            </div>
                            <div class="col-auto">
                                <a href="" class="next-slide"><img src="/upload/arr.svg" style="transform:rotate(180deg)" alt=""></a>
                            </div>
                        </div>
                    <? }else{ ?>
                        <div class="slider slider-for">
                            <? if (@$e["image"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i1">
                                        <img src="<?=$e["image"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image1"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i2">
                                        <img src="<?=$e["image1"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image2"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i3">
                                        <img src="<?=$e["image2"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image3"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i4">
                                        <img src="<?=$e["image3"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image4"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i5">
                                        <img src="<?=$e["image4"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image5"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i6">
                                        <img src="<?=$e["image5"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image6"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i7">
                                        <img src="<?=$e["image6"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image7"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i8">
                                        <img src="<?=$e["image7"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image8"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i9">
                                        <img src="<?=$e["image8"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image9"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i10">
                                        <img src="<?=$e["image9"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                            <? if (@$e["image10"]!=''){ ?>
                                <div>
                                    <a href="" class="fancybox-wrapper" data-id="i11">
                                        <img src="<?=$e["image10"]["o"]?>" alt="" />
                                    </a>
                                </div>
                            <? } ?>
                        </div>
                        <? if (@$e["image"]!=''){ ?>
                           <a href="<?=$e["image"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i1">
                           </a>
                       <? } ?>
                       <? if (@$e["image1"]!=''){ ?>
                           <a href="<?=$e["image1"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i2">
                           </a>
                       <? } ?>
                       <? if (@$e["image2"]!=''){ ?>
                           <a href="<?=$e["image2"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i3">
                           </a>
                       <? } ?>
                       <? if (@$e["image3"]!=''){ ?>
                           <a href="<?=$e["image3"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i4">
                           </a>
                       <? } ?>
                       <? if (@$e["image4"]!=''){ ?>
                           <a href="<?=$e["image4"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i5">
                           </a>
                       <? } ?>
                       <? if (@$e["image5"]!=''){ ?>
                           <a href="<?=$e["image5"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i6">
                           </a>
                       <? } ?>
                       <? if (@$e["image6"]!=''){ ?>
                           <a href="<?=$e["image6"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i7">
                           </a>
                       <? } ?>
                       <? if (@$e["image7"]!=''){ ?>
                           <a href="<?=$e["image7"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i8">
                           </a>
                       <? } ?>
                       <? if (@$e["image8"]!=''){ ?>
                           <a href="<?=$e["image8"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i9">
                           </a>
                       <? } ?>
                       <? if (@$e["image9"]!=''){ ?>
                           <a href="<?=$e["image9"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i10">
                           </a>
                       <? } ?>
                       <? if (@$e["image10"]!=''){ ?>
                           <a href="<?=$e["image10"]["o"]?>" class="fancybox-link" data-fancybox="gallery" data-id="i11">
                           </a>
                       <? } ?>

                       <div class="slider slider-nav d-none">
                        <? if (@$e["image"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image1"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image1"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image2"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image2"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image3"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image3"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image4"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image4"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image5"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image5"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image6"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image6"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image7"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image7"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image8"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image8"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image9"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image9"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                        <? if (@$e["image10"]!=''){ ?>
                            <div>
                                <div class="image">
                                    <img src="<?=$e["image10"]["o"]?>" alt="" />
                                </div>
                            </div>
                        <? } ?>
                    </div>
                    <div class="row align-items-center justify-content-center my-dots no-gutters">
                        <div class="col-auto">
                            <a href="" class="prev-slide"><img src="/upload/arr.svg" alt=""></a>
                        </div>
                        <div class="col-auto">
                            <div class="appendDots"></div>
                        </div>
                        <div class="col-auto">
                            <a href="" class="next-slide"><img src="/upload/arr.svg" style="transform:rotate(180deg)" alt=""></a>
                        </div>
                    </div>
                <? } ?>
            </div>
            <div style="border:1px solid #000; border-top: 0px;" class="mb-5 d-none d-lg-block">
                <ul class="filter mt-5 kk">
                    <li>
                        <a href="">
                            <span class="text-uppercase">Вам также может понравиться</span>
                        </a>
                    </li>
                </ul>
                <div class="p-3 ">
                    <div class="row no-gutters justify-content-between">
                        <?
                        $sql = "select * from i_cat where active=1 and id_section=2 order by rand() limit 5";
                        $res = A::$db->get($sql);
                        foreach($res as $k => $r){
                            ?>
                            <?
                            $sql = "select * from i_block_elements where id='".$r["brand"]."'";
                            $brand = A::$db->get($sql, 1);
                            ?>
                            <div class="col-xl col-md-3 col-6 <?=$k==4?'d-none d-lg-none d-xl-block':''?>">

                                <div class="product-item">
                                    <a href="<?=LINK?>catalog/<?=$r["url"]?>/">
                                        <div class="image">
                                            <img src="/upload/images/small/<?=$r["image"]?>" alt="<?=$r["name"]?>" />
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

                                </div>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <h1 class="title"><?=$e["name"]?></h1>
            <div class="price" style="<?=($r["price_old"]>0?'color: #ff2a00;':'color: #000;')?>">
               <? if ($e["price_old"]>0){ ?>
                   <s><?=A::$api->price($e["price_old"], 1)?> ₸</s>
               <? } ?>
               <?=A::$api->price($e["price"], 1)?> ₸
           </div>

           <div class="art pt-4">
            Код: <?=$e["art"]?>
        </div>



        <div class="colors pt-4" data-id="">

            <?
            $sql = "select * from i_cat where id_section='".$e["id"]."' and active=1 group by color order by color asc";
            $res = A::$db->get($sql);
            foreach($res as $k=>$r){
                $sql = "SELECT * from i_block_elements where name='".$r["color"]."'";
                $color = A::$db->get($sql, 1);
                if ($k==0){ $current = $r["id"]; }
                ?>
                <div class="item <?=$k==0?'active':''?>" data-color='<?=$r["id"]?>' data-color1="<?=$r["name"]?>" data-id='<?=$e["id"]?>'>
                    <div style="background-color: <?=$color["color"]?>;"></div>
                </div>
            <? } ?>
        </div>
        <div class="sizes mt-4">
            <?
            $sql = "select * from i_cat_elements where id_section='".$current."'  and active=1 group by size order by id asc";
            $res = A::$db->get($sql);
            foreach($res as $k=>$r){
                ?>
                <div class="item <?=$r["kol"]==0?'disabled':''?>" data-id='<?=$e["id"]?>' data-size="<?=$r["size"]?>">
                    <?=$r["size"]?>
                </div>
            <? } ?>

        </div>
        <div class="buttons pt-4">
            <button class="btn btn-primary btn-lg basket" data-id="<?=$e["id"]?>">Добавить в корзину</button>
            <a class=" favorites ml-3 like-icon <?= A::$app->wishlist->check($e["id"]) ? 'active' : '' ?>" data-id="<?=$e["id"]?>">
                <img src="/upload/bookmark.svg">
            </a>
        </div>

        <div class="tabs pt-4">
            <ul class="about-ul">
                <li>
                    <a href="" class="active">
                        <span>О товаре</span>
                    </a>
                    <div class="info" style="display: block;">
                        <div class="anounce pb-4">
                            <?=$e["anounce"]?>
                        </div>
                        <?=$e["text"]?>
                    </div>
                </li>
                <li>
                    <a href="" class="">
                        <span><?=A::$app->wf(21, ["f"=>'name'])?></span>
                    </a>
                    <div class="info" >
                        <?=A::$app->wf(21, ["f"=>'text'])?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div style="border:1px solid #000; border-top: 0px;" class="mb-5 d-block d-lg-none">
    <ul class="filter mt-5 kk">
        <li>
            <a href="">
                <span class="text-uppercase">Вам также может понравиться</span>
            </a>
        </li>
    </ul>
    <div class=" p-3 ">
        <div class="row no-gutters justify-content-between">
            <?
            $sql = "select * from i_cat where active=1 and id_section=2 order by rand() limit 5";
            $res = A::$db->get($sql);
            foreach($res as $k => $r){
                ?>
                <?
                $sql = "select * from i_block_elements where id='".$r["brand"]."'";
                $brand = A::$db->get($sql, 1);
                ?>
                <div class="col-xl col-md-3 col-6 <?=$k==4?'d-none d-lg-none d-xl-block':''?>">

                    <div class="product-item">
                        <a href="<?=LINK?>catalog/<?=$r["url"]?>/">
                            <div class="image">
                                <img src="/upload/images/small/<?=$r["image"]?>" alt="<?=$r["name"]?>" />
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

                    </div>
                </div>
            <? } ?>
        </div>
    </div>
</div>
</div>
<style type="text/css">
.filter.kk>li>a:after{
    display: none;
}
</style>

</div>
</div>