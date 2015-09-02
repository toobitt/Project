<?php 
/***************************************************************************

* $Id: email_settings.class.php 41583 2014-11-13 05:46:44Z youzhenghuan $

***************************************************************************/
class emailSettings extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset, $count, $field='', $order='',$dbField = '*',$key='id')
	{
		$field = $field ? $field : 'id';
		$order = $order ? $order : 'DESC';
		$limit = '';
		$orderby = " ORDER BY " . $field . " " . $order;
		$count&& $limit = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT {$dbField} FROM " . DB_PREFIX . "email_settings ";
		$sql .= " WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		
		$info =array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time']&&$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time']&&$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row[$key]?($info[$row[$key]] = $row):($info[] = $row);
		}
		
		if (!empty($info))
		{
			return $info;
		}
		return $info;
	}
	
	public function detail($id)
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "email_settings " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time']  = date('Y-m-d H:i:s' , $row['create_time']);
			$row['smtppassword'] = hg_encript_str($row['smtppassword'], false);
			return $row;
		}

		return false;
	}
	
	public function create($info, $is_head_foot, $header, $footer)
	{
		$data = array(
			'name' 				=> $info['name'],
			'brief' 			=> $info['brief'],
			'appuniqueid' 		=> $info['appuniqueid'],
			'emailsend' 		=> $info['emailsend'],//用于发送信件的邮箱地址
			'emailwrapbracket' 	=> $info['emailwrapbracket'],
			'emailtype' 		=> $info['emailtype'],
			'usessl' 			=> $info['usessl'],//
			'smtpauth' 			=> $info['smtpauth'],//SMTP 身份验证
			'smtphost' 			=> $info['smtphost'],//SMTP 主机名称
			'smtpport' 			=> $info['smtpport'],//SMTP 端口,默认值为 25
			'fromname' 			=> $info['fromname'],
			'smtpuser' 			=> $info['smtpuser'],//SMTP 用户名
			'smtppassword' 		=> $info['smtppassword'],//SMTP 密码
			'is_head_foot' 		=> $is_head_foot,
			'header' 			=> $header,
			'footer' 			=> $footer,
			'email_footer' 		=> $info['email_footer'],
			'appid' 			=> $this->user['appid'],
			'appname' 			=> $this->user['display_name'],
			'user_id' 			=> $this->user['user_id'],
			'user_name' 		=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "email_settings SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}

		return false;
	}
	
	public function update($id, $info, $is_head_foot, $header, $footer)
	{
		$data = array(
			'name' 				=> $info['name'],
			'brief' 			=> $info['brief'],
			'appuniqueid' 		=> $info['appuniqueid'],
			'emailsend' 		=> $info['emailsend'],//用于发送信件的邮箱地址
			'emailwrapbracket' 	=> $info['emailwrapbracket'],
			'emailtype' 		=> $info['emailtype'],
			'usessl' 			=> $info['usessl'],//
			'smtpauth' 			=> $info['smtpauth'],//SMTP 身份验证
			'smtphost' 			=> $info['smtphost'],//SMTP 主机名称
			'smtpport' 			=> $info['smtpport'],//SMTP 端口,默认值为 25
			'fromname' 			=> $info['fromname'],
			'smtpuser' 			=> $info['smtpuser'],//SMTP 用户名
			'smtppassword' 		=> $info['smtppassword'],//SMTP 密码
			'email_footer'		=> $info['email_footer'],
			'is_head_foot' 		=> $is_head_foot,
			'header' 			=> $header,
			'footer' 			=> $footer,
			'update_time' 		=> TIMENOW,
		);
		
		$sql = "UPDATE " . DB_PREFIX . "email_settings SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id = " . $id;
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if ($data['id'])
		{
			return $data;
		}

		return false;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "email_settings WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function audit($id, $type)
	{
		$sql = "SELECT " . $type . " FROM " . DB_PREFIX . "email_settings WHERE id = " . $id;
		$member = $this->db->query_first($sql);

		$status = $member[$type];
		
		$new_status = 0; //操作失败
		
		if (!$status)	//已审核
		{
			$sql = "UPDATE " . DB_PREFIX . "email_settings SET ".$type." = 1 WHERE id = " . $id;
			$this->db->query($sql);

			$new_status = 1;
		}
		else			//待审核
		{
			$sql = "UPDATE " . DB_PREFIX . "email_settings SET ".$type." = 0 WHERE id = " . $id;
			$this->db->query($sql);

			$new_status = 2;
		}

		return $new_status;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "email_settings WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function check_appuniqueid_exists($appuniqueid)
	{
		$sql = "SELECT appuniqueid FROM ".DB_PREFIX."email_settings WHERE appuniqueid='" . $appuniqueid . "'";
		$data = $this->db->query_first($sql);
		return $data;
	}
	
	public function getEmailSettings($appuniqueid)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "email_settings WHERE appuniqueid = '" . $appuniqueid . "'";		
		$email_settings = $this->db->query_first($sql);
		return $email_settings;
	}
	
	public function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name like \'%'.urldecode($this->input['k']).'%\'';
		}
	
		if(isset($this->input['status']) && urldecode($this->input['status'])!= -1)
		{
			$condition .= " AND status = '".urldecode($this->input['status'])."'";
		}
		else if(urldecode($this->input['status']) == '0')
		{
			$condition .= " AND status = 0 ";
		}
		
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
}

?>