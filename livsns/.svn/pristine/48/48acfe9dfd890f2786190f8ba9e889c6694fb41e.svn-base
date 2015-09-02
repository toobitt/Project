<?php
class workload_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//部门一段时间内的发稿量
	public function get_total($condition = '',$orderby = '',$group = '')
	{
		$field = " sum(count) as count , sum(statued) as statued, {$group} ";
		$groupby = ' GROUP BY '.$group;
		$sql = "SELECT ".$field." FROM " . DB_PREFIX . "workload_total  WHERE 1 " . $condition .$groupby. $orderby;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['count'] = intval($r['count']);
			$r['statued'] = intval($r['statued']);
			$data[$r[$group]] = $r;
		}
		return $data;
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT COUNT(distinct user_id) as total FROM " . DB_PREFIX . "workload  WHERE 1 " . $condition ;
		$query = $this->db->query_first($sql);
		$total = $query['total'];
		$field = ' id, user_id ,user_name,avatar ,sum(count) as count ,sum(statued) as statued,sum(unstatued) as unstatued,sum(publish) as publish,sum(published) as published ';
		$group = ' GROUP BY user_id ';
		$sql = "SELECT ".$field." FROM " . DB_PREFIX . "workload  WHERE 1 " . $condition .$group. $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['avatar'] = $r['avatar'] ? unserialize($r['avatar']) : array();
			$info[] = $r;
		}
		$group = ' GROUP BY user_id ,app_uniquedid';
		$sql = "SELECT w.user_id, wd.app_uniquedid , sum(wd.count) as count ,sum(wd.statued) as statued,sum(wd.unstatued) as unstatued,sum(wd.publish) as publish,sum(wd.published) as published  FROM " . DB_PREFIX . "workload w LEFT JOIN " . DB_PREFIX . "work_detail wd ON w.id = wd.wid  WHERE 1 " . $condition .$group. $orderby;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			if($r['app_uniquedid'])
			{
				$appcount[$r['user_id']][$r['app_uniquedid']] = $r['count'];
			}
		}
		if($info)
		{
			foreach ($info as $k=>$v)
			{
				$info[$k]['appcount'] = $appcount[$v['user_id']] ? $appcount[$v['user_id']] :array() ;
			}
		}
		$data['data'] = $info;
		$data['total'] = $total;
		return $data;
	}
	
	//部门一段时间内的发稿量
	public function get_org_count($condition = '',$orderby = '',$node = array())
	{
		$date = $this->setDate();
		if(!$date)
		{
			return false;
		}
		$group = $date['date_type'];
		$orgs = $node['id'];
		$field = ' org_id, sum(count) as count, sum(statued) as statued,'.$group;
		$groupby = ' GROUP BY org_id,'.$group ;
		$sql = "SELECT ".$field." FROM " . DB_PREFIX . "workload_org  WHERE 1 " . $condition .$groupby. $orderby;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$org_id = $node['parent'][$r['org_id']];
			$info[$org_id][$r[$group]] = intval($r['count']);
			$statued[$org_id][$r[$group]] = intval($r['statued']);
			$total[$org_id] += $r['count'];
		}
		if($orgs)
		{
			foreach ($orgs as $k=>$v)
			{
				$nchild = $node['nchild'][$k];
				$nchild_arr = explode(',', $nchild);
                                if(count($nchild_arr) > 1)
                                {
                                        $info[$k][$appuni] = 0;
                                        foreach ($nchild_arr as $vv)
                                        {
                                                $info[$k][$appuni] += $cou[$vv][$appuni];
                                        }
                                }
            }
			foreach ($orgs as $k=>$v)
			{
				if($date['date_time'])
				{
					foreach ($date['date_time'] as $rr)
					{
						$keys = $group == 'date' ? date('d',$rr) : $rr.$this->settings['date_zh'][$group];
						$data[$k]['count'][] = array(
								'date'	=> $keys,
								'count'	=> intval($info[$k][$rr]),
								'statued'	=> intval($statued[$k][$rr])
						);
					}
				}
				$data[$k]['name'] = $v;
				$data[$k]['total'] =  $total[$k] ? $total[$k] : 0;
				$data[$k]['id'] = $k;
			}
		}
		return $data;
	}

	//部门一段时间内的发稿量(根据应用)
	public function get_orgs_precount($condition = '',$orderby = '',$node = array())
	{
		$orgs = $node['id'];
		$apps = $this->get_appname($this->input['apps']);
		$sql = "SELECT org_id,SUM(count) as count,app_uniquedid FROM " . DB_PREFIX . "workload_org WHERE 1 " . $condition .' GROUP BY org_id,app_uniquedid '. $orderby;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$org_id = $node['parent'][$r['org_id']];
			$info[$org_id][$r['app_uniquedid']] += intval($r['count']);
			$total[$org_id] += $r['count'];
		}
 		if($orgs && $apps)
		{
		 	foreach ($apps as $appuni=>$vname)
            {
            	foreach ($orgs as $k=>$v)
            	{
            		$nchild = $node['nchild'][$k];
            		$nchild_arr = explode(',', $nchild);
            		if(count($nchild_arr) > 1)
            		{
            			$info[$k][$appuni] = 0;
            			foreach ($nchild_arr as $vv)
            			{
            				$info[$k][$appuni] += $cou[$vv][$appuni];
            			}
            		}
            	}
            }
			foreach ($apps as $appuni=>$vname)
			{
				foreach ($orgs as $k=>$v)
				{
					$ret[$appuni]['id'] = $appuni;
					$ret[$appuni]['name'] = $vname['name'];
					$ret[$appuni]['color'] = $vname['color'];
					$ret[$appuni]['count'][$k] = $info[$k][$appuni] ? $info[$k][$appuni] : 0;
				}
			}
			$data['org'] = $orgs;
			$data['count'] = $ret;
		}
		return $data;
	}
	
	public function get_one_org($id ,$condition = '',$node = array())
	{
		$ids = $node['child'];
		$condition .= $ids ? ' AND org_id in( '.$ids.')' : ' AND org_id = '.$id;
		$sql = 'SELECT SUM(count) as count,SUM(statued) as statued,SUM(unstatued) as unstatued,SUM(publish) as publish,SUM(published) as published FROM '.DB_PREFIX.'workload_org WHERE 1 '.$condition ;
		$query = $this->db->query_first($sql);
		$ret['info'] = array(
			'count'	=> intval($query['count']),
			'statued'	=> intval($query['statued']),
			'unstatued'	=> intval($query['unstatued']),
			'publish'	=> intval($query['publish']),
			'published'	=> intval($query['published']),
		);
		
		$sql = 'SELECT SUM(count) as count,app_uniquedid,sum(statued) as statued  FROM '.DB_PREFIX.'workload_org WHERE 1 '.$condition .' GROUP BY app_uniquedid';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$data[$r['app_uniquedid']]['count'] = $r['count'];
			$data[$r['app_uniquedid']]['statued'] = $r['statued'];
		}
		
		$sql = 'SELECT  count(distinct user_id) as person_count ,sum(count) as total FROM '.DB_PREFIX.'workload WHERE 1 AND org_id in( '.$ids.')';
		$query = $this->db->query_first($sql);
		$ret['info']['person_count'] = intval($query['person_count']);
		$ret['info']['total']	= intval($query['total']);
		$ret['info']['id']	= $id;
		$ret['info']['name']	= $node['id'][$id];
		
		$sql = 'SELECT sum(count) as count ,sum(statued) as statued ,user_id,user_name, sum(click_num) as click_num , sum(comment_num) as comment_num FROM '.DB_PREFIX.'workload WHERE 1 AND org_id in( '.$ids.')'.$condition .' GROUP BY user_id ORDER BY count DESC ,statued DESC LIMIT 10';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$ret['sort'][] = array(
					'user_id'		=> $r['user_id'],
					'user_name'		=> $r['user_name'],
					'count'			=> $r['count'],
				);
			$ret['info']['click_num'] += intval($r['click_num']);
			$ret['info']['comment_num'] += intval($r['comment_num']);
		}
		$apps = $this->get_appname($this->input['apps']);
		if($apps)
		{
			foreach ($apps as $appuni=>$vname)
			{
				$ret['apps'][] = array(
						'count'	=> intval($data[$appuni]['statued']),
						'name'	=> $vname['name'],
						'color'	=> $vname['color'],
						'statued' => intval($data[$appuni]['statued']),
				);
			}
		}
		return $ret;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "workload SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."workload SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	
	public function insert_data($data , $table)
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "{$table} SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "workload WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "workload SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($user_id = '',$condition = '')
	{
		if(!$user_id)
		{
			return false;
		}
		$sql = 'SELECT user_id, user_name, SUM(count) as count,SUM(statued) as statued ,SUM(unstatued) as unstatued,SUM(publish) as publish,SUM(published) as published ,SUM(click_num) as click_num ,SUM(comment_num) as comment_num FROM '.DB_PREFIX.'workload WHERE 1 AND user_id = '.$user_id ;
		$tt = $this->db->query_first($sql);
		if(!$tt['user_id'])
		{
			return false;
		}
		$sql .= $condition;
		$query = $this->db->query_first($sql);
		$ret['info'] = array(
			'id'		=> $user_id,
			'name'		=> $query['user_name'],
			'count'		=> intval($query['count']),
			'statued'	=> intval($query['statued']),
			'unstatued'	=> intval($query['unstatued']),
			'publish'	=> intval($query['publish']),
			'published'	=> intval($query['published']),
			'click_num'		=> intval($query['click_num']),
			'comment_num'	=> intval($query['comment_num']),
			'total'		=> intval($tt['count']),
		
		);
		$sql = 'SELECT user_id ,SUM(statued) as statued FROM '.DB_PREFIX.'workload WHERE 1'.$condition.' GROUP BY user_id ORDER BY statued DESC ';
		$q = $this->db->query($sql);
		$i = $statued = 0;
		while($o = $this->db->fetch_array($q))
		{
			if($o['statued'] != $statued)
			{
				$statued = $o['statued'];
				$i++;
			}
			$orders[$o['user_id']] = $i;
		}
		$ret['info']['order'] = $orders[$user_id];
		
		$sql = "SELECT SUM(wd.count) as count ,SUM(wd.statued) as statued ,app_uniquedid FROM " . DB_PREFIX . "workload w LEFT JOIN ".DB_PREFIX."work_detail wd ON wd.wid = w.id WHERE 1 AND w.user_id = ".$user_id. $condition .' GROUP BY app_uniquedid ';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$dat[$r['app_uniquedid']]['count'] = intval($r['count']);
			$dat[$r['app_uniquedid']]['statued'] = intval($r['statued']);
		}
		$appname = $this->get_appname($this->input['apps']);
		if($appname)
		{
			foreach ($appname as $appuni=>$vname)
			{
				$ret['apps'][] = array(
					'name'	=> $vname['name'],
					'color'	=> $vname['color'],
					'count'	=> $dat[$appuni] ? $dat[$appuni]['count'] : 0,
					'statued'	=> $dat[$appuni] ? $dat[$appuni]['statued'] : 0,
				);
			}
		}
		return $ret;
	}
	
	public function get_person_total($user_id,$condition = '')
	{
		if(!$user_id)
		{
			return false;
		}
		$date = $this->setDate();
		if(!$date)
		{
			return false;
		}
		$group = $date['date_type'];
		$condition .= ' AND user_id = '.$user_id;
		//$sql = 'SELECT user_name,SUM(count) as count ,SUM(statued) as statued ,'.$group.' FROM '.DB_PREFIX.'workload WHERE 1 '.$condition .' GROUP BY '.$group;
		$sql = 'SELECT user_name,SUM(wd.count) as count ,SUM(wd.statued) as statued ,'.$group.' FROM '.DB_PREFIX.'work_detail wd LEFT JOIN '.DB_PREFIX.'workload w ON wd.wid = w.id WHERE 1 '.$condition .' GROUP BY '.$group;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$data[$r[$group]] = $r['count'];
			$statued[$r[$group]] = $r['statued'];
			$total += intval($r['count']);
			$user_name = $r['user_name'];
		}
		if($date['date_time'])
		{
			foreach ($date['date_time'] as $rr)
			{
				$keys = $group == 'date' ? date('m.d',$rr) : $rr.$this->settings['date_zh'][$group];
				$ret['count'][] = array(
					'date'	=> $keys,
					'count'	=> intval($data[$rr]),
					'statued'	=> intval($statued[$rr]),
				);
			}
		}
		$ret['total'] = $total;
		$ret['id'] = $user_id;
		$ret['name'] = $user_name;
		return $ret;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "workload WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "workload WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "workload WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "workload WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "workload SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	public function get_org_by_ids($authnode_str)
	{
		if(!$this->settings['App_auth']['host'])
		{
			return false;
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','getMergeParentsTree');
		$curl->addRequestData('ids',$authnode_str);
		$back = $curl->request("admin/admin_org.php");
		$back = $back[0];
		return $back;
	}
	
	//获取部门信息  支持多个id且可分页
	public function get_orgs($authnode_str = 0,$fid =0,$count = 10,$offset = 0)
	{
		$count = $this->input['count'] ? intval($this->input['count']) : $count;
		$offset = $this->input['offset'] ? intval($this->input['offset']) : $offset;
		if(!$this->settings['App_auth']['host'])
		{
			return false;
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','getOrgs');
		$curl->addRequestData('offset',$offset);
		$curl->addRequestData('count',$count);
		$curl->addRequestData('ids',$authnode_str);
		$curl->addRequestData('fid',$fid);
		$back = $curl->request("admin/admin_org.php");
		$back = $back[0];
		return $back;
	}
	
	public function get_operation($user_id,$condition = '')
	{
		$condition = ' AND user_id = '.$user_id;
		$order = ' ORDER BY date DESC';
		$sql = "SELECT * FROM " . DB_PREFIX . "operation WHERE 1 ". $condition . $order;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$action_name = $this->settings['operation'][$r['action']] ? $this->settings['operation'][$r['action']] : '';
			$r['date'] = date('Y年m月d日',$r['date']);
			if(!$action_name)
			{
				$count += $r['count'];
				$ret['total']['其他'] = $count;
			}else{
				$ret['total'][$action_name] += $r['count'];
			}
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "operate_detail WHERE 1 ". ' AND user_id = '.$user_id . $order;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['date'] = date('Y年m月d日',$r['date']);
			$ret['date'][$r['date']][$r['operation_name']] = $r['count'];
		}
		return $ret;
	}
	
		
	public function get_appname($apps='')
	{
		$sql = 'SELECT app_uniqueid,name,color FROM '.DB_PREFIX.'appset WHERE 1';
		$sql .= ' AND state = 1';
		if($apps)
		{
			$apps = str_replace(',','","',$apps);
			$sql .= ' AND app_uniqueid in("'.$apps.'")';
		}
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			$data[$r['app_uniqueid']]['name'] = $r['name'];
			$data[$r['app_uniqueid']]['color'] = $r['color'];
		}
		return $data;
	}
	
	public function getAllOrgs($offset = 0,$count = 20,$fid=0,$access_token='')
	{
		if(!$this->settings['App_auth']['host'])
		{
			return false;
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		$curl->addRequestData('offset',$offset);
		$curl->addRequestData('count',$count);
		$curl->addRequestData('fid',$fid);
		$curl->addRequestData('access_token',$access_token);
		$back = $curl->request("admin/admin_org.php");
		return $back;
	}
	
	public function getOrgsCount($con)
	{
		if(!$this->settings['App_auth']['host'])
		{
			return false;
		}
	}
	
	//获取某部门最近一段时间发稿信息
	public function get_one_org_count($id,$condition = '',$orderby = '',$node = array())
	{
		$date = $this->setDate();
		if(!$date)
		{
			return false;
		}
		$group = $date['date_type'];
		$groupby = ' GROUP BY org_id,'.$group ;
		$orderby = '  ORDER BY '. $group .' DESC';
		$sql = "SELECT sum(count) as count,sum(statued) as statued,".$group .' FROM '. DB_PREFIX . "workload_org  WHERE 1 " . $condition .$groupby. $orderby;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$info[$r[$group]] = intval($r['count']);
			$statued[$r[$group]] = intval($r['statued']);
			$total += intval($r['count']);
		}
		if($date['date_time'])
		{
			foreach ($date['date_time'] as $rr)
			{
				$keys = $group == 'date' ? date('m.d',$rr) : $rr.$this->settings['date_zh'][$group];
				$data['count'][] = array(
					'date'		=> $keys,
					'count'		=> intval($info[$rr]),
					'statued'	=> intval($statued[$rr]),
				);
			}
		}
		else if($info)
		{
			foreach ($info as $k=>$v)
			{
				$keys = $group == 'date' ? date('m.d',$k) : $k;
				$data['count'][] = array(
					'date'		=> $keys,
					'count'		=> intval($info[$rr]),
				);
			}
		}
		$data['name'] = $node['id'][$id];
		$data['total'] =  $total ? $total : 0;
		$data['id'] = $id;
		return $data;
	}
	
	public function get_person($condition = '',$limit = '')
	{
		$orderby = '  ORDER BY statued DESC ,user_id ASC';
		//$sql = 'SELECT user_id, sum(count) as count FROM '. DB_PREFIX . "workload  WHERE 1 " . $condition .' GROUP BY user_id'. $orderby.$limit;
		$sql = 'SELECT user_id,user_name,SUM(wd.count) as count ,SUM(wd.statued) as statued FROM '.DB_PREFIX.'workload w LEFT JOIN '.DB_PREFIX.'work_detail wd ON wd.wid = w.id WHERE 1 '.$condition .'  GROUP BY user_id'. $orderby.$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$users[] = $r['user_id'];
			$total_order[$r['user_id']] = intval($r['count']);
		}
		if(!$users)
		{
			return false;
		}
		$user_ids = implode(',',$users);
		$date = $this->setDate();
		if(!$date)
		{
			return false;
		}
		$group = $date['date_type'];
		$groupby = ' GROUP BY user_id,'.$group ;
		$condition .= ' AND user_id in('.$user_ids.')';
		$sql = "SELECT user_id,user_name,avatar, sum(wd.count) as count,sum(wd.statued) as statued,".$group .' FROM '.DB_PREFIX.'workload w LEFT JOIN '.DB_PREFIX.'work_detail wd ON wd.wid = w.id  WHERE 1 ' . $condition .$groupby;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$username[$r['user_id']] = $r['user_name'];
			$avatar[$r['user_id']] = $r['avatar'] ? unserialize($r['avatar']) : array();
			$info[$r['user_id']][$r[$group]]['total'] = intval($r['count']);
			$info[$r['user_id']][$r[$group]]['statued'] = intval($r['statued']);
		}
		if($date['date_time'] && $total_order)
		{
			foreach ($total_order as $user_id=>$v)
			{
				foreach ($date['date_time'] as $rr)
				{
					$keys = $group == 'date' ? date('d',$rr) : $rr.$this->settings['date_zh'][$group];
					$data[$user_id]['count'][] = array(
							'count'		=> intval($info[$user_id][$rr]['total']),
							'statued'	=> intval($info[$user_id][$rr]['statued']),
							'date'		=> $keys,
					);
					$data[$user_id]['name']	= $username[$user_id];
					$data[$user_id]['avatar']	= $avatar[$user_id];
					$data[$user_id]['total']	= $v;
					$data[$user_id]['id']	= $user_id;
				}
			}
		}
		return $data;
	}
	
	public function setDate()
	{
		$date_search = $this->input['date_search'];
		$today = strtotime(date('Y-m-d'));
		$month = date('m');
		switch ($date_search)
		{
			case 1: //本周
				$date_type = 'date';
				$mon = $today-((date('w')==0?7:date('w'))-1)*86400; //周一
				$date = array($mon,$mon+86400,$mon+2*86400,$mon+3*86400,$mon+4*86400,$mon+5*86400,$mon+6*86400); //本周7日
				break;
			case 2: //本月
				$date_type = 'week';
				$toweek = intval(date('W',strtotime(date('Y-m-01')))); //本月第一周
				$endweek = intval(date('W',strtotime(date('Y-m-01').' +1 month -1 day'))); //本月最后一天
				if($endweek === 1 && $month == '12')
				{
					$endweek = intval(date('W',strtotime("last sunday", strtotime(date('Y-12-31'))))) + 1;
				}
				if($month == '01')
				{
					$toweek = 1;
				}
				for($i = $toweek ; $i<= $endweek ; $i++)
				{
					$date[] = $i;
				}
				break;
			case 3://本季度 quarter
				$date_type = 'month';
				$quarter = ceil(date('m')/3);
				$quarter_month = array(
					1=>array(1,2,3),	//	第一季度
					2=>array(4,5,6),	//	第二季度
					3=>array(7,8,9),	//	第三季度
					4=>array(10,11,12),);//	第四季度
				$date = $quarter_month[$quarter];	
				break;
			case 4://本年
				$date_type = 'month';
				$date = array(1,2,3,4,5,6,7,8,9,10,11,12);
				break;
			case 5://本年
				$start_time = strtotime($this->input['start_time']);
				$end_time = strtotime($this->input['end_time']);
				$end_time = $end_time ? $end_time : TIMENOW;
				$days = ceil(($end_time - $start_time)/86400);
				$years = date('y',$end_time) - date('y',$start_time) + 1;
				if($years < 2)
				{
					$months = date('m',$end_time) - date('m',$start_time) + 1 ;
				}else 
				{
					$months = date('m',$end_time) - date('m',$start_time) + ($years-1)*12 ;
				}
				if($days < 20 )
				{
					$date_type = 'date';
					for($i = $start_time;$i <= $end_time;$i = $i+86400)
					{
						$date[] = $i;
					}
				}
				elseif($days >= 20 && $months < 3 && $years < 2)
				{
					$date_type = 'week';
					$toweek = intval(date('W',$start_time)); //第一周
					$endweek = intval(date('W',$end_time)); //最后一天
					if($endweek === 1)
					{
						$endweek = intval(date('W',strtotime("last sunday", strtotime(date('Y-12-31')))));
					}
					for($i = $toweek ; $i<= $endweek ; $i++)
					{
						$date[] = $i;
					}
				}
				elseif($months >= 3 && $years < 2)
				{
					$date_type = 'month';
					$tomonth = intval(date('m',$start_time)); //第一月
					$endmonth = intval(date('m',$end_time)); //最后一月
					for($i = $tomonth ; $i<= $endmonth ; $i++)
					{
						$date[] = $i;
					}
				}
				else
				{
					$date_type = 'year';
					$toyear = intval(date('Y',$start_time)); //第一月
					$endyear = intval(date('Y',$end_time)); //最后一月
					for($i = $toyear ; $i<=$endyear; $i++)
					{
						$date[] = $i;
					}
				}
				break;
			default://默认最近7天数据
				$date_type  = 'date';
				$date = array($today-7*86400,$today-86400*6,$today-86400*5,$today-86400*4,$today-86400*3,$today-86400*2,$today-86400); //本周7日
				break;
		}
		$ret['date_type'] = $date_type;
		$ret['date_time'] = $date;
		return $ret;
	}
}
?>