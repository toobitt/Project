<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  OplogUpdateAPI extends outerUpdateBase
{
    private $tbname = 'op_log';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function create()
    {
        if(!isset($this->input['type']))
        {
            $this->errorOutput("NO_TYPE");
        }
        $params['type'] = trim($this->input['type']);
        
        if(!isset($this->input['type_id']))
        {
            $this->errorOutput("NO_TYPE_ID");
        }
        $params['type_id'] = intval($this->input['type_id']);
        
        if(!isset($this->input['op']))
        {
            $this->errorOutput("NO_OP");
        }
        
        $params['op'] = trim($this->input['op']);
        
        
        $query = "select * from ".DB_PREFIX."op_log where type_id=".$params['type_id']." and type='".$params['type']."' and op='".$params['op']."'";
        $log_re = $this->obj->query($query);
        $remsg['id'] = $params['type_id'];
        $remsg['type'] = $params['type'];
        if($log_re)
        {
            $remsg['status'] = 1;
            $remsg['msg'] = '已经赞过了';
            $this->addItem($remsg);
            $this->output();
        }
        
        $params['user_id'] = $this->user['user_id'];
        $params['create_time'] = TIMENOW;
        $params['update_time'] = TIMENOW;
        $params['ip'] = hg_getip();
        $params['id'] = $this->obj->insert($this->tbname,$params);
        
        if($params['type']=='photo')
        {
            $query = "select * from ".DB_PREFIX."photos where id=".$params['type_id'];
            $typedata_re = $this->obj->query($query);
            $typedata = $typedata_re[$params['type_id']];
        }
        
        if($params['op']=='ding')
        {
            $update_data['num_ding'] = $typedata['num_ding']+1;
        }
        
        if($params['op']=='zan')
        {
            $update_data['num_zan'] = $typedata['num_zan']+1;
        }
        
        $cond = " WHERE 1 AND id=".$params['type_id'];
        
        $this->obj->update("photos", $update_data, $cond);
               
        $this->addItem($params);
        $this->output();    
    }
    
    public function update()
    {
        
    }
    
    public function publish()
    {
        return;
    }
    
    public function delete()
    {
        if(!isset($this->input['type_id']))
        {
            $this->errorOutput("NO_TYPE_ID");
        }
        $type_id = intval($this->input['type_id']);
        
        if(!isset($this->input['type']))
        {
            $this->errorOutput("NO_TYPE");
        }
        $type = trim($this->input['type']); 
              
        $cond = " WHERE `type_id`=$type_id and type='".$type."'";
        
        $datas = $this->obj->delete("op_log",$cond);
        $remsg['id'] = $type_id;
        $remsg['type'] = $type;
        if(!$datas)
        {
            $remsg['status'] = 1;
            $remsg['msg'] = '删除成功';
            
            $this->addItem($remsg);
            $this->output();
        }
        
        if($this->input['type']=='photo')
        {
            $query = "select * from ".DB_PREFIX."photos where id=".$type_id;
            $typedata_re = $this->obj->query($query);
            $typedata = $typedata_re[$type_id];
        }
        
        if($this->input['op']=='ding')
        {
            $update_data['num_ding'] = $typedata['num_ding']-1;
        }
        
        if($this->input['op']=='zan')
        {
            $update_data['num_zan'] = $typedata['num_zan']+1;
        }
        
        $cond = " WHERE 1 AND id=".$type_id;
        
        $re = $this->obj->update("photos", $update_data, $cond);
        
        if($re)
        {
            $remsg['status'] = 1;
            $remsg['msg'] = '删除成功';
        }
        else 
        {
            $remsg['status'] = 2;
            $remsg['msg'] = '删除失败';
        }
        
        $this->addItem($remsg);
        $this->output();
    }
    
    public function audit()
    {
        return ;
    }
    
    public function sort()
    {
        return ;
    }
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }

}

$out = new OplogUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
