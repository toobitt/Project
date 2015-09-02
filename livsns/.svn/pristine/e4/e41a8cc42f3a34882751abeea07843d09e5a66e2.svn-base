<?php
define('MOD_UNIQUEID','gather');//模块标识
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/gather.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
require_once CUR_CONF_PATH . 'core/forward.core.php';
class gatherUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();		
		$this->material = new material();
		$this->gather = new gather();
		$this->forward = new gatherForward();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		/************************节点权限验证开始***************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.intval($this->input['sort_id']).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		/************************节点权限验证结束***************************/
		$content = trim($this->input['content']);
		if (!$content)
		{
			$this->errorOutput("内容不能为空");
		}		
		$data = array(
					'title'			=> $this->input['title'],
					'subtitle'		=> trim($this->input['subtitle']),
					'keywords'		=> trim($this->input['keywords']),
					'brief' 		=> trim($this->input['brief']),
					'author' 		=> trim($this->input['author']),
					'source' 		=> trim($this->input['source']),
					'sort_id' 		=> intval($this->input['sort_id']),
					'indexpic'		=> trim($this->input['indexpic']),
					'pic'			=> trim($this->input['pic']),
					'video'			=> trim($this->input['video']),
					'source_url'	=> trim($this->input['source_url']),
					'appid'   		=> intval($this->user['appid']),
					'appname'  		=> trim($this->user['display_name']),
					'create_time' 	=> TIMENOW,
					'org_id'		=> $this->user['org_id'],
					'user_id'   	=> $this->user['user_id'],
					'user_name'	 	=> $this->user['user_name'],
					'ip' 			=> hg_getip(),
					'update_time' 	=> TIMENOW,
		);
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
		//有直接转发数据插入直接转发队列
		if (!empty($d_forward))
		{
			foreach ($d_forward as $setid)
			{
				$this->gather->insert_gather_plan($id, $setid);
			}
		}
		$data['id'] = $id;
		$data['content'] = $ret;
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$data = array(
					'title'				=> $this->input['title'],
					'subtitle'			=> trim($this->input['subtitle']),
					'keywords'			=> trim($this->input['keywords']),
					'brief' 			=> trim($this->input['brief']),
					'author' 			=> trim($this->input['author']),
					'source' 			=> trim($this->input['source']),
					'sort_id' 			=> intval($this->input['sort_id']),
					'indexpic'			=> trim($this->input['indexpic']),
					'pic'				=> trim($this->input['pic']),
					'video'				=> trim($this->input['video']),
					'source_url'		=> trim($this->input['source_url']),
					'create_time' 		=> TIMENOW,
					'update_org_id'		=> $this->user['org_id'],
					'update_user_id'   	=> $this->user['user_id'],
					'update_user_name'	=> $this->user['user_name'],
					'update_ip' 		=> hg_getip(),
					'update_time' 		=> TIMENOW,
		);
		/**************权限控制开始**************/
		/*
		//获取原纪录
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather WHERE id = '.$id;
		$pre_data = $this->db->query_first($sql);
		*/
		//源数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather WHERE id = '.$id;
		$preData = $this->db->query_first($sql);
		$pre_data = $preData;
		//节点权限
		$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN (' . $preData['sort_id']. ',' . $data['sort_id'] . ')';
		$query = $this->db->query($sql);
		$sortInfo = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sortInfo[$row['id']] = $row['parents'];
		}
		//修改前
		if($preData['sort_id'])
		{
			$node['nodes'][$preData['sort_id']] = $sortInfo[$preData['sort_id']];
		}
		$this->verify_content_prms($node);
		
		//修改后
		if($data['sort_id'])
		{
			$node['nodes'][$data['sort_id']] = $sortInfo[$data['sort_id']];
		}
		$this->verify_content_prms($node);	
		
		//能否修改他人数据
		$arr = array(
				'id'	  => $id,
				'user_id' => $preData['user_id'],
				'org_id'  => $preData['org_id'],
		);
		$this->verify_content_prms($arr);
		/**************权限控制结束**************/
		
		$content = trim($this->input['content']);
		if (!$content)
		{
			$this->errorOutput("内容不能为空");
		}
		
		//验证是否有数据更新
		//主表
		$affected_rows = false;
		$sql = 'UPDATE '.DB_PREFIX.'gather SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$query = $this->db->query($sql);		
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		//内容
		$sql = 'SELECT id FROM '.DB_PREFIX.'gather_content WHERE id = '.$id;
		$con_info = $this->db->query_first($sql);
		if (!$con_info['id'] && $this->input['content'])
		{
			$affected_rows = true;
		}
		if ($con_info['id'])
		{
			$sql = 'UPDATE '.DB_PREFIX.'gather_content SET content = "'.addslashes($this->input['content']).'" WHERE id = '.$con_info['id'];
			$query = $this->db->query($sql);
			if ($this->db->affected_rows($query))
			{
				$affected_rows = true;
			}
		}
		if ($affected_rows)
		{
			$additionalData = array(
				'update_time'		=> TIMENOW,
				'update_org_id'		=> $this->user['org_id'],
				'update_user_id'	=> $this->user['user_id'],
				'update_user_name'	=> addslashes($this->user['user_name']),
				'update_ip'			=> $this->user['ip'],
			);
			/**************权限控制开始**************/
			//修改审核数据后的状态
			if ($preData['status']==1 && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($this->user['prms']['default_setting']['update_audit_content']==1)
				{
					$additionalData['status'] = 0;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
				{
					$additionalData['status'] = 1;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
				{
					$additionalData['status'] = 2;
				}
			}
			$data = array_merge($data,$additionalData);
			/**************权限控制结束**************/
			$res = array_merge($preData, $data);
			//添加日志
			$this->addLogs('更新采集', $preData, $res, $preData['title'], $preData['id'], $preData['sort_id']);
		}
		
		
		
		//如果更换分类，将新分类下的转发配置进行合并
		if ($data['sort_id'])
		{
			$set_id = array();
			$arr = $pre_data['set_id'] ? unserialize($pre_data['set_id']) : array();
			$config = $this->gather->get_config_by_sortId($data['sort_id']);			
			if ($config[$data['sort_id']] && is_array($config[$data['sort_id']]))
			{
				foreach ($config[$data['sort_id']] as $val)
				{
					$set_id[$val['id']] = $val['app_name'];
				}
			}
			if (!empty($set_id))
			{
				foreach ($set_id as $key=>$val)
				{
					$arr[$key] = $val;
				}				
				$data['set_id'] = serialize($arr);
			}
		}
		//更新转发配置信息
		$sql = 'UPDATE '.DB_PREFIX.'gather SET set_id = "'.addslashes($data['set_id']).'" WHERE id = '.$id;
		$this->db->query($sql);
		//$ret = $this->gather->update($data, $content, $id);
		if ($data['status'] == 1)
		{
			$set_url = '';
			$res = $this->forward->forward($id);
			if ($res && $res[$id])
			{
				$set_url = serialize($res[$id]);
				$this->gather->update_set_url($set_url, $id);
			}
		}
		else
		{
			$this->forward->delete_forward($ids);
		}
		$this->addItem(true);
		$this->output();
	}

	//删除采集内容
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			return false;
		}
		//查询原数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$gather = array();
		$recycle = array();
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$gather[] = $row;
			$recycle[$row['id']] = array(
				'cid'=>$row['id'],
				'title'=>$row['title'],
				'delete_people'=>$this->user['user_name'],
			);
			$recycle[$row['id']]['content']['gather'] = $row;
		}
		//节点权限验证
		if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sorts = array_filter($sorts);
			if (!empty($sorts))
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN ('.implode(',',$sorts).')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
				if (!empty($nodes))
				{
					$this->verify_content_prms($nodes);
				}
			}
		}
		//能否修改他人数据
		if (!empty($gather) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($gather as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		//放入回收站
		if ($this->settings['App_recycle'] && !empty($recycle))
		{			
			require_once(ROOT_PATH.'lib/class/recycle.class.php');
			$this->recycle = new recycle();
			foreach ($recycle as $infor)
			{
				$ret = $this->recycle->add_recycle($infor['title'], $infor['delete_people'], $infor['cid'], $infor['content']);
				$result = $ret['sucess'];
				$is_open = $ret['is_open'];
			}
			if (!$result)
			{
				$this->errorOutput('删除失败，数据不完整');
			}
			if ($is_open)
			{
				//删除主表
				$sql = 'DELETE FROM '.DB_PREFIX.'gather WHERE id IN ('.$ids.')';
				$this->db->query($sql);
				$data = $ids;
			}
			else
			{
				$data = $this->gather->delete($ids);
			} 
		}
		else
		{
			$data = $this->gather->delete($ids);
		}
		$this->addLogs('删除采集信息',$gather,'', '删除采集信息' . $ids);
		//$data = $this->gather->delete($ids);
		//删除转发数据
		$this->forward->delete_forward($ids);
		$this->addItem($data);
		$this->output();
	}
	
	public function delete_comp()
	{
		$ids = $this->input['cid'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->gather->delete($ids);
		$this->addItem($data);
		$this->output();
	}
	
	public function audit()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		//节点权限验证
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		$pre_data = array();
		$nodes = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$pre_data[] = $row;	
		}
		if (!empty($sorts))
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		
		$status = intval($this->input['audit']);
		$status = $status ? $status : 2;
		$data = $this->gather->audit($ids,$status);
		
		//添加日志
		$new_data = array();
		if ($status == 1)
		{
			if (!empty($pre_data))
			{
				foreach ($pre_data as $key=>$val)
				{
					$val['status'] = 1;
					$new_data[$key] = $val;
				}
			}
			$this->addLogs('审核采集', $pre_data, $new_data,'审核采集'.$ids);
		}
		if ($status == 2)
		{
			if (!empty($pre_data))
			{
				foreach ($pre_data as $key=>$val)
				{
					$val['status'] = 2;
					$new_data[$key] = $val;
				}
			}
			$this->addLogs('打回采集', $pre_data, $new_data,'打回采集'.$ids);
		}
		//转发处理
		$sql = 'SELECT id,set_url FROM '.DB_PREFIX.'gather WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['set_url'] = $row['set_url'] ? unserialize($row['set_url']) : array();
			$arr[$row['id']] = $row['set_url'];
		}
		if ($status == 1)
		{
			$res = $this->forward->forward($ids);
			$arr_id  = explode(',', $ids);
			foreach ($arr_id as $id)
			{
				$set_url = '';
				if ($arr[$id])
				{
					foreach ($arr[$id] as $key=>$val)
					{
						$res[$id][$key] = $val;
					}
				}
				if ($res[$id])
				{
					$set_url= serialize($res[$id]);
				}
				$this->gather->update_set_url($set_url, $id);
			}
		}
		else 
		{
			//打回时 删除转发数据
			$ret = $this->forward->delete_forward($ids);
			foreach ($arr as $key=>$val)
			{
				$temp = array();
				if ($ret[$key])
				{
					$temp = array_diff($val, $ret[$key]);
				}
				if (empty($temp))
				{
					$temp = '';
				}
				else
				{
					$temp = serialize($temp); 
				}
				$this->gather->update_set_url($temp, $key);
			}
		}
		$this->addItem($data);
		$this->output();
	}

    /**
     * 本地化图片 ...
     * @name        img_local
     * @copyright   hogesoft
     */
    function img_local()
    {
        if(!$this->input['url'])
        {
            $this->errorOutput('请传入URL');
        }
        $url = urldecode($this->input['url']);
        $water_id = urldecode($this->input['water_id']);                //如果设置了水印则要传水印id
        $material = $this->material->localMaterial($url,0,0,$water_id);    //调用图片服务器本地化接口
        if(!empty($material))
        {
            $url_arr = explode(',', $url);
            $info = array();
            foreach ($material as $k => $v)
            {
                if(!empty($v))
                {
                    if(in_array($v['remote_url'], $url_arr))
                    {
                        $info[$v['remote_url']] = array('id' => $v['id'],'remote_url'=>$v['remote_url'],'path' => $v['host'].$v['dir'],'dir' => $v['filepath'],'filename' => $v['filename'],'error' => $v['error']);
                    }
                }
            }
        }
        if(!empty($info))
        {
            $this->addLogs('文稿本地化图片','',$material, $material['name']);
            $this->addItem($info);
            $this->output();
        }
        else
        {
            $this->errorOutput('图片本地化失败');
        }
    }
	
	public function sort()
	{
		$this->addLogs('更改采集排序', '', '', '更改采集排序');
		$ret = $this->drag_order('gather', 'order_id');
		$this->addItem($ret);
		$this->output();
	}	
	
	public function publish(){}
	
	public function unknow()
	{		
		$this->errorOutput("此方法不存在！");
	}	
	
}
$out = new gatherUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
