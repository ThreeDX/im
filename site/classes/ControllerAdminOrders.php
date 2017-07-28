<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class ControllerAdminOrders extends ControllerAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        if (isset($_GET['action'])) {
            // Просмотр
            if ($_GET['action'] == "view" && isset($_GET['id']) && intval($_GET['id'])>0) {
                $order = Orders::get('id',intval($_GET['id']));

                if ($order!==false) {
                    $this->data['order'] = $order->get_all();
                    $this->data['order_items'] = $order->get_order_items_data();
                    $this->data['user'] = $order->get_owner_data();
                    $this->data['content'] = "admin_order";
                    return true;
                }
            }

            // Удаление
            if ($_GET['action'] == "delete" && isset($_GET['id']) && intval($_GET['id'])>0) {
                $order = Orders::get('id',intval($_GET['id']));

                if ($order!==false && $order->delete() !== false) {
                    header("Location: admin.php?page=AdminOrders");
                    return false;
                }
            }

            // Передали zstatus
            if($_GET['action']=="chstatus") {
                $error = 1;
                if (isset($_GET['id']) && intval($_GET['id']) > 0 && isset($_GET['status']) && intval($_GET['status']) > 0) {
                    $order = Orders::get('id',intval($_GET['id']));
                    if ($order !== false && $order->ch_status(intval($_GET['status'])) !== false) {
                        $error = 0;
                        $this->data['order_id'] = intval($_GET['id']);
                        $this->data['status_id'] = intval($_GET['status']);
                        $this->data['status_text'] = $order->get_name_status();
                    }
                }
                $this->templates->set('error', $error);
                if (!$error)
                    $this->templates->set_array(array("order_id" => $this->data['order_id'], "status_id" => $this->data['status_id'], "status_text" => $this->data['status_text']));
                $this->templates->display("api/ch_zstatus.tpl");
                return false;
            }

            // Передали item_delete
            if($_GET['action']=="item_delete") {
                $error = 1;
                if (isset($_GET['id']) && intval($_GET['id']) > 0 && isset($_GET['item_id']) && intval($_GET['item_id']) > 0) {
                    $order = Orders::get('id',intval($_GET['id']));
                    if ($order !== false && $order->del_item(intval($_GET['item_id'])) !== false) {
                        $error = 0;
                        $this->data['item_deleted_id'] = intval($_GET['item_id']);
                        $this->data['order_sum'] = $order->get_sum(true);
                    }
                }
                $this->templates->set('error', $error);
                if (!$error)
                    $this->templates->set_array(array("item_deleted_id" => $this->data['item_deleted_id'], "order_sum" => $this->data['order_sum']));
                $this->templates->display("api/del_zitem.tpl");
                return false;
            }
        }

        $this->data['content'] = "admin_orders";
        $this->data['orders'] = Orders::all();

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
        $this->templates->set_array(array("title" => "Заказы", "content" => $this->data['content'], "current" => "orders"));
        if ($this->data['content'] == "admin_orders") {
            $this->templates->set("orders", $this->data['orders']);
        }
        // Просмотр пользователя
        if ($this->data['content'] == "admin_order") {
            $this->templates->set("user", $this->data['user']);
            $this->templates->set("order", $this->data['order']);
            $this->templates->set("order_items", $this->data['order_items']);
            $this->templates->set("title", "Заказ");
        }
        $this->templates->display("admin_index.tpl");
    }
}