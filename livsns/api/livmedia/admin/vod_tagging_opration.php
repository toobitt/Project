<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class  vod_tagging_opration extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function auto_save()
	{
		//根据传过来hash_id判断执行哪种操作
		$sql = " SELECT * FROM " .DB_PREFIX. "vcr_tmp WHERE hash_id = '" .$this->input['hash_id']. "'";
		$vcr = $this->db->query_first($sql);
		$type = $vcr['id']?'update':'create';
		$func = 'auto_save_' . $type;
		$this->$func($this->input);
	}
	
	//自动保存创建一个片段
	private function auto_save_create($vcr)
	{
		//先插入vcr_tmp表
		$data = array(
			'user_id'		=> $this->user['user_id'],
			'main_video_id'	=> $vcr['main_video_id'],//主页面视频id（从哪个视频点进来的）
			'title'			=> $vcr['title'],
			'vodinfo_id'	=> $vcr['vodinfo_id'],//视频id（该片段来自于哪个视频）
			'input_point' 	=> $vcr['input_point'],//入点时间
			'output_point' 	=> $vcr['output_point'],//出点时间
			'order_id'		=> $vcr['order_id'],
			'hash_id'		=> $vcr['hash_id'],
		);
		
		$sql = ' INSERT INTO ' .DB_PREFIX. 'vcr_tmp SET ';
		foreach($data AS $k => $v)
		{
			$sql .= $k  . ' = "' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		//生成图片
		$img_info = $this->imgdata2pic($vcr['imgdata']);
		$image_info = array(
			'host' 		=> $img_info['host'],
			'dir' 		=> $img_info['dir'],
			'filepath' 	=> $img_info['filepath'],
			'filename' 	=> $img_info['filename'],
		);
		$img_path = $img_info['host'] .$img_info['dir'] . $img_info['filepath'] . $img_info['filename'];
		$sql = "INSERT INTO " .DB_PREFIX. "img_tmp SET hash_id = '" .$vcr['hash_id']. "',img_path = '" . $img_path . "',img_info = '" .serialize($image_info). "'";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	//自动保存更新片段
	private function auto_save_update($vcr)
	{
		//先判断更新哪个表
		if($this->input['imgdata'])
		{
			//更新图片临时表
			$img_info = $this->imgdata2pic($vcr['imgdata']);
			$image_info = array(
				'host' 		=> $img_info['host'],
				'dir' 		=> $img_info['dir'],
				'filepath' 	=> $img_info['filepath'],
				'filename' 	=> $img_info['filename'],
			);
			$img_path = $img_info['host'] .$img_info['dir'] . $img_info['filepath'] . $img_info['filename'];
			$sql = "UPDATE " .DB_PREFIX. "img_tmp SET img_path = '" .$img_path."',img_info = '" .serialize($image_info). "' WHERE hash_id = '" .$vcr['hash_id']. "'";
		}
		else 
		{
			$sql = "UPDATE " .DB_PREFIX. "vcr_tmp SET ";
			if($this->input['title'])
			{
				$data = array(
					'title'			=> $vcr['title'],
				);
			}
			else 
			{
				$data = array(
					'input_point' 	=> $vcr['input_point'],//入点时间
					'output_point' 	=> $vcr['output_point'],//出点时间
				);
			}
			foreach($data AS $k => $v)
			{
				$sql .= $k  . ' = "' . $v . '",';
			}
			$sql  = rtrim($sql,',');
			$sql .= " WHERE hash_id = '" .$vcr['hash_id']. "'";
		}
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	//自动保存删除片段
	public function auto_save_delete()
	{
		if(!$this->input['hash_id'])
		{
			$this->errorOutput(NOID);
		}
		//先删除vcr_tmp表
		$sql = " DELETE FROM " .DB_PREFIX. "vcr_tmp WHERE hash_id IN (" .$this->input['hash_id']. ")";
		$this->db->query($sql);
		//再删除img_tmp表
		$sql = " DELETE FROM " .DB_PREFIX. "img_tmp WHERE hash_id IN (" .$this->input['hash_id']. ")";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	//自动保存排序片段
	public function auto_save_order()
	{
		$order_id = $this->input['order_id'];
		$hash_id  = $this->input['hash_id'];
		foreach($order_id AS $k => $v)
		{
			$sql = " UPDATE " .DB_PREFIX. "vcr_tmp SET order_id = '" .$v. "' WHERE hash_id = '" .$hash_id[$k]. "'";
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();
	}
	
	//视频拆条
	public function video_cutting()
	{
		if(!$this->input['main_video_id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT vt.*,it.img_path,it.img_info FROM " .DB_PREFIX. "vcr_tmp vt LEFT JOIN " . DB_PREFIX . "img_tmp it ON it.hash_id = vt.hash_id  WHERE vt.main_video_id = '" .$this->input['main_video_id']. "' AND vt.user_id = '" .$this->user['user_id']. "' ORDER BY vt.order_id DESC ";
		$q = $this->db->query($sql);
		$vcr_tmp = array();
		while($r = $this->db->fetch_array($q))
		{
			$vcr_tmp[] = $r;
		}
		
		if(!$vcr_tmp && empty($vcr_tmp))
		{
			$this->errorOutput('找不到片段数据');
		}
		
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] .'admin/');
		$curl->setSubmitType('post');
		foreach($vcr_tmp AS $k => $v)
		{
			if($v['input_point'] == -1 || $v['output_point'] == -1)
			{
				continue;
			}
			$curl->initPostData();
			$curl->addRequestData('id',$this->input['main_video_id']);
			$curl->addRequestData('a','add_mark');
			$curl->addRequestData('title',$v['title']);
			$curl->addRequestData('img_info',$v['img_info']);
			$curl->addRequestData('start_time[0]',$v['input_point']);
			$curl->addRequestData('duration[0]',intval($v['output_point']) - intval($v['input_point']));
			$curl->addRequestData('original_id[0]',$v['vodinfo_id']);
			$curl->addRequestData('vodinfo_id[0]',$this->input['main_video_id']);
			$curl->addRequestData('name[0]',$v['title']);
			$curl->addRequestData('order_id[0]',1);
			$curl->request('vod_add_video_mark.php');
		}
		
		//删除临时片段数据
		foreach($vcr_tmp AS $k => $v)
		{
			$sql = "DELETE FROM " . DB_PREFIX . "vcr_tmp WHERE hash_id = '" .$v['hash_id']. "'";
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "img_tmp WHERE hash_id = '" .$v['hash_id']. "'";
			$this->db->query($sql);
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	//将图片二进制数据
	private function imgdata2pic($imgdata)
	{
		$data  = explode(',',$imgdata);
		$data1 = explode(';',$data[0]);
		$type  = explode('/',$data1[0]);
		$material = new material();
    	$img_info = $material->imgdata2pic($data[1],$type[1]);
    	return $img_info[0];
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new vod_tagging_opration();
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