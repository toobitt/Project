<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'columnnode');
require('./global.php');
require (ROOT_PATH . 'lib/class/curl.class.php');
class columnnode extends uiBaseFrm
{	
	private $site;
	function __construct()
	{
		parent::__construct();
		if(!$this->input['siteid'])
		{
			$this->input['siteid'] = 1;
		}
	//	$this->init();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	function config()
	{
		$curl =  new curl('localhost', 'livsns/api/auth/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$ret = $curl->request('configuare.php');
		$user_configs = array(
			'base' => $ret['base'],
			'define' => $ret['define'],
		);
		$curl =  new curl('10.0.1.40', 'livworkbench/api/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->addRequestData('app', 'auth');
		$curl->addRequestData('version', '1.0.0');
		$curl->addRequestData('pre_release', '1');
		$new_configs = $curl->request('config.php');
		if ($new_configs)
		{
			$doset = array();
			foreach ($new_configs AS $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v AS $kk => $vv)
					{
						if (!$user_configs[$k][$kk])
						{
							$doset[$k][$kk] = $vv;
						}
					}
				}
			}
		}
		if ($doset)
		{
			$curl =  new curl('localhost', 'livsns/api/auth/');
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a', 'doset');
			
			foreach ($doset AS $k => $v)
			{
					foreach($v AS $kk => $vv)
					{
						if (is_array($vv))
						{
							foreach($vv AS $kkk => $vvv)
							{
								$curl->addRequestData($k . "[$kk][$kkk]", $vvv);
							}
						}
						else
						{
							$curl->addRequestData($k . "[$kk]", $vv);
						}
					}
			}
			
			$ret = $curl->request('configuare.php');
		}
		print_r($doset);
		print_r($curl);
	}
	function init()
	{
		$siteid = intval(trim(urldecode($this->input['siteid'])));
		$sql = 'SELECT * FROM '.DB_PREFIX.'sites WHERE id = "'.$siteid.'"';
		if($site  = $this->db->query_first($sql))
		{
			$this->site = $site;
		}
	}
	public function show()
	{
		echo '<pre>';
		$this->cache->recache('menu');
		$this->cache->check_cache('menu');
		$menu = $this->cache->cache['menu'];
		foreach ($menu AS $k => $m)
		{		
			$data = array(
				'name' => $m['name'], 	 	
				'module_id' => $m['id'], 	
				'url' => $m['url'],
				'class' => $m['class'],
				'father_id' => 0,
			);
			//hg_fetch_query_sql($data, 'menu');
			//$mid = $this->db->insert_id();
			if ($m['childs'])
			{
				foreach ($m['childs'] AS $k => $m)
				{
					$data = array(
						'name' => $m['name'], 	 	
						'module_id' => $m['id'], 	
						'url' => $m['url'],
						'class' => $m['class'],
						'father_id' => $mid,
					);
					//hg_fetch_query_sql($data, 'menu');
					//$mid = $this->db->insert_id();
				}
			}
		}
		//print_r($menu);
		//exit;
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 2000;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = 'SELECT id,name,fatherid,is_last FROM '.DB_PREFIX.'columns WHERE 1 ';
		$conditions = $this->get_conditions() . ' ORDER BY id ASC ';
		$sql = $sql . $conditions . $data_limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['fid'] = $row['fatherid'];
			unset($row['fatherid']);
			$hg_columns[$row['id']] = $row;
		}
		//print_r($this->input);exit($sql);
		if($this->input['fid'] > 0)
		{
			exit(json_encode($hg_columns));
		}
		$this->tpl->addVar('hg_columns', $hg_columns);
		$hg_columns_selected = array();
		$sql = 'SELECT colid FROM '.DB_PREFIX.'publish WHERE conid = 1 AND mid = 1 AND siteid = 1';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$hg_columns_selected[] = $r['colid'];
		}
		$this->tpl->addVar('hg_columns_selected', $hg_columns_selected);
		$this->tpl->outTemplate('test');
	}
	private function get_conditions()
	{
		$conditions = ' AND siteid = '.$this->site['id'];
		if($this->input['fid'])
		{
			$conditions .= ' AND fatherid = '.intval($this->input['fid']);
		}
		else
		{
			$conditions .= ' AND fatherid = 0';
		}
		return $conditions;
	}
	private function get_selected_col($ids)
	{
		if($ids && is_array($ids))
		{
		}
	}
	function create()
	{
		if($this->input['debug'])
		{
			echo "<pre>";
			//print_r($this->input);
			echo "<pre>";
		}
		//取出模块字典和CMS数据模型对应关系
		$fieldmap = array();
		$fieldmap = $this->db->query_first('SELECT * FROM '.DB_PREFIX.'publish_fieldmap WHERE moduleid = 20 AND medium_type = 1');
		if($fieldmap)
		{
			$fieldmap = unserialize($fieldmap['map_field']);
		}
		print_r($fieldmap);
		exit;
		//发布数据入库
		if(!class_exists('publish'))
		{
			include(ROOT_DIR.'lib/class/publish.class.php');
		}
		$publish = new publish();
		$siteid = $this->input['siteid'];
		if($publish->update($siteid, 1, 1, (array)$this->input['columnid']))
		{
			echo 1;
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>