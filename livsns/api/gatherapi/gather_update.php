<?php
define('MOD_UNIQUEID','gather_update');//模块标识
require_once './global.php';
require_once CUR_CONF_PATH.'lib/gather.class.php';
require_once CUR_CONF_PATH.'core/forward.core.php';
class gatherUpdateApi extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->gather = new gather();
		$this->forward = new gatherForward();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
					'title'			=> trim($this->input['title']),
					'subtitle'		=> trim($this->input['subtitle']),
					'keywords'		=> str_replace(' ',',',trim($this->input['keywords'])),
					'brief' 		=> trim($this->input['brief']),
					'author' 		=> trim($this->input['author']),
					'source' 		=> trim($this->input['source']),
					'sort_id' 		=> intval($this->input['sort_id']),
					'appid'   		=> intval($this->user['appid']),
					'appname'  		=> trim($this->user['display_name']),
					'indexpic'		=> trim($this->input['indexpic']),
					'pic'			=> trim($this->input['pic']),
					'video'			=> trim($this->input['video']),
					'material_ids'	=> trim($this->input['material_ids']),
					'source_url'	=> trim($this->input['source_url']),
					'create_time' 	=> TIMENOW,
					'org_id'		=> $this->user['org_id'],
					'user_id'   	=> $this->user['user_id'],
					'user_name'	 	=> $this->user['user_name'],
					'ip' 			=> hg_getip(),
					'update_time' 	=> TIMENOW,
		);
		$content = trim($this->input['content']);
		if (!$content)
		{
			$this->errorOutput("内容不能为空");
		}
		$data['title'] = $data['title'] ? $data['title'] : hg_cutchars($content,20);
		$d_forward = array();//记录直接转发数据
		//获取转发配置
		if ($data['sort_id'])
		{
			$set_id = array();
			$config = $this->gather->get_config_by_sortId($data['sort_id']);
			if ($config[$data['sort_id']] && is_array($config[$data['sort_id']]))
			{
				foreach ($config[$data['sort_id']] as $val)
				{
					$set_id[$val['id']] = $val['app_name'];
					if ($val['is_open'] && $val['is_relay'])
					{
						$d_forward[] = $val['id'];
					}
				}
			}
			if (!empty($set_id))
			{
				$data['set_id'] = serialize($set_id);
			}
		}
		//插入主表
		$id = $this->gather->insert_gather($data);
		//插入内容表
		$ret = $this->gather->insert_content($content, $id);
		$data['id'] = $id;
		$data['content'] = $ret;
		//有直接转发数据插入直接转发队列
		if (!empty($d_forward))
		{
			if (!$this->input['is_plan'])
			{
				$res = $this->forward->forward($id);
				if ($res && $res[$id])
				{
					$set_url = serialize($res[$id]);
					$this->gather->update_set_url($set_url, $id);
				}
			}
			else
			{
				foreach ($d_forward as $setid)
				{
					$this->gather->insert_gather_plan($id, $setid);
				}
			}
			
		}
		$this->addItem($data);
		$this->output();		
	}
	
	public function update()
	{
	
	}
	
	public function delete()
	{
		$data = $this->gather->get_config_by_sortId($this->input['id']);
		$this->addItem($data);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在');
	}
}
$ouput= new gatherUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
