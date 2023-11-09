<? if (@$this->config['show_info_block']==1){ ?>
    <?=$this->viewJSON('info', array('block'=>$block))?>
<? } ?>

<? if (@$this->config['show_sub_block']==1){ ?>
    <?=$this->viewJSON('sub_category', array('subBlock'=>$subBlock))?>
<? } ?>

<? if (sizeof($elements)>0){ ?>
    <div class="dt-sc-portfolio-container with-space">
        <div class="grid-sizer  portfolio column dt-sc-one-third all-sort"></div>
        <?
        $i = 1;
        foreach ($elements as $r){ ?>
            <div id="dt_portfolios-<?=$r["id"]?>" class="type7 with-space portfolio column dt-sc-one-third all-sort first decor-sort">
                <a href="/upload/images/<?=$r["image"]?>" rel="gallery" title="">
                    <figure> <img src="/upload/images/big/<?=$r["image"]?>" alt="" title="" />
                        <div class="image-overlay">

                            <div class="image-overlay-details">
                                <h2><?=$r["name"]?></h2>

                            </div>
                        </div>
                    </figure>
                </a>
            </div>

            <?
            if($i==4) $i=0;
            $i++;
        }
        ?>
    </div>
    <? } ?>