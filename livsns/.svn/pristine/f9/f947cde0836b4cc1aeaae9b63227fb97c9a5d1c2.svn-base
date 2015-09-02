<?php
define('MOD_UNIQUEID','contribute_node');
require_once './global.php';
require_once ROOT_PATH . 'frm/node_frm.php';
require_once CUR_CONF_PATH . 'lib/contribute_sort.class.php';		
class contribute_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
        $this->setNodeTable('sort');
		$this->setNodeVar('sort');
		$this->sort = new contribute_sort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}


	public function update() 
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
			'id' 			=> intval($this->input['id']),
			'name' 			=> trim($this->input['name']),
			'brief' 		=> trim($this->input['brief']),
			'update_time' 	=> TIMENOW,
            'user_name'		=> $this->user['user_name'],
            'ip'			=> hg_getip(),
            'fid'			=> intval($this->input['fid']),
        	'repeat_switch'	=> intval($this->input['repeat_switch']),
		);
		if ($this->input['fastinput'])
		{
			$data['is_open'] = 1;
			$sort = $this->input['sort'];
			if ($sort)
			{
				$data['input_sort'] = implode(',', $this->input['sort']);
				$data['sortname'] = $this->sort->get_sortName($data['input_sort']);
			}else {
				$data['input_sort'] = '';
				$data['sortname'] = '';
			}
		}else {
			$data['is_open'] = 0;
			$data['input_sort'] = '';
			$data['sortname'] = '';
		}
		$data['is_auto'] = intval($this->input['auto']) ? intval($this->input['auto']) : 0;
		$data['auto'] = intval($this->input['auto']) ? '开启' : '';
		$data['is_userinfo'] = intval($this->input['userinfo']) ? 1 : 0;
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
		$data['userinfo'] = '';
		$userinfo = '';
		if (!empty($this->input['userinfo']))
		{
			if ($this->settings['userinfo'] && is_array($this->settings['userinfo']))
			{
				foreach ($this->settings['userinfo'] as $key=>$val)
				{
					if (in_array($key, array_keys($this->input['user_info'])))
					{
						$userinfo[$key] = 1;
					}else {
						$userinfo[$key] = 0;
					}
					
				}
			$data['userinfo'] = serialize($userinfo);
			}	
		}
		//检测哪些配置被删除
		$con_ids = $this->sort->getIdBySortid(intval($this->input['id']));
		$con_config_id = $this->input['con_config_id']?$this->input['con_config_id']:array();
		$del_ids = array_diff($con_ids, $con_config_id); 
		if (!empty($del_ids))
		{	
			$this->sort->del_configs(implode(',', $del_ids));
		}
		
        //转发配置
		if ($this->input['con_title'] && is_array($this->input['con_title']) && !empty($this->input['con_title']))
		{
			$configs = array();
			foreach ($this->input['con_title'] as  $key=>$val)
			{
				$configs[$key]['id'] = intval($this->input['con_config_id'][$key]);
				$configs[$key]['sort_id'] =  intval($this->input['id']);
				$configs[$key]['title'] = addslashes(trim($val));
				$configs[$key]['is_open'] = intval($this->input['con_open'][$key]);
				$configs[$key]['host'] = addslashes(trim($this->input['con_host'][$key]));
				$configs[$key]['dir'] = addslashes(trim($this->input['con_dir'][$key]));
				$configs[$key]['filename'] = addslashes(trim($this->input['con_file'][$key]));
				$configs[$key]['protocol'] = intval($this->input['con_protocol'][$key]);
				$configs[$key]['request_type'] = intval($this->input['con_request_type'][$key]);
				$match_rule = array();
				$match_rule = array(
					'name'=>$this->input['con_name'][$key],
					'mark'=>$this->input['con_mark'][$key],
					'dict'=>$this->input['con_dict'][$key],
					'value'=>$this->input['con_value'][$key],
					'way'=>$this->input['con_way'][$key],
				);
				$configs[$key]['match_rule'] = addslashes(serialize($match_rule));
			}
			foreach ($configs as $key=>$val)
			{
				$this->sort->update_configs($val);
			}
		}
		
		
		//初始化
        $this->initNodeData();
        $this->setExtraNodeTreeFields(array('is_auto','is_open','input_sort','is_userinfo','userinfo','image','repeat_switch'));
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
	        //$this->verify_delete_node($this->input['id']);
	    }
		if (!$this->input['id'])
	    {
	    	$this->errorOutput(NOID);
	    }
	    //检测分类下是否存在数据，有数据不允许删除
	    $ret = $this->sort->checkDataBysort($this->input['id']);
	    if ($ret)
	    {
	    	$this->errorOutput('该分类下存在报料数据，不可删除！');
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
		if (!$this->input['name']  || trim($this->input['name'])=='在这里添加标题')
		{
			$this->errorOutput(NOSORTNAME);
		}
		$data = array(
            'ip'				=>hg_getip(),
            'create_time'		=>TIMENOW,
            'fid'				=>intval($this->input['fid']),
            'update_time'		=>TIMENOW,
            'name'				=>trim($this->input['name']),
            'brief'				=>trim($this->input['brief']),
            'user_name'			=>trim($this->user['user_name']),
			'repeat_switch'		=>intval($this->input['repeat_switch']),
		);
       if ($this->input['fastinput'])
		{
			$data['is_open'] = 1;
			if ($this->input['sort'])
			{
				$data['input_sort'] = implode(',', $this->input['sort']);
			}
		}
		$data['is_auto'] = $data['auto'] ? $data['auto'] : 0;
		$data['auto'] = intval($this->input['auto']) ? '开启' : '';
		$data['is_userinfo'] = intval($this->input['userinfo']) ? 1 : 0;
		
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
		$data['userinfo'] = '';
		$userinfo = '';
		if (!empty($this->input['userinfo']))
		{
			if ($this->settings['userinfo'] && is_array($this->settings['userinfo']))
			{
				foreach ($this->settings['userinfo'] as $key=>$val)
				{
					if (in_array($key, array_keys($this->input['user_info'])))
					{
						$userinfo[$key] = 1;
					}else {
						$userinfo[$key] = 0;
					}
					
				}
			$data['userinfo'] = serialize($userinfo);
			}	
		}
		
        $this->initNodeData();
        $this->setExtraNodeTreeFields(array('is_auto','is_open','input_sort','is_userinfo','userinfo','image','repeat_switch'));
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        if($nid = $this->addNode())
        {
			$data['id'] = $nid;
			
        //转发配置
		if ($this->input['con_title'] && is_array($this->input['con_title']) && !empty($this->input['con_title']))
		{
			$configs = array();
			foreach ($this->input['con_title'] as  $key=>$val)
			{
				$configs[$key]['id'] = '';
				$configs[$key]['sort_id'] =  $nid;
				$configs[$key]['title'] = addslashes(trim($val));
				$configs[$key]['is_open'] = intval($this->input['con_open'][$key]);
				$configs[$key]['host'] = addslashes(trim($this->input['con_host'][$key]));
				$configs[$key]['dir'] = addslashes(trim($this->input['con_dir'][$key]));
				$configs[$key]['filename'] = addslashes(trim($this->input['con_file'][$key]));
				$configs[$key]['protocol'] = intval($this->input['con_protocol'][$key]);
				$configs[$key]['request_type'] = intval($this->input['con_request_type'][$key]);
				$match_rule = array();
				$match_rule = array(
					'name'=>$this->input['con_name'][$key],
					'mark'=>$this->input['con_mark'][$key],
					'dict'=>$this->input['con_dict'][$key],
					'value'=>$this->input['con_value'][$key],
					'way'=>$this->input['con_way'][$key],
				);
				$configs[$key]['match_rule'] = addslashes(serialize($match_rule));
			}
			foreach ($configs as $key=>$val)
			{
				$this->sort->update_configs($val);
			}
		}
			
			
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
			}
		}
		$this->addItem('success');
		$this->output();
	}
	public function addconfig()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$data = array(
			'num'=>intval($this->input['num']),
			'protocol'=>$this->settings['con_api_protocol'],
			'request_type'=>$this->settings['con_request_type'],
		);
		$this->addItem($data);
		$this->output();
	}
	public function addparam()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$data = array(
			'num'=>intval($this->input['num']),
			'dict'=>$this->settings['con_dictionary'],
		);
		$this->addItem($data);
		$this->output();
	}
}
$out = new contribute_node_update();
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