<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class ControllerAdminItems extends ControllerAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        if (isset($_GET['action'])) {
            // Save item
            if ($_GET['action'] == "save" && isset($_GET['id']) &&  (intval($_GET['id']) > 0 || $_GET['id']=="new")) {
                $item = Items::get('id', $_GET['id']);
                if ($item !== false) {
                    $item->update($_POST);

                    header("Location: admin.php?page=AdminItems&cat=".$item->get_cat_id());
                    return false;
                }
                header("Location: admin.php?page=AdminItems");
                return false;
            }

            // Add item
            if ($_GET['action'] == "add") {
                $item = Items::get('id', "new");
                if ($item !== false) {
                    $this->data['item'] = $item->get_all();
                    $this->data['cats'] = Categories::all();
                    $this->data['content'] = "admin_item";
                    if(isset($_GET['cat']) && intval($_GET['cat'])>0 && Categories::get('id',intval($_GET['cat'])) !== false)
                        $this->data['item']['cat'] = intval($_GET['cat']);
                    return true;
                }
            }

            // Просмотр
            if ($_GET['action'] == "view" && isset($_GET['id']) && intval($_GET['id']) > 0) {
                $item = Items::get('id', intval($_GET['id']));
                if ($item !== false) {
                    $this->data['item'] = $item->get_all();
                    $this->data['cats'] = Categories::all();
                    $this->data['content'] = "admin_item";
                    return true;
                }
            }
        }

            // Передали категорию
        if(isset($_GET['cat']) && intval($_GET['cat'])>0) {
            $cat = Categories::get('id',intval($_GET['cat']));
            if($cat!==false)
                $this->data['cat'] = $cat->get_all();
        }
        $this->data['content'] = "admin_items";
        $this->data['items'] = Items::all((isset($this->data['cat']))?$this->data['cat']['id']:0);

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
        $this->templates->set_array(array("title" => "Товары", "content" => $this->data['content'], "current" => "items"));
        if (isset($this->data['cat']))
            $this->templates->set("cat", $this->data['cat']);
        if (isset($this->data['items']))
            $this->templates->set("items", $this->data['items']);

        // Просмотр товара
        if ($this->data['content'] == "admin_item") {
            $this->templates->set("item", $this->data['item']);
            $this->templates->set("cats", $this->data['cats']);
            if ($this->data['item']['id'] != "new")
                $this->templates->set("title", "Просмотр товара");
            else
                $this->templates->set("title", "Добавление товара");
        }

        $this->templates->display("admin_index.tpl");
    }
}