<?php
/*
 * Created on 2012-12-10
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 define('ROOT_DIR', './');
 define('SCRIPT_NAME', 'viewlog');
 define('WITH_DB', false);
 define('WITHOUT_LOGIN', true);
 session_start();
 require_once('./global.php');
 require_once(ROOT_PATH . 'lib/class/curl.class.php');
 class viewlog extends uiBaseFrm
 {
 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function __destruct()
 	{
 		parent::__destruct();
 	}
 	
 	function show()
 	{
		echo '待开发！';
		exit;
 	}
	public function view()
	{
		$this->verify();
		$this->pheader();
		$this->menu();
		$this->pfooter();
	}

	public function ls()
	{
		$this->verify();
		$this->pheader();
		$this->menu();
		$dir = $this->input['dir'];
		if (!$dir)
		 {
			$dir = '.';
		 }
		$tmp = explode('/', $dir);
		$c = count($tmp) - 1;
		$back = 0;
		$dir = array();
		for($i = $c; $i >= 0; $i--)
		{
			if ($tmp[$i] == '.')
			{
				continue;
			}
			elseif ($tmp[$i] == '..')
			{
				$back++;
			}
			else
			{
				if ($back == 0)
				{
					$dir[] = $tmp[$i];
				}
				else
				{
					$back--;
				}
			}
		}
		krsort($dir);
		$dir = implode('/', $dir);
		$dir = $dir ? $dir : '.';
		$host = $this->input['host'];
		echo '<div style="margin:20px 10px 10px 10px;clear:both;">';
		echo '<form action="?a=ls" method="post">';
		echo '<ul>';
		echo '<li style="float:left;margin-right:10px;">路径：<input type="text" id="dir" size="75" name="dir" value="' . $dir . '" />&nbsp;&nbsp;服务器：<input type="text" size="20" name="host" value="' . $host . '" />&nbsp;&nbsp;';
		echo '<select name="cmd" id="cmd" onchange="hg_show_tar(this.value);">';
		$cmds = array(
			'ls' => 'ls',
			'getfile' => '获取文件',
			'top' => 'top查看',
			'df' => '硬盘空间',
			'ps' => '查看进程',
			'ping' => 'ping',
		);
		if (!array_key_exists($this->input['cmd'], $cmds))
		{
			$this->input['cmd'] = 'ls';
		}
		foreach($cmds AS $k => $v)
		{
			if ($k == $this->input['cmd'] && in_array($k, array('ls', 'top', 'df', 'getfile')))
			{
				$chk = ' selected="selected"';
			}
			else
			{
				$chk = '';
			}
			echo '<option value="' . $k . '"' . $chk . '>' . $v . '</option>';
		}
		echo '</select>&nbsp;&nbsp;';
		echo '<input type="submit" name="s" size="60" value=" 提交 " /></li>';
		echo '<li style="display:none;clear:both;padding-top:10px;" id="tardirli"> 目标路径：<input type="text" id="tardir" size="75" name="tardir" value="" /></li>';
		echo '<li style="clear:both;padding-top:10px;"></li>';
		echo '</ul>';
		echo '</div>';
		echo '<div style="margin:10px 10px 10px 10px;border:1px solid #ccc;width:700px;clear:both;padding:8px;min-height:200px;">';
		if($host)
		{
			$cmd = $this->input['cmd'];
			$content = trim($_REQUEST['content']);
			$tardir = trim($this->input['tardir']);
			if ($cmd == 'download' && !$tardir)
			{
				$cmd = 'ls';
			}
			if ($cmd == 'write2file' && !$content)
			{
				$cmd = 'ls';
			}
			if ($cmd == 'ln')
			{
				$target = $dir;
				$linkname = $tardir;
			}
			$this->doservcmd($host, $cmd, $dir, $tardir, $content, $target, $linkname);
		}
		elseif (is_dir($dir))
		 {
			$this->dols($dir);
		 }
		 else
		{
			$this->dofile($dir);
		}
		echo '</div>';
		$this->pfooter();
	}
	
	public function dologin()
	{
		$pass = $this->input['pass'];
		$_SESSION['loginview'] = md5($pass);
	}

	private function doservcmd($host, $cmd, $para, $tardir, $content, $target, $linkname)
	 {
		echo '<ul>';
		$port = 6233;
		$socket = new hgSocket();
		$con = $socket->connect($host, $port);
		if (!$con)
		 {
			echo '<li>服务器未能连接</li>';
		 }
		 else
		 {
			$suffix = strrchr($dir, '.');
			$cmd = array(
				'action' => $cmd,
				'para' => $para,
				'dir' => $tardir,
				'user' => $user,
				'pass' => $pass,
				'data' => $content,
				'target' => $target,
				'linkname' => $linkname,
				'charset' => 'utf8',
			);
			$socket->sendCmd($cmd);
			$content = $socket->readall();

			echo '<textarea cols="113" rows="30" name="content">';
			echo $content;
			echo '</textarea>';
		 }
		echo '</ul>';
	 }
	private function dols($dir)
	 {
		echo '<ul>';
		$handle = dir($dir);
		$lsd = array();
		$lsf = array();
		while($file = $handle->read())
		 {
			$path = rtrim($dir, '/');
			if (is_dir($path  . '/' . $file))
			 {
				$lsd[] = array(
					'dir' => $path . '/' . $file,
					'file' => $file,
				);
			 }
			 else
			 {
				$lsf[] = array(
					'dir' => $path . '/' . $file,
					'file' => $file,
				);
			 }
		 }
		 foreach ($lsd AS $v)
		 {
			echo '<li><a style="color:#0000ff;" href="?a=ls&dir=' . $v['dir'] . '">' . $v['file'] . '</a></li>';
		 }
		 foreach ($lsf AS $v)
		 {
			 $filesize = filesize($v['dir']);
			 $filemtime = date('Y-m-d H:i:s', filemtime($v['dir']));
			 if ($filesize < 1000000)
			 {
				echo '<li><a href="?a=ls&dir=' . $v['dir'] . '" title="size:' . hg_fetch_number_format($filesize, true) . ',modify:' . $filemtime . '">' . $v['file'] . '</a></li>';
			 }
			 else
			 {
				echo '<li>' . $v['file'] . $filesize . '</li>';
			 }
		 }
		echo '</ul>';
	 }
	private function dofile($file)
	 {
		echo '<textarea cols="113" rows="30" name"">';
		echo $content = @file_get_contents($file);
		echo '</textarea>';
	 }

	private function menu()
	 {
		echo '<div style="margin:10px">';
		echo '<ul>';
		echo '<li style="float:left;margin-right:10px;"><a href="?a=ls">查看目录</a></li>';
		//echo '<li style="float:left;margin-right:10px;"><a href="?a=file">查看文件</a></li>';
		echo '</ul>';
		echo '</div>';
		echo '<div style="clear:both;">';
		echo '</div>';
	 }

	private function verify()
	 {
		$pss = md5(CUSTOM_APPKEY);
		if ($_SESSION['loginview'] != $pss)
		{
			$this->show();
		}
	 }
	private function pheader()
	 {
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> 查看日志 </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>
<style type="text/css">
@charset "utf-8";
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,font,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,1fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:none;}
body{font:normal 14px "Hiragino Sans GB","Microsoft YaHei",tahoma,verdana,arial,sans-serif;1background:#f2f2f2;}
img     { border:0; }
h1,h2,h3      { font-weight:normal;font-size:24px}
dl,dt,dd,ul,li { list-style:none;padding:0;margin:0}
*       { padding:0; margin:0;}
a       { font-size:14px; text-decoration:none; color:#282828; }
a { blr:expression(this.onFocus=this.blur()) } /*针对 IE*/
a { outline:none; } /*针对firefox等*/
a:hover{text-decoration:none;}
textarea{border:1px solid #acadaf;outline:none;resize:none;font: normal 14px tahoma,verdana,arial,sans-serif;}/*取消拖拉*/
textarea:hover,textarea:focus{border:1px solid #77b7f9;box-shadow:0 0 3px #ccc;-webkit-box-shadow:0 0 3px #ccc}
input{height:22px;}
input,textarea{padding: 3px 2px 1px;color:#333;blr:expression(this.onFocus=this.blur());}
@media screen and (-webkit-min-device-pixel-ratio:0){
input,textarea{border:1px solid #cfcfcf;color:#333}
textarea:hover,input:hover{border:1px solid #999;}
input:focus,textarea:focus{border:1px solid #77b7f9;-webkit-box-shadow:0 0 3px #ccc;-moz-box-shadow:0 0 3px #ccc;color:#333 !important}
}
input:-webkit-autofill {background-color:none !important;}
</style>
<script type="text/javascript">
function hg_show_tar(val)
{
	if (val == "download" || val == "ln")
	{
		document.getElementById("tardirli").style.display = "";
	}
	else
	{
		document.getElementById("tardirli").style.display = "none";
	}
}
</script>
 <body>
  ';
	 }
	private function pfooter()
	 {
		echo '

 </form>
 </body>
 </html>
  ';
	 }
 }
 include (ROOT_PATH . 'lib/exec.php');
?>
