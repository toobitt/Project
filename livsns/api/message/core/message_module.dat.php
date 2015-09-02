<?php
class MessageModule extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/*
	*添加模块
	*
	*@module array 模块信息
	*
	*@set array 具体设置
	*
	*/
	function add_module($module,$set)
	{
		$mod = $module;
		
		$sql = 'INSERT INTO '.DB_PREFIX.'message_module SET ';
		foreach($mod as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		
		if($this->db->query($sql))
		{
			$id = $this->db->insert_id();
			if($id)
			{
				$set['mid'] = $id;
				$set_sql = 'INSERT INTO '.DB_PREFIX.'message_set SET ';
				foreach($set as $ks=>$vs)
				{
					if($ks!='mid' && $vs == '')
					{
						$vs = '2';
					}
					$set_sql .= '`'.$ks . '`="' . $vs . '",';
				}
				$set_sql = rtrim($set_sql,',');
				if($this->db->query($set_sql))
				{
					return $this->db->affected_rows();
				}
				else
				{
					return false;
				}
			}		
		}
		else
		{
			return false;
		}
	}
	/**
	*
	*查找所有模块具体配置信息
	*@type int 类型
	*
	**/
	public function show()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."message_module";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$list[] = $r;
		}
		return $list;
	}
	/**
	*
	*查找某个模块具体配置信息
	*
	*@id int 模块id
	*@type int 类型
	*@form array定义前台显示数组
	**/
	public function detail($condition,$type,$form='')
	{	

		if($type != '3')
		{
			$sql = "SELECT ms.*,md.module_name,md.mid as md_mid FROM ".DB_PREFIX."message_set as ms 
			LEFT JOIN ".DB_PREFIX."message_module as md ON ms.mid = md.id 
			WHERE 1 ". $condition;
			$return = array();
			//$return['sql'] = $sql;
			$q = $this->db->query($sql);
			
			while($r = $this->db->fetch_array($q))
			{   
				$return['info'][$r['md_mid']] = $r;
			}
			
			foreach($return['info'] as $key=>$val)
			{
				if(!$key)
				{
					$global = $return['info'][0]; 
				}
				else
				{
					$list = $return['info'][$key];
				}
			}
			//判断模块设置
			if($type == '1' || !$type)//全局
			{
				$return['info'] = $global;
			}
			else if($type == '2')//模块
			{
				if(is_array($list) && count($list)>0){
					foreach($list as $k=>$v)
					{
						if($k!='id' && $k!='mid' && $v=='2')
						{
							$list[$k] = $global[$k];
						}
						else
						{
							$list[$k] = $list[$k];
						}		
					}
				}
				$return['info'] = $list;
			}
			//$return['form'] = $form;
		}
		else
		{
			//$return['form'] = $form;
		}
		return $return;
	}
}
?>