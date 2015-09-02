<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record_server');
class serverUpdateApi extends adminUpdateBase
{
	private $obj;
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/server.class.php');
		$this->obj = new server();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$info = array(
			'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
			'mark' => trim($this->input['mark']) ? trim($this->input['mark']) : '',
			'protocol' => trim($this->input['protocol']) ? trim($this->input['protocol']) : 'http://',
			'host' => trim($this->input['host']) ? trim($this->input['host']) : '',
			'port' => intval($this->input['port']) ? intval($this->input['port']) : 0,	
			'dir' => trim($this->input['dir']) ? trim($this->input['dir']) : '/control/recordserver/task',
			'state' => intval($this->input['state']) ? intval($this->input['state']) : 0,	
			'create_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		
		if(empty($info['name']))
		{
			$this->errorOutput('请传入服务器名');
		}
		
		if(empty($info['host']))
		{
			$this->errorOutput('请传入服务器地址');
		}
		$nodes = array('nodes'=>array('program_record_node'=>array()));
		$this->verify_content_prms($nodes);
		
		$record_server = array(
			'protocol'	=> $info['protocol'],
			'host' => $info['host'],
			'dir' => $info['dir'],
			'port' => $info['port']
		);
		$check_server = $this->checkServer($info['host'] . ':' . $info['port'] . $info['dir']);
		if($check_server)
		{
			$ret_mediaserver = $this->record_server_edit($record_server);
			if (!$ret_mediaserver['result'])
			{
				$this->errorOutput('修改录制服务器路径失败');
			}
			$ret = $this->obj->create($info);
			if(empty($ret))
			{
				$this->errorOutput('更新有误！');
			}
			$this->sys_data($ret['id'],$info['state']);//防止老数据没制定录制服务器
			$this->addLogs('新增录制服务配置','',$ret,'','',$ret['name']);
			$this->addItem($ret);
			$this->output();
		}				
	}
	
	public function update()
	{
		$id = intval($this->input['id'] ? $this->input['id'] : 0);
		$nodes = array('nodes'=>array('program_record_node'=>array()));
		$this->verify_content_prms($nodes);

		$info = array(
			'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
			'mark' => trim($this->input['mark']) ? trim($this->input['mark']) : '',
			'protocol' => trim($this->input['protocol']) ? trim($this->input['protocol']) : 'http://',
			'host' => trim($this->input['host']) ? trim($this->input['host']) : '',
			'port' => intval($this->input['port']) ? intval($this->input['port']) : 0,	
			'dir' => trim($this->input['dir']) ? trim($this->input['dir']) : '/control/recordserver/task',
			'state' => intval($this->input['state']) ? intval($this->input['state']) : 0,
		);
		if(empty($id))
		{
			$this->errorOutput('请传入更新的服务器ID');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		
		if(empty($f))
		{
			$this->errorOutput('此服务配置已不存在');
		}
		if(empty($info['name']))
		{
			$this->errorOutput('请传入服务器名');
		}
		
		if(empty($info['host']))
		{
			$this->errorOutput('请传入服务器地址');
		}
		
		$record_server = array(
			'protocol'	=> $info['protocol'],
			'host' => $info['host'],
			'dir' => $info['dir'],
			'port' => $info['port']
		);
		$check_server = $this->checkServer($info['host'] . ':' . $info['port']);
		if($check_server)
		{
			$ret_mediaserver = $this->record_server_edit($record_server);
			if (!$ret_mediaserver['result'])
			{
				$this->errorOutput('服务器配置有误或者已停止服务');
			}
			$ret = $this->obj->update($id,$info);
			$this->sys_data($id,$info['state']);//防止老数据没制定录制服务器
		}
		if(empty($ret))
		{
			$this->errorOutput('更新有误！');
		}
		$this->addLogs('更新录制服务配置',$f,$ret,'','',$ret['name']);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update_state()
	{
		$id = trim($this->input['id'] ? $this->input['id'] : '');
		$nodes = array('nodes'=>array('program_record_node'=>array()));
		$this->verify_content_prms($nodes);
		$state = intval($this->input['state']) ? intval($this->input['state']) : 0;
		if(empty($id))
		{
			$this->errorOutput('请传入更新的服务器ID');
		}
		$ret = $this->obj->update_state($id,$state);
		if(empty($ret))
		{
			$this->errorOutput('更新有误！');
		}
		$this->sys_data($id,$state);
		$this->addItem($ret);
		$this->output();
	}
	
	private function sys_data($server_id = 0 , $state = 0)
	{
		$open_server = $close_server = 0;
		if($state)//开启状态
		{
			$open_server = $server_id;
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "server_config WHERE state=0";//检索原来关闭的内容
			$f = $this->db->query_first($sql);
			if($f && $f['total'])//如果有，把关闭的内容移到开启的地方，也就是现在传递过来的服务器上
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE state=0";
				$q = $this->db->query($sql);
				$close_server = $space = '';
				while($row = $this->db->fetch_array($q))
				{
					$close_server .= $space . $row['id'];
					$space = ',';
				}
			}
		}
		else//关闭状态
		{
			$close_server = $server_id;
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "server_config WHERE state=1";
			$f = $this->db->query_first($sql);
			if($f && $f['total'])//
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE state=1";
				$sen = $this->db->query_first($sql);
				$open_server = $sen['id'] ? $sen['id'] : 0;
			}
		}
		if($close_server && $open_server)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE server_id IN(" . $close_server .") or server_id=0";//检索已经处于关闭状态的录制
			$q = $this->db->query($sql);
			$record_id = $space = '';
			while($row = $this->db->fetch_array($q))
			{
				if($row['start_time'] >= (TIMENOW+5))// 非开始的收录，需要删除目前的所在服务上的任务，计划任务重新提交
				{//增加5秒是为了冗余计划任务执行时间
					$this->delete_queue($row['conid']);
				}
				$record_id .= $space . $row['id'];
				$space = ',';
			}
			if($record_id)//如果存在，就必须更新录制为新开启服务的任务
			{
				$sql = "update " . DB_PREFIX . "program_record set server_id=" . $open_server . " WHERE id IN(" . $record_id .")";
				$this->db->query($sql);
				//并且要把还未开始的录制重新提交
				$sql =  "UPDATE " . DB_PREFIX . "program_record SET conid=0,is_record=0 WHERE id IN(" . $record_id . ") AND start_time > " . (TIMENOW+5);//小于当前时间，重置录制中，和未开始收录的任务，录制中的会超时，未开始的无任何影响
				$q = $this->db->query($sql);//假如存在过时的任务，只要清空它的服务信息，计划任务自动重新提交
			}
		}
	}
	
	private function delete_queue($conid)
	{
		$sql = "select * from " . DB_PREFIX . "program_queue where id=" . $conid;
		$f = $this->db->query_first($sql);
		if(!empty($f))
		{
			$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id=" . $f['log_id'];
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['id'];
			$this->db->query($sql);
		}	
	}
	
	public function delete()
	{
		$id = intval($this->input['id'] ? $this->input['id'] : 0);
		if(empty($id))
		{
			$this->errorOutput('请传入更新的服务器ID');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$this->errorOutput('此服务配置已不存在');
		}
		$data = array(
			'run' => 0,
			'id' => $id,
			'is_last' => 0
		);
		if(!intval($this->input['enforce']))
		{
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "server_config WHERE state=1";
			$sen = $this->db->query_first($sql);
			if($sen['total'] <= 1)
			{
				$data['is_last'] = 1;
				$this->addItem($data);
				$this->output();
			}
	
			$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE is_record=1 AND conid<>0 AND server_id =" . $id ;
			$q = $this->db->query($sql);
			$i = 0;
			while($row = $this->db->fetch_array($q))
			{
				$i++;
			}
			if($i)
			{
				$this->addItem(array('run' => $i,'id' => $id));
				$this->output();
			}
			$ret = $this->obj->delete($id);
			$this->addLogs('删除录制服务配置',$f,'','','',$f['name'].$id);
			$this->addItem(array('id' => $ret));
			$this->output();
		}
		else
		{
			$ret = $this->obj->delete($id);
			if($ret)//删除成功
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE state=1";
				$sen = $this->db->query_first($sql);
				if($sen['id'])
				{
					$sql = "UPDATE " . DB_PREFIX . "program_record SET server_id=" . $sen['id'] . " WHERE 1 AND server_id=" . $id;
					$this->db->query($sql);
				}
				else//说明可以把当前这个任务删除之后没其他任务了，所以要把所有录制中的server_id置空
				{
					$sql = "UPDATE " . DB_PREFIX . "program_record SET server_id=0 WHERE 1";
					$this->db->query($sql);
				}
				$this->addLogs('删除录制服务配置',$f,'','','',$f['name'].$id);
				$this->addItem($data);
				$this->output();
			}
			else
			{
				$this->errorOutput('删除失败！');
			}
		}		
	}
	
	public function audit()
	{
		
	}
	
	public function sort()
	{
	
	}
	
	public function publish()
	{
		
	}
	
	private function checkServer($url)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if ($head_info['http_code'] != 200)
		{
			return false;
		}
		return true;
	}
	
	private function record_server_edit($record_server)
	{
		if (empty($record_server))
		{
			return false;
		}
		
		$config = $this->get_mediaserver_config();
		
		if (empty($config))
		{
			return false;
		}
		
		$host = $record_server['host'] . ':' . $record_server['port'];
		$dir  = $record_server['dir'];
		
		if($record_server['host'])
		{
			//获取文件配置
			$get_data = array(
				'action'	=> 'GET_CONFIG',
			);
			
			//修改文件路径
			$edit_data = array(
				'action' 						=> 'MODIFY_CONFIG',
				'default_record_file_path' 		=> $config['default_record_file_path'],
				'default_timeshift_file_path' 	=> $config['default_timeshift_file_path'],
			);
			
			$ret_config = $this->mediaServerOperate($host, $dir, $get_data);
			$ret_config = isset($ret_config['response']) ? $ret_config['response'] : $ret_config;
			if ($ret_config['default_record_file_path'] == $config['default_record_file_path'])
			{
				$ret = array(
					'result' => '1',
				);
			}
			else
			{
				$ret = $this->mediaServerOperate($host, $dir, $edit_data);
			}
			
			$ret['default_record_file_path'] 		= $config['default_record_file_path'];
    		$ret['default_timeshift_file_path'] 	= $config['default_timeshift_file_path'];
    		
			return $ret;
		}
		return false;
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
		//TARGET_DIR
		//'default_timeshift_file_path' 	=> $settings['define']['UPLOAD_DIR'],
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
		if($ret)
		{
			$ret = '<node>'.$ret.'</node>'; //防止xml2Array报错
		}
		return xml2Array($ret);
	}
}

$out = new serverUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>