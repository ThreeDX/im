<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Categories extends Model{
    private $id;
    private $name;
    private $count;
    private $status;

    protected function __construct() {
        parent::__construct();
        $this->id = null;
        $this->name = null;
        $this->count = null;
        $this->status = null;
    }

    public static function get($key, $value) {
        return parent::create(array($key => $value));
    }

    public static function all() {
        $data = array();
        $ret = DBWorker::instance()->do_query_all("select * from cat where status<>3 order by name asc;");
        if ($ret!==false)
            foreach($ret as $value) {
                $value['count'] = Items::count($value['id']);
                $cat = self::create($value);
                if ($cat === false)
                    continue;
                $data[$value['id']] = $cat->get_all();
            }
        return $data;
    }

    // Добавляет новую категорию в БД
    public static function add($name) {
        $ret = DBWorker::instance()->do_query("insert into cat (name, status) values($1, 1)",array($name));
        if ($ret!==false) {
            Logger::log("Add category: $name");
            return true;
        }
        return false;
    }

    // Удаляем категорию
    public function delete() {
        // Категория должна существовать и число товаров должно быть 0
        if ($this->state == Model::STATE_INITED && $this->count == 0) {

            $ret = DBWorker::instance()->do_query("update cat set status = 3 WHERE id=$1", array($this->id));
            if ($ret !== false) {
                $this->status = 3;
                Logger::log("Del category: ".$this->name);
                return true;
            }
        }
        return false;
    }

    // Переименовываем категорию
    public function rename($name) {
        // Категория должна существовать
        if ($this->state == Model::STATE_INITED) {

            $ret = DBWorker::instance()->do_query("update cat set name=$1 WHERE id=$2", array($name, $this->id));
            if ($ret !== false) {
                $this->name = $name;
                Logger::log("Rename category: ".$this->name." to ".$name);
                return true;
            }
        }
        return false;
    }

    public function get_all() {
        if ($this->state == Model::STATE_INITED)
            return array("id" => $this->id, "name" => $this->name, "count" => $this->count, "status" => $this->status);
        return false;
    }

    public function get_name() {
        if ($this->state == Model::STATE_INITED)
            return $this->name;
        return false;
    }

    public function get_count() {
        if ($this->state == Model::STATE_INITED)
            return $this->count;
        return false;
    }

    private function init_values(array $data = null) {
        if ($data === null) {
            $this->id = $this->data['id'];
            $this->name = $this->data['name'];
            $this->count = $this->data['count'];
            $this->status = $this->data['status'];
        }
        else {
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->count = $data['count'];
            $this->status = $data['status'];
        }
    }

    protected function init() {
        // Все параметры переданы, проверка в бд не требуется
        if (isset($this->data['id']) && isset($this->data['name']) && isset($this->data['count']) && isset($this->data['status']))
            $this->init_values();
        else if (isset($this->data['id'])) { // by id
            $ret = DBWorker::instance()->do_query_row("select * from cat where id= $1;",array($this->data['id']));
            if ($ret !== false) {
                $ret['count'] = Items::count($this->data['id']);
                $this->init_values($ret);
            }
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