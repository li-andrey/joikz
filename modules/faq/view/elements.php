<form id="faqSearch" action="/about/faq/search/">
    <span class="faq-search icon-magnify"></span>
    <div class="form-group">
        <input type="text" class="form-control" id="faqSearchValue" name="q" maxlength="50">
    </div>
    <h1 class="page-title"><?=$block["name"]?></h1>
</form>
<? if (sizeof($elements)>0){ ?>

    <div class="panel-group" id="faq-accordion" role="tablist" aria-multiselectable="true">
	<?
	$i = 1;
	foreach($elements as $k=>$r){
	?>
    <a name="quest<?=$r["id"]?>"></a>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#faq-accordion"
                       href="#question<?=$r["id"]?>"
                       aria-expanded="<?=$k==0?'true':'false'?>"
                       aria-controls="question<?=$r["id"]?>">
                        <?=$r["quest"]?>                 <span class="icon-arrow_grey_right"></span>
                    </a>
                </h4>
            </div>
            <div id="question<?=$r["id"]?>"
                 class="panel-collapse collapse <?=$k==0?'in':''?>"
                 role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <?=$r["answer"]?>
                </div>
            </div>
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