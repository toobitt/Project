<?php
class mkpublish
{
	function __construct()
	{
		global $gGlobalConfig;
		if(!$gGlobalConfig['App_mkpublish'])
		{
			return false;
		}
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_mkpublish']['host'], $gGlobalConfig['App_mkpublish']['dir']);
	}

	function __destruct()
	{
	}
	
	public function mk_publish($plan)
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('html', true);
		foreach($plan as $k=>$v)
		{
			
                        if(is_array($v))
                        {
                            foreach($v as $kk=>$vv)
                            {
                                $this->curl->addRequestData($k.'['.$kk.']', $vv);
                            }
                        }
                        else
                        {
                            $this->curl->addRequestData($k, $v);
                        }
		}
		
		$ret = $this->curl->request('cron/mkpublish.php');
		return $ret;
	}
	
	public function del_publish($plan)
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del_publish');
		$this->curl->addRequestData('html', true);
		foreach($plan as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		
		$ret = $this->curl->request('cron/mkpublish.php');
		return $ret[0];
	}
        
        public function check_is_mk($rid)
        {
                if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'check_is_mk');
		$this->curl->addRequestData('rid', $rid);
		$this->curl->addRequestData('html', true);
		$ret = $this->curl->request('admin/mkcomplete.php');
		return $ret[0];
        }
        
        public function insert_plan($plan)
        {
                if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'insert_plan');
		$this->curl->addRequestData('rid', $rid);
		$this->curl->addRequestData('html', true);
                $this->array_to_add('plan',$plan);
		$ret = $this->curl->request('admin/mking.php');
		return $ret[0];
        }
        
        public function rename_folder($oldfolder,$newfolder)
        {
                if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'rename_folder');
		$this->curl->addRequestData('oldfolder', $oldfolder);
		$this->curl->addRequestData('newfolder', $newfolder);
		$this->curl->addRequestData('html', true);
		$ret = $this->curl->request('admin/mkpublish.php');
		return $ret[0];
        }
        
        
        public function file_in($filepath, $filename, $content)
        {
            if (!$this->curl) 
            {
                return array();
            }
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();    
            $this->curl->addRequestData('a', 'file_in'); 
            $this->curl->addRequestData('filepath', $filepath); 
            $this->curl->addRequestData('filename', $filename);
            $this->curl->addRequestData('content', $content);  
            $this->curl->addRequestData('html', true);   
            $ret = $this->curl->request('admin/mkpublish.php');
            return $ret[0]; 
        }
        
        public function insert_error_log($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','insert_error_log');
		$this->array_to_add('plan',$data);
		$ret = $this->curl->request('cron/mkpublish.php');
		return $ret;
	}
        
        public function array_to_add($str , $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if(is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}
}
?>
