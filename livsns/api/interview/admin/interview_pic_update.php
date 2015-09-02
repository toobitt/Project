<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH.'lib/pic.class.php');
define('MOD_UNIQUEID','interview_pic');//模块标识
class interview_pic_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->obj = new pic();
	}
	function __destruct()
	{
		parent::__destruct();
	}

	function delete()
	{
		if (!$this->input['id']){
			$this->errorOutput(NOID);
		}
		$arr = explode(',', urldecode($this->input['id']));
		//删除的图片是否是封面图片
		$sql = 'SELECT distinct(interview_id) FROM '.DB_PREFIX.'files WHERE id IN ('.urldecode($this->input['id']).')';
		$res = $this->db->query_first($sql);
		$a = 'SELECT cover_pic FROM '.DB_PREFIX.'interview  WHERE id ='.$res['interview_id'];
		$r = $this->db->query_first($a);
		//存在则将其置空
		if (in_array($r['cover_pic'], $arr)){
			$b = 'UPDATE '.DB_PREFIX.'interview SET cover_pic=0 WHERE id ='.$res['interview_id'];
			$this->db->query_first($b);
		}
		//删除相关图片记录
		$c = 'DELETE FROM '.DB_PREFIX.'files WHERE id IN ('.urldecode($this->input['id']).')';
		$this->db->query($c);
		$this->addItem($arr);
		$this->output();
	}
	
	

	function update()
	{
		if ($this->input['name'])
		{
			$this->errorOutput('图片名称必填');
		}
		
		//参数接收
		$data = array(
			'id'=>urldecode($this->input['id']),
			'name'=>urldecode($this->input['title']),
			'show_pos' => urldecode($this->input['show_pos']),
			'interview_id'=>urldecode($this->input['interview_id']),
			'mid'=>$this->input['kid'],
		);
		//上传图片处理
		if ($_FILES)
		{
		
			//重新上传
			$type = $this->obj->type($_FILES['Filedata']['type']);
			if (!in_array($type, $this->settings['file_type']))
			{
				$this->errorOutput('格式不正确，请上传jpg,png,gif,jpeg格式的图片！');
			}
			if ($_FILES['Filedata']['size']>2000000)
			{
				$this->errorOutput('请上传文件小于2M的图片！');
			}
			//删除之前图片
			$sql = 'SELECT original_id FROM '.DB_PREFIX.'files WHERE id='.$data['id'];
			$res = $this->db->query_first($sql);
			$this->obj->del_pic($res['original_id']);
			$arr = $this->obj->interview_uplaod($_FILES,$data['interview_id']);
			$sql = 'UPDATE '.DB_PREFIX.'files SET file_name="'.$arr['filename'].
					'",host="'.$arr['host'].
					'",dir="'.$arr['dir'].
					'",file_path="'.$arr['filepath'].
					'",file_type="'.$arr['type'].
					'",file_size="'.$arr['filesize'].
					'",is_img=1,original_id='.$arr['id'].' WHERE id='.$data['id'];
			$this->db->query($sql);
		}
		
		//如果此图片被设为头图片，则将以前的头部图片位置设为其他
		if ($data['show_pos']==0){
			$sql = 'UPDATE '.DB_PREFIX.'files SET show_pos=2 WHERE show_pos=0';
			$this->db->query($sql);
		}
		$sql = 'UPDATE '.DB_PREFIX.'files SET name = "'.addslashes($data['name']).
		'",show_pos = "'.$data['show_pos'].'"
		WHERE id = '.$data['id'];
		if ($this->db->query($sql)){
			$this->addItem(array('interview_id'=>$data['interview_id']));
		}else {
			$this->addItem('error');
		}
		$this->output();
	}
	/**
	 * 禁用控制方法
	 */
	function disable()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if (intval($this->input['status']) == 0){

			$state = 1;
		}
		if (intval($this->input['status']) == 1){

			$state = 0;
		}
		$sql = 'UPDATE '.DB_PREFIX.'files SET is_ban = '.$state.
		' WHERE id = '.intval($this->input['id']);
		$this->db->query($sql);
		$this->addItem($state);
		$this->output();
	}
	/**
	 * 
	 * 设置封面的方法
	 */
	function cover_pic()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		//如果封面ID和设置封面的ID相同，则为取消封面设置，否则就是设置封面
		if ($this->input['id']==$this->input['cover_id'])
		{
			$arr = $this->obj->off_cover_pic($this->input['id'],$this->input['vid'],$this->input['cover_id']);
			
		}else {
			$arr = $this->obj->set_cover_pic($this->input['id'],$this->input['vid'],$this->input['cover_id']);
		}
		$this->addItem($arr);
		$this->output();
	}
	/**
	 * 
	 * 上传图片
	 */
	function create()
	{
		//参数接收
		$data = array(
			'interview_id'=>$this->input['interview_id'],
			'mid'=>$this->input['kid'],
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'create_time'=>TIMENOW,
		);
		$data['user_id'] = $data['user_id'] ? $data['user_id']:0;
		$data['user_name'] = $data['user_name'] ? $data['user_name']:'匿名用户';
		$id = $this->input['picid'];
		foreach ($id as $v)
		{
			if ($_FILES['uploadinput'.$v]){
			
				$type = $this->obj->type($_FILES['uploadinput'.$v]['type']);
				if (!in_array($type, $this->settings['file_type']))
				{
					$this->errorOutput('格式不正确，请上传jpg,png,gif,jpeg格式的图片！');
				}
				if ($_FILES['uploadinput'.$v]['size']>2000000)
				{
					$this->errorOutput('请上传文件小于2M的图片！');
				}
				$name=$this->input['upload'.$v.'_name'];
				foreach($_FILES['uploadinput'.$v] AS $key =>$value)
				{
					
					$_FILES['Filedata'][$key] = $value;
					$file['Filedata'] = $_FILES['Filedata'];
				}
						
			    $arr = $this->obj->interview_uplaod($file, $data['interview_id']);
			    if ($arr)
			    {
			    	$fname  = addslashes(trim(urldecode($this->input['upload'.$v.'_name'])));
			    	$sql = 'INSERT INTO '.DB_PREFIX.'files SET interview_id='.$data['interview_id'].
			    	',name="'.$fname.
			    	'",host="'.$arr['host'].
			    	'",dir="'.$arr['dir'].
			    	'",file_name="'.$arr['filename'].
			    	'",file_path="'.$arr['filepath'].
			    	'",file_type="'.$arr['type'].
			    	'",file_size="'.$arr['filesize'].
			    	'",original_id="'.$arr['id'].
			    	'",is_img=1
			    	,create_time='.$data['create_time'].
			    	',is_ban=0
			    	,show_pos=2
			    	,user_id='.$data['user_id'].'
			    	,user_name="'.$data['user_name'].'"';
			    	$this->db->query($sql);
					$oid = $this->db->insert_id();
					//是否存在封面图片，不存在则把第一张图片当封面图片
					$sql = 'SELECT cover_pic FROM '.DB_PREFIX.'interview WHERE id='.$data['interview_id'];
					$query = $this->db->query_first($sql);
					$indexpic = $query['cover_pic'];
					if(!$indexpic)
					{
						$indexpic = $oid;
					}
					$sql = 'UPDATE '.DB_PREFIX.'interview SET cover_pic = '.$indexpic.' WHERE id = '.$data['interview_id'];
					$this->db->query($sql);
					$update_sql = 'UPDATE '.DB_PREFIX.'files set order_id = '.$oid.' WHERE id = '.$oid;
					$this->db->query($update_sql);
					$this->addItem('success');
			    }
			}				
		}
		$this->output();
	}
	function audit()
	{
	
	}
	
	function sort()
	{
	
	}
	
	function publish()
	{
		
	}

}

$ouput= new interview_pic_update();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
