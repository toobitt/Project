<?php
require('global.php');
define(MOD_UNIQUEID,'ranking');
class RankingUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function sort(){}
	public function publish(){}	
	
	function create()
	{
		if(!trim($this->input['title']))
		{
			$this->errorOutput('名称不能为空');
		}
		$info = array(
			'title' 	=> addslashes($this->input['title']),
			'start_time'=> $this->input['start_time'] ? strtotime($this->input['start_time']) : '',
			'duration'  => $this->input['duration'],
			'column_id' => urldecode($this->input['column_id']),
            'publish_duration'  => intval($this->input['publish_duration']),
			'limit_num' => $this->input['limit_num'] ? intval($this->input['limit_num']) : '30',
			'user_id'   => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'output_type' => $this->input['output_type'] ? 1: 0,
            'k'           => $this->input['k'],
		);
        if (!empty($this->input['type'])) {
            $type = implode(',', $this->input['type']);
            if ($this->input['user_defined_type']) {
                $type .= "," . $this->input['user_defined_type'];
            }
        }
        else {
            $type = $this->input['user_defined_type'];
        }
        $info['type'] = $type;
		$sql = "INSERT INTO ".DB_PREFIX."ranking_sort SET ";
		$space = '';
		foreach($info as $key => $value)
		{
			$sql .= $space . $key ."='".$value."'";
			$space = ',';
		}
		$this->db->query($sql);
		$this->addLogs('创建排行分类','',$info,$info['title']);
		$this->addItem('true');
		$this->output();
	}
	
	function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NOID');
		}
		if(!trim($this->input['title']))
		{
			$this->errorOutput('名称不能为空');
		}
		$info = array(
			'title' 	  => addslashes($this->input['title']),
			'start_time'  => $this->input['start_time'] ? strtotime($this->input['start_time']) : '',
 			'duration'    => $this->input['duration'],
			'column_id'   => urldecode($this->input['column_id']),
            'publish_duration'    => intval($this->input['publish_duration']),
			'limit_num'   => $this->input['limit_num'] ? intval($this->input['limit_num']) : '30',
			'update_time' => TIMENOW,
			'output_type' => $this->input['output_type'] ? 1 : 0,
            'k'           => $this->input['k'],
		);	
        if (!empty($this->input['type'])) {
            $type = implode(',', $this->input['type']);
            if ($this->input['user_defined_type']) {
                $type .= "," . $this->input['user_defined_type'];
            }
        }
        else {
            $type = $this->input['user_defined_type'];
        }
        $info['type'] = $type;
        
		$sql = "UPDATE " . DB_PREFIX . "ranking_sort SET ";
		$space = '';
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "='".$value."'";
			$space = ',';	
		}
		$sql .= " WHERE id = " . $id;	
		$this->db->query($sql);
		if($this->db->affected_rows() > 0 )
		{
			$data = array(
				'update_time' => TIMENOW,
			);
			$sql = "UPDATE " . DB_PREFIX . "ranking_sort SET ";
			$space = '';
			foreach($data as $key => $value)
			{
					$sql .= $space . $key . "='".$value."'";
					$space = ',';	
			}
			$sql .= " WHERE id = " . $id;	
			$this->db->query($sql);
			$this->addLogs('修改排行类型','',$info,$info['title']);
		}
		$this->addItem($info);
		$this->output();
	}
	
	function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NOID');
		}
		$sql = "SELECT * FROM ".DB_PREFIX."ranking_sort WHERE id IN(".$id.")";
		$q = $this->db->query($sql);
		$original_data = array();
		while($row = $this->db->fetch_array($q))
		{
			if($row['system'])
			{
				$this->errorOutput($row['title'] . "为系统内置类型不能删除!");
			}
			$original_data[] = $row;
		}
		$sql = "DELETE FROM ".DB_PREFIX."ranking_sort WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		$sql = "DELETE FROM ".DB_PREFIX."ranking_cont WHERE sort_id IN(".$id.")";
		$this->db->query($sql);
		$this->addLogs('删除排行类型',$original_data,'','删除排行类型+' . $id);
		$this->addItem($id);
		$this->output();
	}
	
	function audit()
	{
		$ids = urldecode($this->input['id']);
		$audit = urldecode($this->input['audit']);
		if(!$ids)
		{
			$this->errorOutput('NOID');
		}
		$arr_ids = explode(',',$ids);
		if($audit == 1)
		{
			$sql = "UPDATE ".DB_PREFIX."ranking_sort SET status = 1 WHERE id IN(".$ids.")";
			$this->db->query($sql);
			$opration = '审核排行类型';
			$return = array('id' => $arr_ids,'state' => 1,'msg' => $this->settings['status'][1]);
		}
		else
		{
			$sql = "UPDATE ".DB_PREFIX."ranking_sort SET status = 2 WHERE id IN(".$ids.")";
			$this->db->query($sql);
			$opration = '打回排行类型';
			$return = array('id' => $arr_ids,'state' => 2,'msg' => $this->settings['status'][2]);
		}
		$this->addLogs($opration, '', '', $opration . '+' . $ids);
		$this->addItem($return);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput("NOFUNCTION");
	}
}
$out = new RankingUpdate();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
