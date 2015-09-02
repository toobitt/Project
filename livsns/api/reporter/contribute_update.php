<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once CUR_CONF_PATH.'lib/contribute.class.php';
define('MOD_UNIQUEID','reporter');//模块标识
class contributeUpdateApi extends outerUpdateBase
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
	
	public function create()
	{
			//添加爆料主表
		$data = array(
					'title'=>addslashes(trim($this->input['title'])),
					'brief'=>addslashes(trim($this->input['brief'])),
					'appid'=>$this->user['appid'],
					'client'=>$this->user['display_name'],
		 			'longitude'=>trim($this->input['longitude']),
		 			'latitude'=>trim($this->input['latitude']),
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
		}else{
			//获取该分类下的发布栏目
			$sortInfor = $this->con->getSortInfor($data['sort_id']);
			if (!empty($sortInfor))
			{
				$data['column_id'] = addslashes($sortInfor[$data['sort_id']]['column_id']);
			}
			
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
		
		//转发爆料
		if ($contribute_id)
		{
			$this->con->send_contribute($contribute_id,$flag=1);	
		}
		$this->addItem($contribute_id);
		$this->output();
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		
	}
}
$ouput= new contributeUpdateApi();
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