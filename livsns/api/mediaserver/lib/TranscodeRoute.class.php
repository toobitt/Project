<?php
/*
 * 转码服务器路由类
 * 功能（根据各个转码服务器的状态，动态地选择用于提交哪台转码服务器）
 * */
class TranscodeRoute
{
	public function __construct()
	{
		if(!class_exists('transcode'))
		{
			include_once(CUR_CONF_PATH . 'lib/transcode.class.php');
		}
	}
	
	public function route($server_ids = '')
	{
		//获取所有开启的转码服务器信息
		$servers = hg_get_transcode_servers($server_ids);
		$min = 0;//临时存储最小的个数
		$flag = true;//判断是不是第一次检测
		$id_k = 0;//记录下选取的转码服务器的id
		$ret = array();
		if($servers)
		{
			foreach($servers AS $k => $v)
			{
				$trans = new transcode(array('host' => $v['trans_host'],'port' => $v['trans_port']));
				//$task = json_decode($trans->get_transcode_tasks(),1);
				$task = json_decode($v['transcode_tasks'],1);
				if($task['return'] != 'fail')
				{
					$all_task_num = intval($task['transcoding_tasks']) + intval($task['waiting_tasks']);
					if($all_task_num < $min || $flag)
					{
						$min = $all_task_num;
						$id_k = $k;
					}
					$flag = false;
				}
			}
			
			if($id_k)
			{
				$ret = array(
					'protocol' 	=> 'http://',
					'host' 		=> $servers[$id_k]['trans_host'],
					'port' 		=> $servers[$id_k]['trans_port'],
					'need_file' => $servers[$id_k]['is_carry_file'],
				);
			}
		}
		
		if(empty($ret))
		{
			return array('return' => 'fail');
		}
		return $ret;
	}
}
?>