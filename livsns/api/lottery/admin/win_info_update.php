<?php
define('MOD_UNIQUEID','win_info');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/win_info_mode.php');
class win_info_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'] || count(explode(MOD_UNIQUEID, $this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])) <= 1)
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$this->mode = new win_info_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		
		$data_prms['_action'] = 'audit';
		$this->verify_content_prms($data_prms);
		
		$member_id = intval($this->input['member_id']);
		$prize_id = intval($this->input['prize_id']);
		
		if(!$member_id)
		{
			$arr['error_msg'] = '会员id不正确'; 
			//$this->errorOutput('会员id不存在');
			$this->addItem($arr);
			$this->output();
		}
		
		if(!$prize_id)
		{
			$this->errorOutput('奖品id不存在');
		}
		
		$sql = "SELECT prize_win,prize_num FROM " . DB_PREFIX . "prize WHERE id = " . $prize_id;
		$res = $this->db->query_first($sql);
		
		if($res['prize_win'] >= $res['prize_num'])
		{
			$this->errorOutput('奖品数量不够啦');
		}
		$lottery_id = intval($this->input['lottery_id']);
		
		if(!$lottery_id)
		{
			$this->errorOutput('抽奖id不存在');
		}
		$data = array(
			'lottery_id'	=> $lottery_id,
			'member_id'		=> $member_id,
			'prize_id'		=> $prize_id,
			'create_time'	=> TIMENOW,
			'confirm'		=> 1,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$sql = "UPDATE " . DB_PREFIX . "prize SET prize_win = prize_win + 1 WHERE id = " . $prize_id . " AND prize_win < prize_num";
			$this->db->query($sql);
			
			$data['id'] = $vid;
			$this->addLogs('创建',$data,'','创建' . $vid);
			$this->addItem($data);
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
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新',$ret,'','更新' . $this->input['id']);
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
		$data_prms['_action'] = 'audit';
		$this->verify_content_prms($data_prms);
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除',$ret,'','删除' . $this->input['id']);
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
		
		$this->verify_content_prms();
		
		$audit = intval($this->input['audit']);
		
		$ret = $this->mode->audit($this->input['id'],$audit);
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	
	//发放状态
	public function provide_status()
	{
		$id = intval($this->input['id']);
		
		$audit = intval($this->input['provide_status']);
		switch ($audit)
		{
			case 0:$status = 1;$audit_status = '已发放';break;
			case 1:$status = 0;$audit_status = '未发放';break;
		}
		
		$sql = "UPDATE " .DB_PREFIX. "win_info SET provide_status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		$arr = array('provide_status' => $status,'id' => $id);
		
		$this->addItem($arr);
		$this->output();
	}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new win_info_update();
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