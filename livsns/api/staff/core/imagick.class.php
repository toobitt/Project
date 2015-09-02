<?php 
class ImageMagick
{
	var $srcImg_source;
	var $type;
	var $resolution;
	var $resolution_unit;												//分辨率单位，默认为1英寸，2是厘米
	private $percent   				= 1; 								//水印缩略比例大小
	private $pos         			= 0;                           		//水印默认位置
	private $font       		 	= '';                          		//水印默认字体
	private $fontColor   			= array();                    		//水印默认字体的颜色
	private $waterImg    			= '';                          		//水印图片
	private $waterImg_info 			= '';								//水印图片信息
	private $srcImg      			= '';								//水印底图
	private $srcImg_info 			= '';								//底图信息
	private $srcImg_width			= '100';							//默认画布宽度
	private $srcImg_height			= '100';							//默认画布高度
	private $srcImg_bgcolor         = 'white';							//默认画布背景
	private $srcImg_type            = 'png';
	private $path 					= './';
	private $dateFormat 			= 'Ymd';
	
	private $img_option				= array(
										'pos_x'=>10,
										'pos_y'=>10, 
										'alpha'=>0.5);					//默认水印图片配置
	private $title_option			= array(
										'literal'=>'默认标题水印文字',
										'color'=>'#000000',
										'pos_x'=>100,
										'pos_y'=>200,
										'size'=>14,
										'alpha'=>1,
										'font'=>'../data/font/msyh.ttf');//默认title水印文字配置
    private $content_option			= array('literal'=>'默认正文水印文字',
    									'color'=>'#000000',
    									'pos_x'=>150,
    									'pos_y'=>200,
    									'size'=>12,
    									'alpha'=>1,
    									'font'=>'../data/font/msyh.ttf');//默认正文水印文字配置
	
    //添加水印
	public function addWaterMark($srcImg = array(),$savename='',$imgOpt=array(),$literayOpt=array(),$type='',$resolution = array())
	{
		if (!$srcImg)
		{
			return false;
		}
		
		if (!is_array($srcImg) && $srcImg)
		{
			//底图为图片
			$this->setSrcImg($srcImg);
			$this->srcImg_info = $this->getImageInfo($this->srcImg);
			$this->srcImg_source = new Imagick($this->srcImg);
		}else {
			//创建画布
			$this->srcImg_source = new Imagick();
			$this->srcImg_info  = array_merge(array('width'=>$this->srcImg_width,'height'=>$this->srcImg_height,'bgcolor'=>$this->srcImg_bgcolor,'type'=>$this->srcImg_type),$srcImg);
			$this->srcImg_source->newimage($srcImg_info['width'], $srcImg_info['height'], new ImagickPixel($srcImg_info['bgcolor']));
			$this->srcImg_source->setformat($srcImg_info['type']);
		}
		//图片保存格式
		$this->type = $type ? $type : $this->srcImg_info['type'];
		//图片的分辨率
		$this->resolution = !empty($resolution) ?  $resolution : $this->srcImg_info['resolution'];
		//分别率的单位
		$this->resolution_unit = $resolution['unit'] ? $resolution['unit'] : $this->srcImg_info['unit'];
		//添加图片水印
		if (!empty($imgOpt))
		{
			if (isset($imgOpt['waterImg']))
			{
           		//单水印图
				$this->waterImg=$imgOpt['waterImg'];
                $waterImgOption=array_merge($this->img_option,$imgOpt['waterOpt']);//后面参数优先级高
                $this->addWaterImg($waterImgOption);//添加图片水印
                  	
            }else{
            	//多水印图片
	            foreach ($imgOpt as $parameter)
	            {
	            	$this->waterImg=$parameter['waterImg'];
	                $waterImgOption=array_merge($this->img_option,$parameter['waterOpt']);
	                $this->addWaterImg($waterImgOption);//添加图片水印
	            }
           	}
		}
	  	//添加文字水印
        if (!empty($literayOpt))
        {
            if (isset($literayOpt['titileOpt'])|| isset($literayOpt['contentOpt']))
            {
				
            	$this->addTitileContent($literayOpt);
            }else{
                foreach ($literayOpt as $parameter)
                {
                	$this->addTitileContent($parameter);
                }
            }
        }
        if ($savename)
        {
        	$this->saveImg($savename);
        }else {
            $savename=$this->getSaveName();
            $this->saveImg($savename);
        }
        
		return $savename;
		
	}
	/**
	 * 
	 * 设置背景图
	 * @param unknown_type $srcImg
	 */
	public function setSrcImg($srcImg)
	{
		$this->srcImg = $srcImg;
	}
	/**
	 * 
	 * 获取图片信息
	 * @param unknown_type $img
	 */
	public function getImageInfo($img)
	{
		$image      = new Imagick($img); 
		$handle  	= $image->readimage($img);
		if (!$handle)
		{
			return false;
		}
		$width 	 	= $image->getimagewidth();
		$height  	= $image->getimageheight();
		$mime     	= $image->getimagemimetype();
		$type       = $image->getimageformat();
		$size       = $image->getimagesize();
		$resolution = $image->getimageresolution();
		$unit 		= $image->getimageunits();
		$info       = array(
						'width'=>$width,
						'height'=>$height,
						'mime'=>$mime,
						'type'=>$type,
						'size'=>$size,
						'resolution'=>$resolution,
						'unit'=>$unit,		
					); 
		$image->destroy();
		return $info;
	}
	/**
	 * 
	 * 图片水印
	 * @param unknown_type $data
	 */
	public function addWaterImg($data)
	{
		$waterImg = new Imagick($this->waterImg);
		$wInfo = $this->getImageInfo($this->waterImg);
		if ($wInfo['width']>$this->srcImg_info['width'] || $wInfo['height']>$this->srcImg_info['height'])
		{
			return ;
		}
		$posY = $data['pos_y'];
        $posX =$data['pos_x'];
	 	//对水印图片缩略
        if (!isset($data['newwidth'])&&!isset($data['newheight']))
        {
        	//长宽都没设置，则按百分比
            $data['newwidth'] = $wInfo["width"] * $this->percent;
            $data['newheight'] = $wInfo['height']* $this->percent;
        }elseif (!isset($data['newwidth'])){
        	//没设置宽，则按长为准
            $this->percent=$wInfo["width"] / $wInfo['height'];//宽长比              
            $data['newwidth'] =  round($data['newheight'] * $this->percent,2);
           
        }elseif (!isset($data['newheight'])){
        	//没设置长，则按宽为准
            $this->percent=$wInfo['height']/$wInfo["width"];//宽长比        
            $data['newheight'] = round($data['newwidth'] * $this->percent,2);
        }
        //缩略图
        //$waterImg->thumbnailimage($data['newwidth'], $data['newheight'],true);
  		$waterImg->setimageresolution($this->resolution['x'], $this->resolution['y']);
  		$waterImg->setimageunits($this->resolution_unit);
  		$waterImg->enhanceimage();
  		$waterImg->setimageopacity($data['alpha']);
  		$this->srcImg_source->compositeimage($waterImg, $this->srcImg_source->getimagecompose(), $posX, $posY);
  		$waterImg->destroy();
  		/*
  		$draw = new ImagickDraw();
  		$draw->composite($waterImg->getimagecompose(), $posX, $posY, $data['newwidth'], $data['newheight'], $waterImg);
		$this->srcImg_source->drawimage($draw);
		$draw->destroy();
		*/
  		
  		return $this->srcImg_source;
	}
	
 	public function addTitileContent($data)
    {
    	if (empty($data)) 
        {
        	return ;
        }
        if (isset($data['titileOpt']))
        {
            $titileOpt=array_merge($this->title_option,$data['titileOpt']);
            $this->addWaterText($titileOpt);
        }
        if (isset($data['contentOpt']))
        {
            $contentOpt=array_merge($this->content_option,$data['contentOpt']);
            //获取文字长度
            //$literalen=mb_strlen(trim($contentOpt['literal']),'utf-8');
            $this->addWaterText($contentOpt);
        }
    }
    /**
     * 
     * 文字水印
     * @param unknown_type $data
     */
    public function addWaterText($data)
    {
    	$draw = new ImagickDraw();
    	$this->ImagickPixel = new ImagickPixel();
    	$draw->clear();
    	$draw->setfont($data['font']); 
    	$draw->setfontsize($data['size']);
    	$this->ImagickPixel->setcolor($data['color']);
    	$draw->setfillcolor($data['color']);
    	$draw->setfillalpha($data['alpha']);
    	$draw->settextalignment(imagick::GRAVITY_NORTHWEST);//左对齐
    	$draw->annotation($data['pos_x'], $data['pos_y'], $data['literal']);
    	$this->srcImg_source->drawimage($draw);
    	$draw->destroy();
    	return $this->srcImg_source;
    }
    
	public function color_inverse($color)
	{
	    $color = str_replace('#', '', $color);
	    if (strlen($color) != 6){ return '000000'; }
	    $rgb = '';
	    for ($x=0;$x<3;$x++){
	        $c = 255 - hexdec(substr($color,(2*$x),2));
	        $c = ($c < 0) ? 0 : dechex($c);
	        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
	    }
	    return '#'.$rgb;
	}
	/**
	 * 
	 * 分析颜色
	 * @param unknown_type $color
	 */
    private function _parseColor($color)
    {
        $arr = array();
        for($ii=1; $ii<strlen($color); $ii++)
        {
            $arr[] = hexdec(substr($color,$ii,2));
            $ii++;
        }
        Return $arr;
    }
	/**
	 * 
	 * 文件名
	 */
    private function getSaveName() 
    {
        $md5=md5(mktime().rand());
        $name=substr($md5, 0,13);
        $saveName=$name.'.'.$this->srcImg_info['type'];
        $saveName = $this->getDirName().'/'.$saveName;
        return $saveName;
    }
 	
    /**
     * 
     * 目录
     */
    private function getDirName() {
		$attachdir=$this->path;
        $dir = date($this->dateFormat,time());
        if(!is_dir($attachdir.$dir))
        {	
        	if(!@mkdir($dir, '0777', 1))
			{
				return false;//创建目录失败
			}
			@chmod($dir, '0777');
        }
        return $dir;
    }
	/**
	 * 
	 * 保存图片
	 * @param unknown_type $savename
	 */
    private function saveImg($savename)
    {
    	//如果没有给出保存文件名，默认为原图像名
		if (!$savename) {
            $savename = $this->srcImg;
            @unlink($this->srcImg);
        }
        //保存图像
  		$this->srcImg_source->setimageformat($this->type);
        $this->srcImg_source->setimageresolution($this->resolution['x'], $this->resolution['y']);
  		$this->srcImg_source->setimageunits($this->resolution_unit);
        $this->srcImg_source->writeimage($savename);
        $this->srcImg_source->destroy();
    }
}

?>