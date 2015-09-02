<?php
//本接口用来获取CRE开关status
class outpush {

    public function __construct()
    {
        global $gGlobalConfig;
        include_once('curl.class.php');
        if ($gGlobalConfig['App_outpush']) {
            $this->curl = new curl($gGlobalConfig['App_outpush']['host'], $gGlobalConfig['App_outpush']['dir']);
        }
    }

    public function setCurl()
    {
        global $gGlobalConfig;
        $this->curl = new curl($gGlobalConfig['App_outpush']['host'], $gGlobalConfig['App_outpush']['dir']);
    }

    public function __destruct()
    {
    }

    public function getOutpushInfoByAppid($appid, $access_token)
    {
        $this->setCurl();
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'detail');
        $this->curl->addRequestData('app_id', $appid);
        $this->curl->addRequestData('access_token', $access_token);

        return $this->curl->request('outpush.php');
    }

}
