<?php

require (CUR_CONF_PATH . 'lib/curl.class.php');

class publishcontent_syn
{

    function __construct()
    {

        global $gGlobalConfig;
        if ($gGlobalConfig['publishcontent_cloud'])
        {
            include_once (ROOT_PATH . 'lib/class/curl.class.php');
            $this->curl = new curl_publish($gGlobalConfig['publishcontent_cloud']['host'], $gGlobalConfig['publishcontent_cloud']['dir']);
        }
    }

    function __destruct()
    {
        
    }

    public function syn_site($data)
    {
        if (!$this->curl)
        {
            return array();
        }

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'operate');
        $this->curl->addRequestData('html', 1);
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $this->curl->addRequestData('appid', 1);
        $this->curl->addRequestData('appkey', 'VVUvibqXDJB4OvCo00VApPdWTD3IK4Ow');
        unset($this->input['access_token']);
        $ret = $this->curl->request('admin/site.php');
        //print_r($this->curl);
        //print_r($ret);
        return $ret[0];
    }

    public function syn_site_delete($data)
    {
        if (!$this->curl)
        {
            return array();
        }

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete');
        $this->curl->addRequestData('html', 1);
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $this->curl->addRequestData('appid', 1);
        $this->curl->addRequestData('appkey', 'VVUvibqXDJB4OvCo00VApPdWTD3IK4Ow');
        unset($this->input['access_token']);
        $ret = $this->curl->request('admin/site.php');
        //print_r($this->curl);
        //print_r($ret);
        return $ret[0];
    }

    public function syn_column($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'operate_syn');
        $this->curl->addRequestData('html', 1);
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $this->curl->addRequestData('appid', 1);
        $this->curl->addRequestData('appkey', 'VVUvibqXDJB4OvCo00VApPdWTD3IK4Ow');
        unset($this->input['access_token']);
        $ret = $this->curl->request('admin/column.php');
        //print_r($this->curl);
        return $ret[0];
    }

    public function syn_column_delete($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete');
        $this->curl->addRequestData('html', 1);
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $this->curl->addRequestData('appid', 1);
        $this->curl->addRequestData('appkey', 'VVUvibqXDJB4OvCo00VApPdWTD3IK4Ow');
        unset($this->input['access_token']);
        $ret = $this->curl->request('admin/column.php');
        //print_r($this->curl);
        return $ret[0];
    }

    public function syn_column_sort($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'column_sort');
        $this->curl->addRequestData('html', 1);
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $this->curl->addRequestData('appid', 1);
        $this->curl->addRequestData('appkey', 'VVUvibqXDJB4OvCo00VApPdWTD3IK4Ow');
        unset($this->input['access_token']);
        $ret = $this->curl->request('admin/column.php');
        //print_r($this->curl);
        return $ret;
    }

    public function content($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'content');
        $this->curl->addRequestData('html', 1);
        $this->array_to_add('data', $data);
        $ret = $this->curl->request('admin/content_set_syn.php');
        return $ret;
    }

    public function delete_syn_content($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete');
        $this->curl->addRequestData('html', 1);
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $ret = $this->curl->request('admin/content_set_syn.php');
        return $ret;
    }

    public function update_syn_weight($siteids, $data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_syn_weight');
        $this->curl->addRequestData('html', 1);
        $this->curl->addRequestData('_site_syn_ids', $siteids);
        $this->array_to_add('data', $data);
        $ret = $this->curl->request('admin/content_set_syn.php');
        return $ret;
    }
    
    public function update_syn_content($siteids, $data,$rid)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_syn_content');
        $this->curl->addRequestData('html', 1);
        $this->curl->addRequestData('_site_syn_ids', $siteids);
        $this->curl->addRequestData('rid', $rid);
        $this->array_to_add('data', $data);
        $ret = $this->curl->request('admin/content_set_syn.php');
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