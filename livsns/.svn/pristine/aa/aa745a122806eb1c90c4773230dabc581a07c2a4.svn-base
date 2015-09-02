<?php

class publishplan
{

    public $host;
    public $path;
    public $filename;
    public $action;
    public $curl;

    function __construct()
    {
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
    }

    function __destruct()
    {
        
    }

    //初始化
    function setAttribute($host, $path, $filename, $action)
    {
        $this->host     = $host;
        $this->path     = $path;
        $this->filename = $filename;
        $this->action   = $action;
        $this->curl     = new curl($host, $path);
    }

    //初始化curl
    function setCurl()
    {
        global $gGlobalConfig;
        $this->curl = new curl($gGlobalConfig['App_publishplan']['host'], $gGlobalConfig['App_publishplan']['dir'] . 'admin/');
    }

    //整体导入配置
    public function insert_plan_set($data)
    {
        $this->setCurl();
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'insert_plan');
        $this->curl->addRequestData('html', true);
        $this->array_to_add('data', $data);
        $ret = $this->curl->request('plan_node.php');
        return $ret[0];
    }

    //把需要发布的内容id添加到发布队列列表中
    public function insert_queue($data)
    {
        $this->setCurl();
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'insert_queue');
        $this->curl->addRequestData('html', true);
        $this->array_to_add('data', $data);
        return $ret = $this->curl->request('publish_plan.php');
    }

    //获取主内容
    public function get_content($from_id, $sort_id, $offset, $num, $is_update = false)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', $this->action);
        $this->curl->addRequestData('from_id', $from_id);
        $this->curl->addRequestData('sort_id', $sort_id);
        $this->curl->addRequestData('offset', $offset);
        $this->curl->addRequestData('num', $num);
        if ($is_update)
        {
            $this->curl->addRequestData('is_update', 1);
        }
        $ret = $this->curl->request($this->filename);
        return $ret[0];
    }

    //内容发布后，回调方法，存储发布系统里的内容id
    public function insert_pub_content_id($data)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', $this->action);
        $this->curl->addRequestData('html', true);
        $this->array_to_add('data', $data);
        return $ret = $this->curl->request($this->filename);
    }

    public function get_plan_set($ids)
    {
        $this->setCurl();
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_plan_set');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('ids', $ids);
        return $ret = $this->curl->request('publish_plan.php');
    }
    
    public function get_content_by_fromid($data)
    {
        $this->setCurl();
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_content_by_fromid');
        $this->curl->addRequestData('html', true);
        foreach($data as $k=>$v)
        {
            $this->array_to_add('data',$data);
        }
        return $ret = $this->curl->request('publish.php');
    }

    public function update_content($arr,$content_fromid,$bundle_id,$module_id)
    {
        $this->setCurl();
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_content');
        $this->curl->addRequestData('content_fromid', $content_fromid);
        $this->curl->addRequestData('bundle_id', $bundle_id);
        $this->curl->addRequestData('module_id', $module_id);
        if(is_array($arr))
        {
            $this->array_to_add('data', $arr);
        }
        $this->curl->addRequestData('html', true);
        return $ret = $this->curl->request('publish.php');
    }
    
    public function update_block_content($arr,$update_block_content)
    {
        $this->setCurl();
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_block_content');
        if(is_array($arr))
        {
            $this->array_to_add('block', $arr);
        }
        if(is_array($update_block_content))
        {
            $this->array_to_add('data', $update_block_content);
        }
        $this->curl->addRequestData('html', true);
        return $ret = $this->curl->request('publish.php');
    }

    public function array_to_add($str, $data)
    {
        $str = $str ? $str : 'data';
        if (is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if (is_array($vv))
                {
                    $this->array_to_add($str . "[$kk]", $vv);
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