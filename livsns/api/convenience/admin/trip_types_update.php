<?php
define('MOD_UNIQUEID','trip_types');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/trip_types_mode.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
class trip_types_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new trip_types_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'en'                => trim($this->input['en']),
			'zh'                => trim($this->input['zh']),
			'logo'              => intval($this->input['logoid']),
			'is_quick_search'   => intval($this->input['is_quick_search']),
			'create_time'       => TIMENOW,
			'user_name'         => $this->user['user_name'],
			'user_id'           => $this->user['user_id'],
		    'ip'				=> hg_getip(),
		    'update_time'       => TIMENOW,
		);
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			$this->addLogs('创建出行类型',$ret,'','创建' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			'en'                => trim($this->input['en']),
			'zh'                => trim($this->input['zh']),
			'logo'              => intval($this->input['logoid']),
			'is_quick_search'   => intval($this->input['is_quick_search']),
		    'update_time'       => TIMENOW,
		);
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新出行类型',$ret,'','更新' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除出行类型',$ret,'','删除' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}

	public function sort(){}
	public function audit(){}
	public function publish(){}
	
	/**
	 * 上传图片
	 * 
	 */
	public function upload_img()
	{		
		$logo['Filedata'] = $_FILES['pic'];
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
			);
			$img_info = addslashes(serialize($logo_pic));	
		}
		if(!$logo_pic) 
		{
			$this->errorOutput('没有上传的图片信息');
		}
		$logoid=intval($this->input['logoid']);
		if($logoid)
		{
			$sql = 'SELECT id FROM ' . DB_PREFIX . "material  WHERE id = "  .$logoid;
			$ret = $this->db->query_first($sql);
		}
		if($logoid && $ret['id'])
		{
			$sql = " UPDATE " . DB_PREFIX . "material SET img_info = '" . $img_info ."' WHERE id = "  .$logoid;
			$query = $this->db->query($sql);
			$data['id'] = $logoid;	
		}
		else 
		{
			$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . $img_info ."'";
			$query = $this->db->query($sql);
			$vid = $this->db->insert_id();
			$data['id'] = $vid;
		}
		$data['img_info'] = hg_fetchimgurl($logo_pic,200,160);
		$this->addItem($data);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new trip_types_update();
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