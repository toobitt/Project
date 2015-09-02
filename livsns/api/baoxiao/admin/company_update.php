<?php
require './global.php';
define ('MOD_UNIQUEID', 'company');
class companyUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/company.class.php');
        $this->obj = new company();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	
    	if($this->user['group_type'] == 1)
    	{
    		$tmp_bool = $this->obj->checkRight($this->user['org_id']);
    		if(isset($tmp_bool['error']) && $tmp_bool['error'])
    		{
	    		$this->errorOutput($tmp_bool['error']);//COMPANY_HAVE_ONLY_ONE);
    		}
    	}
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput(NO_NAME);
    	}
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    		'tel' => trim($this->input['tel']) ? trim($this->input['tel']) : '',
    		'address' => trim($this->input['address']) ? trim($this->input['address']) : '',
    		'legal' => trim($this->input['legal']) ? trim($this->input['legal']) : '',
    		'token' => hg_generate_user_salt(8),
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);
    	$ret = $this->obj->create($data);
    	if($ret['id'])
    	{
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
			$auth_info = array
			(
				'name' => $ret['name'],
				'fid' => 0,
			);
			$auth_ret = $auth->create_org($auth_info); 
	    	$org_id = $auth_ret['id'];
	    	if($org_id)
	    	{
		    	$this->obj->update_org($org_id,$ret['id']);
	    	}
	    	$ret['org_id'] = $org_id;
    	}
    	$this->addItem($ret);
        $this->output();
    }
    
    public function update()
    {
    	if(empty($this->input['id']))
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput(NO_NAME);
    	}
    	$id = intval($this->input['id']);
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    		'tel' => trim($this->input['tel']) ? trim($this->input['tel']) : '',
    		'address' => trim($this->input['address']) ? trim($this->input['address']) : '',
    		'legal' => trim($this->input['legal']) ? trim($this->input['legal']) : '',
    		'update_time' => TIMENOW,
    	);
    	$this->obj->update($data,$id);
    	$this->resetOrg($id);
        $this->addItem($data);
        $this->output();
    }
    
    public function createOrg()
    {
	    $father_org_id = $this->input['fid'] ? intval($this->input['fid']) : 0;
	    $name = $this->input['name'] ? trim($this->input['name']) : '';
	    if(!$father_org_id)
	    {
		    $this->errorOutput(NO_FID);
	    }
	    if(!$name)
	    {
		    $this->errorOutput(NO_NAME);
	    }
	    include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$auth = new auth();
		$info = array
		(
			'name' => $name,
			'fid' => $father_org_id,
		);
		$data = $auth->create_org($info);
		$this->sysOrg();
        $this->addItem($data);
        $this->output(); 
    }
    
    public function updateOrg()
    {
	    $id = $this->input['id'] ? intval($this->input['id']) : 0;
	    $father_org_id = $this->input['fid'] ? intval($this->input['fid']) : 0;
	    $name = $this->input['name'] ? trim($this->input['name']) : '';
	    if(!$id)
	    {
		    $this->errorOutput(NO_ID);
	    }
	    if(!$father_org_id)
	    {
		    $this->errorOutput(NO_FID);
	    }
	    if(!$name)
	    {
		    $this->errorOutput(NO_NAME);
	    }
	    include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$auth = new auth();
		$info = array
		(
			'name' => $name,
			'fid' => $father_org_id,
			'id' => $id,
		);
		$data = $auth->update_org($info);
		$this->sysOrg();    
        $this->addItem($data);
        $this->output(); 
    }
    
    public function deleteOrg()
    {
	    $id = $this->input['id'] ? intval($this->input['id']) : 0;
	    if(!$id)
	    {
		    $this->errorOutput(NO_ID);
	    }
	    include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$auth = new auth();
		$data = $auth->delete_org($id);
		$this->sysOrg();    
        $this->addItem($data);
        $this->output(); 
    }
    
    public function audit()
    {
       
    }
    
    public function delete()
    {
        $id = $this->input['id'];
        if (!$id) {
            $this->errorOutput(NO_ID);
        }
    }
    
    private function resetOrg($id)
    {
	    if(!empty($id))
	    {
		    $ret = $this->obj->detail($id);
		    if(empty($ret['org_id']))
		    {
				include_once(ROOT_PATH . 'lib/class/auth.class.php');
				$auth = new auth();
				$auth_info = array
				(
					'name' => $ret['name'],
					'fid' => 0,
				);
				$auth_ret = $auth->create_org($auth_info); 
		    	$org_id = $auth_ret['id'];
		    	if($org_id)
		    	{
			    	$this->obj->update_org($org_id,$ret['id']);
		    	}
		    	$ret['org_id'] = $org_id;			    
		    }
		    return $ret;
	    }
    }
    
    public function sysOrg()
    {
	    $data = $this->obj->sysOrg();
        $this->addItem($data);
        $this->output();
    }
    public function unknow(){}
}

$out = new companyUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
