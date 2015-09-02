<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 * ************************************************************************* */
define('WITH_DB', false);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'xs');
require('./global.php');
require('./lib/class/curl.class.php');

class xs extends uiBaseFrm
{

    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function get_keywords()
    {
        if ($content = htmlspecialchars_decode($this->input['content']))
        {
            $num    = intval($this->input['num']);
            if (!$this->settings['App_textsearch'])
            {
                echo json_encode(array('errmsg' => '未安装迅搜'));exit;
            }
            $this->curl = new curl($this->settings['App_textsearch']['host'], $this->settings['App_textsearch']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('a', 'xs_get_keyword');
            $this->curl->addRequestData('text', $content);
            $this->curl->addRequestData('limit', $num);
            $this->curl->addRequestData('bundle_id', 1);
            $this->curl->addRequestData('module_id', 1);
            $result = $this->curl->request('textsearch.php');
            echo json_encode($result);exit;
        }
        else
        {
            echo json_encode(array('errmsg' => '内容为空'));exit;
        }
    }

}

include (ROOT_PATH . 'lib/exec.php');
?>