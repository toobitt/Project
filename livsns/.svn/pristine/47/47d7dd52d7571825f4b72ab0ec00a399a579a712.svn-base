<?php
define('MOD_UNIQUEID','xml');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/xml_mode.php');
class xml_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new xml_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if(!$_FILES['Fileda'])
		{
			$this->errorOutput("请上传模板文件");
		}
		
		$file_name= $_FILES['Fileda']['name'];
		$file_type = strtolower(strrchr($file_name,"."));
		if($file_type != '.xml')
		{
			$this->errorOutput('模板文件格式不对');
		}
		
		//获取模板名称
		$title = trim(urldecode($this->input['sourse_title']));
		if(!$title)
		{
			$this->errorOutput('模板名称不能为空');
			//$title = $_FILES['Fileda']['name'];
		}
		//检查模板名称是否重复
		if(!$this->check_name($title))
		{
			$this->errorOutput('模板名称不能重复');
		}
		$content = addslashes(file_get_contents($_FILES['Fileda']['tmp_name']));
		$data = array(
			'title'				=> $title,
			'content'			=> $content,
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'user_id'	 		=> $this->user['user_id'],
			'update_user_id' 	=> $this->user['user_id'],
			'user_name'	  		=> $this->user['user_name'],	
			'update_user_name'	=> $this->user['user_name'],		 	
			'ip'          		=> hg_getip(),
			'update_ip'			=> hg_getip(),
			'org_id'				=> $this->user['org_id'],
			'update_org_id' 		=> $this->user['org_id'],
			//'status'				=> 
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			if($this->input['group_id'] == 'other')
			{
				if(trim($this->input['new_group']))
				{
					$sql = "INSERT INTO " .DB_PREFIX. "xml_type SET title = '" .$this->input['new_group']. "', user_name = '" .$this->user['user_name']. "', create_time = '" .TIMENOW. "'";
					$this->db->query($sql);
					$type_id_new = $this->db->insert_id();
				}
			}
			elseif($this->input['group_id'] > 0)
			{
				$type_id_new = $this->input['group_id'];
			}
			if($type_id_new)
			{
				$sql = "UPDATE " .DB_PREFIX. "xml SET type_id = " .$type_id_new. " WHERE id = " .$vid;
				$this->db->query($sql);
			}
			
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if($_FILES['Fileda'])
		{
			$file_name= $_FILES['Fileda']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			if($file_type != '.xml')
			{
				$this->errorOutput('模板文件格式不对');
			}
			
			//获取模板名称
			$title = trim(urldecode($this->input['sourse_title']));
			if(!$title)
			{
				//$title = $_FILES['Fileda']['name'];
				$this->errorOutput('模板名不能为空');
			}
			
			$content = addslashes(file_get_contents($_FILES['Fileda']['tmp_name']));
		}
		else
		{
			$title = trim($this->input['title']);
			$content = trim(html_entity_decode($this->input['content']));
			if(!$content)
			{
				$this->errorOutput('模板内容错误');
			}
		}
		//检查模板名称是否重复
		if(!$this->check_name($title, $this->input['id']))
		{
			$this->errorOutput('模板名称不能重复');
		}
		$update_data = array(
			'content'			=> $content,
		);
		$title ? $update_data['title'] = $title : 1;
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$update_data2 = array(
				'update_time'		=> TIMENOW,
				'update_user_id' 	=> $this->user['user_id'],
				'update_user_name'	=> $this->user['user_name'],		 	
				'update_ip'			=> hg_getip(),
				'update_org_id' 		=> $this->user['org_id'],
			);
			$this->mode->update($this->input['id'],$update_data2);
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
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
		$id = explode(',', $this->input['id']);
		for($i=0;$i<count($id);$i++)
		{
			if(substr($id[$i],0,1) == '_')
			{
				$type_id[] = trim($id[$i],'_');
			}
			else
			{
				$xml_id[] = $id[$i];
			}
		}
		if(!empty($type_id))
		{
			$type_id = implode(',',$type_id);
			$sql = "SELECT is_open FROM " .DB_PREFIX. "xml_type WHERE id IN (" .$type_id. ")";
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				if($row['is_open'])
				{
					$delete = 1;
				}
			}
			$sql = "DELETE FROM " .DB_PREFIX. "xml_type WHERE id IN (" .$type_id. ")";
			$sql2 = "DELETE FROM " .DB_PREFIX. "xml WHERE type_id IN (" .$type_id. ")";
		}
		if(!empty($xml_id))
		{
			$xml_id = implode(',',$xml_id);
			$sql = "SELECT is_open FROM " .DB_PREFIX. "xml WHERE id IN (" .$xml_id. ")";
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				if($row['is_open'])
				{
					$delete = 1;
				}
			}
		}
		if(!$delete)
		{
			if($type_id)
			{
				$sql = "DELETE FROM " .DB_PREFIX. "xml_type WHERE id IN (" .$type_id. ")";
				$sql2 = "DELETE FROM " .DB_PREFIX. "xml WHERE type_id IN (" .$type_id. ")";
				$this->db->query($sql);
				$this->db->query($sql2);
			}
			if($xml_id)
			{
				$sql = "DELETE FROM " .DB_PREFIX. "xml WHERE id IN (" .$xml_id. ")";
				$this->db->query($sql);
			}
		}
		else
		{
			$this->errorOutput('已开启的模板配置不可删除');
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	public function audit()
	{
		$id = urldecode($this->input['id']);	
		$audit = $this->input['audit']; //操作标识,'审核'或'打回'
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}

		/**************审核权限判断***************
		$sql = 'SELECT * FROM '.DB_PREFIX.'bgpicture WHERE id IN ('. $id .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				//$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
				if($val['is_using'] && $audit == 0)
				{
					$this->errorOutput('背景图片正在使用中,不可打回<br/>若要打回,请先确定要打回的图片不被占用');
				}
			}
		}
		*********************************************/
		
		if($audit == 1)	//'审核'操作
		{
			$status = 1;
			$audit_status = '已审核';
		}
		elseif($audit == 0)	//'打回'操作
		{
			$status = 2;
			$audit_status = '已打回';
		}
		
		$sql = " UPDATE " .DB_PREFIX. "xml SET status = " .$status. " WHERE id in (" . $id . ")";
		$this->db->query($sql);
		$ret = array('status' => $status,'id' => $id,'audit'=>$audit_status);
	
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核XML模板' . $id);	//此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	public function check_name($name = '', $id = '0')
	{
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "xml WHERE title = '" .$name. "' AND id != " .$id;
		$row = $this->db->query_first($sql);
		if ($row['total'] > 0)
		{
			return false;
		}
		return true;
	}
	/*
	 * 开关功能 (代替原来的审核功能)
	 */
	public function open()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = explode(',', $this->input['id']);
		for($i=0;$i<count($id);$i++)
		{
			if(substr($id[$i],0,1) == '_')
			{
				$type_id[] = trim($id[$i],'_');
			}
			else
			{
				$xml_id[] = $id[$i];
			}
		}
		
		$is_open = $this->input['is_open']; //1打开2关闭
		
		/**************权限判断***************
		$sql = 'SELECT * FROM '.DB_PREFIX.'bgpicture WHERE id IN ('. $id .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				//$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
				if($val['is_using'] && $audit == 0)
				{
					$this->errorOutput('背景图片正在使用中,不可打回<br/>若要打回,请先确定要打回的图片不被占用');
				}
			}
		}
		*********************************************/
		
		if($is_open == 1)	//'打开'操作
		{
			$is_open = 1;
		}
		elseif($is_open == 0)	//'关闭'操作
		{
			$is_open = 0;
		}
		if(!empty($type_id))
		{
			$type_id = implode(',', $type_id);
			$sql = " UPDATE " .DB_PREFIX. "xml_type SET is_open = " .$is_open. " WHERE id in (" . $type_id . ")";
			$this->db->query($sql);
		}
		if(!empty($xml_id))
		{
			$xml_id = implode(',', $xml_id);
			$sql = " UPDATE " .DB_PREFIX. "xml SET is_open = " .$is_open. " WHERE id in (" . $xml_id . ")";
			$this->db->query($sql);
		}
		//$ret = array('status' => $status,'id' => $id,'audit'=>$audit_status);
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

$out = new xml_update();
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