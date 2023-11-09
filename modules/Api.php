<?
class Api
{

    private static $_instance;

    public static function getInstance() 
    {
        if(!self::$_instance) { 
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {

    }

    public function __destruct()
    {

    }

    public function date($lang, $str, $type_from, $type_to)
    {
        $conv_date = '';
        $lang_mass = array(
            'ru' => array(
                'at'     => 'в',
                'mounth' => array(
                    '01' => 'января',
                    '02' => 'февраля',
                    '03' => 'марта',
                    '04' => 'апреля',
                    '05' => 'мая',
                    '06' => 'июня',
                    '07' => 'июля',
                    '08' => 'августа',
                    '09' => 'сентября',
                    '10' => 'октября',
                    '11' => 'ноября',
                    '12' => 'декабря'),
            ),
            'en' => array(
                'at'     => 'at',
                'mounth' => array(
                    '01' => 'january',
                    '02' => 'february',
                    '03' => 'march',
                    '04' => 'april',
                    '05' => 'may',
                    '06' => 'june',
                    '07' => 'july',
                    '08' => 'august',
                    '09' => 'september',
                    '10' => 'october',
                    '11' => 'november',
                    '12' => 'december'),
            ),

            'kz' => array(
                'at'     => '',
                'mounth' => array(
                    '01' => 'қаңтар',
                    '02' => 'ақпан',
                    '03' => 'наурыз',
                    '04' => 'сәуір',
                    '05' => 'мамыр',
                    '06' => 'маусым',
                    '07' => 'шілде',
                    '08' => 'тамыз',
                    '09' => 'қыркүйек',
                    '10' => 'қазан',
                    '11' => 'қараша',
                    '12' => 'желтоқсан'),
            ),
        );

        # Если из SQL формата
        if ($type_from == 'sql') {
            $date_time = explode(' ', $str);
            $date      = explode('-', $date_time[0]);
            $time      = explode(':', $date_time[1]);

            # Обычный тип даты
            if ($type_to == 'date') {
                $conv_date = $date[2] . '.' . $date[1] . '.' . $date[0];
            }

            # Текстовая дата
            if ($type_to == 'datetext') {
                $conv_date = $date[2] . ' ' . $lang_mass[$lang]['mounth'][$date[1]] . ' ' . $date[0];
            }

            # Дата и время
            if ($type_to == 'datetime') {
                $conv_date = $date[2] . '.' . $date[1] . '.' . $date[0] . ' ' . $lang_mass[$lang]['at'] . ' ' . $time[0] . ':' . $time[1];
            }

            # Текстовые дата и время
            if ($type_to == 'datetimetext') {
                if (substr($date[2], 0, 1) == 0) {$date[2] = substr($date[2], 1);}
                if (substr($time[0], 0, 1) == 0) {$time[0] = substr($time[0], 1);}
                $conv_date = $date[2] . ' ' . $lang_mass[$lang]['mounth'][$date[1]] . ' ' . $date[0] . ' ' . $lang_mass[$lang]['at'] . ' ' . $time[0] . ':' . $time[1];
            }
        }

        # Из обычного формата
        if ($type_from == 'date') {
            $date_time = explode('.', $str);

            # SQL
            if ($type_to == 'sql') {
                $conv_date = $date_time[2] . '-' . $date_time[1] . '-' . $date_time[0];
            }

        }

        return $conv_date;
    }

    public function mime($str, $data_charset = 'utf-8', $send_charset = 'utf-8')
    {
        if ($data_charset != $send_charset) {
            $str = iconv($data_charset, $send_charset, $str);
        }

        return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
    }

    public function sklon($num, $arr)
    {

        if ($num == 1) {
            return $arr[0];
        } else if ($num >= 2 && $num <= 4) {
            return $arr[1];
        } else if (($num >= 5 && $num <= 19) or $num == 0) {
            return $arr[2];
        } else {
            $num1 = substr($num, -1, 1);
            $num2 = substr($num, -2, 1);
            if ($num2 == 1) {
                return $arr[2];
            } else if ($num1 == 1) {
                return $arr[0];
            } else if ($num1 >= 2 && $num1 <= 4) {
                return $arr[1];
            } else if (($num1 >= 5 && $num1 <= 9) or $num1 == 0) {
                return $arr[2];
            }
        }
    }

    public function price($price, $f = 0)
    {
        $kurs = 1;
        $curr = explode("\n", @A::$app->catalog->config["currency"]);
        foreach ($curr as $v){
            $e = explode(' - ', $v);
            if (isset($_SESSION["currency"]) && $_SESSION["currency"]==$e[0]){
                $kurs   = floatval(str_replace(',','.',str_replace(' ', '' , $e[1])));
            }
        }

        $price = $price / $kurs;

        if ($f == 0) {
            if ($kurs>1){
                return number_format($price, '2', '.', '');
            }else{
                return number_format($price, '0', '.', '');
            }
        } else {
            if ($kurs>1){
                return number_format($price, '2', '.', ' ');
            }else{
                return number_format($price, '0', '.', ' ');
            }
        }
    }

    public function adress($id)
    {
        $sql = "select a.name as aname, b.name as bname from i_block a left join i_block b on a.id=b.id_section where b.id='".$id."' ";
        $res = A::$db->get($sql);
        if (sizeof($res)>0){
            return $res[0]["aname"].', '.$res[0]["bname"];
        }else{
            return '';
        }
    }



    public function sbt($s, $length){
        $s = trim(strip_tags($s));
        $max_length = $length;
        if (strlen($s) > $max_length){
            $offset = ($max_length - 3) - strlen($s);
            $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
        }

        return $s;
    }

    function format($str, $type = 'phone')
    {
        switch($type){
            case 'phone':
            $phone = ['+7'];
            $phone[] = '('.mb_substr($str,1,3).')';
            $phone[] = mb_substr($str,4,3).'-'.mb_substr($str,7,2).'-'.mb_substr($str,9,2);
            return join(' ', $phone);
            break;
            case 'number':
            return number_format($str, 0, '.', ' ');
            break;
        }

    }

    public function sendSMS($phone, $text)
    {
        $link = 'https://smsc.kz/sys/send.php?login='.urlencode('kalkamanov.work').'&psw='.urlencode('12345outfit').'&phones='.$phone.'&mes='.urlencode($text);
        $send = file_get_contents($link);

        return $send;
    }
}