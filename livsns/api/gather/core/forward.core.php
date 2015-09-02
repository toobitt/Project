<?php
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class gatherForward extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * @Description 通过内容id进行转发
	 * @author Kin
	 * @date 2013-8-29 下午05:15:34
	 */
	public function forward($ids, $setIds = '' ,$forwardAgain = 0)
	{
		if (!$ids)
		{
			return false;
		}
		//获取内容
		$data = $this->_get_Content_by_ids($ids);
		
		if (empty($data))
		{
			return false;
		}
		
		//保留有效的内容
		$id_setId = array();//内容id 和 转发配置id 关系
		
		foreach ($data as $key=>$val)
		{
			if ($setIds)
			{
				$id_setId[$val['id']] = $setIds;
			}
			else 
			{
				if ($val['set_id'])
				{
					$val['set_id'] = unserialize($val['set_id']);
					$val['set_id'] = implode(',', array_keys($val['set_id']));
				}
				$id_setId[$val['id']] = $val['set_id'];
			}
		}
		//检测是否有符合条件的转发配置
		$tmp = array_filter($id_setId);
		if (empty($tmp))
		{
			return false;
		}
		//获取配置id与内容的对应关系
		$setId_id = array();		
		$tem1 = array_unique(explode(',', implode(',', array_filter($id_setId))));
		foreach ($tem1 as $set_id)
		{
			foreach ($id_setId as $key=>$val)
			{
				if ($val)
				{
					$val = explode(',', $val);
					if (in_array($set_id, $val))
					{
						$setId_id[$set_id][] = $key;
					}
				}
			}
		}
		if (empty($setId_id))
		{
			return false;
		}
		//获取配置
		$setIds = implode(',', $tem1);		
		$sets = $this->_get_set_by_ids($setIds);
		
		if (!$sets)
		{
			return false;
		}
		$forward_data = array(); //要转发得数据，批量
		$ret = array();
		
		foreach ($sets as $key=>$val)
		{
			if ($setId_id[$key] && is_array($setId_id[$key]))
			{
				foreach ($setId_id[$key] as $v)
				{
					$forward_data[$v] = $data[$v];
				}
			}
			if (!empty($forward_data))
			{
				$ret[$val['sort_id']][] = $this->forward_curl($val, $forward_data, $forwardAgain);
			}
		}
		
		if (empty($ret))
		{
			return false;
		}		
		//纪录转发关系
		$res = array();
		foreach ($ret as $key=>$val)
		{
			//$key 分类id,$kk配置id，$vvv['cid']内容id，$vvv['rid']转发返回id
			if ($val && is_array($val))
			{
				foreach ($val as $vv)
				{
					if ($vv && is_array($vv))
					{
						foreach ($vv as $kkk=>$vvv)
						{
							if ($vvv && is_array($vvv))
							{
								foreach ($vvv as $vvvv)
								{
									$relation_id = $this->_relation($vvvv['cid'], $key, $kkk, $vvvv['rid']);
									$res[$vvvv['cid']][$kkk] = $relation_id;
								}
							}
						}
					}
				}
			}
			
		}
		
		if (empty($res))
		{
			return false;
		}
		return $res;		
	}
	/**
	 * 
	 * @Description  删除方法
	 * @author Kin
	 * @date 2013-9-3 上午11:13:18
	 */
	public function delete_forward($ids, $setIds = '')
	{
		if (!$ids)
		{
			return false;
		}
		//获取内容
		$data = $this->_get_Content_by_ids($ids);
		
		if (empty($data))
		{
			return false;
		}
		
		//保留有效的内容,获取内容id 和 转发关联id关系
		$id_setId = array();//内容id 和 转发配置id 关系
		if ($setIds)
		{
			$setIds = explode(',', $setIds);
		}
		foreach ($data as $id=>$content)
		{
			if ($setIds)
			{
				if ($content['set_url'])
				{
					$set_url = unserialize($content['set_url']);
					if (is_array($setIds))
					{
						foreach ($setIds as $setid)
						{
							if ($set_url[$setid])
							{
								$id_setId[$id][] = $set_url[$setid];
							}
						}
					}
					if ($id_setId[$id] && is_array($id_setId[$id]))
					{
						$id_setId[$id] = implode(',', $id_setId[$id]);
					}
				}
			}
			else 
			{
				if ($content['set_url'])
				{
					$set_url = unserialize($content['set_url']);
					$set_url = implode(',', $set_url);
				}
				$id_setId[$content['id']] = $set_url;
			}
		}		
		if (empty($id_setId))
		{
			return false;
		}
		//hg_pre($id_setId);exit();
		
		//获取转发关联表得id
		$relation_ids= '';
		foreach ($id_setId as $key=>$val)
		{
			$relation_ids .= $val.',';
		}
		$relation_ids = rtrim($relation_ids, ',');
		//echo $relation_ids;exit();
		

		$relation = $this->_get_relation($relation_ids);
		//hg_pre($relation);exit();
		if (empty($relation))
		{
			return false;
		}

		//获取配置id和rid之间得关系
		$set_rid = array();//设置id和返回id关系
		foreach ($relation as $key=>$val)
		{
			$set_rid[$val['set_id']][] = $val['rid']; 
		}
		//hg_pre($set_rid);exit();
		
		//获取有效得配信息
		$configs = array();
		$config_id = array();//所有配置id
		foreach ($set_rid as $key=>$val)
		{
			$config_id[] = $key;
			$set_rid[$key] = implode(',', $val);
		}
		//hg_pre($set_rid);exit();
		$config_ids = implode(',', $config_id);
		//echo $config_ids;exit();
		$configs = $this->_get_set_by_ids($config_ids);
		if (empty($configs))
		{
			return false;
		}
		
		//根据转发配置id 分组删除数据
		$ret = array(); //返回成功得数据
		$delete_relation_id = array(); //删除成功得relation _id
		
		foreach ($configs as $config)
		{
			
			$ret[] = $this->delete_forward_curl($config, $set_rid[$config['id']]);
		}
		if (empty($ret))
		{
			return false;
		}
		$delete_relation_id = implode(',', $ret);
		//echo $delete_relation_id;exit();
		$this->delete_gather_relation($delete_relation_id);
		$delete_relation_id = explode(',', $delete_relation_id);
		//hg_pre($delete_relation_id);exit();
		
		//数据处理进行返回
		$return = array();
		foreach ($id_setId as $key=>$val)
		{
			if ($val)
			{
				$id_setId[$key] = explode(',', $val);
				foreach ($id_setId[$key] as $k=>$v)
				{
					if (in_array($v, $delete_relation_id))
					{
						$return[$key][] = $v; 
					}
				}
			}
		}
		//hg_pre($return);exit();
		return $return;
	}
	/**
	 * 
	 * @Description  通过内容id获取内容
	 * @author Kin
	 * @date 2013-8-29 下午05:16:04
	 */
	private function _get_Content_by_ids($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT g.*, c.content FROM '.DB_PREFIX.'gather g 
				LEFT JOIN '.DB_PREFIX.'gather_content c ON g.id = c.id 
				WHERE g.id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$data = array();
		while ($row = $this->db->fetch_array($query))
		{
			$data[$row['id']] = $row;
		}
		return $data;	
	}
	/**
	 * 
	 * @Description 通过配置id获取配置信息
	 * @author Kin
	 * @date 2013-8-30 下午03:47:00
	 */
	private function _get_set_by_ids($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather_set WHERE is_open = 1 AND id IN ('.$ids.')';		
		$query = $this->db->query($sql);
		$sets = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['parameter'] = $row['parameter'] ? unserialize($row['parameter']) : '';
			$host = '';
			$dir = '';
			switch ($row['request_type'])
			{
				case 1 : $row['request_type'] = 'get';break;
				case 2 : $row['request_type'] = 'post';break;
			}
			
			if ($row['bundle'] && $row['bundle'] != '-1')
			{
				
				if ($this->settings['App_'.$row['bundle']])
				{
					
					$host = $this->settings['App_'.$row['bundle']]['host'];
					$dir = $this->settings['App_'.$row['bundle']]['dir'];
					
				}
			}
			elseif ($row['host'])
			{
				$host = $row['host'];
				$dir = $row['dir'];
			}
			
			if ($host && $row['parameter'])
			{
				$row['host'] = $host;
				$row['dir'] = $dir;
				$sets[$row['id']] = $row;
			} 
		}
		if (empty($sets))
		{
			return false;
		}
		return $sets;
	}
	
	private function forward_curl($config, $datas, $forwardAgain = 0)
	{
		if (!$config || !$datas)
		{
			return false;
		}
		//检测是否在此转发
		if (!$forwardAgain)
		{
			$arr_ids = array();
			foreach ($datas as $val)
			{
				$arr_ids[] = $val['id'];
			}
			if (!empty($arr_ids))
			{
				$ids = implode(',', $arr_ids);
				$sql = 'SELECT id,cid FROM '.DB_PREFIX.'gather_relation WHERE cid IN ('.$ids.') AND set_id = '.$config['id'];
				$query = $this->db->query($sql);
				$cids = array();
				while ($row = $this->db->fetch_array($query))
				{
					$cids[$row['id']] = $row['cid'];
				}
				if (!empty($cids))
				{
					$arr = array_diff($arr_ids, $cids);					
					if (empty($arr))
					{
						return false;
					}
					foreach ($datas as $key=>$val)
					{
						if (in_array($val['id'], $arr))
						{
							unset($datas[$key]);
						}
					}
				}
			}
		}		
		$return = array();		
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a',$config['funcname']);
		if (is_array($datas) && !empty($datas))
		{
			foreach ($datas as $data)
			{
				if ($config['parameter']['way'] &&is_array($config['parameter']['way']) && !empty($config['parameter']['way']))
				{
				   foreach ($config['parameter']['way'] as $key=>$val)
				   {
				   		if ($val ==1)
				   		{
				   			if ($config['parameter']['dict'][$key] && $config['parameter']['mark'][$key])
				   			{
						   		/*
				   				if ($config['parameter']['dict'][$key]=='index_pic')
								{
									foreach ($data['index_pic'] as $kk=>$vv)
									{
										$curl->addRequestData($config['parameter']['mark'][$key].'['.$kk.']',$vv);
									}
								}elseif ($config['parameter']['dict'][$key]=='video')
								{
									foreach ($data['video'] as $kk=>$vv)
									{
										$curl->addRequestData($config['parameter']['mark'][$key].'['.$kk.']',$vv);
									}
								}elseif ($config['parameter']['dict'][$key]=='picture')
								{
									foreach ($data['picture'] as $kk=>$vv)
									{
										foreach ($vv as $kkk=>$vvv)
										{
											$curl->addRequestData($config['parameter']['mark'][$key].'['.$kk.']'.'['.$kkk.']',$vvv);
										}		
									}
								}else {					
								}
								*/
				   				
				   				$curl->addRequestData($config['parameter']['mark'][$key],$data[$config['parameter']['dict'][$key]]);
				   				
				   			}
				   		}elseif ($val==2)
				   		{
				   			if ($config['parameter']['value'][$key] && $config['parameter']['mark'][$key])
				   			{
				   				$curl->addRequestData($config['parameter']['mark'][$key],$config['parameter']['value'][$key]);
				   			}		
				   		}
				   }
				}
				$ret = $curl->request($config['filename']);
				if (is_array($ret) && !empty($ret) && !$ret['ErrorCode'])
				{
					$return[$config['id']][] = array('cid'=>$data['id'],'rid'=>$ret[0]['id']);
				}
				
			}
		}
		return $return;
	}
	
	private function _relation($cid, $sort_id, $set_id, $rid = 0)
	{
		if (!$cid || !$sort_id || !$set_id || !$rid)
		{
			return false;
		}
		$sql = 'INSERT INTO ' .DB_PREFIX. 'gather_relation (id,cid,sort_id,set_id,rid) 
				VALUES("",'.$cid.','.$sort_id.','.$set_id.','.$rid.')';
		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	/**
	 * 
	 * @Description  获取关系
	 * @author Kin
	 * @date 2013-9-3 下午02:43:22
	 */
	private function _get_relation($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather_relation WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[$row['id']] = $row;
		}
		return $arr;
	}
	
	private function delete_forward_curl($config, $ids)
	{
		if (!$config || !$ids)
		{
			return false;
		}
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a',$config['delete_funcname']);
		$curl->addRequestData('id', $ids);
		$ret = $curl->request($config['filename']);
		$sql = 'SELECT id FROM '.DB_PREFIX.'gather_relation WHERE set_id = '.$config['id'].' AND rid IN ('.$ids.')';	
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[]= $row['id'];
		}
		$return = implode(',', $arr);
		return $return;
	}
	
	public function delete_gather_relation($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'gather_relation WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	
}