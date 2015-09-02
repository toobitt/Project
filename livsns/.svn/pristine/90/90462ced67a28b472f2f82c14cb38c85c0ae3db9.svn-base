<?php 
class mediaserver
{
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_mediaserver']['host'], $gGlobalConfig['App_mediaserver']['dir'] . 'admin/');
	}
	public function __destruct()
	{
		unset($this->curl);
	}
	/**
	 * 获取转码配置
	 */
	public function getSettings()
	{
		$waterpic = $this->getWaterPic();
		$defaultwater = $this->getDefaultWater();
		$mosaic = $this->getMosaic();
		$transcodeserver = $this->getTranscodeServer();
		$configtype = $this->getConfigType();
		$max_size = $transcodeserver['max_size'] ? $transcodeserver['max_size'] : '';
		if($transcodeserver['max_size'])unset($transcodeserver['max_size']);
		$ret = array(
			'server_id'		 => array_values($transcodeserver),
			'water_id' 		 => $waterpic,
			'mosaic_id'		 => $mosaic,
			'vod_config_id'	 => $configtype,
			'other' 	 		 => array('defaultwater'=>$defaultwater,'max_size'=>$max_size),
		);
		return $ret;
	}
	
	//获取水印图片
	private function getWaterPic()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('count',$this->input["water_count"]);
		$this->curl->addRequestData('offset',$this->input["offset"]);
		$ret = $this->curl->request('water_config.php');
		return $ret;
	}
	
	//获取马赛克
	private function getMosaic()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('count',$this->input["mosaic_count"]);
		$this->curl->addRequestData('offset',$this->input["offset"]);
		$ret = $this->curl->request('mosaic.php');
		return $ret;
	}
	
	//获取转码服务器
	private function getTranscodeServer()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getCanUseServers');
		$ret = $this->curl->request('transcode_center.php');
		if($ret && $ret[0])
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	//获取默认的水印
	private function getDefaultWater()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getDefaultWater');
		$ret = $this->curl->request('vod_config.php');
		return $ret;
	}
	//获取转码配置
	private function getConfigType()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getVodConfig');
		$ret = $this->curl->request('vod_config_type.php');
		if($ret && $ret[0])
		{
			$ret = $ret[0];
		}
		return $ret;
	}
	
	/**
     *
     * @Description  获取视频的配置
     * @author Kin
     * @date 2013-4-13 下午04:48:54
     */
    public function getVideoConfig()
    {
        $videoConfig = array();
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','__getConfig');
        $ret = $this->curl->request('index.php');
        if (empty($ret))
        {
            return false;
        }
        $temp = explode(',', $ret[0]['video_type']['allow_type']);
        $videoConfig['type'] = $temp;
        if (is_array($temp) && !empty($temp))
        {
            foreach ($temp as $val)
            {
                $videoType[] = ltrim($val,'.');
            }
            $videoConfig['hit'] = implode(',', $videoType);

        }
        return $videoConfig;
    }
    
    /**
     *
     * @Description 视频上传
     * @author Kin
     * @date 2013-4-13 下午04:34:29
     */
    public function uploadToVideoServer($file,$title='',$brief = '',$vod_lexing = 1)
    {
    	$this->curl->setSubmitType('post');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addFile($file);
    	$this->curl->addRequestData('title', $title);
    	$this->curl->addRequestData('comment',$brief);
    	$this->curl->addRequestData('vod_leixing',$vod_lexing);//网页传的视频类型是1，手机传的视频是2
    	$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
    	$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
    	$ret = $this->curl->request('create.php');
    	return $ret[0];
    }
}

?>