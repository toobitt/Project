<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
//在没有出入任何可选数据时 控件默认调用的类

class NodePrms
{	
	private $db;
	private $input;
	private $cache;
	private $mod_uniqueid = '';
	function __construct()
	{
		global $_INPUT;
		global $gGlobalConfig;
		global $gCache;
		$this->db = hg_checkDB();
		$this->cache =  $gCache;
		$this->input = $_INPUT;
	}
	function __destruct()
	{
		
	}

	function initNodeCurl($app = array())
	{
		if(!$this->nodevar)
		{
			return false;
		}
		
		$sql = 'SELECT node_uniqueid From '.DB_PREFIX.'node WHERE application_id = '.$app['id'].' AND node_uniqueid = "'.$this->nodevar.'"';
		$node = $this->db->query_first($sql);
		if(!$node)
		{
			return false;
		}
		$host = $app['host'];
		$dir = $app['dir'];
		//$dir = str_replace('admin/', '', $apps[$appid]['dir']);
		$this->curl = new curl($host, $dir);
		$this->curl->initPostData();
		//$this->curl->addRequestData('access_token', $this->user['token']);
		$this->curl->addRequestData('trigger_mod_uniqueid', $this->mod_uniqueid);
		$ac = trim($this->input['ac']) ? trim($this->input['ac']) : 'show';
		$this->curl->addRequestData('trigger_action', $ac);
		return true;
	}
	public function getNodeDataByAppN($app_uniqueid = '', $nodevar = '')
	{
		$this->nodevar = $nodevar;
		$this->cache->check_cache('applications');
		$apps = $this->cache->cache['applications'];
		$app = array();
		if($apps)
		{
			foreach($apps as $appid=>$v)
			{
				if($v['softvar'] == $app_uniqueid)
				{
					$app = $v;
					break;
				}
			}
		}
		if(!$this->initNodeCurl($app))
		{
			return -1;
		}
		$this->curl->addRequestData('fid', intval($this->input['fid']));
		return $node_data = $this->curl->request($this->nodevar . '.php');
	}
	public function Mid2App($mid)
	{
		$this->cache->check_cache('modules');
		$modules = $this->cache->cache['modules'];
		if(!$modules[$mid])
		{
			return false;
		}
		$this->mod_uniqueid = $modules[$mid]['mod_uniqueid'];
		$appid = $modules[$mid]['application_id'];
		$this->cache->check_cache('applications');
		$apps = $this->cache->cache['applications'];
		
		if(!$apps[$appid]['softvar'])
		{
			return false;
		}
		return $apps[$appid];
		
	}
	public function getNodeDataByMidN($mid = 0, $nodevar = '')
	{
		$hg_nodes = array();
		$app = $this->Mid2App($mid);
		$this->nodevar = $nodevar;
		if(!$this->initNodeCurl($app))
		{
			return -1;
		}
		$this->curl->addRequestData('fid', intval($this->input['fid']));
		$node_data = $this->curl->request($this->nodevar . '.php');
		if($node_data)
		{
			foreach ($node_data as $k=>$v)
			{
				$v['biaoshi'] = $mid . '@' . $this->nodevar;
				$hg_nodes[] = $v;
			}
		}
		return $hg_nodes;
	}
	/*
	//节点授权时获取已选中的节点
	public function get_selected_node($node_info = '', $prms_setting = FALSE)
	{
		$ret_node = array();
		if(!$node_info)
		{
			return;
		}
		if(!is_array($node_info) || !count($node_info))
		{
			return;
		}
		foreach ($node_info as $key => $val)
		{
			//无分类
			$default =array(
				'0' => array(
					'id' 		=> '0',
					'fid' 		=> '0',
					'name' 		=> '管理无分类',
					'biaoshi' 	=> $key,
					'parents'	=> '1',
				    'childs' 	=> '1',
				    'depath' 	=> '1',
				    'is_last' 	=> 0,
					'is_auth' 	=> 1,
					'order_id' 	=> '1',
				)
			);
			$mid_nodevar = explode('@', $key);
			$app = $this->Mid2App($mid_nodevar[0]);
			$this->nodevar = $mid_nodevar[1];
			$this->initNodeCurl($app);
			if(is_array($val))
			{
				$selected_id = implode(',',$val);
			}
			if($selected_id === '0' && $prms_setting)
			{
				return array(0=>$default);
			}
			$this->curl->addRequestData('id', $selected_id);
			$this->curl->addRequestData('a', 'get_selected_node_path');
			$res = $this->curl->request($mid_nodevar[1].'.php');
			
			if(is_array($res) && count($res))
			{
				foreach ($res as $k=>$v)
				{
					foreach ($v as $kk=>$vv)
					{
						$v[$kk]['biaoshi'] = $key;//节点标识
					}
					$ret_node[] = $v;
				}
				if(in_array('0', $val) && $prms_setting)
				{
					$ret_node[] = $default;
				}
			}
		}
		return $ret_node;
	}
	*/
}

?>