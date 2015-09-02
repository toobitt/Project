<?php
require './global.php';
define ('MOD_UNIQUEID', 'user');
class userApi extends adminReadBase
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
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $this->addItem($value);
        $this->output();  
    }
    
    public function getUser()
    {
    	//$id = 0;
		//$this->obj->detail($id);
		if($this->user['org_id'])
		{	
			$org_id = $this->user['org_id'];
		}
		else
		{
			$org_id = $this->input['org_id'] ? trim($this->input['org_id']) : '';
		}
		if($org_id)
		{
			$ret = array();
			$ret = $this->obj->checkExists($org_id);
			$user_info = array();
			if($ret)
			{
				include_once(ROOT_PATH . 'lib/class/auth.class.php');
				$auth = new auth();
				foreach($ret as $k => $v)
				{
					$data = $auth->getMemberByOrg($v);
					if($data)
					{
						foreach($data as $kk => $vv)
						{
							$user_info[] = $vv;
						}
						
					}
				}				
			}
			$this->addItem($user_info);	
		}
		else
		{
			$this->addItem(array());
		}		
        $this->output();	    
    }
    
    public function checkOrg()
    {
	    if($this->user['org_id'])
		{	
			$org_id = $this->user['org_id'];
		}
		else
		{
			$org_id = $this->input['org_id'] ? trim($this->input['org_id']) : '';
		}
		if($org_id)
		{
			$ret = array();
			$ret = $this->obj->checkExists($org_id);
			$this->addItem($ret);			
		}
		else
		{
			$this->addItem(array());
		}		
        $this->output();	
    }
    
    public function getMemberById()
    {
	    $id = $this->input['id'] ? trim($this->input['id']) : 0;
	    $data = array();
	    if(!empty($id))
	    {	    	
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $data = $auth->getMemberById($id);
	    }
	    $this->addItem($data);
        $this->output(); 
    }
    
    public function getMemberByName()
    {
	    $user_name = $this->input['user_name'] ? trim($this->input['user_name']) : 0;
	    $data = array();
	    if(!empty($user_name))
	    {	    	
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $data = $auth->getMemberByName($user_name);
	    }
	    $this->addItem($data);
        $this->output(); 
    }
    
    public function getMemberByOrg()
    {
	    $org_id = $this->input['org_id'] ? intval($this->input['org_id']) : 0;
	    $data = array();
	    if(!empty($org_id))
	    {
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $data = $auth->getMemberByOrg($org_id);
	    }
	    $this->addItem($data);
        $this->output(); 
    }
    
    public function getMembersByOrg()
    {   
		$re =  $this-> get_org();
	    $org_id = $this->input['org_id'] ? intval($this->input['org_id']) : 0;
       
        foreach($re as $key => $vo ){
		    if($org_id == $vo['id'])
		    {
				$childs = $vo['childs'];
			}
		}
	    $fid = explode(",",$childs);
	    $data = array();
        if(!empty($org_id))
	    {
		    foreach($fid as $k => $v)
			{   
			    include_once(ROOT_PATH . 'lib/class/auth.class.php');
		        $auth = new auth();
				$data = $auth->getMemberByOrg($v);
			    if($data)
				{
					foreach($data as $kk => $vv)
					{
						$user_info[] = $vv;
					}	
				}	
			}	
	    }
	    $this->addItem($user_info);
        $this->output(); 
    }
    
    public function get_org()
    {
	    $id = 1;
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
	   return $data;
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
    
    public function getOrgById()
    {
	    if($this->user['org_id'])
		{	
			$org_id = $this->user['org_id'];
		}
		else
		{
			$org_id = $this->input['org_id'] ? trim($this->input['org_id']) : '';
		}
	    $data = array();
	    if(!empty($org_id))
	    {
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $data = $auth->get_one_org($org_id);
	    }
	    $this->addItem($data);
        $this->output(); 
	    
    }
    
    
     public function getFatherOrgById()
     {  
        if($this->user['org_id'])
		{	
		   $org_id = $this->user['org_id'];	
		}
		else
		{
			$org_id = $this->input['org_id'] ? trim($this->input['org_id']) : '';
		}
        $data = array();
	    
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $data = $auth->get_one_org($org_id);
		    $id =$data [0]['fid'];
		    $data = $auth->get_one_org($id);
		    $id2 =$data [0]['fid'];
		    if($id2 == 0){
			    $fid =array($id);
		    }else{
		        $fid =array($id,$id2);
		    }
		    if($fid)
			{
				include_once(ROOT_PATH . 'lib/class/auth.class.php');
				$auth = new auth();
				foreach($fid as $k => $v)
				{
					$data = $auth->getMemberByOrg($v);
					if($data)
					{
						foreach($data as $kk => $vv)
						{
							$user_info[] = $vv;
						}	
					}
				}				
			}
	    $this->addItem($user_info);
        $this->output();    
    }

    public function count()
    {
        $condition = $this->get_condition();
        $total = array();
        echo json_encode($total);
    }
    
    public function get_condition()
    {
        $condition = '';
        return $condition;
    }
}

$out = new userApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
