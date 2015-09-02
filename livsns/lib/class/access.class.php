<?php

class access
{

    function __construct()
    {
        global $gGlobalConfig;
        $this->curl = new curl($gGlobalConfig['App_access']['host'], $gGlobalConfig['App_access']['dir']);
    }

    function __destruct()
    {
        
    }

    public function delete($cid)
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('cid', $cid);
        return $ret = $this->curl->request('edit_del.php');
    }

    public function add_access($id,$column_id,$appunid,$modunid,$title,$content_fromid = 0,$fromType = '')
    {
        if (!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->setCurlTimeOut(1);
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('column_id', $column_id);
        $this->curl->addRequestData('app_uniqueid', $appunid);
        $this->curl->addRequestData('mod_uniqueid', $modunid);
        $this->curl->addRequestData('title', $title);
        $this->curl->addRequestData('content_fromid', $content_fromid);
        $this->curl->addRequestData('rec', 1);
        if($fromType == 'dingdone')
        {
        	$this->curl->addRequestData('a', 'dingdoneAddNums');
        }
        $ret = $this->curl->request('stats.php');
        return $ret;
    }

}

?>
