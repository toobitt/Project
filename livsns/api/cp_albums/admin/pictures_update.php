<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: pictures_update.php 23021 2013-05-31 09:37:07Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','pictures');//模块标识
require('global.php');
/**
 * 
 * 图片数据更新API
 * 
 * 提供的方法：
 * 1) 更新图片数据
 * 2) 删除图片数据
 * 3) 推荐图片数据
 * 
 * @author chengqing
 *
 */

class updatePicturesApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function create(){}
	public function update(){}
	public function sort(){}
	public function publish(){}
	
	/**
	 * 审核图片数据
	 */
	public function audit()
	{
		//审核的状态(默认为1 通过审核)
		$state = isset($this->input['state']) ? intval($this->input['state']) : 1;
				
		//审核的相册IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['material_id'])));		
	
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值		
		$id_array = array_filter($id_array);
		
		$verify_id = implode(',' , $id_array);
		$sql = "UPDATE " . DB_PREFIX . "pictures SET state = " . $state . " WHERE material_id IN (" . $verify_id . ")";

		$r = $this->db->query($sql);

		$this->setXmlNode('pictures_info' , 'pictures');
		if($r)
		{
			$this->addItem('批量审核成功');
		}
		else
		{
			$this->addItem('批量审核失败');	
		}
		$this->output(); 
	}
	
	/**
	 * 批量删除图片数据
	 */
	public function delete()
	{
		/*
		//删除的相册IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['material_id'])));		
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入删除ID');		
		}
		
		$delete_id = implode(',' , $id_array);
		*/
		
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$delete_id = $id;
		
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "pictures WHERE material_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['material_id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'] ? $row['name'] : $row['file_name'],
					'cid' => $row['material_id'],
			);
			$data2[$row['material_id']]['content']['pictures'] = $row;
		}

		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "pictures WHERE material_id IN (" . $delete_id . ")";
	
			$r = $this->db->query($sql);
			
			$this->setXmlNode('pictures_info' , 'pictures');
			if($r)
			{
				$this->addItem('批量删除成功');
			}
			else
			{
				$this->errorOutput('删除失败！');	
			}
		}
		else
		{
			$this->errorOutput('删除失败！');
		}
		
		$this->output();
	}
	//还原
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		//还原照片
		if(!empty($content['pictures']))
		{
			$sql = "insert into " . DB_PREFIX . "pictures set ";
			$space='';
			foreach($content['pictures'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}
		return $data;
	}*/
	
	/**
	 * 
	 * 方法名不存在时调用的方法
	 */
	public function none()
	{
		$this->errorOutput('方法不存在');		
	}
}

/**
 *  程序入口
 */
$out = new updatePicturesApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>