<?php
define('MOD_UNIQUEID','gatherapi_node');
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
require('../lib/gather.class.php');
class gatherapi_node_update extends nodeFrm
{
    public function __construct()
    {
    	parent::__construct();
   		$this->setNodeTable('sort');
        $this->setNodeVar('gather_node');
    }

    public function __destruct()
    {
    	parent::__destruct();
    }


    public function update() 
    {
	   
		$this->updateNode();
        $this->addItem($data);
        $this->output();
    }

    public function delete()
    {
        $id=$this->input['id'];
    	$sql="delete from ".DB_PREFIX."plan where id in ({$id})";
    	$data=$this->db->query($sql);
    	$this->addItem($data);
        $this->output(); 
    }

    public function create(){
    	$url=$this->input['urladdress'];
     	$name=$this->input['name'];
    	$time=time();
    	$verify=new gatherapi();
    	$url=$verify->VerifyUrl($url);
    	$urlsql="insert into ".DB_PREFIX."plan (id,createtime,url,status) values (null,'{$time}','{$url}','0')";
    	$this->db->query($urlsql);
    }
    
    //排序
    public function drag_order()
    {
            $sort = json_decode(html_entity_decode($this->input['sort']),true);

            if(!empty($sort))
            {
                    foreach($sort as $key=>$val)
                    {
                            $data = array(
                                    'order_id' => $val,
                            );
                            if(intval($key) && intval($val))
                            {
                                    $sql ="UPDATE " . DB_PREFIX . "sort SET";

                                    $sql_extra=$space=' ';
                                    foreach($data as $k => $v)
                                    {
                                            $sql_extra .=$space . $k . "='" . $v . "'";
                                            $space=',';
                                    }
                                    $sql .=$sql_extra.' WHERE id='.$key;
                                    $this->db->query($sql);
                            }
                            $id[] = $key;
                    }
            }
            $this->addItem('success');
            $this->output();
    }
}
$out = new gatherapi_node_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>