<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: advert.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class advertApi extends adminBase
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
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	
	/**
	 * 查询广告信息
	 * @return $info 广告信息
	 */
	function show(){
		$mInfo = $this->mUser->verify_credentials();
		
		$count = $this->input['count']? $this->input['count']:0;
		$page = $this->input['page']? $this->input['page']:0;
		if($count)
		{
			$offset = $count*$page;
			$end = " LIMIT $offset,$count";
		}
		
		$sql = "SELECT * FROM  ".DB_PREFIX."advertising ".$end;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$advert[] = $row;
		}
		$sql = "SELECT count(*) as total FROM  ".DB_PREFIX."advertising";
		$q = $this->db->query_first($sql);
		if($count)
		{
			$advert['total'] = $q['total'];
		}
		$this->create_record();
		$this->setXmlNode('user','info');
		$this->addItem($advert);
		$this->output();
	}

	
	/**
	* 创建广告
	* @param $mark 广告标识
	* @param $name 广告名称
	* @param $content 广告代码
	* @return $info 广告信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$info = array(
			'mark' => $this->input['mark']?urldecode( $this->input['mark']):"",
			'name' => $this->input['name']?urldecode( $this->input['name']):"",
			'content' => $_REQUEST['content']?urldecode( $_REQUEST['content']):"",
			'adver_id' => 0,
			'create_time' => time(),
		);
		
		if(!$info['mark'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "INSERT INTO ".DB_PREFIX."advertising(mark,name,content,create_time,state) 
		VALUES(
			'".$info['mark']."',
			'".$info['name']."',
			'".htmlspecialchars_decode($info['content'])."',
			".$info['create_time'].",1
			)";
		$this->db->query($sql);
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
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$info = array(
			'mark' => $this->input['mark']?urldecode( $this->input['mark']):"",
			'name' => $this->input['name']?urldecode( $this->input['name']):"",
			'content' => $_REQUEST['content']?htmlspecialchars_decode(urldecode( $_REQUEST['content'])):"",
			'adver_id' => $this->input['adver_id']?$this->input['adver_id']:"",
			'create_time' => time(),
		);
		
		$sql = "UPDATE ".DB_PREFIX."advertising SET
			mark='".$info['mark']."',
			name='".$info['name']."',
			content='".$info['content']."'
			WHERE id = ".$info['adver_id'];
		$this->db->query($sql);
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
	function del(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$info = array(
			'adver_id' => $this->input['adver_id']?$this->input['adver_id']:"",
			'id' => $this->input['id']?$this->input['id']:"",
		);
		$sql = "DELETE FROM ".DB_PREFIX."advertising WHERE id = ".$info['adver_id'];
		$this->db->query($sql);	
		$dir = ROOT_PATH."api\/cache\/";
		$handle=opendir($dir); 
		while($file=readdir($handle))  
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
		$this->output();
	}	
	
	function create_record()
	{
		$mark = $this->input['mark']? urldecode($this->input['mark']):0;
		if($mark)//当$mark存在的时候 默认生成单一的广告文件
		{
			$sql = "SELECT * FROM  ".DB_PREFIX."advertising WHERE state=1 AND mark ='".$mark."' ORDER BY create_time DESC";
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
				if(!is_file(ROOT_PATH."cache/" . $filename . ".php"))
				{
					file_put_contents(ROOT_PATH."cache/" . $filename . ".php", $htmls);
				}
				else
				{
					@unlink(ROOT_PATH . "cache/" . $filename . ".php");
				}
			}
			else 
			{
				$htmls = '<?php $advert = array(); ?>';
				if(!is_file(ROOT_PATH."cache/" . $mark . ".php"))
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
			$sql = "SELECT * FROM  ".DB_PREFIX."advertising WHERE state=1 ";
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
}

$out = new advertApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>