<?php
define('MOD_UNIQUEID','our_interface');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/our_interface_mode.php');
class our_interface_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new our_interface_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	
	/**
     * 关于我们接口的更新
     *
     * @access public
     * @param  body_interface关于我们接口的正文
     *
     * @return array
     */
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			'body_interface' =>$this->input['body_interface'],
			'create_time' => TIMENOW,
		    'update_time' => TIMENOW,
		    'user_id'     => $this->user['user_id'],
		    'user_name'   => $this->user['user_name']
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new our_interface_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>