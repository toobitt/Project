<?php
define('MOD_UNIQUEID','lbs_node');
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once CUR_CONF_PATH . 'lib/lbs_node.class.php';	
class LBSNodeUpdate extends nodeFrm
{
    public function __construct()
    {
    	parent::__construct();
   		$this->setNodeTable('sort');
        $this->setNodeVar('lbs_node');
        $this->sort = new ClassLBSSort();
    }

    public function __destruct()
    {
    	parent::__destruct();
    }


    public function update() 
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
    	if ($this->input['bgimage'] && !$_FILES['Filedata'])
		{
			//删除图片
			$this->sort->delSortImage(intval($this->input['id']));
		}
    	//图片
		if ($_FILES['Filedata'])
		{
			$ret = $this->sort->uploadToPicServer($_FILES, intval($this->input['id']));
			if ($ret)
			{
				$image = array(
				'host'=>$ret['host'],
				'dir'=>$ret['dir'],
				'filepath'=>$ret['filepath'],
				'filename'=>$ret['filename'],
				);
				$data['image'] = serialize($image);
			}
			
		}
		//初始化
		$this->initNodeData();
		$this->setExtraNodeTreeFields(array('image'));
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
		//查询主分类
		$sql = 'SELECT id FROM '.DB_PREFIX.'sort WHERE fid=0';
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
    	if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        //判读该分类是否在使用
       	$sql = 'SELECT sort_id FROM ' . DB_PREFIX . 'lbs WHERE sort_id = '.intval($this->input['id']);
       	$ret = $this->db->query_first($sql);
    	if ($ret['sort_id'])
       	{
       		$this->errorOutput('有数据正在使用此分类，不能删除');
       	}
        $this->initNodeData();
        //判断是否成功删除
        if($this->batchDeleteNode($this->input['id']))
        {
        	$this->sort->delete('fieldbind', array('sort_id' => $this->input['id']));//清除附加信息绑定关系
            $this->addItem(array('id' => urldecode($this->input['id'])));
        }
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
    	if ($_FILES['Filedata'])
		{
			$ret = $this->sort->uploadToPicServer($_FILES, intval($this->input['id']));
			if ($ret)
			{
				$image = array(
				'host'=>$ret['host'],
				'dir'=>$ret['dir'],
				'filepath'=>$ret['filepath'],
				'filename'=>$ret['filename'],
				);
				$data['image'] = serialize($image);
			}
			
		}
        if (!$data['name'])
        {
        	$this->errorOutput(NOSORTNAME);
        }
		$this->initNodeData();
		$this->setExtraNodeTreeFields(array('image'));
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
$out = new LBSNodeUpdate();
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