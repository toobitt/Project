<?php
require('global.php');
define('MOD_UNIQUEID','cp_thread_m');//模块标识
define('SNS_TOPIC', 'http://vblog.example.cn');

require_once './lib/thread.class.php';
require_once './lib/group.class.php';

class threadApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->thread = new thread();
		$this->group = new group();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$data = $this->check_data();
		if (is_numeric($data['group_id']))
		{
			$flag = isset($this->input['flag']) ? intval($this->input['flag']) : false;
			$action_id = isset($this->input['action_id']) ? intval($this->input['action_id']) : 0;
			$group = $this->group->detail($data['group_id'], $flag, $action_id);
			if (!$flag)
			{
				if (!$group['status']) $this->errorOutput(OBJECT_NULL);
			}
		}
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$threads = $this->thread->show($offset, $count, $data);
		$this->setXmlNode('thread_info' , 'thread');
		foreach($threads as $thread)
		{
			$this->addItem($thread);
		}
		$this->output();
	}
	
	
	//获取对应总数
	public function count()
	{
		$data = $this->check_data();
		$info = $this->thread->count($data);
		echo json_encode($info);
	}
	
	private function check_data()
	{
		$data = array(
			'key' => trim(urldecode($this->input['k'])),
			'user_name' => trim(urldecode($this->input['user_name'])),
			'start_time' => strtotime(trim(urldecode($this->input['start_time']))),
			'end_time' => strtotime(trim(urldecode($this->input['end_time']))),
			'date_search' => $this->input['date_search'],
			'state' => $this->input['state'],
			'group_id' => $this->input['group_id'],
			'thread_type' => $this->input['thread_type'],
			'thread_img' => $this->input['thread_img'],
			'hgupdn' => trim(urldecode($this->input['hgupdn'])),
			'hgorder' => trim(urldecode($this->input['hgorder'])),
			'_type' => $this->input['_type'],
		);
		return $data;
	}
	
	//获取单条帖子信息
	public function detail()
	{
		if (isset($this->input['id']))
		{
			$thread_id = intval($this->input['id']);
		}
		elseif (isset($this->input['thread_id']))
		{
			$thread_id = intval($this->input['thread_id']);
		}
		else
		{
			$thread_id = -1;
		}
		//$thread_id = isset($this->input['thread_id']) ? intval($this->input['thread_id']) : -1;
		if ($thread_id < 0) $this->errorOutput(PARAM_WRONG);
		$thread = $this->thread->detail($thread_id);
		$thread['content'] = htmlspecialchars_decode($thread['content']);
		$material_num = isset($this->input['material_num']) ? intval($this->input['material_num']) : 3;
		$data_limit = 'LIMIT ' . $material_num;
		$type = ' AND thread_id = ' . $thread['thread_id'];
		$thread['material'] = $this->group->get_material_info($type, $data_limit);
		$thread['id'] = $thread['thread_id'];
		$this->addItem($thread);
		$this->output();
	}
	
	/**
	 * 获取帖子类型
	 */
	public function thread_type()
	{
		$sql="select * from " . DB_PREFIX . "thread_type where 1";
		$ret=$this->db->query($sql);
		while($row=$this->db->fetch_array($ret))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	
	/**
	 * 根据地盘ID检索地盘信息
	 * @name show_opration
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param id int 地盘ID
	 * @return array $return 文章信息
	 */
	public function show_opration()
	{
		//获取全部的板块接口
		
		if(!$this->input['id'])
		{
			$this->errorOutput('未传入帖子ID');
		}
		$sql="SELECT t.*,tt.*
		FROM " . DB_PREFIX."thread t
		LEFT JOIN " . DB_PREFIX ."thread_type tt
		ON t.thread_type = tt.t_typeid where t.thread_id=" . $this->input['id'];
		$return=$this->db->query_first($sql);
		if($return['contain_img'])
		{
			$sql="select * from " . DB_PREFIX . "material where id=" . $return['thread_id'] . " limit 1";
			$ret=$this->db->query_first($sql);
			$return['logo']=$this->settings['livime_upload_url'] . $ret['filepath'] . $ret['filename'];
		}
		if(!$return)
		{
			$this->errorOutput('文章不存在或已被删除');
		}
	
		//记录页面的所处的类型与类别
		if($this->input['frame_type'])
		{
			$return['frame_type'] = intval($this->input['frame_type']);
		}
		else
		{
			$return['frame_type'] = '';
		}
	
		if($this->input['frame_sort'])
		{
			$return['frame_sort'] = intval($this->input['frame_sort']);
		}
		else
		{
			$return['frame_sort'] = '';
		}
		$return['create_time']=date('Y-m-d H:i',$return['create_time']);
		$return['update_time']=date('Y-m-d H:i',$return['update_time']);
		$return['pub_time']=date('Y-m-d H:i',$return['pub_time']);
		$this->addItem($return);
		$this->output();
	}
	
	public function detailPost()
	{
		$thread_id = intval($this->input['thread_id']);
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql ="SELECT * FROM " . DB_PREFIX . "post WHERE thread_id = " . $thread_id . ' AND reply_user_id != 0' . $data_limit;
		$info = $this->db->fetch_all($sql);
		$this->addItem($info);
		$this->output();
	}
	
	public function post_count()
	{
		$thread_id = intval($this->input['thread_id']);
		$sql= 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'post 
		WHERE thread_id = ' . $thread_id . ' AND reply_user_id != 0';
		$result = $this->db->query_first($sql);
		$this->addItem($result['total']);
		$this->output();
	}
	
	public function showActionandThread()
	{
		$action_id = intval(trim($this->input['action_id']));
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql ="SELECT t . * , p.pagetext FROM `".DB_PREFIX . "thread` t, `".DB_PREFIX . "post` p, `".DB_PREFIX . "group` g WHERE g.action_id =".$action_id."
				AND g.group_id = t.group_id AND t.thread_id = p.thread_id ";
		//获取查询条件
		$condition = $this->get_condition();
		$sql = $sql . $condition . $data_limit;
		
		$info = $this->db->fetch_all($sql);
		$this->addItem($info);
		$this->output();
	}
	
	//插入附件关系表
	public function add_material_info()
	{
		$data['user_id'] = $this->input['user_id'];
		$data['thread_id'] =  $this->input['thread_id'];
		$data['group_id'] =  $this->input['group_id'];
		$img_info = $this->input['img_info '];
		if(!$data['group_id'] || !$data['thread_id'] || !$data['user_id'] || !$img_info)
		{
			$this->errorOutput('缺少重要参数');
		}
		return $this->group->add_material($img_info, $data);
	}
	
	public function get_material_info()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$group_id = intval(trim($this->input['group_id']));
		$thread_id = intval(trim($this->input['thread_id']));
		$user_id = (trim($this->input['user_id']));
		if(!$user_id && !$thread_id && !$group_id)
		{
			$this->errorOutput('缺少重要参数');
		}
		$type = '';
		if($group_id)
		{
			$type .= " and group_id = ".$group_id;
		}
		if($thread_id)
		{
			$type .= " and thread_id = ".$thread_id;
		}
		if($user_id)
		{
			$type .= " and user_id = ".$user_id;
		}
		$this->group->get_material_info($type, $data_limit, '');
	}
	//根据活动id获取帖子id
	public function getInfosByActionId()
	{
		$action_id = trim($this->input['action_id']);
		if(!$action_id)
		{
			$this->errorOutput("缺少参数");
		}
		$sql = 'select group_id from ' . DB_PREFIX . 'group where action_id ='.$action_id." and state=1";
		$group = $this->db->query_first($sql); 
		$group_id = $group['group_id'];
		if(!$group_id)
		{
			$this->errorOutput('缺少参数');
		}
		$offset = empty($this->input['offset']) ? 0 : intval($this->input['offset']);
		$count = empty($this->input['count']) ? 20 : intval($this->input['count']);
		$threads = $this->thread->show($offset, $count, array('group_id' => $group_id, 'state' => 2));
		$material_num = empty($this->input['material_num']) ? 3 : intval($this->input['material_num']) ;
		$data_limit = 'LIMIT ' . $material_num;
		foreach($threads as $k=>$v)
		{
			$type = ' AND thread_id = ' . $v['thread_id'];
			$threads[$k]['material'] = $this->group->get_material_info($type, $data_limit);
		}
		$this->addItem_withkey('threads',$threads);
		$limit = empty($this->input['limit']) ? 10 :intval($this->input['limit']) ;
		$material_info = $this->group->get_material_info(' and group_id='.$group_id, " limit  ".$limit, '');
		$this->addItem_withkey('material_info',$material_info);
		$this->addItem_withkey('group_id',$group_id);
		$this->output();
	}
}

/**
 *  程序入口
 */
$out = new threadApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
