<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: shorturl_update.php 8667 2012-08-01 05:35:24Z hanwenbin $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR.'global.php');
define('MOD_UNIQUEID','shorturl_m');//模块标识
class shorturlUpdateApi extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	function __destruct()
	{
		parent::__destruct();
		$this->db->close();
	}
	
	public function create(){
	}

	public  function update()
	{
		$video_id = $this->input['id'] ? intval($this->input['id']) : -1;
		
		if($video_id <= 0)
		{
			$this->errorOutput('未传入ID');	
		}
		
		//视频中需要更新的字段
		$update_field = array(
		                      'url' 		=> urldecode(trim($this->input['url']))	
		);

		$sql = "UPDATE " . DB_PREFIX . "urls SET ";
		
		$field = '';
		foreach($update_field as $db_field => $value )
		{
			if(trim($value))
			{
				$field .= $db_field . " = '" . $value . "' ,";
			}
		}
		$this->setXmlNode('shorturl' , 'url');
		if (!$field)
		{
			$this->output(); 
		}
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE id = " . $video_id;		
		$sql = $sql . $field . $condition;
				
		$this->db->query($sql);
		if($r)
		{
			$this->addItem('更新成功');
		}
		else
		{
			$this->addItem('更新失败');	
		}
		$this->output(); 
	}
	/**
	 * 删除短URL
	 */
	public  function delete()
	{
		$this->preFilterId();
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "urls WHERE id IN (" . $this->input['id'] . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['url'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['urls'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = 'delete from '.DB_PREFIX.'urls where id in('.$this->input['id'].')';
			//hg_pre($sql);
			$r = $this->db->query($sql);
			if($r)
			{
				$this->addItem('success');
			}
			$this->output();
		}
	}
	private function preFilterId()
	{
		if(isset($this->input['id']) && !empty($this->input['id']))
		{
			$this->input['id'] = urldecode($this->input['id']);
			$ids = explode(',', $this->input['id']);
			//批量删除不能大于20个
			if(count($ids)>20)
			{
				$this->errorOutput('批处理上限');
			}
			foreach ($ids as $id)
			{
				
				if(!preg_match('/^\d+$/', $id))
				{
					$this->errorOutput('参数不合法');
				}
			}
			$this->input['id'] = implode(',', array_unique($ids));
		}
		else 
		{
			$this->errorOutput('参数不合法');
		}
	}
	public function unknow()
	{
		$this->errorOutput('方法不存在');
	}
}
$shorturlUpdateApi = new shorturlUpdateApi();
if(!method_exists($shorturlUpdateApi, $_INPUT['a']))
{
	$a = 'unknow';
}
else
{
	$a = $_INPUT['a'];
}
$shorturlUpdateApi->$a();