<?
include_once(dirName(__FILE__) . "/admin/modules/general/mysql.php");


function updateGood($item)
{

    $sql = "select * from i_cat_elements where art='" . iconv("windows-1251", "UTF-8", $item[0]) . "'";
    $goods = A::$db->get($sql);

    if (sizeof($goods) > 0) {

        foreach ($goods as $good) {
            $sql = "update i_cat_elements set ";
            $sql .= "kol='" . $item[3] . "', ";
            $sql .= "active='" . ($item[3] > 0 ? '1' : '0') . "', ";
            $sql .= "price='" . $item[2] . "' where id='" . $good["id"] . "'  ";

            echo $sql . '<br>';

            A::$db->query($sql);

            $sql = "select * from i_cat where id='" . $good["id_section"] . "'";
            $cat = A::$db->get($sql, 1);

            if ($cat["id_section"] != 2) {
                $sql = "select * from i_cat where id='" . $cat["id_section"] . "'";
                $cat = A::$db->get($sql, 1);
            }

            $sql = "update i_cat set ";
            $sql .= "active='1' ";
            $sql .= "where id='" . $cat["id"] . "'  ";

            echo $sql . '<br>';

            A::$db->query($sql);
        }
    }
    $sql = "select id from i_cat where art='" . iconv("windows-1251", "UTF-8", $item[0]) . "' and id_section=2";
    $good = A::$db->get($sql, 1);

    if (isset($good["id"])) {
        $sql = "update i_cat set ";
        $sql .= "kol='" . $item[3] . "', ";
        $sql .= "active='" . ($item[3] > 0 ? '1' : '0') . "', ";
        $sql .= "price='" . $item[2] . "' where id='" . $good["id"] . "'  ";

        echo $sql . '<br>';

        A::$db->query($sql);
    }
}

$sql = "update i_cat set active=0 where id_section=2 and art!='' and art is not null";
$good = A::$db->query($sql);

$sql = "update i_cat_elements set active=0 where art!='' and art is not null";
$good = A::$db->query($sql);

$handle = fopen('../NomenklaturaFTP.csv', 'r');
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    updateGood($data);
}

$sql = "select id from i_cat where id in (select id_section from i_cat_elements where active=0 and art!='' and art is not null ) group by id";
$colors = A::$db->get($sql);
$in = 0;
foreach ($colors as $color) {
    $sql = "select count(id) as kol from i_cat_elements where active=1 and id_section='" . $color['id'] . "' and art!='' and art is not null";
    $items = A::$db->get($sql, 1);

    if ($items["kol"] > 0) {
        $sql = "update i_cat set active=1 where id='" . $color["id"] . "'";
        A::$db->query($sql);
    } else {
        $sql = "update i_cat set active=0 where id='" . $color["id"] . "'";
        A::$db->query($sql);
    }
}
