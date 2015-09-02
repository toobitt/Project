<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: advert_update.php 8427 2012-07-27 03:12:02Z hanwenbin $
***************************************************************************/
require_once './global.php';
define('MOD_UNIQUEID','advert_m');//模块标识
class advertUpdateApi extends outerUpdateBase
{
	private $mVideo;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 创建广告
	* @param $mark 广告标识
	* @param $name 广告名称
	* @param $content 广告代码
	* @return $info 广告信息
	*/
	function create(){
		
		$info = array(
			'mark' => $this->input['mark']?urldecode( $this->input['mark']):"",
			'name' => $this->input['name']?urldecode( $this->input['name']):"",
			'content' => $_REQUEST['content']?htmlspecialchars_decode(urldecode($_REQUEST['content'])) : "",
			'adver_id' => 0,
			'create_time' => time(),
		);
		
		if(!$info['mark'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "INSERT INTO ".DB_PREFIX."advertising(mark,name,content,create_time) 
		VALUES(
			'".$info['mark']."',
			'".$info['name']."',
			'". $info['content'] ."',
			".$info['create_time']."
			)";
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'create', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		$info['adver_id'] = $this->db->insert_id();
		$this->create_record();
		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 更新广告
	* @param $adver_id 广告ID
	* @param $mark 广告标识
	* @param $name 广告名称
	* @param $content 广告代码
	* @return $info 广告信息
	*/
	function update(){

		$id = $this->input['id']?$this->input['id']:0;
		if(!$id)
		{
			$this->errorOutput("未传入ID");
		}
		$info = array(
			//'mark' => $this->input['mark']?urldecode( $this->input['mark']):"",
			'name' => $this->input['name']?urldecode( $this->input['name']):"",
			'content' => $_REQUEST['content']?htmlspecialchars_decode(urldecode($_REQUEST['content'])):"",
		);
		if($info['mark'])
		{
			
		}
		
		$sql = "UPDATE ".DB_PREFIX."advertising SET";
		$extra = $space = '';
		foreach($info as $key => $value)
		{
			$extra .= $space ." " . $key . "='" . $value . "'";
			$space = ',';
		}
		$sql = $sql . $extra . " WHERE id = " . $id;
		
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		$this->create_record();
		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 删除广告
	* @param $adver_id 广告id
	* @return $info 广告信息
	*/
	function delete(){
		
		$info = array(
			'id' => $this->input['id']?$this->input['id']:"",
		);
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "advertising WHERE id = ".$info['id'];
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['advertising'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = "DELETE FROM ".DB_PREFIX."advertising WHERE id = ".$info['id'];
			$this->db->query($sql);	
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
			$dir = ROOT_PATH."api\/cache\/";
			$handle = opendir($dir); 
			while($file = readdir($handle))  
			{ 
				if($file !='.'&&$file !='..')
				{
					unlink($dir.$file);
				}
			} 
			closedir($handle); 
			
			$this->create_record();
			$this->setXmlNode('user','info');
			$this->addItem($info);
		}
		else 
		{
			$this->errorOutput('删除失败！');
		}
		
		$this->output();
	}	
	//还原
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		//还原广告记录表
		if(!empty($content['advert']))
		{
			$sql = "insert into " . DB_PREFIX . "advertising set ";
			$space='';
			foreach($content['advert'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'recover', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
		}
		return $data;
	}*/	
	public function audit()
	{
		$id = trim($this->input['id']);
		if(!$id)
		{
			return false;
		}
		$state = ($this->input['state'] ? 0 : 1);
		$sql = "UPDATE " . DB_PREFIX . "advertising SET state=" . $state . " WHERE id=" . $id;
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'audit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$this->setXmlNode('user','info');
		$this->addItem(array('id' => $id,'state' => $state));
		$this->output();
	}
	
	function create_record()
	{
		$mark = $this->input['mark']? urldecode($this->input['mark']):0;
		if($mark)//当$mark存在的时候 默认生成单一的广告文件
		{
			$sql = "SELECT * FROM  ".DB_PREFIX."advertising WHERE mark ='".$mark."' ORDER BY create_time DESC";
			$f = $this->db->query_first($sql);
			if($f && is_array($f))
			{
				$htmls = '<?php $advert = array(';
				$child = '';
				$c_s = '';
				$filename = '';
				foreach($f as $k => $v)
				{
					$child .= $c_s."'".$k."'=>'".$v."'";
					$c_s = ',';
					if($k == 'mark')
					{
						$filename = $v;
					}
				}
				$htmls .= $child.');'.' ?>';
				if(!is_file(ROOT_PATH . "cache/" . $filename . ".php"))
				{
					file_put_contents(ROOT_PATH . "cache/".$filename.".php", $htmls);
				}
				else
				{
					@unlink(ROOT_PATH . "cache/" . $filename . ".php");
				}
			}
			else 
			{
				$htmls = '<?php $advert = array(); ?>';
				if(!is_file(ROOT_PATH."cache/".$mark.".php"))
				{
					file_put_contents(ROOT_PATH."cache/" . $mark . ".php", $htmls);
				}
				else
				{
					@unlink(ROOT_PATH . "cache/" . $mark . ".php");
				}
			}
		}
		else 
		{
			$sql = "SELECT * FROM  ".DB_PREFIX."advertising ";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$htmls = '<?php $advert = array(';
				$child = '';
				$c_s = '';
				$filename = '';
				foreach($row as $k => $v)
				{
					$child .= $c_s."'".$k."'=>'".$v."'";
					$c_s = ',';
					if($k == 'mark')
					{
						$filename = $v;
					}
				}
				$htmls .= $child.');'.' ?>';
				if(!is_file(ROOT_PATH."cache/".$filename.".php"))
				{
					file_put_contents(ROOT_PATH."cache/" . $filename . ".php", $htmls);
				}
				else
				{
					@unlink(ROOT_PATH . "cache/" . $filename . ".php");
				}
			}
		}
	}
	
	function get(){
		$mark = $this->input['mark']? urldecode($this->input['mark']):0;
		if(!is_file(ROOT_PATH."cache/".$mark.".php"))
		{
			$this->create_record();
			include(ROOT_PATH."cache/".$mark.".php");
		}
		else 
		{
			include(ROOT_PATH."cache/".$mark.".php");
			if(!$advert['content'] || !is_array($advert))
			{
				unlink(ROOT_PATH."cache/".$mark.".php");
				$this->create_record();
				include(ROOT_PATH."cache/".$mark.".php");
			}
		}
		$this->setXmlNode('advert','info');
		$this->addItem($advert);
		$this->output();
	}
	
	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
	
}

$out = new advertUpdateApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>