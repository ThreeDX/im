<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class SesHelper
{
    const SESSION_NULL = 0;
    const SESSION_STARTED = 1;
    const SESSION_INITED = 2;

    private $status;
    private static $instance;

    public static function get_ip() {
        $ip = null;
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    protected function __construct() {
        $this->status = SESHelper::SESSION_NULL;
        $this->start_session();
    }

    public static function instance() {
        if (empty(self::$instance)) {
            self::$instance = new SESHelper();
        }
        return self::$instance;
    }

    private function start_session() {
        if ($this->status == SESHelper::SESSION_NULL) {
            session_start();

            $this->status = SESHelper::SESSION_STARTED;
            $this->check_user();
        }
    }

    private function check_user() {
        if (!isset($_SESSION['user_id']))
            return false;

        $this->status = SESHelper::SESSION_INITED;
        return true;
    }

    public function unset_user() {
        if($this->status == SESHelper::SESSION_INITED)
            $this->del_cart();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        $this->status = SESHelper::SESSION_STARTED;
    }

    public function set_user($id, $name, $email, $role) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $role;
        $this->status = SESHelper::SESSION_INITED;
        $cart = Cart::get('owner',$id);
        if ($cart !== false && $cart->get_count() > 0) {// Нужно совместить или подгрузить корзины
            if (!isset($_SESSION['cart']))
                $_SESSION['cart'] = array('sum' => 0, 'count' => 0);
            $items = $cart->get_cart_items_data();
            if (is_array($items))
                foreach($items as $item) {
                    $key = $item['item']['id'] . "::" . $item['var'];
                    if (array_key_exists($key, $_SESSION['cart'])) {// мержим
                        $_SESSION['cart'][$key]['count'] += $item['count'];
                    }
                    else
                        $_SESSION['cart'][$key]['count'] = $item['count'];
                    $_SESSION['cart']['count'] += $item['count'];
                    $_SESSION['cart']['sum'] += $item['item_price_sum_raw'];
                }
        }
        // Сохраняем данные в бд, чтобы данные не разошлись
        if (isset($_SESSION['cart']) && $_SESSION['cart']['count'] > 0)
            $cart->save_cart($_SESSION['cart']);
    }

    public function get_session_status() {
        return $this->status;
    }

    public function update_user($name, $email) {
        if($this->status == SESHelper::SESSION_INITED) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
        }
    }

    public function get_user_id() {
        return (isset($_SESSION['user_id']))?$_SESSION['user_id']:null;
    }

    public function get_user_name() {
        return (isset($_SESSION['user_name']))?$_SESSION['user_name']:null;
    }

    public function get_user_email() {
        return (isset($_SESSION['user_email']))?$_SESSION['user_email']:null;
    }

    public function get_user_role() {
        return (isset($_SESSION['user_role']))?$_SESSION['user_role']:null;
    }

    public function is_granted() {
        return (isset($_SESSION['user_role']) && $_SESSION['user_role'] > 0)?true:false;
    }

    public function get_cart() {
        if(isset($_SESSION['cart'])) {
            $c = $_SESSION['cart'];
            $c['sum'] = number_format($c['sum'], 0, '.', ' ');
            return $c;
        }
        return array('sum' => 0, 'count' => 0);
    }

    public function del_from_cart($key) {
        if(isset($_SESSION['cart'][$key])) {
            $it = explode("::",$key);
            $item = Item::get('id',$it[0]);
            if ($item !== false) {
                $_SESSION['cart']['count']-=$_SESSION['cart'][$key]['count'];
                $_SESSION['cart']['sum']-=$item->get_price() * $_SESSION['cart'][$key]['count'];
                Logger::log("Cart del item: ".implode(",",$_SESSION['cart'][$key]));
                unset($_SESSION['cart'][$key]);
                // Save to DB
                if($this->status == SESHelper::SESSION_INITED) {
                    $cart = Cart::get('owner',$_SESSION['user_id']);
                    if ($cart !== false)
                        $cart->save_cart($_SESSION['cart']);
                }
                return true;
            }
        }
        return false;
    }

    public function ch_count_cart($key, $c) {
        if(isset($_SESSION['cart'][$key])) {
            $it = explode("::",$key);
            $item = Item::get('id',$it[0]);
            if ($item !== false) {
                $_SESSION['cart']['count']-=$_SESSION['cart'][$key]['count'];
                $_SESSION['cart']['sum']-=$item->get_price() * $_SESSION['cart'][$key]['count'];
                $_SESSION['cart']['count']+=$c;
                $_SESSION['cart']['sum']+=$item->get_price() * $c;
                $_SESSION['cart'][$key]['count'] = $c;
                Logger::log("Cart change count item: ".$item->get_id());

                // Save to DB
                if($this->status == SESHelper::SESSION_INITED && $_SESSION['cart'][$key]['count'] != $c) {
                    $cart = Cart::get('owner',$_SESSION['user_id']);
                    if ($cart !== false)
                        $cart->save_cart($_SESSION['cart']);
                }
                return $c * $item->get_price();
            }
        }
        return false;
    }

    public function add_to_cart($id, $price, $var='') {
        $key = $id."::".$var;
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['count']++;
            $_SESSION['cart']['sum']+=$price;
            $_SESSION['cart']['count']++;
        }
        else {
            $_SESSION['cart'][$key]['count']=1;
            if (isset($_SESSION['cart']['sum'])) {
                $_SESSION['cart']['sum'] += $price;
                $_SESSION['cart']['count'] ++;
            }
            else {
                $_SESSION['cart']['sum'] = $price;
                $_SESSION['cart']['count'] = 1;
            }
        }
        // Save to DB
        if($this->status == SESHelper::SESSION_INITED) {
            $cart = Cart::get('owner',$_SESSION['user_id']);
            if ($cart !== false)
                $cart->save_cart($_SESSION['cart']);
        }
    }

    public function del_cart($db = false) {
        unset($_SESSION['cart']);
        if ($this->status == SESHelper::SESSION_INITED && $db) {
            $cart = Cart::get('owner',$_SESSION['user_id']);
            if ($cart !== false)
                $cart->delete();
        }
    }
}