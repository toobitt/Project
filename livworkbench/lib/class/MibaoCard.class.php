<?php 
class MibaoCard
{
	private $curl;
	public function __construct()
	{
		if(!class_exists('curl'))
		{
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
		}
		
		global $gGlobalConfig;
		if($gGlobalConfig['App_auth'])
		{
			$this->curl = new curl($gGlobalConfig['App_auth']['host'], $gGlobalConfig['App_auth']['dir']);
		}
	}
	
	//获取密保卡信息
	public function get_mibao_info($id)
	{
		if(!$id)
		{
			return false;
		}
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_mibao_info');
		$this->curl->addRequestData('id',$id);
		$return = $this->curl->request('member.php');
		if($return)
		{
			$return = $return[0];
			return $return;
		}
		else 
		{
			return false;
		}
	}
	
	//去auth为用户绑定密保卡，如果已经绑定了也会重新绑定
	public function bind_card($id)
	{
		if(!$id)
		{
			return false;
		}
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','bind_card');
		$this->curl->addRequestData('id',$id);
		$return = $this->curl->request('member.php');
		if($return)
		{
			$return = $return[0];
			return $return;
		}
		else 
		{
			return false;
		}
	}
	
	//取消绑定密保卡
	public function cancel_bind($id)
	{
		if(!$id)
		{
			return false;
		}
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','cancel_bind');
		$this->curl->addRequestData('id',$id);
		$return = $this->curl->request('member.php');
		if($return)
		{
			$return = $return[0];
			return $return;
		}
		else 
		{
			return false;
		}
	}
	
	//为所有用户绑定密保($is_retain指明是否保留原有已经绑定的密保，默认是保留，如果不保留重新绑定)
	public function bind_all_user($is_retain = true)
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','bind_all_user');
		$this->curl->addRequestData('is_retain',$is_retain);
		$return = $this->curl->request('member.php');
		if($return)
		{
			$return = $return[0];
			return $return;
		}
		else 
		{
			return false;
		}
	}

	//下载密保卡
	public function download_card($img_path = '', $download_name = "")
	{
		if(!file_exists($img_path))
		{
			return false;
		}
		header("Content-Type: application/force-download; name=".$download_name."密保卡.jpg");
		header('Content-type: image/jpeg');
		header("Content-Disposition: attachment; filename=".$download_name."密保卡.jpg"); 
		@readfile($img_path);
	}
	
	//zip打包下载密保卡
	public function download_card_zip($file_path)
	{
		if(!file_exists($file_path))
		{
			return false;
		}
		header("Cache-Control: public");   
		header("Content-Description: File Transfer");   
		header('Content-disposition: attachment; filename=密保卡集合.zip'); //文件名  
		header("Content-Type: application/zip"); //zip格式的  
		header("Content-Transfer-Encoding: binary");    //告诉浏览器，这是二进制文件   
		header('Content-Length: '. filesize($file_path));    //告诉浏览器，文件大小  
		@readfile($file_path);  
	}
	
	//生成密保卡图片
	public function create_secret_image($card_name,$ret)
	{
		$nimage=imagecreatetruecolor(375,415);
		$black=imagecolorallocate($nimage,115,115,115);
		$simage =imagecreatefromjpeg('./res/mibao.jpg');
		imagecopy($nimage,$simage,0,0,0,0,375,415);
		
		$top  = 97;
		$left = 62;
		$l_s  = 40;
		$t_s  = 40;
		
		for($j = 'A';$j<='H';$j++)
		{
			$left = 57;
			for($i=1;$i<=8;$i++)
			{
				@imagestring($nimage,5,$left,$top,$ret["$j$i"], $black);
				$left += $l_s;
			}
			$top += $t_s;
		}
		$mibao_path = './cache/mibao/';
		hg_mkdir($mibao_path);
		imagejpeg($nimage,$mibao_path . $card_name . '.jpg');
		return $mibao_path .$card_name.'.jpg';
	}
}
?>