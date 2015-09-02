<?php
define('MOD_UNIQUEID', 'SiteConfig'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class Cate extends  adminReadBase
{
    private $obj=null;
    private $tbname = 'site_config';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    
    public function detail()
    {
        $id = intval($this->input['id']);
        if(!$id)
        {
            $this->errorOutput(NO_ID);
        }
        
        $data_limit = ' where id='.$id;
        
        $info = $this->obj->detail($this->tbname,$data_limit);
        
        if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
        $this->addItem($info);
        $this->output();
    }
    
    public function show()
    {
        $query = "SELECT * FROM ".DB_PREFIX.$this->tbname;
        $where = " WHERE 1 ";
        
        $datas = $this->obj->query($query);
        
        foreach($datas as $data)
        {
            $this->addItem($data);
        }        
        $this->output();
    }
    
    public function count()
    {
        $condition = $this->get_condition();
        
        $info = $this->obj->count($this->tbname,$condition);
        
        echo json_encode($info);
    }
    
    public function index()
    {

    }
    private function get_condition()
    {
        $cond = " where 1 ";
        return $cond;
    }
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new Cate();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();

?>