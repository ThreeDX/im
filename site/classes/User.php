<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class User extends Model{
    private $id;
    private $email;
    private $name;
    private $role;
    private $password;

    protected function __construct() {
        parent::__construct();
        $this->id = null;
        $this->email = null;
        $this->name = null;
        $this->role = null;
        $this->password = null;
        $this->phone = null;
        $this->city = null;
        $this->street = null;
        $this->house = null;
        $this->room = null;
    }

    public static function get($key, $value) {
        return parent::create(array($key => $value));
    }

    private static function format_phone($data){
        if(  preg_match( '/^(\d{1,2})(\d{3})(\d{3})(\d{2})(\d{2})$/', $data,  $matches ) )
        {
            $result = "+".$matches[1] .' '.$matches[2].' '.$matches[3].'-'.$matches[4].'-'.$matches[5];
            return $result;
        }
        return $data;
    }

    public function get_all($native = false) {
        if ($this->state == Model::STATE_INITED) {
            if ($native)
                return array("id" => $this->id, "email" => $this->email, "name" => $this->name, "role" => $this->role, "password" => $this->password, "phone" => $this->phone,
                    "city" => $this->city, "street" => $this->street, "house" => $this->house, "room" => $this->room);
            else {
                $ret = array("id" => $this->id, "email" => $this->email, "name" => $this->name, "role" => $this->role, "password" => $this->password,
                    "city" => $this->city, "street" => $this->street, "house" => $this->house, "room" => $this->room);
                $ret['phone'] = self::format_phone($this->phone);
                return $ret;
            }
        }
        return false;
    }

    public function get_phone() {
        if ($this->state == Model::STATE_INITED) {
            return $this->phone;
        }
        return null;
    }

    public function get_orders_data($with_cancel = false) {
        if ($this->state == Model::STATE_INITED) {
            $orders = Orders::get('owner', $this->id);
            $ret = array();
            $sum = 0;
            if ($orders !==false) {
                foreach ($orders as $key => $value) {
                    if (!$with_cancel && $value->get_name_status() == "отменен")
                        continue;
                    $ret[$key] = $value->get_all();
                    $sum+=$value->get_sum();
                }
                $ret['sum'] = $sum;
                return $ret;
            }
        }
        return false;
    }

    // Удаляем пользователя
    public function delete() {
        // Пользователь должен существовать
        if ($this->state == Model::STATE_INITED) {

            $ret = DBWorker::instance()->do_query("DELETE FROM users WHERE id=$1", array($this->id));
            if ($ret !== false) {
                DBWorker::instance()->do_query("DELETE FROM orders WHERE owner=$1", array($this->id));
                $this->state = Model::STATE_NOT_INITED;
                return true;
            }
        }
        return false;
    }

    private function create_id() {
        if ($this->state == Model::STATE_INITED) {
            $ret =  DBWorker::instance()->do_query_row("insert into users(email, role, password, name) values($1, $2, $3, $4) RETURNING id;", array($this->email, $this->role, $this->password, $this->name));
            if ($ret !== false) {
                $this->id = $ret['id'];
                return true;
            }
        }
        return false;
    }

    public function update(array $post = array()) {
        if ($this->state == Model::STATE_INITED && isset($post['name']) && isset($post['email'])) {
            if (strlen($post['name']) > 0)
                $this->name = $post['name'];
            if (strlen($post['email']) > 0)
                $this->email = $post['email'];
            if (isset($post['pass1']) && isset($post['pass2']) && strlen($post['pass1']) > 0 && $post['pass1'] == $post['pass2'])
                $this->password = $post['pass1'];
            // Получаем id
            if ($this->id=="new"  && $this->create_id() === false)
                return false;
            if (isset($post['phone']))
                $this->phone = preg_replace("/\D+/","",$_POST['phone']);
            if (isset($post['city']))
                $this->city = $post['city'];
            if (isset($post['street']))
                $this->street = $post['street'];
            if (isset($post['house']))
                $this->house = $post['house'];
            if (isset($post['room']))
                $this->room = $post['room'];

            return $this->save();
        }
        return false;
    }

    public function update_address(array $post = array()) {
        if ($this->state == Model::STATE_INITED) {
            if (isset($post['city']))
                $this->city = $post['city'];
            if (isset($post['street']))
                $this->street = $post['street'];
            if (isset($post['house']))
                $this->house = $post['house'];
            if (isset($post['room']))
                $this->room = $post['room'];

            return $this->save();
        }
        return false;
    }

    private function save() {
        if ($this->state == Model::STATE_INITED) {
            $ret = DBWorker::instance()->do_query("update users set email=$1, role = $2, password = $3, name = $4, phone = $5, city = $6, street = $7, house = $8, room = $9 WHERE id=$10",
                array($this->email, $this->role, $this->password, $this->name, $this->phone, $this->city, $this->street, $this->house, $this->room, $this->id));
            if ($ret !== false) {
                SesHelper::instance()->update_user($this->name, $this->email);
                Logger::log("User updated: ".$this->email);
                return true;
            }
        }
        return false;
    }

    private function init_values(array $data = null) {
        if ($data === null) {
            $this->id = $this->data['id'];
            $this->email = $this->data['email'];
            $this->name = $this->data['name'];
            $this->role = $this->data['role'];
            $this->password = $this->data['password'];
            $this->phone = $this->data['phone'];
            $this->city = $this->data['city'];
            $this->street = $this->data['street'];
            $this->house = $this->data['house'];
            $this->room = $this->data['room'];
        }
        else {
            $this->id = $data['id'];
            $this->email = $data['email'];
            $this->name = $data['name'];
            $this->role = $data['role'];
            $this->password = $data['password'];
            $this->phone = $data['phone'];
            $this->city = $data['city'];
            $this->street = $data['street'];
            $this->house = $data['house'];
            $this->room = $data['room'];
        }
    }

    protected function init() {
        // Все параметры переданы, проверка в бд не требуется
        if (isset($this->data['id']) && isset($this->data['email']) && isset($this->data['password']) && isset($this->data['role']) && isset($this->data['name']) && isset($this->data['phone'])
                && isset($this->data['city']) && isset($this->data['street']) && isset($this->data['house']) && isset($this->data['room']))
            $this->init_values();
        else if (isset($this->data['id'])) { // by id
            if ($this->data['id'] == "new" && isset($this->data['email']) && isset($this->data['password']) && isset($this->data['name'])) { // новый пользователь
                $this->id = $this->data['id'];
                $this->email = $this->data['email'];
                $this->name = $this->data['name'];
                $this->data['role'] = 0;
                $this->password = $this->data['password'];
                $this->data['phone'] = "";
                $this->data['city'] = "";
                $this->data['street'] = "";
                $this->data['house'] = "";
                $this->data['room'] = "";
                $this->init_values();
            } else {
                $ret = DBWorker::instance()->do_query_row("SELECT * FROM users WHERE id= $1;", array($this->data['id']));
                if ($ret !== false)
                    $this->init_values($ret);
                else
                    return false;
            }
        } else if (isset($this->data['email']) && isset($this->data['password'])) { // auth
            $ret = DBWorker::instance()->do_query_row("select * from users where email= $1 and password=$2;",array($this->data['email'], $this->data['password']));
            if ($ret !== false)
                $this->init_values($ret);
            else
                return false;
        } else if (isset($this->data['email'])) { // by email
            $ret = DBWorker::instance()->do_query_row("select * from users where email= $1;",array($this->data['email']));
            if ($ret !== false)
                $this->init_values($ret);
            else
                return false;
        } else if (isset($this->data['phone'])) { // by phone
            $ret = DBWorker::instance()->do_query_row("select * from users where phone= $1;",array($this->data['phone']));
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