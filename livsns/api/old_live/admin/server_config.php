<?php
/***************************************************************************
* $Id: server_config.php 17632 2013-02-23 08:53:47Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','server_config');
require('global.php');
class serverConfigApi extends adminReadBase
{
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$condition  = $this->get_condition();
		$offset		= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count		= $this->input['count'] ? intval($this->input['count']) : 20;
		
		$info = $this->mServerConfig->show($condition, $offset, $count);
		if (!empty($info))
		{
			$server_id = @array_keys($info);
			$output_server = $this->mServerConfig->output_show(implode(',', $server_id));
			foreach ($info AS $k => $v)
			{
				$v['output'] = $output_server[$k];
				$this->addItem($v);
			}
		}
		$this->output();
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		$ret = $this->mServerConfig->detail($id);
		$this->addItem($ret);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mServerConfig->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 检测服务器剩余流信号条数
	 * Enter description here ...
	 */
	public function checked_server_stream_count()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入服务器id');
		}
		$server_info = $this->mServerConfig->get_server_by_id($id);
		
		if (empty($server_info))
		{
			$this->errorOutput('该服务器信息不存在或已被删除');
		}
		
		$stream_info = $this->mServerConfig->get_stream_by_server_id($id, 'stream_count');
		
		$stream_count = 0;
		if (!empty($stream_info))
		{
			foreach ($stream_info AS $v)
			{
				$stream_count = $v['stream_count'] + $stream_count;
			}
		}
		
		if ($stream_count > $server_info['counts'])
		{
			$this->errorOutput('该服务器已经无法再添加信号，请选择其他服务器');
		}
		//剩余数目
		$ret_count = $server_info['counts'] - $stream_count;
		
		$this->addItem($ret_count);
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
	
	public function index()
	{
		
	}

}

$out = new serverConfigApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>