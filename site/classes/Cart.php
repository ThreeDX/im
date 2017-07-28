<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Cart extends Model{
    private $owner;
    private $count;
    private $sum;

    protected function __construct() {
        parent::__construct();
        $this->owner = null;
        $this->count = null;
        $this->sum = null;
    }

    public static function get($key, $value) {
        return parent::create(array($key => $value));
    }

    public function get_all($native = false) {
        if ($this->state == Model::STATE_INITED)
            if ($native)
                return array("owner" => $this->owner, "count" => $this->count, "sum" => $this->sum);
            else {
                $ret = array("owner" => $this->owner, "count" => $this->count, "sum" => $this->sum);
                $ret['sum'] = number_format($ret['sum'], 0, '.', ' ');
                return $ret;
            }
        return false;
    }

    public function get_count() {
        if ($this->state == Model::STATE_INITED) {
            return $this->count;
        }
        return null;
    }

    public function get_sum() {
        if ($this->state == Model::STATE_INITED) {
            return $this->sum;
        }
        return null;
    }

    public static function get_info_cart($cart) {
        foreach($cart as $key => $value) {
            if ($key == "sum" || $key == "count")
                continue;
            $it = explode("::", $key);
            $item = Item::get('id',$it[0]);
            if ($item!==false) {
                $cart[$key]['name'] = $item->get_name();
                isset($it[1])?$cart[$key]['var'] = $it[1]:$cart[$key]['var'] = '';
                $cart[$key]['image'] = $item->get_image();
                $cart[$key]['status'] = $item->get_status();
                $cart[$key]['price_sum'] = number_format(($item->get_price()*$value['count']), 0, '.', ' ');
                $cart[$key]['price'] = number_format($item->get_price(), 0, '.', ' ');
            }
        }
        return $cart;
    }

    // Удаление
    public function delete() {
        if ($this->state == Model::STATE_INITED) {
            DBWorker::instance()->do_query("delete from cart_items WHERE up=$1;", array($this->owner));
            $ret = DBWorker::instance()->do_query("delete from cart WHERE owner=$1", array($this->owner));
            if ($ret !== false) {
                $this->count = 0;
                $this->sum = 0;
                return true;
            }
        }
        return false;
    }

    public function save_cart($cart) {
        if ($this->state == Model::STATE_INITED) {
            $this->delete();
            $this->sum = $cart['sum'];
            $this->count = $cart['count'];
            $ret = DBWorker::instance()->do_query("insert into cart VALUES($1,$2,$3);", array($this->owner,$this->sum,$this->count));
            if ($ret !== false) {
                foreach($cart as $key => $value) {
                    if($key=="sum" || $key=="count")
                        continue;
                    $item = explode("::",$key);
                    DBWorker::instance()->do_query("insert into cart_items(up,item,count,var) VALUES($1,$2,$3,$4);", array($this->owner,$item[0],$value['count'],$item[1]));
                }
                return true;
            }
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

    public function get_cart_items_data() {
        if ($this->state == Model::STATE_INITED) {
            $items = array();
            $this->count = 0;
            $this->sum = 0;
            $ret = DBWorker::instance()->do_query_all("select * from cart_items where up=$1 order by id asc;",array($this->owner));
            if ($ret!==false) {
                foreach ($ret as $v) {
                    $item = Items::get('id', $v['item']);
                    if ($item === false)
                        continue;
                    $v['item'] = $item->get_all();
                    $v['item_price_sum_raw'] = $item->get_price() * $v['count'];
                    $v['item_price_sum'] = number_format($v['item_price_sum_raw'], 0, '.', ' ');
                    $this->count += $v['count'];
                    $this->sum += $v['item_price_sum_raw'];
                    $items[$v['id']] = $v;
                }
            }
            return $items;
        }
        return false;
    }


    private function init_values(array $data = null) {
        if ($data === null) {
            $this->owner = $this->data['owner'];
            $this->count = $this->data['count'];
            $this->sum = $this->data['sum'];
        }
        else {
            $this->owner = $data['owner'];
            $this->count = $data['count'];
            $this->sum = $data['sum'];
        }
    }

    protected function init() {
        // Все параметры переданы, проверка в бд не требуется
        if (isset($this->data['owner']) && isset($this->data['count']) && isset($this->data['sum']))
            $this->init_values();
        else if (isset($this->data['owner'])) { // by owner
            $ret = DBWorker::instance()->do_query_row("select * from cart where owner= $1;",array($this->data['owner']));
            if ($ret !== false)
                $this->init_values($ret);
            else if (SesHelper::instance()->get_session_status() == SesHelper::SESSION_INITED){
                $this->data['owner'] = SesHelper::instance()->get_user_id();
                $this->data['count'] = 0;
                $this->data['sum'] = 0;
                $this->init_values();
            } else
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