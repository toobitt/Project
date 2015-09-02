<?php
class WaterMark extends InitFrm
{
	public function __construct()
	{
		parent::__construct();		
	}

	public function __destruct()
	{
		parent::__destruct();
	}	
	
	private   $font='../data/font/msyh.ttf';
    private   $font_alpha='80';
    private   $title_option=array('literal'=>'默认标题水印文字','color'=>'#000000','pos_x'=>100,'pos_y'=>200,'size'=>14,'alpha'=>0,'font'=>'../data/font/msyh.ttf');//默认title水印文字配置
    private   $content_option=array('literal'=>'默认正文水印文字','color'=>'#000000','pos_x'=>150,'pos_y'=>200,'size'=>12,'alpha'=>0,'font'=>'../data/font/msyh.ttf');//默认title水印文字配置
    private   $img_option=array('pos_x'=>10,'pos_y'=>10, 'alpha'=>50);//默认水印图片配置
    private   $image='';//中间图
    private   $sourceImage='';//原图
    private   $sInfo='';//原图信息
    private   $waterImg='';//水印图片
    private   $contentLimt=125; //单行文字长度
    private   $contentMax=200; //正文最大文字长度
    private   $percent=1; //水印缩略比例大小
    private   $dateFormat = 'Ymd';
    private   $path = '../cache/card/';
    /**
     * 创建图片
     */
    public function createImg($image)
    {
        $info = $this->getImageInfo($image);
        //建立图像
        $createFun = "imagecreatefrom".$info['type'];
        $image = $createFun($image);
        return $image ;
    }
    /**
     * 设置水印背景图片
     * @param $sourceImage
     */
    public function setSourceImage($sourceImage)
    {
        $this->sourceImage = $sourceImage;
    }
    /**
     * 总的调用方式
     * @param $data
     */
    public function addWaterMark($sourceImage,$savename='',$imgOpt=array(),$literayOpt=array())
    {
        if (!file_exists($sourceImage))
        {
        	//使用默认画布
        	
        }else {
        
        
        }
        $this->setSourceImage($sourceImage);
        $this->sInfo = $this->getImageInfo($this->sourceImage);
        $this->image = $this->createImg($this->sourceImage);
        //添加图片水印
        if (!empty($imgOpt))
        {
            if (isset($imgOpt['waterImg']))
            {
                $this->waterImg=$imgOpt['waterImg'];
                $waterImgOption=array_merge($this->img_option,$imgOpt['waterOpt']);//后面参数优先级高
                $this->addWaterImg($waterImgOption);//添加图片水印
            }else{
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
        if ($savename!='') 
        {
        	//使用自定义的图片路径和文件名
            $this->saveImg($savename);
        }
        else {
            $attachdir=$this->path;        
            $savename=$this->getSaveName();
            $this->saveImg($attachdir.$savename);
        }
        $path =$savename;
		$image = file_get_contents($path);

		$image = substr_replace($image, pack("Cnn", 1, 300, 300), 13, 5);

		
        return $savename;
    }

    /**
     * 为图片添加图片水印
     * @param string $source 原文件名
     * @param mixed $water  水印图片array('img'=>'./1.jpg','pos_x'=>10,'pos_y'=>10, 'alpha'=>80)
     * @return mix
     * @throws ThinkExecption
     */
    public function addWaterImg($data) 
    {
        //检查文件是否存在
        if (!file_exists($this->waterImg))
        {
        	
        }  
        //图片信息
        $wInfo = $this->getImageInfo($this->waterImg);

        //建立图像
        $wImage = $this->createImg($this->waterImg);

        //设定图像的混色模式
        imagealphablending($wImage, true);

        //图像位置,默认为右下角右对齐,以后调整
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
        $thumb = imagecreatetruecolor( $data['newwidth'], $data['newheight']);
        $wImage = $this->createImg($this->waterImg);
        // Resize
        imagecopyresized($thumb, $wImage, 0, 0, 0, 0,  $data['newwidth'], $data['newheight'], $wInfo["width"],$wInfo['height']);
        //生成混合图像，这是生成图片水印最关键的
        imagecopymerge($this->image, $thumb, $posX, $posY, 0, 0,  $data['newwidth'], $data['newheight'], $data['alpha']);
        imagedestroy($thumb);
        return $this->image;
    }
    /**
     * 分别给文字标题和内容加水印
     *  @param $data
        array(
        'titileOpt'=>array('literal'=>'默认水印文字'),
        'contentOpt'=>array(...)
        )
     */
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
            $literalen=mb_strlen(trim($contentOpt['literal']),'utf-8');
            if ($literalen>$this->contentLimt)
            {	
            	//文字水印超过了单行限制
                if ($literalen>$this->contentMax)
                {
                	throw_exception('正文内容超过了最大文字长度限制：'.$this->contentMax);
                }
                //$literal=$this->phaseContent(trim($contentOpt['literal']));

                foreach ($literal as $i=>$tring)
                {
                    $contentOpt['literal']=$tring;
                    if ($i==0)
                    {
                    	$this->addWaterText($contentOpt);	
                    }
                    else{
                        $contentOpt['pos_y']+=20;
                        $this->addWaterText($contentOpt);
                    }
                }
            }else $this->addWaterText($contentOpt);
        }

    }
    /**
     * 分割字符串为数组
     * @param unknown_type $string
     */
    public function phaseContent($string)
    {
        import("ORG.Util.String");
        $data=array();
        $string_arr=explode("\n", $string);//必须用双引号
        foreach ($string_arr as $value)
        {
            $lenth=mb_strlen(trim($value),'utf-8');
            if($lenth>$this->contentLimt)
            {
                for ($i=0;$i<$lenth;$i+=$this->contentLimt)
                {
                    $temp=String::msubstr(trim($value), $i, $this->contentLimt,'utf-8',false);
                    array_push($data, $temp);
                }
                continue;
            }
            array_push($data, $value);
        }    
        if (count($data)>4)
        {
        	array_splice($data, 4);
        } 
        return $data;
    }
    /**
     *
     *给图片添加文字
     *文字水印
     *@param array $data array(array('literal'=>'literal','color'=>'mixed','pos_x'=>10,'pos_y'=>10,'size'=>14,'alpha'=>80,'font'=>'mysh.ttf'))
     */
    function addWaterText($data)
    {
        $c = $this->_parseColor($data['color']);
        $data['color'] = imagecolorallocatealpha($this->image, $c[0], $c[1], $c[2], $data['alpha']);
        imagettftext($this->image,$data['size'], 0, $data['pos_x'], $data['pos_y'], $data['color'], $data['font'],$data['literal']);
        return $this->image;
    }
    /**
     * 保存图片
     * @param unknown_type $sourceImage 源图像路径
     * @param unknown_type $sImage    通过函数产生的图像
     * @param $savename 存储路径
     */
    function saveImg($savename)
    {
        //输出图像
        $ImageFun = 'Image' . $this->sInfo['type'];
        //如果没有给出保存文件名，默认为原图像名
        if (!$savename) {
            $savename = $this->sourceImage;
            @unlink($this->sourceImage);
        }
        //保存图像
        $ImageFun($this->image, $savename);
        imagedestroy($this->image);
    }
	/**
	 * 获取图像信息
	 * @param unknown_type $img
	 */
    function getImageInfo($img) 
    {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) 
        {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            $imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                "size" => $imageSize,
                "mime" => $imageInfo['mime']
            );
            return $info;
        } else {
            return false;
        }
    }

    /**
     * 分析颜色
     * @param    string     $color    十六进制颜色
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
    private function getSaveName() 
    {
        $md5=md5(mktime().rand());
        $name=substr($md5, 0,13);
        $saveName=$name.'.'.$this->sInfo['type'];
        $saveName = $this->getDirName().'/'.$saveName;
        return $saveName;
    }
    /**
     * 获取子目录的名称
     */
    private function getDirName() {
        $attachdir=$this->path;
        $dir = date($this->dateFormat,time());
        if(!is_dir($attachdir.$dir)) {
            hg_mkdir($attachdir.$dir);
        }
        return $dir;
    }
}