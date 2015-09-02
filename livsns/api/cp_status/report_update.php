<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: report_update.php 8430 2012-07-27 03:33:01Z hanwenbin $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID','mblog_report_m');//模块标识
class reportUpdateApi extends outerUpdateBase
{
	function __construct() {
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
	}
	function __destruct() {
		parent::__destruct();
		$this->db->close();
	}
	
	public function create()
	{
		
	}
	
	/**
	 * 
	 * 更新举报的数据
	 */
	public function update()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		
		if($id <= 0)
		{
			$this->errorOutput('未传入更新举报的ID');	
		}
		
		//视频中需要更新的字段
		$update_field = array('content'       => urldecode(trim($this->input['content']))
		);
		
		$sql = "UPDATE " . DB_PREFIX . "report SET ";
		
		$field = '';
		foreach($update_field as $db_field => $value )
		{
			if(trim($value))
			{
				$field .= $db_field . " = '" . $value . "' ,";
			}
		}
		
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE id = " . $id;		
		$sql = $sql . $field . $condition;
				
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		$num = $this->db->affected_rows();

		$this->setXmlNode('report_info' , 'report');
		if($num)
		{
			$this->addItem('举报更新成功');
		}
		else
		{
			$this->addItem('举报更新失败');	
		}
		$this->output(); 
	}
	
function delete()
{
	$this->preFilterId();
	
	//内容表
	$sql = "select * from " . DB_PREFIX . "report where id  in(" . $this->input['id'] .")";
	$ret = $this->db->query($sql);
	while($row = $this->db->fetch_array($ret))
	{
		$data2[$row['id']] = array(
				'title' => $row['content'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
		$data2[$row['id']]['content']['report'] = $row;
	}
	
	//放入回收站
	if(!empty($data2))
	{
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
	}
	if($res['sucess'])
	{
		$sql = 'delete from '.DB_PREFIX.'report where id in('.$this->input['id'].')';
		$r = $this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		if($r)
		{
			$this->addItem('Success');
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

/**
 * 举报信息的审核
 */
public function audit()
{
	$this->preFilterId();
	$sql = 'UPDATE '.DB_PREFIX.'report'.' SET state = 1 WHERE id in('.$this->input['id'].')';
	//exit($sql);
	$this->db->query($sql);
	//记录日志
	$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'audit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
	//记录日志结束	
	if($rows = $this->db->affected_rows())
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
 * 举报信息的审核
 */
public function back()
{
	$this->preFilterId();
	$sql = 'UPDATE '.DB_PREFIX.'report'.' SET state = 0 WHERE id in('.$this->input['id'].')';
	$this->db->query($sql);
	//记录日志
	$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'back', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
	//记录日志结束	
	if($rows = $this->db->affected_rows())
	{
		$this->addItem('打回成功');
	}
	else
	{
		$this->addItem('打回失败');
	}
	$this->output();
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
$reportUpdateApi = new reportUpdateApi();
if(!method_exists($reportUpdateApi, $_INPUT['a']))
{
	$a = 'unknow';
}
else
{
	$a = $_INPUT['a'];
}
$reportUpdateApi->$a();
?>