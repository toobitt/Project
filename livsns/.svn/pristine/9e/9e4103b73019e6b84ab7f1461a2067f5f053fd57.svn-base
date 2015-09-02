<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auth.php 6701 2012-05-14 07:49:07Z zhoujiafei $
***************************************************************************/
define('MOD_UNIQUEID','auth');
define('CUR_CONF_PATH', '../');
require('./global.php');
class auth_update extends Auth_frm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function sort(){}
	public function publish(){}
	public function create()
	{
		if($this->user['user_id'] != 0 && $this->user['group_type']!=9999999999)
		{
			$this->verify_setting_prms();
		}
		if(!$this->input['custom_name'])
		{
			$this->errorOutput('没有客户名');
		}

		if(!$this->input['bundle_id'])
		{
			$this->errorOutput('没有bundle_id');
		}

		//查询数据库里面有没有该客户名
		$sql = " SELECT appid FROM ".DB_PREFIX."authinfo WHERE custom_name = '".urldecode($this->input['custom_name'])."'";
		$arr = $this->db->query_first($sql);
		if($arr['appid'])
		{
			$this->errorOutput('该客户名已经存在');
		}
		$bundle_id = urldecode($this->input['bundle_id']);
		$sql = " SELECT appid FROM ".DB_PREFIX."authinfo WHERE bundle_id = '" . $bundle_id."'";
		$arr = $this->db->query_first($sql);
		if($arr['appid'])
		{
			$this->errorOutput('bundle_id已存在');
		}
		//获取appkey
		$appkey = $this->create_appkey();
		$expire_time = strtotime($this->input['expire_time']);
		$sql  = " INSERT INTO ".DB_PREFIX."authinfo SET ";
		$sql .= " custom_name = '".urldecode($this->input['custom_name'])."',".
		 		" custom_desc = '".urldecode($this->input['custom_desc'])."',".
		 		" bundle_id = '".$bundle_id."',".
		 		" domain = '".urldecode($this->input['domain'])."',".
		 		" display_name = '".urldecode($this->input['display_name'])."',".
		 		" expire_time = '".$expire_time."',".
		 		" appkey = '".$appkey."',".
		 		" create_time = '".TIMENOW."',".
		 		" update_time = '".TIMENOW."',".
				" mobile = '".intval($this->input['mobile'])."',".
		 		" is_auth = ".intval($this->input['is_auth']);

		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."authinfo SET order_id = '".$vid."' WHERE appid = '".$vid."'";
		$this->db->query($sql);
		//把值返回
		$sql = "SELECT * FROM " .DB_PREFIX."authinfo WHERE appid = {$vid}";
		$ret = $this->db->query_first($sql);
		//写入日志系统
		$this->addLogs('create', '', $ret, $vid, 0);
		$this->addItem($ret);
		$this->output();
	}

	public function create_appkey()
	{
		//生成appkey
		$keys = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$appkey = '';
		for($i = 0;$i<32;$i++)
		{
			$n = rand(0,61);
			$appkey .= $keys[$n];
		}

		$sql = " SELECT * FROM " .DB_PREFIX. "authinfo WHERE appkey = '".$appkey."'";
		$ret = $this->db->query_first($sql);
		if($ret['appid'])
		{
			return $this->create_appkey();
		}
		else
		{
			return $appkey;
		}
	}

	//重新更改appkey
	public function rebind_appkey()
	{
		if(!$this->input['appid'])
		{
			$this->errorOutput(NOID);
		}
		$appkey = $this->create_appkey();

		$sql = " UPDATE ".DB_PREFIX."authinfo SET appkey = '".$appkey."' WHERE appid = '".intval($this->input['appid'])."'";
		$this->db->query($sql);
		$this->addItem(array('appid' => intval($this->input['appid']),'appkey' => $appkey));
		$this->output();
	}

	public function update()
	{
		$this->verify_setting_prms();
		if(!$this->input['appid'])
		{
			$this->errorOutput(NOID);
		}
		//检测是否可以修改
        $sql = 'SELECT * FROM '.DB_PREFIX.'authinfo WHERE appid = '.intval($this->input['appid']);
        $return = $this->db->query_first($sql);
       
		$fields = ' SET  ';
		if($this->input['custom_name'])
		{
			$sql = " SELECT count(*) as total FROM ".DB_PREFIX."authinfo WHERE custom_name = '".urldecode($this->input['custom_name'])."' AND appid != '".intval($this->input['appid'])."'";
			$arr = $this->db->query_first($sql);
			if(intval($arr['total']) >= 1)
			{
				$this->errorOutput('客户名已经存在');
			}

			$fields .= '  custom_name = \''.urldecode($this->input['custom_name']).'\',';
		}
		else
		{
			$this->errorOutput('客户名不能为空');
		}
	
		if($this->input['display_name'])
		{
			$fields .= '  display_name = \''.urldecode($this->input['display_name']).'\',';
		}
		if ($return['is_update'] || ($return['expire_time']<TIMENOW && $return['expire_time']!=0))
        {
			if($this->input['bundle_id'])
			{
				$sql = " SELECT count(*) as total FROM ".DB_PREFIX."authinfo WHERE bundle_id = '".urldecode($this->input['bundle_id'])."' AND appid != '".intval($this->input['appid'])."'";
				$arr = $this->db->query_first($sql);
				if(intval($arr['total']) >= 1)
				{
					$this->errorOutput('bundle_id已经存在');
				}
	
				$fields .= '  bundle_id = \''.urldecode($this->input['bundle_id']).'\',';
			}
			else
			{
				$this->errorOutput('bundle_id不能为空');
			}
	
			if($this->input['custom_desc'])
			{
				$fields .= '  custom_desc = \''.urldecode($this->input['custom_desc']).'\',';
			}
	
	
			if($this->input['domain'])
			{
				$fields .= '  domain = \''.urldecode($this->input['domain']).'\',';
			}
			$fields .= '  mobile = \''.intval($this->input['mobile']).'\',';
			if(isset($this->input['expire_time']))
			{
				$expire_time = strtotime(urldecode($this->input['expire_time']));
				$fields .= '  expire_time = \''.$expire_time.'\',';
			}
			$fields .= '  is_auth = \''.intval($this->input['is_auth']).'\',';
			$fields .= '  update_time = \''.TIMENOW.'\',';
        }
        $fields = rtrim($fields,',');
	    $sql = "UPDATE ".DB_PREFIX.'authinfo ' . $fields .'  WHERE  appid = ' . intval($this->input['appid']);
		$this->db->query($sql);

		//返回数据
		$sql = "SELECT * FROM ".DB_PREFIX."authinfo WHERE appid = '".intval($this->input['appid'])."'";
		$ret = $this->db->query_first($sql);
		//写入日志系统
	    foreach ($return as $key=>$val)
	    {
	    	if ($ret[$key] != $val)
	    	{
	    		$this->addLogs('update', $return, $ret, intval($this->input['appid']), 0);break;
	    	}
	    }
		$ret['expire_time'] = $ret['expire_time'] ? date('Y-m-d',$ret['expire_time']) : $ret['expire_time'];
		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{
		$this->verify_setting_prms();
		if(!$this->input['appid'])
		{
			$this->errorOutput(NOID);
		}
		//检测是否可以修改
        $sql = 'SELECT * FROM '.DB_PREFIX.'authinfo WHERE appid = '.intval($this->input['appid']);
        $return = $this->db->query_first($sql);
        if ($return['is_update'])
        {
			$sql = " DELETE FROM " .DB_PREFIX. "authinfo WHERE appid IN (".urldecode($this->input['appid']).")";
			$this->db->query($sql);
        }else
        {
        	$this->errorOutput('不可删除');
        }
        //纪录日志
        $this->addLogs('delete', $return, '', intval($this->input['appid']), 0);
		$this->addItem('success');
		$this->output();
	}

	//审核
	public function audit()
	{
		/*
		if(!$this->input['appid'])
		{
			$this->errorOutput(NOID);
		}

		$sql = "SELECT * FROM ".DB_PREFIX."authinfo WHERE appid = '".intval($this->input['appid'])."'";
		$data = $this->db->query_first($sql);

		$sql = " UPDATE ".DB_PREFIX."authinfo SET status = {$status} WHERE appid = '".intval($this->input['appid'])."'";
		$this->db->query($sql);

		//返回数据
		$this->addItem(array('appid' => intval($this->input['appid']),'status' => $this->settings['auth_status'][$status],'bt_val' => $bt_val));
		$this->output();
		*/
	}

	public function see_appkey()
	{
		if(!$this->input['appid'])
		{
			$this->errorOutput(NOID);
		}

		$sql = "SELECT * FROM ".DB_PREFIX."authinfo WHERE appid = '".intval($this->input['appid'])."'";
		$arr = $this->db->query_first($sql);
		$this->addItem($arr);
		$this->output();
	}
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$out = new auth_update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>