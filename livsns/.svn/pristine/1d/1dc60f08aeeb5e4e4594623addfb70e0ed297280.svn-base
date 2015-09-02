<?php
define('MOD_UNIQUEID','domain_audit');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/domain_audit_mode.php');
require_once(CUR_CONF_PATH . 'lib/approved_domain_mode.php');
class domain_audit_update extends adminUpdateBase
{
	private $mode;
	private $approved;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new domain_audit_mode();
		$this->approved = new approved_domain_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			/*
				code here;
				key => value
			*/
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	/**
     * H5自定义域名的状态更新
     *
     * @access public
     * @param  web_url自定义域名
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
			'web_url' => $this->input['web_url'],
			'status' => $this->input['status']
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
			$condition = " AND domain='".$ret['data']['web_url']."'";
			$res = $this->approved->show($condition);
			if($ret['status'] == 2)
			{
				$status = 1;
			}
			else 
			{
				$status = 0;
			}
			if(!$res)
			{
				$r = $this->approved->create(array(
						'domain' => $ret['data']['web_url'],
						'status' => $status,
						'create_time' => TIMENOW,
						'user_id'=>$this->user['user_id'],
						'user_name'=>$this->user['user_name'],
				));
			}
			else 
			{
				$this->approved->update($res[0]['id'],array(
						'status' => $status,
						'update_time' => TIMENOW,
						'user_id'=>$this->user['user_id'],
						'user_name'=>$this->user['user_name'],
				));
			}	
			
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

$out = new domain_audit_update();
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