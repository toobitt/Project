<?php
require_once './global.php';
define('MOD_UNIQUEID','seekhelp_section');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/section_mode.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once CUR_CONF_PATH.'lib/seekhelp_blacklist_mode.php';
class Seekhelp_section extends outerReadBase
{
	private $seekhelp;
    private $blacklist;
	public function __construct()
	{
		parent::__construct();
		$this->seekhelp = new ClassSeekhelp();
		$this->material = new material();
		$this->api = new section_mode();
        $this->blacklist = new seekhelp_blacklist_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 版块列表
	 * (non-PHPdoc)
	 * @see outerReadBase::show()
	 */
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$limit = ' limit ' .$offset.','.$count.'';
		$orderby = ' ORDER BY create_time  DESC';
		$data = $this->get_condition();
		if(!$data['sort_id'])
		{
			$this->errorOutput(PARAM_WRONG);
		}
		
		//验证是否有回收版块
		$this->verify_recycle_section($data['sort_id']);
		
		$condition = " AND sort_id=".$data['sort_id']."";
		$result = $this->api->show($condition,$orderby,$limit);
		foreach ($result as $k=>$v)
		{
			if($v['avatar'])
			{
				$result[$k]['avatar'] = unserialize($v['avatar']);
			}
			else
			{
				$section_data[$k]['avatar'] = array(
						'host'=> '',
						'dir' => '',
						'filepath' => '',
						'filename' => '',
						'width' => '',
						'height' => '',
						'id' => '',
				);
			}
			//话题数量
			$seekhelp_total = $this->seekhelp->count(" AND section_id=".$v['id']."");
			$result[$k]['seekhelp_total'] = $seekhelp_total['total'];
		}
		
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 创建版块
	 */
	public function create()
	{
        if($this->input['app_id'])
        {
            //检查社区黑名单
            $blackInfo = $this->blacklist->check_blackByappId($this->input['app_id']);
            if($blackInfo && $blackInfo['deadline'] == -1)
            {
                $this->errorOutput(SEEKHELP_IS_BLACK);
            }
        }
        $data = $this->get_condition();
		$data['avatar'] = $this->uploadimg("avatar");
		$data['create_time'] = TIMENOW;
		if(!$data['name'])
		{
			$this->errorOutput(NO_SECTION_NAME);
		}
		if(!$data['sort_id'])
		{
			$this->errorOutput(NO_SORT_ID);
		}
		
		//验证社区已创建版块数量
		$this->check_section_num($data['sort_id']);
		
		$result = $this->api->create($data);
		if(!$result)
		{
			$this->errorOutput(CREATE_FAIL);	
		}
		$data['avatar'] = unserialize($data['avatar']);
		$data['name'] = seekhelp_clean_value($data['name']);
		$data['id'] = $result;
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 更新版块
	 */
	public function update()
	{
		$data = $this->get_condition();
		$avatar = $this->uploadimg('avatar');
		if($avatar)
		{
			$data['avatar'] = $avatar;
		}
		if(!$data['id'])
		{
			$this->errorOutput(PARAM_WRONG);
		}
		if($data['type'])
		{
			$data['type'] = $data['type'];
		}
		else 
		{
			unset($data['type']);
		}
		$result = $this->api->update($data['id'],$data);
		if(!$result)
		{
			$this->errorOutput(UPDATE_FAIL);
		}
		if($avatar)
		{
			$data['avatar'] = unserialize($data['avatar']);
		}
		else 
		{
			$sectionInfo = $this->api->detail($data['id']);
			$data['avatar'] = unserialize($sectionInfo['avatar']);
		}
		if($data['name'])
		{
			$data['name'] = seekhelp_clean_value($data['name']);
		}
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 删除版块
	 */
	public function delete()
	{
		$data = $this->get_condition();
		if(!$data['id'])
		{
			$this->errorOutput(PARAM_WRONG);
		}
		
		$result = $this->api->delete($data['id']);
		if(!$result)
		{
			$this->errorOutput(DELETE_FAIL);
		}
		$this->addItem($result);
		$this->output();
	}
	
	private function get_condition()
	{
		$id = $this->input['id'];
		$name = trim($this->input['name']);
		$sort_id = $this->input['sort_id'];//社区id
		$type = trim($this->input['type']);
		if($type == 'default')
		{
		    $name = '默认版块';
		}
		return array(
				'id'      => $id,
				'name'    => $name,
				'sort_id' => $sort_id,
				'type'    => $type,
		);
	}
	
	/**
	 * 上传图片
	 * @param unknown $var_name
	 * @return string
	 */
	private function uploadimg($var_name)
	{
		if($_FILES[$var_name])
		{
			//处理avatar图片
			if($_FILES[$var_name] && !$_FILES[$var_name]['error'])
			{
				$_FILES['Filedata'] = $_FILES[$var_name];
				$material = new material();
				$img_info = $material->addMaterial($_FILES);
				if($img_info)
				{
					$avatar = array(
							'host' 		=> $img_info['host'],
							'dir' 		=> $img_info['dir'],
							'filepath' 	=> $img_info['filepath'],
							'filename' 	=> $img_info['filename'],
							'width'		=> $img_info['imgwidth'],
							'height'	=> $img_info['imgheight'],
							'id'        => $img_info['id'],
					);
					$avatar = @serialize($avatar);
				}
			}
			return $avatar;
		}
	}
	
	
	/**
	 * 检查是否有回收版块
	 */
	private function verify_recycle_section($sort_id)
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$limit = ' limit ' .$offset.','.$count.'';
		$orderby = ' ORDER BY create_time  DESC';
		if(!$sort_id)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$condition = " AND sort_id=".$sort_id." AND type='recycle'";
		$result = $this->api->show($condition,$orderby,$limit);
		if(!$result)
		{
			$this->create_recycle_section($sort_id);
		}
		return $result;
	}
	
	private function check_section_num($sort_id)
	{
	    if(!$sort_id)
	    {
	        $this->errorOutput(NO_SORT_ID);
	    }
	    $sectionCount = $this->api->count(" AND sort_id = ".$sort_id."");
	    if($sectionCount['total'] >= MAX_SECTION_NUMBER)
	    {
	       $this->errorOutput(SECTION_HAS_MAX);    
	    }
	    return true;
	}
	
	/**
	 * 创建默认的回收站版块
	 * @param unknown $sortId
	 * @return string
	 */
	public function create_recycle_section($sortId = 0)
	{
        if(!$sortId)
		{
		    $sortId = $this->input['sort_id'];
		}
		if(!$sortId)
		{
		    $this->errorOutput(NO_SORT_ID);
		}
		
	    $name = '回收站';
		$data = array(
				'name' => $name,
				'sort_id' => $sortId,
                'avatar' => array(),
				'type' => 'recycle',
                'create_time' => TIMENOW
		);
		$result = $this->api->create($data);
		if(!$result)
		{
			$this->errorOutput(CREATE_FAIL);	
		}
		if($this->input['sort_id'])
		{
		    $data['id'] = $result;
		    $this->addItem($data);
		    $this->output();
		}
		
		return true;
	}
	
	public function detail(){}
	
	public function count(){}
	
}
$ouput = new Seekhelp_section();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
