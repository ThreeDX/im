<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Account extends Model {
    public static function get($key, $value) {
        return parent::create(array($key => $value));
    }

    public static function auth($email, $password) {
        $user = User::create(array("email" => $email, "password" => $password));
        if ($user !== false) {
            $ret = $user->get_all();
            if ($ret !== false) {
                SesHelper::instance()->set_user($ret['id'], "", $ret['email'], $ret['role']);
                Logger::log("User login: ".$ret['email']);
                return true;
            }
        }
        return false;
    }

    public static function logout() {
        Logger::log("User logout: ".SesHelper::instance()->get_user_email());
        SesHelper::instance()->unset_user();
    }

    public static function create(array $data=array()) {
        // ToDo: Check Account exists in db
        return parent::create($data);
    }
}