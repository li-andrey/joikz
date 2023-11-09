<?php

class User extends Module
{

    public $table = 'i_shop_group';
    public $tableE = 'i_shop_users';

    protected $user;
    protected $data;

    private static $_instance;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
        $this->path = realpath(dirname(__FILE__));
        $this->configLoad();
        $this->auth();
    }

    public function __get($property)
    {
        if (isset($this->user[$property])) {
            return $this->user[$property];
        } else {
            return '';
        }
    }


    public function breadcrumb($title)
    {
        $breadcrumb = new breadcrumb();
        return $breadcrumb->init($this->table, $this->tableE, $this->link, $title);
    }

    public function init()
    {
        if (@A::$url->url[1] != '') {
            $method = A::$url->url[1];

            if (method_exists($this, $method.'Action')) {
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method.'Action'], []);
                    $this->after();
                }
            } else {
                $this->page404(array());
                exit;
            }
        }
    }


    public function auth()
    {
        if (!isset($_COOKIE["uid"])) {
            return false;
        }
        $sql = "select * FROM `i_shop_users` WHERE `active`='1' AND `id`='" . $_COOKIE["uid"] . "'  LIMIT 1";
        $result = $this->sql->get($sql, 1);

        if (@$result["id"] > 0) {
            $this->user = $result;
            return true;
        }
        return false;
    }

    public function check($login, $pass)
    {
        $sql = "select id FROM `i_shop_users` WHERE `active`='1' AND (`login`='" . $login . "' or phone='" . $login . "') AND `password`='" . sha1($pass) . "'  LIMIT 1";

        $result = $this->sql->get($sql, 1);

        if ($login != '' && $pass != '' && @$result["id"] > 0) {
            return true;
        }

        return false;
    }

    public function login($login, $pass, $response = true)
    {
        if ($this->check($login, $pass)) {

            $sql = "select * FROM `i_shop_users` WHERE `active`='1' ";
            $sql .= "AND (`login`='" . $login . "' or phone='" . $login . "') AND `password`='" . sha1($pass) . "'  LIMIT 1";

            $result = $this->sql->get($sql, 1);

            $this->user = $result;

            setcookie(
                "uid",
                $this->id,
                time() + 7776000,
                '/',
                str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"])
            );
            if ($response) {
                $this->success(t('Вы успешно авторизовались'));
            }

        } else {
            if ($response) {
                $this->error(t('Неверный телефон или пароль'));
            }

        }
    }

    public function logout()
    {
        if ($this->auth()) {
            $this->user = '';
            setcookie(
                "uid",
                '',
                time() - 7776000,
                '/',
                str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"])
            );
            return true;
        }

        return false;
    }

    public function checkRegister($phone, $type)
    {
        if ($phone != '') {
            if ($type==1){
                $sql = "select * from i_shop_users where phone='" . $phone . "' limit 1";
            } else if ($type==2){
                $sql = "select * from i_shop_users where login='" . $phone . "' limit 1";
            }
            $res = $this->sql->get($sql, 1);

            if (@$res["id"] > 0) {
                return false;
            }
        }

        return true;
    }


    public function canRequest($phone)
    {
        $sql = "select * from i_user_activation_sms where phone='" . $phone . "' and done=0 order by id desc limit 1";
        $res = A::$db->get($sql);
        if (sizeof($res) == 0) {
            return true;
        } else {
            if (time() - strtotime(@$res[0]["data"]) >= 60) {
                return true;
            }
        }

        return false;
    }

    public function sendSmsCode($phone, $msg='- код подтверждения')
    {
        if ($this->canRequest($phone)) {

            $sql = "select * from i_shop_users where phone='" . $phone . "' limit 1";
            $res = $this->sql->get($sql, 1);

            if (@$res["id"] == 0) {
                $this->error(t('Данный номер не зарегистрирован'));
                return false;
            }

            $_SESSION["code_sms"] = rand(1000, 9999);
            $sql = "insert into i_user_activation_sms set phone='" . $phone . "', code='" . $_SESSION["code_sms"] . "', ";
            $sql .= "done=0, data='" . date('Y-m-d H:i:s') . "'";
            $i = $this->sql->query($sql);
            if ($i) {

                $smsText = "https://".$_SERVER['HTTP_HOST']."/ - " . $_SESSION["code_sms"] . " " . $msg;
                //A::$api->sendSMS($phone, $smsText);

                $this->setData(["code" => $smsText]);
                $this->success(t('Код подтверждения отправлен на указанный номер телефона'));

            } else {
                $this->error(t('Error: ') . $this->sql->error);
            }
        } else {
            $this->error(t('Вы можете отправить запрос через минуту'));
        }
    }

    public function checkSmsCode($phone, $code)
    {
        if (isset($_SESSION["code_sms"]) && $_SESSION["code_sms"] == $code) {
            $pass = rand(1000, 9999);

            $sql = "update i_shop_users set password='".sha1($pass)."' where phone='".$phone."'";
            A::$db->query($sql);

            $smsText = "https://".$_SERVER['HTTP_HOST']."/ - " . $pass . " - ваш новый пароль" ;
            //A::$api->sendSMS($phone, $smsText);

            $this->setData(['code' => $smsText]);
            $this->success(t('Вам выслан новый пароль'));
        } else {
            $this->error(t('Код неверный'));
        }
    }

    public function registerAction()
    {
        $data = [];
        $path = '';
        $view = 'register';

        $this->viewStatic($view, $data, $path);
    }

    public function loginAction()
    {
        $data = [];
        $path = '';
        $view = 'login';

        $this->data["meta"] = [
            'title' => 'Авторизация',
            'h1' => 'Авторизация',
            'description' => '',
            'keywords' => '',
        ];

        $this->view($view, $this->data, $path);
    }

    public function forgotAction()
    {
        $data = [];
        $path = '';
        $view = 'forgot';

        $this->viewStatic($view, $data, $path);
    }

    public function wishlistAction()
    {
        $data = [];
        $path = '';
        $view = 'wishlist';

        $this->data["meta"] = [
           'title' => 'Список желаний',
           'h1' => 'Список желаний',
           'description' => '',
           'keywords' => '',
       ];



       $this->view($view, $this->data, $path);
   }

   public function historyAction()
   {
    $data = [];
    $path = '';
    $view = 'history';

    $this->viewStatic($view, $data, $path);
}

public function add()
{

    $error = '';

    $phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);


    if (!$this->checkRegister($phone, 1)){
        $error = t('Пользователь с данным номер уже зарегистрирован');
    }

    if (!$this->checkRegister(@$_POST["email"], 2)){
        $error = t('Пользователь с данным e-mail уже зарегистрирован');
    }

    if (@$_POST["password"] != '' && strlen(@$_POST["password"]) < 6) {
        $error = t('Пароль должен содержать не менее 6 символов');
    }

    if (@$_POST["password"]  != '' && @$_POST["password"]  != @$_POST["password1"] ) {
        $error = t('Пароли не совпадают');
    }

    if ($error == ''){

        $sql = "insert into i_shop_users set ";
        $sql .= "id_section='1', ";
        $sql .= "version='ru', ";
        $sql .= "active='1', ";
        $sql .= "name='" . @$_POST["name"] . "', ";
        $sql .= "login='" . @$_POST["email"] . "', ";
        $sql .= "mail='" . @$_POST["mail"] . "', ";
        $sql .= "phone='" . @$phone . "', ";
        $sql .= "password='" . sha1($pin) . "' ";

        $i = A::$db->query($sql);
        if ($i) {
            $uid = A::$db->insert_id;

            $sql = "update i_shop_users set url='" . translit($_POST["name"]) . "-" . $uid . "', data='" . date('Y-m-d H:i:s') . "' ";
            $sql.= "where id='" . $uid . "'";
            A::$db->query($sql);

            alias($uid, 'shop_users', translit($_POST["name"]) . "-" . $uid, 0);

            $smsText = "https://".$_SERVER['HTTP_HOST']."/ - " . $pin . " - ваш пинкод" ;
                //A::$api->sendSMS($phone, $smsText);

            $this->login($_POST['email'], $_POST['password'], 0);

            $this->success(t('Вы успешно зарегистрировались'));

        } else {
            $this->error(t('Error: ') . A::$db->error);
        }
    }else {
        $this->error($error);
    }

}


public function edit()
{

    $error = '';



    if (isset($_POST["email"]) && $_POST["email"] != '') {
        $sql = "select * from i_shop_users where login='" . $_POST["email"] . "' and id!='" . A::$app->user->id . "' limit 1";
        $res = $this->sql->get($sql, 1);

        if (@$res["id"] > 0) {
            $this->error(t('Данный e-mail уже зарегистрирован'));
            return false;
        }
    }

    if (isset($_POST["phone"]) && $_POST["phone"] != '') {

        $phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);

        $sql = "select * from i_shop_users where phone='" . $phone . "' and id!='" . A::$app->user->id . "' limit 1";
        $res = $this->sql->get($sql, 1);

        if (@$res["id"] > 0) {
            $this->error(t('Данный телефон уже зарегистрирован'));
            return false;
        }
    }
    if (isset($_POST["password"]) && $_POST["password"] != '') {

        if ($_POST["password"] != '' && strlen(@$_POST["password"]) < 6) {
            $this->error(t('Пароль должен содержать не меннее 6 символов'));
            return false;
        }

        if (@$_POST["password"] != '' && $_POST["password"] != $_POST["password1"]) {
            $this->error(t('Пароли не совпадают'));
            return false;
        }
    }

    if ($error == ''){

        $sql = "update i_shop_users set ";
        $sql.= "login='".($_POST["email"])."', ";
        $sql.= "mail='".($_POST["email"])."', ";
        if ($_POST["password"]!=''){
            $sql.= "password='".sha1($_POST["password"])."', ";
        }
        $sql.= "name='".($_POST["name"])."', ";
        $sql.= "fam='".($_POST["fam"])."', ";
        $sql.= "phone='".($_POST["phone"])."' ";
        $sql.= "where id='".A::$app->user->info["id"]."' ";

        $i = A::$db->query($sql);
        if ($i) {

            $this->success(t('Данные успешно сохранены'));

        } else {
            $this->error(t('Error: ') . A::$db->error);
        }
    }else {
        $this->error($error);
    }

}



public function forgot($login)
{
    $sql = "select * from i_shop_users where mail='".$_POST["email"]."' limit 1";
    $res = A::$db->get($sql, 1);
    if (@$res["id"]>0){
        $id = md5(uniqid(rand(),true));
        $new_psw = substr($id,0,8);
        $sql = "update i_shop_users set password='".sha1($new_psw)."' where mail='".$_POST["email"]."'";
        A::$db->query($sql);
    }else{
        $error = 'Пользователь с таки e-mail не найден';
    }

    $headers="Content-type: text/html; charset=utf-8\n";
    $configFrom = ML::param('E-mail отправителя');
    $headers.="From: " .  $configFrom . " <" . $configFrom  . ">\n";

    $message='<html>
    <body>
    Отправлено: ' . date('d.m.Y') . ' в ' . date('h:i') . ' с IP ' . $_SERVER['REMOTE_ADDR'] . '<br/>
    <br/>
    <h2>Восстановление пароля на сайте ' . $_SERVER['HTTP_HOST'] . '.</h2>
    <p><b>E-mail:</b> '.$_POST["email"].'</p>
    <p><b>Пароль:</b> '.@$new_psw.'</p>
    </body>
    </html>';
    $mail_to   =  Array();
    $mail_to[] = $_POST["email"];

    for($i=0; $i<sizeof($mail_to); $i++){
        if ($error=='') {
            $send = mail($mail_to[$i], ('Восстановление пароля на сайте ' . $_SERVER['HTTP_HOST']), $message, $headers);
        }
    }

    if ($send && $error==''){
        $this->success(t('Новый пароль выслан на указанные e-mail'));
    }else{
        $this->error($error);
    }
}
}