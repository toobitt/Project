<?php 
class SelectTimeShiftServer
{
	private $server;
	public function __construct()
	{
		if(!class_exists('server'))
		{
			include_once(CUR_CONF_PATH . 'lib/server.class.php');
			$this->server = new server();
		}
	}
	
	//选择时移服务器
	public function select()
	{
		$serverInfo = $this->server->show();
		if(!$serverInfo)
		{
			return false;
		}
		
		$serverWithOk = array();
		foreach($serverInfo AS $k => $v)
		{
			if($v['is_open'] && $v['isSuccess'])
			{
				$serverWithOk = $v;
				break;
			}
		}
		return  $serverWithOk;
	}
}
?>