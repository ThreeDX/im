<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class Item extends Model{
    private $id;
    private $cat;
    private $name;
    private $description;
    private $images;
    private $price;
    private $price_old;
    private $bage;
    private $status;
    private $vars;

    protected function __construct() {
        parent::__construct();
        $this->id = null;
        $this->cat = null;
        $this->name = null;
        $this->description = null;
        $this->images = null;
        $this->price = null;
        $this->price_old = null;
        $this->bage = null;
        $this->status = null;
        $this->vars = null;
    }

    public static function get($key, $value) {
        return parent::create(array($key => $value));
    }

    public function get_price() {
        return $this->price;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_image() {
        if($this->images)
            foreach($this->images as $image)
                if (strlen($image) > 0)
                    return $image;
        return null;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_cat_id() {
        return $this->cat;
    }

    public function get_status() {
        return $this->status;
    }

    public function check_var($var) {
        if ($this->state == Model::STATE_INITED && is_array($this->vars))
            foreach($this->vars as $v)
                if($v==$var)
                    return true;
        return false;
    }

    private function create_id() {
        if ($this->state == Model::STATE_INITED) {
            $ret =  DBWorker::instance()->do_query_row("insert into items(name, description, price) values($1, $2, $3) RETURNING id;", array($this->name, $this->description, $this->price));
            if ($ret !== false) {
                $this->id = $ret['id'];
                Logger::log("Add item: ".$this->name);
                return true;
            }
        }
        return false;
    }

    public function update(array $post = array()) {
        if ($this->state == Model::STATE_INITED && isset($post['name']) && isset($post['price']) && isset($post['description'])) {
            if (strlen($post['name']) > 0)
                $this->name = $post['name'];
            if (strlen($post['description']) > 0)
                $this->description = $post['description'];
            if (intval($post['price']) > 0)
                $this->price = intval($post['price']);
            // Получаем id
            if ($this->id=="new"  && $this->create_id() === false)
                return false;
            if (isset($post['price_old']) && intval($post['price_old']) >= 0)
                $this->price_old = intval($post['price_old']);
            if (isset($post['bage']) && intval($post['bage']) >= 0 && intval($post['bage']) <= 3)
                $this->bage = intval($post['bage']);
            if (isset($post['status']) && intval($post['status']) >= 1 && intval($post['status']) <= 3)
                $this->status = intval($post['status']);
            if (isset($post['cat']) && intval($post['cat']) >= 0) {
                if ($post['cat'] >0) {
                    $cat = Categories::get('id', intval($post['cat']));
                    if ($cat !== false)
                        $this->cat = intval($post['cat']);
                } else
                    $this->cat = 0;
            }
            if (isset($post['vars']) && is_array($post['vars'])) {
                foreach($post['vars'] as $key => $value) {
                    $value = str_replace("::","",$value);
                    if (strlen($value) == 0)
                        unset($post['vars'][$key]);
                    else
                        $post['vars'][$key] = $value;
                }
                $this->vars = $post['vars'];
            } else
                $this->vars = null;

            $this->upload_images($post);

            $this->save();

            return true;
        }
        return false;
    }

    private function upload_photo($i, $file)
    {
        if ($file['name'] == '')
            return false;
        if ($file['type'] != 'image/jpeg' && $file['type'] != 'image/png' && $file['type'] != 'image/gif')
            return false;
        if ($file['size'] > 2097152) //2Mb
            return false;
        if(!self::img_resize($file['tmp_name'],'./images/items/m'.$this->id."_$i.".pathinfo($file['name'], PATHINFO_EXTENSION),148,148))
            return false;
        if(!self::img_resize($file['tmp_name'],'./images/items/l'.$this->id."_$i.".pathinfo($file['name'], PATHINFO_EXTENSION),470,470))
            return false;
        if(!self::img_resize($file['tmp_name'],'./images/items/k'.$this->id."_$i.".pathinfo($file['name'], PATHINFO_EXTENSION),292,236))
            return false;
        if(copy($file['tmp_name'], './images/items/'.$this->id."_$i.".pathinfo($file['name'], PATHINFO_EXTENSION)))
            return true;
        else
            return false;
    }


    private function upload_images(array $post) {
        foreach ($this->images as $key => $value) {
            // Передали файл
            if (isset($_FILES['img' . $key]) && $_FILES['img' . $key]['error'] == 0) {
                // Уже было изображение
                if (strlen($value) > 0) {
                    unlink("./images/items/$value");
                    unlink("./images/items/m$value");
                    unlink("./images/items/l$value");
                    unlink("./images/items/k$value");
                    $this->images[$key] = null;
                }
                if ($this->upload_photo($key, $_FILES['img' . $key])) {
                    $this->images[$key] = $this->id . "_$key." . pathinfo($_FILES['img' . $key]['name'], PATHINFO_EXTENSION);
                }
            } else
                if (strlen($value) > 0 && isset($post['img_deleted' . $key]) && $post['img_deleted' . $key] == 1) {
                    unlink("./images/items/$value");
                    unlink("./images/items/m$value");
                    unlink("./images/items/l$value");
                    unlink("./images/items/k$value");
                    $this->images[$key] = null;
                }
        }
    }

    private function save() {
        if ($this->state == Model::STATE_INITED) {
            $ret = DBWorker::instance()->do_query("update items set name=$1,cat = $2, description = $3, img1 = $4, img2 = $5, img3 = $6, img4 = $7, price = $8, price_old = $9, bage = $10, status = $11, vars = $12 WHERE id=$13",
                array($this->name, $this->cat, $this->description, $this->images[1], $this->images[2], $this->images[3], $this->images[4], $this->price, $this->price_old, $this->bage, $this->status, implode("::",$this->vars), $this->id));
            if ($ret !== false) {
                Logger::log("Update item: ".$this->name);
                return true;
            }
        }
        return false;
    }

    public function get_all($native = false) {
        if ($this->state == Model::STATE_INITED)
            if ($native)
                return array("id" => $this->id, "cat" => $this->cat, "name" => $this->name, "description" => $this->description, "images" => $this->images,
                    "price" => $this->price, "price_old" => $this->price_old, "bage" => $this->bage, "status" => $this->status, "vars" => $this->vars);
            else {
                $ret = array("id" => $this->id, "cat" => $this->cat, "name" => $this->name, "description" => $this->description, "images" => $this->images,
                    "price" => $this->price, "price_old" => $this->price_old, "bage" => $this->bage, "status" => $this->status, "vars" => $this->vars);
                /*if ($ret['cat'] > 0) {
                    $ret['cat_obj'] = Categories::create(array("id" => $ret["cat"]));
                }*/
                $ret['price_raw'] = $ret['price'];
                $ret['price'] = number_format($ret['price'], 0, '.', ' ');
                $ret['price_old_raw'] = $ret['price_old'];
                if($ret['price_old'] > 0)
                    $ret['price_old'] = number_format($ret['price_old'], 0, '.', ' ');
                return $ret;
            }
        return false;
    }

    private function init_values(array $data = null) {
        if ($data === null) {
            $this->id = $this->data['id'];
            $this->cat = $this->data['cat'];
            $this->name = $this->data['name'];
            $this->description = $this->data['description'];
            $this->images = $this->data['images'];
            $this->price = $this->data['price'];
            $this->price_old = $this->data['price_old'];
            $this->bage = $this->data['bage'];
            $this->status = $this->data['status'];
            $this->vars = $this->data['vars'];
        }
        else {
            $this->id = $data['id'];
            $this->cat = $data['cat'];
            $this->name = $data['name'];
            $this->description = $data['description'];
            $this->images = $data['images'];
            $this->price = $data['price'];
            $this->price_old = $data['price_old'];
            $this->bage = $data['bage'];
            $this->status = $data['status'];
            $this->vars = $data['vars'];
        }
    }

    protected function init() {
        // Все параметры переданы, проверка в бд не требуется
        if (isset($this->data['id']) && isset($this->data['cat']) && isset($this->data['name']) && isset($this->data['description']) && isset($this->data['images'])
            && isset($this->data['price']) && isset($this->data['price_old']) && isset($this->data['bage']) && isset($this->data['status']) && isset($this->data['vars']))
            $this->init_values();
        else if (isset($this->data['id'])) { // by id
            if ($this->data['id'] == "new") {
                $this->data['cat'] = 0;
                $this->data['name'] = "";
                $this->data['description'] = "";
                $this->data['images'] = array(1 => null, 2 => null, 3 => null, 4 => null);
                $this->data['price'] = 0;
                $this->data['price_old'] = 0;
                $this->data['bage'] = 0;
                $this->data['status'] = 1;
                $this->data['vars'] = null;
                $this->init_values();
            } else {
                $ret = DBWorker::instance()->do_query_row("SELECT * FROM items WHERE id= $1;", array($this->data['id']));
                if ($ret !== false) {
                    $ret['images'] = array(1 => $ret['img1'], 2 => $ret['img2'], 3 => $ret['img3'], 4 => $ret['img4']);
                    if ($ret['vars'])
                        $ret['vars'] = explode("::", $ret['vars']);
                    $this->init_values($ret);
                } else
                    return false;
            }
        } else
            return false;

        $this->state = Model::STATE_INITED;
        return true;
    }

    // Создаём модель данных
    public static function create(array $data = array()) {
        return parent::create($data); //Вызываем родительский метод, где присваиваются базовые параметры и создаётся объект нужного класса
    }

    public static function img_resize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100) {
        if (!file_exists($src)) return false;

        $size = getimagesize($src);

        if ($size === false) return false;

        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) return false;

        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];

        $ratio       = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);

        $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
        $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
        $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
        $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

        $isrc = $icfunc($src);
        $idest = imagecreatetruecolor($width, $height);

        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
            $new_width, $new_height, $size[0], $size[1]);

        imagejpeg($idest, $dest, $quality);

        imagedestroy($isrc);
        imagedestroy($idest);

        return true;
    }
}