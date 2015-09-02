<?php
require('./global.php');
define ('MOD_UNIQUEID', 'churu_update');
class churu_updateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include('../lib/churu.class.php');
		$this->obj = new churu();
	}
	public function __destruct() {
        parent::__destruct();
    }
    public function index()
    {
	    
    }
    public function create()
    {
	    $this->get_condition();
	    $uid = intval($this->input['uid']) ? intval($this->input['uid']) : '';
	    if(!$uid)
	    {
		    $this->errorOutput(NO_UID);
	    }
	    $reason = trim($this->input['reason']) ? trim($this->input['reason']) : '';
	    if(!$reason)
	    {
		    $this->errorOutput(NO_REASON);
	    }
	    $tip = trim($this->input['tip']) ? trim($this->input['tip']) : '';
	    $type = trim($this->input['type']) ? trim($this->input['type']) : 'waichu';
	    $data = array(
	    	'uid' => $uid,
	    	'reason' => $reason,
	    	'start_time' => TIMENOW,
	    	'tip' => $tip,
	    	'type' => $type,
	    );
	    $data = $this->obj->create($data);
		if($data)
		{
			$this->addItem($data);
			$this->output();
		}; 
    }
    public function update()
    {
	    $id = intval($this->input['id']) ? intval($this->input['id']) : '';
	    if(!$id)
	    {
		    $this->errorOutput(NO_ID);
	    }
	    $time = trim($this->input['time']) ? trim($this->input['time']) : '';
	    $data = $this->obj->update($id,$time);
	    if($data)
		{
			$this->addItem($data);
			$this->output();
		};
    }
    public function delete()
    {
	    $id = intval($this->input['id']) ? intval($this->input['id']) : '';
	    if(!$id)
	    {
		    $this->errorOutput(NO_ID);
	    }
	    $data = $this->obj->delete($id);
	    if($data)
		{
			$this->addItem($data);
			$this->output();
		};
    }
    public function audit()
    {
	    
    }
    public function sort()
    {
	    
    }
    public function publish()
    {
	    
    }
    public function get_condition()
    {	
    	$condition = '';
	    if($this->input['id'])
	    {
		    $condition = $this->input['id']; 
	    }
    }
}
$out = new churu_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'create';
}
$out->$action();
?>