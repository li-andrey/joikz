<? 
$this->meta(
    array(
        'h1' => 'Ваша корзина',
        'title' => 'Ваша корзина',
        'descr' => '',
        'keyw' => '',
    )
);
$this->header();
?>

<style type="text/css">
    .page-header{
        display: none;
    }
</style>

<div class="portfolio">
    <div class="container">
        <div class="row work-page">
            <div class="col-md-8">
                <div >
                    <div id="basket_protocol" class="protocol"></div>
                    <div id="basket_place" class="card card-block" ></div>
                     <a href="/" class="btn btn-link" style="margin-top: 0rem"><i class="fa fa-angle-left" aria-hidden="true"></i> Вернуться к покупкам</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-block" >
                    <div class="order-info" style="margin-top: 5px;">
                        
                        <div class="row" style="margin-bottom: 1rem">
                            <div class="col-xs-8 col-md-8">Стоимость</div>
                            <div class="col-xs-4 col-md-4" style="text-align: right;"><b id="itogo"></b></div>
                        </div>
                        <div class="row" style="margin-bottom: 1rem">
                            <div class="col-xs-8 col-md-8">Доставка</div>
                            <div class="col-xs-4 col-md-4" style="text-align: right;"><b id="dost">-</b></div>
                        </div>
                       
                        <hr style="margin: 25px -20px;">
                        <div class="row" style="margin-bottom: 2rem">
                            <div class="col-xs-8 col-md-8">Итого</div>
                            <div class="col-xs-4 col-md-4" style="text-align: right;"><b id="amount"></b></div>
                        </div>
                        <div align="center">
                            <a href="/order/" class="btn btn-filled btn-big">Оформить заказ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<? $this->footer() ?>