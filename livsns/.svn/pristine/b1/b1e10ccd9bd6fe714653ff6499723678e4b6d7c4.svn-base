<?php
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/live.class.php';
define('MOD_UNIQUEID', 'live');  //模块标识
class liveapi extends appCommonFrm
{
	private $live;

	public function __construct()
	{
		parent::__construct();
		$this->live = new live();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$data = array(
		'name'=>'云直播测试',
		'code'=>'cloudlive9',
		'is_push'=>1,
		'stream_name'=>array('sd'),
		'url'=>array('rtmp://10.0.1.20/live/test2015_sd'),
		'bitrate'=>array(600),
		'is_default'=>0,
		'is_audio'=>0,
		'is_control'=>0,
		'time_shift'=>1,
		);
		$responce = $this->live->create($data);
		
		if(is_array($responce) && !empty($responce[0]))
		{
			$this->addItem($responce[0]);
			$this->output();
		}
		$this->errorOutput('服务器错误，请稍后重试');
	}
	public function update()
	{
		$data = array(
		'id'=>245,
		'name'=>'云直播测试3',
		'code'=>'cloudlive9',
		'is_push'=>1,
		'stream_name'=>array('sd'),
		'url'=>array('rtmp://10.0.1.20/live/test2015_sd'),
		'bitrate'=>array(600),
		'is_default'=>0,
		'is_audio'=>0,
		'is_control'=>0,
		'time_shift'=>1,
		);
		if(!$data['id'])
		{
			$this->errorOutput("直播信息不完整");
		}
		$responce = $this->live->update($data);
		if(is_array($responce) && !empty($responce[0]))
		{
			$this->addItem($responce[0]);
			$this->output();
		}
		$this->errorOutput('服务器错误，请稍后重试');
	}
	public function delete()
	{
		$data = array(
		'id',
		);
	}
	public function show()
	{
		$conditions = array();
		if(isset($this->input['k']))
		{
			$conditions['k'] = $this->input['k'];
		}
		if(isset($this->input['status']))
		{
			$conditions['status'] = $this->input['status'];
		}
		if(isset($this->input['offset']))
		{
			$conditions['offset'] = $this->input['offset'];
		}
		if(isset($this->input['count']))
		{
			$conditions['count'] = $this->input['count'];
		}
		$responce = $this->live->channel_list($conditions);
		
		if(is_array($responce) && !empty($responce))
		{
			foreach($responce as $ch)
			{
				$this->addItem($ch);
			}
		}
		$this->output();
	}
	public function count()
	{
		$conditions = array();
		if(isset($this->input['k']))
		{
			$conditions['k'] = $this->input['k'];
		}
		if(isset($this->input['status']))
		{
			$conditions['status'] = $this->input['status'];
		}
		$total = $this->live->count($conditions);
		$this->addItem($total);
		$this->output();
	}
}

$out = new liveapi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>