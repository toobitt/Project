<?php
define('MOD_UNIQUEID','market_member');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/market_member_mode.php');
class market_member_update extends outerUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new market_member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	/***************************************扩展操作*****************************************/
	//绑定会员（对手机用户提交的信息与库里面的信息进行比较，一致则绑定成功）
	public function bind()
	{
		//用户的会员id(用户传递ACCESS_TOKEN)
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NOT_LOGIN);
		}

		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}

		if(!$this->input['card_number'])
		{
			$this->errorOutput(NO_CARD_NUMBER);
		}
		
		if(!$this->input['phone_number'])
		{
			$this->errorOutput(NO_PHONE_NUMBER);
		}

		$member_data = array(
			'phone_number' 	=> $this->input['phone_number'],
			'card_number' 	=> $this->input['card_number'],
			'market_id' 	=> $this->input['market_id'],
		);
		$ret = $this->mode->bind($member_data,$this->user['user_id']);
		switch ($ret)
		{
			case 1:$this->errorOutput(NO_USER_INFO);break;
			case 2:$this->errorOutput(THIS_USER_ALREADY_EXISTS);break;
			case 3:$this->errorOutput(ALREADY_BIND);break;
			case 4:$this->errorOutput(THIS_USER_NOT_EXISTS);break;
		}

		$this->addItem($ret);
		$this->output();
	}
	
	//判断有没有绑定会员(需要制定是哪个超市的会员)
	public function isBind()
	{
		//用户的会员id(用户传递ACCESS_TOKEN)
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NOT_LOGIN);
		}
		
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}

		//通过用户中心的会员id判断有没有绑定超时里面的会员
		$ret = $this->mode->isBind($this->user['user_id'],$this->input['market_id']);
		if(!$ret)
		{
			$this->errorOutput(YOU_HAVE_NOT_BIND);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	//解绑定
	public function unbind()
	{
		//用户的会员id(用户传递ACCESS_TOKEN)
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NOT_LOGIN);
		}
		
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}

		$ret = $this->mode->unbindMember($this->user['user_id'],$this->input['market_id']);
		if($ret)
		{
			$this->addItem(array('return' => 1));
			$this->output();
		}
		else
		{
			$this->errorOutput(UNBIND_ERROR);
		}
	}

	/***************************************扩展操作*****************************************/
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new market_member_update();
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