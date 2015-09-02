<?php
class creditrules extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$offset = 0,$count = 0)
	{
		$limit = '';
		if($count){
			$limit 	 = " LIMIT " . $offset . " , " . $count;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "credit_rules ";
		$sql.= " WHERE 1 " . $condition . " ORDER BY order_id DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['cycletypename']=$this->settings['cycletype'][$row['cycletype']];
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id)
	{

		$condition = " WHERE id = " . $id;
		$sql = "SELECT * FROM " . DB_PREFIX . "credit_rules " . $condition;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}

	/**
	 *
	 * 开关 ...
	 */
	public function display($ids, $opened)
	{
		$sql = 'UPDATE '.DB_PREFIX.'credit_rules SET opened = '.$opened.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'opened'=>$opened,
		);
		return $arr;
	}

	public function delete($id)
	{
		if(empty($id))
		{
			return 0;
		}
		$rules = $this->show('AND id IN ('.$id.')');
		if(is_array($rules))
		{
			foreach ($rules as $v)
			{
				if($v['issystem'])
				{
					return -1;
				}
				if($v['iscustom'])
				{
					$this->creditrules_diy_unset($v['operation'],$v['gids'],$v['appids']);
				}	
			}
		}
		if($this->membersql->delete('credit_rules', array('id'=>$id))){
			$this->membersql->delete('credit_rules_log',array('rid'=>$id));
			return array('status'=>1,'id'=>$id);
		}
	}

	public function getDiyRules($field = 'operation,rname',$isopen = 0)
	{
		$where = '';
		if($isopen)
		{
			$where =  ' AND opened = 1';
		}
		$sql  = 'SELECT '.$field.' FROM '.DB_PREFIX.'credit_rules WHERE iscustom = 1'.$where;
		$query=$this->db->query($sql);
		$ret = array();
		while ($row=$this->db->fetch_array($query))
		{
			$ret[$row[operation]] = $row;
		}
		return $ret;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "credit_rules WHERE 1 " . $condition;
		return  $this->db->query_first($sql);
	}

	//自定义积分字段清除
	public function creditrules_diy_unset($operation,$_gids,$appids)
	{
		$gids = '';
		$credits_rules=array();
		if(is_array($_gids)&&$_gids){
			$gids = trim("'".implode("','", $_gids )."'");
		}elseif($_gids)
		{
			$gids = $_gids;
		}
		$reDiy = array();
		if ($gids&&$operation)
		{
			$sql='SELECT id,rules FROM '.DB_PREFIX.'group WHERE id IN('.$gids.')';
			$query=$this->db->query($sql);
			while ($row=$this->db->fetch_array($query))
			{
				if($row['rules']){
					$rules=maybe_unserialize($row['rules']);
					unset($rules[$operation]);
					$credits_rules[$row['id']]=$rules;
				}
			}
			foreach ($credits_rules as $gid=>$rules)
			{
				$rules_diy=array();
				foreach ($rules as $key=>$val)
				{
					$rules_diy[$key]=$val['credits'];
				}
				$reDiy[group] = $this->Members->credits_rules_diy_group($gid, $rules_diy);
			}
		}
		if($appids&&$operation)
		{
			$DiyRulesInfo = $this->Members->getDiyRulesInfo($appids,true);
			if(is_array($DiyRulesInfo)){
				foreach ($DiyRulesInfo as $k => $v)
				{
					foreach ($v as $kk=>$vv)
					{
						if($kk == $operation){
							unset($v[$kk]);
						}
					$reDiy[app] = $this->Members->credits_rules_diy_app($k, $v);
					}
				}
			}
		}
		return $reDiy;
	}

}

?>