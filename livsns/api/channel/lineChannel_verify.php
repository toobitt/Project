<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: lineChannel_verify.php 3939 2011-05-20 02:04:05Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 
 * 功能 ：频道数据审核API
 * 
 * 提供的方法：
 * 1) 频道数据单条审核
 * 2) 频道数据批量审核
 * 
 * @author chengqing
 *
 */
class lineChannelVerify extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();			
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 审核单条频道数据
	 */
	public function single_verify()
	{
		//视频ID
		$id = isset($this->input['id']) ? intval($this->input['id']) : -1;
		
		if($id <= 0 )
		{
			$this->errorOutput('未传入审核网台的ID');	
		}
		
		//审核的状态
		$state = isset($this->input['state']) ? intval($this->input['state']) : -1;
		
		if($state < 0)
		{
			$this->errorOutput('未传入审核网台的状态');	
		}
		
		$sql = "UPDATE " . DB_PREFIX . "network_station SET state = " . $state . " WHERE id = " . $id;		
		$r = $this->db->query($sql);
		
		$this->setXmlNode('channel_info' , 'channel');
		if($r)
		{
			$this->addItem('审核成功');
		}
		else
		{
			$this->addItem('审核失败');	
		}
		$this->output(); 				
	}
	
	/**
	 * 批量审核频道数据
	 */
	public function verify()
	{
		//审核的状态
		$state = isset($this->input['state']) ? intval($this->input['state']) : -1;
		
		if($state < 0)
		{
			$this->errorOutput('未传入审核网台的状态');	
		}
		
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim($this->input['ids']));		
		$id_array = array();		
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		foreach($id_array as $k => $v)
		{
			if(!$v)
			{
				unset($id_array[$k]);
			}
		}
		
		$verify_id = implode(',' , $id_array);
		$sql = "UPDATE " . DB_PREFIX . "network_station SET state = " . $state . " WHERE id IN (" . $verify_id . ")";		
		$r = $this->db->query($sql);

		$this->setXmlNode('channel_info' , 'channel');
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
$out = new lineChannelVerify();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>
