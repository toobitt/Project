<?php
define('SCRIPT_NAME', 'certificate');
define('MOD_UNIQUEID','certificate');
require_once('./global.php');
require(CUR_CONF_PATH."lib/functions.php");
class certificate extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	}
	function show()
	{
		//检测是否具有配置权限,exclude_auth排除发送消息请求应用时权限判断
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'] && !$this->input['exclude_auth'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	        //允许管理的应用
			$app_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
	    }
	    
		$order = ' ORDER BY appid DESC';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
	    
	    $sql = 'SELECT * FROM '.DB_PREFIX.'certificate  WHERE 1';
		$condition = $this->get_condition();	
		if($app_ids)
		{
			$condition .= ' AND appid IN ('.implode(',', $app_ids).')';
		}	
		$sql = $sql . $condition . $order . $limit;
		
		$q = $this->db->query($sql);
		
		$appAuthInfo = $this->get_app_auth();
		
		if($appAuthInfo)
		{
			foreach ($appAuthInfo as $k => $v)
			{
				$authInfo[$v['appid']] = $v['custom_name'];
			}
		}
		
		while($row = $this->db->fetch_array($q))
		{
			if($authInfo[$row['appid']])
			{
				$row['appname'] = $authInfo[$row['appid']];
			}
			else 
			{
				$row['appname'] = '此应用已删除';
			}
			if($row['send_way'])
			{
				$row['send_way'] = '推送';
			}
			else 
			{
				$row['send_way'] = '拉取';
			}
			$this->addItem($row);
		}
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND appname LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND appid = '.$this->input['id'];
		}
		if($this->input['app_id'] && $this->input['app_id'] != -1)
		{
			$condition .= ' AND appid = '.$this->input['app_id'];	
		}

		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'certificate  WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	function create()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    $this->errorOutput('没有权限创建应用');
	    }
	    
		$appid = intval($this->input['app_id']);
		if(!$appid)
		{
			$this->errorOutput('应用id不存在');
		}
		if(!is_dir(ZS_DIR . $appid . '/'))
		{
			hg_mkdir(ZS_DIR . $appid . '/');
		}
		
		if ($_FILES)
		{
			foreach ($_FILES as $k => $v)
			{
				if ($_FILES[$k]["error"] > 0)
				{
					$this->erroroutput($_FILES[$k]["error"]);
				}
				else
				{
					if($v["type"] == 'application/x-pkcs12')
					{
						$file_name = $k . '_' . $appid . '.p12';
						if (!@move_uploaded_file($_FILES[$k]["tmp_name"],ZS_DIR . $appid . '/' . $file_name))
						{
							continue;
						}
						else 
						{
							if(is_file(ZS_DIR . $appid . '/' . $k . '_push.pems'))
							{
								@unlink(ZS_DIR . $appid . '/' . $k . '_push.pems');
							}
						}
						
						$cmd = './mkprem.py -d' . ZS_DIR . $appid . '/ -i' . ZS_DIR . $appid . '/' . $file_name;
						exec($cmd);
						if (is_file(ZS_DIR . $appid . '/push.pems'))
						{
							rename(ZS_DIR . $appid . '/push.pems', ZS_DIR . $appid . '/' . $k . '_push.pems');
							$info[$k] = $appid . '/' . $k . '_push.pems';
						}
						@unlink(ZS_DIR . $appid . '/key.pem');
						@unlink(ZS_DIR . $appid . '/cert.pem');
						@unlink(ZS_DIR . $appid . '/key.unencrypted.pem');
					}
				}
			}
		}
		$info['appid'] = $appid;
		$info['appname'] = $this->input['app_name'];
		
		$info['version'] = $this->input['version'];
		$info['force_up'] = intval($this->input['force_up']);
		$info['up_url'] = $this->input['up_url'];
		
		$info['link_appid'] = intval($this->input['link_appid']);
		
		$sql = 'SELECT * FROM ' . DB_PREFIX .'certificate WHERE  appid='.$appid;
		if($this->db->query_first($sql))
		{
			$sql = "UPDATE ".DB_PREFIX."certificate SET ";
			foreach($info as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$sql = rtrim($sql, ',');
			$sql .= " WHERE appid=".$appid;
		}
		else 
		{
			$sql = "INSERT INTO ".DB_PREFIX."certificate SET ";
			foreach($info as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$sql = rtrim($sql, ',');
		}
		
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$appid = intval($this->input['app_id']);
		if(!$appid)
		{
			$this->errorOutput('appid不存在');
		}
		if(!is_dir(ZS_DIR . $appid . '/'))
		{
			hg_mkdir(ZS_DIR . $appid . '/');
		}
		if ($_FILES)
		{
			foreach ($_FILES as $k => $v)
			{
				if ($_FILES[$k]["error"] > 0)
				{
					$this->erroroutput($_FILES[$k]["error"]);
				}
				else
				{
					if($v["type"] == 'application/x-pkcs12')
					{	
						if (!$_FILES[$k]["tmp_name"])
						{
							continue;
						}
					
						$file_name = $k . '_' . $appid . '.p12';
						if (!@move_uploaded_file($_FILES[$k]["tmp_name"],ZS_DIR . $appid . '/' . $file_name))
						{
							continue;
						}
						else 
						{
							if(is_file(ZS_DIR . $appid . '/' . $k . '_push.pems'))
							{
								@unlink(ZS_DIR . $appid . '/' . $k . '_push.pems');
							}
						}
						$cmd = './mkprem.py -d' . ZS_DIR . $appid . '/ -i' . ZS_DIR . $appid . '/' . $file_name;
						exec($cmd);
						if (is_file(ZS_DIR . $appid . '/push.pems'))
						{
							rename(ZS_DIR . $appid . '/push.pems', ZS_DIR . $appid . '/' . $k . '_push.pems');
							$info[$k] = $appid . '/' . $k . '_push.pems';
						}
						@unlink(ZS_DIR . $appid . '/key.pem');
						@unlink(ZS_DIR . $appid . '/cert.pem');
						@unlink(ZS_DIR . $appid . '/key.unencrypted.pem');
					}
				}
			}
		}
		$info['appid'] = $appid;
		$info['appname'] = $this->input['app_name'];
		$info['link_appid'] = intval($this->input['link_appid']);
		
		$info['version'] = $this->input['version'];
		$info['force_up'] = intval($this->input['force_up']);
		$info['up_url'] = $this->input['up_url'];
		
		$sql = "UPDATE ".DB_PREFIX."certificate SET ";
		foreach($info as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$sql = rtrim($sql, ',');
		$sql .= " WHERE appid=".$appid;
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	//查看证书
	function check_cert()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
	    
		$appid = intval($this->input['id']);
		if(!$appid)
		{
			$this->errorOutput('应用id不存在！');
		}
		if(@$develop = file_get_contents(ZS_DIR . $appid . '/develop_push.pems'))
		{
			$cert['develop'] = $develop;
		}
		
		if(@$apply = file_get_contents(ZS_DIR . $appid . '/apply_push.pems'))
		{
			$cert['apply'] = $apply;
		}
		$this->addItem($cert);
		$this->output();
	}
	function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('未找到应用id');
		}
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."certificate WHERE 1 ".$condition;
		
		$q = $this->db->query_first($sql);
		
		if($q)
		{
			$appAuthInfo = $this->get_app_auth();
			if($appAuthInfo)
			{
				if($appAuthInfo)
				{
					foreach ($appAuthInfo as $k => $v)
					{
						$authInfo[$v['appid']] = $v['custom_name'];
					}
				}
				if($authInfo[$q['appid']])
				{
					$q['appname'] = $authInfo[$q['appid']];
				}
				else 
				{
					$q['appname'] = '此应用已删除';
				}
			}
		}
		
		$this->addItem($q);
		$this->output();
	}
	function delete()
	{
		$ids = trim(urldecode($this->input['id']));
		if(!$this->input['id'])
		{
			$this->errorOutput(NOAPPID);
		}
		
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	        
	        //判断被删除的应用是否在授权的应用里
	        $app_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
	        if($app_ids)
	        {
	        	$ids_arr = explode(',', $ids);
	        	foreach ($ids_arr as $k => $v)
	        	{
	        		if(!in_array($v, $app_ids))
	        		{
	        			$this->errorOutput('没有权限删除此应用');
	        		}
	        	}
	        }
	    }
		$sql = 'DELETE FROM '.DB_PREFIX.'certificate WHERE appid in('.$ids.')';
		$this->db->query($sql);
		
		//删除文件和目录
		$id_arr = explode(',', $ids);
		if($id_arr)
		{
			foreach ($id_arr as $k => $appid)
			{
				deldir(ZS_DIR . $appid);
			}
		}
		$this->addItem('success');
		$this->output();
	}
	//获取方式
	function set_way()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('NOID');
		}
		//权限判断
	  	if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$sql = 'SELECT send_way FROM '.DB_PREFIX.'certificate WHERE appid='.$this->input['id'];
		$res = $this->db->query_first($sql);
		$way = $res['send_way'];
		if($way)
		{
			$set_way = 0;
		}
		else 
		{
			$set_way = 1;
		}
		$sql = 'UPDATE '.DB_PREFIX.'certificate SET send_way='.$set_way.' WHERE appid='.$this->input['id'];
		$this->db->query($sql);
		$data['send_way'] = $set_way;
		$data['id'] = $this->input['id'];
		
		$this->addItem($data);
		$this->output();
	}
	public function append_appinfo()
	{
		$sql = "SELECT appid,appname FROM ".DB_PREFIX."certificate ORDER BY appid DESC";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$appinfo[$r['appid']] = $r['appname'];
		}
		$this->addItem($appinfo);
		$this->output();
	}
	
	//获取授权应用
	public function append_app_auth()	
	{	
		$app_auth = $this->get_app_auth();
		
		if($app_auth)
		{
			$sql = "SELECT appid,appname FROM ".DB_PREFIX."certificate ORDER BY appid DESC";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$appinfo[$r['appid']] = $r['appname'];
			}
			foreach($app_auth as $k=>$v)
			{
				if($appinfo[$v['appid']])
				{
					continue;
				}
				$apps[$v['appid']] = $v['custom_name'];
			}
		}
		
		$this->addItem($apps);
		$this->output();
	}	
	public function get_app_auth()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->pub = new Auth();
		$app_auth = $this->pub->get_auth_list(0,100);
		return $app_auth;
	}
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
}
include(ROOT_PATH . 'excute.php');