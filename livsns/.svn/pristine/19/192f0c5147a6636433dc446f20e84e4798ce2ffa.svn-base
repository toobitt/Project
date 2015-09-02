<?php
define('MOD_UNIQUEID','attribute');
require_once('global.php');
class appendData extends adminReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function show(){}
	public function count(){}
	public function detail(){}
	
	//获取UI数据
	public function getUIData()
	{
	    $sql = "SELECT * FROM " .DB_PREFIX. "user_interface ORDER BY order_id ASC ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $this->addItem($r);
        }
        $this->output();	    
	}
	
	//获取属性类型
	public function getAttributeType()
	{
	    $sql = "SELECT * FROM " .DB_PREFIX. "attribute_type ORDER BY order_id ASC ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $this->addItem($r);
        }
        $this->output();
	}
	
	//获取属性分组
	public function getAttributeGroup()
	{
        $sql = "SELECT * FROM " .DB_PREFIX. "attribute_group ORDER BY id ASC ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $this->addItem($r);
        }
        $this->output();
	}
	
	//获取属性
	public function getAttribute()
	{
	    $sql = "SELECT * FROM "  . DB_PREFIX . "attribute ORDER BY id ASC ";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['attr_type_name'] = $this->settings['attribute_type'][$r['attr_type_id']]['name'];
			$this->addItem($r);
		}
		$this->output();
	}
	
    //获取前台属性分组
	public function getUIAttributeGroup()
	{
        $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_group ORDER BY id ASC ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $this->addItem($r);
        }
        $this->output();
	}
}

$out = new appendData();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();