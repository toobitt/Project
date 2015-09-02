<?php
define ( 'MOD_UNIQUEID', 'contentaccess_update' ); //模块标识
define('MOD_UNIQUEID','gather');
require_once './global.php';
require_once CUR_CONF_PATH.'lib/content_update_plan.class.php';
class contentaccess_update extends adminUpdateBase {
	public function __construct() {
		parent::__construct ();
		$this->set=new contentUpdatePlan;
	}
	public function __destruct() {
		parent::__destruct ();
	}
	
	public function create() {
	
        $allinfo=array(
        	'url'     => $this->input['urladdress'],
        	'title'   => $this->input['title'],
        	'mk_time' => $this->input['mk_time'],
        	'createtime'       => TIMENOW,
        	'next_time'  => time()+$this->input['mk_time'],	
            'create_user' =>  $this->user['user_name'],
        	'is_open' => $this->input['is_open'],
        	'auto_publish' => $this->input['auto_publish'],
        	'sort_id' => $this->input['column_fid']
         );
        $data=$this->set->create($allinfo);
		$this->addItem($data);
		$this->output();
	}
	
    public function update() {
    	$id=$this->input['id'];
    	$allinfo=array(
    		'title'		 => $this->input['title'],
    		'url' => $this->input['urladdress'],
    		'mk_time'    => $this->input['mk_time'],
    	    'create_user'=> $this->user['user_name'],
    		'is_open' => $this->input['is_open'],
    		'auto_publish' => $this->input['auto_publish'],
    	    'sort_id' => $this->input['column_fid']
    	);
    	
    	$this->set->update($allinfo,$id);
     	$this->addItem($data);
     	$this->output(); 
	}
	
	public function delete() {
		$id=$this->input['id'];
    	$sql="delete from ".DB_PREFIX."plan where id in ({$id})";
    	$data=$this->db->query($sql);
    	$this->addItem($id);
        $this->output(); 
	}

	public function audit() {
	}
	
	public function sort() {
	}
	
	public function publish() {
	}
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new contentaccess_update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>