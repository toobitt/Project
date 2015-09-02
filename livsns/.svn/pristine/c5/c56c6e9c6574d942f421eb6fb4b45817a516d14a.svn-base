<?php
define('MOD_UNIQUEID','qrcode');
define(ROOT_DIR, '../../');
require ROOT_DIR . 'global.php';
require_once CUR_CONF_PATH .'core/phpqrcode/phpqrcode.php';
require_once ROOT_PATH . 'lib/class/material.class.php';
class qrcodeApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();			
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function code()
	{
		
		$type = intval($this->input['type']);
		$content = $this->input['data'];
		$config = $this->input['config'];
		$flag = $this->input['flag'];
		switch ($type)
		{
			case 0:$str = $this->text($content);break;
			case 1:$str = $this->web($content);break;
			case 2:$str = $this->tel($content);break;
			case 3:$str = $this->sms($content);break;
			case 4:$str = $this->card($content);break;
			case 5:$str = $this->email($content);break;
			case 6:$str = $this->wifi($content);break;
			default:$str = $this->text($content);break;
		}
	
		if (!$str)
		{
			$this->errorOutput('参数错误');
		}
		$data = array();
		$data['data'] = $str;
		$data['errorCorrectionLevel'] = $config['errorCorrectionLevel'];
		$data['matrixPointSize'] = $config['errorCorrectionLevel'];
		$data['margin'] = $config['margin'];
		
		if ($this->settings['qrcode_default'])
		{
			$data['errorCorrectionLevel'] = $data['errorCorrectionLevel'] ? $data['errorCorrectionLevel'] : $this->settings['qrcode_default']['errorCorrectionLevel'];
			$data['matrixPointSize']= $data['matrixPointSize'] ? $data['matrixPointSize'] : $this->settings['qrcode_default']['matrixPointSize'];
			$data['margin'] = $data['margin'] ? $data['margin'] : $this->settings['qrcode_default']['matrixPointSize'];
			$data['path'] = $this->settings['qrcode_default']['path'];
		}else 
		{
			$data['errorCorrectionLevel'] = $data['errorCorrectionLevel'] ? $data['errorCorrectionLevel'] : 'L';
			$data['matrixPointSize']= $data['matrixPointSize'] ? $data['matrixPointSize'] : 4;
			$data['margin'] = $data['margin'] ? $data['margin'] : 2;
			$data['path'] = 'cache/';
		}
		$ret = $this->phpqrcode($data);
		if ($flag && !empty($ret))
		{
			header('Content-Type: image/png');			
			$im = imagecreatefrompng($ret['host'].$ret['dir'].$ret['filepath'].$ret['filename']);
			imagepng($im);
		}else {
			$this->addItem($ret);
			$this->output();
		}	
	}
	
	private function text($data)
	{
		$str = '';
		if (!$data['content'])
		{
			return false;
		}
		$str = $data['content'];
		return $str;
	}
	
	private function web($data=array())
	{
		
		$str = 'URLTO:';
		if (!$data['content'])
		{
			return false;
		}
		$str .= $data['content'];
		
		return $str;	
	}
	
	private function tel($data)
	{
		$str = 'tel:';
		if (!$data['content'])
		{
			return false;
		}
		$str .= $data['content'];
		return $str;
	}
	
	private function sms($data)
	{
		$str = '';
		if (!$data['content'] || $data['tel'])
		{
			return false;
		}
		$str = 'smsto:'.$data['tel'].':'.$data['content'];
		return $str;
	}
	
	private function card($data)
	{
		$value = "BEGIN:VCARD\r\nVERSION:2.1\r\nN:".$val['surname'].";".$val['name']."\r\nFN:".$val['surname'].$val['name']."\r\nORG:".$val['company']."\r\nTITLE:".$val['position']."\r\nTEL;CELL:".$val['mobile']."\r\nTEL;WORK:".$val['tel']."\r\nTEL;EXT:".$val['ext_num']."\r\nEMAIL:".$val['email']."\r\nURL:".$val['web']."\r\nEND:VCARD";
		$str = "BEGIN:VCARD\r\nVERSION:2.1\r\n";
		if ($data['surname'] && $data['name'])
		{
			$str .= "N:".$data['surname'].";".$data['name']."\r\nFN:".$data['surname'].$data['name']."\r\n";
		}
		if ($data['tel'])
		{
			$str .= "TEL;WORK:".$data['tel']."\r\n";
			if ($data['ext_num'])
			{
				$str .= "TEL;EXT:".$data['ext_num']."\r\n";
			}
		}
		if ($data['mobile']) {
			$str .= "TEL;CELL:".$data['mobile']."\r\n";
		}
		if ($data['company'])
		{
			$str .= "ORG:".$data['company']."\r\n";
		}
		if ($data['position'])
		{
			$str .= "TITLE:".$data['position']."\r\n";
		}
		if ($data['email']) {
			$str .= "EMAIL:".$data['email']."\r\n";
		}
		if ($data['addr'])
		{
			$str .= "ADR:".$data['addr']."\r\n";
		}
		if ($data['web'])
		{
			$str .= "URL:".$data['web']."\r\n";
		}
		$str .= "END:VCARD";
	}
	
	private function email($data)
	{
		$str = 'mailto:';
		if (!$data['content'])
		{
			return false;
		}
		$str .= $data['content'];
		return $str;
	}
	
	private function wifi($data)
	{
		$str = 'WIFI:';
		if (!isset($data['type']) || !$data['ssid'] || !$data['password'])
		{
			return false;
		}
		switch ($data['type'])
		{
			case 0:$data['type'] = 'nopass';break;
			case 1:$data['type'] = 'WPA';break;
			case 2:$data['type'] = 'WEP';break;
			default:$data['type'] = 'nopass';break;
		}
		$str .= 'T:'.$data['type'].';S:'.$data['ssid'].';P:'.$data['password'].';H:true;';
		return $str;		
	}
	
	public function create()
	{
		$data = array(
			'data' =>$this->input['data'],
			'errorCorrectionLevel'=>strtoupper($this->input['errorCorrectionLevel']),
			'matrixPointSize'=>intval($this->input['matrixPointSize']),
			'margin'=>intval($this->input['margin']),
		);
		if (!$data['data'])
		{
			$this->errorOutput(NO_DATA);
		}
		if ($this->settings['qrcode_default'])
		{
			$data['errorCorrectionLevel'] = $data['errorCorrectionLevel'] ? $data['errorCorrectionLevel'] : $this->settings['qrcode_default']['errorCorrectionLevel'];
			$data['matrixPointSize']= $data['matrixPointSize'] ? $data['matrixPointSize'] : $this->settings['qrcode_default']['matrixPointSize'];
			$data['margin'] = $data['margin'] ? $data['margin'] : $this->settings['qrcode_default']['matrixPointSize'];
			$data['path'] = $this->settings['qrcode_default']['path'];
		}else 
		{
			$data['errorCorrectionLevel'] = $data['errorCorrectionLevel'] ? $data['errorCorrectionLevel'] : 'L';
			$data['matrixPointSize']= $data['matrixPointSize'] ? $data['matrixPointSize'] : 4;
			$data['margin'] = $data['margin'] ? $data['margin'] : 2;
			$data['path'] = 'cache/';
		}
		$ret = $this->phpqrcode($data);
		$this->addItem($ret);
		$this->output();
	}
	private  function phpqrcode($data)
	{
		$ret = array();
		$content  = $data['data'];
		$PNG_WEB_DIR = $data['path'];
		$errorCorrectionLevel = $data['errorCorrectionLevel'];
		$matrixPointSize = $data['matrixPointSize'];
		$margin = $data['margin'];
		$filename = $PNG_WEB_DIR.'qrcode'.md5($data['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		$url_filename = $this->settings['App_qrcode']['protocol'] . $this->settings['App_qrcode']['host'].'/'.$this->settings['App_qrcode']['dir'].$filename;
		QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize,$margin);
		if (!$filename)
		{
			return false;
		}
		//上传图片服务器
		$material = $this->material->localMaterial($url_filename, 0, 0, '-1');
		$material = $material[0];
		if ($material)
		{
			$ret = array(
				'host'		=>	$material['host'],
				'dir'		=>	$material['dir'],
				'filepath'	=>	$material['filepath'],
				'filename'	=>	$material['filename'],
			);
			//删除图片
			if (file_exists($filename))
			{
				unlink($filename);
			}
			return $ret;
		}
	}

}
$ouput= new qrcodeApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();