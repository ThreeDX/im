<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Logger {
    private static $users = array();

    public static function log($desc) {
        $user = 0;
        if(SesHelper::instance()->get_session_status() == SesHelper::SESSION_INITED)
            $user = SesHelper::instance()->get_user_id();
        $ip = SesHelper::get_ip();

        DBWorker::instance()->do_query("insert into logs(ip,\"user\",\"desc\",td) values($1,$2,$3,CURRENT_TIMESTAMP);", array($ip,$user,$desc));
    }

    public static function get_all() {
        $data = array();
        $ret = DBWorker::instance()->do_query_all("select * from logs order by id desc;");
        if ($ret!==false)
            foreach($ret as $key => $value) {
                if ($value['user'] > 0) {
                    if (isset(self::$users[$value['user']]))
                        $value['user'] = self::$users[$value['user']];
                    else {
                        $user = Users::get('id',$value['user']);
                        if ($user!==false) {
                            self::$users[$value['user']] = $user->get_all();
                            $value['user'] = self::$users[$value['user']];
                        }
                    }
                }
                $value['td'] = date_format(date_create($value['td']), 'd.m.Y в H:i');
                $data[] = $value;
            }
        return $data;
    }

}