<?php

class ControllerItem extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        if (isset($_GET['id']) && intval($_GET['id']) > 0) {
            $item = Items::get('id', intval($_GET['id']));
            if ($item !== false) {
                $this->data['item'] = $item->get_all();
                $images = array();
                foreach($this->data['item']['images'] as $image)
                    if ($image)
                        $images[]=$image;
                $this->data['item']['images'] = $images;
                $this->data['content'] = "item";
                $this->data['cats'] = Categories::all();
                $this->data['cat'] = $item->get_cat_id();
                $this->data['cat_items'] = Items::all($this->data['cat'], 8);

                return true;
            }
        }
        header("Location: index.php");
        return false;
    }

    public function process() {
        if (!$this->prepare())
            return false;

        // Do something before render
        return true;
    }

    function render()
    {
        if ($this->ses_helper->get_session_status() == SesHelper::SESSION_INITED)
            $this->templates->set_array(array("authed" => true, "cart" => $this->ses_helper->get_cart(), "role" => $this->ses_helper->get_user_role()));
        else
            $this->templates->set('cart',$this->ses_helper->get_cart());

        $this->templates->set_array(array("title" => $this->data['item']['name'], "content" => $this->data['content']));

        $this->templates->set_array(array("cats" => $this->data['cats'], "cat_items" => $this->data['cat_items'], "item" => $this->data['item'], "cat" => $this->data['cat']));

        $this->templates->display("index.tpl");
    }
}