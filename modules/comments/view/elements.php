<? if (sizeof($elements)>0){ ?>
  <div class="row">
   <?
   $i = 1;
   foreach($elements as $r){
     ?>
     <a name="guest<?=$r["id"]?>"></a>


     <div class="product-layout  col-xs-12 product-layout">

      <p></p>
      <blockquote>

        <p style="margin-bottom: 1rem"><strong><?=$r["name"]?></strong> <?=A::$api->date(LANG, $r["guest_date"], 'sql', 'datetimetext')?></p>
        <p><?=$r["com"]?></p>
      </blockquote>
    </div>
    <?	
    if ($i==3) $i=0;
    $i++;
  } 
  ?>
</div>

<? }else{ ?>
  <p align="center"><strong><?=$this->t("no_element")?></strong></p>
<? } ?> 


