<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class ControllerAdminUsers extends ControllerAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        if (isset($_GET['action'])) {
            // Просмотр пользователя
            if ($_GET['action'] == "view" && isset($_GET['id']) && intval($_GET['id'])>0) {
                $user = Users::get('id',intval($_GET['id']));
                if ($user!==false) {
                    $this->data['user'] = $user->get_all();
                    $this->data['orders'] = $user->get_orders_data();
                    if ($this->data['orders'] !== false) {
                        $this->data['sum'] = number_format($this->data['orders']['sum'], 0, '.', ' ');
                        unset($this->data['orders']['sum']);
                    }
                    $this->data['content'] = "admin_user";
                    if ($this->data['user']['id'] == SesHelper::instance()->get_user_id())
                        $this->data['can_delete'] = false;
                    else
                        $this->data['can_delete'] = true;
                    return true;
                }
            }
            // Удаление категории
            if ($_GET['action'] == "delete") {
                if (isset($_GET['id']) && intval($_GET['id']) > 0 && SesHelper::instance()->get_user_id() != intval($_GET['id'])) {
                    $user = Users::get('id', intval($_GET['id']));
                    if ($user !== false)
                        $user->delete();
                }
                //Переадресовываем на обычный вывод, чтобы action не было в строке браузера
                header("Location: admin.php?page=AdminUsers");
                return false;
            }

        }
        $this->data['content'] = "admin_users";
        $this->data['users'] = Users::all();

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
        $this->templates->set_array(array("title" => "Пользователи", "content" => $this->data['content'], "current" => "users"));
        if ($this->data['content'] == "admin_users")
            $this->templates->set("users",$this->data['users']);

        // Просмотр пользователя
        if ($this->data['content'] == "admin_user") {
            $this->templates->set("user", $this->data['user']);
            $this->templates->set("orders", $this->data['orders']);
            $this->templates->set("sum", $this->data['sum']);
            $this->templates->set("can_delete", $this->data['can_delete']);
            $this->templates->set("title", "Просмотр пользователя");
        }

        $this->templates->display("admin_index.tpl");
    }
}