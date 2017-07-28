<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class ControllerAdminCategories extends ControllerAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        // Добавление категории
        if (isset($_GET['action']) && $_GET['action'] == "add") {
            if (isset($_POST['name']) && strlen($_POST['name']) > 0)
                Categories::add($_POST['name']);

            //Переадресовываем на обычный вывод, чтобы action не было в строке браузера
            header("Location: admin.php?page=AdminCategories");
            return false;
        }

        // Удаление категории
        if (isset($_GET['action']) && $_GET['action'] == "delete") {
            if (isset($_GET['id']) && intval($_GET['id']) > 0) {
                $cat = Categories::get('id', intval($_GET['id']));
                if ($cat !== false)
                    $cat->delete();
            }
            //Переадресовываем на обычный вывод, чтобы action не было в строке браузера
            header("Location: admin.php?page=AdminCategories");
            return false;
        }

        // Переименование категории
        if (isset($_GET['action']) && $_GET['action'] == "rename") {
            if (isset($_GET['id']) && intval($_GET['id']) > 0) {
                if (isset($_POST['name']) && strlen($_POST['name']) > 0) {
                    $cat = Categories::get('id', intval($_GET['id']));
                    if ($cat !== false)
                        $cat->rename($_POST['name']);
                }
                header("Location: admin.php?page=AdminItems&cat=".intval($_GET['id']));
                return false;
            }
            header("Location: admin.php?page=AdminItems");
            return false;
        }
        $this->data['cat'] = Categories::all();

        return true;
    }

    public function process() {
        if (!$this->prepare())
            return false;

        // Do something before render
        return true;
    }

    function render()
    {
        $this->templates->set_array(array("title" => "Категории", "content" => "admin_cat", "current" => "cat", "cat" => $this->data['cat']));
        $this->templates->display("admin_index.tpl");
    }
}