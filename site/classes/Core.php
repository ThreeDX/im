<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Core
{
    private $pages;
    private $def_page;
    private $controller;

    public function __construct(array $pages, $default = "Index") {
        $this->pages = $pages;
        $this->def_page = $default;
        $this->controller = $this->get_controller();
    }

    private function check_page($page = null) {
        if ($page=== null && isset($_GET['page']) && in_array($_GET['page'],$this->pages,true))
            return $_GET['page'];
        else if ($page!==null && in_array($page,$this->pages,true))
            return $page;
        else
            return $this->def_page;
    }

    private function prepare() {
        if($this->controller === null)
            return false;
        switch ($this->controller->can_process()) {
            case Controller::CONTROLLER_SHOULD_LOGIN: // Надо перенаправить пользователя на страницу логина
                $this->controller = $this->get_controller("Account");
                return $this->prepare();
            case Controller::CONTROLLER_REDIRECTED: // Контроллер сделал редирект сам, просто завершаем работу
                return false;
            default:
                break;
        }
        return true;
    }

    public function process() {
        if(!$this->prepare())
            return;

        if (!$this->controller->process())
            return;

        $this->controller->render();
    }

    private function get_controller($page = null) {
        return Controller::get_controller($this->check_page($page));
    }
}