<?php

class publishsys
{	
	function __construct()
	{
		global $gGlobalConfig;
		if ($gGlobalConfig['App_publishsys'])
		{
			$this->curl = new curl($gGlobalConfig['App_publishsys']['host'],$gGlobalConfig['App_publishsys']['dir']);
		}
	}
	function __destruct()
	{
		
	}
	
	//触发生成生成发布的缓存文件
	function mk_cache($plan)
	{
		if (!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('only_mk_cache', 1);
		$this->array_to_add('plan', $plan);
		$this->curl->request('admin/mk.php');
	}

    function getallsites()
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'show');
        $this->curl->addRequestData('need_normal_id', 1);
        $sites = $this->curl->request('admin/deploy_node.php');
        foreach ($sites as $k => $v)
        {
            $site[$v['id']] = $v['name'];
        }
        return $site;
    }

    function getallsites_mkpublish()
    {
        global $gGlobalConfig;
        if ($gGlobalConfig['App_mkpublish'])
        {
            $this->curl = new curl($gGlobalConfig['App_mkpublish']['host'],$gGlobalConfig['App_mkpublish']['dir']);
        }
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'show');
        $this->curl->addRequestData('get_site', 1);
        $this->curl->addRequestData('need_normal_id', 1);
        $sites = $this->curl->request('admin/mkpublish.php');
        foreach ($sites as $k => $v)
        {
            $site[$v['id']] = $v['name'];
        }
        return $site;
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