<?php
/**
 *
 * 新会员curl,本接口内会员相关信息仅限于新会员应用内的数据 ...
 * @author Ayou
 *
 */
class members
{
	public $operation;
	private $curl;
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_members'])
		{
			$this->curl = new curl($gGlobalConfig['App_members']['host'],$gGlobalConfig['App_members']['dir']);
		}
	}
	function __destruct()
	{
	}

	/**
	 * 初始化operation变量,一般情况下为同一方法下第二次使用Setoperation方法才需要初始化,因为第一次使用在构造方法里已经初始化过.
	 */
	public function Initoperation($operation = '')
	{
		$this->operation = $operation;
	}

	/**
	 *
	 * 自动生成operation
	 * @param string $app_uniqueid 应用id(必选)
	 * @param string $mod_uniqueid 模块id(可选)
	 * @param string $action 操作方法(可选)
	 * @param string $action_op 子操作方法(操作方法下细化控制,可选)
	 */
	public function Setoperation($app_uniqueid,$mod_uniqueid='',$action='',$action_op='')
	{
		if(empty($this->operation))//如果为空则自动生成,不为空则已手动设置.
		{
			$pieces=array();
			if($app_uniqueid)
			{
				$pieces[]=strtolower($app_uniqueid);
			}
			if($mod_uniqueid)
			{
				$pieces[]=strtolower($mod_uniqueid);
			}
			if($action)
			{
				$pieces[]=strtolower($action);
			}
			if($action_op)
			{
				$pieces[]=strtolower($action_op);
			}
			if(is_array($pieces)&&$pieces)
			{
				$this->operation=@implode('_', $pieces);
			}
		}

	}
	
	/**
	 * 更新会员信息
	 */
	public function update($member_id,$data = array())
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
	    if(empty($member_id))
	    {
	        return array();
	    }
	    $this->curl->setSubmitType('post');
	    $this->curl->setReturnFormat('json');
	    $this->curl->initPostData();
	    $this->curl->addRequestData('member_id', $member_id);
	    if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				if (is_array($v))
				{
					$this->array_to_add($k, $v);
				}
				else
				{
					$this->curl->addRequestData($k, $v);
				}
			}
		}
	    $this->curl->addRequestData('a','update');
	    $ret = $this->curl->request('admin/member_update.php');
	    return $ret;
	}

	/**
	 *
	 * 积分规则调用函数 ...
	 * @param int $member_id 用户id
	 * @param string $app_uniqueid 应用id,可选, 传了以后，同一个积分规则，来自不同的应用会增加不同积分(PS：按目前需求此功能暂未提供，只是预留).
	 * @param int $sid 分类id
	 * @param int $cid 内容id
	 */
	public function get_credit_rules($member_id,$app_uniqueid='',$mod_uniqueid='',$sid=0,$cid=0)
	{
		if (!$this->curl)
		{
			return array();
		}

		if(empty($member_id))
		{
			return array();
		}

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('member_id', $member_id);
		if($this->operation)
		{
			$this->curl->addRequestData('operation', $this->operation);
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid',$app_uniqueid);
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid',$mod_uniqueid);
		}
		if($sid)
		{
			$this->curl->addRequestData('sid',$sid);
		}
		if($cid)
		{
			$this->curl->addRequestData('cid',$cid);
		}
		$this->curl->addRequestData('a','credit_rules');
		$ret = $this->curl->request('member_credit_rules_update.php');
		return $ret[0]?$ret[0]:array();
	}
	/**
	 *
	 * 获取会员信息.
	 * @param string $member_id 会员id.如果为detail方法member_id和access_token，必须传一个，member_id优先级最高。但是access_token有权限获取会员更多信息.
	 * @param string $action 需要使用的方法,目前仅支持member.php的show和detail
	 */
	public function get_members($member_id=0,$action='show',$access_token='',$param = array(),$is_admin = false)
	{
		if (!$this->curl||!in_array($action, array('show','detail'))||$action=='detail'&&empty($member_id)&&empty($access_token))
		{
			return array();
		}

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if($member_id)
		{
			$this->curl->addRequestData('member_id', $member_id);
		}
		elseif(is_array($param)&&$param)
		{
			foreach ($param as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		elseif($access_token)
		{
			$this->curl->addRequestData('access_token', $access_token);
		}
		$this->curl->addRequestData('a',$action);
		$file = 'member.php';
		if($is_admin)
		{
			$file = 'admin/'.$file;
		}
		$ret = $this->curl->request($file);
		return $ret;
	}

	/**
	 *
	 * 获取会员数量一般情况用于分页.
	 */
	public function count($param = array(),$is_admin = false)
	{
		if (!$this->curl)
		{
			return array();
		}

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if(is_array($param)&&$param)
		{
			foreach ($param as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('a','count');
		$file = 'member.php';
		if($is_admin)
		{
			$file = 'admin/'.$file;
		}
		$ret = $this->curl->request($file);
		return $ret;
	}

	//获取组会员信息
	public function get_group_member($gid=0,$offset=0,$count=0)
	{
		if (!$this->curl)
		{
			return array();
		}

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('gid', $gid);
		if($offset)
		{
			$this->curl->addRequestData('offset', $offset);
		}
		if($count)
		{
			$this->curl->addRequestData('count', $count);
		}
		$this->curl->addRequestData('a','showmember');
		$ret = $this->curl->request('member_group.php');
		return $ret;
	}

	/**
	 *
	 * 获取组信息.
	 * @param string $member_id 组id.如果为detail方法为必传.否则随意.
	 * @param string $action 需要使用的方法,目前仅支持member_group.php的show和detail
	 */
	public function get_group($gid=0,$action='show')
	{
		if (!$this->curl||!in_array($action, array('show','detail'))||$action=='detail'&&empty($gid))
		{
			return array();
		}

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if($gid)
		{
			$this->curl->addRequestData('id', $gid);
		}
		$this->curl->addRequestData('a',$action);
		$ret = $this->curl->request('member_group.php');
		return $ret;
	}

	//检测会员黑名单
	public function check_blacklist($member_id)
	{
		if(!$this->curl)
		{
			return array();
		}

		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('member_id', $member_id);
		$this->curl->addRequestData('a','check_blacklist');
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	//权限检测,member_id和gid二选一
	public function check_purview($member_id,$gid=0)
	{
		if(!$this->curl)
		{
			return array();
		}

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if($gid)
		{
			$this->curl->addRequestData('gid', $gid);
		}
		elseif($member_id)
		{
			$this->curl->addRequestData('member_id', $member_id);
		}
		if($this->operation)
		{
			$this->curl->addRequestData('operation', $this->operation);
		}
		$this->curl->addRequestData('a','purview');
		$ret = $this->curl->request('member_purview.php');
		return $ret;
	}
	//获取分组权限
	public function group_purview($gid)
	{
		if(!$this->curl)
		{
			return array();
		}

		if(empty($gid))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('gid', $gid);
		$this->curl->addRequestData('a','showpurview');
		$ret = $this->curl->request('member_purview.php');
		return $ret;
	}
	
	/**
	 * 根据token 判断是否权限
	 * @param unknown $access_token
	 * @return Ambigous <string, unknown>
	 */
	public function check_purview_Bytoken($access_token,$operation)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','purview');
		$this->curl->addRequestData('access_token', $access_token);
		$this->curl->addRequestData('operation', $operation);
		$ret = $this->curl->request('member_purview.php');
		return $ret;
	}

	/**
	 *
	 * 获取会员基本信息
	 * @param int $member_id 会员id
	 * @param string $member_name 会员名
	 * @param string $type 会员类型
	 */
	public function get_member_info($member_id=0, $member_name='', $type = 'm2o') {
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_member_info');
		if($member_id)
		{
			$this->curl->addRequestData('member_id', $member_id);
		}
		elseif($member_name)
		{
			$this->curl->addRequestData('member_name', $member_name);
		}
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('member.php');
		return $ret;
	}
	/**
	 *
	 * 增加积分文案处理 ...
	 * @param array $credits
	 */
	public function copywriting_credit($credits)
	{
		if($credits&&is_array($credits))
		{
			if(count($credits)==1)
			{
				return (string)$credits[0][copywriting_credit];
			}
			$credit_type='';
			foreach ($credits as $k => $v)
			{
				if($v['updatecredit'])
				{
					if(empty($credit_type))
					{
						$credit_type = $v['credit_type'];
					}
					if($credit_type&&is_array($credit_type))
					{
						foreach ($credit_type as $kk => $vv)
						{
							$$kk +=intval($v[$kk]);
						}
					}
					else return '';
				}
			}
			if($credit_type&&is_array($credit_type))
			{
				foreach ($credit_type as $k => $v)
				{
					if($$k>0)
					{
						$copywriting_credit_add .='+'.$$k.$v['title'].',';
					}
					elseif($$k<0) {
						$copywriting_credit_sub .=$$k.$v['title'].',';
					}
				}
				return trim($copywriting_credit_add.ltrim($copywriting_credit_sub,','),',');
			}
			return '';
		}
		else
		{
			return '';
		}
	}
	/**
	 *
	 * 检测消费积分是否满足本次消费(非积分消费函数)
	 */
	public function check_consume_credits($member_id,$credit)
	{
		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','check_consume_credits');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		$this->curl->addRequestData('credit', $credit);//需要消费的积分数
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	/**
	 *
	 * 积分消费
	 */
	public function consume_credits($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$ret_url=array(),$title='',$icon= array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','consume_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费订单id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//操作原因：例如：购买xxxx商品
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：购买商品
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		$this->curl->addRequestData('credit', $credit);//操作积分
		$ret = $this->curl->request('member_credits_update.php');
		if($ret['logid']>0)//回调函数
		{
			$_url=$ret_url['url'];
			$_extend=$ret_url['extend'];
			if($_extend)
			{
				$_extend .='&integral_status='.$ret['isFrozen'];
			}
			else{
				$_extend ='integral_status='.$ret['isFrozen'];
			}
			$this->post($_url,$_extend);
		}
		return $ret;
	}

	/**
	 *
	 * 积分消费撤销
	 */
	public function return_credit($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$isFrozen = 0,$title='',$icon=array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','return_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//撤销订单id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//操作原因：例如：撤销订单:xxxx
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：购买商品
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		$this->curl->addRequestData('isFrozen', $isFrozen);//此订单是否冻结积分了
		$this->curl->addRequestData('credit', $credit);//操作积分
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}

	/**
	 *
	 * 取消冻结积分 ...
	 * @param unknown_type $member_id 会员id
	 * @param unknown_type $relatedid 订单id
	 * @param unknown_type $app_uniqueid 应用标识
	 * @param unknown_type $mod_uniqueid 模块标识
	 * @param int $isFrozen 订单是否冻结了积分
	 */
	public function finalFrozenCredit($member_id,$relatedid,$app_uniqueid,$mod_uniqueid,$credit,$isFrozen = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','finalFrozenCredit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费订单id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		$this->curl->addRequestData('credit', $credit);//此订单是否冻结积分了
		$this->curl->addRequestData('isFrozen', $isFrozen);//此订单是否冻结积分了
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}
	/**
	 *
	 * 积分(同时支持credit1或者credit2)增加接口
	 */
	public function add_credit($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$title='',$icon=array())
	{
		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','add_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费物品id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//积分描述：例如：阿尤亲笔签名照片一张
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：购买商品
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		if($credit&&is_array($credit))
		{
			foreach ($credit as $key => $value)
			$this->curl->addRequestData($key, intval($value));//积分(具体名称根据会员后台定义为准)
		}
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}
	/**
	 *
	 * 积分1号增加接口
	 */
	public function add_credit1($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$title='',$icon=array())
	{
		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','add_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费物品id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//操作原因：例如：充值
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：购买商品
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		if($credit)
		{
			$this->curl->addRequestData('credit1', $credit);//积分1号(具体名称根据会员后台定义为准)
		}
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}
	/**
	 *
	 * 积分2号增加接口
	 */
	public function add_credit2($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$title='',$icon=array())
	{
		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','add_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费物品id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//操作原因：例如：充值
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：购买商品
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		if($credit)
		{
			$this->curl->addRequestData('credit2', $credit);//积分2号(具体名称根据会员后台定义为准)
		}
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}

	/**
	 *
	 * 积分（同时支持积分或者经验）减少接口
	 */
	public function sub_credit($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$title = '',$icon = array())
	{
		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','sub_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费物品id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//操作原因：例如：删除帖子
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：违规
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		if($credit&&is_array($credit))
		{
			foreach ($credit as $key => $value)
			$this->curl->addRequestData($key, intval($value));//积分(具体名称根据会员后台定义为准)
		}
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}
	/**
	 *
	 * 积分1号减少接口
	 */
	public function sub_credit1($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$title='',$icon=array())
	{
		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','sub_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费物品id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//操作原因：例如：删除帖子
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：违规
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		if($credit)
		{
			$this->curl->addRequestData('credit1', $credit);//积分1号(具体名称根据会员后台定义为准)
		}
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}

	/**
	 *
	 * 积分2号减少接口
	 */
	public function sub_credit2($member_id,$credit,$relatedid,$app_uniqueid,$mod_uniqueid,$action,$remark,$title = '',$icon=array())
	{
		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','sub_credit');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($relatedid)
		{
			$this->curl->addRequestData('relatedid', $relatedid);//消费物品id
		}
		if($app_uniqueid)
		{
			$this->curl->addRequestData('app_uniqueid', $app_uniqueid);//应用id
		}
		if($mod_uniqueid)
		{
			$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);//模块id
		}
		if($action)
		{
			$this->curl->addRequestData('action', $action);//操作方法
		}
		if($remark)
		{
			$this->curl->addRequestData('remark', $remark);//操作原因：例如：删除帖子
		}
		if($title)
		{
			$this->curl->addRequestData('creditlogtitle', $title);//操作原因：例如：违规
		}
		if($icon)
		{
			/*操作原因：例如：图片地址 格式:  array(
											host: "http://img.dev.hogesoft.com:233/",
											dir: "material/members/img/",
											filepath: "2014/01/",
											filename: "20140110120828blpd.gif"
										);
										*/
			$this->array_to_add('creditlogicon', $icon);
		}
		if($credit)
		{
			$this->curl->addRequestData('credit2', $credit);//积分2号(具体名称根据会员后台定义为准)
		}
		$ret = $this->curl->request('member_credits_update.php');
		return $ret;
	}

	/**
	 *
	 * 积分获取接口(含经验)
	 */
	public function get_member_credits($member_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_member_credits');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	/**
	 *
	 * 检测会员b是否是会员a的黑名单好友.
	 * @param int $member_id 需要检测a会员id
	 * @param int $fb_uid 被检测b会员id
	 */
	public function check_friend_blacklist($member_id,$fb_uid=0)
	{
		if (!$this->curl)
		{
			return array();
		}

		if(empty($member_id))
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','check_friend_blacklist');
		$this->curl->addRequestData('member_id', $member_id);//会员id
		if($fb_uid)
		{
			$this->curl->addRequestData('fb_uid', $fb_uid);//会员id
		}
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	/**
	 * 获取会员的好友黑名单列表
	 *
	 * @return array|mixed|string
	 */
	public function get_friend_blacklist()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$ret = $this->curl->request('member_friend_blacklist.php');
		return $ret[0];
	}

	/**
	 *
	 * 获取已启用的积分类型
	 */
	public function get_credit_type()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_credit_type');
		$ret = $this->curl->request('member_credit_type.php');
		return $ret;
	}

	/**
	 *
	 * 获取允许交易的积分字段(建议使用get_credit_type方法获取,然后写个循环根据is_trans状态判断哪个为交易积分类型同时还可以获得详细的积分配置) ...
	 */
	public function get_trans_credits_type()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_trans_credits_type');
		$ret = $this->curl->request('member_credit_type.php');
		return $ret;
	}

	/**
	 *
	 * 获取允许升级的经验字段(建议使用get_credit_type方法获取,然后写个循环根据is_update状态判断哪个为交易积分类型同时还可以获得详细的积分配置) ...
	 */
	public function get_grade_credits_type()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_grade_credits_type');
		$ret = $this->curl->request('member_credit_type.php');
		return $ret;
	}

	/**
	 * 获取会员的基本信息和扩展信息字段名
	 * $is_base_field  为1，则同时获取基本信息字段，否则不获取。
	 */
	public function get_extension_field($is_base_field = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','extension_fields');
		$this->curl->addRequestData('is_base_field', $is_base_field);
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	/**
	 * 获取会员的基本信息字段名
	 */
	public function get_base_field()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','base_fields');
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	public function update_member_info($access_token,$info)
	{
		if (!$this->curl)
		{
			return array();
		}
		$base_info = array();
		$base_field = $this->get_base_field();
		if(is_array($base_field))
		{
			foreach ($base_field as $v)
			{
				if(isset($info[$v['field']]))
				{
					$base_info[$v['field']] = $info[$v['field']];
					unset($info[$v['field']]);
				}
			}
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','edit');
		$this->curl->addRequestData('access_token',$access_token);
		if(is_array($base_info) && count($base_info) > 0)//传递基本资料
		{
			foreach($base_info as $k => $v)
			{
				$this->curl->addRequestData($k,$v);
			}
		}
		if(is_array($info) && count($info) > 0)//传递扩展资料
		{
			$member_info = array('member_info'=>$info);
			foreach($member_info as $k => $v)
			{
				if(is_array($v))
				{
					$this->array_to_add($k,$v);
				}
				else {
					$this->curl->addRequestData($k, $v);
				}
			}
		}
		$ret = $this->curl->request('member_update.php');
		return $ret;
	}

	private function post($url, $post_data=null)
	{
		$post=array();//需要post数据
		if($post_data&&is_string($post_data))
		{
			$post_data = urldecode($post_data);
			$post_data = explode('&', $post_data);
			if($post_data&&is_array($post_data))
			foreach ($post_data AS $v)
			{
				$v = explode('=', $v);
				if ($v[0])
				{
					$post[$v[0]] = $v[1];
				}
			}
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($post)
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ret = curl_exec($ch);
		$head_info = curl_getinfo($ch);
		curl_close($ch);
		return json_decode($ret, true);
	}
/**
 * 
 * 更新我的数据列表 ...
 * @param array $param 参数数组，具体传啥参数请参照redmine，会员接口文档
 */
	public function updateMyData($param = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		if($param&&is_array($param))
		foreach ($param as $k => $v)
		{
			if(is_array($v))
			{
				$this->array_to_add($k, $v);
			}else {
				$this->curl->addRequestData($k, $v);
			}
		}
		if(APP_UNIQUEID)
		{
			$this->curl->addRequestData('app_uniqueid', APP_UNIQUEID);//应用id
		}
		if(MOD_UNIQUEID)
		{
			$this->curl->addRequestData('mod_uniqueid', MOD_UNIQUEID);//模块id
		}
		$ret = $this->curl->request('member_my_update.php');
		return $ret;
	}
	
	/**
	 * 根据uid获取会员信息
	 * @param unknown $uid
	 * @return boolean|unknown
	 */
	public function get_member_infoByuid($uid)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_member_info');
		$this->curl->addRequestData('member_id', $uid);
		$ret = $this->curl->request('member.php');
		if(empty($ret))
		{
			return false;
		}
		$gid = $ret[$uid]['gid'];
		return $gid;
	}
	
	/**
	 * 根据uanme type获取会员信息
	 * @param unknown $uid
	 * @return boolean|unknown
	 */
	public function get_member_infoByuname($owner_uname = array(),$member_type = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$owner_unames = implode(',',$owner_uname);
		if($member_type)
		{
			$member_types = implode(',',$member_type);
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_member_info');
		$this->curl->addRequestData('member_name', $owner_unames);
		$this->curl->addRequestData('type', $member_types);
		$ret = $this->curl->request('member.php');
		return $ret;
	}
	
	//根据用户id获取用户信息，新会员,批量
	public function get_newUserInfo_by_ids($uid)
	{
		if (!$this->curl)
		{
			return array();
		}
		if (!$uid)
		{
			return false;
		}
		$ret = array();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('member_id',$uid);
		$ret = $this->curl->request('member.php');
		return $ret;
	}
	
	//根据用户id获取用户信息，新会员
	public function get_newUserInfo_by_id($uid)
	{
		if (!$this->curl)
		{
			return array();
		}
		if (!$uid)
		{
			return false;
		}
		$ret = array();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		$this->curl->addRequestData('member_id',$uid);
		$ret = $this->curl->request('member.php');
		$ret = $ret[0];
		if ($ret && is_array($ret))
		{
			if ($ret['extension'] && is_array($ret['extension']))
			{
				foreach ($ret['extension'] as $val)
				{
					if ($val['field'] == 'email')
					{
						$ret['email'] = $val['value'];
					}
					if ($val['field'] == 'add')
					{
						$ret['address'] = $val['value'];
					}
				}
			}
		}
		return $ret;
	
	}
	
	/**
	 * 在我的统计里创建纪录
	 */
	public function createMycount($member_id, $action, $numbers)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
	    $ret = array();
	    $this->curl->setSubmitType('post');
	    $this->curl->setReturnFormat('json');
	    $this->curl->initPostData();
	    $this->curl->addRequestData('a', 'create');
	    $this->curl->addRequestData('member_id',$member_id);
	    $this->curl->addRequestData('action',$action);
	    $this->curl->addRequestData('numbers',$numbers);
	    $ret = $this->curl->request('member_mycount_update.php');
	    return $ret;
	}
	
	/**
	 * 获取我的统计
	 */
	public function getMycount($member_id)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
	    $ret = array();
	    $this->curl->setSubmitType('post');
	    $this->curl->setReturnFormat('json');
	    $this->curl->initPostData();
	    $this->curl->addRequestData('a', 'detail');
	    $this->curl->addRequestData('member_id',$member_id);
	    $ret = $this->curl->request('member_mycount.php');
	    return $ret[0];
	}
	
	/**
	 * 更新我的统计（个人中心）
	 */
	public function updateMycount($member_id,$action, $numbers)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
	    $ret = array();
	    $this->curl->setSubmitType('post');
	    $this->curl->setReturnFormat('json');
	    $this->curl->initPostData();
	    $this->curl->addRequestData('a', 'update');
	    $this->curl->addRequestData('member_id',$member_id);
	    $this->curl->addRequestData('action',$action);
	    $this->curl->addRequestData('numbers',$numbers);
	    $ret = $this->curl->request('member_mycount_update.php');
	    return $ret;
	}

	/*
	 public function  create($content_id,$input='',$files='')
	 {
		if (!$this->curl)
		{
		return array();
		}
		if (!$content_id) return false;
		if (empty($input) && empty($files)) return false;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if(is_array($input) && count($input) > 0)
		{
		foreach($input as $k => $v)
		{
		if(is_array($v))
		{
		$this->array_to_add($k,$v);
		}
		else
		{
		$this->curl->addRequestData($k,$v);
		}
		}
		}
		if(is_array($files) && count($files) > 0)
		{
		$this->curl->addFile($files);
		}
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id', $content_id);
		$this->curl->addRequestData('a','create');
		$ret = $this->curl->request('catalog_update.php');
		return $ret[0];

		}
		*/
	public function array_to_add($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
		else
		{
			$this->curl->addRequestData($str, $data);
		}
	}
}
?>