<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Order extends Model{
    private $id;
    private $owner;
    private $status;
    private $dt;
    private $sum;
    private $dostavka;
    private $comment;

    protected function __construct() {
        parent::__construct();
        $this->id = null;
        $this->owner = null;
        $this->status = null;
        $this->dt = null;
        $this->sum = null;
        $this->dostavka = null;
        $this->comment = null;
    }

    public static function get($key, $value) {
        return parent::create(array($key => $value));
    }

    private static function create_id($owner, $sum, $dostavka, $comment) {
        $ret =  DBWorker::instance()->do_query_row("insert into orders(owner, status, dt, sum, dostavka, comment) values($1, 1, CURRENT_TIMESTAMP, $2, $3, $4) RETURNING id;", array($owner, $sum, $dostavka, $comment));
        if ($ret !== false) {
            return $ret['id'];
        }
        return null;
    }

    public static function new_order($dostavka, $comment) {
        $owner = SesHelper::instance()->get_user_id();
        $cart = Cart::get('owner',$owner);
        if ($cart !== false && $cart->get_count() > 0) {
            $items = $cart->get_cart_items_data();
            if (is_array($items)) {
                $id = self::create_id($owner, $cart->get_sum(), $dostavka, $comment);
                if ($id > 0) {
                    foreach ($items as $item)
                        DBWorker::instance()->do_query("insert into order_items(up,item,count, var) values($1, $2, $3, $4);", array($id, $item['item']['id'], $item['count'], $item['var']));
                    Logger::log("New order: ".$id);
                    return $id;
                }
            }
        }
        return null;
    }

    public function get_all($native = false) {
        if ($this->state == Model::STATE_INITED)
            if ($native)
                return array("id" => $this->id, "owner" => $this->owner, "status" => $this->status, "dt" => $this->dt, "sum" => $this->sum, "dostavka" => $this->dostavka, "comment" => $this->comment);
            else {
                $ret = array("id" => $this->id, "owner" => $this->owner, "status" => $this->status, "dt" => $this->dt, "sum" => $this->sum, "dostavka" => $this->dostavka, "comment" => $this->comment);
                $ret['status_name'] = self::get_status_name($ret['status']);
                $ret['dostavka_name'] = self::get_dostavka_name($ret['dostavka']);
                $ret['sum'] = number_format($ret['sum'], 0, '.', ' ');
                $ret['id_pad'] = str_pad($ret['id'], 4, '0', STR_PAD_LEFT);
                $ret['dt'] = date_format(date_create($ret['dt']), 'd.m.Y в H:i');
                return $ret;
            }
        return false;
    }

    // Изменение статуса
    public function ch_status($status) {
        // Заказ есть и статус корректен
        if ($this->state == Model::STATE_INITED && $status>=1 && $status<=5) {
            $ret = DBWorker::instance()->do_query("update orders set status=$1 WHERE id=$2", array($status, $this->id));
            if ($ret !== false) {
                $this->status = $status;
                Logger::log("Order status change: ".$this->id." -> ".self::get_status_name($status));
                return true;
            }
        }
        return false;
    }

    // Удаление заказа
    public function delete() {
        // Заказ есть и статус корректен
        if ($this->state == Model::STATE_INITED) {
            DBWorker::instance()->do_query("delete from order_items WHERE up=$1;", array($this->id));
            $ret = DBWorker::instance()->do_query("delete from orders WHERE id=$1", array($this->id));
            if ($ret !== false) {
                Logger::log("Del order: ".$this->id);
                $this->state = Model::STATE_NOT_INITED;
                return true;
            }
        }
        return false;
    }

    // Удаление предмета из заказа
    public function del_item($item_id) {
        // Заказ есть и статус корректен
        if ($this->state == Model::STATE_INITED && $this->status<4) {
            $items = $this->get_order_items_data();
            if ($items !== false && array_key_exists($item_id, $items)) {
                $ret = DBWorker::instance()->do_query("delete from order_items WHERE id=$1;", array($item_id));
                if ($ret !== false) {
                    // Пересчитываем сумму
                    $this->sum = 0;
                    Logger::log("Del item from order: ".$items[$item_id]['name']);
                    unset($items[$item_id]);
                    foreach($items as $it)
                        $this->sum += $it['item_price_sum_raw'];
                    $this->save_sum();
                    return true;
                }
            }
        }
        return false;
    }

    private function save_sum () {
        if ($this->state == Model::STATE_INITED) {
            $ret = DBWorker::instance()->do_query("update orders set sum=$1 WHERE id=$2", array($this->sum, $this->id));
            if ($ret !== false) {
                return true;
            }
        }
        return false;
    }

    public function get_name_status() {
        if ($this->state == Model::STATE_INITED) {
            return self::get_status_name($this->status);
        }
        return false;
    }

    public function get_sum($is_padded = false) {
        if ($this->state == Model::STATE_INITED) {
            if ($is_padded)
                return number_format($this->sum, 0, '.', ' ');
            else
                return $this->sum;
        }
        return false;
    }

    public function get_owner_data() {
        if ($this->state == Model::STATE_INITED) {
            $user = Users::get("id", $this->owner);
            if ($user !== false)
                return $user->get_all();
        }
        return false;
    }

    public function get_order_items_data() {
        if ($this->state == Model::STATE_INITED) {
            $items = array();
            $ret = DBWorker::instance()->do_query_all("select * from order_items where up=$1 order by id asc;",array($this->id));
            if ($ret!==false) {
                foreach ($ret as $v) {
                    $item = Items::get('id', $v['item']);
                    if ($item === false)
                        continue;
                    $v['item'] = $item->get_all();
                    $v['item_price_sum_raw'] = $item->get_price() * $v['count'];
                    $v['item_price_sum'] = number_format($v['item_price_sum_raw'], 0, '.', ' ');
                    $items[$v['id']] = $v;
                }
            }
            return $items;
        }
        return false;
    }

    private static function get_status_name($status) {
        switch($status) {
            case 1:
                return "принят";
            case 2:
                return "отгружен";
            case 3:
                return "у курьера";
            case 4:
                return "доставлен";
            case 5:
                return "отменен";
        }
        return "";
    }

    private static function get_dostavka_name($status) {
        switch($status) {
            case 1:
                return "Курьерская доставка с оплатой при получении";
            case 2:
                return "Почта России с наложенным платежом";
            case 3:
                return "Доставка через терминалы QIWI Post";
        }
        return "";
    }

    private function init_values(array $data = null) {
        if ($data === null) {
            $this->id = $this->data['id'];
            $this->owner = $this->data['owner'];
            $this->status = $this->data['status'];
            $this->dt = $this->data['dt'];
            $this->sum = $this->data['sum'];
            $this->dostavka = $this->data['dostavka'];
            $this->comment = $this->data['comment'];
        }
        else {
            $this->id = $data['id'];
            $this->owner = $data['owner'];
            $this->status = $data['status'];
            $this->dt = $data['dt'];
            $this->sum = $data['sum'];
            $this->dostavka = $data['dostavka'];
            $this->comment = $data['comment'];
        }
    }

    protected function init() {
        // Все параметры переданы, проверка в бд не требуется
        if (isset($this->data['id']) && isset($this->data['owner']) && isset($this->data['status']) && isset($this->data['dt']) && isset($this->data['sum']) && isset($this->data['dostavka']) && isset($this->data['comment']))
            $this->init_values();
        else if (isset($this->data['id'])) { // by id
            $ret = DBWorker::instance()->do_query_row("select * from orders where id= $1;",array($this->data['id']));
            if ($ret !== false)
                $this->init_values($ret);
            else
                return false;
        } else
            return false;

        $this->state = Model::STATE_INITED;
        return true;
    }

    // Создаём модель данных
    public static function create(array $data = array()) {
        return parent::create($data); //Вызываем родительский метод, где присваиваются базовые параметры и создаётся объект нужного класса
    }
}