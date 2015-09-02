<?php
class notice extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = 'SELECT * FROM ' .DB_PREFIX . 'notice WHERE 1' .$condition.$orderby.$limit; 
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['send_time']   = $r['send_time'] ? date('Y-m-d H:i:s',$r['send_time']) : 0;
			$r['notice_state'] = get_states($r['from_time'],$r['to_time']);
			$r['from_time']   = $r['from_time'] ? date('Y-m-d H:i:s',$r['from_time']) : 0;
			$r['to_time']     = $r['to_time'] ? date('Y-m-d H:i:s',$r['to_time']) : 0;
			$r['create_time'] = $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : 0;
			$r['update_time'] = $r['update_time'] ? date('Y-m-d H:i:s',$r['update_time']) : 0;
			$r['audit_time']  = $r['audit_time'] ? date('Y-m-d H:i:s',$r['audit_time']) : 0;
			$r['content'] = htmlspecialchars_decode(stripslashes($r['content']));
			$r['type_name'] = $this->settings['type'][$r['type']];
			if($r['type'] == 5)
			{
				$r['owner_uname'] = '所有人';
			}
			$ret[] = $r;
		}
		return $ret;
	}
	
	public function detail($id,$condition = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'notice WHERE id = ' . $id . $condition;
		$info = $this->db->query_first($sql);
		$info['send_time']   = $info['send_time'] ? date('Y-m-d H:i:s',$info['send_time']) : 0;
		$info['from_time']   = $info['from_time'] ? date('Y-m-d H:i:s',$info['from_time']) : 0;
		$info['to_time']     = $info['to_time'] ? date('Y-m-d H:i:s',$info['to_time']) : 0;
		$info['create_time'] = $info['create_time'] ? date('Y-m-d H:i:s',$info['create_time']) : 0;
		$info['update_time'] = $info['update_time'] ? date('Y-m-d H:i:s',$info['update_time']) : 0;
		$info['audit_time']  = $info['audit_time'] ? date('Y-m-d H:i:s',$info['audit_time']) : 0;
		$info['content'] = htmlspecialchars_decode(stripslashes($info['content']));
		$info['type_name'] = $this->settings['type'][$info['type']];
			if($info['type'] == 5)
			{
				$info['onwer_uname'] = '所有人';
			}
		return $info;
	}
	
	public function create($data = array(),$table)
	{
		if(!$data || !$table)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . $table . " SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		
		$vid = $this->db->insert_id();
		$sql = " UPDATE ". DB_PREFIX . $table ." SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		$data['id'] = $vid;
		return $data;
	}
			
	public function update($id,$table,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX . $table . " WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . $table . " SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		$data['affected_rows'] = $this->db->affected_rows();
		$data['id'] = $id;
		return $data;
	}

	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "notice WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function set_statu($notice_id,$statu,$uid,$utype)
	{
		$nid = explode(',',$notice_id);
		foreach ($nid as $k=>$v)
		{
			$data[] = array(
				'notice_id' => $v,
				'user_id'   => $uid,
				'user_type' => $utype,
				'statu'     => $statu,
				);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'notice_log WHERE notice_id in('.$notice_id .') AND user_id = '.$uid .' AND user_type = ' .$utype;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$log[$r['notice_id']]['id'] = $r['id'];
			$log[$r['notice_id']]['statu'] = $r['statu'];
		}
		foreach ($data as $k=>$v)
		{
			if($log[$v['notice_id']]['id'] && $log[$v['notice_id']]['id']!=$s) //如果记录表里有该通知的记录，且为未阅读，则记录要更新的id
			{
				$update_id[] = $log[$v['notice_id']]['id']; //记录要更新的id
			}
			elseif(!$log[$v['notice_id']]['id'])//如果记录表里没有该通知的记录，添加该通知记录，且标记为已读
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . 'notice_log' . '(notice_id,user_id,statu,user_type) VALUES ('.$v['notice_id'].','.$v['user_id'].','.$statu.','.$v['user_type'].')';
				$this->db->query($sql);
			}
		}
		if($update_id)
		{
			$upid = implode(',',$update_id);
			$sql = 'UPDATE '.DB_PREFIX.'notice_log SET statu = '.$statu.' WHERE id in ('.$upid .')';
			$this->db->query($sql);
		}
		return $data;
	}
	
	
}