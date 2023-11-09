<?
include_once(dirName(__FILE__) . "/admin/modules/general/mysql.php");
include_once(dirName(__FILE__) . "/libs/Payment.php");


exit;

$data = [
    'amount' => 10,
    'reference' => '3',
    'description' => 'Оплата заказа №3 на сайте joi.kz',
    'success_url' => 'https://joi.kz/payment-success/?id=3',
    'failure_url' => 'https://joi.kz/payment-failure/?id=3',
    'params' => [
        'flag_get_url' => 1,
        'user_id' => 1,
        'user_phone' => '+71111111111'
    ]
];

$result = Payment::init()->setData($data, Payment::PAY)->send();

$data = Payment::parseResponseData($result["data"]);

if (isset($data->url)){
    header("Location: ".$data->url);
    exit;
}


$data = [
    'reference' => '1',
];

$result = Payment::init()->setData($data, Payment::PAY_STATUS)->send();

$data = Payment::parseResponseData($result["data"]);

vd($data->items[0]->status);
exit;

exit;




$from = 'ru';
$to = 'kz';
$modules = array(
    //'i_cat',
    //'i_cat_elements',
    //'i_block',
    //'i_block_elements',
    'i_gallery',
    //'i_gallery_elements',
    //'i_guest',
    //'i_menu',
    //'i_news',
    //'i_news_elements',
    //'i_page',
    //'i_slider',
    //'i_slider_elements',
    //'i_quest',
);
foreach ($modules as $v) {
    $sql = "select * from $v where version='$from' and id_section=0";
    $res = $mysql->get($sql);
    foreach ($res as $key => $r) {
        duplicate($v, 'id', $r["id"], $lang = $to);
    }
}
