<?php
define('MOD_UNIQUEID','app_cards');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app_cards_mode.php');
class app_cards_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new app_cards_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function SetAppCards()
	{
	    $card_ids = $this->input['card_id'];
	    $app_id = $this->input['app_id'];
	    
	    if(!$card_ids)
	    {
	        $this->errorOutput();
	    }
	    if(!$app_id)
	    {
	        $this->errorOutput();
	    }
	    
	    if(is_string($card_ids))
	    {
	        $card_ids_arr = explode(",", $card_ids);
	    }
	    
	    $param = array(
	            'card_id' => $card_ids_arr,
	    );
	    $result = $this->mode->SetAppCards($app_id,$param);
	    
	    $this->addItem($result);
	    $this->output();
	}
	
	public function create()
	{
		$data = array(
		        
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
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			/*
				code here;
				key => value
			*/
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

$out = new app_cards_update();
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