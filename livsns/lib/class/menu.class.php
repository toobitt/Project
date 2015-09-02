<?php
//获取应用
define('DB_PREFIX', 'liv_');

class menu {

    public $host     = 'db.dev.hogesoft.com';
    public $user     = 'root';
    public $pass     = 'hogesoft';
    public $database = 'dev_workbench';
    public $charset  = 'utf8';
    public $db;
    public $link;

    public function __construct()
    {
        include_once('../db/db_mysql.class.php');
        $this->db = new db();
        $this->link = $this->db->connect($this->host, $this->user, $this->pass, $this->database, $this->charset);
    }

    public function __destruct()
    {
    }

    public function show()
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'menu';
        $res = mysql_query($sql,$this->link);
        if ($res) {
            while($re = mysql_fetch_assoc($res))
            {
                $data[] = $re;
            }
        }
        return $data;
    }


}
