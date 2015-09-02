<?php
require_once './global.php';
include_once ROOT_PATH . 'lib/class/curl.class.php';
define('MOD_UNIQUEID', 'sync');  //模块标识
class syncApi extends appCommonFrm
{
	
	public function __construct()
	{
		parent::__construct();

	}
	public function __destruct()
	{
		parent::__destruct();

	}
	public function create()
	{
		$name = $this->input['uname'];
		if ($name)
		{
			$sql = 'SELECT id,seekhelp_sort_id FROM '.DB_PREFIX.'user WHERE user_name ="'.$name.'"';
			$result = $this->db->query_first($sql);
			if (!$result['seekhelp_sort_id'])
			{
				//注册互助分类
				if ($this->settings['App_seekhelp'])
				{
					$this->curl = new curl($this->settings['App_seekhelp']['host'], $this->settings['App_seekhelp']['dir']);
					$this->curl->setSubmitType('post');
					$this->curl->setReturnFormat('json');
					$this->curl->initPostData();
					$this->curl->addRequestData('a','create');
					$this->curl->addRequestData('name', $name);
					$this->curl->addRequestData('brief', $name);
					$this->curl->addRequestData('fid', 0);
					$ret = $this->curl->request('seekhelp_node_update.php');
					if ($ret && is_array($ret))
					{
						$seekhelp_sort_id = $ret[0]['id'];
					}
				}	
				$sql = 'UPDATE '.DB_PREFIX.'user SET seekhelp_sort_id = '.$seekhelp_sort_id.' WHERE id = '.$result['id'];
				$this->db->query($sql);
			}
		}
		$this->addItem('success');
		$this->output();
	}
}

$out = new syncApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>