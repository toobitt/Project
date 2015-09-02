<?php
require_once CUR_CONF_PATH . 'core/membersql.core.php';
class members extends InitFrm
{
	private $membersql;
	private $mMemberId = 0;
	public function __construct()
	{
		parent::__construct();
		$this->membersql = new membersql();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *  根据积分检测用户所属组
	 */
	public function checkgroup_credits($credits)
	{
		$sql='SELECT id as gid,name as groupname,starnum,usernamecolor,icon as groupicon  FROM '.DB_PREFIX.'group WHERE isupdate = 0 AND creditshigher<='.intval($credits).' AND creditslower>'.intval($credits);
		$newgroup=$this->db->query_first($sql);
		if(!empty($newgroup))
		{
			$newgroup['groupexpiry']=0;
		}
		else
		{
			$newgroup=false;
		}
		return $newgroup;
	}
	/**
	 *  根据经验检测用户所属等级
	 */
	public function checkgrade_credits($credits)
	{
		return $this->getGrade('AND creditshigher<='.$credits.' AND creditslower>'.$credits, 'id as gradeid,name as graname,icon as graicon,digital', false);
	}
	/**
	 *
	 * 获取经验(可升级)类型字段.
	 */
	public function get_grade_credits_type($type = 0)
	{
		if(!$type)
		{
			$field = 'db_field';
		}
		else
		{
			$field = 'title,db_field';
		}
		$type_info=$this->db->query_first('SELECT '.$field.' FROM '.DB_PREFIX.'credit_type WHERE 1 AND is_on=1 AND is_update=1');
		if($type_info)
		{
			return !$type?$type_info['db_field']:$type_info;
		}
		return FALSE;
	}

	/**
	 *
	 * 获取消费(可交易)积分类型字段.
	 */
	public function get_trans_credits_type()
	{
		$type_info=$this->db->query_first('SELECT db_field FROM '.DB_PREFIX.'credit_type WHERE 1 AND is_on=1 AND is_trans=1');
		if($type_info)
		{
			return $type_info['db_field'];
		}
		return FALSE;
	}
	/**
	 *
	 * 获取平台名...
	 * @param string $type 平台标识
	 */
	public function get_platform_name($type)
	{
		$SystemMemberType = array();
		$platform = array();
		$_platform = array();
		$field = 'name,status';
		if($this->settings['SystemMemberType']&&is_array($this->settings['SystemMemberType'])){
				$SystemMemberType = $this->settings['SystemMemberType'];
		}																												
		if(array_key_exists($type, $SystemMemberType))
		{
			$platform = $SystemMemberType[$type];
			$field = 'status';
		}
		if(!$platform || !isset($platform[$field]))
		{
			$sql='SELECT '.$field.' FROM '.DB_PREFIX.'member_platform WHERE mark=\''.trim(urldecode($type)).'\'';
			$_platform = $this->db->query_first($sql);
			if($platform&&$_platform)
			{
				$platform[$field] = $_platform[$field];
			}
			elseif ($_platform)
			{
			     $platform = $_platform;
			}
		}
		return $platform?$platform:array();
	}

	/**
	 * 用户组设置函数
	 */
	public function updategroup_id($memberid,$gid,$groupexpiry)
	{
		if(empty($gid))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "member SET gid=".intval($gid).",groupexpiry=".intval($groupexpiry);
		$sql .= " WHERE member_id = " . $memberid;
		return $this->db->query($sql);
	}

	/**
	 * 用户等级设置函数
	 */
	public function updategrade_id($memberid,$gradeid)
	{
		if(empty($gradeid))
		{
			return false;
		}
		return $this->db->query("UPDATE " . DB_PREFIX . "member SET gradeid=".intval($gradeid)." WHERE member_id = " . $memberid);
	}

	/**
	 * 积分规则日志处理,此函数作用是为以后增加积分类型,可以处理更多的积分.
	 */
	function addlogarr($logarr, $rule, $coef,$credit_type) {
		if(is_array($credit_type)&&$credit_type)
		{
			foreach ($credit_type as $v)
			{
				if($rule[$v]) {
					$extcredit = intval($rule[$v]) * $coef;
					$logarr[$v] = $extcredit;
				}
			}
		}
		return $logarr;
	}
	/**
	 *
	 * 积分规则处理函数,积分规则入口.
	 * @param String $operation 积分规则操作名，必传
	 * @param INT $uid 会员id
	 * @param int $coef 可以说是积分倍数吧.比如说这里值传2的话.积分当然是增加2倍了,当然同时积分操作次数也加2.嘿嘿...
	 * @param int $update 控制是否更新积分,积分日志等.
	 * @param String $appid 应用标识,周期级别设置为应用级或者自定义应用积分规则使用，为了保持功能完整性和可选性，建议都传
	 * @param String $modid 模块标识 周期级别设置为模块级则必传，其它可选
	 * @param $sid intval 分类id 周期级别设置为分类级则必传，其它可选
	 * @param $cid intval 内容id 周期级别设置为内容级则必传，其它可选
	 */
	public function credits_rule($operation, $uid,$coef = 1,$update=1,$appid='',$modid ='',$sid = 0,$cid = 0)
	{
		if(empty($operation))
		{
			return -1;//未传操作key;
		}
		if(empty($uid))
		{
			return 0;//未传会员id;
		}
		elseif ((is_string($uid)&&(stripos($uid, ',')!==false))||is_array($uid))
		{
			return -2;//会员id格式不正确,仅支持单个会员id
		}

		$gid=$this->uid_to_gid($uid);
		if($gid===false)//防止用户组为0的情况
		{
			return -3;//无此会员或者会员已被删除
		}
		$rule=$this->creditRuleProcess($operation,$appid,$gid);
		$cycleLevel = $this->checkRulesCycleLevel($rule,trim($appid),trim($modid),intval($sid),intval($cid));
		if(is_numeric($cycleLevel)&&$cycleLevel<0)//如果返回为数字则出错
		{
			return $cycleLevel;
		}
		$updatecredit = false;
		$enabled = false;
		if($rule&&$rule['opened'])
		{
			$credit_type=array();
			$credit_type_info=$this->get_credit_type('db_field,title,img');//获取已启用的积分类型
			$credit_type=array_keys($credit_type_info);
			$member_credit = $this->membercredit($uid,false,false);
			if($credit_type&&is_array($credit_type))
			{
				foreach($credit_type as $v)
				{
					if(isset($rule[$v]))
					{
						if(($member_credit[$v]>=abs_num($rule[$v])&&$rule[$v]<0)||($rule[$v]>=0))
						{
							$enabled=true;
						}
						elseif($member_credit[$v]<abs_num($rule[$v])&&$rule[$v]<0)//如果积分规则是扣除积分，那么判断积分是否充足
						{
							$enabled=false;
							return -6;//可执行积分不足
						}
					}
				}
			}else
			{
				return -5;//无可用积分类型
			}

		}
		elseif($rule&&!$rule['opened']) {
			return -7;//此规则未启用
		}
		else {
			return -4;//无此积分规则
		}
		if($enabled)
		{
			$rulelog=array();
			$appid =$cycleLevel['appid']?$cycleLevel['appid']:'';
			//$appids = $rule['appids'] ? explode(',', $rule['appids']) : array();
			//$appid = in_array($appid, $appids) ? trim($appid) : '';
			$modid =$cycleLevel['modid']?$cycleLevel['modid']:'';
			$sid = $cycleLevel['sid']?$cycleLevel['sid']:0;
			$cid = $cycleLevel['cid']?$cycleLevel['cid']:0;
			$rulelog=$this->getcreditrulelog($rule['id'],$uid,$appid,$modid,$sid,$cid);
			if($rule['rewardnum'] && $rule['rewardnum'] < $coef) {
				$coef = $rule['rewardnum'];
			}
			if(empty($rulelog))
			{
				$logarr=array(
				'uid'=>$uid,
				'rid'=>$rule['id'],
				'appid'=>$appid,//应用标识
				'modid'=>$modid,//模块标识
				'sid'=>$sid,//分类id
				'cid'=>$cid,//内容id
				'total'=>$coef,
				'cyclenum'=>$coef,
				'dateline' => TIMENOW
				);
				if(in_array($rule['cycletype'], array(2,3))) {
					$logarr['starttime'] = TIMENOW;
				}
				$logarr = $this->addlogarr($logarr, $rule, $coef,$credit_type);
				if($update)
				{
					$ret_log=$this->membersql->create('credit_rules_log', $logarr);
				}
				if($ret_log)//如果成功则把控制更新积分变量变为true
				{
					$updatecredit=true;
				}
			}
			else {
					
				$newcycle = false;
				$logarr = array();
				switch($rule['cycletype']) {
					case 0:
						break;
					case 1:
					case 4:
						if($rule['cycletype'] == 1) {
							$today = strtotime(date('Y-m-d',TIMENOW));
							if($rulelog['dateline'] < $today && $rule['rewardnum']) {
								$rulelog['cyclenum'] =  0;
								$newcycle = true;
							}
						}
						if(empty($rule['rewardnum']) || $rulelog['cyclenum'] < $rule['rewardnum']) {
							if($rule['rewardnum']) {
								$remain = $rule['rewardnum'] - $rulelog['cyclenum'];
								if($remain < $coef) {
									$coef = $remain;
								}
							}
							$cyclenunm = $newcycle ? $coef : $rulelog['cyclenum']+$coef;
							$logarr = array(
								'cyclenum' => $cyclenunm,
								'total' => $rulelog['total']+$coef,
								'dateline' => TIMENOW
							);
							$updatecredit = true;
						}
						break;

					case 2:
					case 3:
						$nextcycle = 0;
						if($rulelog['starttime']) {
							if($rule['cycletype'] == 2) {
								$start = strtotime(date('Y-m-d H:00:00', $rulelog['starttime']));
								$nextcycle = $start+$rule['cycletime']*3600;
							} else {
								$nextcycle = $rulelog['starttime']+$rule['cycletime']*60;
							}
						}
						if(TIMENOW <= $nextcycle && $rulelog['cyclenum'] < $rule['rewardnum'])
						{
							if($rule['rewardnum']) {
								$remain = $rule['rewardnum'] - $rulelog['cyclenum'];
								if($remain < $coef) {
									$coef = $remain;
								}
							}
							$logarr = array(
								'cyclenum' => $rulelog['cyclenum']+$coef,
								'total' => $rulelog['total']+$coef,
								'dateline' => TIMENOW
							);
							$updatecredit = true;
						} elseif(TIMENOW >= $nextcycle) {
							$newcycle = true;
							$logarr = array(
								'cyclenum' => $coef,
								'total' => $rulelog['total']+$coef,
								'dateline' => TIMENOW,
								'starttime' =>TIMENOW,
							);
							$updatecredit = true;
						}
						break;
				}
				if($update&&$logarr)
				{
					$logarr = $this->addlogarr($logarr, $rule, $coef,$credit_type);
					$this->membersql->update('credit_rules_log', $logarr, array('uid'=>intval($uid),'rid'=>$rulelog['rid'],'appid'=>$rulelog['appid'],'gid'=>$rulelog['gid']));
				}
			}
		}
		$rule['member_info'] = array();
		if ($enabled&&$updatecredit&&$update)
		{
			$credit_log=array(
		'app_uniqueid'=>APP_UNIQUEID,
		'mod_uniqueid'=>MOD_UNIQUEID,
		'action'=>'credit_rules',
		'method'=>'credit_rules',
		'relatedid'=>$rule['id'],
		'title'=>'积分策略',
		'remark'=> $rule['rname'],
			);
			$members_info = $this->credits($rule,$uid,$coef,true,true,true,$credit_type,array(),$credit_log);
			$rule['member_info'] = $members_info;
		}
		$rule['updatecredit'] = $updatecredit;
		$rule['credit_type'] = $credit_type_info;
		$rule['copywriting_credit']=copywriting_credit(array($rule),false,$credit_type_info);
		return $rule;
	}
	/**
	 *
	 * 检测规则等级 ...
	 * @param unknown_type $appid
	 * @param unknown_type $modid
	 * @param unknown_type $sid
	 * @param unknown_type $cid
	 */
	public function checkRulesCycleLevel($rule,$appid = '',$modid = '',$sid = 0,$cid = 0)
	{
		$Params = array(
					'appid'=>'',
					'modid'=>'',
					'sid'=>0,
					'cid'=>0,
		);
		if(empty($appid)){
			if($rule['cyclelevel']>0){
				return -8;
			}
		}
		elseif($rule['cyclelevel']>0){
			$Params['appid'] = $appid;
		}

		if(empty($modid)){
			if($rule['cyclelevel']>1){
				return -9;
			}
		}
		elseif($rule['cyclelevel']>1){
			$Params['modid'] = $modid;
		}

		if(empty($sid))
		{
			if($rule['cyclelevel']<4&&$rule['cyclelevel']>2){
				return -10;
			}
		}
		elseif($rule['cyclelevel']>2){
			$Params['sid'] = (int)$sid;
		}

		if(empty($cid))
		{
			if($rule['cyclelevel']>3){
				return -11;
			}
		}
		elseif($rule['cyclelevel']>3){
			$Params['cid'] = (int)$cid;
		}
		return $Params;
	}
	/**
	 *
	 * 获取积分规则 ...
	 * @param unknown_type $operation
	 * @param unknown_type $isBatch
	 */
	public function getcreditrule($operation,$isBatch = false)
	{
		$arrOperation = array();
		$strOperation = '';
		$where = '';
		if(empty($operation)){
			return array();
		}
		if(!is_array($operation)){
			$operation = trim(urldecode($operation));
			$arrOperation = explode(',',$operation);//转为数组方便字符串转换
		}
		else{
			$arrOperation = $operation;
		}
		if(!$isBatch&&count($arrOperation)>1)
		{
			return array();//未开启批量查找，所以禁止多参数
		}
		if($arrOperation&&is_array($arrOperation))
		{
			$strOperation = trim("'".implode("','", $arrOperation )."'");
			if(count($arrOperation)>1)
			{
				$where=' AND cr.operation IN( '.$strOperation.')';
			}
			else{
				$where=' AND cr.operation = '.$strOperation;
			}
		}
		$sql='SELECT * FROM '.DB_PREFIX.'credit_rules cr WHERE 1'.$where;
		$query = $this->db->query($sql);
		$rule = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['credit1'] = intval($row['credit1']);
			$row['credit2'] = intval($row['credit2']);
			$rule[$row['operation']] = $row;
		}
		return $isBatch?$rule:$rule[trim($strOperation,'\'')];
	}
	/**
	 * 积分规则调用处理
	 */
	public function creditRuleProcess($operation,$appid='',$gid=0)
	{
		//用户组自定义积分规则功能,仅仅只有自定义积分设置,设置能多获得多少积分,或者少获得多少积分,此自定义积分,如果有相加在应用自定义积分规则或者全局规则上所获取的额外积分.
		//应用积分规则自定义,则可以定义不同的执行方式,比如周期,次数.同时可以自定义积分,则优先级以应用自定义规则为先.
		$iscustom = 0;//是否允许自定义积分规则变量
		$appids = '';//已定义积分规则的应用标识变量，多个以逗号分割
		$gids ='';//已定义积分规则的用户组id变量，多个以逗号分割
		$rule = array();//积分规则
		$row =array();//数据库查询临时变量
		if(empty($operation)) {
			return $rule;
		}
		$rule = $this->getcreditrule($operation);
		if($rule){
			$appids=$rule['appids'];
			$gids=$rule['gids'];
			$iscustom=$rule['iscustom'];
		}
		if($appids&&$appid&&$iscustom)//允许自定义,才调用自定义规则.
		{
			$appids=explode(',', $appids);
			if(in_array($appid, $appids))
			{
				$grules_arr = $this->getDiyRulesInfo($appid);
				if(isset($grules_arr[$operation])) {
					if(is_array($rule))
					{
						foreach ($rule as $k => $v)
						{
							$rule[$k] = isset($grules_arr[$operation][$k])?$grules_arr[$operation][$k]:$rule[$k];
						}
					}
				}
			}
		}
		if($gids&&$gid&&$iscustom)//允许自定义,才调用自定义规则,可用.
		{
			$gids=explode(',', $gids);
			if(in_array($gid, $gids))
			{
				$sql='SELECT rules FROM '.DB_PREFIX.'group WHERE id='.$gid;
				$grules=$this->db->query_first($sql);
				$grules_arr = maybe_unserialize($grules['rules']);
				if(isset($grules_arr[$operation])) {
					if($grules_arr[$operation]['credits']&&is_array($grules_arr[$operation]['credits']))
					{
						foreach ($grules_arr[$operation]['credits'] AS $k => $v)
						{
							$rule[$k] += $grules_arr[$operation]['credits'][$k];
						}
						$rule['gids']=$grules_arr[$operation]['gids'];
					}
				}
			}
		}
		return $rule;
	}

	/**
	 * 积分规则日志调用
	 */
	public function getcreditrulelog($rid,$uid,$appid='',$modid='',$sid=0,$cid=0)
	{
		$rlog = array();
		if($rid&&$uid)
		{
			$whereExtend = '';
			if($appid){
				$whereExtend = ' AND appid=\''.$appid.'\'';
			}
			if($modid){
				$whereExtend .= ' AND modid=\''.$modid.'\'';
			}
			if($sid||$cid){
				$whereExtend .= ' AND sid='.$sid;
			}
			if($cid){
				$whereExtend .= ' AND cid='.$cid;
			}
			$sql='SELECT * FROM '.DB_PREFIX.'credit_rules_log WHERE uid='.$uid.' AND rid='.$rid.$whereExtend;
			$rlog=$this->db->query_first($sql);
		}
		return $rlog;
	}

	/**
	 * 积分日志调用
	 */
	public function getcreditlog($cond,$field='*')
	{
		$log = array();
		if($cond)
		{
			$sql='SELECT '.$field.' FROM '.DB_PREFIX.'credit_log WHERE 1 '.$cond;
			$log=$this->db->query_first($sql);
		}
		return $log?$log:array();
	}
	/**
	 * 创建积分日志记录
	 *
	 */
	private function addcreditlog($member_id,$creditLogData,$credits)
	{
		if($member_id>0)
		{
			$creditLog = array('member_id'=>(int)$member_id,'dateline'=>TIMENOW);
		}
		else{
			return array('status'=>-4);//缺少必选字段
		}
		/**
		 *
		 *配置属性:支持字段(required=1为必传)
		 * @var array()
		 */
		$configField = array(
		'app_uniqueid'=>array('required'=>1),//应用标识
		'mod_uniqueid'=>array('required'=>1),//模块标识
		'action'=>array(),//执行方法
		'method'=>array('required'=>1),//操作类型标识
		'relatedid'=>array('required'=>1),//相关id，比如操作人id，商品id，积分规则id，订单号等
		'icon'=>array(),//日志图标
		'title'=>array(),//日志标题
		'remark'=>array('required'=>1),//日志描述
		'isFrozen'=>array(),//是否冻结积分
		'credit1'=>array(),//积分
		'credit2'=>array(),//经验
		);
		if(!is_array($creditLogData))
		{
			return array('status'=> -5);//参数格式不正确
		}
		if(is_array($credits))
		{
			$creditLogData = array_merge($creditLogData,$credits);
		}
		foreach ($configField as $k => $v)
		{
			/**处理提交的数据开始**/
			if(isset($creditLogData[$k]))
			{
				$creditLog[$k] = $creditLogData[$k];
			}
			elseif ($v[required])
			{
				return array('status'=>-4);//缺少必选字段
			}
			/**处理提交的数据结束**/
		}
		$logid = $this->membersql->create('credit_log', $creditLog,'','logid');
		return array('status'=> 1,
					 'logid'=>$logid['logid']
					);
	}

	/**
	 * 积分更新
	 * @param int $credits 需要增加的积分.
	 * @param int $memberid 会员id
	 * @param int $coef 积分增加倍数
	 * @param bool $add 控制是直接覆盖原有用户积分还是在用户原有基础上增加积分.真新增,假更新.
	 * @param bool $updategroup 控制增加积分的时间,是否更新用户组.如果有需要请设置为true,此项增加的原因是为了防止后台同时更新积分和设置用户组的重复操作的.
	 * @param bool $updategrade 控制是否更新等级.如果有需要请设置为true.
	 * @param array $credit_type 已启用的积分类型.
	 * @param array $member_credit 会员原积分数据.格式参照membercredit($memberid,false,false)方法的输出格式
	 * @param array $credit_log 积分日志数组($app_uniqueid(应用id),$mod_uniqueid(模块id),$method(方法),$action(子操作,可选),$reason(原因,可选),$relatedid(操作相关id,比如文稿,图集等id))
	 */
	public function credits($credits,$memberid,$coef=1,$add=false,$updategroup=false,$updategrade=false,$credit_type=null,$member_credit=array(),$credit_log=array())
	{
		if (empty($memberid))
		{
			return 0;//未传会员id
		}
		$members=$this->memberinfo($memberid);
		if(empty($members))
		{
			return -1;//会员不存在
		}
		$memberscredit=array();//记录需要统计积分的数据
		$_memberscredit=array();//记录需要插入的数据
		$credit_count_log = array();//记录需要插入日志的积分
		if($member_credit&&is_array($member_credit))
		{
			$old_memberscredit=$memberscredit=$member_credit;
		}
		else
		{
			$old_memberscredit=$memberscredit=$this->membercredit($memberid,false,false);
		}
		if(empty($credit_type))
		{
			$credit_type=$this->get_credit_type_field();//获取已启用的积分字段
		}
		$update_credit_log=false;//是否更新积分日志
		if($credit_type&&is_array($credit_type))
		{
			if($credits&&is_array($credits))
			{
				foreach ($credits as $key => $val)
				{
					$credit=intval($val);
					if(($credit!=0||!$add)&&in_array($key, $credit_type))
					{
						if($add&&$memberscredit[$key])//如果为真,则新增积分(如果为假,则直接更新积分).
						{
							$new_credit=intval($credits[$key])*$coef;
							$credit_count_log[$key]=$new_credit;//积分日志.
							if($credit_count_log[$key])
							{
								$update_credit_log=true;
							}
							$_memberscredit[$key] = $memberscredit[$key]+$new_credit;
							$memberscredit[$key]=$_memberscredit[$key];
						}
						else
						{
							$new_credit=intval($credits[$key])*$coef;
							$credit_count_log[$key]=$new_credit-$memberscredit[$key];//积分日志.
							if($credit_count_log[$key])
							{
								$update_credit_log=true;
							}
							$_memberscredit[$key] = $new_credit;
							$memberscredit[$key]=$_memberscredit[$key];
						}
					}
				}
			}
		}
		else return -2;//未启用任何积分类型
		if(is_array($credit_log)&&$credit_log&&$update_credit_log)
		{
			$loginfo = $this->addcreditlog($memberid,$credit_log, $credit_count_log);//积分日志入库
			if($loginfo['status'] == 1)//积分日志入库成功
			{
				if(!$this->addcredit($memberid,$_memberscredit,$old_memberscredit))
				{
					return -3;//无可更新积分
				}
				$ret['logid'] = $loginfo['logid'];
			}
			else 
			{
				return $loginfo['status'];//返回积分日志相关报错状态
			}
		}
		$_credits=$this->credits_count($memberid,$memberscredit);//统计用户积分
		$ret['credits']=$_credits;
		$ret['credit']=$memberscredit;
		if((empty($members['isupdate']))&&$updategroup)
		{
			$newgroup=$this->checkgroup_credits($_credits);
			if($newgroup&&$newgroup['gid']!=$members['gid'])
			{
				$re_groups=$this->updategroup_id($memberid, $newgroup['gid'], $groupexpiry=0);
				if($re_groups)
				{
					$ret['gid']=$newgroup['gid'];
					$ret['groupexpiry'] = 0;
				}
			}
		}
		$grade_credit_type=$this->get_grade_credits_type();//获取等级升级类型.
		if($grade_credit_type&&isset($_memberscredit[$grade_credit_type])&&$_memberscredit[$grade_credit_type]>=0&&$updategrade)
		{
			$newgrade=$this->checkgrade_credits(intval($_memberscredit[$grade_credit_type]));
			if($newgrade&&$newgrade['gradeid']!=$members['gradeid'])
			{
				$re_grade=$this->updategrade_id($memberid, $newgrade['gradeid']);
				if($re_grade)
				{
					$ret['gradeid']=$newgrade['gradeid'];
				}
			}
		}
		return $ret;
	}
	/**
	 * 
	 * 积分入库操作 ...
	 * @param array $credits
	 * @param int $type 值为TRUE则更新，值为FLASE则创建
	 */
	public function addcredit($memberId,array $credits = array(),$type = true)
	{
		$memberId = (int)$memberId;
		if($type){
			if($credits&&is_array($credits))
			{
				$this->membersql->update('member_count', $credits,array('u_id'=>$memberId));
				if($this->membersql->affected_rows())
				{
					return $memberId;
				}
			}
		}
		else{
			if($credits&&is_array($credits)){
				$credits['u_id'] = $memberId;
				$this->membersql->create('member_count', $credits,false,'id');
				if($this->membersql->affected_rows())
				{
					return $memberId;
				}
			}
		}
		return 0;
	}
	/**
	 *
	 * 增加冻结积分 ...
	 */
	public function addfrozenCredit($member_id,$credit)
	{
		$frozenCredit = array();
		$isUpdate = array();
		$frozenCredit['frozenCredit'] = $credit;
		$isUpdate = $this->membersql->update('member', $frozenCredit,array('member_id'=>intval($member_id)),true);
		if($isUpdate)
		{
			return array('member_id'=>$member_id,'credit'=>$credit,'status'=>1);
		}
		return array('member_id'=>$member_id,'credit'=>$credit,'status'=>0);
	}

	/**
	 *
	 * 获取已经冻结积分 ...
	 */
	public function getFrozenCredit($member_id)
	{
		if(!$member_id)
		{
			return array();
		}
		$sql = 'SELECT frozenCredit FROM '.DB_PREFIX.'member WHERE member_id = \''.$member_id.'\'';
		$frozenCredit = $this->db->query_first($sql);
		return intval($frozenCredit['frozenCredit']);
	}

	/**
	 *
	 * 取消冻结积分 ...
	 */
	public function finalFrozenCredit($member_id,$credit)
	{
		$frozenCredit = array();
		$isUpdate = array();
		$frozenCredit['frozenCredit'] = $credit;
		$isUpdate = $this->membersql->update('member', $frozenCredit,array('member_id'=>intval($member_id)),true,array('frozenCredit'=>'-'));
		if($isUpdate)
		{
			return array('member_id'=>$member_id,'credit'=>$credit,'status'=>1);
		}
		return array('member_id'=>$member_id,'credit'=>$credit,'status'=>0);
	}

	/**
	 * 权限调用函数
	 * @param INT $gid  组id
	 * @return array
	 * allow 操作类型,拥有此权限是否允许操作. operation 操作key. stint 限制数量,某些权限用到.
	 */
	public function showpurview($gid=0)
	{
		$where='';
		if($gid)
		{
			$where="AND pb.gid =".$gid;
		}
		$join="inner join ".DB_PREFIX."purview as p on p.id=pb.pid";
		$row=array();
		$sql = "SELECT pb.gid,pb.stint,p.pname,p.operation,p.allow FROM " . DB_PREFIX . "purview_bind as pb ".$join." WHERE 1 ".$where;
		$query=$this->db->query($sql);
		while ($ret = $this->db->fetch_array($query))
		{
			$row[$ret['gid']][$ret['operation']]=array('pname'=>$ret['pname'],'allow'=>$ret['allow'],'stint'=>$ret['stint']);
		}
		return $row?$row:false;
	}
	/**
	 * 权限检测函数
	 * @param INT $gid 组id
	 * @param string $operation 操作key
	 * @return array
	 * allow 操作类型,拥有此权限是否允许操作. operation 操作key. stint 限制数量,某些权限用到.
	 */
	public function checkpurview($gid,$operation)
	{
		if(empty($gid)||empty($operation))
		{
			return false;
		}
		$where="AND pb.gid =".intval($gid)." AND p.operation='".trim($operation)."'";
		$join="inner join ".DB_PREFIX."purview as p on p.id=pb.pid";
		$sql = "SELECT p.operation,p.allow,pb.stint FROM " . DB_PREFIX . "purview_bind as pb ".$join." WHERE 1 ".$where;
		return $this->db->query_first($sql);
	}

	/**
	 * 权限判断函数
	 * @param INT $gid 组id
	 * @param string $operation 操作key
	 */
	public function purview($gid,$operation)
	{
		$where='';
		if(empty($gid))//未传分组id
		{
			return 0;
		}
		if(empty($operation))//未传操作key;
		{
			return -1;
		}
		$operation=trim($operation);
		if($gid)
		{
			$gid=intval($gid);
			$purview=$this->checkpurview($gid, $operation);
		}
		if(empty($purview))
		{
			$where="AND operation='".$operation."'";
			$sql = "SELECT operation,allow FROM " . DB_PREFIX . "purview WHERE 1 ".$where;
			$purview_pb=$this->db->query_first($sql);
		}
		if(empty($purview)&&empty($purview_pb))//无此权限数据
		{
			return -2;
		}
		elseif (empty($purview)&&$purview_pb)
		{
			if($purview_pb['allow'])
			{
				return array('allow'=>false,'stint'=>0);//无权限.因为这是允许权限.
			}
			else
			{
				return array('allow'=>true,'stint'=>0);//无权限.因为这是拒绝权限.所以拥有者拒绝此操作.
			}
		}
		else
		{
			if($purview['allow'])
			{
				return array('allow'=>true,'stint'=>$purview['stint']);
			}
			else {
				return array('allow'=>false,'stint'=>$purview['stint']);
			}
		}
		return -3;//授权失败
	}
	
	/**
	 * 获取会员之间黑名单关系
	 */
	public function get_friend_blacklist($member_id=0)
	{
		$where='';
		if($member_id)
		{
			$uid=intval($member_id);
			$where=' AND fb.uid ='.$uid;
		}
		else $field='fb.uid,';
		$sql='SELECT '.$field.'fb.fb_uid,m.member_name,m.avatar,m.email FROM '.DB_PREFIX.'friend_blacklist as fb inner join '.DB_PREFIX.'member as m ON m.member_id=fb.fb_uid WHERE 1'.$where;
		$blacklist=array();
		$query=$this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['avatar']=maybe_unserialize($row['avatar']);
			if($member_id)
			{
				$fb_uid=$row['fb_uid'];
				//	unset($row['fb_uid']);
				$blacklist[$fb_uid]=$row;
			}
			else {
				$fb_uid=$row['fb_uid'];
				$uid=$row['uid'];
				//	unset($row['uid'],$row['fb_uid']);
				$blacklist[$uid][$fb_uid]=$row;
			}
		}
		return $blacklist?$blacklist:array();
	}
	/**
	 *
	 * 检测会员用户之间的黑名单关系...
	 * @param unknown_type $member_id
	 * @param unknown_type $fb_uid
	 */
	public function check_friend_blacklist($member_id,$fb_uid)
	{
		$where='';
		if($member_id)
		{
			$uid=intval($member_id);
			$where=' AND fb.uid ='.$uid;
			$sql='SELECT fb.fb_uid FROM '.DB_PREFIX.'friend_blacklist as fb WHERE 1'.$where;
		}
		$blacklist=array();
		$query=$this->db->query($sql);
		$row_fb_uid=array();
		while($row = $this->db->fetch_array($query))
		{
			$row_fb_uid[]=$row['fb_uid'];
		}
		if ($row_fb_uid&&is_array($row_fb_uid))
		{
			if($fb_uid&&in_array($fb_uid, $row_fb_uid))//如果传了fb_uid.则判断是否黑名单
			{
				return true;
			}
			return false;//如果未传fb_uid或者不是黑名单
		}
		else
		{
			return false;//如果查不出黑名单信息
		}
	}
	/**
	 * 创建用户之间黑名单关系 ...
	 */
	public function insert_friend_blacklist($member_id,$fb_uid)
	{
		if(!$member_id||!$fb_uid)
		{
			return false;
		}
		$uid=intval($member_id);
		$fb_uid=intval($fb_uid);
		$black=$this->checkfriend_blacklist($uid,$fb_uid);
		if($black)
		{
			return false;
		}
		$data=array('uid'=>$uid,'fb_uid'=>$fb_uid);
		$this->membersql->create('friend_blacklist', $data);
		return true;
	}
	/**
	 * 删除用户之间黑名单关系 ...
	 */
	public function delete_friend_blacklist($member_id,$fb_uid)
	{
		$uid=intval($member_id);
		if(empty($uid))
		{
			return false;
		}
		$black=$this->checkfriend_blacklist($uid,$fb_uid);
		if(empty($black))
		{
			return false;
		}
		if(empty($fb_uid)&&$black)
		{
			$this->membersql->delete('friend_blacklist', array('uid'=>$uid)); //删除黑名单关系
		}
		elseif($fb_uid&&is_array($fb_uid)&&$black)
		{
			$this->membersql->delete('friend_blacklist', array('fb_uid' => $fb_uid,'uid'=>$uid)); //删除黑名单关系
		}
		elseif($black)
		{	$fb_uid=intval($fb_uid);
		$this->membersql->delete('friend_blacklist', array('fb_uid' => $fb_uid,'uid'=>$uid)); //删除黑名单关系
		}
		return true;
	}
	/**
	 * 黑名单检测函数,检测黑名单是否过期,是否是黑名单用户.
	 * @param string $member_id 会员id,多个会员id以英文标点符号,隔开
	 */
	public function blacklist($member_id)
	{
		if(!$member_id)
		{
			return array();
		}
		if(is_string($member_id)&&!is_numeric($member_id))
		{
			$member_id=explode(',', $member_id);
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			if($member_id&&is_array($member_id))
			{
				$uid=implode(',', $member_id);
			}
			if($uid){
				$where=' AND uid IN( '.$uid.')';
			}else
			{
				return array();
			}
		}
		elseif ($member_id&&is_array($member_id))
		{
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			$uid=implode(',', $member_id);
			if($uid){
				$where=' AND uid IN( '.$uid.')';
			}
			else {
				return array();
			}
		}
		else
		{
			$uid=intval($member_id);
			$where=' AND uid ='.$uid;
		}
		$sql='SELECT uid,total,deadline,type FROM '.DB_PREFIX.'member_blacklist WHERE 1'.$where;
		$blacklist=array();
		$query=$this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$blacklist[$row['uid']]=array('total'=>$row['total'],'deadline'=>$row['deadline'],'type' => $row['type']);
		}
		if($member_id&&is_array($member_id)) {//批量返回
			foreach ($member_id as $memberid)
			{
				$black=$blacklist[$memberid];
				if(!empty($black))
				{
					if($black['deadline']>TIMENOW||($black['deadline'] < 0))
					{
						if($black['deadline']>0)
						{
							$black['deadline']=date('Y-m-d',$black['deadline']-24*3600);
						}
						$black['isblack']=1;
						$reblacklist[$memberid]=$black;
					}
					elseif (($black['deadline']<TIMENOW)||empty($black['deadline']))
					{
						if($black['deadline']>0)
						{
							$black['deadline']=date('Y-m-d',$black['deadline']-24*3600);
						}
						$black['isblack']=0;
						$reblacklist[$memberid]=$black;
					}
				}
				else {
					$reblacklist[$memberid]=array('total'=>0,'deadline'=>0,'isblack'=>0,'type' => 0);//不是黑名单用户,或者已过期.
				}
			}
			return $reblacklist;
		}
		else//单独返回
		{
			$black=$blacklist[$uid];
			if(!empty($black))
			{
				if($black['deadline']>TIMENOW||($black['deadline'] < 0))
				{
					if($black['deadline']>0)
					{
						$black['deadline']=date('Y-m-d',$black['deadline']-24*3600);
					}
					$black['isblack']=1;
					$reblacklist[$uid]=$black;
				}
				elseif (($black['deadline']<TIMENOW)||empty($black['deadline']))
				{
					if($black['deadline']>0)
					{
						$black['deadline']=date('Y-m-d',$black['deadline']-24*3600);
					}
					$black['isblack']=0;
					$reblacklist[$uid]=$black;
				}
			}
			else {
				$reblacklist[$uid]=array('total'=>0,'deadline'=>0,'isblack'=>0,'type' => 0);//不是黑名单用户.
			}
			return $reblacklist;
		}
	}

	/**
	 * 黑名单设置函数,支持设置黑名单.
	 * @param int $member_id 会员id
	 * @param int $deadline 黑名单用户状态,-1永久有效,0为取消此用户黑名单,传合法时间为Y-m-d H:i,必须按照此格式精确到分,限制时间.
	 * @author 批量设置仅支持批量设置同一状态,比如全部取消或者全部添加为黑名单或者全部设置为同一有效期.
	 */
	public function blacklist_set($member_id,$deadline,$type)
	{
		$deadline=trim(urldecode($deadline));
		if(!$member_id)
		{
			return false;
		}
		if(datecheck($deadline))
		{
			$deadline=intval(strtotime($deadline))+24*3600;
		}
		else $deadline=intval($deadline);
		if(is_string($member_id)&&!is_numeric($member_id))
		{
			$member_id=explode(',', $member_id);
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			if($member_id&&is_array($member_id))
			{
				$uid=trim(implode(',', $member_id));
			}
		}
		elseif ($member_id&&is_array($member_id))
		{
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			$uid=trim(implode(',', $member_id));
		}
		else $uid=intval($member_id);
		$sql='SELECT uid,total,deadline FROM '.DB_PREFIX.'member_blacklist WHERE uid IN( '.$uid.')';
		$blacklist=array();
		$query=$this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$blacklist[$row['uid']]=array('total'=>$row['total'],'deadline'=>$row['deadline']);
		}
		$set=false;
		if($member_id&&is_array($member_id))
		{
			$sql='';
			foreach ($member_id as $memberid)
			{
				$black=$blacklist[$memberid];
				if(!empty($black))
				{
					$blackinfo=array('deadline'=>intval($deadline));
					$is_total = 0;
					if($deadline>$black['deadline'])
					{
						$is_total = 1;
					}
					if(!empty($deadline)&&$is_total)	//只要不是取消黑名单的更新操作,都+拉黑次数1
					{
						$blackinfo['total']=$black['total']+1;
					}
                    $blackinfo['type'] = $type;
					$sql = "UPDATE " . DB_PREFIX . "member_blacklist SET ";
					$space = '';
					foreach ($blackinfo AS $key => $value)
					{
						$sql .= $space . $key . "=" . $value ;
						$space = ",";
					}

					$sql .= " WHERE uid  = " . $memberid;
				}
				elseif(empty($black)&&!empty($deadline))
				{
					$blackinfo=array('uid'=>$memberid,'total'=>1,'deadline'=>intval($deadline),'type' => $type);
					$sql = "INSERT INTO " . DB_PREFIX . "member_blacklist SET ";
					$space = '';
					foreach ($blackinfo AS $key => $value)
					{
						$sql .= $space . $key . "=" . $value ;
						$space = ",";
					}
				}
				if(!empty($sql))
				{
					$this->db->query($sql);
					$this->force_logout_user($memberid);//设置黑名单强制用户退出
					$set=true;
					
				}
			}
		}
		else //单独设置
		{
			$black=$blacklist[$uid];
			$sql='';
			if(!empty($black))
			{
				$blackinfo=array('deadline'=>intval($deadline));
				$is_total = 0;
				if($deadline>$black['deadline'])
				{
					$is_total = 1;
				}
				if(!empty($deadline)&&$is_total)	//只要不是取消黑名单的更新操作,都+拉黑次数1
				{
					$blackinfo['total']=$black['total']+1;
				}
                $blackinfo['type'] = $type;
				$sql = "UPDATE " . DB_PREFIX . "member_blacklist SET ";
				$space = '';
				foreach ($blackinfo AS $key => $value)
				{
					$sql .= $space . $key . "=" . $value ;
					$space = ",";
				}

				$sql .= " WHERE uid  = " . $uid;
			}
			elseif(empty($black)&&!empty($deadline))//如果是取消黑名单的话 仅仅只做更新操作,不插入.
			{
				$blackinfo=array('uid'=>$uid,'total'=>1,'deadline'=>intval($deadline),'type' => $type);
				if(!empty($deadline))
				{
					$blackinfo['deadline']=intval($deadline);
				}
				$sql = "INSERT INTO " . DB_PREFIX . "member_blacklist SET ";
				$space = '';
				foreach ($blackinfo AS $key => $value)
				{
					$sql .= $space . $key . "=" . $value ;
					$space = ",";
				}
			}
			if(!empty($sql))
			{
				$this->db->query($sql);
				$this->force_logout_user($uid);//设置黑名单强制用户退出
				$set=true;
			}
		}
		return $set?true:false;

	}

    /**
	 * 用户信息查询
	 */
	function memberinfo($memberid)
	{
		if(empty($memberid))
		{
			return false;
		}
		$sql='SELECT m.member_name,m.gradeid,m.gid,m.credits,m.groupexpiry,g.isupdate FROM '.DB_PREFIX.'member as m left join '.DB_PREFIX.'group as g on m.gid = g.id WHERE m.member_id = '.$memberid;
		$members=$this->db->query_first($sql);
		return $members?$members:false;
	}
	/**
	 *
	 * 获取会员id ...
	 * @param string $member_name 会员名
	 * @param bol $is_batch 是否允许多个会员名查找
	 * @param bol $is_fuzzy 是否启用模糊查找
	 * @param string $type 会员类型
	 */
	function get_member_id($member_name,$is_batch=true,$is_fuzzy=false,$type='',$identifier = 0)
	{
		if(empty($member_name))
		{
			return false;
		}
		if(is_string($member_name)&&(stripos($member_name, ',')!==false))
		{
			if($is_batch)
			{
				$member_name=explode(',', trim(urldecode($member_name)));//转为数组方便字符串转换
				if($member_name&&is_array($member_name))
				{
					$member_name=trim("'".implode("','", $member_name )."'");
					$where=' AND m.member_name IN( '.$member_name.')';
				}
			}
			else return false;
		}
		elseif ($member_name&&is_array($member_name))
		{
			if($is_batch)
			{
				$member_name=trim("'".implode("','", $member_name )."'");
				$where=' AND m.member_name IN( '.$member_name.')';
			}
			else return false;
		}
		else
		{
			$member_name=trim(urldecode($member_name));
			if($is_fuzzy&&$is_batch)
			{
				$where=' AND m.member_name LIKE \'%' . $member_name . '%\'';
			}
			else
			{
				$where=' AND m.member_name =\''.$member_name.'\'';
			}
		}
		if($type)
		{
			$where .=' AND type=\''.$type.'\'';
		}

        if($identifier)
        {
		    $where .= ' AND identifier = \''.$identifier.'\'';
        }
		$sql='SELECT member_id,member_name FROM '.DB_PREFIX.'member AS m WHERE 1 '.$where;
		$query=$this->db->query($sql);
		$members_info=array();
		while ($row=$this->db->fetch_array($query))
		{
			$members_info[$row['member_name']]=$row['member_id'];
		}
		return $members_info?($is_batch?$members_info:$members_info[$member_name]):false;
	}
	
	/**
	 *
	 * 根据用户id获取用户名 ...
	 */
	function get_member_name($member_id,$is_batch=true)
	{
		if(!$member_id)
		{
			return false;
		}
		if(is_string($member_id)&&!is_numeric($member_id)&&(stripos($member_id, ',')!==false))
		{
			if($is_batch)
			{
				$member_id=explode(',', $member_id);
				$member_id=array_filter($member_id,"clean_array_null");
				$member_id=array_filter($member_id,"clean_array_num");
				if($member_id&&is_array($member_id))
				{
					$member_id=trim(implode(',', $member_id));
					$where=' AND m.member_id IN( '.$member_id.')';

				}
			}
			else return false;
		}
		elseif ($member_id&&is_array($member_id))
		{
			if($is_batch)
			{
				$member_id=array_filter($member_id,"clean_array_null");
				$member_id=array_filter($member_id,"clean_array_num");
				$member_id=trim(implode(',', $member_id));
				$where=' AND m.member_id IN( '.$member_id.')';
			}
			else return false;
		}
		else {
			$member_id=intval($member_id);
			$where=' AND m.member_id ='.$member_id;
		}
		$sql='SELECT member_id,member_name FROM '.DB_PREFIX.'member AS m WHERE 1 '.$where;
		$query=$this->db->query($sql);
		$members_info=array();
		while ($row=$this->db->fetch_array($query))
		{
			$members_info[$row['member_id']]=$row['member_name'];
		}
		return $members_info?($is_batch?$members_info:$members_info[$member_id]):false;
	}
	/**
	 *
	 * 获取会员信息(此方法为新增方法,此方法可以代替其它获取会员相关信息的方法)
	 * @param string $condition 数据库查询条件
	 * @param string $field   需要查询的字段
	 * @param string $leftjoin 需要连接的表
	 * @param string $key  数组key(请传存在的数据库字段,并且保证绝对有值)
	 * @param string $is_batch 是否带key输出
	 */
	function get_member_info($condition, $field = ' * ',$leftjoin='',$key='member_id',$is_batch=false)
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member as m {$leftjoin} WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$members_info=array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			}

			if ($row['update_time'])
			{
				$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			}

			if ($row['avatar'])
			{
				$row['avatar'] 	= unserialize($row['avatar']);
			}
			if($row['groupicon'])
			{
				$row['groupicon'] = maybe_unserialize($row['groupicon']);
			}
			if($row['graicon'])
			{
				$row['graicon'] = maybe_unserialize($row['graicon']);
			}
			if($row['groupexpiry'])
			{
				$row['groupexpiry'] = date('Y-m-d H:i:s', $row['groupexpiry']);
			}
			if($is_batch)
			{
				$members_info[$row[$key]] = $row;
			}
			else {
				$members_info = $row;
			}
		}
		return $members_info?$members_info:array();
	}

	/**
	 *
	 * 根据用户id获取用户组信息 ...
	 */
	function get_member_group($member_id,$key='member_id')
	{
		if(!$member_id)
		{
			return false;
		}
		if(is_string($member_id)&&!is_numeric($member_id)&&(stripos($member_id, ',')!==false))
		{
			$member_id=explode(',', $member_id);
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			if($member_id&&is_array($member_id))
			{
				$member_id=trim(implode(',', $member_id));
				$where=' AND m.member_id IN( '.$member_id.')';

			}
		}
		elseif ($member_id&&is_array($member_id))
		{
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			$member_id=trim(implode(',', $member_id));
			$where=' AND m.member_id IN( '.$member_id.')';
		}
		else {
			$member_id=intval($member_id);
			$where=' AND m.member_id ='.$member_id;
		}
		if($where)
		{
			$sql='SELECT m.member_id,m.member_name,m.gid,g.name as groupname FROM '.DB_PREFIX.'member m LEFT JOIN '.DB_PREFIX.'group g ON g.id=m.gid WHERE 1 '.$where;
			$query=$this->db->query($sql);
			while($row=$this->db->fetch_array($query))
			{
				$member_infos[$row[$key]]=$row;
				//unset($member_infos[$row[$key]][$key]);
			}
			return $member_infos;
		}
		return false;
	}

	/**
	 *
	 * 用户积分表查询
	 * @param  $member_id 会员id
	 * @param  $is_on  是否过滤未开启的积分
	 * @param  $is_id  是否输出带会员id键值
	 */
	public function membercredit($member_id,$is_on=false,$is_id=true,$isAdd = false)
	{
		if(empty($member_id))
		{
			return false;
		}
		if(is_string($member_id)&&!is_numeric($member_id))
		{
			$member_id=explode(',', $member_id);
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			if($member_id&&is_array($member_id))
			{
				$uid=trim(implode(',', $member_id));
			}
			$where=' AND mc.u_id IN( '.$uid.')';
		}
		elseif ($member_id&&is_array($member_id))
		{
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			$uid=trim(implode(',', $member_id));
			$where=' AND mc.u_id IN( '.$uid.')';
		}
		else
		{
			$uid=intval($member_id);
			$where=' AND mc.u_id ='.$uid;
		}

		$field='*';
		if($is_on)//只取出已启用的积分字段
		{
			$membercredit_type=$this->get_credit_type_field();
			if(empty($membercredit_type))
			{
				return array();
			}
			$field='u_id,'.implode(',', $membercredit_type);
		}
		$sql='SELECT '.$field.' FROM '.DB_PREFIX.'member_count as mc WHERE 1 '.$where;
		$members=array();
		$query=$this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
				if($is_id)
				{
						$members[$row['u_id']]=$row;
						unset($members[$row['u_id']]['u_id']);
				}
				else {
					$members = $row;
					unset($members['u_id']);
				}
		
		}
		if($member_id&&$is_id&&$isAdd)
		foreach ((array)$member_id as $v){
			if(!isset($members[$v])){
				foreach ($membercredit_type as $vv){
							$members[$v][$vv] = '0';
				}
			}
		}
		return $members?$members:array();
	}

	/**
	 * 获取已启用用户积分信息
	 * @param int $memberid 会员id
	 */
	public function get_member_credits($memberid,$is_id=true)
	{
		if(empty($is_id)&&is_numeric($memberid))//如果不带id输出,则仅支持一个会员查找积分.
		{
			$memberid=intval($memberid);
		}
		elseif(empty($is_id)) {
			return false;
		}
		$membercredit=$this->membercredit($memberid);
		$membercredit_type=$this->get_credit_type();
		$db_field=array_keys($membercredit_type);
		if($membercredit&&is_array($membercredit))
		{
			foreach ($membercredit as $k => $v)
			{
				if($db_field&&is_array($db_field))
				{
					foreach ($db_field as $vv)//过滤未启用积分类型
					{
						$vc[$vv]=$v[$vv];
					}
				}
				$re_credit[$k]=$vc;
			}
		}
		return $re_credit?($is_id?$re_credit:$re_credit[$memberid]):Array();
	}

	/**
	 * 检测用户是否存在
	 */
	public function checkuser($memberid)
	{
		if(empty($memberid))
		{
			return false;
		}
		$members=array();
		$sql='SELECT member_id FROM '.DB_PREFIX.'member WHERE member_id = '.intval($memberid);
		$members=$this->db->query_first($sql);
		return $members['member_id']?$members['member_id']:false;
	}

	/**
	 * 用户id获取组id
	 */
	public function uid_to_gid($memberid)
	{
		if(empty($memberid))
		{
			return false;
		}
		$sql='SELECT gid FROM '.DB_PREFIX.'member WHERE member_id = '.intval($memberid);
		$members=$this->db->query_first($sql);
		return $members?$members['gid']:false;
	}

	/**
	 * 用户id获取等级id
	 */
	public function uid_to_gradeid($memberid)
	{
		if(empty($memberid))
		{
			return false;
		}
		$sql='SELECT gradeid FROM '.DB_PREFIX.'member WHERE member_id = '.intval($memberid);
		$members=$this->db->query_first($sql);
		return $members?$members['gradeid']:false;
	}

	/**
	 * 组id获取所拥有的用户id
	 */
	public function gid_to_uid($gid)
	{
		$where='';
		$ret = array();
		if(($gid&&is_string($gid)&&(stripos($gid, ',')!==false))||is_numeric($gid)&&$gid>0&&!is_array($gid))
		{
			$gid = explode(',', $gid);
		}
		if ($gid&&is_array($gid))
		{
			$gid=array_filter($gid,"clean_array_null");
			$gid=array_filter($gid,"clean_array_num_max0");
			$gid=trim(implode(',', $gid));
			if(is_string($gid)&&(stripos($gid, ',')!==false)&&$gid)
			{
				$where=' AND gid IN('.$gid.')';
			}
			else $where=' AND gid = '.$gid;
		}
		elseif(empty($gid)) {
			return false;
		}
		$sql = 'SELECT member_id FROM '.DB_PREFIX.'member WHERE 1 '.$where;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$ret[] = $row['member_id'];
		}
		return $ret;
	}

	/**
	 * 等级id获取所拥有的用户id
	 */
	public function gradeid_to_uid($gradeid)
	{
		$where='';
		$ret = array();
		if(($gradeid&&is_string($gradeid)&&(stripos($gradeid, ',')!==false))||is_numeric($gradeid)&&$gradeid>0&&!is_array($gradeid))
		{
			$gradeid = explode(',', $gradeid);
		}
		if ($gradeid&&is_array($gradeid))
		{
			$gradeid=array_filter($gradeid,"clean_array_null");
			$gradeid=array_filter($gradeid,"clean_array_num_max0");
			$gradeid=trim(implode(',', $gradeid));
			if(is_string($gradeid)&&(stripos($gradeid, ',')!==false)&&$gradeid)
			{
				$where=' AND gradeid IN('.$gradeid.')';
			}
			else $where=' AND gradeid = '.$gradeid;
		}
		elseif(empty($gradeid)) {
			return false;
		}
		$sql = 'SELECT member_id FROM '.DB_PREFIX.'member WHERE 1 '.$where;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$ret[] = $row['member_id'];
		}
		return $ret;
	}

	/**
	 *
	 * 用户等级更新
	 */
	public function updategrade($memberid)
	{
		$uid=intval($memberid);
		if(!$uid)
		{
			return false;
		}
		$member_credits=$this->get_member_credits($uid,false);
		$grade_credit_type=$this->get_grade_credits_type();
		$grade_credit = intval($member_credits[$grade_credit_type]);
		$newgrade=array();
		if($grade_credit>=0)//获取新等级
		{
			$newgrade=$this->checkgrade_credits($grade_credit);
		}
		if(empty($newgrade))//没有匹配
		{
			return false;
		}
		else
		{
			$this->updategrade_id($uid, $newgrade['gradeid']);
		}
		return $newgrade;

	}
	/**
	 * 
	 * 设置操作会员ID ...
	 * @param unknown_type $memberId
	 */
	public function setMemberId ($memberId)
	{
		$this->mMemberId = (int)$memberId;
	}
	/**
	 *
	 * 获取用户等级信息
	 * @param unknown_type $gradeId
	 * @param unknown_type $memberInfo
	 */
	public function getMemberGrade($gradeId,$memberInfo = array())
	{
		$field = '';
		$gradeCreditField = $this->get_grade_credits_type(1);
		if($gradeCreditField && $memberInfo)
		{
			$field = ',creditshigher,creditslower';
		}
		$gradeInfo = $this->getGradeInfo('id',$gradeId,'name as graname,icon as graicon,digital'.$field);		
		if(empty($gradeInfo) && $this->mMemberId)
		{
			$gradeInfo = $this->updategrade($this->mMemberId);
		}
		if($gradeInfo && $gradeCreditField && $memberInfo)
		{
			if($gredeCredit = $gradeInfo['creditslower'] - $gradeInfo['creditshigher'])
			{
				$gradeInfo[nextgraderate] = round(($memberInfo[$gradeCreditField['db_field']] - $gradeInfo['creditshigher'])/$gredeCredit,3);
			}
			else if ($memberInfo[$gradeCreditField['db_field']] - $gradeInfo['creditshigher'])
			{
				$gradeInfo[nextgraderate] = 1;
			}
			else 
			{
				$gradeInfo[nextgraderate] = 0;
			}
			$nextgrade = $this->getNextGrade($gradeInfo['digital'], $memberInfo,$gradeCreditField);
			$nextgrade&&$gradeInfo[nextgrade] = $nextgrade;
			unset($gradeInfo['creditshigher'],$gradeInfo['creditslower']);
		}
		return $gradeInfo;
	}

	//计算距离下一等级升级差距 ...
	private function getNextGrade($digital,$memberInfo,$gradeCreditField = array())
	{
		$nextgrade = '';
		if($digital)
		{
			empty($gradeCreditField) && $gradeCreditField = $this->get_grade_credits_type(1);
			if(isset($memberInfo[$gradeCreditField['db_field']])&&$credit = $memberInfo[$gradeCreditField['db_field']])
			{
				$nextGradeInfo = $this->getGradeInfo('digital', array($digital+1),'name as graname,digital,creditshigher');
				if($nextGradeInfo)
				{
					$nextGradeCredit = $nextGradeInfo['creditshigher'] - $credit;
					$nextgrade = '距 '.$nextGradeInfo[digitalname].''.$nextGradeInfo[graname].' 还差 '.$nextGradeCredit.' '.$gradeCreditField['title'].'';
				}
				else
				{
					$nextgrade = '您已经达到了系统最高等级';
				}
			}

		}
		return $nextgrade;
	}

	public function getGradeInfo($key,$value,$field = '*',$isBatch = false)
	{
		$arrValue = array();
		$strValue = '';
		$where = '';
		if(empty($key)||empty($value)){
			return array();
		}
		elseif (!is_string($key))
		{
			return array();
		}
		if(!is_array($value)){
			$value = trim(urldecode($value));
			$arrValue = explode(',',$value);//转为数组方便字符串转换
			$arrValue=array_filter($arrValue,"clean_array_null");
			$arrValue=array_filter($arrValue,"clean_array_num_max0");
		}
		else{
			$arrValue = $value;
			$arrValue=array_filter($arrValue,"clean_array_null");
			$arrValue=array_filter($arrValue,"clean_array_num_max0");
		}
		if(!$isBatch&&count($arrValue)>1)
		{
			return array();//未开启批量查找，所以禁止多参数
		}
		if($arrValue&&is_array($arrValue))
		{
			$strValue = trim("'".implode("','", $arrValue )."'");
			if(count($arrValue)>1)
			{
				$where=' AND '.$key.' IN( '.$strValue.')';
			}
			else{
				$where=' AND '.$key.' = '.$strValue;
			}
		}
		else{
			return array();
		}
		return $this->getGrade($where,$field,$isBatch);
	}

	public function getGrade($where,$field,$isBatch)
	{
		$memberGrade = new grade();
		$gradeInfo = $memberGrade->show($where,0,0,$field);
		return $isBatch?$gradeInfo:($gradeInfo[0]?$gradeInfo[0]:array());
	}

	/**
	 *
	 * 用户更新组
	 * 当gid=0时,不论用户所拥有当前组是否可以按照积分升级,则强制根据积分更新用户组.否则请留gid为非0的空值
	 */
	public function updategroup($memberid,$gid='',$groupexpiry='')
	{
		$uid=intval($memberid);
		if(!$uid)
		{
			return false;
		}
		if(datecheck($groupexpiry))
		{
			$groupexpiry=intval(strtotime($groupexpiry))+24*3600-1;
		}
		else $groupexpiry=intval($groupexpiry);
		$memberinfo=$this->memberinfo($uid);
		$newgroup=array();
		if($gid==0||(empty($gid)&&(empty($memberinfo['isupdate']))))//此项特殊,只要传gid为0,强制根据积分判断新用户组或者如果只传会员id,会员拥有组为可升级,则根据积分判断新组
		{
			$newgroup=$this->checkgroup_credits($memberinfo['credits']);
		}
		elseif (empty($gid)&&(!empty($memberinfo['isupdate']))&&!empty($memberinfo['groupexpiry']))//如果会员组为不可升级,并且用户组已设置有效期
		{
			if(TIMENOW>$memberinfo['groupexpiry'])//如果过期,则默认退回当前积分所分配组
			{
				$newgroup=$this->checkgroup_credits($memberinfo['credits']);
			}
		}
		elseif(!empty($gid))
		{
			if($gid==$memberinfo['gid']&&(empty($memberinfo['isupdate'])))
			{
				$this->updategroup($uid);
				return;
			}
			$newgroup=array('gid'=>$gid);
			if($groupexpiry)
			{
				$newgroup['groupexpiry']=intval($groupexpiry);
			}
		}
		if($newgroup)
		{
			if(empty($newgroup))//没有匹配用户组,则默认保留当前用户组
			{
				return false;
			}
			else
			{
				$this->updategroup_id($uid, $newgroup['gid'], $newgroup['groupexpiry']);
			}
		}
		return $newgroup;

	}
	/**
	 * 星星图标函数,查询星星图标资源
	 */
	public function staricon()
	{
		$sql='SELECT star,moon,sun FROM '.DB_PREFIX.'staricon WHERE opened=1';
		$query=$this->db->query($sql);
		while ($ret=$this->db->fetch_array($query))
		{
			$starimg['star']=maybe_unserialize($ret['star']);
			$starimg['moon']=maybe_unserialize($ret['moon']);
			$starimg['sun']=maybe_unserialize($ret['sun']);
		}
		return $starimg;
	}
	/**
	 * 星星数据函数
	 * @param INT $starnum 星星数量
	 * @param Array $starimg 星星图片资源
	 */
	public function showstar($starnum,$starimg)
	{
		$starnum=$starnum?$starnum:0;
		if(empty($starimg)||!is_array($starimg)||empty($starnum)||$starnum<0)
		{
			return array();
		}
		//starnum分组星星数,base底数,几个星星换成一个月亮,几个月亮换成太阳用.showstar星星月亮太阳图标.
		$return['starnum']=$starnum;
		$return['base']=$this->settings['showstars'];
		$return['starimg']=$starimg;
		//计算星星图标开始.
		$star[1]=hg_fetchimgurl($starimg['star']);
		$star[2]=hg_fetchimgurl($starimg['moon']);
		$star[3]=hg_fetchimgurl($starimg['sun']);
		$return['star']=showstars($this->settings['showstars'],$starnum, $star);
		//计算星星图标结束
		return $return;
	}
	/**
	 * 积分规则自定义函数(分组自定义规则)，不需要的规则不传即可.
	 */
	public function credits_rules_diy_group($gid,$rules_diy)
	{
		$configDiyField = array(
		'credit1'=>array(),//不限制
		'credit2'=>array(),//不限制
		);
		$gid=intval($gid);
		if (empty($gid))
		{
			return false;
		}
		$op=array();
		if(is_array($rules_diy)&&$rules_diy)//处理提交数据
		{
			$op=array_keys($rules_diy);
			$rules = $this->getcreditrule($op,true);//获取积分规则
			while (list($key,$val)=each($rules_diy))
			{
				if($rules[$key]['iscustom']&&is_array($val)){
					while (list($keys,$vals)=each($val))
					{
						if(array_key_exists($keys, $configDiyField)){
							$rules_diy[$key][$keys]=intval($vals);
						}
						else{
							unset($rules_diy[$key][$keys]);
						}
					}
				}else{
					unset($rules_diy[$key]);
				}
			}
		}
		$old_rules=array();
		$insert=array();
		$sql="SELECT gids,operation FROM ".DB_PREFIX."credit_rules WHERE gids <>''";
		$query=$this->db->query($sql);
		while($row=$this->db->fetch_array($query))
		{
			if($row['gids']&&is_string($row['gids']))
			{
				$gids=explode(',', $row['gids']);
			}
			else {
				$gids=array();
			}
			$old_rules[$row['operation']]=$gids;
		}
		foreach ($old_rules as $key=>$gids)
		{
			if(in_array($key, $op))
			{
				if(!in_array($gid, $gids))
				{
					$insert[$key]=$gids;
					$insert[$key][]=$gid;

				}
				else
				{
					$insert[$key]=$gids;
				}
			}
			elseif(!in_array($key, $op))
			{
				if(in_array($gid, $gids))
				{
					$insert[$key]=$gids;
					$arr_key=array_search($gid,$insert[$key]);
					array_splice($insert[$key],$arr_key,1);
				}
				elseif (!in_array($gid, $gids))
				{
					$insert[$key]=$gids;
				}
			}
		}
		foreach ($op as $key)
		{
			if(empty($insert[$key]))
			{
				$insert[$key][]=$gid;
			}
		}

		foreach ($insert as $key => $val)
		{
			$gidss=is_array($val)&&$val?implode(',', $val):'';
			$this->membersql->update('credit_rules', array('gids'=>$gidss), array('operation' => trim($key)));
		}

		if (empty($rules_diy))
		{
			$data['rules']='';
		}
		else {

			foreach ($rules_diy as $key => $val)
			{
				$rules_update[$key]=array('credits'=>$val);
				$rules_update[$key]['gids']=implode(',', $insert[$key]);
			}
			$data['rules']=maybe_serialize($rules_update);
		}

		$this->membersql->update('group', $data, array('id' => intval($gid)));
	}
	/**
	 *
	 * 根据应用标识获取自定义积分规则 ...
	 * @param unknown_type $appUniqueid
	 * @param unknown_type $isBatch
	 */
	public function getDiyRulesInfo($appUniqueid,$isBatch = false)
	{
		$arrAppUniqueid = array();
		$strAppUniqueid = '';
		$where = '';
		if(empty($appUniqueid)){
			return array();
		}
		if(!is_array($appUniqueid)){
			$appUniqueid = trim(urldecode($appUniqueid));
			$arrAppUniqueid = explode(',',$appUniqueid);//转为数组方便字符串转换
		}
		else{
			$arrAppUniqueid = $appUniqueid;
		}
		if(!$isBatch&&count($arrAppUniqueid)>1)
		{
			return array();//未开启批量查找，所以禁止多参数
		}
		if($arrAppUniqueid&&is_array($arrAppUniqueid))
		{
			$strAppUniqueid = trim("'".implode("','", $arrAppUniqueid )."'");
			if(count($arrAppUniqueid)>1)
			{
				$where=' AND crca.appid IN( '.$strAppUniqueid.')';
			}
			else{
				$where=' AND crca.appid = '.$strAppUniqueid;
			}
		}
		$sql  = 'SELECT appid,operation,rules FROM '.DB_PREFIX.'credit_rules_custom_app crca WHERE crca.rules <>\'\''.$where;
		$query = $this->db->query($sql);
		$rule = array();
		$_rule = array();
		while ($row = $this->db->fetch_array($query))
		{
			$_rule  =maybe_unserialize($row['rules']);
			$rule[$row['appid']][$row['operation']] = $_rule;
		}
		return $isBatch?$rule:($rule[trim($strAppUniqueid,'\'')]?$rule[trim($strAppUniqueid,'\'')]:array());
	}

	/**
	 * 积分规则自定义函数(应用自定义规则)，如果某个应用的某个定义规则需要删除，则不传即可。否则不需要删除的，都需要重新传一次
	 */
	public function credits_rules_diy_app($appid,$rules_diy)
	{
		/**
		 *
		 *配置属性:字段类型(支持检测类型):type 目前仅支持整形和字符型;支持属性:min 最小(值或者字符串个数)限制,max最大(值或者字符串个数)限制,legal允许填的合法值(例如某个字段只限制0或者1，就设置array(0,1))...
		 *备注：每项参数进行参数检测之前都要进行根据type内容进行强制类型转换
		 * @var array()
		 */
		$configDiyField = array(
		'rname'=>array('type'=>'string','min'=>'1','max'=>'10'),//最短的名字长度，最长的名字长度
		'opened'=>array('type'=>'int','legal'=>array(0)),//允许设置的最大值和最小值(在数据库表现为0为关，1为开)，也就是此积分规则在应用自定义时选择了设置，只允许关闭
		'cyclelevel'=>array('type'=>'int','min'=>'0','max'=>'4'),//允许设置的周期级别范围，具体含义请参考周期级别类型定义
		'cycletype'=>array('type'=>'int','min'=>'0','max'=>'4'),//允许设置的周期类型范围
		'cycletime'=>array('type'=>'int','min'=>'0'),//允许设置的周期类型范围，min为最小，无max则最大不限制
		'rewardnum'=>array('type'=>'int','min'=>'0'),//允许设置的周期类型范围，min为最小，无max则最大不限制
		'credit1'=>array('type'=>'int'),//不限制
		'credit2'=>array('type'=>'int'),//不限制
		);
		$appid=trimall($appid);
		if (empty($appid))
		{
			return array('status'=>0);
		}
		$op=array();
		if($rules_diy&&is_array($rules_diy)){
			$op=array_keys($rules_diy);//取更新的积分规则key
			$rules = $this->getcreditrule($op,true);//获取积分规则
			/**处理提交的数据开始**/
			foreach ($rules_diy as $k => $v)//提交允许自定义的积分规则变量
			{
				if(is_array($v)&&$rules[$k]['iscustom'])
				{
					foreach ($v as $kk => $vv)
					{
						if(array_key_exists($kk, $configDiyField)&&isset($rules[$k][$kk]))
						{
							if($configDiyField[$kk]['type'] == 'string')
							{
								$rules_diy[$k][$kk] = $vv = (string)$vv;
							}
							elseif($configDiyField[$kk]['type'] == 'int')
							{
								$rules_diy[$k][$kk] = $vv = (int)$vv;
							}
							if(is_string($vv))
							{
								$strlen = mb_strlen($vv,'UTF8');
								if(isset($configDiyField[$kk]['min'])&&!($strlen>=$configDiyField[$kk][min]))
								{
									return array('status'=>-1);//值小于最小限制
								}
								if(isset($configDiyField[$kk]['max'])&&!($strlen<=$configDiyField[$kk][max]))
								{
									return array('status'=>-2);//值大于最大限制
								}
								if(isset($configDiyField[$kk]['legal'])&&!in_array($vv, $configDiyField[$kk]['legal']))
								{
									return array('status'=>-3);//值不合法，不在可设置范围
								}
							}
							if(is_int($vv))
							{
								if(isset($configDiyField[$kk]['min'])&&!($vv>=$configDiyField[$kk][min]))
								{
									return array('status'=>-1);//值小于最小限制
								}
								if(isset($configDiyField[$kk]['max'])&&!($vv<=$configDiyField[$kk][max]))
								{
									return array('status'=>-2);//值大于最大限制
								}
								if(isset($configDiyField[$kk]['legal'])&&!in_array($vv, $configDiyField[$kk]['legal']))
								{
									return array('status'=>-3);//值不合法，不在可设置范围
								}
							}
						}
						else {
							unset($rules_diy[$k][$kk]);//unset掉不允许定义的字段，防止非法数据
						}
					}
				}
				else{
					unset($rules_diy[$k]);//unset掉不允许定义的规则，防止非法数据
				}
			}
			/**处理提交的数据结束**/
		}
		$old_rules=array();
		$insert=array();
		$sql="SELECT appids,operation FROM ".DB_PREFIX."credit_rules WHERE appids <>''";
		$query=$this->db->query($sql);
		while($row=$this->db->fetch_array($query))
		{
			if($row['appids']&&is_string($row['appids']))
			{
				$appids=explode(',', $row['appids']);
			}
			else {
				$appids=array();
			}
			$old_rules[$row['operation']]=$appids;
		}
		foreach ($old_rules as $key=>$val)
		{
			if(in_array($key, $op))
			{
				if(!in_array($appid, $val))
				{
					$insert[$key]=$val;
					$insert[$key][]=$appid;

				}
				else
				{
					$insert[$key]=$val;
				}
			}
			elseif(!in_array($key, $op))
			{
				if(in_array($appid, $val))
				{
					$insert[$key]=$val;
					$arr_key=array_search($appid,$insert[$key]);
					array_splice($insert[$key],$arr_key,1);
				}
				elseif (!in_array($gid, $val))
				{
					$insert[$key]=$val;
				}
			}
			if(empty($insert[$key]))
			{
				$insert[$key] = array();
			}
		}
		if(is_array($op))
		foreach ($op as $key)
		{
			if(empty($insert[$key]))
			{
				$insert[$key][]=$appid;
			}
		}

		foreach ($insert as $key => $val)
		{
			$appidss = is_array($val)&&$val?implode(',', $val):'';
			$this->membersql->update('credit_rules', array('appids'=>$appidss), array('operation' => trim($key)));
		}
		if (empty($rules_diy))
		{
			$this->membersql->delete('credit_rules_custom_app', array('appid'=>$appid));
		}
		else {
			$appDiyRules = $this->getDiyRulesInfo($appid);
			$oldOp = array_keys($appDiyRules);
			$delOp = array_diff($oldOp, $op);
			if(is_array($delOp)&&$delOp)//处理掉已经删除的
			{
				$this->membersql->delete('credit_rules_custom_app', array('appid'=>$appid,'operation'=>$delOp));
			}
			$rules_update = array();
			if(is_array($rules_diy)){
				foreach ($rules_diy as $k => $rule)
				{
					foreach ($rule as $key => $val)
					{
						$rules_update[$k][$key] = $val;
					}
					$rules_update[$k]['appids'] = implode(',', $insert[$k]);
				}
			}
			$insertkey  = array('appid'=>$appid);
			$insertData = array();
			foreach ($rules_update as $k => $v)
			{
				$insertkey['operation'] = $k;
				if(array_key_exists($k, $appDiyRules)&&$v){
					$insertData['rules'] = maybe_serialize($v);
					$this->membersql->update('credit_rules_custom_app', $insertData,$insertkey);
				}elseif ($v){
					$insertData = $insertkey;
					$insertData['rules'] = maybe_serialize($v);
					$this->membersql->create('credit_rules_custom_app', $insertData);
				}
			}
		}
		return array('status'=>1,'rules'=>$rules_update);
	}

	/**
	 *
	 * 检测邮箱状态 ...
	 * @param string $email
	 */
	public function check_reg_mail($email,$member_id=0,$identifier = 0)
	{
		$checkemail = 0;//邮箱未传值，未检测
		if($email)
		{
			$checkemail = 1;//开始检测
			if(!hg_check_email_format($email))
			{
				$checkemail = -4;
			}
			if($checkemail>0&&$this->settings['ucenter']['open']&&!$identifier)
			{
				$mMember = new member();
				$ucid = $mMember->checkUc($member_id);
				include_once (CUR_CONF_PATH . 'uc_client/client.php');
				if($ucid)
				{
					$ucInfo = uc_get_user($ucid,1);
					if($ucInfo&&$ucInfo[2]==$email)
					{
						return 1;
					}
				}
				$checkemail = uc_user_checkemail($email);
			}
			if($checkemail>0)
			{
				if($member_id)
				{
					$where=' AND member_id!='.$member_id;
				}
				$sql = 'SELECT count(*) as total FROM ' . DB_PREFIX . 'member_bind WHERE 1 '.$where.' AND platform_id=\''.$email.'\' AND identifier = '.$identifier;
				$result = $this->db->query_first($sql);
				if($result['total'])
				{
					$checkemail = -6;
				}
			}
		}
		return $checkemail;
	}
	/**
	 *
	 * 获取已启用的积分类型 ...
	 */
	public function get_credit_type($field='*')
	{
		$sql='SELECT '.$field.' FROM '.DB_PREFIX.'credit_type  WHERE 1 AND is_on = 1';
		$query=$this->db->query($sql);
		$credit_info=array();
		while($row = $this->db->fetch_array($query))
		{
			if($row['img'])
			{
				$row['img']=maybe_unserialize($row['img']);
			}
			$credit_info[$row['db_field']]=$row;
		}
		return $credit_info;
	}

	/**
	 *
	 * 获取已启用的积分类型字段 ...
	 */
	public function get_credit_type_field()
	{
		$sql='SELECT db_field FROM '.DB_PREFIX.'credit_type  WHERE 1 AND is_on = 1';
		$query=$this->db->query($sql);
		$credit_info=array();
		while($row = $this->db->fetch_array($query))
		{
			$credit_info[]=$row['db_field'];
		}
		return $credit_info?$credit_info:array();
	}

	/**
	 * 统计用户积分
	 */
	public function credits_count($u_id=0,$member_credits=array(),$update=true)
	{
		if(empty($member_credits)&&$u_id)
		{
			$member_credits=$this->membercredit($u_id);
		}
		$credits = $this->credits_count_rules($member_credits);
		if($update&&$u_id)
		{
			$this->db->query('UPDATE '.DB_PREFIX.'member SET credits = '.intval($credits).' WHERE 1 AND member_id = '.$u_id);
		}

		return $credits;
	}
	/**
	 *
	 * 积分统计规则 ...
	 * @param unknown_type $member_credits
	 */
	private function credits_count_rules($member_credits)
	{
		$type = CREDITS_PLAN;//启用统计规则
		$credits = 0;
		if($type == 1)//把可交易类型统计到主表字段
		{
			$credit_field = $this->get_trans_credits_type();
			$credits = $member_credits[$credit_field];
		}
		elseif($type == 2)//把可升级类型统计到主表字段
		{
			$credit_field = $this->get_grade_credits_type();
			$credits = $member_credits[$credit_field];
		}
		else//把所有积分类型统计到主表总积分字段
		{
			if ($member_credits&&is_array($member_credits))
			foreach ($member_credits as $k=>$v)
			{
				$credits +=$v;//未来可扩展为根据自定义公式计算.
			}
		}
		return $credits;
	}
	/**
	 *
	 * 获取签到心情 ...
	 */
	public function get_qdxq()
	{

		$sql = "SELECT name,qdxq,count,img,description FROM " . DB_PREFIX . "sign_emot ";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			if($row['img'])
			{
				$row['img']=maybe_unserialize($row['img']);
			}
			if($row['description'])
			{
				$row['description']=html_entity_decode($row['description']);
			}
			$data[$row['qdxq']]=$row;
		}
		return $data?$data:false;
	}
	/**
	 *
	 * 获取勋章信息 ...
	 * @param array $medalid 勋章id.当is_id为真为必传.当is_batch为真不支持多个.
	 * @param array $field 查询的数据库字段
	 * @param int $available 勋章数据是否开启也查询.
	 * @param bol $is_id medalid 是否必传
	 * @param bol $is_batch 是否支持多数据
	 */
	public function get_medal($medalid,$field='*',$available=1,$is_id = false,$is_batch=true,$orderby='')
	{
		$where='';
		if(($medalid&&is_string($medalid)&&(stripos($medalid, ',')!==false))||is_numeric($medalid)&&!is_array($medalid))
		{
			$medalid = explode(',', $medalid);
		}
		if ($medalid&&is_array($medalid))
		{
			$medalid=array_filter($medalid,"clean_array_null");
			$medalid=array_filter($medalid,"clean_array_num");
			$medalid=trim(implode(',', $medalid));
			if(is_string($medalid)&&(stripos($medalid, ',')!==false)&&$medalid)
			{
				$where=' AND id IN('.$medalid.')';
			}
			else $where=' AND id = '.($medalid);
		}
		elseif($is_id) {
			return array();
		}
		$available = $available !== false ? ' AND available='.intval($available) : '';
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "medal WHERE 1".$available.$where.$orderby;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			if($row['image'])
			{
				$row['image']=maybe_unserialize($row['image']);
				$row['image_url']=hg_fetchimgurl($row['image']);
			}
			if($row['brief'])
			{
				$row['brief']=html_entity_decode($row['brief']);
			}
			if($is_batch)
			{
				$data[$row['id']]=$row;
			}
			else
			{
				$data=$row;
			}
		}
		return $data?$data:false;
	}
	/**
	 *
	 * 获取会员勋章信息(会员id,勋章id,勋章有效期)
	 * @param array $member_id 会员id
	 * @param string $field 数据库查询字段
	 * @param int $is_members 查询模式:1为多用户以member_id为key,0为单用户模式.2为多用户以medalid为key.
	 */
	public function get_member_medal($member_id,$field='*',$is_members=1)
	{
		$where='';
		if ($member_id&&is_array($member_id))
		{
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			$member_id=trim(implode(',', $member_id));
			if(is_string($member_id)&&(stripos($member_id, ',')!==false)&&$member_id)
			{
				$where=' AND member_id IN('.$member_id.')';
			}
			else $where=' AND member_id = '.$member_id;
		}
		else return array();
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member_medal WHERE 1{$where} AND (expiration=0 OR expiration>".TIMENOW.')';
		$query = $this->db->query($sql);
		$member_id = $medalid = $expiration = 0;
		$data=array();
		while($row = $this->db->fetch_array($query))
		{
			$member_id=$row['member_id'];
			$medalid=$row['medalid'];
			$expiration=$row['expiration']?$row['expiration']:0;
			if($is_members==1) {//多会员查询,以member_id为key
				$data[$member_id][$medalid]['expiration']=$expiration;
			}
			elseif(empty($is_members)) {//单用户查询
				$data[$medalid]['expiration']=$expiration;
			}
			elseif($is_members==2) {//多用户查询以medalid为key
				$data[$medalid][$member_id]['expiration']=$expiration;
			}
		}
		return $data?$data:array();
	}
	/**
	 *
	 * 统计某个会员是否拥有此勋章 ...
	 * @param int $member_id
	 * @param int $medalid
	 */
	public function get_member_medal_count($member_id, $medalid,$is_expiration=false)
	{
		$medal_count=array();
		$sql='SELECT COUNT(*) as total FROM '.DB_PREFIX.'member_medal WHERE member_id='.$member_id .' AND medalid='.$medalid.($is_expiration?' AND (expiration=0 OR expiration>'.TIMENOW.')':'');
		$medal_count =  $this->db->query_first($sql);
		return $medal_count['total']?true:false;
	}

	/**
	 *
	 * 统计某个会员是否申请过此勋章 ...
	 * @param int $member_id
	 * @param int $medalid
	 */
	public function get_member_medallog_count($member_id, $medalid)
	{	$medal_count=array();
	$medal_count = $this->db->query_first('SELECT COUNT(*) as total FROM '.DB_PREFIX.'medallog WHERE member_id='.$member_id .' AND medalid='.$medalid.' AND type=2');
	return $medal_count['total']?true:false;
	}

	/**
	 *
	 * 会员勋章数据合并处理 ...
	 * @param array $info
	 * @param unknown_type $medal_infos
	 * @param unknown_type $member_medal
	 * @param unknown_type $is_batch
	 */
	public function make_medal($info,$medal_infos,$member_medal,$is_batch=true)
	{
		if($member_medal&&is_array($member_medal)&&is_array($info))
		{
			$i=0;
			foreach ($member_medal as $k=>$v)
			{
				if($v&&is_array($v))
				{
					foreach ($v as $kk=>$vv)
					{
						$i++;
						$vv['expiration']=$vv['expiration']?date('Y-m-d H:i',$vv['expiration']):'';
						$medal_info=array();
						if($medal_infos[$k])
						{
							$medal_info = $medal_infos[$k];
							$medal_info=array_merge($medal_info,$vv);
						}
						if($is_batch)
						{
							if($medal_info)
							{
								$info[$kk]['medal_info'][]=$medal_info;
							}
							elseif ($i==1){
								$info[$kk]['medal_info']=array();
							}
						}
						else {
							if($i==1)
							{
								$info = $info[$kk];
							}
							if($medal_info)
							{
								$info['medal_info'][]=$medal_info;
							}
							elseif ($i==1){
								$info['medal_info']=array();
							}
						}
					}
				}

			}
		}
		elseif($info&&is_array($info)) {
			if (!$is_batch) {
				$member_id = array_keys($info);
				$info = $info[$member_id[0]];
				$info['medal_info']=array();
			}
			else{
				foreach ($info as $k => $v)
				{
					$info[$k]['medal_info']=array();
				}
			}
		}
		return $info;
	}
	/**
	 *
	 * 检测device_token(消息推送标识)合法性 ...
	 * @param string $device_token
	 */
	public function check_device_token($device_token,$isMobile = 0)
	{
		if($device_token&&($device_token!='www' || $isMobile))
		{
			if($this->settings['App_mobile'])
			{
				include_once (ROOT_PATH.'lib/class/curl.class.php');
				$this->curl = new curl($this->settings['App_mobile']['host'],$this->settings['App_mobile']['dir']);
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('device_token',$device_token);
				$ret = $this->curl->request('mobile_device.php');
				if(!$ret[0])
				{
					return 0;//如果错误返回0
				}
				return $device_token;
			}
		}
		elseif ($device_token)
		{
			return $device_token;//如果传过来的是www则直接输出
		}
		return $isMobile?0:'unknown';//如果未传值则返回unknown
	}
	
	/**
	 *
	 * 检测udid（设备唯一标识）合法性 ...
	 * @param string $uuid 设备唯一标识
	 * @param int $isMobile 是否强制手机
	 */
	public function check_udid($uuid,$isMobile = 0)
	{
		if($uuid&&($uuid!='www' || $isMobile))
		{
			if($this->settings['App_mobile'])
			{
				include_once (ROOT_PATH.'lib/class/curl.class.php');
				$this->curl = new curl($this->settings['App_mobile']['host'],$this->settings['App_mobile']['dir']);
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('uuid',$uuid);
				$ret = $this->curl->request('mobile_device.php');
				if(!$ret[0])
				{
					return 0;//如果错误返回0
				}
				return $uuid;
			}
		}
		elseif ($uuid)
		{
			return $uuid;//如果传过来的是www则直接输出
		}
		return $isMobile?0:'unknown';//如果未传值则返回unknown
	}
	/**
	 *
	 * 获取应用信息列表 ...
	 * @param string $app 需要获取的应用标识 可选
	 * @param unknown_type $field 需要获取的字段 可选
	 */
	public function getApp($app='',$field='name,bundle')
	{
		$auth = new auth();
		$appInfo = $auth->get_app($field,$app);
		$reApp = array();
		if(is_array($appInfo))
		{
			foreach ($appInfo as $v)
			{
				$reApp[$v[bundle]] = array('appname'=>$v[name]);
			}
		}
		return $reApp;
	}
	/**
	 * 
	 * 清空会员TOKEN ...
	 * @param mixed $user_id 会员ID
	 * @param object $auth auth对象
	 * @param int $isKey 是否强制返回带member_id为前缀的 关联数组。默认单个用户清楚不带关联key，多用户默认带关联key。
	 */
	public function force_logout_user($user_id,$auth  = null,$isKey = 0)
	{
		$logoutInfo = array();
		!$auth && $auth = new auth();
		if((is_string($user_id)&&(stripos($user_id, ',')!==false)||$isKey)&&$user_id){
			$userId = array();
			$arrValue = array();
			$arrValue = explode(',',$user_id);//转为数组方便字符串转换
			$arrValue=array_filter($arrValue,"clean_array_null");
		    $arrValue=array_filter($arrValue,"clean_array_num_max0");
		    $userId = $arrValue;
		}
		else {
			$userId = 0;
			$userId = (int)$user_id;
		}
		if(is_array($userId)&&$userId)
		{
			foreach ($userId AS $member_id)
			{
				$logoutInfo[$member_id] = $this->force_logout_user($member_id,$auth,0);
			}	
		}
		elseif($userId&&$auth)  {
			     $logoutInfo =  $auth->force_logout_user($userId);
		}
		return $logoutInfo;
	}
	
	/**
	 * 添加会员行为日志
	 */
	public function addActionLogs($member_id, $to_member_id, $action, $relation_id = 0)
	{
	    if(!$member_id || !$to_member_id)
	    {
	        $this->errorOutput(NO_MEMBER_ID);
	    }
	    if (!$action)
	    {
	        $this->errorOutput(NO_ACTION_TYPE);
	    }
	     
	    $param = array(
	            'member_id' => $member_id,
	            'to_member_id' => $to_member_id,
	            'action' => $action,
	            'relation_id' => $relation_id,
	    );
	    $actionlog = new member_action_log_mode();
	    $result = $this->actionlog->create($param);
	     
	    return $result;
	}

}