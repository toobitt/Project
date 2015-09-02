<?php
define('MOD_UNIQUEID','member_group');//模块标识
require('./global.php');
class membergroup extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->group = new group();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->group->show($condition,$offset,$count);

		if (!empty($info)&&is_array($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			return false;
		}
		$info = $this->group->detail($id);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 
	 * 新节点,不含父类 ...
	 */
	public function show_node()
	{
			$sql='SELECT id,name FROM '.DB_PREFIX.'group WHERE 1';
			$query=$this->db->query($sql);
			$node_arr=array();
			while ($row=$this->db->fetch_array($query))
			{
				$node_arr[$row['id']]=array('id'=>$row['id'],'name'=>$row['name'],'fid'=>'0','childs'=>$row['id'],'parents'=>$row['id'],'depath'=>'1','is_last'=>'1');
			}
			foreach ($node_arr as $node)
			$this->addItem($node);
			$this->output();
	}
	/**节点改造暂时注释
	public function show_node()
	{
		$updatetype=$this->settings['updatetype'];
		while(list($k,$v)=each($updatetype))
		{
			$fid_info[$k+1]=$v;
		}
		$fids=array_keys($fid_info);
		$maxfid=max($fids);
		$fid=isset($this->input['fid'])&&$this->input['fid']<=$maxfid?intval($this->input['fid']):0;
		if($fid<=$maxfid&&$fid>=0)
		{
			$fid=$fid-1;
		}
		elseif($fid>$maxfid)
		{
			$fid=$fid-$maxfid;
		}
		if($fid>=0)
		{
			$sql='SELECT id,name,isupdate as fid FROM '.DB_PREFIX.'group WHERE isupdate ='.$fid;
			$query=$this->db->query($sql);
			while ($row=$this->db->fetch_array($query))
			{
				$row['id']=$row['id']+$maxfid;
				$row['fid']+=1;
				$node_arr[$row['fid']][$row['id']]=array('id'=>$row['id'],'name'=>$row['name']);
			}
		}
		else {
			$sql='SELECT id,name,isupdate as fid FROM '.DB_PREFIX.'group WHERE 1';
			$query=$this->db->query($sql);
			while ($row=$this->db->fetch_array($query))
			{
				$row['id']=$row['id']+$maxfid;
				$row['fid']+=1;
				$node_arr[$row['fid']][$row['id']]=array('id'=>$row['id'],'name'=>$row['name']);
			}
		}
		$node=array();
		if($fid<0)
		{
			if(is_array($fid_info)&&$fid_info)
			{
				foreach ($fid_info as $id => $name)
				{
					$is_last=!empty($node_arr[$id])?0:1;
					$childs=$id;
					if(is_array($node_arr[$id])&&$node_arr[$id])
					{
						$childs .=',';
						$childs .=implode(',', array_keys($node_arr[$id]));
					}
					$depath=1;//深度1
					$fid=0;//无父类为0
					$node=array('id'=>"$id",'name'=>$name,'fid'=>'0','childs'=>"$childs",'parents'=>"$id",'depath'=>"$depath",'is_last'=>"$is_last");
					$this->addItem($node);
				}
			}
		}
		else
		{
			if(is_array($node_arr[$fid+1])&&$node_arr[$fid+1])
			{
				foreach ($node_arr[$fid+1] as $childsarr)//因为只考虑到会员组只有2级,此次模拟暂不支持三级以及以上分类.
				{
					$is_last=1;//因为无三级节点,所以全是光棍,全为1.
					$childs=$childsarr['id'];
					$parents=$childsarr['id'].','.($fid+1);
					$depath=2;
					$node=array('id'=>(string)$childsarr['id'],'name'=>$childsarr['name'],'fid'=>(string)($fid+1),'childs'=>"$childs",'parents'=>"$parents",'depath'=>"$depath",'is_last'=>"$is_last");
					$this->addItem($node);
				}
			}
		}
		$this->output();
	}
	*/
	/**
	 * 
	 * 获取非总积分升级用户组
	 */
	public function showgroup()
	{
		$sql = "SELECT id,name FROM " . DB_PREFIX . "group ";
		$sql.= " WHERE isupdate!=0";
		$info = $this->db->fetch_all($sql);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	/**
	 * 
	 * 获取会员组自定义积分规则
	 */
	public function showcredit_rules_diy()
	{
		$id=$this->input['id'];
		if(!$id)
		{
			return false;
		}
		$sql  = 'SELECT rules FROM '.DB_PREFIX.'group WHERE rules <>\'\' AND id ='.$id;
		$query=$this->db->query($sql);
		while ($row=$this->db->fetch_array($query))
		{
			$ret=maybe_unserialize($row['rules']);
			if($ret&&is_array($ret))
			{
				foreach ($ret as $key=>$val)
				{
					$this->addItem_withkey($key, $val);
				}
			}
			else $this->addItem($ret);
		}
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "group WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		if (isset($this->input['isupdate']) && $this->input['isupdate'] != -1)
		{
			$condition .= " AND isupdate = " . intval($this->input['isupdate']);
		}

		return $condition;
	}

}

$out = new membergroup();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>