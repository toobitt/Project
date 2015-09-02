<?php

class publishcms
{

    function __construct()
    {
        global $gGlobalConfig;
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        if (!$gGlobalConfig['App_livcms'])
        {
            return false;
        }
        $this->curl = new curl($gGlobalConfig['App_livcms']['host'], $gGlobalConfig['App_livcms']['dir']);
    }

    function __destruct()
    {
        
    }

    public function insert_cms_column($data)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'create');
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $ret = intval($this->curl->request('sync_cms_column.php'));
        return $ret;
    }

    public function insert_cms_site($data)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'create');
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $ret = intval($this->curl->request('sync_cms_site.php'));
        return $ret;
    }

    public function update_cms_column($data)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update');
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $ret = intval($this->curl->request('sync_cms_column.php'));
        return $ret;
    }

    public function update_cms_site($data)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update');
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $ret = intval($this->curl->request('sync_cms_site.php'));
        return $ret;
    }

    public function delete_cms_site($siteid)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete');
        $this->curl->addRequestData('siteid', $siteid);
        $ret = intval($this->curl->request('sync_cms_site.php'));
        return $ret;
    }

    public function delete_cms_column($columnid)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete');
        $this->curl->addRequestData('columnid', $columnid);
        $ret = $this->curl->request('sync_cms_column.php');
        return $ret;
    }

    public function column_sort($sort)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'drag_order');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('order', $sort);
        $ret = $this->curl->request('sync_cms_column.php');
        return $ret;
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