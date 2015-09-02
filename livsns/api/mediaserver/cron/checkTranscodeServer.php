<?php
require('global.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
define('MOD_UNIQUEID','checkTranscodeServer');
set_time_limit(0);
class checkTranscodeServer extends cronBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '实时检测转码服务器',
            'brief' => '实时检测转码服务器',
            'space' => '30',//运行时间间隔，单位秒
            'is_use' => 0,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
	
    public function run()
    {
		$sql = "SELECT id,trans_host,trans_port FROM ".DB_PREFIX."transcode_center ORDER BY order_id  DESC LIMIT 0,10";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$server_info = array('host' => $row['trans_host'],'port' => $row['trans_port']);
			$transcode_tasks = $this->get_transcode_tasks($server_info);
			$transcode_status = $this->get_transcode_task_info($server_info);
			$transcode_version = $this->get_transcode_version($server_info);
			$transcode_config = $this->get_transcode_config($server_info);
			$sql = '';
			$sql = "UPDATE " .DB_PREFIX. "transcode_center SET 
				transcode_tasks = '" .json_encode((array)$transcode_tasks). "',
				transcode_status = '" .json_encode((array)$transcode_status). "',
				transcode_version = '" .json_encode((array)$transcode_version). "',
				transcode_config = '" .json_encode((array)$transcode_config). "',
				update_time = " .TIMENOW. "   
				WHERE id = " .$row['id'];
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();	
	}
	//获取当前正在转码的个数
	public function get_transcode_tasks($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_tasks();
		$ret = json_decode($ret,1);
		return $ret;
	}
	//获取某台转码服务器所有正在转码的任务信息
	public function get_transcode_task_info($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_status();
		$ret = json_decode($ret,1);
		return $ret;
	}
	//获取当前转码服务版本号
	public function get_transcode_version($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_version();
		$ret = json_decode($ret,1);
		return $ret;
	}
	//获取转码服务器配置
	private function get_transcode_config($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_config();
		$ret = json_decode($ret,1);
		return $ret;
	}
}

$out = new checkTranscodeServer();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'run';
}
$out->$action();

?>