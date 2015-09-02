<?php
define('MOD_UNIQUEID','user_interface');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/user_interface_mode.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/attribute_relate_mode.php');

class user_interface_update extends adminUpdateBase
{
	private $mode;
	private $attr_relate;
	private $material;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new user_interface_mode();
		$this->attr_relate = new attribute_relate_mode();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
	    $name     = $this->input['name'];//UI名称
        $type     = intval($this->input['type']);//类型   
	    $brief    = $this->input['brief'];//简介
	    $uniqueid = $this->input['uniqueid'];//标识
	    
	    if(!$name)
	    {
	        $this->errorOutput(NO_UI_NAME);
	    }
	    
	    if(!$type)
	    {
	        $this->errorOutput(NO_UI_TYPE);
	    }
	    
	    if(!$uniqueid)
	    {
	        $this->errorOutput(NO_UNIQUEID);
	    }
	    else 
	    {
	        //检测是否已经存在该标识
	        if($this->mode->detail(''," AND uniqueid = '" .$uniqueid. "' "))
	        {
	            $this->errorOutput(UNIQUEID_HAS_EXISTS);
	        }
	    }
	    
		$data = array(
			'name'        	=> $name,
		    'brief'         => $brief,
		    'uniqueid'		=> $uniqueid,
		    'type'			=> $type,
		    'user_id'	    => $this->user['user_id'],
		    'user_name'		=> $this->user['user_name'],
		    'create_time'	=> TIMENOW,
		    'update_time'	=> TIMENOW,
		);
		
	    //处理UI图片
		if($_FILES['ui_pic'])
		{
			$_FILES['Filedata'] = $_FILES['ui_pic'];
			$img_info = $this->material->addMaterial($_FILES);
			if($img_info)
			{
				$pic = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'width'		=> $img_info['width'],
					'height'	=> $img_info['height'],
				);
				$data['img_info'] = @serialize($pic);
			}
		}
		
		if($_FILES['comp_img'])
		{
			$_FILES['Filedata'] = $_FILES['comp_img'];
			$img_info = $this->material->addMaterial($_FILES);
			if($img_info)
			{
				$pic = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'width'		=> $img_info['width'],
					'height'	=> $img_info['height'],
				);
				$data['comp_img'] = @serialize($pic);
			}
		}
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建UI',$data,'','创建UI:' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		$id       = $this->input['id'];//id
		$name     = $this->input['name'];//UI名称
        $type     = intval($this->input['type']);//类型   
	    $brief    = $this->input['brief'];//简介
	    $uniqueid = $this->input['uniqueid'];//标识
	    
	    if(!$id)
	    {
	        $this->errorOutput(NOID);
	    }
		
	    if(!$name)
	    {
	        $this->errorOutput(NO_UI_NAME);
	    }
	    
	    if(!$type)
	    {
	        $this->errorOutput(NO_UI_TYPE);
	    }
	    
	    if(!$uniqueid)
	    {
	        $this->errorOutput(NO_UNIQUEID);
	    }
	    else 
	    {
	        //检测是否已经存在该标识
	        if($this->mode->detail(''," AND uniqueid = '" .$uniqueid. "' AND id != '" .$id. "' "))
	        {
	            $this->errorOutput(UNIQUEID_HAS_EXISTS);
	        }
	    }
	    
		$update_data = array(
			'name'        	=> $name,
		    'brief'         => $brief,
		    'uniqueid'		=> $uniqueid,
		    'type'			=> $type,
		    'update_time'	=> TIMENOW,
		);
		
	    //处理UI图片
		if($_FILES['ui_pic'])
		{
			$_FILES['Filedata'] = $_FILES['ui_pic'];
			$img_info = $this->material->addMaterial($_FILES);
			if($img_info)
			{
				$pic = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'width'		=> $img_info['width'],
					'height'	=> $img_info['height'],
				);
				$update_data['img_info'] = @serialize($pic);
			}
		}
		
	    if($_FILES['comp_img'])
		{
			$_FILES['Filedata'] = $_FILES['comp_img'];
			$img_info = $this->material->addMaterial($_FILES);
			if($img_info)
			{
				$pic = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'width'		=> $img_info['width'],
					'height'	=> $img_info['height'],
				);
				$update_data['comp_img'] = @serialize($pic);
			}
		}
		
		$ret = $this->mode->update($id,$update_data);
		if($ret)
		{
			$this->addLogs('更新UI',$ret,'','更新UI:' . $id);
			$this->addItem('success');
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
			$this->addLogs('删除UI',$ret,'','删除UI:' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	//执行复制
	public function do_copy_attr()
	{
	    $id    = $this->input['id'];//来源UI的id
	    $ui_id = $this->input['ui_id'];//复制到的ui的id
	    if(!$id || !$ui_id)
	    {
	        $this->errorOutput(NOID);
	    }
	    
	    //判断被复制的有没有属性，如果有属性不能复制
	    $isHavAttr = $this->mode->isHavAttr($ui_id);
	    if($isHavAttr)
	    {
	        $this->errorOutput(THIS_UI_ALREADY_HAS_ATTR);
	    }
	    
	    //查询出来源UI下的所有属性
	    $attr_source = $this->mode->getAttrByUI($id);
	    if($attr_source)
	    {
	        foreach ($attr_source AS $k => $v)
	        {
	            $this->attr_relate->create(array(
	                    'name'         => $v['name'],
	                    'attr_id'      => $v['attr_id'],
	                    'ui_id'        => $ui_id,
	                    'group_id'     => $v['group_id'],
	                    'role_type_id' => $v['role_type_id'],
	                    'style_value'  => $v['style_value'],
	                    'default_value'=> $v['default_value'],
	                    'is_show'      => $v['is_show'],
	                    'user_id'      => $this->user['user_id'],
	                    'user_name'    => $this->user['user_name'],
	                    'create_time'  => TIMENOW,
	                    'update_time'  => TIMENOW,
	            ));
	        }
	    }
	    else 
	    {
	        $this->errorOutput(THIS_UI_NOT_HAS_ATTR);
	    }
	    
        $this->addItem('success');
        $this->output();	    
	}
	
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new user_interface_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 