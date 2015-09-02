<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record_server');//模块标识
class serverApi extends adminReadBase
{
	private $obj;
	function __construct()
	{
		$this->mModPrmsMethods = array(
			'update_state'=>array('name'=>'状态'),
		);
		unset($this->mPrmsMethods['audit'],$this->mPrmsMethods['sort']);		
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/server.class.php');
		$this->obj = new server();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		#####
		$this->verify_content_prms();
		#####
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$ret = $this->obj->show($condition,$data_limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
	
	/**
	 * Enter description here ...
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		#####
		$this->verify_content_prms();
		#####
		$id = intval($this->input['id'] ? $this->input['id'] : 0);
		$condition = '';
		if($id)
		{
			$condition = " AND id=" . $id;
		}
		$ret = $this->obj->detail($condition);
		if(!empty($ret))
		{
			$config = $this->get_mediaserver_config();
			$edit_data = array(
				'action' 						=> 'GET_CONFIG',
				'default_record_file_path' 		=> $config['default_record_file_path'],
			);
			$ret['config'] = $this->mediaServerOperate($ret['host'] . ":" . $ret['port'], $ret['dir'], $edit_data);
			
			$this->addItem($ret);
			$this->output();
		}
	}
	
	private function get_mediaserver_config()
	{
		//获取需要修改的配置
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$settings = $curl->request('configuare.php');
		
		$config = array(
			'default_record_file_path' 		=> !empty($settings) ? $settings['define']['UPLOAD_DIR'] : '',
		);
		return $config;
	}
	
	private function mediaServerOperate($host, $dir, $data = array())
	{
		$this->curl = new curl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setUrlHost($host, $dir);
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->setReturnFormat('json');
		$action = array('MODIFY_CONFIG', 'GET_CONFIG');
		if (!in_array($data['action'], $action))
		{
			return array();
		}
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('');
		return xml2Array($ret);
	}
	
	function getServerSource()
	{
		$condition = ' AND state=1 ';
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$ret = $this->obj->show($condition,$data_limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$tmp = array('id' => $v['id'],'name' => $v['name']);
				$this->addItem($tmp);
			}
			$this->output();
		}
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
}

$out = new serverApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>