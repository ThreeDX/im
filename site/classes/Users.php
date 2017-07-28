<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Users extends Model {
    public static function get($key, $value) {
        return User::create(array($key => $value));
    }

    public static function all($native = false) {
        $data = array();
        $ret = DBWorker::instance()->do_query_all("select * from users order by email asc;");
        if ($ret!==false)
            foreach($ret as $value) {
                $user = User::create($value);
                if ($user === false)
                    continue;
                $data[$value['id']] = $user->get_all($native);
            }
        return $data;
    }
}