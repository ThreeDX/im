<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

interface IModel {
    public static function create(array $data = array());
    public static function get($key,$value);
}

abstract class Model implements IModel {
    const STATE_NOT_INITED = 0;
    const STATE_INITED = 1;

    protected $data;
    protected $state;

    protected function __construct() {
        $this->data = array();
        $this->state = Model::STATE_NOT_INITED;
    }

    protected function set_data($name, $value) {
        $this->data[$name] = $value;
    }

    protected function set_array_data(array $data = array()) {
        foreach($data as $key => $value)
            $this->data[$key] = $value;
    }

    protected function init() {
        $this->state = Model::STATE_INITED;
        return true;
    }

    public function get_state() {
        return $this->state;
    }

    public static function create(array $data = array()) {

        $className = get_called_class();
        $model = new $className();

        $model->set_array_data($data);

        if ($model->init())
            return $model;
        else
            return false;
    }
}

?>