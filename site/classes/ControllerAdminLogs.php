<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class ControllerAdminLogs extends ControllerAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        $this->data['content'] = "admin_logs";
        $this->data['logs'] = Logger::get_all();

        return true;
    }

    public function process() {
        if(!$this->prepare())
            return false;

        // Do something before render
        return true;
    }

    function render()
    {
        $this->templates->set_array(array("title" => "Логи", "content" => $this->data['content'], "current" => "logs"));
        $this->templates->set("logs",$this->data['logs']);

        $this->templates->display("admin_index.tpl");
    }
}