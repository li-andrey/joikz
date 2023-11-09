<?
include_once(dirName(__FILE__)."/admin/modules/general/mysql.php");

$sql = "select * from i_cat where active=1 and id_section=94";
$res = A::$db->get($sql);

$fp = fopen('file.csv', 'w');
$goods = [
    'id',
    'brand',
    'title',
    'description',
    'availability',
    'condition',
    'price',
    'link',
    'image_link',
];
fputcsv($fp, $goods);
foreach ($res as $key => $r) {
    $sale = 0;
    
    if ($r["metka"] > 0) {
        $sql = "select id,sale, name,name2 from i_block_elements where id='" . $r["metka"] . "' limit 1";
        $metka = A::$db->get($sql, 1);
        
        if (@$metka["id"] > 0) {
            $sale = $metka["sale"];
            $e["price_old"] = $r["price"];
        }
    }
    
    $r["price"] = $r["price"] - ($r["price"] * $sale / 100);
    $goods = [
        $r["id"],
        'egelge',
        $r["name"],
        $r["anounce"]!=''?trim(str_replace("\n", " ", $r["anounce"])):$r["name"],
        $r["kol"]>0?'in stock':'out off stock',
        'new',
        price($r["price"]).' RUB',
        'https://egelge.com/catalog/'.$r["url"].'/',
        'https://egelge.com/upload/images/'.$r["image"],
    ];
    fputcsv($fp, $goods);
}
fclose($fp);