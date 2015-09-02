<?php
class {$class_name}	extends coreFrm
{
	protected $hg_agruments = '{$args}';
	protected $hg_settings = '{$settings}';
	protected $hg_fieldreleate = '{$fieldreleate}';

	function __construct()
	{
		parent::__construct();
		$this->init_var();
		$this->curl = new curl($this->hg_settings['host'], $this->hg_settings['dir']);	
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function init_var()
	{
		$a = unserialize($this->hg_agruments);
		$this->hg_agruments = $a ? $a : array();
		$a = unserialize($this->hg_settings);
		$this->hg_settings = $a ? $a : array();
		$a = unserialize($this->hg_fieldreleate);
		$this->hg_fieldreleate = $a ? $a : array();
	}

	private function get_date($data)
	{	
		$this->hg_settings['codefmt'] = 'UTF-8';
        if($this->hg_settings['is_parameter'])
        {
            include_once(M2O_ROOT_PATH.'lib/class/curl_withnosys.class.php');
            $this->curl = new curl_withnosys($this->hg_settings['host'], $this->hg_settings['dir']);
        }
		$this->curl->initPostData();
		$this->curl->setCharset($this->hg_settings['codefmt']);
		$get_params = '?';
        foreach ((array)$this->hg_agruments['ident'] as $k=>$v)
        {
            if (!$this->hg_agruments['add_status'][$k] && $this->hg_agruments['type'][$k] != 'auto')
            {
                //系统自动添加参数
                $va = $this->hg_agruments['value'][$k];
            }
            else if ($this->hg_agruments['add_status'][$k] == 1 || $this->hg_agruments['type'][$k] == 'auto')
            {
                //用户自定义添加参数, 用户没传此参数时使用参数默认值
                if (array_key_exists($v,$data))
                {
                    $va = $data[$v];
                    //防止用户提交的内容里面@符开头,curl在请求的时候报错
                    if($va && $va[0] == '@')
                    {
                        $va = ' ' .$va;
                    }
                }
                else if($this->hg_agruments['value'][$k])
                {
                    $va = $this->hg_agruments['value'][$k];
                }
                else
                {
                    continue;
                }
            }
            else if ($this->hg_agruments['add_status'][$k] == 2)
            {
                //文件上传
                if($_FILES)
                {
                    $this->curl->addFile($_FILES);
                }
                continue;
            }

            if ($this->hg_settings['codefmt'] && $this->hg_settings['codefmt'] != 'UTF-8')
            {
                //$va = iconv('UTF-8', $this->hg_settings['codefmt'], $va);
            }

            if (is_array($va))
            {
                $this->array_to_add($v, $va);
            }
            else
            {
            	if ($this->hg_agruments['add_request'][$k] == 'post')
            	{
                	$this->curl->addRequestData($v, $va);
                }
                else
                {
                	$get_params .= "$v=$va&";
                }
            }
        }

		$this->curl->setSubmitType('post');

		if($this->hg_settings['protocol'] == 1)
		{
			$protocol = 'http';
		}
		else
		{
			$protocol = 'https';
		}
		$this->curl->setRequestType($protocol);

        //设置数据返回格式
        $this->hg_settings['data_format'] = $this->hg_settings['data_format'] ? $this->hg_settings['data_format'] : 1;
        $data_format = strtolower($this->settings[$this->hg_settings['data_format']]);
        $data_format = in_array($data_format, array('json','xml', 'str')) ? $data_format : 'json';
        $this->curl->setReturnFormat($data_format);

		$input = $data;
		$data = $this->curl->request("{$file_name}{$get_params}");

        //返回设置的数据节点
        if($this->hg_settings['data_node'] || $this->hg_settings['data_node']==='0')
		{
            if($data[$this->hg_settings['data_node']])
            {
			    $data = $data[$this->hg_settings['data_node']];
            }
		}

        //数据映射替换返回值
		if (is_array($data) && $this->hg_fieldreleate)
		{
                        $array = array();
                        if ($input['need_count'])
                        {
                            if(array_key_exists('total', $data))
                            {
                                $array['total'] = $data['total'];
                                $array['data'] = array();
                                $data = is_array($data['data']) ? $data['data'] : array();
                            }
                        }
			$tmp = array();
			foreach ($data AS $k => $v)
			{
				$val = array();
                $val = $v;
				foreach ($this->hg_fieldreleate AS $kk => $vv)
				{
					$val[$kk] = $v[$vv];
				}
				$tmp[] = $val;
			}
			if ($input['need_count'])
			{
				$array['data'] = $tmp;
				$data = $array;
			}
			else
			{
				$data = $tmp;
			}
		}
		return $data;
	}

	function show($data)
	{	
		$ret = $this->get_date($data);
		if(!is_array($ret))
        {
			$ret_arr = json_decode($ret,true);
            if (!is_array($ret_arr))
            {
                $ret_arr = $ret;
            }
        }
		else
        {
			$ret_arr = $ret;
        }
		return $ret_arr;
    }

    private function array_to_add($str, $data)
    {
        $str = $str ? $str : 'data';
        if (is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if (is_array($vv))
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

	function verifyToken()
	{

	}
        
    function curl_request()
    {

    }
}
?>