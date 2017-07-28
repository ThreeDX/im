<?php

class ControllerIndex extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {
        $this->data['cats'] = Categories::all();

        if(isset($_GET['cat']) && intval($_GET['cat']) > 0) {
            $cat = Categories::get('id',intval($_GET['cat']));
            if ($cat !== false) {
                $current_page = 1;
                $count = $cat->get_count();
                $max_page = 1 + ceil(($count-17)/24);
                if(isset($_GET['p']) && intval($_GET['p']) >= 1 && intval($_GET['p']) <= $max_page)
                    $current_page = intval($_GET['p']);
                $lim = ($current_page == 1)?17:24;
                $offset = 0;
                for ($j = 1; $j<$current_page;$j++)
                    $offset+=($j==1)?17:24;

                $this->data['category'] = $cat->get_all();
                $this->data['items'] = Items::all(intval($_GET['cat']),$lim, $offset);
                $this->data['content'] = "cat";
                $this->data['current_page'] = $current_page;
                $this->data['offset'] = $offset + 1;
                $this->data['max_page'] = $max_page;
                $this->data['cat'] = intval($_GET['cat']);
                return true;
            }

        }

        $this->data['content'] = "home";
        $this->data['new_items'] = Items::all(0,16);
        $this->data['pop_items'] = Items::pop_items(0,8);

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
        $this->templates->set_array(array("title" => "Super Shop", "content" => $this->data['content']));
        if ($this->ses_helper->get_session_status() == SesHelper::SESSION_INITED)
            $this->templates->set_array(array("authed" => true, "cart" => $this->ses_helper->get_cart(), "role" => $this->ses_helper->get_user_role()));
        else
            $this->templates->set('cart',$this->ses_helper->get_cart());
        $this->templates->set_array(array("cats" => $this->data['cats']));
        if ($this->data['content'] == "home")
            $this->templates->set_array(array("new_items" => $this->data['new_items'], "pop_items" => $this->data['pop_items']));
        if ($this->data['content'] == "cat")
            $this->templates->set_array(array("items" => $this->data['items'], "category" => $this->data['category'], "cat" => $this->data['cat'], "current_page" => $this->data['current_page'], "offset" => $this->data['offset'], "max_page" => $this->data['max_page']));
        $this->templates->display("index.tpl");
    }
}