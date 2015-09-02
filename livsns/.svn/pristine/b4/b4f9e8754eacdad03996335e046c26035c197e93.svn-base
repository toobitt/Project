<?php
require './global.php';
define ('MOD_UNIQUEID', 'xmlSetting');
class xmlSettingApi extends adminBase
{
    public function __construct() 
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/xml_setting.class.php');
        $this->obj = new xmlSetting();
    }
    
    public function __destruct() 
    {
        parent::__destruct();
    }
    
    public function show()
    {
    	$condition = '';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
    	$data = $this->obj->show($condition . $dataLimit);
    	if(!empty($data))
    	{
	    	foreach($data as $k => $v)
	    	{
	       		$this->addItem($v);
	    	}
    	}
        $this->output();
    }
    
    public function detail()
    {
       $id = intval($this->input['id']);
        if (!$id) {
            $this->output();
        }
        $ret = $this->obj->detail($id);
        if ($ret) {
            $this->addItem($ret);
        }
        $this->output();

    }
    
    public function xml_struct()
    {
	    $data = $this->obj->xml_struct();
	    if(!empty($data))
    	{
	    	foreach($data as $k => $v)
	    	{
	       		$this->addItem($v);
	    	}
    	}
        $this->output();
    }
    public function unknow()
    {}
}

$out = new xmlSettingApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
