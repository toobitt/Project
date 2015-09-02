<?php 
/***************************************************************************

* $Id: member.class.php 30579 2013-10-18 01:51:39Z zhuld $

***************************************************************************/
class member extends InitFrm
{
	private $mMaterial;
	private $curlAuth;
	private $prefix;
	public function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();

		require_once ROOT_PATH . 'lib/class/curl.class.php';

		$this->curlAuth = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		
		$this->prefix = $this->settings['mobile_prefix'];
		
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
		
		if ($this->settings['ucenter']['open'])
		{
			include_once  UC_CLIENT_PATH . 'uc_client/client.php';
		}
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset, $count)
	{
		$data_limit = " LIMIT " . $offset . " , " . $count;

		$sql = "SELECT m.*,mn.name AS node_name, mi.*, mc.*, m.member_name AS member_name, m.create_time AS create_time, m.update_time AS update_time, m.ip AS ip, m.email AS email, m.mobile AS mobile, mc.mobile AS mc_mobile, mc.email AS mc_email, mb.bloodtype_name, mco.constellation_name FROM " . DB_PREFIX . "member m  ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_node mn ON m.node_id = mn.id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_info mi ON m.id=mi.member_id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_contact mc ON m.id=mc.member_id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_bloodtype mb ON mb.bloodtype_id=mi.bloodtype ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_constellation mco ON mco.constellation_id=mi.constellation ";
		
		$sql .= " WHERE 1 " . $condition . " ORDER BY m.id DESC " . $data_limit;
		$q = $this->db->query($sql);

		$member = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);

			if ($row['update_time'])
			{
				$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			}
			$row['member_id'] = $row['id'];
			
			if ($row['birth'])
			{
				$row['birth'] = date('Y-m-d', $row['birth']);
			}
			
			if ($row['mobile'])
			{
				$row['mobile'] = substr($row['mobile'], strlen($this->prefix));
			}
			
			if ($row['other_com'])
			{
				$row['other_com'] = unserialize($row['other_com']);
			}
			
			unset($row['password']);
			
			$member[$row['id']] = $row;
		}
	
		if (!empty($member))
		{
			return $member;
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['column_id'] = unserialize($row['column_id']);
			if(is_array($row['column_id']))
			{
				$column_id = array();
				foreach($row['column_id'] as $k => $v)
				{
					$column_id[] = $k;
				}
				$column_id = implode(',',$column_id);
				$row['column_id'] = $column_id;
			}		
			return $row;
		}

		return false;
	}

	/**
	 * 根据会员Id获取会员信息 (后台调用)
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $width
	 * @param unknown_type $height
	 */
	public function getMemberInfoDetail($member_id)
	{
	
	//星座
		$sql = "SELECT * FROM " . DB_PREFIX . "member_constellation WHERE 1";
		$q = $this->db->query($sql);
		$constellation = array();
		while($row = $this->db->fetch_array($q))
		{
			$constellation[$row['constellation_id']] = $row['constellation_name'];
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_bloodtype WHERE 1";
		$q = $this->db->query($sql);
		$blood = array();
		while($row = $this->db->fetch_array($q))
		{
			$blood[$row['bloodtype_id']] = $row['bloodtype_name'];
		}
		
		$sql = "SELECT avatar_id,member_id,host,dir,filepath,filename,name_prefix,orderid FROM " . DB_PREFIX . "member_avatar WHERE 1 AND member_id IN(" . $member_id . ") ORDER BY create_time DESC";
		$q = $this->db->query($sql);
		$avatar_info = array();
		while($row = $this->db->fetch_array($q))
		{
			$avatar_info[$row['member_id']][] = $row;
		}
		
		$sql = "SELECT member_id,content,source,create_time FROM " . DB_PREFIX . "talk WHERE 1 AND member_id IN(" . $member_id . ")";
		$q = $this->db->query($sql);
		$talk = array();
		while($row = $this->db->fetch_array($q))
		{
			$talk[$row['member_id']] = $row;
		}
	
		$sql = "SELECT m.*, mi.*, mc.*, m.id AS id, m.nick_name,m.email AS email, m.sex AS sex, m.ip AS ip, m.mobile AS mobile, m.create_time AS create_time, mc.mobile AS mc_mobile, mc.email AS mc_email, mi.sex AS mi_sex FROM " . DB_PREFIX . "member m ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "member_info mi ON m.id=mi.member_id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_contact mc ON m.id=mc.member_id ";
//		$sql .= " LEFT JOIN " . DB_PREFIX . "member_bloodtype mb ON mb.bloodtype_id=mi.bloodtype ";
//		$sql .= " LEFT JOIN " . DB_PREFIX . "member_constellation mco ON mco.constellation_id=mi.constellation ";
		
 		$sql .= " WHERE m.id IN (" . $member_id . ")";

		$q = $this->db->query($sql);
		$member = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			
			if ($row['birth'])
			{
				$row['birth'] = date('Y-m-d', $row['birth']);
			}
			
			if ($row['mobile'])
			{
				$row['mobile'] = substr($row['mobile'], strlen($this->prefix));
			}
			
			if ($row['other_com'])
			{
				$row['other_com'] = unserialize($row['other_com']);
			}
			
			$row['indexpic']['host'] = $row['host'];
			$row['indexpic']['dir'] = $row['dir'];
			$row['indexpic']['filepath'] = $row['filepath'];
			$row['indexpic']['filename'] = $row['filename'];
			
			$row['avatar'] = $avatar_info[$row['id']];
			$row['talk'] = $talk[$row['id']];
			
			$row['bloodtype_name'] = $row['bloodtype'] ? $blood[$row['bloodtype']] : '保密';
			$row['constellation_name'] = $row['constellation'] ? $constellation[$row['constellation']] : '保密';
			unset($row['host'],$row['dir'],$row['filepath'],$row['filename']);
			
			$member[$row['id']] = $row;
		}
		
		if (!empty($member))
		{
			return $member;
		}
		
		return false;
	}
	
	public function create($info , $uc_id='', $files='',$appid='',$appname='',$user_name='')
	{
		$salt = '';
		$password = '';
		if ($info['password'])
		{
			$salt = hg_generate_salt();

			$password = md5(md5($info['password']).$salt);
		}
		
		$data = array(
			'uc_id' 		=> $uc_id,
			'node_id' 		=> $info['node_id'] ? $info['node_id'] : 0,
			'group_id' 		=> $info['group_id'] ? $info['group_id'] : 0,
			'member_name' 	=> $info['member_name'],
			'nick_name' 	=> $info['nick_name'] ? $info['nick_name'] : $info['member_name'],
			'sex' 			=> $info['sex'],
			'password' 		=> $password,
			'email' 		=> $info['email'],
			'appid' 		=> $appid,
			'appname' 		=> $appname,
			'salt' 			=> $salt,
			'status' 		=> $this->settings['member_status'],
			'create_time' 	=> TIMENOW,
			'ip' 			=> hg_getip(),
			'openid'		=> $info['openid'],
		);
		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$data['column_id'] = serialize($data['column_id']);
		
		$sql = "INSERT INTO " . DB_PREFIX . "member SET ";
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
			if ($files)
			{
				$type = 2;
				$file = $files;
			}
			
			if ($info['avatar_url'])
			{
				$type = 1;
				$file = $info['avatar_url'];
			}
			
			if ($file)
			{
				$data['avatar'] = $this->avatarEdit($data['id'], $file, $type);
			}
			
			$sql = "INSERT INTO " . DB_PREFIX . "member_extra SET ";
			$sql.= " member_id = " . $data['id'];
			$this->db->query($sql);
			
			//绑定
			if($info['platform'] && $info['platform_id'])
			{
			//	$ret_member_bound = $this->member_bound_add($data['id'], $data['member_name'], $info['platform'], $info['platform_id'], $info['access_plat_token'], $info['avatar_url'], $info['plat_name']);
				
				$check_bound = $this->check_member_bound_exists($data['member_name'], $info['platform'], $info['platform_id']);
				if(empty($check_bound))//未绑定
				{
					$bound_data = array(
						'member_id' 	=> $data['id'],
						'member_name' 	=> $data['member_name'],
						'platform' 		=> $info['platform'],
						'platform_id'	=> $info['platform_id'],
						'avatar_url'	=> $info['avatar_url'],
						'access_plat_token' => $info['access_plat_token'],
						'plat_member_name' 	=> $info['plat_member_name'],
					);
					
					$ret_member_bound = $this->add_bound($bound_data);
				
					if (!empty($ret_member_bound))
					{
						$sql = "UPDATE " . DB_PREFIX . "member SET is_bound=1 WHERE id = " . $data['id'];
						$this->db->query($sql);
					}	
				}
			}
			
			//放入发布队列
			$sql = "SELECT status,column_id FROM " . DB_PREFIX . "member WHERE id = " . $data['id'];
			$r = $this->db->query_first($sql);
			if(intval($r['status']) == 1 && !empty($r['column_id']))
			{
				$op = 'insert';
				$this->publish_insert_query($data['id'],$op,'','',$user_name);
			}
			
			return $data;
		}
		return false;
	}
	
	public function update($info, $node_id, $files,$user_name='')
	{
		if ($info['password'])
		{
			$salt = hg_generate_salt();
			
			$password = md5(md5($info['password']).$salt);
			
			$sql_password = " password = '" . $password . "', salt = '" . $salt . "', ";
		}
		
		if ($this->settings['ucenter']['open'] && $info['uc_id'])
		{
			$ret = $this->uc_user_edit($info['uc_id'], $info['member_name'], $info['email'], $info['old_password'], $info['password']);
			
			if ($ret < 0)
			{
				return false;
			}
		}
		
		//查询修改会员之前已经发布到的栏目
		$sql = "SELECT column_id FROM " . DB_PREFIX ."member WHERE id = " . $info['id'];
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$data = array(
			'member_name' 	=> $info['member_name'],
			'email' 		=> $info['email'],
			'update_time' 	=> TIMENOW,
		);
		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$data['column_id'] = serialize($data['column_id']);
		
		if (isset($node_id))
		{
			$data['node_id'] = $node_id;
		}
		
		$sql = "UPDATE " . DB_PREFIX . "member SET " . $sql_password;
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $info['id'];

		$this->db->query($sql);
		
		$data['id'] = $info['id'];
		
		if ($data['id'])
		{
			if ($files)
			{
				$this->avatarEdit($data['id'], $files);
			}
			//发布系统
			$sql = "SELECT * FROM " . DB_PREFIX ."member WHERE id = " . $data['id'];
			$ret = $this->db->query_first($sql);
			//更改文章后发布的栏目
			$ret['column_id'] = unserialize($ret['column_id']);
			$new_column_id = array();
			if(is_array($ret['column_id']))
			{
				$new_column_id = array_keys($ret['column_id']);
			}
			
			if(intval($ret['status']) == 1)
			{
				if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
				{
					$del_column = array_diff($ori_column_id,$new_column_id);
					if(!empty($del_column))
					{
						$this->publish_insert_query($data['id'], 'delete',$del_column,'',$user_name);
					}
					$add_column = array_diff($new_column_id,$ori_column_id);
					if(!empty($add_column))
					{
						$this->publish_insert_query($data['id'], 'insert',$add_column,'',$user_name);
					}
					$same_column = array_intersect($ori_column_id,$new_column_id);
					if(!empty($same_column))
					{
						$this->publish_insert_query($data['id'], 'update',$same_column,'',$user_name);
					}
				}
				else 							//未发布，直接插入
				{
					$op = "insert";
					$this->publish_insert_query($data['id'],$op,'','',$user_name);
				}
			}
			else    //打回
			{
				if(!empty($ret['expand_id']))
				{
					$op = "delete";
					$this->publish_insert_query($data['id'],$op,'','',$user_name);
				}
			}
				return $data;
		}

		return false;
	}

	public function update_member($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "member SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];

		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql = "SELECT uc_id, id, status, expand_id FROM " . DB_PREFIX . "member WHERE	id IN (" . $id . ")";
		$q = $this->db->query($sql);

		$uc_ids = array();
		while ($row = $this->db->fetch_array($q))
		{
			$uc_ids[$row['id']] = $row['uc_id'];
			
			if(intval($row['status']) == 1 && $row['expand_id'])
			{
				$op = "delete";
				$this->publish_insert_query($row['id'],$op);
			}
		}
	/*	
		if ($this->settings['ucenter']['open'])
		{
			$ret = uc_user_delete(trim(implode(',', $uc_ids)));
			if (!$ret)
			{
				return false;
			}
		}
	*/
		$sql = "DELETE m.*, mi.*, mc.*, mb.*, me.*, mco.* FROM " . DB_PREFIX . "member m ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "member_info mi ON m.id=mi.member_id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "member_contact mc ON m.id=mc.member_id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "member_bound mb ON m.id=mb.member_id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "member_extra me ON m.id=me.member_id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "member_collect mco ON m.id=mco.member_id ";
		$sql.= " WHERE m.id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function audit($id, $type)
	{
		$sql = "SELECT " . $type . " FROM " . DB_PREFIX . "member WHERE id = " . $id;
		$member = $this->db->query_first($sql);

		$status = $member[$type];
		
		$new_status = 0; //操作失败
		
		if (!$status)	//已审核
		{
			$sql = "UPDATE " . DB_PREFIX . "member SET ".$type." = 1 WHERE id = " . $id;
			$this->db->query($sql);
			$sql = "SELECT * FROM " . DB_PREFIX ."member WHERE id IN(" . $id . ")";
			$ret = $this->db->query($sql);
			while($info = $this->db->fetch_array($ret))
			{
				if(!empty($info['expand_id']))
				{
					$op = "update";			
				}
				else
				{
					$op = "insert";
				}
				$this->publish_insert_query($info['id'], $op);
			}
			$new_status = 1;
		}
		else			//待审核
		{
			$sql = "UPDATE " . DB_PREFIX . "member SET ".$type." = 0 WHERE id = " . $id;
			$this->db->query($sql);
			$sql = "SELECT * FROM " . DB_PREFIX ."member WHERE id IN(" . $id .")";
			$ret = $this->db->query($sql);
			while($info = $this->db->fetch_array($ret))
			{
				if(!empty($info['expand_id']))
				{
					$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
				}		
				else 
				{
					$op = "";
				}
				$this->publish_insert_query($info['id'], $op);
			}
			$new_status = 2;
		}

		return $new_status;
	}
	
	public function login($member_name, $password, $appid, $appkey, $platform = '', $platform_id = '', $access_plat_token = '', $avatar_url='', $is_more = 0, $plat_member_name = '')
	{
		$binary = '';//不区分大小些
		if(defined('DIFFER_SIZE') && !DIFFER_SIZE)//区分大小些
		{
			$binary = 'binary ';
		}
		
		$condition = '';
		if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $member_name))
		{   
			$condition = " WHERE mobile = '" . $this->prefix . $member_name . "'";
		}
		else if (!strpos($member_name, '@'))
		{
			$condition = " WHERE " . $binary . " member_name= '" . $member_name . "'";
		}
		else 
		{
			$condition = " WHERE " . $binary . " email = '" . $member_name . "'";
		}
		
				
		
		if($platform)
		{
			//绑定表和用户关联检索
			$member = array();
			$sql = "SELECT id, uc_id,openid, member_name, nick_name, password, salt, email, is_bound, host, dir, filepath, filename, mobile, sex FROM " . DB_PREFIX . "member WHERE " . $binary . " member_name='" . $member_name . "'";
			//$sql = "SELECT * FROM " . DB_PREFIX . "member m LEFT JOIN " . DB_PREFIX . "member_bound b ON m.id=b.member_id WHERE b.platform='" . $platform . "' AND b.platform_id='" . $platform_id . "'";
			$f = $this->db->query_first($sql);
			if(empty($f))
			{
				return -1;	//用户不存在		
			}
			else
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "member_bound WHERE member_id=" . $f['id'] . " AND platform='" . $platform . "' AND platform_id='" . $platform_id . "'";
				$sen = $this->db->query_first($sql);
				
				$password = md5(md5($password).$f['salt']);
		//		if($password == $f['password'] && $member_name == ((defined('DIFFER_SIZE') && !DIFFER_SIZE) ? $f['member_name'] : strtolower($f['member_name'])))
				if($member_name == ((defined('DIFFER_SIZE') && !DIFFER_SIZE) ? $f['member_name'] : strtolower($f['member_name'])))
				{
					//正确
					$sql = "";
					if(empty($sen))//不是当前的绑定，重新插入新的绑定
					{
						$bound_data = array(
							'member_id' 	=> $f['id'],
							'member_name' 	=> $f['member_name'],
							'platform' 		=> $platform,
							'platform_id'	=> $platform_id,
							'avatar_url'	=> $avatar_url,
							'access_plat_token' => $access_plat_token,
							'plat_member_name' 	=> $plat_member_name,
						);
						
						$ret_member_bound = $this->add_bound($bound_data);
						
					//	$sql = "INSERT INTO " . DB_PREFIX . "member_bound(member_id,member_name,platform,platform_id,avatar_url,access_plat_token,plat_name) VALUES(" . $f['id'] . ",'" . $f['member_name'] . "','" . $platform . "','" . $platform_id . "','" . $avatar_url . "','" . $access_plat_token . "','" . $plat_name . "')";
					//	$this->db->query($sql);
						$member = $f;
						if(!$f['is_bound'] && !empty($ret_member_bound))//已经有绑定
						{
							$sql = "UPDATE " . DB_PREFIX . "member SET is_bound=1 WHERE id=" . $f['id'];
							$this->db->query($sql);
						}
					}
					else//是当前绑定
					{
						//直接登陆				
						$member = $f;
					}
				}
				/*
				else
				{
					return -2;//密码错误
				}
				*/
			}
		}
		else
		{
			//根据用户名密码是否对
			$sql = "SELECT id,openid, uc_id, member_name, nick_name, password, salt, email, is_bound, host, dir, filepath, filename, mobile, sex FROM " . DB_PREFIX . "member " . $condition;
			$member = $this->db->query_first($sql);
			
			if (empty($member))
			{
				$condition = " WHERE " . $binary . " member_name= '" . $member_name . "'";
				$sql = "SELECT id, uc_id, member_name, nick_name, password, salt, email, is_bound, host, dir, filepath, filename, mobile, sex FROM " . DB_PREFIX . "member " . $condition;
				$member = $this->db->query_first($sql);
				
				if (empty($member))
				{
					return -1;	//用户不存在
				}
			}
			
			$password = md5(md5($password).$member['salt']);
			if ($password != $member['password'])
			{
				return -2;	//密码不正确
			}
		}

		$auth = $this->get_token($member['id'], $member['member_name'], $appid, $appkey);
		
		$data = array(
			'member_id' => $member['id'],
			'member_name' => $member['member_name'],
			'group_id' => 0,
			'token' => $auth['token'],
			'admin_type' => 0,
			'appid' => $appid,
			'appname' => $auth['display_name'],
			'login_time' => TIMENOW,
			'ip' => hg_getip()
		);

		$sql = "INSERT INTO " . DB_PREFIX . "member_login SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);

		$return = array(
			'member_id' => $member['id'],
			'nick_name' => $member['nick_name'],
			'uc_id' 	=> $member['uc_id'],
			'openid'	=> $member['openid'],
			'token' 	=> $data['token'],
			'avatar'	=> array(
				'host'		=> $member['host'],
				'dir'		=> $member['dir'],
				'filepath'	=> $member['filepath'],
				'filename'	=> $member['filename'],
			),
		);

		if ($is_more)
		{
			$return['sex']	  = $member['sex'];
			$return['email']  = $member['email'];
			$return['mobile'] = $member['mobile'] ? substr($member['mobile'], strlen($this->prefix)) : '';
		}
		
		return $return;
	}

	public function get_token($member_id, $member_name, $appid, $appkey)
	{
		if (!$this->curlAuth)
		{
			return array();
		}
		
		$this->curlAuth->setSubmitType('post');
		$this->curlAuth->setReturnFormat('json');
		$this->curlAuth->addRequestData('a', 'show');
		$this->curlAuth->addRequestData('appid', $appid);
		$this->curlAuth->addRequestData('appkey', $appkey);
		$this->curlAuth->addRequestData('ip', hg_getip());
		$this->curlAuth->addRequestData('user_name', $member_name);
		$this->curlAuth->addRequestData('id', $member_id);
		$this->curlAuth->addRequestData('admin_group_id', 0);
		$this->curlAuth->addRequestData('group_type', 0);
		$ret = $this->curlAuth->request('get_access_token.php');
		
		return $ret[0];
	}
	
	public function member_bound_add($member_id, $member_name, $platform, $platform_id,$access_plat_token,$avatar_url,$plat_member_name)
	{
		$data = array(
			'member_id' 	=> $member_id,
			'member_name' 	=> $member_name,
			'platform' 		=> $platform,
			'platform_id'	=> $platform_id,
			'avatar_url'	=> $avatar_url,
			'access_plat_token' => $access_plat_token,
			'plat_member_name' 	=> $plat_member_name,
		);

		$sql = "INSERT INTO " . DB_PREFIX . "member_bound SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$bound_id = $this->db->insert_id();
		
		if ($bound_id)
		{
			return $bound_id;
		}
		return false;
	}
	
	public function activate_key_add($member_id)
	{
		$sql = "SELECT email FROM " . DB_PREFIX . "member WHERE id=" . $member_id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		
		$data = array(
			'member_id' 	=> $member_id,
			'toff' 			=> $this->settings['App_email']['email_toff'],
			'activate_key' 	=> md5(microtime()*rand()),
			'create_time'	=> TIMENOW,
		);
		$sql = "SELECT * FROM " . DB_PREFIX . "member_activate_key WHERE member_id=" . $member_id;
		$sen = $this->db->query_first($sql);
		if(empty($sen))
		{
			$sql = "INSERT IGNORE INTO " . DB_PREFIX . "member_activate_key SET ";
			$space = "";
			foreach ($data AS $key => $value)
			{
				$sql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "member_activate_key SET activate_key='" . $data['activate_key'] . "',create_time=" . $data['create_time'] . " WHERE member_id=" . $data['member_id'];
		}

		if ($this->db->query($sql))
		{
			$data['email'] = $f['email'];
			return $data;
		}
		return false;
	}
	
	public function activate_key_detail($activate_key)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "member_activate_key WHERE activate_key = '" . $activate_key . "'";
		$ret = $this->db->query_first($sql);
		return $ret;
	}

	public function activate_key_delete($activate_key)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_activate_key WHERE activate_key = '" . $activate_key . "'";
		if ($this->db->query_first($sql))
		{
			return true;
		}
		return false;
	}
	
	public function password_forget_key_add($email, $member_name)
	{
		$data = array(
		//	'member_id' 			=> $member_id,
			'member_name' 			=> $member_name,
			'email' 				=> $email,
			'toff' 					=> $this->settings['App_email']['password_forget_key_toff'],
			'password_forget_key' 	=> md5(microtime()*rand()),
			'create_time'			=> TIMENOW,
		);

		$sql = "INSERT INTO " . DB_PREFIX . "member_password_forget_key SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}

	public function password_forget_key_detail($password_forget_key)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "member_password_forget_key WHERE password_forget_key = '" . $password_forget_key . "'";
		$ret = $this->db->query_first($sql);
		return $ret;
	}

	public function password_forget_key_delete($password_forget_key)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_password_forget_key WHERE password_forget_key = '" . $password_forget_key . "'";
		if ($this->db->query_first($sql))
		{
			return true;
		}
		return false;
	}
	
	public function logout($member_id, $token)
	{
		if (!$this->curlAuth)
		{
			return array();
		}
		
		$this->curlAuth->setSubmitType('post');
		$this->curlAuth->setReturnFormat('json');
		$this->curlAuth->addRequestData('a', 'logout');
		$this->curlAuth->addRequestData('token', $token);
		$this->curlAuth->addRequestData('id', $member_id);
		$auth = $this->curlAuth->request('get_access_token.php');

	//	$sql = "DELETE FROM " . DB_PREFIX . "member_login WHERE token = '" . $token . "' AND member_id = " . $member_id;
	//	$this->db->query($sql);

		return true;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member AS m WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}

	/**
	 * 会员编辑
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $nick_name
	 * @param unknown_type $mobile
	 * @param unknown_type $sex
	 */
	public function memberEdit($member_id, $nick_name, $mobile, $sex, $email)
	{
		$data = array(
			'update_time' => TIMENOW,
		);
		
		if (isset($nick_name))
		{
			$data['nick_name'] = $nick_name;
		}
		
		if (isset($mobile))
		{
			$data['mobile'] = $this->prefix . $mobile;
		}
	
		if (isset($sex))
		{
			$data['sex'] = $sex;
		}
		if(isset($email))
		{
			$data['email'] = $email;
		}
		$sql = "UPDATE " . DB_PREFIX . "member SET " . $sql_password;
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $member_id;
		
		if ($this->db->query($sql))
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * 头像编辑
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $files
	 * @return boolean
	 */
	public function avatarEdit($member_id, $files, $type = '')
	{
		if(!$member_id)
		{
			return false;
		}
		if ($type == 1)
		{
			$avatar = $files;
		}
		else 
		{
			$avatar['Filedata'] = $files;
		}
		
		$type = $type ? $type : 2;
		
		//$material = $this->mMaterial->addMaterial($avatar, $member_id);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member WHERE id=" . $member_id;
		$f = $this->db->query_first($sql);
	
		//头像传两分
		$sql = "SELECT * FROM " . DB_PREFIX . "member_avatar WHERE member_id=" . $member_id . " ORDER BY orderid DESC";
		$sen = $this->db->query_first($sql);
		
		$data = array();
		if(!$f['dir'])
		{
			$data['dir'] = app_to_dir('avatars','img');
			$data['filepath'] = '0000/' . (intval($member_id/10000) < 10 ? '0' . intval($member_id/10000) : intval($member_id/10000)) . '/';
			$data['name_prefix'] = hg_generate_user_salt(4) . $member_id . hg_generate_user_salt(2);
			$data['orderid'] = 1;
			$data['filename'] = $data['name_prefix'] . '0000.jpg';
		}//以上的就是当前头像的名字
		else
		{
			$data['dir'] = $f['dir'];
			$data['filepath'] = $f['filepath'];
			$data['name_prefix'] = $sen['name_prefix'];
			$data['orderid'] = $sen['orderid']+1;
		//	$data['filename'] = $f['filename'];
			$data['filename'] = $sen['name_prefix'] . hg_order_num($data['orderid']) . '.jpg';
		}
		$material_nodb = $this->mMaterial->addMaterialNodb($avatar, $type, $data['dir'] . $data['filepath'], $data['filename']);//覆盖一份
		if(empty($material_nodb))
		{
			return false;
		}

		$material = array();
		
		if(!empty($sen))
		{
			$data['orderid'] = $sen['orderid']+1;
			$data['name_prefix'] = $sen['name_prefix'];
		}
		
		$material = array(
			'member_id' => $member_id,
			'member_name' => $f['member_name'],
			'host' => $material_nodb['host'],
			'dir' => $data['dir'],
			'filepath' => $data['filepath'],
			'filename' => $data['name_prefix'] . hg_order_num($data['orderid']) . '.jpg',
			'name_prefix' => $data['name_prefix'],
			'orderid' => $data['orderid'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		
		$material_tmp_nodb = $this->mMaterial->addMaterialNodb($avatar, $type, $material['dir'] . $material['filepath'], $material['filename']);//保存一份
		
		if(empty($material_tmp_nodb))
		{
			return false;
		}
		else
		{
			$sql_avatar = "INSERT INTO " . DB_PREFIX . "member_avatar SET ";
			$space = "";
			foreach($material as $key => $value)
			{
				$sql_avatar .= $space . $key ."='" . $value . "'";
				$space = ',';
			}
			$this->db->query($sql_avatar);			
		}
		
		if (!empty($material) && $material['host'])
		{
			/*
			$albums_info = array(
				'id' => $material['id'],
				'host' => $material['host'] . $material['dir'],
				'filepath' => $material['filepath'] . $material['filename'],
			);
			include_once(ROOT_PATH . 'lib/class/albums.class.php');
			$this->albums = new albums();
			$ret = $this->albums->add_sys_albums(3, serialize($albums_info));
*/
			$sql = "UPDATE " . DB_PREFIX . "member SET avatar=1, host='".$material['host']."', dir='" . $data['dir'] . "',filepath='" . $data['filepath'] . "', filename='" . $data['filename'] . "' WHERE id = " . $member_id;
			$this->db->query($sql);
		}
		$avatar =  array(
			'host' => $material['host'],
			'dir' => $data['dir'],
			'filepath' => $data['filepath'],
			'filename' => $data['filename'],
		);
		return $avatar;
		//return false;
	}
	
	/**
	 * 根据头像ID删除头像
	 */
	public function avatarDelete($avatar_id)
	{
		if(!$avatar_id) 
			return false;
		$sql = "DELETE FROM " . DB_PREFIX ."member_avatar WHERE avatar_id = " . $avatar_id;
		$this->db->query($sql);
		return true;	
	}
	
	/**
	 * 切换头像
	 * @param int $avatar_id  头像id
	 * @return array 头像地址
	 */
	 public function avatarSwitch($avatar_id)
	 {
	 	if(!$avatar_id)
	 		return false;
	 	$sql = "SELECT orderid FROM " . DB_PREFIX ."member_avatar ORDER BY orderid DESC ";
	 	$max_avatar = $this->db->query_first($sql);			//最早的历史头像
	 	//取出当前头像重新存一份
	 	$sql = "SELECT * FROM " . DB_PREFIX ."member_avatar ORDER BY orderid ASC ";	 	
	 	$avatar_one = $this->db->query_first($sql);
		$material = array(
			'member_id' => $avatar_one['member_id'],
			'member_name' => $avatar_one['member_name'],
			'host' => $avatar_one['host'],
			'dir' => $avatar_one['dir'],
			'filepath' => $avatar_one['filepath'],
			'filename' => $avatar_one['name_prefix'] . hg_order_num($max_avatar['orderid'] +1 ) . '.jpg',
			'name_prefix' => $avatar_one['name_prefix'],
			'orderid' => $max_avatar['orderid'] + 1,
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);	 	
	 	$url = $avatar_one['host'] . $avatar_one['dir'] . $avatar_one['filepath'] . $avatar_one['filename'];		
		$material_tmp_nodb = $this->mMaterial->addMaterialNodb($url, 1, $material['dir'] . $material['filepath'], $material['filename']);//保存一份		
		if(empty($material_tmp_nodb))
		{
			return false;
		}
		else
		{
			$sql_avatar = "INSERT INTO " . DB_PREFIX . "member_avatar SET ";
			$space = "";
			foreach($material as $key => $value)
			{
				$sql_avatar .= $space . $key ."='" . $value . "'";
				$space = ',';
			}
			$this->db->query($sql_avatar);			
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."member_avatar WHERE avatar_id = " . $avatar_id;
		$avatar = $this->db->query_first($sql);
		$return = array(
			'host'     => $avatar['host'],
			'dir'  	   => $avatar['dir'],
			'filepath' => $avatar['filepath'],
			'filename' => $avatar['name_prefix'] . '0000.jpg',
			'url'      => $avatar['host'] . $avatar['dir'] . $avatar['filepath'] . $avatar['filename'],
		);		
		$this->mMaterial->addMaterialNodb($return['url'], 1, $return['dir'] . $return['filepath'], $return['filename']);  //需要更改的头像存为当前头像
		return $return;
	 }
	
	/**
	 * 根据会员Id获取会员信息
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $width
	 * @param unknown_type $height
	 */
	public function getMemberById($member_id)
	{
		$sql = "SELECT m.id AS id, m.nick_name, m.sex AS sex, m.host, m.dir, m.filepath, m.filename, m.ip AS ip, m.mobile AS mobile, mi.*, mc.*, m.create_time AS create_time, mc.mobile AS mc_mobile, mc.email AS mc_email, mb.bloodtype_name, mco.constellation_name FROM " . DB_PREFIX . "member m ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "member_info mi ON m.id=mi.member_id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_contact mc ON m.id=mc.member_id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_bloodtype mb ON mb.bloodtype_id=mi.bloodtype ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_constellation mco ON mco.constellation_id=mi.constellation ";
		
 		$sql .= " WHERE m.id IN (" . $member_id . ")";

		$q = $this->db->query($sql);
		$member = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
		
			if ($row['birth'])
			{
				$row['birth'] = date('Y-m-d', $row['birth']);
			}
			
			if ($row['mobile'])
			{
				$row['mobile'] = substr($row['mobile'], strlen($this->prefix));
			}
			
			if ($row['other_com'])
			{
				$row['other_com'] = unserialize($row['other_com']);
			}
			unset($row['password']);
			
			$member[$row['id']] = $row;
		}
		
		if (!empty($member))
		{
			return $member;
		}
		
		return false;
	}

	/**
	 * 获取会员信息
	 * Enter description here ...
	 * @param unknown_type $condition
	 * @param unknown_type $offset
	 * @param unknown_type $count
	 */
	public function getMemberInfo($condition, $offset, $count)
	{
		$data_limit = $count == -1 ? '' : " LIMIT " . $offset . " , " . $count;

		$sql = "SELECT m.id AS id, m.nick_name, m.sex AS sex, m.host,m.filepath, m.dir, m.filename, m.ip AS ip, m.mobile AS mobile, mi.*, mc.*, m.create_time AS create_time, mc.mobile AS mc_mobile, mc.email AS mc_email, mb.bloodtype_name, mco.constellation_name FROM " . DB_PREFIX . "member m ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "member_info mi ON m.id=mi.member_id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_contact mc ON m.id=mc.member_id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_bloodtype mb ON mb.bloodtype_id=mi.bloodtype ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_constellation mco ON mco.constellation_id=mi.constellation ";
		
		$sql .= " WHERE 1 " . $condition . " ORDER BY m.id DESC " . $data_limit;
		$q = $this->db->query($sql);

		$member = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);

			$row['member_id'] = $row['id'];
			
			if ($row['mobile'])
			{
				$row['mobile'] = substr($row['mobile'], strlen($this->prefix));
			}
			if ($row['filename'])
			{
				$row['avatar']['host'] 		= $row['host'];
				$row['avatar']['dir'] 		= $row['dir'];
				$row['avatar']['filepath'] 	= $row['filepath'];
				$row['avatar']['filename'] 	= $row['filename'];
			}
			$member[$row['id']] = $row;
		}
	
		if (!empty($member))
		{
			return $member;
		}
		return false;
	}
	
	public function _get_member_by_id($member_id, $type = '', $field = '')
	{
		$field = $field ? ', ' . $field : '';
		$sql = "SELECT id, uc_id, nick_name, host, dir, filepath, filename, sex " . $field . " FROM " . DB_PREFIX . "member ";
		
		if ($type == 'nick_name')
		{
			$member_id_tmp = explode(',', $member_id);
			$member_id = implode("','", $member_id_tmp);
			$sql.= " WHERE nick_name IN ('" . $member_id . "')";
		}
		else 
		{
			$sql.= " WHERE id IN (" . $member_id . ")";
		}
		
		$q = $this->db->query($sql);
		
		$member_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			/*
			if($row['filename'])
			{
				$row['avatar'] = array(
					'host' => $row['host'],	
					'dir' => $row['dir'],	
					'filepath' => $row['filepath'],	
					'filename' => $row['filename'],	
				);
			}
			unset($row['host'], $row['dir'],$row['filepath'],$row['filename']);
			*/
			if ($row['mobile'])
			{
				$row['mobile'] = substr($row['mobile'], strlen($this->prefix));
			}
			$member_info[$row['id']] = $row;
		}
		
		return $member_info;
	}
	
	/**
	 * 获取会员信息 (会员id, 昵称, 性别, 注册时间, 各种信息数目)
	 * $id
	 * $nick_name
	 * $host
	 * $dir
	 * $filename
	 * $create_time
	 * $info_count array
	 * Enter description here ...
	 * @param unknown_type $member_id
	 */
	public function getMemberInfoById($member_id, $type='')
	{
		$sql = "SELECT m.id, m.nick_name, m.sex, m.host, m.dir, m.filepath, m.filename, m.create_time, m.is_email, m.privacy, m.group_id, me.* FROM " . DB_PREFIX . "member m ";
		
		$sql.= " LEFT JOIN " . DB_PREFIX . "member_extra me ON m.id=me.member_id ";
		
		if ($type == 'nick_name')
		{
			$member_id_tmp = explode(',', $member_id);
			$member_id = implode("','", $member_id_tmp);
			$binary = '';//不区分大小些
			if(defined('DIFFER_SIZE') && !DIFFER_SIZE)//区分大小些
			{
				$binary = 'binary ';
			}
			$sql .= " WHERE " . $binary . " m.nick_name IN ('" . $member_id . "')";
		}
		else 
		{
			$sql .= " WHERE m.id IN (" . $member_id . ")";
		}

		$q = $this->db->query($sql);
		
		$member_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['avatar']['host'] = $row['host'];
			$row['avatar']['dir'] = $row['dir'];
			$row['avatar']['filepath'] = $row['filepath'];
			$row['avatar']['filename'] = $row['filename'];
			
			$row['privacy'] = $row['privacy'];
			
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			
		//	unset($row['host'], $row['dir'], $row['filepath'], $row['filename']);
			
			$member_info[$row['id']] = $row;
		}
		
		if ($type == 'nick_name')
		{
			if (empty($member_info))
			{
				return false;
			}
			
			$member_id = implode(',', @array_keys($member_info));
		}
		
		$member_info_count = $this->getMemberInfoCount($member_id);
		
		if (!empty($member_info))
		{
			$info = array();
			foreach ($member_info AS $k => $v)
			{
				if ($member_info_count[$k])
				{
					$info[$k] = @array_merge($member_info[$k], $member_info_count[$k]);
				}
				else 
				{
					$info[$k] = $member_info[$k];
				}
			}
			
			return $info;
		}
		return false;
	}
	
	/**
	 * 获取 各种信息数目
	 * Enter description here ...
	 * @param unknown_type $member_id
	 */
	public function getMemberInfoCount($member_id)
	{
		$sql = "SELECT mic.id, mic.member_id, mic.counts, mig.name FROM " . DB_PREFIX . "member_info_count mic ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "member_info_group mig ON mic.gid=mig.gid ";
		$sql .= " WHERE mic.member_id IN (" . $member_id . ")";
		$q = $this->db->query($sql);
		
		$member_info_count = array();
		while ($row = $this->db->fetch_array($q))
		{
			$member_info_count[$row['member_id']]['info'][$row['id']] = $row;
			unset($member_info_count[$row['member_id']]['info'][$row['id']]['id']);
			unset($member_info_count[$row['member_id']]['info'][$row['id']]['member_id']);
		}
		
		if (!empty($member_info_count))
		{
			return $member_info_count;
		}
		return false;
	}
	/**
	 * 添加各种信息数目
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $info
	 */
	public function addMemberInfoCount($member_id, $info)
	{
		$sql = "SELECT gid FROM " . DB_PREFIX . "member_info_group WHERE appid = " . $info['appid'];
		$group = $this->db->query_first($sql);
		
		$gid = $group['gid'];
		
		if (empty($group))
		{
			$data_group = array(
				'appid' => $info['appid'],
				'appuniqueid' => $info['appuniqueid'],
				'name' => $info['name'],
			);
			
			$sql = "INSERT INTO " . DB_PREFIX . "member_info_group SET ";
			$space = "";
			foreach ($data_group AS $key => $value)
			{
				$sql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}

			$this->db->query($sql);
			
			$gid = $this->db->insert_id();
		}
	
		$data = array(
			'member_id' => $member_id,
			'gid' => $gid,
			'appid' => $info['appid'],
			'counts' => $info['counts'],
		);

		$sql = "INSERT INTO " . DB_PREFIX . "member_info_count SET ";
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
	
	public function editMemberInfoCount($member_id, $info)
	{
		$sql = "UPDATE " . DB_PREFIX . "member_info_count ";
		$sql .= " SET counts=" . $info['counts'];
		$sql .= " WHERE member_id = " . $member_id . " AND appid = " . $info['appid'];
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 修改密码
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $old_password
	 * @param unknown_type $new_password
	 */
	public function memberPasswordEdit($member_id, $old_password, $new_password)
	{
		$sql = "SELECT salt, password, uc_id, member_name, email FROM " . DB_PREFIX . "member WHERE id = " . $member_id;
		$member = $this->db->query_first($sql);
		
		if (empty($member))
		{
			return -2;
		}
		
		if (md5(md5($old_password).$member['salt']) != $member['password'])
		{
			return -1;
		}
	
		if ($this->settings['ucenter']['open'] && $member['uc_id'])
		{
			$ret = $this->uc_user_edit($member['uc_id'], $member['member_name'], $member['email'], $old_password, $new_password);
			
			if ($ret < 0)
			{
				return $ret;
			}
		}
		
		$salt = hg_generate_salt();
		$password = md5(md5($new_password).$salt);
		$sql = "UPDATE " . DB_PREFIX . "member SET salt = '" . $salt . "', password = '" . $password . "' WHERE id = " . $member_id;
		if ($this->db->query($sql))
		{
			return $member_id;
		}
		return false;
	}
	
	/**
	 * 忘记密码
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $new_password
	 */
	public function memberPasswordForget($email, $new_password)
	{
		$sql = "SELECT uc_id, member_name, email FROM " . DB_PREFIX . "member WHERE email = '" . $email . "'";
		$member = $this->db->query_first($sql);
		
		if (empty($member))
		{
			return -1;
		}
	/*	
		if ($this->settings['ucenter']['open'] && $member['uc_id'] && $member['platform'] == $this->settings['platform']['uc'])
		{
			$ret = $this->uc_user_edit($member['uc_id'], $member['member_name'], $member['email'], $old_password, $new_password);
			
			if ($ret < 0)
			{
				return false;
			}
		}
	*/
		$condition = " WHERE email = '" . $email . "'";
		
		$salt = hg_generate_salt();
		$password = md5(md5($new_password).$salt);
		$sql = "UPDATE " . DB_PREFIX . "member SET salt = '" . $salt . "', password = '" . $password . "' " . $condition;
		
		if ($this->db->query($sql))
		{
			return $member;
		}
		return false;
	}
	
	/**
	 * 激活邮箱
	 * Enter description here ...
	 * @param unknown_type $member_id
	 */
	public function memberEmailActivate($member_id)
	{
		$sql = "UPDATE " . DB_PREFIX . "member SET is_email = 1 WHERE id = " . $member_id ;
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	/***************************************ucenter*********************************************************/
	
	/**
	 * 修改ucenter会员信息
	 * Enter description here ...
	 * @param unknown_type $uc_id
	 * @param unknown_type $member_name
	 * @param unknown_type $email
	 * @param unknown_type $old_password
	 * @param unknown_type $new_password
	 */
	public function uc_user_edit($uc_id, $member_name, $email, $old_password, $new_password)
	{		
		$ret = uc_user_edit($member_name, $old_password, $new_password, $email);
		return $ret;
	}
	
	/**
	 * ucenter会员登陆
	 * Enter description here ...
	 * @param unknown_type $member
	 * @param unknown_type $new_password
	 */
	public function ucenter_login($member_name, $password)
	{	
		$ret = uc_user_login($member_name, $password);
		return $ret;
	}
	
	public function uc_user_register($member_name, $password, $email)
	{
		$ret = uc_user_register($member_name, $password, $email);
		return $ret;
	}
	
	/**************************************************检测************************************************************/
	
	function check_member_name($member_name) 
	{
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($member_name);
		if($len > 15 || $len < 3 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $member_name))
		{
			return FALSE;
		} 
		else 
		{
			return TRUE;
		}
	}

	function dstrlen($str) 
	{
		if(strtolower(UC_CHARSET) != 'utf-8') 
		{
			return strlen($str);
		}
		$count = 0;
		for($i = 0; $i < strlen($str); $i++)
		{
			$value = ord($str[$i]);
			if($value > 127) 
			{
				$count++;
				if($value >= 192 && $value <= 223) $i++;
				elseif($value >= 224 && $value <= 239) $i = $i + 2;
				elseif($value >= 240 && $value <= 247) $i = $i + 3;
		    }
	    	$count++;
		}
		return $count;
	}

	function check_member_name_exists($member_name, $uc_id='', $member_id = '') 
	{
		$condition = '';
		if ($uc_id)
		{
			$condition .= " AND uc_id = " . $uc_id;
		}
		
		if ($member_id)
		{
			$condition .= " AND id NOT IN (" . $member_id . ")";
		}
		
		$binary = '';//不区分大小些
		if(defined('DIFFER_SIZE') && !DIFFER_SIZE)//区分大小些
		{
			$binary = 'binary ';
		}		
		$sql = "SELECT id, member_name, is_bound, uc_id FROM ".DB_PREFIX."member WHERE " . $binary . " member_name='" . $member_name . "'" . $condition;
		$data = $this->db->query_first($sql);
		return $data;
	}
		
	function _check_member_name($member_name) 
	{
		$member_name = addslashes(trim(stripslashes($member_name)));

		if(!$this->check_member_name($member_name)) 
		{
			return -1;//长度检测
		} 
		elseif($this->check_member_name_exists($member_name))
		{
			return -2;//用户名存在
		}
		return 1;
	}

	function check_email_format($email)
	{
		return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
	}

	function check_email_exists($email) 
	{
		$sql = "SELECT id, member_name, email FROM  " . DB_PREFIX . "member WHERE email='".$email."'";
		$email = $this->db->query_first($sql);
		return $email;
	}

	function _check_email($email) 
	{
		if(!$this->check_email_format($email)) 
		{
			return -3;//邮箱不合法
		} 
		elseif($this->check_email_exists($email))
		{
			return -4;//邮箱已存在
		}
		else 
		{
			return 1;
		}
	}
	
	public function check_mobile_exists($mobile, $member_id = '')
	{
		$mobile = $this->prefix . $mobile;
		
		if ($member_id)
		{
			$condition = " AND id NOT IN (" . $member_id . ")";
		}
		
		$sql = "SELECT mobile FROM  " . DB_PREFIX . "member WHERE mobile='" . $mobile . "'" . $condition;
		$mobile = $this->db->query_first($sql);
		return $mobile;
	}
	
	public function check_member_count($member_id,$appid)
	{
		$sql = "SELECT id FROM " . DB_PREFIX . "member_info_count ";
		$sql .= " WHERE member_id = " . $member_id . " AND appid = " . $appid;
		$member_count = $this->db->query_first($sql);
		
		if (!empty($member_count))
		{
			return true;
		}
		return false;
	}
	
	public function check_email_activate($member_id)
	{
		$sql = "SELECT is_email FROM " . DB_PREFIX . "member WHERE id = " . $member_id ;
		$is_email = $this->db->query_first($sql);
		if ($is_email['is_email'])
		{
			return true;
		}
		return false;
	}
	
	public function add_activity_account($user_id)
	{
		$sql = "UPDATE " . DB_PREFIX . "member_extra SET activity_account = activity_account + 1 WHERE member_id = " . $user_id;
		$this->db->query($sql);
		return $user_id;
	}
	
	public function del_activity_account($user_id)
	{
		$sql = "UPDATE " . DB_PREFIX . "member_extra SET activity_account = activity_account - 1 WHERE member_id = " . $user_id;
		$this->db->query($sql);
		return $user_id;
	}

	public function check_nick_name_exists($nick_name,$user_id)
	{
		$sql = "SELECT nick_name FROM " . DB_PREFIX . "member WHERE nick_name='$nick_name' AND id NOT IN(" . $user_id . ")";
		$data = $this->db->query_first($sql);
		return $data;
	}
	
	public function check_platform_name_exists($member_name, $platform)
	{
		$binary = '';//不区分大小些
		if(defined('DIFFER_SIZE') && !DIFFER_SIZE)//区分大小些
		{
			$binary = 'binary ';
		}
		$sql = "SELECT member_name FROM " . DB_PREFIX . "member ";
		$sql.= " WHERE " . $binary . " member_name= '" . $member_name . "'";
		//$sql.= " AND platform = '" . $platform . "'";
		$member = $this->db->query_first($sql);
		return $member;
	}
	
	public function check_member_bound_exists($member_name = '', $platform = '', $platform_id = '')
	{
		$condition = '';
		
		$binary = '';//不区分大小些
		if(defined('DIFFER_SIZE') && !DIFFER_SIZE)//区分大小些
		{
			$binary = 'binary ';
		}
		
		if ($member_name && !$platform && !$platform_id)
		{
			$condition = " WHERE " . $binary . " member_name= '" . $member_name . "'";
		}
		elseif (!$member_name && $platform && $platform_id)
		{
			$condition = " WHERE platform = " . $platform . " AND platform_id = " . $platform_id;
		}
		elseif ($member_name && $platform && $platform_id)
		{
			$condition = " WHERE " . $binary . " member_name= '" . $member_name . "' AND platform = " . $platform . " AND platform_id = " . $platform_id;
		}
		else 
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_bound " . $condition;
		
		$data = $this->db->query_first($sql);
		return $data;
	}
	
	/**********************************************发布**************************************************/
	
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($id,$op,$column_id = array(),$child_queue = 0,$user_name="")
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX ."member WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}			
		}
		else 
		{
			$column_id = implode(',',$column_id);
		}

 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	MEMBER_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['member_name'],
			'action_type' => $op,
			'publish_time'  => $info['pub_time'],
			'publish_people' => $user_name,
			'ip'   =>  hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = MEMBER_PLAN_SET_ID;
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	public function publish($user_name)
	{
		$id = intval($this->input['id']);
		$column_id = trim($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	
		//查询修改文章之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."member where id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$sql = "update " . DB_PREFIX ."member set column_id = '". $column_id ."' where id = " . $id;
		$this->db->query($sql);
		
		if(intval($q['status']) == 1)
		{
			if(!empty($q['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($id, 'delete',$del_column,'',$user_name);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($id, 'insert',$add_column,'',$user_name);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($id, 'update',$same_column,'',$user_name);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				$this->publish_insert_query($id,$op,'','',$user_name);
			}
		}
		else    //打回
		{
			if(!empty($q['expand_id']))
			{
				$op = "delete";
				$this->publish_insert_query($id,$op,'','',$user_name);
			}
		}
		
		return true;
		
	}
	/************************************************************************************************/
	
	public function getBoundById($member_id,$platform)
	{
		if(empty($member_id) || empty($platform))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "member_bound WHERE member_id=" . $member_id . " AND platform=" . $platform;
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function add_visit($member_id,$scan_num)
	{	
		if(empty($member_id))
		{
			return false;
		}
		$sql = "SELECT member_id FROM " . DB_PREFIX . "member_extra WHERE member_id=" . $member_id;
		$f = $this->db->query_first($sql);
		if(!empty($f))
		{
			$sql = "UPDATE " . DB_PREFIX . "member_extra SET visit_count=visit_count+" . intval($scan_num) . " WHERE member_id=" . $member_id;
			$this->db->query($sql);
			return true;
		}
	}
	
	public function edit_member_mark($mark,$user_id)
	{
		if(empty($user_id) || empty($mark))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "member_info WHERE member_id=" . $user_id;
		$f = $this->db->query_first($sql);
		$sql = "";
		if(empty($f))
		{
			$sql = "INSERT INTO " . DB_PREFIX . "member_info(member_id,mark) VALUES(" . $user_id . ",'" . $mark . "')";
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "member_info SET mark='" . $mark . "' WHERE member_id=" . $user_id;
		}
		$this->db->query($sql);
		include_once ROOT_PATH . 'lib/class/mark.class.php';
		$obj_mark = new mark();
		$data = array(
			'user_id' => $user_id,
			'source' => 'user',
			'source_id' => $user_id,
			'action' => 'myself',
			'name' => $mark,
		);	
		$mark = $obj_mark->create_source_id_mark($data);
		//file_put_contents('./cache/1.php',var_export($mark,true));
		return true;

	}
	
	public function getConstellation()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "member_constellation WHERE 1";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[$row['constellation_id']] = $row['constellation_name'];
		}
		return $info;
	}
	
	public function email_edit($member_id, $email)
	{
		$sql = "UPDATE " . DB_PREFIX . "member SET email='" . $email . "', is_email=0 WHERE id = " . $member_id;
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_all_mark($con)
	{
		$sql = "SELECT member_id,mark FROM " . DB_PREFIX . "member_info WHERE 1 " . $con;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function member_info_edit($member_id, $data)
	{
		$sql = "UPDATE " . DB_PREFIX . "member SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $member_id;
		
		$data['member_id'] = $member_id;
		
		if ($this->db->query($sql))
		{
			return $data;
		}
		
		return false;
	}
	
	public function add_bound($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member_bound SET ";
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
	
	public function get_member_bound_info($member_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member_bound ";
		$sql.= " WHERE member_id = " . $member_id;
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	public function delete_bound_by_plat_info($plat_info)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_bound ";
		$sql.= " WHERE member_id = " . $plat_info['member_id'] . " AND platform = " . $plat_info['platform'] . " AND platform_id = " . $plat_info['platform_id'];
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
}

?>