<?php

class ControllerOrder extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        $c = $this->ses_helper->get_cart();
        if($c['count'] == 0) {
            header("Location: index.php?page=Cart");
            return false;
        }
        $this->data['content'] = "step1";
        if (isset($_GET['action'])) {
            if($_GET['action'] == "step2" && $this->ses_helper->get_session_status() == SesHelper::SESSION_INITED && !isset($_POST['dostavka'])) {
                $user = Users::get('id', $this->ses_helper->get_user_id());
                if (strlen($user->get_phone())<4)
                    $this->data['content'] = "step1";
                else
                    $this->data['content'] = "step2";
            }
            if($_GET['action'] == "step2" && $this->ses_helper->get_session_status() == SesHelper::SESSION_INITED && isset($_POST['dostavka'])) {
                $user = Users::get('id', $this->ses_helper->get_user_id());
                if ($user !== false) {
                    $user->update_address($_POST);
                    $dostavka=1;
                    switch($_POST['dostavka']) {
                        case 1:
                        case 2:
                        case 3:
                            $dostavka = $_POST['dostavka'];
                            break;
                    }
                    $this->data['content'] = "step3";
                    $this->data['dostavka'] = $dostavka;
                    if (isset($_POST['comment']))
                        $this->data['comment'] = $_POST['comment'];
                    $this->data['user'] = $user->get_all();
                    $this->data['cart_info'] = Cart::get_info_cart($this->ses_helper->get_cart());
                    return true;
                }
                $this->data['message'] = "Ошибка при сохранении данных.";
            }
            if($_GET['action'] == "step3" && $this->ses_helper->get_session_status() == SesHelper::SESSION_INITED && isset($_POST['dostavka'])) {
                $dostavka=1;
                switch($_POST['dostavka']) {
                    case 1:
                    case 2:
                    case 3:
                        $dostavka = $_POST['dostavka'];
                        break;
                }
                $comment = isset($_POST['comment'])?$_POST['comment']:'';
                $id = Order::new_order($dostavka,$comment);
                if ($id) {
                    $this->data['order_id'] = str_pad($id, 4, '0', STR_PAD_LEFT);
                    SesHelper::instance()->del_cart(true);
                    $this->data['content'] = "step4";
                    return true;
                }
                $this->data['content'] = "step2";
                $this->data['message'] = "Ошибка при сохранении данных.";
            }
        }

        if ($this->ses_helper->get_session_status() == SesHelper::SESSION_INITED) {
            $user = Users::get('id', $this->ses_helper->get_user_id());
            if ($user !== false)
                $this->data['user'] = $user->get_all();
        }

        return true;
    }

    public function process() {
        if (!$this->prepare())
            return false;

        // Do something before render
        return true;
    }

    function render()
    {
        if ($this->ses_helper->get_session_status() == SesHelper::SESSION_INITED)
            $this->templates->set_array(array("authed" => true, "role" => SesHelper::instance()->get_user_role()));

        $this->templates->set_array(array("title" => "Оформление заказа", "content" => $this->data['content']));
        $this->templates->set_array(array("cats" => Categories::all(), "cart" => $this->ses_helper->get_cart()));
        if (isset($this->data['user'])) {
            $this->templates->set("user", $this->data['user']);
            $this->templates->set_array(array("name" => $this->data['user']['name'], "email" => $this->data['user']['email'], "phone" => $this->data['user']['phone']));
        }
        if(isset($this->data['dostavka']))
            $this->templates->set("dostavka", $this->data['dostavka']);
        if(isset($this->data['comment']))
            $this->templates->set("comment", $this->data['comment']);
        if(isset($this->data['cart_info']))
            $this->templates->set("cart_info", $this->data['cart_info']);
        if (isset($this->data['message']))
            $this->templates->set('message',$this->data['message']);
        if (isset($this->data['order_id']))
            $this->templates->set('order_id',$this->data['order_id']);

        $this->templates->display("index.tpl");
    }
}