<?php
require './global.php';
define ('MOD_UNIQUEID', 'company');
class companyApi extends adminReadBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/company.class.php');
        $this->obj = new company();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function index(){}
    
    public function show() {
    	if(!$this->user['user_id'])
	    {
		    $this->errorOutput(NO_LOGIN);
	    }
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        
        $father_org_id = $this->getTopFatherOrgid($this->user['org_id']);
        if($father_org_id)
        {
	        $condition .= " AND org_id=" . $father_org_id;
	        $data = $this->obj->show($condition . $dataLimit);
	        foreach($data as $key => $value)
	        { 
	        	$this->addItem($value);
	        }       
        }
        else
        {
	        $this->addItem(array());
        }
        $this->output(); 
              
    }
    
    public function getOrgByUserid()
    {
	    $user_id = $this->input['user_id'] ? intval($this->input['user_id']) : 0;
	    $data = array();
	    if($user_id)
	    {
		    include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();			
		    $user_info = $auth->getMemberById($user_id);
		    if($user_info)
		    {
			    $user_info = $user_info[0];
			    $ret = $this->getTopFatherOrgid($user_info['org_id']);
			    if($ret)
			    {
				    $this->addItem($ret);
			    }
		    }
	    }
	    $this->addItem($data);
	    $this->output();
    }
    
    private function getTopFatherOrgid($org_id)
    {
	    if($org_id)
	    {
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();	
			$data = array();
		    $data = $auth->get_one_org($org_id);
		    if($data)
		    {
		    	$data = $data[0];
		    	if($data['fid'])
		    	{
		    		//hg_pre($data);exit;
			    	return $this->getTopFatherOrgid($data['fid']);
		    	}
		    	else//顶级
		    	{
			    	return $data['id'];
		    	}
		    }
	    }
    }
    
    public function getCompanyByToken()
    {
	    $token = trim($this->input['token']) ? trim($this->input['token']):'';
	    if(!$token)
	    {
		    $this->errorOutput(NO_TOKEN);
	    }
	    $data = $this->obj->getCompanyByToken($token);
	    $this->addItem($data);
	    $this->output();
    }
    
    public function get_org()
    {
	    $id = intval($this->input['id']) ? intval($this->input['id']) : 0;
	    $data = array();
	    if($id)
	    {
		    $ret = $this->obj->detail($id);
		    if($ret['org_id'])
		    {
			    include_once(ROOT_PATH . 'lib/class/auth.class.php');
				$auth = new auth();
			    $data = $auth->get_org($ret['org_id']);
		    }
	    }
	    $this->addItem($data);
        $this->output(); 
    }
    
    public function detail() 
    {
    	$ret = array();
    	$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	if(!empty($id))
    	{
    		$ret = $this->obj->detail($id);
    	}
        $this->addItem($ret);
        $this->output();
    }
    
    public function count()
    {
        $condition = $this->get_condition();
        $total = $this->obj->count($condition);
        echo json_encode($total);
    }
    
    public function get_condition()
    {
        $condition = '';
        if(!empty($this->input['key']))
        {
	        $condition .= " AND name='%" . trim($this->input['key']) . "%'";
        }
        return $condition;
    }
}

$out = new companyApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
