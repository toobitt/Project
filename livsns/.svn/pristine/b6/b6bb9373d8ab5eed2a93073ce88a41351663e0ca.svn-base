<?php

class core extends adminReadBase{
	public $curl=null;
    public function __contruct()
    {
    	parent::__construct();
    	
    }

    public function show()
    {
        
    }

    public function detail()
    {
        
    }

    public function count()
    {
        
    }

    public function index()
    {
        
    }

    public function create_curl_obj($app_name)
    {
    	$key		= 'App_'.$app_name;
    	
		global $gGlobalConfig;
		if(!$gGlobalConfig[$key])
		{
			return false;
		}
		$this->curl = new curl($gGlobalConfig[$key]['host'], $gGlobalConfig[$key]['dir']);
    }
    
    
    public function __destruct()
    {
    	
    }
    
	/*
	 * @function:get datas 
	 * @param:$params array
	 * like array('sort_id'=1,'a'=>show,'r'=>'contribute');
	 * 表示类别为1，方法名为show，请求的文件为contribute
	 */
	public function get_common_datas($params)
	{
		return $this->parseval($params);  
	}
    
    
    
    //解析curl数据
	public function parseval($params)
    {
    	
    	$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
    	foreach($params as $key=>$val)
    	{
    		if($key!='r')
    		{
    			$this->curl->addRequestData($key,$val);
    		}
    		else
    		{
    			return $this->curl->request($val.".php");
    		}
    			
    	}
    	
    	
    }
}
?>