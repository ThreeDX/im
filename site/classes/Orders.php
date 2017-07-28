<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Orders extends Model{
    public static function get($key, $value) {
        if($key == "id")
            return Order::get($key, $value);
        else if ($key == "owner"){
            $orders = array();
            $ret = DBWorker::instance()->do_query_all("select * from orders where owner=$1 order by id desc;",array($value));
            if ($ret!==false) {
                foreach ($ret as $v) {
                    $ord = Order::create($v);
                    if ($ord === false)
                        continue;
                    $orders[$v['id']] = $ord;
                }
            }
            return $orders;
        }
        return false;
    }

    public static function all($native=false) {
        $orders = array();
        $ret = DBWorker::instance()->do_query_all("select * from orders order by id desc;");
        if ($ret!==false)
            foreach($ret as $value) {
                $ord = Order::create($value);
                if ($ord === false)
                    continue;
                $orders[$value['id']] = $ord->get_all($native);
                $orders[$value['id']]['owner_data'] = $ord->get_owner_data();
            }
        return $orders;
    }

} 