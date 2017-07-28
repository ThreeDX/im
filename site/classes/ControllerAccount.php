<?php

class ControllerAccount extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        if (isset($_GET['action'])) {
            // Logout
            if ($_GET['action'] == "logout") {
                if ($this->ses_helper->get_session_status() == SesHelper::SESSION_INITED)
                    Account::logout();
                header("Location: index.php");
                return false;
            }
            //Login
            if ($_GET['action'] == "login") {
                // Переданы данные для авторизации
                if (isset($_POST['email']) && isset($_POST['pass']) && strlen($_POST['email']) > 0 && strlen($_POST['pass']) > 0) {
                    if ($this->ses_helper->get_session_status() != SesHelper::SESSION_INITED && !Account::auth($_POST['email'], $_POST['pass'])) {
                        // Ошибка авторизации
                        if(isset($_GET['r']))
                            $this->data['content'] = "step1";
                        else
                            $this->data['content'] = "login";
                        $this->data['email'] = $_POST['email'];
                        $this->data['cats'] = Categories::all();
                        $this->data['message'] = "Пользователь с такими учетными данными не найден.";
                        return true;
                    }
                    if(isset($_GET['r']))
                        header("Location: index.php?page=Order");
                    else
                        header("Location: index.php");
                    return false;
                }
            }
            //Registration
            if ($_GET['action'] == "registration") {
                if ($this->ses_helper->get_session_status() == SesHelper::SESSION_INITED) {
                    if(isset($_GET['r']))
                        header("Location: index.php?page=Order");
                    else
                        header("Location: index.php?page=Account");
                    return false;
                }
                // Переданы данные для авторизации
                if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass1']) && isset($_POST['pass2']) && strlen($_POST['email']) > 0 && strlen($_POST['pass1']) > 0 && strlen($_POST['name']) > 0) {
                    if ($_POST['pass1'] != $_POST['pass2'])
                        $this->data['message'] = "Пароли не совпадают.";
                    else if (Users::get("email", $_POST['email'])!== false)
                        $this->data['message'] = "Пользователь с таким email уже зарегистрирован.";
                    else {
                        $user = User::create(array('id' => "new", 'email' => $_POST['email'], 'name' => $_POST['name'], 'password' => $_POST['pass1']));
                        if ($user !== false && $user->update($_POST) !== false) {
                            Account::auth($_POST['email'], $_POST['pass1']);
                            if(isset($_GET['r']))
                                header("Location: index.php?page=Order&action=step2");
                            else
                                header("Location: index.php?page=Account");
                            return false;
                        }
                        $this->data['message'] = "Ошибка создания пользователя.";
                    }
                }
                if(isset($_GET['r']))
                    $this->data['content'] = "step1";
                else
                    $this->data['content'] = "register";
                if (isset($_POST['email']))
                    $this->data['email'] = $_POST['email'];
                if (isset($_POST['name']))
                    $this->data['name'] = $_POST['name'];
                if (isset($_POST['phone']))
                    $this->data['phone'] = $_POST['phone'];
                $this->data['cats'] = Categories::all();
                return true;
            }

            // Save user
            if ($_GET['action'] == "save" && $this->ses_helper->get_session_status() == SesHelper::SESSION_INITED) {
                $user = Users::get('id', $this->ses_helper->get_user_id());
                if ($user !== false) {
                    $us = $user->get_all();

                    if (isset($_POST['email']) && $_POST['email'] != $us['email'] && Users::get("email", $_POST['email'])!== false)
                        $this->data['message'] = "Пользователь с таким email уже зарегистрирован.";
                    else if(!isset($_POST['phone']) || (isset($_POST['phone']) && strlen($_POST['phone']) < 4))
                        $this->data['message'] = "Некорректный телефон.";
                    else {
                        $user->update($_POST);

                        if(isset($_GET['r']))
                            header("Location: index.php?page=Order&action=step2");
                        else
                            header("Location: index.php?page=Account");
                        return false;
                    }
                } else
                    $this->data['message'] = "Ошибка при сохранении данных.";
            }
        }

        $this->data['cats'] = Categories::all();

        if(isset($_GET['r'])) {
            $this->data['content'] = "step1";
            if (isset($_POST['email']))
                $this->data['email'] = $_POST['email'];
            if (isset($_POST['name']))
                $this->data['name'] = $_POST['name'];
            if (isset($_POST['phone']))
                $this->data['phone'] = $_POST['phone'];
            return true;
        }

        // Неавторизованный пользователь хочет зайти в аккаунт
        if ($this->ses_helper->get_session_status() != SesHelper::SESSION_INITED) {
            $this->data['content'] = "login";
            return true;
        }

        // Пользователь авторизован
        $this->data['content'] = "account";

        $user = Users::get('id',$this->ses_helper->get_user_id());
        if ($user!==false) {
            $this->data['user'] = $user->get_all();
            $this->data['orders'] = $user->get_orders_data();
            if ($this->data['orders'] !== false) {
                $this->data['sum'] = number_format($this->data['orders']['sum'], 0, '.', ' ');
                unset($this->data['orders']['sum']);
            }
        }


        return true;
    }

    public function process() {
        if (!$this->prepare())
            return false;

        // Do something before render
        return true;
    }

    public function render()
    {
        if ($this->ses_helper->get_session_status() == SesHelper::SESSION_INITED)
            $this->templates->set_array(array("authed" => true, "cart" => $this->ses_helper->get_cart(), "role" => $this->ses_helper->get_user_role()));
        else
            $this->templates->set('cart',$this->ses_helper->get_cart());

        $this->templates->set_array(array("title" => "Аккаунт", "content" => $this->data['content'], "cats" => $this->data['cats']));
        if ($this->data['content'] == "account")
            $this->templates->set_array(array("orders" => $this->data['orders'], "user" => $this->data['user']));
        if (isset($this->data['email']))
            $this->templates->set('email',$this->data['email']);
        if (isset($this->data['name']))
            $this->templates->set('name',$this->data['name']);
        if (isset($this->data['phone']))
            $this->templates->set('phone',$this->data['phone']);
        if (isset($this->data['message']))
            $this->templates->set('message',$this->data['message']);
        $this->templates->display("index.tpl");
    }

}