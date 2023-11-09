<?php

use Exception;

final class Payment
{

    public $data;

    protected $sign;
    protected $url;

    private $key = '7b8a8310-ddb3-013b-09c6-0645dcfd0614';
    private $secret = '16a53a2acd2dd01642dfad3164d1790e2e7c417cac442bd4';

    const PAY = 'https://1vision.app/pay';
    const PAY_STATUS = 'https://1vision.app/pay/get_orders_data';

    private static $instance = null;

    public static function init()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function setData($data, $url)
    {
        if (empty($data) || !is_array($data)) {
            throw new Exception("Массив с параметрами не должен быть пустым");
        }

        if (empty($url)) {
            throw new Exception("Вы не указали адрес запроса");
        }

        $data['api_key'] = $this->key;

        $date = new DateTime();
        $date->modify('+1 day');

        $data['expiration'] = $date->format('Y-m-d H:i:s');
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        $data['currency']  = 'KZT';


        $this->data = base64_encode(json_encode($data));
        $this->sign = hash_hmac('md5', $this->data, $this->secret);
        $this->url = $url;

        return $this;
    }


    public function send()
    {

        $data = $this->data;
        $sign = $this->sign;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . $data . '&sign=' . $sign);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen('data=' . $data . '&sign=' . $sign)
            )
        );
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, 1);
    }

    public static function parseResponseData($data)
    {
        $data = json_decode(base64_decode($data));
        return $data;
    }
}
