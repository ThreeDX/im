<?php

// Шаблоны
class Template
{
    private $dir_tmpl; // Директория с tpl-файлами
    private $data; // Данные для вывода

    private static $instance;

    // Один экземпляр на программу
    public static function instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Template(TEMPLATES_DIR);
        }
        return self::$instance;
    }

    protected function __construct($dir_tmpl) {
        $this->dir_tmpl = $dir_tmpl;
        $this->data = array();
    }

    // Метод для добавления новых значений в данные для вывода
    public function set($name, $value) {
        $this->data[$name] = $this->protect($value);
    }

    private function protect($data) {
        if (!is_array($data))
            return htmlspecialchars($data, ENT_QUOTES | ENT_IGNORE, "UTF-8");
        else
            foreach($data as $key => $value)
                $data[$key] = $this->protect($value);
        return $data;
    }

    // Метод для добавления новых значений в данные для вывода
    public function set_array(array $data = array()) {
        foreach($data as $key => $value) {
            $this->data[$key] = $this->protect($value);
        }
    }

    // Метод для удаления значений из данных для вывода
    public function delete($name) {
        unset($this->data[$name]);
    }

    // При обращении, например, к $this->title будет выводиться $this->data["title"]
    public function __get($name) {
        if (isset($this->data[$name])) return $this->data[$name];
        return null;
    }

    // Перегружаем isset()
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    // Вывод tpl-файла, в который подставляются все данные для вывода
    public function display($template) {
        $template = $this->dir_tmpl.$template;
        ob_start();
        include ($template);
        echo ob_get_clean();
    }
}