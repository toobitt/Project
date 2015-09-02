<?php
/*******************************************************
 * 
 * 
 * 
 * 
 * 
 ******************************************************/
require_once 'global.php';
define('MOD_UNIQUEID','material');
class sketchMap extends adminReadBase
{	
	private $width = 355;	   		//声称缩印图宽度
	private $height = 235; 			//声称缩印图高
	
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function show(){}
	public function detail(){}
	public function count(){}
	
	public function create_map_vod()
	{
		if (empty($this->input['app_bundle']) || empty($this->input['module_bundle']) || empty($this->input['srcFile']) || empty($this->input['name'])) {
			$this->errorOutput('参数不完整');
		}
		
		$app_bundle = urldecode($this->input['app_bundle']);
		$module_bundle = urldecode($this->input['module_bundle']);
		$srcFile = hg_getimg_dir() . urldecode($this->input['srcFile']);
		$dstPath = hg_getimg_dir() . "material/sketch_map/".$app_bundle ."/". $module_bundle . "/";
		$name = urldecode($this->input['name']);
		$title = urldecode($this->input['title']);
		$url = hg_getimg_host() .  "material/sketch_map/".$app_bundle ."/". $module_bundle . "/" . $name;
		
		if (!file_exists($srcFile)) {
			return false;
		}
		else {
			$save_file_path = $dstPath . $name;
			if ($this->check_file($save_file_path)) {
				$this->addItem($url);
				$this->output();
			}
			else {			
				//一张固定大小的图片
				$size = array(
					'width' => 355,
					'height' => 235,
				);	
				hg_mk_images($srcFile, $name, $dstPath, $size,'',100,1);	
				
				$img_info = getimagesize($save_file_path);
				if ($img_info[0] < $size['width'] || $img_info[1] < $size['height']) {	
					$img = imagecreatetruecolor($size['width'],$size['height']);
					$black = imagecolorallocate($img, 0, 0, 0);		
					imagerectangle($img, 0, 0, $size['width'], $size['height'], $black);

					switch ($img_info[2]) {
			            case 3:   
			                $s_img = imagecreatefrompng($save_file_path);   
			                break;   
			            case 2:   
			                $s_img = imagecreatefromjpeg($save_file_path);   
			                break;   
			            case 1:   
			                $s_img = imagecreatefromgif($save_file_path);   
			                break;   
			            default:   
			                return false;  
					}
					$dst_x = ($size['width']-$img_info[0])/2;
					$dst_y = ($size['height']-$img_info[1])/2;
					imagecopy($img, $s_img, $dst_x, $dst_y, 0, 0, $img_info[0], $img_info[1]);
				}
				else  {
					switch ($img_info[2]) {
			            case 3:   
			                $img = imagecreatefrompng($save_file_path);   
			                break;   
			            case 2:   
			                $img = imagecreatefromjpeg($save_file_path);   
			                break;   
			            case 1:   
			                $img = imagecreatefromgif($save_file_path);   
			                break;   
			            default:   
			                return false;  
					}	
				}
										
				//示意图拷贝到背景上
				$video_map_border = CUR_CONF_PATH . "font/video_map_border.png";
				$video_map_border_info = @getimagesize($video_map_border);	
				switch ($video_map_border_info[2]) {
					case 3:
						$border = imagecreatefrompng($video_map_border);
						break;
					case 2:
						$border  = imagecreatefromjpeg($video_map_border);
						break;
					case 1:
						$border = imagecreatefromgif($video_map_border);
						break;
					default:
						return false;
				}
				$map = imagecreatetruecolor($video_map_border_info[0], $video_map_border_info[1]);
				$white_alpha = imagecolorallocatealpha($map, 255, 255, 255,127);
				imagealphablending($map, false);
    			imagesavealpha($map, true);
				
				imagefill($map, 0, 0, $white_alpha);	
				imagecopy($map, $img, 12, 11, 0, 0, $size['width'], $size['height']);	
				imagealphablending($map, true);
				imagecopy($map,$border,0,0,0,0,$video_map_border_info[0],$video_map_border_info[1]);
				//添加标题		
				$waterStr = "视频：". $title;
				$white = imagecolorallocate($map,255,255,255);
				imagettftext($map,12,0,55,237,$white,'../font/simhei.ttf',$waterStr);
				switch ($video_map_border_info[2]) {
		            case 3:   
		                imagepng($map,$save_file_path);   
		                break;   
		            case 2:   
		                imagejpeg($map,$save_file_path);   
		                break;   
		            case 1:   
		                imagegif($map,$save_file_path);   
		                break;   
		            default:   
		                return false;  
				} 
				$this->addItem($url);
				$this->output();
			}
		}
	}
	
	
	public function create_map_tuji()
	{	
		if (empty($this->input['app_bundle']) || empty($this->input['module_bundle']) || empty($this->input['srcFile']) || empty($this->input['name'])) {
			$this->errorOutput('参数不完整');
		}
		
		$app_bundle = urldecode($this->input['app_bundle']);
		$module_bundle = urldecode($this->input['module_bundle']);
		$srcFile = explode(",", urldecode($this->input['srcFile']));
		$dstPath = hg_getimg_dir() . "material/sketch_map/".$app_bundle ."/". $module_bundle . "/";
		$name = urldecode($this->input['name']);
		$title = urldecode($this->input['title']);
		$pic_num = count($srcFile);
		$url = hg_getimg_host() .  "material/sketch_map/".$app_bundle ."/". $module_bundle . "/" . $name;
		$save_file_path = $dstPath . $name;	
		
		if ($this->check_file($save_file_path)) {
			$this->addItem($url);
			$this->output();
		}
		else {
			//示意图拷贝到背景上
			$tuji_map_bg = CUR_CONF_PATH . "font/tuji_map_bg.png";
			$tuji_map_bg_info = @getimagesize($tuji_map_bg);	
			switch ($tuji_map_bg_info[2]) {
				case 3:
					$bg = imagecreatefrompng($tuji_map_bg);
					break;
				case 2:
					$bg  = imagecreatefromjpeg($tuji_map_bg);
					break;
				case 1:
					$bg = imagecreatefromgif($tuji_map_bg);
					break;
				default:
					return false;
			}
				
			imagealphablending($bg,false);
			imagesavealpha($bg, true);
			
			for ($i=0;$i<4;$i++) {
				if (empty($srcFile[$i])) {
					continue;
				}
				$src = hg_getimg_dir() . app_to_dir($app_bundle) . $srcFile[$i];
				
				if (!file_exists($src)) {
					continue;
				}
				//声称一张固定大小的图片
				$size = array(
					'width' => 115,
					'height' => 85,
				);	
				hg_mk_images($src, $name, $dstPath, $size,'',20,1);	
				
				$img_info = getimagesize($save_file_path);
				switch ($img_info[2]) {
		            case 3:   
		                $img = imagecreatefrompng($save_file_path);   
		                break 1;   
		            case 2:   
		                $img = imagecreatefromjpeg($save_file_path);   
		                break 1;   
		            case 1:   
		                $img = imagecreatefromgif($save_file_path);   
		                break 1;   
		            default:   
		                return false;  
				}
				imagecopy($bg, $img, 5+10*($i+1)+115*$i, 45, 0, 0, $img_info[0], $img_info[1]);
			}
			
			
			$empty_img = imagecreatetruecolor($img_info[0], $img_info[1]);
			$white = imagecolorallocate($empty_img, 255, 255, 255);
			$gray = imagecolorallocate($empty_img, 192, 192, 192);
			imagefill($empty_img, 0, 0, $white);
			$str = "共{$pic_num}张图片";
			$rec = imagettfbbox(10, 0, '../font/simhei.ttf', $str);
			$w = $rec[2] - $rec[6];
			$h = $rec[3] - $rec[7];
			$x = ($img_info[0]-$w)/2;
			$y = (85-$h)/2+$h;
			imagettftext($empty_img, 10, 0, $x, $y, $gray, "../font/simhei.ttf", $str);
			
			$pic_num >= 4 ? $pic_num = 4 : $pic_num;
			imagecopy($bg, $empty_img, 5+10*($pic_num+1)+115*$pic_num, 45, 0, 0, $img_info[0], $img_info[1]);
	
			//写标题
			$title = "图集：" . $title;
	        $rect = imagettfbbox(12,0,"../font/simhei.ttf",$title);   
	        $w = abs($rect[2]-$rect[6]);   
	        $h = abs($rect[3]-$rect[7]);  
	        $tmp_img =  imagecreatetruecolor($w, $h);
	        $white = imagecolorallocate($tmp_img,255,255,255); 
	        imagefill($tmp_img,0,0,$white);  
	        imagecolortransparent($tmp_img,$white);  
	        
			$black = imagecolorallocate($bg, 0, 0, 0);
			imagettftext($tmp_img,12,0,0,12,$black,"../font/simhei.ttf",$title);  
			
			imagecopymerge($bg,$tmp_img,60,18,0,0,$w,$h,100);   
			
						
			switch($tuji_map_bg_info[2]) {
	            case 3:   
	                imagepng($bg,$save_file_path);   
	                break;   
	            case 2:   
	                imagejpeg($bg,$save_file_path);   
	                break;   
	            case 1:   
	                imagegif($bg,$save_file_path);   
	                break;   
	            default:   
	                return false;  
			} 
			
			$this->addItem($url);
			$this->output();
		}
		
	}
	
	
	public function create_map_vote_question()
	{
		
		if (empty($this->input['app_bundle']) || empty($this->input['module_bundle']) || empty($this->input['name'])) {
			$this->errorOutput('参数不完整');
		}
		
		$app_bundle = urldecode($this->input['app_bundle']);
		$module_bundle = urldecode($this->input['module_bundle']);	
		$srcFile = hg_getimg_dir() . app_to_dir($app_bundle) . urldecode($this->input['srcFile']);
		$dstPath = hg_getimg_dir() . "material/sketch_map/".$app_bundle ."/". $module_bundle . "/";
		hg_mkdir($dstPath);
		$name = urldecode($this->input['name']);
		$title = urldecode($this->input['title']);
		$url = hg_getimg_host() .  "material/sketch_map/".$app_bundle ."/". $module_bundle . "/" . $name;
		$save_file_path = $dstPath . $name;	
		
		if ($this->check_file($save_file_path)) {
			$this->addItem($url);
			$this->output();
		}
		else {
			//示意图拷贝到背景上
			$vote_map_bg = CUR_CONF_PATH . "font/vote_map_bg.png";
			$vote_map_bg_info = @getimagesize($vote_map_bg);	
			switch ($vote_map_bg_info[2]) {
				case 3:
					$map = imagecreatefrompng($vote_map_bg);
					break;
				case 2:
					$map  = imagecreatefromjpeg($vote_map_bg);
					break;
				case 1:
					$map = imagecreatefromgif($vote_map_bg);
					break;
				default:
					return false;
			}
			
			imagesavealpha($map, true);
			//$size = array('width' => 355,'height' => 200);
			//$map = imagecreatetruecolor($size['width'], $size['height']);
			//$color =imagecolorallocate($map, 245, 245, 245);
			//imagefill($map, 0, 0, $color);
			$title = explode(',', $title);
			$tit = '投票：' . $title[0];
			unset($title[0]);
			$option = array();
			foreach ($title as $k => $v) {
				$arr = explode('_', $v);
				list($name,$single_total) = $arr;
				$option[] = array('title' => $name,'single_total' => $single_total);				
			}
			
			
			$black = imagecolorallocate($map, 0, 0, 0);
			imagettftext($map, 12, 0, 70, 30, $black, "../font/simhei.ttf", $tit);
			
			$total_num = 0;
			foreach ($option  as $k => $v) {
				$total_num += $v['single_total'];
			}
			
			$num = 2;
			for ($i=0;$i<$num;$i++) {
				imagettftext($map, 10, 0, 70, 75+40*$i, $black, "../font/simhei.ttf", $option[$i]['title']);
				
				/*
				$bar_size = array('width' => 300,'height' => 30);
				$progress_size = array('width'=>150,'height'=>20);
				
				$bar = imagecreatetruecolor($bar_size['width'], $bar_size['height']);
				$bar_color = imagecolorallocate($bar, 245, 245, 245);
				imagefill($bar, 0, 0, $bar_color);
				
				$progress = imagecreatetruecolor($progress_size['width'], $progress_size['height']);
				$progress_color = imagecolorallocate($progress, 245, 245, 245);
				imagefill($progress, 0, 0, $progress_color);
				
				$black_color = imagecolorallocate($progress, 0, 0, 0);
				imagerectangle($progress, 0, 0, $progress_size['width']-1, $progress_size['height']-1, $black_color);
				
		
				if($total_num !=0)
				{
					$per = $option[$i]['single_total']/$total_num;
				}
				else 
				{
					$per = 0;
				}
				
				$per_width = $per * ($progress_size['width']-2); 
				$per = round($per*100) . '%';
				
				$blue =imagecolorallocate($progress, 65, 122, 201);
				if($per_width != 0 )
				{
					imagefilledrectangle($progress, 1, 1, $per_width, $progress_size['height']-2, $blue);
				}
				
				imagecopy($bar, $progress, 1, 5, 0, 0, $progress_size['width'], $progress_size['height']);
				
				$color = imagecolorallocate($bar, 0, 0, 0);
				$str = $option[$i]['single_total'] . '(' . $per . ')';
				
				imagettftext($bar, 10, 0, $progress_size['width']+10, 20, $color, '../font/simhei.ttf', $str);
				
				imagecopy($map, $bar, 70, 65+50*$i, 0, 0, $bar_size['width'], $bar_size['height']);
				*/
			}
			
			$option_num = count($option);	
			
			$gray = imagecolorallocate($map,189,189,189);
			if ($option_num >= $num) {
				$option = "共".$option_num."个选项";
				imagettftext($map, 9, 0, 70, 68+40*2, $gray, "../font/simhei.ttf", $option);
			}
			imagealphablending($map,false);
			imagepng($map,$save_file_path);
			imagedestroy($map);
			
			$this->addItem($url);
			$this->output();
		}
		
	}
	
	function check_file($filePath) {
		if (!$filePath) {
			$this->errorOutput('目标路径不存在');
		}
		if (file_exists($filePath)) {
			$file_date = filectime($filePath);
			if (TIMENOW - $file_date > $this->settings['SKETCH_MAP_TIME']*3600) {
				return false;
			}
		}
		else {
			return false;
		}
		return true;
	}

	
}

$out = new sketchMap();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create_map1';
}
$out->$action();