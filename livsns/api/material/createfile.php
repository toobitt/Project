<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: water.php 6406 2012-04-12 09:47:23Z wangleyuan $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('WITHOUT_DB', true);
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class createfile extends InitFrm
{
	/**
	 * 构造函数
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function update(){}
	public function delete(){}

    /**
     * 文件不存在时转向到此方法 创建缩略图
     *
     * 源文件不存在 显示默认图  font/indexbg.jpg
     * 应用自定义默认图放在 font/{$app_uniqueid}/indexbg.jpg
     *
     * @param host string
     * @param refer_to string required 文件路径
     *      如material/news/img/100x75/2012/05/20120529143609jSG7.jpg?21jj
     */
    function create()
	{
		$url = urldecode($this->input['refer_to']);
		$host = urldecode($this->input['host']);
		//$url = 'material/news/img/100x75/2012/05/20120529143609jSG7.jpg?21jj';
		if ( empty($url) || empty($host) ) {
			$this->readfile(CUR_CONF_PATH . 'font/indexbg.jpg', 'indexbg.jpg');
		}
		else {			preg_match_all('/^\/(.*?\/.*?\/)?(.*?\/img\/)([0-9]*)[x|-]([0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)(\.[a-zA-Z0-9_]*)(\?\w*)?/i',$url,$out);
			$tmp = explode('/', $out[2][0]);
			if ($tmp[0] == 'material')
			{
          	  $app_uniqueid = $tmp[1];
          	}
          	else
          	{
          		$app_uniqueid = $tmp[0];
          	}
			$info = array(
					'host' => $host,//
					'dir' =>  $out[2][0],//
					'filepath' => $out[5][0] ,// 2012/05/
					'filename' => $out[6][0] ,// 20120529143609jSG7
					'type' => $out[7][0],//.jpg		
					'app_uniqueid'  => $app_uniqueid,      //news
			);
			$size = array(
					'label' => $out[3][0] . 'x' . $out[4][0],// 100-75/
					'width' => $out[3][0],
					'height' => $out[4][0],
					'other_dir' => $out[5][0],
			);
			$IMG_DIR = $this->settings['imgdirs']['http://' . $info['host'] . '/'];
			if (!$IMG_DIR)
			{
				$IMG_DIR = IMG_DIR;
			}
			$filepath = $IMG_DIR . $info['dir'] . $info['filepath'] . $info['filename'] . ".json";
			$water_url = $position = "";
			if ( file_exists($filepath) ) {
				$json = json_decode(file_get_contents($filepath),true);
				//$water = $json['water'];   //原图加水印 缩略图不重复加水印
			}
			$path = $IMG_DIR . $info['dir'];
            $file = $path . $info['filepath'] . $info['filename'] . $info['type'];
            if (!file_exists($file) || !is_file($file)) {
                //文件不存在 显示默认图  font/indexbg.jpg
                //应用自定义默认图放在 font/{$app_uniqueid}/indexbg.jpg
                $default_img = CUR_CONF_PATH . 'font/indexbg.jpg';
                if ( file_exists(CUR_CONF_PATH . 'font/' . $info['app_uniqueid'] . '/indexbg.jpg') ) {
                    $default_img = CUR_CONF_PATH . 'font/' . $info['app_uniqueid'] . '/indexbg.jpg';
                }
                $this->readfile($default_img, 'indexbg.jpg');
            }
			$isSucc = hg_mk_images($file, $info['filename'] . $info['type'], $IMG_DIR . $info['dir'], $size, $water);
			if ( $isSucc ) {
				if (file_exists($filepath)) {
					$thumb = json_decode(file_get_contents($filepath),true);
				}
                else {
					$thumb = array();
				}
                $path = rtrim(realpath($path), '/') . '/';  //记录绝对路径
				$thumb_tmp = $path . $size['label'] . "/" . $info['filepath'] . $info['filename'] . $info['type'];
				if ( (is_array($thumb['thumb']) && !in_array($thumb_tmp,$thumb['thumb'])) || empty($thumb['thumb']) ) {
                    // 判断这个列表不不存文件中
					$thumb['thumb'][] = $thumb_tmp;
				}
				hg_file_write($filepath,json_encode($thumb));
				$header_url = 'http://' . $info['host']  . "/" . $info['dir'] . $size['label'] . "/" . $info['filepath'] . $info['filename'] . $info['type'];
				if ($this->settings['realtime_refresh_cdn'] && $this->settings['App_cdn']) {
                    include_once(ROOT_PATH . 'lib/class/cdn.class.php');
                    $cdn = new cdn();
                    $cdn->push($header_url,'');
                }
				$file = $path . $size['label'] . "/" . $info['filepath'] . $info['filename'] . $info['type'];
				$this->readfile($file,$info['filename'] . $info['type'], $info['type']);
			}
			else {  //缩略图创建失败  显示原图
				$this->readfile($file ,$info['filename'] . $info['type']);
			}

		}
		 
	}
	
	private function readfile($file,$filename, $type=".jpg")
	{
		if (!is_file($file))
		{
			return;
		}
		header('Cache-control: max-age=31536000');
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . ' GMT');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		if ($type == '.jpg')
		{
				$ttype = 'jpeg';
		}
		else
		{
				$ttype = trim($type, '.');
		}
		header('Content-Type:image/' . $ttype);
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: ' . filesize($file));
		readfile($file);
		if ($this->settings['deletethumb'])
		{
			@unlink($file);
		}
		exit;
	}

	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new createfile();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();

?>