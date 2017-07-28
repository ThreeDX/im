<?php

class ControllerCart extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        if (isset($_GET['action'])) {
            // Добавление в корзину
            if ($_GET['action'] == "add") {
                $error = 1;
                if (isset($_GET['id']) && intval($_GET['id']) > 0) {
                    $item = Items::get('id', intval($_GET['id']));
                    if ($item !== false && $item->get_status() != 3) {
                        // Проверяем вариацию
                        if (isset($_POST['vars']) && $item->check_var($_POST['vars']))
                            $this->ses_helper->add_to_cart($item->get_id(), $item->get_price(), $_POST['vars']);
                        else
                            $this->ses_helper->add_to_cart($item->get_id(), $item->get_price());

                        $error = 0;
                        Logger::log("Cart add item: ".$item->get_id());
                        $cart = $this->ses_helper->get_cart();
                        $this->data['cart_count'] = $cart['count'];
                        $this->data['cart_sum'] = $cart['sum'];
                    }
                }
                $this->templates->set('error', $error);
                if (!$error)
                    $this->templates->set_array(array("cart_sum" => $this->data['cart_sum'], "cart_count" => $this->data['cart_count']));
                $this->templates->display("api/cart_add.tpl");
                return false;
            }
            // Удаление из корзины
            if ($_GET['action'] == "del") {
                $error = 1;
                if (isset($_GET['id']) && $_GET['id']!="sum" && $_GET['id'] != "count") {
                    if ($this->ses_helper->del_from_cart($_GET['id'])) {
                        $error = 0;
                        $cart = $this->ses_helper->get_cart();
                        $this->data['cart_count'] = $cart['count'];
                        $this->data['cart_sum'] = $cart['sum'];
                    }
                }
                $this->templates->set('error', $error);
                if (!$error)
                    $this->templates->set_array(array("cart_id" => $_GET['id'], "cart_sum" => $this->data['cart_sum'], "cart_count" => $this->data['cart_count']));
                $this->templates->display("api/cart_del.tpl");
                return false;
            }
            // Изменение количества
            if ($_GET['action'] == "count") {
                $error = 1;
                if (isset($_GET['id']) && $_GET['id']!="sum" && $_GET['id'] != "count" && isset($_GET['count']) && intval($_GET['count']) >= 1) {
                    $c = $this->ses_helper->ch_count_cart($_GET['id'], intval($_GET['count']));
                    if ($c > 0) {
                        $error = 0;
                        $cart = $this->ses_helper->get_cart();
                        $this->data['cart_count'] = $cart['count'];
                        $this->data['cart_sum'] = $cart['sum'];
                        $this->data['cart_item_sum'] = number_format($c, 0, '.', ' ');;
                    }
                }
                $this->templates->set('error', $error);
                if (!$error)
                    $this->templates->set_array(array("cart_id" => $_GET['id'], "cart_sum_item" => $this->data['cart_item_sum'], "cart_count_item" => intval($_GET['count']), "cart_sum" => $this->data['cart_sum'], "cart_count" => $this->data['cart_count']));
                $this->templates->display("api/cart_count.tpl");
                return false;
            }
        }


        $this->data['content'] = "cart";
        $this->data['cats'] = Categories::all();
        $this->data['cart_info'] = Cart::get_info_cart($this->ses_helper->get_cart());
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

        $this->templates->set_array(array("title" => "Корзина", "content" => $this->data['content']));
        $this->templates->set_array(array("cats" => $this->data['cats'], "cart" => $this->ses_helper->get_cart()));
        if ($this->data['content'] == "cart")
            $this->templates->set('cart_info',$this->data['cart_info']);

        $this->templates->display("index.tpl");
    }
}