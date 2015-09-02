<?php
/*******************************************************************
 * filename :member_medal_update.php
 * 申请勋章
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','member_medal');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_medal.class.php';
class member_medalUpdateApi extends appCommonFrm
{
	private $Members;//核心类创建使用
	private $membersql;//核心sql类创建使用
	private $member_id;//会员id
	private $medalid = array();//勋章id
	private $medal_info;//勋章信息
	private $status = 0;//默认不报错
	private $applysucceed = 0;//是否进行勋章申请处理流程
	private $medal_infomessage;//返回信息
	private $applysucceedlog = 0;//是否插入勋章申请日志
	private $expiration = 0;//勋章有效期
	
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 *
	 * 勋章申请入口
	 */
	public function apply_medal()
	{
		$this->get_condition();//获取申请需要的条件
		$this->medal_info = $this->Members->get_medal($this->medalid,'name,used_num,limit_num,expiration,type,start_date,end_date',1,true,false);//获取勋章数据(参数)
		$this->apply_Judge();//勋章申请条件判断
		$this->apply_process();//勋章申请处理
		$this->apply_error();//勋章申请错误处理,并抛出异常
		$this->addItem_withkey('status', 0);//0为代表申请成功
		$this->addItem_withkey('message',$this->medal_infomessage);//返回消息
		$this->output();

	}

	/**
	 *
	 * 获取申请需要的条件
	 */
	protected function get_condition()
	{
		if($this->user['user_id']||$this->input['member_id'])
		{
			$this->member_id=$this->user['user_id']?intval($this->user['user_id']):($this->input['member_id']?intval($this->input['member_id']):0);
		}
		if($this->input['medalid'])
		{
			$this->medalid = array($this->input['medalid']);
		}
	}
	/**
	 *
	 * 勋章申请错误处理
	 */
	private function apply_error()
	{
		switch ($this->status)
		{
			case 1:$this->errorOutput(NO_MEMBER_ID);break;
			case 2:$this->errorOutput(NO_MEDAL_ID);break;
			case 3:$this->errorOutput(NO_APPLY_MEDAL);break;
			case 4:$this->errorOutput(USED_APPLY_MEDAL);break;
			case 5:$this->errorOutput(STOP_APPLY_MEDAL);break;
			case 6:$this->errorOutput(LIMIT_APPLY_MEDAL);break;
			case 7:$this->errorOutput(NO_START_APPLY_MEDAL);break;
			case 8:$this->errorOutput(APPLY_MEDAL_TYPE_ERROR);break;
			case 9:$this->errorOutput(NO_MEMBER);break;	
			default:break;
		}
	}
	/**
	 *
	 * 勋章申请是否满足基本条件处理 ...
	 */
	private function apply_Judge()
	{
		if (empty($this->member_id))
		{			
			$this->status = 1;//抱歉,您未提交会员id
		}
		elseif (!$this->Members->checkuser($this->member_id))
		{
				$this->status = 9;
		}
		elseif (empty($this->medalid))
		{
			$this->status = 2;//抱歉,您未提交需申请的勋章id
		}
		elseif(empty($this->medal_info)) {
			$this->status = 3;//抱歉，此勋章不可申请.
		}
		elseif($this->Members->get_member_medal_count($this->member_id, $this->medalid)) {
			$this->status = 4;//抱歉，您已申请过或领取过此勋章，请不要重复申请或领取.
		}
		elseif ((($this->medal_info['end_date']<=TIMENOW)&&!empty($this->medal_info['end_date']))&&$this->medal_info['type']>0)
		{
			$this->status = 5;//抱歉,您申请的勋章已经停止颁发.
		}
		elseif(($this->medal_info['used_num']>=$this->medal_info['limit_num']&&!empty($this->medal_info['limit_num']))&&$this->medal_info['type']>0){
			$this->status = 6;//抱歉,您申请的勋章已颁发完毕.
		}
		elseif($this->medal_info['start_date']>=TIMENOW&&!empty($this->medal_info['start_date'])&&$this->medal_info['type']>0){
			$this->status = 7;//抱歉,您申请的勋章还未到颁发时间.
		}
		elseif($this->member_id&&$this->medalid) {
			$this->applysucceed = 1;//允许申请
		}
	}
	/**
	 *
	 * 勋章申请处理流程 ...
	 */
	private function apply_process()
	{
		if($this->applysucceed) {
			$this->expiration = empty($this->medal_info['expiration'])? 0 : TIMENOW + $this->medal_info['expiration'] * 86400;
			$function = 'apply_process_medal'.$this->medal_info['type'];
			if(method_exists($this, $function))
			{
				$this->$function();
			}
			/**以后增加更多勋章类型,添加apply_process_medal勋章类型和按规则添加处理方法即可**/
			if($this->applysucceedlog)
			{
				$data=array(
		    		'member_id' => $this->member_id,
		   			'medalid' => $this->medalid,
		    		'type' => $this->medal_info['type'],
		    		'dateline' => TIMENOW,
		    		'expiration' => $this->expiration,
		    		'status' => ($this->expiration ? 1 : 0),
				);
				$this->membersql->create('medallog', $data);
				$this->apply_count();
			}
		}
	}
	/**
	 *
	 * 勋章类型:人工授权 处理函数...
	 */
	private function apply_process_medal0()
	{
		empty($this->medal_info['type'])&&($this->status = 8);
	}
	/**
	 *
	 * 勋章类型:自主申请 处理函数...
	 */
	private function apply_process_medal1()
	{
		if($this->medal_info['type'] == 1) {
			$this->membersql->create('member_medal', array('member_id' => $this->member_id, 'medalid' => $this->medalid,'expiration'=>$expiration),false,'id',true);
			$this->medal_infomessage = '恭喜您获得勋章'.$this->medal_info['name'];
			$this->applysucceedlog=1;
		}
	}
	/**
	 *
	 * 勋章类型:人工审核 处理函数 ...
	 */
	private function apply_process_medal2()
	{
		if($this->medal_info['type'] == 2)
		{
			if($this->Members->get_member_medallog_count($this->member_id, $this->medalid)) {
				$this->status = 4;
			}
			else {
				$this->medal_infomessage = '勋章'.$this->medal_info['name'].'申请成功,请等待管理员审核。';
				$this->applysucceedlog=1;
			}
		}
	}
	
	private function apply_count()
	{
		$member_medal = new medal();
		$member_medal->update_used_num(array($this->medalid));
	}
	
	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{

		$this->errorOutput("此方法不存在");
	}


}

$out = new member_medalUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>