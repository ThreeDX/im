<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class DBWorker
{
    private $mdb;
    private static $instance;

    protected function __construct() {
        $this->mdb = pg_connect(DB_CONNECT_STRING);
        if ($this->mdb===false)
        {
            die("Error opening DB!");
        }
    }

    function __destruct() {
        pg_close($this->mdb);
    }

    public static function instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new DBWorker();
        }
        return self::$instance;
    }

    public function do_query_all($query, array $data = array()) {
        $result = pg_query_params($this->mdb, $query, $data);
        if ($result === false) {
            die(pg_last_error($this->mdb));
        }
        return pg_fetch_all($result);
    }

    public function do_query_row($query, array $data = array()) {
        $result = pg_query_params($this->mdb, $query, $data);
        if ($result === false) {
            die(pg_last_error($this->mdb));
        }
        $row = pg_fetch_assoc($result);
        pg_free_result($result);

        return $row;
    }

    public function do_query($query, array $data = array()) {
        $result = pg_query_params($this->mdb, $query, $data);
        if ($result === false) {
            die(pg_last_error($this->mdb));
        }
        return true;
    }

}