<?php
/*******************************************************************
 * filename     :Core.class.php
 * Created      :2014年01月22日, by Scala 
 * Description  :
 ******************************************************************/
define('MOD_UNIQUEID', 'acp_config'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Dao.class.php');
class ConfigAPI extends  adminReadBase
{
    private $obj=null;
    private $tbname = 'site_config';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Dao();
    }
    
    public function detail()
    {
        $id = intval($this->input['id']);
        if(!$id)
        {
            $id = 0;
            //$this->errorOutput(NO_ID);
        }

        $data_limit = ' where id='.$id;
        
        $info = $this->obj->detail($this->tbname,$data_limit);

        if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
        
        $info['config_content'] = json_decode(urldecode($info['config_content']),1);
        
        $this->addItem($info);
        $this->output();
    }
    
    
    public function show()
    {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        
        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count; 

        $query = "
                SELECT *
                FROM ".DB_PREFIX."$this->tbname
                   ";
        $datas = $this->obj->query($query.$data_limit);
        
        
        foreach($datas as $k=>$v)
        {
            $this->addItem($v);
        
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
        
        if(isset($this->input['site_id']))
        {
            $cond .= ' and site_id='.intval($this->input['site_id']);
        }
        return $cond;
    }
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
    
    public function __desctruct()
    {
        
    }
}

$out = new ConfigAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>
