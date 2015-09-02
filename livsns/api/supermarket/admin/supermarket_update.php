<?php
define('MOD_UNIQUEID','supermarket');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/supermarket_mode.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH .'lib/class/recycle.class.php');
class supermarket_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new supermarket_mode();
		$this->recycle = new recycle();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		/*********************************权限*****************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput('您没有权限创建超市');
		}
		/*********************************权限*****************************/
		
	    if(!trim($this->input['market_name']))
		{
			$this->errorOutput('名称不能为空');
		}

		$data = array(
			'market_name'         => trim($this->input['market_name']),
			'logo_id'             => intval($this->input['logo_id']),
		    'ip'				  => hg_getip(),
		    'create_time'         => TIMENOW,
			'update_time'         => TIMENOW,
			'user_name'	          => $this->user['user_name'],
			'user_id'	          => $this->user['user_id'],
			'update_user_name'	  => $this->user['user_name'],
			'update_user_id'	  => $this->user['user_id'],
		    'org_id'	          => $this->user['org_id'],
		);
		$ret = $this->mode->create($data);
		if($ret)
		{
			$this->addLogs('创建商店','',$ret,'创建商店'.$ret['id']);
			$this->addItem($ret);
 			$this->output();
		}
	}

	public function update()
	{
		/*********************************权限控制********************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms();
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				if(!in_array($this->input['id'],$authnode))
				{
					$this->errorOutput('您没有审核该超市的权限');
				}
			}
			else 
			{
				$this->errorOutput('您没有审核该超市的权限');
			}
		}
		/*********************************权限控制********************************************/
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
			
		if(!trim($this->input['market_name']))
		{
			$this->errorOutput(NO_TITLE);
		}
	
		$update_data = array(
			'logo_id'             => intval($this->input['logo_id']),
		    'market_name' 		  => $this->input['market_name'],
			'update_time'         => TIMENOW,
			'update_user_name'	  => $this->user['user_name'],
			'update_user_id'	  => $this->user['user_id'],
		);
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新商店',$ret,'','更新商店' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function delete()
	{
		/*********************************权限*****************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput('您没有权限删除超市');
		}
		/*********************************权限*****************************/
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			foreach ($ret AS $k => $v)
			{
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 		=> $v['market_name'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('supermarket' => $v),
				);
			}
			
			/********************************回收站***********************************/
			if($recycle)
			{
				foreach($recycle as $key => $value)
				{
					$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				}
			}
			/********************************回收站***********************************/
			
			$this->addLogs('删除商店',$ret,'','删除商店' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		/*********************************权限控制********************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms();
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				if(!in_array($this->input['id'],$authnode))
				{
					$this->errorOutput('您没有审核该超市的权限');
				}
			}
			else 
			{
				$this->errorOutput('您没有审核该超市的权限');
			}
		}
		/*********************************权限控制********************************************/
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id'],$this->input['op']);
		if($ret)
		{
			$this->addLogs('审核商店','',$ret,'审核商店id' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	/**
	 * 上传logo
	 * 
	 */
	public function upload_img()
	{		
		$logo['Filedata'] = $_FILES['logo'];
		
		if($logo['Filedata'])
		{
			$material_pic = new material();
			$logo_info = $material_pic->addMaterial($logo);
			
			$logo  = array();
			$logo_pic = array(
			'host'     => $logo_info['host'],
			'dir'      => $logo_info['dir'],
			'filepath' => $logo_info['filepath'],
			'filename' => $logo_info['filename'],
			'imgwidth' => $logo_info['imgwidth'],
			'imgheight'=> $logo_info['imgheight'],
			);
			$img_info = addslashes(serialize($logo_pic));	
		}
		if(!$logo_pic) 
		{
			$this->errorOutput('没有上传的图片信息');
		}
		$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . $img_info ."'";
		$query = $this->db->query($sql);
		
		$vid = $this->db->insert_id();
        $data['id'] = $vid;
		$data['img_info'] = hg_fetchimgurl($logo_pic);
		$this->addItem($data);
		$this->output();
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new supermarket_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>