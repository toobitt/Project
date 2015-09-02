<?php

/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: comment.php 5959 2012-02-20 06:46:51Z hanwenbin $
 ***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','message');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."conf/config.php");
require_once CUR_CONF_PATH.'lib/app_config_mode.php';
require_once(CUR_CONF_PATH . 'core/message.dat.php');

class Comment extends outerReadBase
{
	private $curl;
    private $appconfig;
	public function __construct()
	{
		parent::__construct();
        $this->appconfig = new app_config_mode();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	function detail(){}

	public function show()
	{
		//cmid发布库id,cid内容id,member_id评论者Id
		if(!$this->input['cmid'] && !$this->input['cid'] && !$this->input['member_id'] && !$this->input['content_id'] && !$this->user['user_id'] && !$this->input['site_id'] && !intval($this->input['groupid']))
		{
			$this->errorOutput(PARALACK);
		}

		//内容id请求评论时要传入app_uniqueid,mod_uniqueid
		if($this->input['cid'] || $this->input['content_id'])
		{
			if(!$this->input['app_uniqueid'] || !$this->input['mod_uniqueid'])
			{
				$this->errorOutput(NOUNIQUEID);
			}
		}

		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";

		//获取评论设置
		$set = $this->comment_set();

		//显示顺序
		$order = $set['display_order'];
		if ($order)
		{
			$descasc = 'DESC';
		}
		else
		{
			$descasc = 'ASC';
		}

		//查询用户自己的所有评论
		if($this->input['my_conment'] && $this->user['user_id'])
		{
			$member_id = $this->user['user_id'] ? $this->user['user_id'] : $this->input['member_id'];
			$sql = 'SELECT table_name FROM ' . DB_PREFIX . 'comment_point ORDER BY id DESC';
			$q = $this->db->query($sql);
			$table_name = array();
			while ($r = $this->db->fetch_array($q))
			{
				$table_name[] = $r['table_name'];
			}

			if(!empty($table_name))
			{
				$sql = "SELECT * FROM (";
				foreach ($table_name as $v)
				{
					$sql .= " SELECT * FROM " . DB_PREFIX . $v . " UNION ALL ";
				}
				$sql = rtrim($sql,'UNION ALL');
				$sql .= ")t WHERE member_id = " . $member_id . " ORDER BY pub_time DESC " . $limit;
			}
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$res[] = $r;
			}
		}
		else
		{
			//按点赞数排序
			if($this->input['hot_comment'])
			{
				$orderby = ' ORDER BY m.useful ' . $descasc;
			}
			else //默认时间
			{
				$orderby = ' ORDER BY m.id ' . $descasc;
			}
			$condition = $this->get_condition();

			$mes = new Message();
			$field = ' m.id,m.fid,m.title,m.content_title,m.app_uniqueid,m.mod_uniqueid,m.userid,m.username,m.member_id,m.member_type,m.author,m.pub_time,m.ip,m.ip_info,m.state,m.content,m.useful,m.yawp,m.contentid,m.cmid,m.appname,m.order_id,m.last_reply,m.floor,m.floor_reply,m.reply_num,m.baidu_longitude,m.baidu_latitude,m.address ';
			$res = $mes->show($field,$condition,$orderby,$limit);
		}

		if(!$res)
		{
			$this->addItem();
			$this->output();
		}


		$need_reply			= $this->input['need_reply'];//需要查询恢复

		$reply_count		= intval($this->input['reply_count']);
		$reply_count 		= $reply_count? $reply_count : 10;//查询回复个数
		$need_member_info 	= intval($this->input['need_member_info']);//需要用户信息

		$info = array();
		foreach ($res as $k => $v)
		{
			//查询回复上层评论
			if($need_reply && $v['fid'] && $v['tablename'])
			{
				$comment = array();
				$comment = $this->get_comments_reply($v['fid'], $v['tablename'],$reply_count,$need_member_info);
				if(!empty($comment))
				{
					ksort($comment);
					$v['up'] = $comment;
				}
			}

			//替换ip后2位
			if ($v['ip'])
			{
				$arr = array();
				$arr = explode('.', $v['ip']);
				if(!empty($arr))
				{
					$arr[2] 	= '*';
					$arr[3] 	= '*';
					$v['ip'] 	= implode('.', $arr);
				}
			}
				
			//不需要用户信息,显示昵称或者部分ip
			$v['username'] = $this->check_username($v['author'],$v['user_id'],$v['ip'],$v['username']);


			if($need_member_info)
			{
				$member_id[$v['member_type']][$v['member_id']] = 1;
			}
			$info[] = $v;
		}
		######################
		/*$member_id = array(
			0 => array(
			101 => 1,
			102 => 1,
			),
			1=> array(
			1101 => 1,
			1102 => 1,
			),
			);*/
		########################
		if(!empty($info))
		{
			//获取会员信息
			if($need_member_info && $member_id)
			{
				$member_info = $this->get_member_info($member_id);

			}
			######################
			/*$member_id = array(
				0 => array(
				101 => array('nick_name'=>'hoge','sex'=>'male'),
				102 => array('nick_name'=>'hoge','sex'=>'male'),
				),
				1=> array(
				1101 => array('nick_name'=>'hoge','sex'=>'male'),
				1102 => array('nick_name'=>'hoge','sex'=>'male'),
				),
				);*/
			########################
			if($this->input['need_count'])
			{
				foreach ($info as $key => $val)
				{
					if($need_member_info && $member_info && $member_info[$val['member_type']][$val['member_id']])
					{
						$info[$key]['member_info'] = $member_info[$val['member_type']][$val['member_id']];
					}
				}
				$totalcount = $this->return_count();
				$this->addItem_withkey('total',$totalcount['total'] );
				$this->addItem_withkey('data',$info );
			}
			else
			{
				foreach ($info as $key => $val)
				{
					//反序列化最后一次回复
					if($val['last_reply'])
					{
						$val['last_reply'] = unserialize($val['last_reply']);
					}
					//整理会员信息
					if($need_member_info && $member_info && $member_info[$val['member_type']][$val['member_id']])
					{
						$val['member_info'] = $member_info[$val['member_type']][$val['member_id']];
					}
					
					if($this->user['user_id'] && $val['member_id'] == $this->user['user_id'])
					{
						$val['is_my'] = 1;
					}
					else 
					{
						$val['is_my'] = 0;
					}
					$this->addItem($val);
				}
			}
		}
		$this->output();
	}

	/**
	 *
	 * 读取评论回复
	 * @param unknown_type $fid 评论id
	 * @param unknown_type $table_name 表名
	 * @param unknown_type $count 分页数目
	 * @param int $need_member_info  是否需要用户信息
	 */
	private function get_comments_reply($fid,$table_name,$count,$need_member_info='',$arr=array())
	{
		if(!$fid || !$table_name)
		{
			return false;
		}
		$sql = "SELECT * FROM ".DB_PREFIX . $table_name . " WHERE state = 1 AND id = " . $fid . " ORDER BY id DESC";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			//控制返回上级评论个数
			if(count($arr) == $count)
			{
				return $arr;
			}
			if($r['last_reply'])
			{
				$r['last_reply'] = unserialize($r['last_reply']);
			}
			$r['pubtime'] = $r['pub_time'];
			$r['pub_time'] = date('Y-m-d H:i:s',$r['pub_time']);
			if($r['fid']==0)
			{
				$arr[$r['id']] = $r;
				return  $arr;
			}
			else
			{
				$arr[$r['id']] = $r;
				return $this->get_comments_reply($r['fid'],$table_name,$count,$need_member_info,$arr);
			}
		}
	}


	private function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND m.content LIKE "%' . trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND m.id = ' . intval($this->input['id']);
		}
		//内容id
		if($this->input['cid'])
		{
			$condition .= ' AND m.contentid = ' . intval($this->input['cid']);
		}
		//内容id
		if($this->input['content_id'])
		{
			$condition .= ' AND m.contentid = ' . intval($this->input['content_id']);
		}
		//作者id
		if($this->input['member_id'])
		{
			$condition .= ' AND m.member_id = ' . intval($this->input['member_id']);
		}

		//我的评论
		if($this->input['my_comment'])
		{
			if($this->user['user_id'])
			{
				$condition .= ' AND m.member_id = ' . intval($this->user['user_id']);
			}
			elseif ($this->input['member_id'])
			{
				$condition .= ' AND m.member_id = ' . intval($this->input['member_id']);
			}
			else
			{
				return false;
			}
			//会员类型,1是新会员,0是老会员
			if($this->input['member_type'])
			{
				$condition .= ' AND m.member_type = ' . intval($this->input['member_type']);
			}
			else
			{
				$condition .= ' AND m.member_type = 0';
			}
		}
		//被评论内容发布id
		if($this->input['cmid'])
		{
			$condition .= ' AND m.cmid = ' . intval($this->input['cmid']);
		}
		//内容所在应用标识
		if($this->input['app_uniqueid'])
		{
			$condition .= ' AND m.app_uniqueid = "' .urldecode($this->input['app_uniqueid']).'"';
		}
		//模块标识
		if($this->input['mod_uniqueid'])
		{
			$condition .= ' AND m.mod_uniqueid = "' .urldecode($this->input['mod_uniqueid']).'"';
		}
		//站点id
		if($this->input['site_id'])
		{
			$condition .= ' AND m.site_id = '.intval($this->input['site_id']);
		}
		//栏目id
		if($this->input['column_id'])
		{
			$condition .= ' AND m.column_id = ' . intval($this->input['column_id']);
		}

		//分类id
		if(intval($this->input['groupid']))
		{
			$condition .= ' AND m.groupid = ' . intval($this->input['groupid']);
		}
		$condition .= ' AND m.state=1';

		return $condition;
	}

	public function count()
	{
		$mes = new Message();
		if($this->input['cmid'])
		{
			$mes->get_publish_content($this->input['cmid']);
		}
		$condition = $this->get_condition();

		$mes->count($condition);
	}

	public function return_count()
	{
		$mes = new Message();
		if($this->input['cmid'])
		{
			$mes->get_publish_content($this->input['cmid']);
		}
		$condition = $this->get_condition();
		return $mes->return_count($condition);
	}

	//获取客户端评论条数
	public function get_comment_num()
	{
		$appid = intval($this->user['appid']);
		$sql = "SELECT comment_num FROM " . DB_PREFIX . "message_appinfo WHERE appid = " . $appid;
		echo json_encode($this->db->query_first($sql));
	}

	//添加评论
	public function add_message()
	{
		$content = trim($this->input['content']);

		if(!$content)
		{
			$this->errorOutput(NOCONTENT);
		}
		if(!get_magic_quotes_gpc())
		{
			$content = addslashes($content);
		}

		//根据发布id查询信息
		$cmid = intval($this->input['cmid']);
		if(!$cmid)
		{
			//非发布库内容评论要传入应用标识和模块标识
			if(!$this->input['app_uniqueid'] || !$this->input['mod_uniqueid'])
			{
				$this->errorOutput(NOUNIQUEID);
			}
		}

		//评论设置
		$set = $this->comment_set();

		$contentid = intval($this->input['contentid']);
		if(!$contentid)
		{
			$this->errorOutput(NOCONTENTID);
		}

		$display 		= $set['display']; 		//是否审核显示
		$max_word 		= $set['max_word'];		//评论最大字数
		$login 			= $set['is_login'];		//是否登录评论
		$colation 		= $set['colation'];		//是否过滤
		$is_open 		= $set['state']; 		//评论开启关闭
		$rate 			= $set['rate'];			//评论频率限制
		$allow_reply 	= $set['allow_reply'];	//回复设置
		$verify_mode	= $set['verify_mode'];	//验证码
		$is_credits     = $set['is_credits']; 	//未审核获取积分
		$is_credits_extra = $set['is_credits_extra'];//审核获取积分
		$is_diy_credits = $set['is_diy_credits'];//自定义积分规则
		$same_user_same_record = $set['same_user_same_record'];

		//评论功能开启／关闭
		if(!$is_open)
		{
			$this->errorOutput(MESSAGECLOSED);
		}

		$fid = intval($this->input['fid']);
		if($fid && !$allow_reply)
		{
			$this->errorOutput(REPLYCLOSED);
		}
		//登陆评论
		if($login)
		{
			if($this->user['user_id'] < 1)
			{
				$this->errorOutput(NOTLOGIN);
			}
		}

		if($max_word)
		{
			//评论长度判断
			$len = '';
			$len = strlen($content);
			if($len>$max_word*3)
			{
				$this->errorOutput(MAXNUM);
			}
		}

		//审核显示
		$state = $display ? 0 : 1;

        if($app_id = $this->input['app_id'])
        {
            $appconfig = $this->appconfig->detail($app_id);
            if($appconfig['comment_audit'] == 0)
            {
                $state = 1;
            }
            else
            {
                $state = 0;
            }
        }

		//过滤敏感词
		if($colation && $this->settings['App_banword'])
		{
			include_once(ROOT_PATH . 'lib/class/banword.class.php');
			$this->banword = new banword();
			$banword = $this->banword->exists($content);
			if($banword)
			{
				$colation_state = '';
				if($colation == 1)//禁止入库
				{
					$this->errorOutput(BANWORD);
				}
				elseif ($colation == 2)//入库,标识敏感词
				{
					$colation_state = 3;
				}
				elseif($colation == 3)//替换敏感词
				{
					$content = $this->banword->replace($content,'*');
					$colation_state = 0;//替换后状态为未审核
				}

				//如果存在敏感词，敏感词的设置高于普通设置
				if ($colation_state)
				{
					$state = $colation_state;
				}

				//记录敏感词
				$banwords = array();
				foreach ($banword as $v)
				{
					$banwords[] = $v['banname'];
				}
				$banwords = implode(',', $banwords);
			}
		}

		//验证码开启并且安装验证码
		if ($this->settings['App_verifycode'] && $verify_mode)
		{
            if(!$this->input['not_need_verifycode'])
            {
                include_once ROOT_PATH . 'lib/class/verifycode.class.php';
                $this->mVerifyCode = new verifycode();

                $verifycode = trim($this->input['verify_code']);
                $session_id = trim($this->input['session_id']);

                if(!$verifycode || !$session_id)
                {
                    $this->errorOutput(VERIFY);
                }
                $check_result = $this->mVerifyCode->check_verify_code($verifycode, $session_id);  //验证验证码
                if( $check_result != 'SUCCESS')
                {
                    $data['error'] = $check_result;
                    $this->addItem($data);
                    $this->output();
                }
            }
		}
		//兼容user_name传递用户昵称
		if($this->input['user_name'] && !$this->input['author'])
		{
			$this->input['author'] = $this->input['user_name'];
		}

		$data = array(
			'title'			=> urldecode($this->input['title']),
			'username'		=> $this->user['user_name'],
			'author' 		=> trim($this->input['author']),
			'member_id' 	=> $this->user['user_id'],
			'content'		=> $content,
			'pub_time'		=> TIMENOW,
			'ip'			=> hg_getip(),
			'state'			=> $state,
			'contentid'		=> $contentid,
			'content_title' => $this->input['content_title'],
			'content_url'	=> $this->input['content_url'],
			'cmid' 			=> $cmid,
			'app_uniqueid'	=> $this->input['app_uniqueid'],
			'mod_uniqueid'	=> $this->input['mod_uniqueid'],
			'site_id' 		=> $this->input['site_id'],
			'column_id' 	=> $this->input['column_id'],
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'long' 			=> $this->input['long'],
			'lati' 			=> $this->input['lati'],
			'banword'		=> $banwords,
			'fid'			=> $fid,
			'member_type'	=> intval($this->input['member_type']),//会员类型
			'groupid'		=> intval($this->input['groupid']),
		
			'baidu_longitude' => $this->input['baidu_longitude'],
			'baidu_latitude' => $this->input['baidu_latitude'],
			'address'		=> $this->input['address'],
		);

		//获取ip的地域信息
		if (function_exists('hg_getIpInfo'))
		{
			$data['ip_info'] = hg_getIpInfo($data['ip']);
		}

		//入库
		$mes = new Message();
		$res = $mes->add_message($data,$rate);

		if(!$res)
		{
			$this->errorOutput(RATE);
		}

		$res['pub_time'] = date('Y-m-d H:i:s',$res['pub_time']);
		/***********************调用积分规则,给已审核评论增加积分START*****************/
		if($this->input['iscreditsrule'])//是启用新会员系统
		{
			include (ROOT_PATH . 'lib/class/members.class.php');
			$Members = new members();
			if($this->settings['App_members'])
			{
				if($res['member_id']&&$same_user_same_record)//统计同一个会员在同一个应用同一条记录的评论次数
				{
					$res_total=$mes->return_count(" AND contentid=".$res['contentid']." AND cmid = ".$res['cmid']." AND app_uniqueid = '".$res['app_uniqueid']."' AND mod_uniqueid = '".$res['mod_uniqueid']."' AND member_id=" . $res['member_id'],$tableName);
				}
				if($res_total['total']-1<$same_user_same_record||empty($same_user_same_record))
				{
					$Members->Initoperation();//初始化
					$Members->Setoperation(APP_UNIQUEID);
					/***未审核增加积分**/
					if(($is_credits)&&$this->user['user_id'])
					{
						$credit_rules=$Members->get_credit_rules($this->user['user_id'],$data['app_uniqueid'],$data['mod_uniqueid'],$data['column_id'],$data['contentid']);
					}
					/***审核增加积分**/
					if(($is_credits_extra&&$res['state'] == 1)&&$this->user['user_id'])//审核增加积分为真&&已审核状态&&有user_id
					{
						$Members->Initoperation();//初始化
						$Members->Setoperation(APP_UNIQUEID,'','','extra');
						$credit_rules_extra=$Members->get_credit_rules($this->user['user_id'],$data['app_uniqueid'],$data['mod_uniqueid'],$data['column_id'],$data['contentid']);
						$field='is_credits=0';//已经增加积分
					}
					elseif(empty($is_credits_extra))
					{
						$field='is_credits=-1';//禁止增加积分,因为未开启审核增加积分,所以即使审核也不增加
					}
				}
				else {
					$field='is_credits=-1';//禁止增加积分,此会员此条记录评论超过限制次数
				}
			}
			/**积分文案处理**/
			$credit_copy=array();
			if($credit_rules['updatecredit'])
			{
				$credit_copy[]=$credit_rules;
			}
			if($credit_rules_extra['updatecredit'])
			{
				$credit_copy[]=$credit_rules_extra;
			}
			$res['copywriting_credit'] = $Members->copywriting_credit($credit_copy);
			/**积分文案处理结束**/
		}
		else {
			$field='is_credits=-1';	//禁止增加积分,非新会员
		}
		/**更新获得积分字段**/
		if($field)
		{
			$this->db->query("UPDATE " . DB_PREFIX . "{$res['tableame']} SET ".$field." WHERE id=".$res['id']);
		}
		/***********************调用积分规则,给已审核评论增加积分END*****************/
			
		//已审核状态
		if($res['state'] == 1)
		{
			/***********************即时更新内容评论计数****************************/
			if($this->settings['App_' . $data['app_uniqueid']])
			{
				$path['host'] = $this->settings['App_' . $data['app_uniqueid']]['host'];
				$path['dir'] = $this->settings['App_' . $data['app_uniqueid']]['dir'].'admin/';

				if($path)
				{
					$host = $path['host'];
					$dir = $path['dir'];
					$filename = $data['app_uniqueid'];
					if($filename == 'livmedia')
					{
						$filename = 'vod';
					}
					else if($filename == 'cheapbuy')
					{
						$filename = 'product';
					}



					include_once(ROOT_PATH.'lib/class/curl.class.php');
					$curl = new curl($host,$dir);
					$curl->setSubmitType('post');
					$curl->initPostData();
					$curl->addRequestData('a','update_comment_count');
					$curl->addRequestData('id',$data['contentid']);
					$curl->addRequestData('type','audit');
					$curl->request($filename.'_update.php');
				}
			}
			/***********************即时更新内容评论计数****************************/
			
			/***********************更新会员我的评论计数****************************/
			if($this->settings['App_members'])
			{
				$path['host'] = $this->settings['App_members']['host'];
				$path['dir'] = $this->settings['App_members']['dir'];

				if($path)
				{
					include_once(ROOT_PATH.'lib/class/curl.class.php');
					$curl = new curl($path['host'],$path['dir']);
					$curl->setSubmitType('post');
					$curl->initPostData();
					$curl->addRequestData('a','create');
					$curl->addRequestData('mark','mymessage');
					$curl->addRequestData('totalsum',1);
					$curl->addRequestData('summath',1);
					$curl->addRequestData('access_token',$this->user['token']);
					$curl->request('member_my_update.php');
				}
			}
			/***********************更新会员我的评论计数****************************/
		}

		//统计app下面的评论计数
		if($data['appid'])
		{
			$sql = "SELECT appid FROM " . DB_PREFIX . "message_appinfo WHERE appid = " . $data['appid'];
			if($this->db->query_first($sql))
			{
				$sql = "UPDATE " . DB_PREFIX . "message_appinfo SET appname='".$data['appname']."',comment_num = comment_num+1 WHERE appid=".$data['appid'];
			}
			else
			{
				$sql = "INSERT INTO " . DB_PREFIX . "message_appinfo SET appid=".$data['appid'].",appname='".$data['appname']."',comment_num=comment_num+1";
			}
			$this->db->query($sql);
		}
		$res['copywriting']='评论成功';
		$this->addItem($res);
		$this->output();
	}

	//模块取配置
	public function  mod_set()
	{
		$set = $this->comment_set();
		$this->addItem($set);
		$this->output();
	}

	//取各自应用配置
	private function comment_set()
	{
		//配置缓存文件不存在时，生成缓存文件
		if(!file_exists('./cache/comment_set_cache.php'))
		{
			$mes = new Message();
			$mes->build_comment_set_cache();
		}

		global $gGlobalConfig;
		include_once('./cache/comment_set_cache.php');
		//存在发布库id,请求发布库获取内容id和标识
		if(intval($this->input['cmid']))
		{
			$mes = new Message();
			$mes->get_publish_content($this->input['cmid']);
		}

		//读取评论传递参数
		if($this->input['cid'])
		{
			$this->input['contentid'] = $this->input['cid'];
		}

		//原cms读取评论传递参数
		if($this->input['content_id'])
		{
			$this->input['contentid'] = $this->input['content_id'];
		}
		//查找内容的评论设置(contentid为手机接口传递参数)
		if($this->input['contentid'])
		{
			//$sql = 'SELECT value FROM '.DB_PREFIX.'app_settings WHERE bundle_id="'.$this->input['app_uniqueid'].'" AND module_id="'.$this->input['mod_uniqueid'].'" AND content_id='.intval($this->input['contentid']);
			$sql = 'SELECT value FROM '.DB_PREFIX.'app_settings WHERE var_name="'.$this->input['app_uniqueid'].'_'.$this->input['mod_uniqueid'].'"';
			$res = $this->db->query_first($sql);
		}
			
		//内容设置存在，直接取内容的评论设置
		if($res)
		{
			$set = @unserialize($res['value']);
		}
		else //内容设置没有，先查内容所属的模块设置，模块设置没有再取应用设置
		{
			if($this->input['app_uniqueid'] && $this->input['mod_uniqueid'])
			{
				$key = $this->input['app_uniqueid'].'_'.$this->input['mod_uniqueid'];
				$app_key = $this->input['app_uniqueid'];
			}
			else
			{
				$key = $this->input['app_uniqueid'];
			}
			if($this->settings[$key])
			{
				$set = $this->settings[$key];//模块设置或者应用设置
			}
			else if($this->settings[$app_key])
			{
				$set = $this->settings[$app_key];//应用设置
			}
		}
		return $set;
	}
	/**
	 *
	 * Enter description here ...
	 * 确定评论作者
	 * @param unknown_type $author
	 * @param unknown_type $user_id
	 * @param unknown_type $ip
	 */
	private function check_username($author='',$user_id='',$ip='',$user_name='')
	{
		//有昵称显示昵称
		if($author)
		{
			return $author;
		}

		if($user_name)
		{
			//对手机用户名做处理
			if (is_numeric($user_name) && strlen($user_name)==11)
			{
				$username = str_replace(substr($user_name, 3,4), '****', $user_name);
			}
			else
			{
				$username = $user_name;
			}
		}
		//非后台添加且没有username，显示部分ip
		elseif (!$user_id && $ip)
		{
			$username = $ip;
		}
		else
		{
			$username = '网友';
		}
		return $username;
	}
	/**
	 *
	 * Enter description here ...
	 * 获取会员信息
	 * @param unknown_type $member_id 会员ids
	 */
	private function get_member_info($member_id=array())
	{

		if(is_array($member_id))
		{
			if($member_id[0])
			{
				$mem_ids 		= array_keys($member_id[0]);//老会员
			}
			if($member_id[1])
			{
				$mem_new_ids 	= array_keys($member_id[1]);//新会员
			}
		}

		if($mem_new_ids)
		{
			if(is_array($mem_new_ids))
			{
				$member_ids = implode(',', $mem_new_ids);
			}
			if( $member_ids)
			{
				$this->create_curl_obj('members');
				$params['member_id'] = $member_ids;
				$params['a'] = 'show';
				$params['r']= 'member';
				$member_new_infos = $this->get_common_datas($params);
				
				if($member_new_infos)
				{
					foreach($member_new_infos as $k => $v)
					{
						$mem_info = array();
						$mem_info['id']				= $v['member_id'];
						
						//对手机用户名做处理
						if (is_numeric($v['nick_name']) && strlen($v['nick_name'])==11)
						{
							$v['nick_name'] = str_replace(substr($v['nick_name'], 3,4), '****', $v['nick_name']);
						}
						
						$mem_info['nick_name'] 		= $v['nick_name'];
						
						
						$mem_info['avatar']['host']			= $v['avatar']['host'];
						$mem_info['avatar']['dir']			= $v['avatar']['dir'];
						$mem_info['avatar']['filepath']		= $v['avatar']['filepath'];
						$mem_info['avatar']['filename']		= $v['avatar']['filename'];

						$mem_infos[$v['member_id']] = $mem_info;
							
					}
				}
				$member_info[1] = $mem_infos;
			}
		}

		if($mem_ids)
		{
			if(is_array($mem_ids))
			{
				$member_ids = implode(',', $mem_ids);
			}

			include_once(ROOT_PATH . 'lib/class/member.class.php');
			$member = new member();

			$member_infos = $member->getMemberByIds($member_ids);
			$member_info[0] = $member_infos[0];
		}
		return $member_info;
	}

	/**
	 * 获取更多回复
	 * Enter description here ...
	 */
	public function get_more_reply()
	{
		$fid = intval($this->input['fid']);
		if(!$fid)
		{
			return false;
		}
		$table_name = $this->input['table_name'];
		if(!$table_name)
		{
			$this->errorOutput('表名不存在');
		}
		$count = intval($this->input['reply_count']);
		$count = $count ? $count : 10;

		$need_member_info = $this->input['need_member_info'];

		$comment = $this->get_comments_reply($fid, $table_name,$count,$need_member_info);
		if($comment)
		{
			ksort($comment);
		}

		if($comment)
		{
			foreach ($comment as $k => $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}

	//对回复满意
	public function message_useful()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}

		$user_id = intval($this->user['user_id']);
		if(!$user_id)
		{
			$this->errorOutput(NOLOGIN);
		}
		$ip = hg_getip();
		$data = array(
			'id'		=> $id,
			'ip'		=> $ip,
			'user_id'	=> $user_id,
		);
		$obj = new message();
		$res = $obj->comment_vote($data);
		$this->addItem($res);
		$this->output();
	}

	//对回复不满意
	public function message_yawp()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}

		$user_id = intval($this->user['user_id']);
		if(!$user_id)
		{
			$this->errorOutput(NOLOGIN);
		}
		$ip = hg_getip();
		$data = array(
			'id'		=> $id,
			'ip'		=> $ip,
			'user_id'	=> $user_id,
		);
		$obj = new message();
		$res = $obj->comment_vote($data,'ywap');

		$this->addItem($res);
		$this->output();
	}

	//回复留言
	function reply_message()
	{
		$fid = intval($this->input['id']);
		if(!$fid)
		{
			$this->errorOutput(NOID);
		}
		$content = urldecode($this->input['reply_content']);
		if(!$content)
		{
			$this->errorOutput(NOCONTENT);
		}
		$tablename = $this->input['tablename'];
		if(!$tablename)
		{
			$this->errorOutput(NO_TABLENAME);
		}
		$data = array(
			'fid'				=> $fid,
			'title'				=> $this->input['title'],
			'content'			=> $content,
			'member_id'			=> $this->input['user_id'],
			'pub_time'			=> TIMENOW,
			'username'			=> $this->user['user_name'],
			'cmid'				=> intval($this->input['cmid']),
			'contentid'			=> intval($this->input['contentid']),
			'app_uniqueid'		=> $this->input['app_uniqueid'],
			'mod_uniqueid'		=> $this->input['mod_uniqueid'],
			'member_type'		=> intval($this->input['member_type']),
		);

		$mes = new Message();
		$res = $mes->reply_comment($fid,$data,$tablename);
		$this->addItem($res);
		$this->output();
	}

	//定时计划任务更新评论计数，（暂时不用）
	function update_comment_count()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "message_count limit 0,10";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$arr[$r['app_uniqueid']][$r['mod_uniqueid']][$r['content_id']] = $r['count_comment'];
		}

		if(is_array($arr) && count($arr))
		{
			foreach ($arr as $key=>$val)
			{
				foreach ($val as $k=>$v)
				{
					$sql = "SELECT * FROM ".DB_PREFIX."app WHERE bundle='".$key."' AND module='".$k."'";
					$res = $this->db->query_first($sql);
					$arr[$key][$k]['lj'] = $res;
				}
			}

			include_once(ROOT_PATH.'lib/class/curl.class.php');
			foreach ($arr as $key => $val)
			{
				foreach ($val as $k => $v)
				{
					$lj = $v['lj'];
					unset($v['lj']);
					//循环更新各内容评论计数
					$curl = new curl($lj['host'],$lj['dir']);
					$curl->setSubmitType('get');
					$curl->initPostData();
					$curl->addRequestData('a','update_comment_count');

					foreach ($v as $kk => $vv)
					{
						$curl->addRequestData('count_comment',$vv);
						$curl->addRequestData('id',$kk);
						$res = $curl->request($lj['filename'].'.php');

						if($res)
						{
							$sql = "DELETE FROM ".DB_PREFIX."message_count WHERE app_uniqueid='".$key."' AND mod_uniqueid='".$k."' AND content_id=".$kk;
							$this->db->query($sql);
						}
					}
				}
			}
		}
	}

	//更新评论cmid
	public function update_comment_cmid()
	{
		$sql = 'SELECT id,app_uniqueid,mod_uniqueid,contentid,cmid FROM '.DB_PREFIX.'message';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['app_uniqueid'] != 'column' && $r['mod_uniqueid'] != 'column' && !$r['cmid'] && $r['app_uniqueid'] && $r['mod_uniqueid'])
			{
				$arr[$r['id']]['bundle_id'] = $r['app_uniqueid'];
				$arr[$r['id']]['module_id'] = $r['mod_uniqueid'];
				$arr[$r['id']]['content_fromid'] = $r['contentid'];
			}
		}
		if(!empty($arr))
		{
			$mes = new Message();

			foreach ($arr as $id => $con)
			{
				$res = $mes->get_content_by_other($con);

				if($res[0]['id'])
				{
					$sql = 'UPDATE '.DB_PREFIX.'message SET cmid = '.$res[0]['id'].' WHERE id='.$id;
					$this->db->query($sql);
				}
			}
		}
	}


	/**
	 * 获取评论数最多的信息
	 * author:scala
	 */
	public function get_most_comment()
	{
		//$cond = " where a.fid=b.id and a.state=1 ";
		$cond = " where 1 and a.state=1 ";
		if(isset($this->input['app_uniqueid']))
		{
			$cond .= " and a.app_uniqueid='".trim($this->input['app_uniqueid'])."'";
		}
		if(isset($this->input['mod_uniqueid']))
		{
			$cond .= " and a.mod_uniqueid='".trim($this->input['mod_uniqueid'])."'";
		}
		//$query = "select count(a.id) as total ,a.* from ".DB_PREFIX."message a,".DB_PREFIX."message b $cond group by a.fid  order by total desc";
		$query = "select count(a.id) as total ,a.* from ".DB_PREFIX."message a $cond group by a.contentid  order by total desc";
		$result = $this->db->query($query);
		while (($r = $this->db->fetch_array($result))!=false)
		{
			$r['pub_time'] = date('Y-m-d H:i:s',$r['pubtime']);
			$data[] = $r;
			$this->addItem($r);
		}
		$this->output();
	}
	/**
	 * 创建curl
	 */
	public function create_curl_obj($app_name)
	{
		$key = 'App_'.$app_name;
		global $gGlobalConfig;
		if(!$gGlobalConfig[$key])
		{
			return false;
		}
		$this->curl = new curl($gGlobalConfig[$key]['host'], $gGlobalConfig[$key]['dir']);
	}

	/**
	 * 解析curl数据
	 */
	public function get_common_datas($params)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		foreach($params as $key=>$val)
		{
			if($key!='r')
			{
				$this->curl->addRequestData($key,$val);
			}
			else
			{
				return $this->curl->request($val.".php");
			}
		}
	}
}

$out = new Comment();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$res = $out->$action();

?>
