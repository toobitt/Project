<?php 
class email_content_template extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset, $count, $field='', $order='',$dbfield = '*')
	{
		$field = $field ? $field : 'id';
		$order = $order ? $order : 'DESC';
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 20;
		
		$orderby = " ORDER BY " . $field . " " . $order;
		$limit = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT {$dbfield} FROM " . DB_PREFIX . "email_module_settings ";
		$sql .= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$info =array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] && $row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] &&  $row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['body'] && $row['body'] = html_entity_decode($row['body']);
			$row['id']?($info[$row['id']] = $row):($info[] = $row);
		}
		
		if (!empty($info))
		{
			return $info;
		}
		return false;
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "email_module_settings " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time']  = date('Y-m-d H:i:s' , $row['create_time']);
			$row['smtppassword'] = hg_encript_str($row['smtppassword'], false);
			return $row;
		}

		return false;
	}
	
	public function create($data)
	{
		
		$Cdb = new Csql();
		$data = $Cdb->create('email_module_settings', $data);		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function update($idArr,$data)
	{
				
		$Cdb = new Csql();
		$data = $Cdb->update('email_module_settings',$data,$idArr);
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$Cdb = new Csql();
		return $Cdb->delete('email_module_settings', array('id'=>$id));
	}
	
	public function audit($id, $type)
	{
		$sql = "SELECT " . $type . " FROM " . DB_PREFIX . "email_module_settings WHERE id = " . $id;
		$member = $this->db->query_first($sql);

		$status = $member[$type];
		
		$new_status = 0; //操作失败
		
		if (!$status)	//已审核
		{
			$sql = "UPDATE " . DB_PREFIX . "email_module_settings SET ".$type." = 1 WHERE id = " . $id;
			$this->db->query($sql);

			$new_status = 1;
		}
		else			//待审核
		{
			$sql = "UPDATE " . DB_PREFIX . "email_module_settings SET ".$type." = 0 WHERE id = " . $id;
			$this->db->query($sql);

			$new_status = 2;
		}

		return $new_status;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "email_module_settings WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function check_appuniqueid_exists($appuniqueid)
	{
		$sql = "SELECT appuniqueid FROM ".DB_PREFIX."email_module_settings WHERE appuniqueid='" . $appuniqueid . "'";
		$data = $this->db->query_first($sql);
		return $data;
	}
	
	public function getEmailContentSettings($appuniqueid)
	{
		$sql = "SELECT subject,body FROM " . DB_PREFIX . "email_module_settings WHERE appuniqueid = '" . $appuniqueid . "'";		
		$email_settings = $this->db->query_first($sql);
		//$email_settings['body'] = html_entity_decode($email_settings['body']);
		return $email_settings;
	}
	
}

?>