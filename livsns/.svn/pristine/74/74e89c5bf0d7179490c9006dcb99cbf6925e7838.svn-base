<?php
define('MOD_UNIQUEID','feedback');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/feedback_mode.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
require_once(CUR_CONF_PATH . 'lib/template_mode.php');
class feedback extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'show_result' =>'查看反馈结果',
		'manage'	=>'管理',
		'audit'		=>'状态',
		'_node'=>array(
			'name'=>'表单分类',
			'filename'=>'feedback_node.php',
			'node_uniqueid'=>'feedback_node',
			),
		);
		parent::__construct();
		$this->mode = new feedback_mode();
		$this->members = new members();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$this->verify_content_prms();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND org_id IN('.$this->user['slave_org'].')';
				}
			}
			if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str === '0')
				{
					$condition .= ' AND node_id IN(' . $authnode_str . ')';
				}
				if($authnode_str != -1)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM ' . DB_PREFIX . 'feedback_node WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$authnode_array[$row['id']]= explode(',', $row['childs']);
					}
					$authnode_str = '';
					foreach ($authnode_array as $node_id=>$n)
					{
						if($node_id == intval($this->input['_id']))
						{
							$node_father_array = $n;
							if(!in_array(intval($this->input['_id']), $authnode))
							{
								continue;
							}
						}
						$authnode_str .= implode(',', $n) . ',';
					}
					$authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
					if(!$this->input['_id'])
					{
						$condition .= ' AND node_id IN(' . $authnode_str . ')';
					}
					else
					{
						$authnode_array = explode(',', $authnode_str);
						if(!in_array($this->input['_id'], $authnode_array))
						{
							//
							if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							//$this->errorOutput(var_export($auth_child_node_array,1));
							$condition .= ' AND node_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		if($this->input['_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX . "feedback_node WHERE id in ( " . trim($this->input['_id']).")";
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  node_id in (" . $ret['childs'] . ")";
		}

		####增加权限控制 用于显示####
				
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if(isset($this->input['state']) && $this->input['state'] && urldecode($this->input['state'])!= -1)
		{
			$condition .= " AND status = '".urldecode($this->input['state'])."'";
		}
		else if(urldecode($this->input['state']) == '0')
		{
			$condition .= " AND status = 0 ";
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND weight <= " . $this->input['end_weight'];
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['enddate']) //查询过期的
		{
			$condition .= ' AND  end_time < '.TIMENOW .' AND end_time != 0 ';
		}
		if($this->input['no_enddate']) //查询过期的
		{
			$condition .= ' AND  ( end_time > '.TIMENOW .' OR end_time = 0 )';
		}
		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if(!$ret)
			{
				$this->errorOutput(NO_DATA);
			}
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}

	/**
	 * 获取显示在表单页的常用组件
	 */
	public function get_common()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput('用户未登录');
		}
		$common = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'common WHERE user_id = '.$this->user['user_id'].' or user_id = 0 ORDER BY order_id ASC , id DESC ';
		$q =$this->db->query($sql);
		while($r=$this->db->fetch_array($q))
		{
			if($r['configs'])
			{
				$r['configs'] = unserialize($r['configs']);
				$r = array_merge($r,$r['configs']);
				if($r['fixed_id'] != 4 && $r['conf'])
				{
					$r['conf'] = unserialize($r['conf']);
				}
				if($r['type'] == 'standard')
				{
					$r['mode_type'] = $this->settings['standard'][$r['form_type']];
				}else 
				{
					$r['mode_type'] = $this->settings['fixed'][$r['form_type']];
				}
				unset($r['configs']);
			}
			$common[] = $r;
		}
		$this->addItem($common);
		$this->output();
	}

	public function get_common_conf()
	{
		$id = intval($this->input['id']);
		$template_id = intval($this->input['template_id']);
		if(!$id)
		{
			$this->errorOutput('请传入常用组件id');
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'common WHERE id = '.$id.' ORDER BY id DESC';
		$co[] =$this->db->query_first($sql);
		$ret = $this->mode->get_complete_component($template_id, $co);
		$this->addItem($ret[0]);
		$this->output();
	}
	/**
	 * 获取验证码种类
	 */
	public function get_verify_type()
	{
		include_once(ROOT_PATH . 'lib/class/verifycode.class.php');
		$this->verifycode = new verifycode();
		$ret = $this->verifycode->get_verify_type();
		$this->addItem($ret);
		$this->output();	
	}
	
	/***
	 * 获取积分种类
	 */
	public function get_credit_type()
	{
		$ret = $this->members->get_credit_type();
		$this->addItem($ret);
		$this->output();	
	}
	
	/***
	 * 获取会员扩展字段
	 */
	public function get_extension_field()
	{
		$ret = $this->members->get_extension_field(1);
		if($ret && is_array($ret))
		{
			foreach ($ret as $k=>$v)
			{
				if($v['field'] == 'avatar' || $v['field'] == 'nick_name')
				{
					unset($ret[$k]);
				}
			}
		}
		
		$this->addItem($ret);
		$this->output();	
	}	
	
	/*************取对话消息************/
	public function fetch_message()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT user_id,user_name,feedback_id,message_id FROM '.DB_PREFIX.'record_person WHERE id = '.$id .' ORDER BY create_time DESC';
		$backinfo = $this->db->query_first($sql);
		$msg_id = $backinfo['message_id'];
		if(!$backinfo['user_id'])
		{
			$this->errorOutput('会员未登录，无法获取该用户会员信息');
		}
		if($msg_id && $backinfo['feedback_id'])
		{
			$sql = 'SELECT title,is_reply FROM '.DB_PREFIX.'feedback WHERE id = '.$backinfo['feedback_id'];
			$ret = $this->db->query_first($sql);
			$title = $ret['title'];
			if(!$ret['is_reply'])
			{
				$this->errorOutput('该表单回复功能未开启');
			}
			require_once ROOT_PATH . 'lib/class/curl.class.php';
			$this->curl = new curl($this->settings['App_im']['host'],$this->settings['App_im']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('session_id', $msg_id);
			$this->curl->addRequestData('sort_type', 'ASC');
			$this->curl->addRequestData('a', 'session_detail');
			$message = $this->curl->request('message.php');
			$message = $message[0];
			$message['messages'] = $message['messages'][$msg_id];
			if($message['users'] && is_array($message['users']))
			{
				foreach ($message['users'] as $k=>$v)
				{
					$users[$v['uid']] = $v['utype'];
					if($v['utype'] != 'admin')
					{
						$message['uavatar'] = $v['uavatar'];
						$message['uname'] = $v['uname'];
					}
				}
			}
			if($message['messages']  && is_array($message['messages']))
			{
				foreach ($message['messages'] as $k=>$v)
				{
					$message['messages'][$k]['utype'] = $users[$v['send_uid']];
				}
			}
			$message['title'] = $title;
			if($message)
			{
				$sql = 'UPDATE '.DB_PREFIX.'record_person SET new_reply = 0 WHERE id = '.$id ;
				$this->db->query($sql);
			}
			$message['new_reply'] = 0;
		}
		elseif(!$msg_id && $backinfo['feedback_id'] && $backinfo['user_id'])
		{
			$sql = 'SELECT title FROM '.DB_PREFIX.'feedback WHERE id = '.$backinfo['feedback_id'];
			$ret = $this->db->query_first($sql);
			$title = $ret['title'];
			$users = $this->members->get_members($backinfo['user_id'],'detail');
			$users = $users[0];
			$message = array(
				'title' 	=> $title,
				'uavatar' 	=> $users['avatar'] ? $users['avatar'] : array(),
				'uname'  	=> $backinfo['user_name'],
			);
		}
		else
		{
			$message = array();
		}
		$this->addItem($message);
		$this->output();
	}
	
	public function get_feedback_info()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->get_feedback(' id='.$id);
		if(!$ret)
		{
			$this->errorOutput(NO_DATA);
		}
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if(!$this->user['prms']['default_setting']['show_other_data'] && $ret['user_id'] != $this->user['user_id'])
            {
            	$this->errorOutput(NO_PRIVILEGE);
            }
        }
		$ret['indexpic'] = $ret['indexpic'] ? unserialize($ret['indexpic']) : array();
		$ret['start_time'] = $ret['start_time'] ? date('Y-m-d',$ret['start_time']) : '';
		$ret['end_time'] = $ret['end_time'] ? date('Y-m-d',$ret['end_time']) : '';
		$ret['header_info'] =  $ret['header_info'] ? unserialize($ret['header_info']) : array();
		$ret['footer_info'] =  $ret['footer_info'] ? unserialize($ret['footer_info']) : array();
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_feedback_form()
	{
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->forms($id);
		if(is_array($ret))
		{
			foreach ($ret as $v)
			{
				if($v && $v['options'])
				{
					$options = array();
					foreach ($v['options'] as $vv)
					{
						if(is_array($vv))
						{
							$options[] = $vv;
						}
						else 
						{
							$options[] = array('name'=>$vv);
						}
					}
					$v['options'] = $options;
				}
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	public function get_one_problem()
	{
		$id = intval($this->input['id']);
		$type = trim($this->input['type']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if($type == 'standard')
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'standard WHERE id = '.$id;
		}else 
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'fixed WHERE id = '.$id;
		}
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}

    public function advanced_edit()
    {
        if(!$id = intval($this->input['id']))
        {
            $this->errorOutput(NOID);
        }
        $fb = $this->mode->get_feedback_list('id = '.$id,'create_time');
        if($fb)
        {
            $temp = defined('TEMPLATE_NAME') && TEMPLATE_NAME ? TEMPLATE_NAME : 'baoming';
            $tpl_dirname = CORE_DIR.$temp.'tpl.css';
            $css_file_arr = array( 'index','form');
            if(file_exists($tpl_dirname))
            {
                $tpl_css = @file_get_contents($tpl_dirname);
                if($tpl_css)
                {
                    $ret['tpl'] = $tpl_css;
                }
            }
            foreach($css_file_arr as $v)
            {
                $data_dirname = DATA_DIR.$fb['create_time'].$id.'css/'.$v.'.css';
                if(file_exists($data_dirname))
                {
                    $ret[$v] = @file_get_contents($data_dirname);
                }else
                {
                    $ret[$v] = '';
                }
            }
        }
        $this->addItem($ret);
        $this->output();
    }

    public function create_advanced_css()
    {
        if(!$id = intval($this->input['id']))
        {
            $this->errorOutput(NOID);
        }
        if($css = $this->input['css'])
        {
            $fb = $this->mode->get_feedback_list('id = '.$id,'create_time');
            foreach($css as $k=>$v)
            {
                if($v)
                {
                    $data_dirname = DATA_DIR.$fb['create_time'].$id.'css/'.$k.'.css';
                    $v = str_replace('<NS>',$k,$v);
                    file_put_contents($data_dirname,$v);
                }
            }
        }
        $this->addItem('success');
        $this->output();
    }
}

$out = new feedback();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>