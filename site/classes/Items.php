<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Items extends Model{
    public static function get($key, $value) {
        return Item::get($key, $value);
    }

    public static function all($cat = 0, $limit=0, $pad = 0, $native=false) {
        $items = array();
        $params=array();
        $i=1;
        $query = "select * from items where status<>3";
        if ($cat> 0) {
            $query .= " and cat=$" . $i;
            $params[] = $cat;
            $i++;
        }
        $query.=" order by id desc";
        if ($limit > 0 && $pad==0) {
            $query .= " limit $" . $i;
            $params[] = $limit;
        }
        if ($limit > 0 && $pad>0) {
            $query .= " limit $" . $i." OFFSET $".($i+1);
            $params[] = $limit;
            $params[] = $pad;
        }
        $ret = DBWorker::instance()->do_query_all($query, $params);
        if ($ret!==false)
            foreach($ret as $value) {
                $value['images'] = array(1 => $value['img1'], 2 => $value['img2'], 3 => $value['img3'], 4 => $value['img4']);
                if ($value['vars'])
                    $value['vars'] = explode("::",$value['vars']);
                $item = Item::create($value);
                if ($item === false)
                    continue;
                $items[$value['id']] = $item->get_all($native);
            }
        return $items;
    }

    public static function pop_items($cat = 0, $limit=0, $native=false) {
        $items = array();
        $params=array();
        $i=1;
        $query = "select * from items where status<>3";
        if ($cat> 0) {
            $query .= " and cat=$" . $i;
            $params[] = $cat;
            $i++;
        }
        $query.=" order by id asc";
        if ($limit > 0) {
            $query .= " limit $" . $i;
            $params[] = $limit;
        }
        $ret = DBWorker::instance()->do_query_all($query, $params);
        if ($ret!==false)
            foreach($ret as $value) {
                $value['images'] = array(1 => $value['img1'], 2 => $value['img2'], 3 => $value['img3'], 4 => $value['img4']);
                if ($value['vars'])
                    $value['vars'] = explode("::",$value['vars']);
                $item = Item::create($value);
                if ($item === false)
                    continue;
                $items[$value['id']] = $item->get_all($native);
            }
        return $items;
    }

    public static function count($cat = 0) {
        $params=array();
        $query = "select count(id) as c from items where status<>3";
        if ($cat> 0) {
            $query .= " and cat=$1";
            $params[] = $cat;
        }
        $ret = DBWorker::instance()->do_query_row($query, $params);
        if ($ret!==false)
            return $ret['c'];
        return false;
    }
}