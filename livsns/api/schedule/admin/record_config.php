<?php
/***************************************************************************
* $Id: record_config.php 21634 2013-05-07 02:43:19Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','record_config');
require('global.php');
class recordConfigApi extends adminReadBase
{
	private $mRecordConfig;
	private $mLivemms;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/record_config.class.php';
		$this->mRecordConfig = new recordConfig();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
		
	}
	
	public function show()
	{
		$condition  = $this->get_condition();
		$offset		= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count		= $this->input['count'] ? intval($this->input['count']) : 20;
		
		$info = $this->mRecordConfig->show($condition, $offset, $count);
		if (!empty($info))
		{
			foreach ($info AS $k => $v)
			{
				//检测服务器是否通路
		        $ret_check_server = $this->mRecordConfig->check_server($v['record_host'] . ':' . $v['record_port']);
				$v['is_success'] = $ret_check_server ? 1 : 0;
				$this->addItem($v);
			}
		}
		$this->output();
	}

	public function detail()
	{
		$id = trim($this->input['id']);
		$ret = $this->mRecordConfig->detail($id);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 取源视频地址目录
	 * Enter description here ...
	 */
	public function set_timeshift_file_path()
	{
		$config = $this->mRecordConfig->get_mediaserver_config();
		$return = $config['default_timeshift_file_path'];
		$this->addItem($return);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mRecordConfig->count($condition);
		echo json_encode($info);
	}
	
	public function show_opration()
	{
		$id = trim($this->input['id']);
		$ret = $this->mRecordConfig->detail($id);
		$this->addItem($ret);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= " AND name LIKE \"%" . trim(urldecode($this->input['k'])) . "%\"";
		}
		
		return $condition;
	}
}

$out = new recordConfigApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>