<?php
define('MOD_UNIQUEID','domain');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/domain_mode.php');
class domain_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new domain_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	 /**
     * H5域名的创建
     *
     * @access public
     * @param  domain域名
     *
     * @return array
     */
	public function create()
	{
		$domain_reg = mysql_real_escape_string($this->input['domain_reg']);
		$domain_reg = str_replace('&#036;','$',$domain_reg);  //替换'$'转义符
		$data = array(
			'domain' =>$this->input['domain'],
			'domain_reg' =>$domain_reg,
			'is_display' => $this->input['is_display'],	
			'name'       =>$this->input['name'],	
			'create_time' => TIMENOW,
		    'update_time' => TIMENOW,
		    'user_id'     => $this->user['user_id'],
		    'user_name'   => $this->user['user_name']
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
     * H5域名的更新
     *
     * @access public
     * @param  domain域名
     *
     * @return array
     */
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$domain_reg = mysql_real_escape_string($this->input['domain_reg']);
		$domain_reg = str_replace('&#036;','$',$domain_reg);  //替换'$'转义符
		$update_data = array(
			'domain' =>$this->input['domain'],
			'domain_reg' =>$domain_reg,
			'is_display' => $this->input['is_display'],
			'name'       =>$this->input['name'],
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
	
	/**
     * H5域名的删除
     *
     * @access public
     * @param  
     *
     * @return array
     */
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
		
		$status = intval($this->input['status']);
        if(!$status)
        {
            $this->errorOutput(NO_STATUS);
        }

        $data = array(
			'status' 		=> $status,
        );
        
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

$out = new domain_update();
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