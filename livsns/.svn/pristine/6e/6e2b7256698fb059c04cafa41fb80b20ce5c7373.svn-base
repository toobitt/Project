<?php
define('MOD_UNIQUEID','feedback');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/feedback_mode.php');
//require_once(CUR_CONF_PATH . 'lib/mood_mode.php');
class feedback extends outerReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new feedback_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$total=json_decode($this->count(),1);
		$count = $this->input['count'] ? intval($this->input['count']) : 5;
// 		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$offset = ( $this->input['page'] -1 )  * $count;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show_all($condition,$orderby,$limit);
		
		$data['page_info']['total_page']=ceil($total['total'] / $count);
		$data['page_info']['total_num']=$total['total'];
		$data['page_info']['page_num']=$count;
		$data['page_info']['current_page']=$this->input['page'];
		$data['info']=$ret;
	    echo json_encode($data);exit;
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
		return  json_encode($info);
// 		echo json_encode($info);
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->show_detail($this->input['id']);
		if(!$ret['title'])
		{
			$this->errorOutput(NODATA);
		}
		/***从会员接口取会员相关信息和拓展信息****/
		if($this->user['user_id'] && $this->settings['App_members'])
		{
			$curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir'].'admin/');
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('id', $this->user['user_id']);
			$curl->addRequestData('a', 'detail');
			$info = $curl->request('member.php');
			$info = $info[0];
			if($info && is_array($info))
			{
				$data = array(
					'mobile' =>$info['mobile'],
					'avatar' =>$info['avatar'],
					'mobile' =>$info['mobile'],
					'email'  =>$info['email'],
				);
				if($info['extension'] && is_array($info['extension']))
				{
					foreach ($info['extension'] as $k=>$v)
					{
						$data[$v['field']] = $v['value'];
					}
				}
			}
		}
		/***从会员接口取会员相关信息和拓展信息****/
		//会员信息对应到相关字段上去
		if($data && $ret['forms'] && is_array($ret['forms']))
		{
			foreach ($ret['forms'] as $key => $val)
			{
				if(!$val['default_value'] && $val['member_field'] && !$val['member_field_addr'])
				{
					$ret['forms'][$key]['default_value'] = $data[$val['member_field']];
				}
				else if(!$val['default_value'] && $val['member_field_addr'] && is_array($val['element']))
				{
					foreach ($val['element'] as $ks => $ele)
					{
						$ret['forms'][$key]['element'][$ks]['default_value'] = $data[$val['member_field_addr'][$ele['id']]];
					}
				}
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = ' AND status = 1 ';
		
		if(trim($this->input['id']))
		{			
			$condition .= ' AND id IN ('.trim($this->input['id']) .')';
		}
		
		if(trim($this->input['node_id']))
		{			
			$condition .= ' AND node_id IN ('.trim($this->input['node_id']) .')';
		}
		
		if(trim($this->input['isTimeout']))
        {
        	$condition .= ' AND (end_time >' . TIMENOW . ' OR end_time=0)';
        }
		return $condition;
	}
	
	//获取会员参加过得报名表单列表
	public function get_member_feedback_list()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby = '  ORDER BY f.create_time DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$sql = 'SELECT f.id,f.indexpic,f.title,f.brief,f.create_time as fcreate,rp.total_reply,rp.process,rp.create_time,rp.admin_reply_count FROM '.DB_PREFIX.'record_person rp LEFT JOIN '.DB_PREFIX.'feedback f ON f.id = rp.feedback_id WHERE rp.user_id = '.$this->user['user_id'];
		$sql .= $orderby .$limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['indexpic'] = $r['indexpic'] ? unserialize($r['indexpic']) : $r['indexpic'];
			$r['url'] = FB_DOMAIN . $r['fcreate'].$r['id'] . '/'.$r['id'].'.html';
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['process_status'] = $this->settings['process'][$r['process']];
			$r['admin_reply_count'] = $r['admin_reply_count'] < 100 ? $r['admin_reply_count'] : 99;
			unset($r['fcreate']);
			if($r['id'])
			{
				$list[] = $r;
			}
		}
		if($list)
		foreach ($list as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	//获取会员参加的报名数量
	public function member_count()
	{
		$condition = ' AND user_id = '.$this->user['user_id'];
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record_person WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		echo json_encode($total);
	}

	//查询会员表单信息
	public function get_member_feedback()
	{
		$id = intval($this->input['id']);
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = 'SELECT r.id,r.person_id,r.form_name,r.value,r.order_id,r.type FROM '.DB_PREFIX.'record_person rp LEFT JOIN '.DB_PREFIX.'record r ON rp.id = r.person_id WHERE rp.user_id = '.$this->user['user_id'].' AND r.feedback_id = '.$id;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['type'] == 'file')
			{
				$fileid[] = $r['value'];
			}
			$result[] = $r;
		}
		if(!$result)
		{
			$this->errorOutput(NO_RECORD);
		}
		if($fileid)
		{
			$fileids = implode(',',$fileid);
			$fileids = str_replace('_',',',$fileids);
			$sql = 'SELECT * FROM ' .DB_PREFIX . 'materials WHERE id in(' .$fileids.')';	
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				if($r['vodid'])
				{
					$r['tp'] = 'video';
					$r['m3u8'] = $r['host'].'/'.$r['dir'].$r['filename'].'.m3u8';
				}
				if($r['pic_name'])
				{
					$r['tp'] = 'picture';
				}
				$mat[$r['id']] = $r;
			}
		}
		foreach ($result as $k=>$v)
		{
			if($v['type'] == 'file' && $v['value'])
			{
				$vid = explode(',',$v['value']);
				$mat_id = explode('_',$vid[0]);
				if($mat_id[1])
				{
					$mat[$mat_id[0]]['indexpic'] = $mat[$mat_id[1]];
				}
				$result[$k]['value'] = $mat[$mat_id[0]];
			}
		}
		$this->addItem($result);
		$this->output();
	}
	
	public function fetch_message()
	{
		$id = intval($this->input['id']);
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		$sql = 'SELECT id,process,message_id FROM '.DB_PREFIX.'record_person WHERE feedback_id = '.$id .' and user_id = "'.$this->user['user_id'] .'"';
		$backinfo = $this->db->query_first($sql);
		if($backinfo['id'])
		{
			if($this->settings['App_im'])
			{
				$msg_id = $backinfo['message_id'];
				if($msg_id)
				{
					$this->curl = new curl($this->settings['App_im']['host'],$this->settings['App_im']['dir']);
					$this->curl->setSubmitType('post');
					$this->curl->setReturnFormat('json');
					$this->curl->initPostData();
					$this->curl->addRequestData('session_id', $msg_id);
					$this->curl->addRequestData('sort_type', 'ASC');
					$this->curl->addRequestData('a', 'session_detail');
					$message = $this->curl->request('message.php');
					$message = $message[0];
					$msg = $message['messages'][$msg_id];
					$userinfo = $message['users'];
					if($id)
					{
						$sql = 'SELECT admin_reply_count FROM '.DB_PREFIX.'record_person WHERE feedback_id = '.$id .' AND user_id = '.$this->user['user_id'] ;
						$back = $this->db->query_first($sql);
						$reply = $back['admin_reply_count'];
						$sql = 'UPDATE '.DB_PREFIX.'record_person SET admin_reply_count = 0 WHERE feedback_id = '.$id .' AND user_id = '.$this->user['user_id'] ;
						$this->db->query($sql);
						if($message['session_info']['id'] && $reply)
						{
							/*************会员查看过消息之后，管理员的回复数量加新消息数************/
							if($this->settings['App_members'])
							{
								require_once ROOT_PATH . 'lib/class/members.class.php';
								$members = new members();
								$data = array(
									'member_id'	=> $this->user['user_id'],
									'mark'		=> 'apply',
									'math'		=> 2,
									'total'		=> $reply,
								);
								$ret = $members->updateMyData($data);
							}
							/*************会员查看过消息之后，管理员的回复数量加新消息数************/
						}
					}
					if($userinfo && is_array($userinfo))
					{
						foreach ($userinfo as $k=>$v)
						{
							$user[$v['uid']] = $v['utype'];
						}
					}
					if($msg && is_array($msg))
					{
						foreach ($msg as $k=>$v)
						{
							$msg[$k]['utype'] = $user[$v['send_uid']];
						}
					}
					$retutn = array(
						'msg' => $msg,
						'users' => $userinfo,
					);
				}
			}
		}
		$this->addItem($retutn);
		$this->output();
	}
	
	public function show_province()
	{
		$province = $this->mode->show_province();
		$this->addItem($province);
		$this->output();
	}
	
	public function show_city()
	{
		$province_id = $this->input['province_id'];
		$city = $this->mode->show_city($province_id);
		$this->addItem($city);
		$this->output();
	}
	
	public function show_area()
	{
		$city_id = $this->input['city_id'];
		$area = $this->mode->show_area($city_id);
		$this->addItem($area);
		$this->output();
	}
	
	public function result_list()
	{
		$id = intval($this->input['fid']);
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
        $condition = ' AND rp.user_id = '.$this->user['user_id'];
		$orderby = ' ORDER BY rp.order_id DESC , rp.id DESC';
		$feedback = $this->mode->get_feedback(' id='.$id);
		if(!$feedback)
		{
			$this->errorOutput('没有此反馈表单！');
		}
		$sql = 'SELECT MIN(id) as id FROM ' .DB_PREFIX.'fixed WHERE fid IN ('.$id.') AND is_name = 1';
		$use_fix_name = $this->db->query_first($sql);
		$sql = 'SELECT MIN(id) as id FROM ' .DB_PREFIX.'standard WHERE fid IN ('.$id.') AND is_name = 1';
		$use_st_name = $this->db->query_first($sql);
		if($use_fix_name['id'])
		{
			$list_name['id'] = $use_fix_name['id'];
			$list_name['type'] = 'fixed';
		}
		elseif($use_st_name['id'])
		{
			$list_name['id'] = $use_st_name['id'];
			$list_name['type'] = 'standard';
		}
		if($feedback['is_login'])//如果不需要用户登录，则读取设定的单行文本作为列表的title
		{
			$orderby = ' ORDER BY rp.order_id DESC , rp.id DESC';
			$sql = 'SELECT * FROM ' .DB_PREFIX.'record_person rp WHERE rp.feedback_id IN ('.$id.') '. $condition;
			$sql .= $orderby .' LIMIT ' . $offset . ' , ' . $count;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$r['create_time'] = $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : 0;
				$result['list'][] = array(
				    'id'    => $r['id'],
				    'title' => $r['user_name'] ? $r['user_name'] : '匿名用户',
				    'create_time' => $r['create_time'],
				    'process'    => $r['process'],
                    'process_status'    => $this->settings['process'][$r['process']],
				    'order_id'   => $r['order_id'],
                    'url'        => FB_DOMAIN.$feedback['create_time'].$id.'/'.$id.'.html?pid='.$r['id'],
				);
			}
		}
		elseif($list_name)
		{
			$sql = 'SELECT * FROM ' .DB_PREFIX.'record_person rp WHERE rp.feedback_id IN ('.$id.') '. $condition;
			$sql .= $orderby .' LIMIT ' . $offset . ' , ' . $count ;
			$qs = $this->db->query($sql);
			while ($rs = $this->db->fetch_array($qs))
			{
				$rec_person[] = $rs['id'];
				$rs['create_time'] = $rs['create_time'] ? date('Y-m-d H:i:s',$rs['create_time']) : 0;
				$rec_ot[$rs['id']] = $rs;
			}
			if(!$rec_person)
			{
				$this->addItem($result['list']);
				$this->output();
			}
			$persons = implode(',',$rec_person);
			$sql = 'SELECT * FROM ' .DB_PREFIX.'record r WHERE r.person_id IN ('.$persons.') AND r.form_id = '.$list_name['id'].' AND type = "'.$list_name['type'].'"' ;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$rec[$r['person_id']] = $r;
			}
			foreach ($rec_person as $v)
			{
				$result['list'][] = array(
				    'id'    => $rec_ot[$v]['id'],
				    'title' => $rec[$v]['value'] ? trim($rec[$v]['value']) : '未填写',
				    'create_time' => $rec_ot[$v]['create_time'],
				    'process'    => $rec_ot[$v]['process'],
                    'process_status'    => $this->settings['process'][$rec_ot[$v]['process']],
				    'order_id'   => $rec_ot[$v]['order_id'],
                    'url'        => FB_DOMAIN.$feedback['create_time'].$id.'/'.$id.'.html?pid='.$rec_ot[$v]['id'],
				);
			}
		}
		else
		{
			$this->errorOutput('没有设置显示的标题');
		}
		$return = $result['list'];
		if(is_array($return))
		{
			foreach ($return as $v)
			{
				$v['create_time_stamp'] = strtotime($v['create_time']);
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	public function get_greeting_form()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$cond = ' id = '.$id;
		$filed = 'id,title,indexpic,brief,counts,page_title';
		$feedback = $this->mode->get_feedback($cond,$filed);
		$feedback['indexpic'] = $feedback['indexpic'] ? unserialize($feedback['indexpic']) : array();
		$feedback['page_title'] = $feedback['page_title'] ? $feedback['page_title'] : $feedback['title'];
		$form = $this->mode->get_forms($id,SORT_ASC);
		$feedback['form'] = $form;
		$this->addItem($feedback);
		$this->output();
	}
	
	public function get_greeting_result()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(ID_ERROR);
		}
		$sql = 'SELECT create_time FROM '.DB_PREFIX.'record_person WHERE id='.$id;
		$data = $this->db->query_first($sql);
		$data['create_time'] = $data['create_time'] ? date('Y年m月d日',$data['create_time']) : '';
		$result = $this->mode->get_result_with_pid($id);
		if(!$result)
		{
			$this->errorOutput(NO_RESULT);
		}
		$data['result'] = $result;
		$this->addItem($data);
		$this->output();
	}
	
	//抽奖接口
	public function get_winners()
	{
		$count = intval($this->input['count']);
		$feedback_id = intval($this->input['id']);
		$clear = $this->input['clear'] ? intval($this->input['clear']) : 0 ;
		$clearlog = $this->input['clearlog'] ? intval($this->input['clearlog']) : 0 ;
		$filename = 'winner_result'.$feedback_id.'.txt';
		if($clear && file_exists(CACHE_DIR.$feedback_id.'winner.txt'))
		{
			unlink(CACHE_DIR.$feedback_id.'winner.txt');
		}
				
		if($clearlog && file_exists(CACHE_DIR.$filename))
		{
			unlink(CACHE_DIR.$filename);
		}
		if(!$feedback_id)
		{
			$data['error'] = 'id错误';
			$this->addItem($data);
			$this->output();
		}
		if($count<=0)
		{
			$data['error'] = '人数错误';
			$this->addItem($data);
			$this->output();
		}
		if(!defined('PHP_EOL'))
		{
			define(PHP_EOL,"\n");
		}
		$_win_record = '';
		$_record = @file_get_contents(CACHE_DIR.$feedback_id.'winner.txt');
		$sql = 'SELECT id FROM '.DB_PREFIX.'record_person WHERE 1 AND process = 1 AND feedback_id = '.$feedback_id;
		if($_record)
		{
			$_record = trim(str_replace(PHP_EOL,',',$_record),',');
			$sql .= ' AND id NOT IN('.$_record.')';
		}
		$record = $this->db->fetch_all($sql);
		$count_r = count($record);
		if(!$record || $count_r < $count)
		{
			$data['error'] = '数据太少，无法继续';
			$this->addItem($data);
			$this->output();
		}
		$ran = array();
		$i = 0;
		while($i < $count)
		{
			$n = mt_rand(0,$count_r-1);
			if(!in_array($record[$n]['id'],$ran))
			{
				$ran[] = $record[$n]['id'];
				$i++;
			}
		}
		$win_id = implode(',',$ran);
		if($win_id)
		{
			$win_id = $win_id.PHP_EOL;
			file_put_contents(CACHE_DIR.$filename,"-------------".date('Y-m-d H:i')." ------------"."\r\n",FILE_APPEND);
			file_put_contents(CACHE_DIR.$feedback_id.'winner.txt', $win_id ,FILE_APPEND);
			$sql = 'SELECT * FROM '.DB_PREFIX.'record WHERE 1 AND person_id in( '.$win_id.') ORDER BY order_id DESC';
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$result[$r['person_id']][] = $r;
			}
			$sql = 'SELECT device_token,id FROM '.DB_PREFIX.'record_person WHERE 1 AND id in( '.$win_id.')';
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$device[$r['id']] = $r['device_token'];
			}
		}
		
		if(!$result || !$win_id)
		{
			//$this->errorOutput('请再次尝试');
			$data['error'] = '请重新尝试';
			$this->addItem($data);
			$this->output();
		}
		if($device && $this->settings['App_mobile'])
		{
			foreach ($device as $k=>$v)
			{
				if($v)
				{
					$this->curl = new curl($this->settings['App_mobile']['host'],$this->settings['App_mobile']['dir']);
					$this->curl->setReturnFormat('json');
					$this->curl->initPostData();
					$this->curl->addRequestData('device_token',$v);
					$this->curl->addRequestData('uuid',$uuid);
					$ret = $this->curl->request('mobile_device.php');
					$ct[$k] = $ret[0]['create_time'];
				}
			}
		}
		foreach ($ran as $v)
		{
			$data = array(
					'name'	=> $result[$v][0]['value'],
					'tel'	=> $result[$v][1]['value'],
					'device_token' => $device[$v],
					'install_time' => $ct[$v] ? date('Y-m-d H:i',$ct[$v]) : '',
				);
			file_put_contents(CACHE_DIR.$filename,'姓名：'.$data['name'].' 	电话：'.$data['tel'].'		设备号：'.$data['device_token'].'		安装时间：'.$data['install_time']."\r\n",FILE_APPEND);
			$this->addItem($data);
		}
		file_put_contents(CACHE_DIR.$filename,"\r\n",FILE_APPEND);
		$this->output();
	}
	
	public function all_players()
	{
		$feedback_id = intval($this->input['id']);
		$sql = 'SELECT r.* FROM '.DB_PREFIX.'record r LEFT JOIN '.DB_PREFIX.'record_person rp ON r.person_id = rp.id  WHERE r.feedback_id = '.$feedback_id .' ORDER BY r.order_id DESC , form_id ASC';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$player[$r['person_id']][] = $r;
		}
		if(!$player)
		{
			$this->errorOutput('没有参与人员');
		}
		shuffle($player);
		foreach($player as $key => $v)
		{
			$ret = array(
				'name'	=> $v[0]['value'],
				'tel'	=> $v[1]['value'],
			);
			$this->addItem($ret);
		}
		$this->output();
	}
	
	public function filtinfo()
	{
		$feedback_id = intval($this->input['id']);
		$device_token = trim($this->input['device_token']);
		if(!$feedback_id)
		{
			$this->errorOutput(NOID);
		}
		if($this->user['user_id'] )
		{
			$conditon = ' AND user_id = '.$this->user['user_id'];
		}
		elseif($device_token)
		{
			$conditon = ' AND device_token = '.$device_token;
		}
		$conditon .= ' AND feedback_id = '.$feedback_id;
		$sql = 'SELECT id FROM '.DB_PREFIX.'record_person WHERE 1 '.$conditon.' ORDER BY order_id DESC ,create_time DESC';
		$rec = $this->db->query_first($sql);
		if($rec['id'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'record WHERE 1 AND person_id  = '.$rec['id'].' ORDER BY order_id DESC';
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				if($r['type'] != 'file' )
				{
					$data[] = array(
						'unique_name' => 'form['.$r['type'].'_'.$r['form_id'].']',
						'default_value' => $r['value'],
					);
				}
			}
		}
		if($data)
		{
			foreach ($data as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	public function get_app_fbinfo()
	{
		$source_id = $this->input['source_id'];
		$fb_id = $this->input['feedback_id'];
		$source_app = $this->input['source_app'];
		$id = $this->input['id'];
		$sql = 'SELECT rp.source_id,r.* FROM '.DB_PREFIX.'record_person rp LEFT JOIN '.DB_PREFIX.'record r ON rp.id = r.person_id WHERE 1 ';
		if($source_id)
		{
			$sql .= ' AND rp.source_id = '.$source_id;
		}
		if($source_app)
		{
			$sql .= ' AND rp.source_app = '.$source_app;
		}
		if($fb_id)
		{
			$sql .= ' AND rp.feedback_id = '.$fb_id;
		}
		if($id)
		{
			$sql .= ' AND rp.id = '.$id;
		}
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = array(
				'name'	=> $r['form_name'],
				'value'	=> $r['value'],
			);
		}
		$this->addItem($ret);
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