<?php
define('MOD_UNIQUEID','gather_node');
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class gather_node_update extends nodeFrm
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
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	        $this->verify_create_node(intval($this->input['fid']));
	    }
    	if (!$this->input['id'])
        {
        	$this->errorOutput(NOID);
            return ;
        }
        if (!$this->input['name'])
        {
            $this->errorOutput(NOSORTNAME);
        }
    	$data = array(
        	'id' => intval($this->input['id']),
             'name' => trim(urldecode($this->input['name'])),
             'brief' => trim(urldecode($this->input['brief'])),
    		 'fid'=>intval($this->input['fid']),
        );
        $this->verify_create_node($data['fid']);
		//初始化
		$this->initNodeData();
		//设置新增或者需要更新的节点数据
		$this->setNodeData($data);
		//设置操作的节点ID
		$this->setNodeID($data['id']);
		//更新方法
		$this->updateNode();
        $this->addItem($data);
        $this->output();
    }

    public function delete()
    {
   	 	//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
    	if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $this->initNodeData();
        //判断是否成功删除
        if($this->batchDeleteNode($this->input['id']))
        {
            $this->addItem(array('id' => urldecode($this->input['id'])));
        }
        $this->output();
    }

    public function create()
    {
    	//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	        $this->verify_create_node(intval($this->input['fid']));
	    }
    	$data = array(
		        'ip'=>hg_getip(),
		        'create_time'=>TIMENOW,
		        'fid'=>intval($this->input['fid']),
		        'update_time'=>TIMENOW,
		        'name'=>trim(urldecode($this->input['name'])),
		        'brief'=>trim(urldecode($this->input['brief'])),
		        'user_name'=>trim(urldecode($this->user['user_name']))
            );
            if (!$data['name'])
            {
                    $this->errorOutput(NOSORTNAME);
            }
		    $this->initNodeData();
		    //设置新增或者需要更新的节点数据
		    $this->setNodeData($data);
		    //增加节点无需设置操作节点ID
		    if($nid = $this->addNode())
		    {
		            $data['id'] = $nid;
		            $this->addItem($data);
		    }
    		$this->output();
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
$out = new gather_node_update();
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