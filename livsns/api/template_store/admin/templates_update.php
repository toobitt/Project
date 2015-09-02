<?php
require_once('global.php');
define('MOD_UNIQUEID', 'template_update');
define('SCRIPT_NAME', 'template_update');
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
class template_update extends adminBase
{
	private $curd = null;
	public function __construct()
	{
		parent::__construct();
		$this->curd = new curd('templates');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show(){}
	public function create()
	{
		$input = $this->initdata();
		if($id = $this->curd->create($input))
		{
			$input['id'] = $id;
		
			if($input['video'])
			{
				$this->insert2cron($input['video']);
			}
			$this->curd->update(array('id'=>$id, 'order_id'=>$id));
			$this->addItem($input);
			$this->output();
		}
		$this->errorOutput('创建失败');
	}
	public function update()
	{
		$input = $this->initdata();
		if(!$input['id'])
		{
			$this->errorOutput("纪录不存在");
		}
		else
		{
			$data = $this->curd->detail($input['id']);
			if($data['video'] != $input['video'])
			{
				$this->insert2cron($input['video']);
			}
		}
		if($this->curd->update($input))
		{
			$this->addItem($input);
			$this->output();
		}
		$this->errorOutput('更新失败');
	}
	public function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput("纪录不存在");
		}
		if(!$this->curd->delete($id))
		{
			$this->errorOutput("删除失败");
		}
		$this->addItem('success');
		$this->output();
	}
	protected  function initdata()
	{
		$data = array(
		'title'			=>$this->input['title'],
		'brief'			=>$this->input['brief'],
		'keywords'		=>$this->input['keywords'],
		'sort_id'		=>$this->input['sort_id'],
		'color'			=>$this->input['template_color'],
		'version'		=>$this->input['template_version'],
		'style'			=>$this->input['template_style'],
		'use'			=>$this->input['template_use'],
		'size'			=>$this->input['size'],
		'resolution'	=>$this->input['resolution'],
		'duration'		=>$this->input['duration'],
		'format'		=>$this->input['format'],
		//'record'		=>0,
//		'index_pic'		=>$_FILES['indexpic'] ? $this->upload_material($file['Filedata'] = $_FILES['indexpic']) : '',
		'video'			=>$this->input['video'],
		'price'			=>'',
		'user_id'		=>$this->user['user_id'],
		'user_name'		=>$this->user['user_name'],
		'ip'			=>hg_getip(),
		'create_time'	=>TIMENOW,
		);
		if($_FILES['indexpic'])
		{
			$file['Filedata'] = $_FILES['indexpic'];
			unset($_FILES['indexpic']);
			if($tmp = $this->upload_material($file))
			{
				$data['index_pic'] = serialize($tmp);
			}
		}
		if($_FILES['template_material'])
		{
			$file['Filedata'] = $_FILES['template_material'];
			unset($_FILES['template_material']);
			if($tmp = $this->upload_material($file))
			{
				$data['material'] = serialize($tmp);
			}
		}
		if(intval($this->input['id']))
		{
			$data['id'] = intval($this->input['id']);
			unset($data['create_time']);
			unset($data['user_id']);
			unset($data['user_name']);
		}
		$erro_text = array(
		'title'			=>'标题不能为空',
		'brief'			=>'',
		'keywords'		=>'',
		'sort_id'		=>'请选择分类',
		'color'			=>'请选择色系',
		'version'		=>'请选择版本',
		'style'			=>'请选择风格',
		'use'			=>'请选择用途',
		'indexpic'		=>'',
		'video'			=>'',
		//'price'			=>'请设置价格',
		);
		foreach($data as $key=>$val)
		{
			if(!$val && $erro_text[$key])
			{
				$this->errorOutput($erro_text[$key]);
			}
		}
		return $data;
	}
	public function upload_material($file, $index='Filedata')
	{
		if($file[$index]['error'])
		{
			return false;
		}
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		$attatch = $this->mMaterial->addMaterial($file);
		if($attatch)
		{
			return array('host'=>$attatch['host'], 'dir'=>$attatch['dir'], 'filepath'=>$attatch['filepath'], 'filename'=>$attatch['filename']);
		}
		return false;
	}
	public function update_weight()
	{
		$data = json_decode(html_entity_decode($this->input['data']),1);
		
		if(!$data)
		{
			$this->errorOutput("数据解析失败");
		}
		foreach ($data as $key=>$val)
		{
			$this->curd->update(array('id'=>$key,'weight'=>$val));
		}
		$this->addItem($data);
		$this->output();
	}
	public function audit()
	{
		if (!$id = $this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $id = urldecode($this->input['id']);
        $data = $this->curd->show(' id,status ', ' AND id IN('.$id.') ');
        if(!$data)
        {
        	$this->errorOutput("操作记录无效");
        }
        $audit = intval($this->input['audit']);
		switch ($audit)
        {
        	case 1 : $audit=2;break;
        	case 0 : $audit=3;break;
        	//case 3 : $audit=2;break;
        }
        foreach($data as $val)
        {
	        if(in_array($val['status'],array(-1,0)))
	        {
	        	$this->errorOutput("视频状态错误，无法操作");
	        }
	        $update = array('id'=>$val['id'], 'status'=>$audit, 'audit_time'=>TIMENOW,'audit_user_id'=>$this->user['user_id'],'audit_user_name'=>$this->user['user_name']);
	        if(!$this->curd->update($update))
	        {
	        	$this->errorOutput('操作失败');	
	        }
	        
        }
	    $this->addItem(array('status'=>$audit,'id'=>explode(',', $id)));
        $this->output();
	}
	public function insert2cron($vid = 0)
	{
		if($vid)
		{
			$this->curd->set_table('attach');
			$cron = $this->curd->detail($vid);
			if($cron)
			{
				return true;
			}
			$data = array(
			'attach_id'		=>$vid,
			'status'		=>0,
			'update_time'	=>TIMENOW,
			'create_time'	=>TIMENOW,
			'host'			=>$this->input['host'],
			'port'			=>$this->input['port'],
			);
			$this->curd->create($data);
			$this->curd->set_table('templates');
			return true;
		}
		return false;
	}
	function drag_order()
	{
		parent::drag_order('templates', 'order_id');
		$this->addItem('succss');
		$this->output();
	}
}

include ROOT_PATH . 'excute.php';
?>