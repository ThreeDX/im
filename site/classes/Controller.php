<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

abstract class Controller
{
    const CONTROLLER_CAN_PROCESS = 0;
    const CONTROLLER_SHOULD_LOGIN = 1;
    const CONTROLLER_REDIRECTED = 2;

    protected $is_locked; // Флаг требования авторизации
    protected $data; // Данные контроллера
    protected $db;
    protected $ses_helper;
    protected $templates;

    public static function get_controller($name) {
        $name = "Controller".$name;
        if(class_exists($name))
            return new $name();
        return null;
    }

    public function __construct()
    {
        $this->is_locked = false;
        $this->db = DBWorker::instance();
        $this->ses_helper = SesHelper::instance();
        $this->templates = Template::instance();
        $this->data = array();
    }

    public function can_process() {
        if ($this->is_locked == false)
            return Controller::CONTROLLER_CAN_PROCESS;
        else if ($this->ses_helper->get_session_status()==SesHelper::SESSION_INITED)
            return Controller::CONTROLLER_CAN_PROCESS;
        else
            return Controller::CONTROLLER_SHOULD_LOGIN;
    }

    protected function prepare() {
        // Load some data
    }

    public function process() {
        $this->prepare();

        // Do something before render
        return true;
    }

    public function render() {
        $this->templates->display("404.tpl");
    }
}