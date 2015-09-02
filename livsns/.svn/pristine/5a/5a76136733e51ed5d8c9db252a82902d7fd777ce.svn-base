<?php
require './global.php';
define ('MOD_UNIQUEID', 'videoop');
class videoopApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/videoop.class.php');
        $this->obj = new videoop();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function show() {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $ret = $this->obj->getContentList($condition . $dataLimit);
        if (is_array($ret) && count($ret) > 0 ) {
            foreach($ret as $key => $value) {
                if($value['site_info']) {
                    $value['xml_url'] = rtrim($value['site_info']['sub_weburl'], '.') . '.' . rtrim($value['site_info']['weburl'], '/')  . rtrim($value['videoop_xml_dir'], '/') . '/' . $value['videoop_xml_filename'];
                }
                $this->addItem($value);
            }
        }
        $this->output();       
    }
    
    public function detail() {
        $id = intval($this->input['id']);
        if (!$id) {
            $this->output();
        }
        $ret = $this->obj->getContentById($id);
        if ($ret) {
            $this->addItem($ret);
        }
        $this->output();
    }
    
    public function count()
    {
        $condition = $this->get_condition();
        $total = $this->obj->getTotalNum($condition);
        echo json_encode($total);
    }
    
    
    public function siteList()
    {
        if(!class_exists('publishconfig'))
        {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $publishConfig = new publishconfig();
        }
        //获取所有站点
        $hgSites = $publishConfig->get_sites();
        $this->addItem($hgSites);
        $this->output();
    }
    
    public function get_condition()
    {
        $condition = '';
        return $condition;
    }
}

$out = new videoopApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
