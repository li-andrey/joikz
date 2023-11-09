<? if (sizeof($subBlock)>0){ ?>
<div class="dt-sc-portfolio-container with-space">
    <div class="grid-sizer  portfolio column dt-sc-one-third all-sort"></div>
    <?
	$i = 1;
	foreach ($subBlock as $r){ ?>
    <div id="dt_portfolios-<?=$r["id"]?>" class="type7 with-space portfolio column dt-sc-one-third all-sort first decor-sort">
        <figure> <img src="/upload/images/big/<?=$r["image"]?>" alt="" title="" />
            <div class="image-overlay">
                <div class="links"> <a style="margin-left: 40px;" title="" href="<?=A::$app->link().''.$this->url.'/'.$r["url"].'/'?>"> <span class="icons icon-link"> </span> </a> </div>
                <div class="image-overlay-details">
                    <h2><a href="<?=A::$app->link().''.$this->url.'/'.$r["url"].'/'?>" title=""><?=$r["name"]?></a></h2>
                    
                </div>
            </div>
        </figure>
    </div>
    
    <?
	if($i==4) $i=0;
 	$i++;
	}
	?>
 </div>
<? } ?>