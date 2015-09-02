<?php
define('MOD_UNIQUEID','append_data');
require_once('global.php');
class append_data extends outerReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
    public function detail(){}
    public function count(){}
    
    //获取一些配置参数
    public function show()
    {
        $sql = "SELECT * FROM " .DB_PREFIX. "special_sort WHERE fid = 0 ORDER BY order_id ASC ";
        $q = $this->db->query($sql);
        $special_sort = array();
        while ($r = $this->db->fetch_array($q))
        {
            $special_sort[] = $r;
        }
        
        $date_arr = array();
        foreach ($this->settings['agenda_date'] AS $k => $v)
        {
            if(!intval($k))
            {
                continue;
            }
            $date_arr[] = array('id' => $k,'name' => $v);
        }
        
        $this->addItem_withkey('special', $special_sort);
        $this->addItem_withkey('date', $date_arr);
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