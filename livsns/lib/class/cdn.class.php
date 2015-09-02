<?php

class cdn
{

    function __construct()
    {
        global $gGlobalConfig;
        if (!$gGlobalConfig['App_cdn'])
        {
            return false;
        }
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        $this->curl = new curl($gGlobalConfig['App_cdn']['host'], $gGlobalConfig['App_cdn']['dir']);
    }

    function __destruct()
    {
        
    }

    public function push($urls,$dirs,$type='')
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'push');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('urls', $urls);
        $this->curl->addRequestData('dirs', $dirs);
        $this->curl->addRequestData('type', $type);
        $ret = $this->curl->request('admin/CDNUpdate.php');
        return $ret[0];
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
