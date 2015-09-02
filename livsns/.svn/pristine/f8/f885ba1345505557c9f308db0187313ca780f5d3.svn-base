<?php
define('MOD_UNIQUEID','reporter_node');
require_once './global.php';
require_once ROOT_PATH . 'frm/node_frm.php';
require_once CUR_CONF_PATH . 'lib/contribute_sort.class.php';
include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
class reporter_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		//检测是否具有管理权限
        $this->verify_node_prms(array('_action'=>'manage'));
        $this->setNodeTable('sort');
		$this->setNodeVar('sort');
		$this->sort = new contribute_sort();
		$this->publish_column = new publishconfig();
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
		if (!$this->input['name'])
		{
			$this->errorOutput(NOSORTNAME);
		}
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['name'])),
			'brief' => trim(urldecode($this->input['brief'])),
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>  hg_getip(),
            'fid'=>intval($this->input['fid']),
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
		$column_id = $this->input['column_id'] ? $this->input['column_id'] : '';
		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
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
				$configs[$key]['direct_forward'] = intval($this->input['con_direct_forward'][$key]);
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
        $this->setExtraNodeTreeFields(array('is_auto','is_open','direct_forward','input_sort','is_userinfo','userinfo','image','column_id'));
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
		if (!$this->input['name']  || trim(urldecode($this->input['name'])=='在这里添加标题'))
		{
			$this->errorOutput(NOSORTNAME);
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
        $this->setExtraNodeTreeFields(array('is_auto','is_open','input_sort','is_userinfo','userinfo','image'));
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
				$configs[$key]['direct_forward'] = intval($this->input['con_direct_forward'][$key]);
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
		$data = array(
			'num'=>intval($this->input['num']),
			'dict'=>$this->settings['con_dictionary'],
		);
		$this->addItem($data);
		$this->output();
	}
}
$out = new reporter_node_update();
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