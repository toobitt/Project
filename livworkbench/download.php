<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: download.php 9169 2015-06-10 06:31:24Z repheal $
***************************************************************************/
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'download');
require('./global.php');
class download extends uiBaseFrm
{
	function __construct()
	{
		parent::__construct();		
	}
	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		
	}
	public function video()
	{
		$id = intval($this->input['id']);
		$f = $this->input['f'];
		$api = stripslashes(urldecode($_REQUEST['api']));
		$api = json_decode($api, true);
		
		if (!$id)
		{
			$this->ReportError('请指定要下载的视频!');
		}
		if (!$api || !$f)
		{
			$this->ReportError('未指定下载接口!');
		}
		$url = 'http://' . $api['host'] . '/' . $api['dir'] . $f . '?access_token=' . $this->user['token'] . '&id=' . $id;

		$filename =  time() . mt_rand(1, 99999);
		hg_mkdir(ROOT_PATH . 'cache/tmp/');
		$tempfile = ROOT_PATH . 'cache/tmp/' . $filename . '.mp4';
		$count = 0;
		while(is_file($tempfile) && $count < 50)
		{
			$filename =  time() . mt_rand(1, 99999);
			$tempfile = ROOT_PATH . 'cache/tmp/' . $filename . '.mp4';
		}
		$cmd = 'curl "' . $url . '" > ' . $tempfile;
		//$filesize = strlen($content);
		exec($cmd);
		$filesize = filesize($tempfile);
		if ($this->input['title'])
		{
			$filename = iconv('UTF-8', 'GBK', $this->input['title']);
		}
		header("Content-Type: application/force-download");
		header("Content-Transfer-Encoding: binary\n");
		header('Content-Length: ' . $filesize);
		header("Content-Disposition: attachment; filename=".$filename . '.mp4');
		readfile($tempfile);
		@unlink($tempfile);
	}

	public function example()
	{
		ob_clean();
		$str = date("Y-m-d",TIMENOW) . '
00:30:00,广告
00:33:00,四集电视剧连播
05:30:00,广告
05:40:00,牛视影院（上）
06:25:00,广告
06:30:00,牛视影院（下）
07:20:00,广告
07:25:00,无锡一家人
08:05:00,直播hoge,城市特快
08:20:00,直播hoge
08:45:00,广告
09:20:00,广告

'.date("Y-m-d",TIMENOW+24*3600).'
00:30:00,广告
00:33:00,四集电视剧连播
05:30:00,广告
05:40:00,牛视影院（上）
06:25:00,广告
06:30:00,牛视影院（下）
07:20:00,广告
07:25:00,hoge一家人
08:05:00,直播hoge,城市特快
08:20:00,直播hoge,特攻005
08:45:00,广告
09:20:00,广告';
		$file_dir = CACHE_DIR;
		$source = 'new.txt';
		$filename = date("YmdHis",TIMENOW) . '.txt';
		hg_file_write($file_dir . $source,$str);
		if (!file_exists($file_dir . $source)) 
		{ 
			echo '文件不存在！';
			exit;
		}
		else
		{
			$file = fopen($file_dir . $source,"r"); // 打开文件
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".$filename);
			echo fread($file,filesize($file_dir . $source));
			fclose($file);
			@unlink($file_dir . $source);
		}
	}

	/**
	 * 下载节目单 xls 格式
	 * Enter description here ...
	 */
	public function download_xls()
	{
		ob_clean();
		$file_dir = RESOURCE_DIR;
		$filename = 'program.xls';
		$new_name = date('YmdHis') . '.xls';
		if (!file_exists($file_dir . $filename)) 
		{ 
			echo 'program.xls文件不存在！';
			exit;
		}
		else
		{
			$file = fopen($file_dir . $filename, "r"); // 打开文件
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".$new_name);
			echo fread($file,filesize($file_dir . $filename));
			fclose($file);
		}
	}
	
	public function download_feedback()
	{
		$fid = $this->input['fid'];
		$process = $this->input['process'];
		$access = $this->input['access_token'];
		if($this->settings['App_feedback'])
		{
			$url = 'http://'.$this->settings['App_feedback']['host'].'/'.$this->settings['App_feedback']['dir'].'admin/feedback_result.php';
		}
		else
		{
			$url = 'http://10.0.1.40/livsns/api/feedback/admin/feedback_result.php';//本地测试时使用			
			//$this->ReportError('应用未安装');
		}
		$post = http_build_query(array('fid'=>$fid, 'a'=>'download_excel','process'=>$process,'access_token' => $access));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_NOBODY, 0);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		$response = curl_exec($curl);
		if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
		    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		    $header = substr($response, 0, $headerSize);
		    $body = substr($response, $headerSize);
		}
		$error = json_decode($body,1);
		if($error['ErrorCode'])
		{
			$this->ReportError($error['ErrorCode']);
		}
		$headers = explode("\r\n",$header);		
		foreach ($headers as $k=>$v)
		 {
		 	if($v)
		 	{
		 		header($v);
		 	}
		 }
		 echo $body;	
		 exit();
	}
	
	public function banword()
	{
		$file_dir = CACHE_DIR;
		$file_name = 'example.txt';
		$file_path = $file_dir . $file_name;
		$str = '贪官={BANNED}
賽馬會={BANNED}
谱尼测试科技={BANNED}
谁能赢得这辆女性车你说了算={BANNED}
证件集团=无聊之事不足信
证件文凭=无聊之事不足信
论文发表={BANNED}
论文代写={BANNED}
论坛自动发贴机={BANNED}
论坛群发软件={BANNED}
论坛发贴工具={BANNED}
讨伐中宣部={BANNED}
言論自由={BANNED}
解码器=decode器
解决台湾={BANNED}
见证一个假农民形成的可悲历程={BANNED}
西藏独立={BANNED}
裸聊合法={BANNED}
裸体=**
装饰图纸类=**';
		hg_file_write($file_path, $str);
		if (!file_exists($file_path))
		{
			echo '文件不存在';
			exit;
		}
		$download_file = TIMENOW . '.txt';
		header("Content-type: application/octet-stream");
		header("Accept-Ranges: bytes");
		header("Accept-Length: ".filesize($file_path));
		header("Content-Disposition: attachment; filename=" . $download_file);
		readfile($file_path);
		@unlink($file_path);
	}
	
	public function right()
	{
		$object_id = intval($this->input['object_id']) ? intval($this->input['object_id']) : 0;
		if(empty($object_id))
		{
			return false;
		}
		$db = array(
			'host'     => '192.168.60.18',
			'user'     => 'forapp',
			'pass'     => 'Hoge@mysql',
			'database' => 'm2o_mediaserver',
			'charset'  => 'utf8',
			'pconnect' => '0',
		);
		include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
        $g_db = new db();
        $g_db->connect($db['host'], $db['user'], $db['pass'], $db['database'], $db['charset'], $db['pconnect'], $db['dbprefix']);
        $sql = "select * from " . DB_PREFIX . "right_temp where obj_id=" . $object_id;
        $f = $g_db->query_first($sql);
        $right_info = json_decode($f['content'],1);
        hg_pre($right_info);
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>