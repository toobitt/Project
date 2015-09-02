<?php
//交换名片接口
define('MOD_UNIQUEID','exchange_cards');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/push_message.class.php');
class exchange_cards extends outerUpdateBase
{
	private $member_mode;
	private $pushMessage;
	public function __construct()
	{
		parent::__construct();
		$this->member_mode = new member_mode();
		$this->pushMessage = new pushMessage();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	
	public function run()
	{
		//判断扫描的人有没有登陆
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		//判断有没有传递被扫描人的用户id
		if(!intval($this->input['other_member_id']))
		{
			$this->errorOutput(NO_OTHER_MEMBER_ID);
		}
		
		//判断扫描的人有没有激活身份，并且取出当前扫描人用户信息
		$_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}
		
		//判断是不是自己和自己交换
		if(intval($_memberInfo['id']) == intval($this->input['other_member_id']))
		{
			$this->errorOutput(CAN_NOT_EXCHANGE_WITH_SELF);
		}
		
		//判断当前用户与被扫描用户是否已经交换过名片
		if($this->member_mode->isHaveExchanged($_memberInfo['id'],$this->input['other_member_id']))
		{
			$this->errorOutput(YOU_HAVE_EXCHANGE_CARDS_WITH_OTHER);
		}
		
		//将信息插入交换表
		$data_a = array(
			'self_exchange_id' 	=> $_memberInfo['id'],
			'other_exchange_id' => $this->input['other_member_id'],
			'create_time'		=> TIMENOW,
		);
		
		$data_b = array(
			'self_exchange_id' 	=> $this->input['other_member_id'],
			'other_exchange_id' => $_memberInfo['id'],
			'create_time'		=> TIMENOW,
		);
		
		/*********开启一个事务***************/       
        $this->db->commit_begin();
        $commit_judge = 1;
        /*********开启一个事务***************/
        
        //先插入针对于自己的交换表
        if(!$this->member_mode->exchange_cards_info($data_a))
        {
        	$commit_judge = 0;
        }
        
        //再插入针对于对方的一张交换表
        $data_b['is_use'] = 1;
		if(!$this->member_mode->exchange_cards_info($data_b))
        {
        	$commit_judge = 0;
        }
        
        //设置双方已交换名片的个数加1
		if(!$this->member_mode->setExchangeNums(array($_memberInfo['id'],$this->input['other_member_id'])))
        {
        	$commit_judge = 0;
        }

        /************判断事务是否执行成功*************/
		if (!$commit_judge) 
		{   
            $this->db->rollback();
            $this->errorOutput(UNKNOW);
        }
    	$this->db->commit_end();
    	/************判断事务是否执行成功*************/
    	
    	/************发送通知告诉被扫描的嘉宾************/
    	$_message = array('uid'	=> $this->input['other_member_id'],'message' => $_memberInfo['name'] . '已和你交换名片','exchange_id' => $_memberInfo['id'],'title' => '交换名片成功');
    	$this->pushMessage->sendMessage($_message);
    	/************发送通知告诉被扫描的嘉宾************/
    	$this->addItem(array('return' => 1));
    	$this->output();
	}
}

$out = new exchange_cards();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 