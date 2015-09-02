<?php
define('MOD_UNIQUEID','append_data');
require_once('global.php');
class append_data extends adminReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
    public function show(){}
    public function detail(){}
    public function count(){}
    public function index(){}
    
    //获取专题分类顶级节点
    public function getSpecialFirstNode()
    {
        $sql = "SELECT * FROM " .DB_PREFIX. "special_sort WHERE fid = 0 ORDER BY order_id ASC ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $this->addItem($r);
        }
        $this->output();   
    }
    
    //获取所有演讲嘉宾
    public function getAllGuests()
    {
        $sql = "SELECT id,name FROM " .DB_PREFIX. "guest";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $this->addItem($r);
        }
        $this->output();   
    }
}

$out = new append_data();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();