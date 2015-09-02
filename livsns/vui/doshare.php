<?php
define('ROOT_DIR', '../');
require('./global.php');
class do_share extends uiBaseFrm
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
		$type = intval($this->input['type']);
		$url = $this->input['url'];
		include_once(ROOT_PATH . 'lib/class/shorturl.class.php');
		$shorturl = new shorturl($url);
		$url = $shorturl->shorturl($url);
		$vedio_url = $this->input['vedio_addr'];
		$title = $this->input['title'];
		
		$this->tpl->addVar('type', $type);
		$this->tpl->addVar('url', $url);
		$this->tpl->addVar('vedio_url', $vedio_url);
		$this->tpl->addVar('title', $title);
		echo $this->tpl->outTemplate('share_comm','hg_html_share_comm');
	}
	
	public function show_video()
	{
		$video_id = $this->input['id'];
	  	ob_end_clean(); 
		echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$video_id));
		exit;
	}
	
	public function uploadvideo(){
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
		$url = $this->input['url'];
		$ret = "";
		if(preg_match("((((f|ht){1}tp|ftp|gopher|news|telnet|rtsp|mms)://|www\.)[-a-zA-Z0-9@:%_\+.~#?&//=]+)" ,$url))
		{
			$ret = $this->status->uploadVideo($url);
		}
		echo json_encode($ret);
		exit;
	}
	
	/**
	* 发布，转发点滴的处理方法
	* 
	*/	
	public function update()
	{
		if($this->user['id'])
		{
			include_once(ROOT_PATH . 'lib/class/status.class.php');
			$this->status = new status();
			$text = $this->input['status']?$this->input['status']:"";
			$ret = $this->status->verifystatus();
			if($ret['total']&&$ret['text'] === $text&&!$ret['reply_status_id'])
			{
				ob_end_clean();
				echo json_encode($ret);
				exit;
			}
			$source = $this->input['source']?$this->input['source']:"";
			$id = $this->input['status_id']? $this->input['status_id']:0;
			$type = $this->input['type']?$this->input['type']:"";  
			$media_id = $this->input['media_id'];
			$ret = $this->status->update($text,$source,$id,0,$type); 
			if(!$ret['total'])
			{
				if($media_id)
				{
					$info = $this->status->updateMedia($ret['id'], $media_id);
				}
			}
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
		else
		{
			ob_end_clean();
			echo json_encode('false');
			exit;
		}
	}
	
	public function uploadpic(){
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
		if(!$this->input['media_id'] && !$this->input['status_id'])
		{
			$file = json_encode($this->status->uploadeImage($_FILES));
			echo '<script>parent.endUploads("' . addslashes($file) . '")</script>';	
		}
		else
		{
			$info = $this->status->updateMedia($this->input['status_id'], $this->input['media_id']);
			ob_end_clean();
			echo json_encode($info);
			exit;
		}	
	}
	 
}

$out = new do_share();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();