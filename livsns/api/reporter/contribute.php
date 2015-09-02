<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once CUR_CONF_PATH.'lib/contribute.class.php';
define('MOD_UNIQUEID','reporter');//模块标识
class contributeApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->con = new contribute();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY c.order_id  DESC';
		$res = $this->con->show($this->get_condition(),$orderby,$offset,$count);
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	public function get_condition()
	{
		$condition = '';
		if ($this->input['id'])
		{
			$condition .= ' AND c.id = '.intval(urldecode($this->input['id']));
		}
		if ($this->input['sort_id'])
		{
			$condition .= ' AND c.sort_id = '.intval(urldecode($this->input['sort_id']));
		}
		
		if ($this->input['self'])
		{
			
			$condition .= ' AND c.user_id='.intval($this->user['user_id']);
		}
		if ($this->input['title'])
		{
			
			$condition .= ' AND c.title LIKE "%'.urldecode($this->input['title']).'%"';
		}
		if ($this->input['user_id'])
		{
			$condition .= ' AND c.user_id = '.intval(urldecode($this->input['user_id']));
		}
		if ($this->input['user_name'])
		{
			
			$condition .= ' AND c.user_name LIKE "%'.urldecode($this->input['user_name']).'%"';
		}
		if ($this->input['audit'])
		{
			$condition.= ' AND c.audit IN ('.trim($this->input['audit']).')';
		}
		if ($this->input['start_time'])
		{
			$condition.= ' AND c.create_time > '.intval(urldecode($this->input['start_time']));
		}
		if ($this->input['end_time'])
		{
			$condition.= ' AND c.create_time < '.intval(urldecode($this->input['end_time']));
		}
		//1是已发布，2是未发布
		if ($this->input['is_pub'])
		{
			$condition .= ' AND c.expand_id != 0';
		}
		return $condition;
	}
	public function count()
	{
		$ret = $this->con->count($this->get_condition());
		echo json_encode($ret);
	}
	public function detail()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = intval(urldecode($this->input['id']));
		$data = $this->con->detail($id);
		$this->addItem($data);
		$this->output();
	}
	public function sort()
	{
		$id = intval($this->input['id']);
		$exclude_id = intval($this->input['exclude_id']);
		$data = $this->con->sort($id, $exclude_id);
		$data = $this->con->sort($id);
		if (!empty($data))
		{
			foreach ($data as $k=>$v)
			{	
				$this->addItem($v);
			}
		}
		$this->output();
	}
	public function fastInput()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(noid);
		}
		$id = urldecode($this->input['id']);
		$data = $this->con->fastInput($id);
		if ($data)
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	/**
	 * 增加投稿
	 */
	/*
	function create()
	{
		//添加爆料主表
		$data = array(
					'title'=>trim(urldecode($this->input['title'])),
					'brief'=>addslashes(trim(urldecode($this->input['brief']))),
					'appid'=>$this->user['appid'],
					'client'=>$this->user['display_name'],
		 			'longitude'=>trim(urldecode($this->input['longitude'])),
		 			'latitude'=>trim(urldecode($this->input['latitude'])),
					'create_time'=>TIMENOW,
					'user_id'=>$this->input['user_name'] ? 0 : $this->user['user_id'],
					'user_name'=>addslashes($this->input['user_name'])?addslashes($this->input['user_name']):addslashes($this->user['user_name']),
					'audit'=>1,
		 			'sort_id'=>trim(urldecode($this->input['sort_id'])), 			 	
		);	
		$content = addslashes(trim(urldecode($this->input['content'])));

		if (!$content)
		{
			$this->errorOutput('请输入投稿内容');
		}
		if (!$data['sort_id'])
		{
			$data['sort_id'] = 0; 
		}
		if (!$data['title'])
		{
			$data['title'] = hg_cutchars($content,20);
							
		}
		if (!$data['brief'])
		{
			$data['brief'] = hg_cutchars($content,100);
		}
		
		$contribute_id = $this->con->add_content($data);
		
		//添加内容表	
		
		$body = array(
			'id'=>$contribute_id,
			'text'=>$content
		);	
		$this->con->add_contentbody($body);
		
		//用户信息
		$userinfo = array();
		
		$userinfo = array(
			'con_id'=>intval($contribute_id),
			'tel'=>$this->input['tel'],
			'email'=>addslashes($this->input['email']),
			'addr'=>addslashes($this->input['addr']),
		);
		if ($this->input['user_name'])
		{
	      	$userinfo = array(
	      		'con_id'=>intval($contribute_id),
	      		'tel'=>$this->input['tel'],
	      		'email'=>addslashes($this->input['email']),
	      		'addr'=>addslashes($this->input['addr']),
	      	);
		}
		elseif ($this->user['user_id'] && !$this->input['user_name'])
		{
			$return = $this->con->get_userinfo_by_id($this->user['user_id']);
			if (!empty($return))
			{
				$userinfo = array(
					'con_id'=>intval($contribute_id),		      		
		      		'tel'=>$return['mobile'],
		      		'email'=>$return['email'],
		      		'addr'=>$return['address'],
				);
			}
		}
		if (!empty($userinfo))
		{
			$this->con->user_info($userinfo);		
		}
		//图片上传
		if ($_FILES['photos'])
		{		
			$count = count($_FILES['photos']['error']);
			for($i = 0;$i<$count;$i++)
			{			
				if ($_FILES['photos']['error'][$i]==0)
				{
					$pics = array();
					foreach($_FILES['photos'] AS $k =>$v)
					{
						$pics['Filedata'][$k] = $_FILES['photos'][$k][$i];
											
					}
					//插入图片服务器

					$ret = $this->con->uploadToPicServer($pics, $contribute_id);

					//准备入库数据
					$arr = array(
							'content_id'=>$contribute_id,
							'mtype'=>$ret['type'],						
							'original_id'=>$ret['id'],
							'host'=>$ret['host'],
							'dir'=>$ret['dir'],
							'material_path'=>$ret['filepath'],
							'pic_name'=>$ret['filename'],
					);
					$id = $this->con->upload($arr);
					//默认第一张图片为索引图
					if (!$indexpic)
					{
						$indexpic = $this->con->update_indexpic($id, $contribute_id);
					}					
				}
			}
		}		
		//视频上传
		if ($_FILES['videofile'])
		{
			//上传视频服务器
			$videodata = $this->con->uploadToVideoServer($_FILES, $data['title'], $data['brief']);
			//有视频没有图片时，将视频截图上传作为索引图
			if (!$indexpic)
			{					
				$url = $videodata['img']['host'].$videodata['img']['dir'].$videodata['img']['filepath'].$videodata['img']['filename'];
				$material = $this->con->localMaterial($url, $contribute_id);
				$arr = array(
						'content_id'=>$contribute_id,
						'mtype'=>$material['type'],
						'original_id'=>$material['id'],
						'host'=>$material['host'],
						'dir'=>$material['dir'],
						'material_path'=>$material['filepath'],
						'pic_name'=>$material['filename'],
				);
				$indexpic = $this->con->upload($arr);
				$this->con->update_indexpic($indexpic, $contribute_id);
			}
			//视频入库
			$arr = array(
						'content_id'=>$contribute_id,
						'mtype'=>$videodata['type'],
						'host'=>$videodata['protocol'].$videodata['host'],
						'dir'=>$videodata['dir'],
						'vodid'=>$videodata['id'],
						'filename'=>$videodata['file_name'],
					);
					
			$this->con->upload($arr);
		}
		$this->addItem($contribute_id);
		$this->output();
	}
	*/		
}
$ouput= new contributeApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>