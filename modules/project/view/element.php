
<?
$fon = A::$app->wf(42, 'block_elements');
$img = @$fon["image"];
if ($e["image1"]!=''){
    $img = $e["image1"];
}
?>

<div class="dlab-bnr-inr overlay-black-middle text-center bg-pt" style="background-image:url(/upload/images/<?=$img?>);">
    <div class="container">
        <div class="dlab-bnr-inr-entry align-m text-center">
            <h1 class="text-white"><?=$e["name2"]?></h1>
            <div class="breadcrumb-row">
                <ul class="list-inline">
                    <?=A::$app->breadcrumb($e["name2"])?>
                </ul>
            </div>
        </div>
    </div>
</div>
<section class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4><?=$e["name"]?></h4>
                <?
                $t = str_replace(array('btgrid'), ' ', str_replace(array('<p'), '<p ', str_replace(array('<li>'), '<li> ', $e["text"])));
                
                $t = str_replace(array('<ul>'), '<ul> ', $t);
                echo $t;
                ?>
            </div>
        </div>
    </div>
</section>