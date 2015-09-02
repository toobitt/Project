<?php
define('MOD_UNIQUEID','feedback_node');
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class feedback_node_update extends nodeFrm
{
    public function __construct()
    {
            parent::__construct();
            //检测是否具有配置权限
           	$this->verify_setting_prms();
   			$this->setNodeTable('feedback_node');
            $this->setNodeVar('feedback_node');
            include_once(CUR_CONF_PATH . 'lib/feedback_mode.php');
            include_once(ROOT_DIR . 'lib/class/material.class.php');
    		$this->material = new material();
            $this->mode = new feedback_mode();
    }

    public function __destruct()
    {
            parent::__destruct();
    }


    public function update() 
    {
    	if (!$this->input['id'])
        {
        	$this->errorOutput(NOID);
            return ;
        }
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
	    	if(!intval($this->input['fid']))
	    	{
	    		$this->errorOutput(NO_PRIVILEGE);
	    	}
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
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
		$this->addLogs('修改反馈节点','',$data,$data['name']);
        $this->addItem($data);
        $this->output();
    }

    public function delete()
    {
    	if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
    	//查询主分类
		$sql = 'SELECT id FROM '.DB_PREFIX.'feedback_node WHERE fid=0';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$fids[] = $r['id'];
		}
		//非管理员不能删除主分类
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
	    	if(in_array(intval($this->input['id']),$fids))
	    	{
	    		$this->errorOutput(NO_PRIVILEGE);
	    	}
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
	    
	    //查询分类
		$sql = 'SELECT id FROM '.DB_PREFIX.'feedback WHERE node_id IN ('.$this->input['id'].')';
		$parkinfo = $this->db->query_first($sql);
		if($parkinfo)
		{
			$this->errorOutput('该分类下有内容！');
		}
		
        $this->initNodeData();
        //判断是否成功删除
        if($this->batchDeleteNode($this->input['id']))
        {
            $this->addItem(array('id' => urldecode($this->input['id'])));
        }
        $this->addLogs('删除反馈节点','','','删除反馈节点+' . $this->input['id']);
        $this->output();
    }

    public function create()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
	    	if(!intval($this->input['fid']))
	    	{
	    		$this->errorOutput(NO_PRIVILEGE);
	    	}
	    	if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }	        
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
            $this->verify_create_node($data['fid']);
		    $this->initNodeData();
		    //设置新增或者需要更新的节点数据
		    $this->setNodeData($data);
		    //增加节点无需设置操作节点ID
		    if($nid = $this->addNode())
		    {
		            $data['id'] = $nid;
		            $this->addItem($data);
		    }
		    $this->addLogs('创建反馈节点','',$data,$data['name']);
    		$this->output();
    }
    //排序
    public function drag_order()
    {
            $node = json_decode(html_entity_decode($this->input['sort']),true);

            if(!empty($node))
            {
                    foreach($node as $key=>$val)
                    {
                            $data = array(
                                    'order_id' => $val,
                            );
                            if(intval($key) && intval($val))
                            {
                                    $sql ="UPDATE " . DB_PREFIX . "feedback_node SET";

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
            $this->addLogs('反馈节点排序','','','反馈节点排序+' . implode(',',$id));
            $this->addItem('success');
            $this->output();
    }
    
    //更新分类属性
    public function updateCatagory()
    {
    	$id = $this->input['id'];
    	if(!$id)
    	{
    		$this->errorOutput(NOID);
    	}
    	$data = array(
    		'id'		=> $id,
    		'title'		=> $this->input['title'],
    		'brief'		=> addslashes($this->input['brief']),
    		'style'		=> $this->input['style'],
    		'template'	=> $this->input['template'],
    		'start_time'=> strtotime($this->input['start_time']),
    		'end_time'	=> strtotime($this->input['end_time']),
    		'more_info'	=> addslashes($this->input['more_info']),
    		'more_brief'	=> addslashes($this->input['more_brief']),
    		'lottery_id'=> intval($this->input['lottery_id']),
    	);
    	$sql = 'SELECT * FROM '.DB_PREFIX.'node_info WHERE id = '.$id ;
    	$pre_data = $this->db->query_first($sql);
    	$data['indexpic'] = $pre_data['indexpic'];
    	$data['more_picture'] = $pre_data['more_picture'];
    	if($_FILES)
    	{
    		if($_FILES['indexpic'])
    		{
	    		$file['Filedata'] = $_FILES['indexpic'];
	    		$material = $this->material->addMaterial($file,$id);
	    		$indexpic = array(
					'host' => $material['host'],
					'dir' => $material['dir'],
					'filepath' => $material['filepath'],
					'filename' => $material['filename'],
				);
				$data['indexpic'] = $indexpic ? serialize($indexpic) : '';
    		}
    		if($_FILES['more_picture'])
    		{
				$_file['Filedata'] = $_FILES['more_picture'];
	    		$_material = $this->material->addMaterial($_file,$id);
	    		$more_picture = array(
					'host' => $_material['host'],
					'dir' => $_material['dir'],
					'filepath' => $_material['filepath'],
					'filename' => $_material['filename'],
				);
				$data['more_picture'] = $more_picture ? serialize($more_picture) : '';
    		}
    	}
    	$sql = 'REPLACE INTO '.DB_PREFIX.'node_info SET ';
    	foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
    	$this->db->query($sql);
    	$affected_rows = $this->db->affected_rows();
    	if($affected_rows)
    	{
	    	$update_user = array(
	    		'update_time'	=> TIMENOW,
	    		'update_user_id'	=> $this->user['user_id'],
	    	);
	    	$this->mode->update($id, 'feedback_node',$update_user);
	    	$this->addLogs('更新表单分类属性', $pre_data, $data);
    	}
    	$this->addItem($data);
    	$this->output();
    }
}
$out = new feedback_node_update();
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