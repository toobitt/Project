<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','vod');
require_once(ROOT_PATH.'lib/class/material.class.php');
class  vod_update_img extends adminBase
{
	private $thumb;
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:视频的记录id
	 *功能:更新视频的缩略图
	 *返回值:视频的id与更新后图片的链接
	 * */
	public function update_img()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT *  FROM ".DB_PREFIX."vodinfo  WHERE  id=".$this->input['id'];
		$arr = $this->db->query_first($sql);
		$status = intval($arr['status']);
		$expand_id = intval($arr['expand_id']);
		$column_id = $arr['column_id'];
		
		 $img_info = array();
		//先查出有没有从本地上传图片，如果有的话采用本地上传的图片
		if($this->input['img_src_cpu'])
		{
		    $img_path = urldecode($this->input['img_src_cpu']);
		    if(strrpos($img_path,'?'))
		    {
		    	$img_path = substr($img_path,0,strrpos($img_path,'?'));
		    }
		    $img_info = $this->create_thumb($img_path,$this->input['id']);
		}
		else 
		{
			if($this->input['img_src'])
		    {
			    $img_path = urldecode($this->input['img_src']);
		        $img_info = $this->create_thumb($img_path,$this->input['id']);
		    }
		    else if($this->input['source_img_pic'])
		    {
		    	$img_path = urldecode($this->input['source_img_pic']);
		    	if(strrpos($img_path,'?'))
		    	{
		    		$img_path = substr($img_path,0,strrpos($img_path,'?'));
		    	}
		        $img_info = $this->create_thumb($img_path,$this->input['id']);
		    }
		    else 
		    {
		    	$this->errorOutput(NOIMGPATH);
		    }
		    
		}
		
		if ($img_info)
		{
			$img_info_arr = array(
				'host' => $img_info['host'],
				'dir' => $img_info['dir'],
				'filepath' =>$img_info['filepath'],
				'filename' => $img_info['filename'],
				'imgwidth' 	=> $img_info['imgwidth'],
				'imgheight' => $img_info['imgheight'],
			);
			$sql = "UPDATE ".DB_PREFIX."vodinfo  SET  ";
			$sql .= " img_info ='".serialize($img_info_arr)."' ";
			$sql .= "  WHERE 1 AND id=".$this->input['id'];
			
			$q = $this->db->query($sql);
			$return['id'] = intval($this->input['id']);
			$return['img'] = $img_info['host'].$img_info['dir'].$img_info['filepath'].$img_info['filename'];
			if($status == 2)
			{
				$op = '';
				if(!empty($expand_id))
				{
					$op = "update";			
				}
				$this->publish_video(intval($this->input['id']), $op);
				$return['pubstatus'] = 1;
			}
			else 
			{
				$return['pubstatus'] = 0;
			}
			$this->addItem($return);
		}
		else 
		{
		    $this->errorOutput('无法设定指定图片作为示意图');
		}
		
		$this->output();
		
	}
	
	//去图片服务器请求图片
	public function create_thumb($url,$cid)
    {
    	$material = new material();
    	$img_info = $material->localMaterial($url,$cid);
    	return $img_info[0];
    }
	
	/*功能:保存预览图片,视频截图
	 *返回值:图片的访问路径
	 * */
	public function  preview_pic()
	{
		$material_pic = new material();
		if($this->input['base64'] && $this->input['Filedata'])
		{
			//$img = base64_decode(str_replace('data:image/png;base64,', '', urldecode($this->input['Filedata'])));
			$info = $material_pic->imgdata2pic(urldecode($this->input['Filedata']));
			$return = $info[0]['host'].$info[0]['dir'].$info[0]['filepath'].$info[0]['filename'];
		}
		if($_FILES['Filedata'])
		{
			$img_info = $material_pic->addMaterial($_FILES);
			$return = array('id'=> $this->input['id'],'img_path' => $img_info['url']);
		}
		$this->addItem($return);
		$this->output();
	}

	private function publish_video($id,$op,$column_id = array())
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
			
		$sql = "select * from " . DB_PREFIX ."vodinfo where id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}
		}
		else
		{
			$column_id = implode(',',$column_id);
		}

		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 		=> PUBLISH_SET_ID,
			'from_id' 		=> $info['id'],
			'class_id' 		=> 0,
			'column_id' 	=>  $column_id,
			'title' 		=> $info['title'],
			'action_type'	=> $op,
			'publish_time'	=> $info['pub_time'],
			'publish_people'=> $this->user['user_name'],
			'ip'=> hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	  
}

$out = new vod_update_img();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'update_img';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>