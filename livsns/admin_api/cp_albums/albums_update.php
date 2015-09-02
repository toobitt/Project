<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: albums_update.php 8429 2012-07-27 03:23:40Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_albums_m');//模块标识
require(ROOT_DIR . 'global.php');

/**
 * 
 * 相册数据更新API
 * 
 * 提供的方法：
 * 1) 更新相册数据
 * 2) 删除相册数据
 * 3) 推荐相册数据
 * 
 * @author chengqing
 *
 */
class updateAlbumsApi extends BaseFrm
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

	/**
	 * 审核相册数据
	 */
	public function audit()
	{
		//审核的状态(默认为1 通过审核)
		$state = isset($this->input['state']) ? intval($this->input['state']) : 1;
				
		//审核的相册IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['albums_id'])));		
	
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值		
		$id_array = array_filter($id_array);
		
		$verify_id = implode(',' , $id_array);
		$sql = "UPDATE " . DB_PREFIX . "albums SET state = " . $state . " WHERE albums_id IN (" . $verify_id . ")";		
				
		$r = $this->db->query($sql);

		$this->setXmlNode('albums_info' , 'albums');
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
	 * 批量删除相册数据
	 */
	public function delete()
	{
		//删除的相册IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['albums_id'])));		
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入删除ID');		
		}
		
		$delete_id = implode(',' , $id_array);
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "albums WHERE albums_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['albums_id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['albums_name'],
					'cid' => $row['albums_id'],
			);
			$data2[$row['albums_id']]['content']['albums'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "albums WHERE albums_id IN (" . $delete_id . ")";

			$r = $this->db->query($sql);
			
			$this->setXmlNode('albums_info' , ' albums');
			if($r)
			{
				$this->addItem('删除成功');
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
	
	//还原,弃用,基类还原
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		//还原相册记录表
		if(!empty($content['albums']))
		{
			$sql = "insert into " . DB_PREFIX . "albums set ";
			$space='';
			foreach($content['albums'] as $k => $v)
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
$out = new updateAlbumsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>
