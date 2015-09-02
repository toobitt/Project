<?php
define('MOD_UNIQUEID','member_group');//模块标识
require('./global.php');
class membergroupUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->group = new group();
		$this->Members=new members();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		if(is_array($this->input['purview'])&&!empty($this->input['purview']))
		{
			$purview=array_filter($this->input['purview'],"clean_array_null");
		}
		else $purview=$this->input['purview'];

		//验证名称是否重复
		$checkResult = $this->group->verify(array('name' => $data['name']));
		if ($checkResult) $this->errorOutput('会员组名称重复');
		if(empty($data['isupdate']))
		{
			//分组积分规划
			$sql='SELECT id,creditshigher FROM '.DB_PREFIX.'group WHERE isupdate=0';
			$query=$this->db->query($sql);
			while ($res = $this->db->fetch_array($query))
			{
				if($data['creditshigher']==$res['creditshigher'])
				{
					$this->errorOutput('上限不能等于下限');
				}
				$creditold[$res['creditshigher']]=$res['id'];
			}
			$maxid=max($creditold);
			$creditold[$data['creditshigher']]=$maxid+1;
			if(empty($creditold[0]) || min(array_flip($creditold)) >= 0) {
				$this->errorOutput('缺少积分为0或者为负数的会员组');
			}
			ksort($creditold);
			$creditoldkey=array_keys($creditold);
			$creditnew=array();
			for($i = 0; $i < count($creditoldkey); $i++) {
				$creditnew[$creditold[$creditoldkey[$i]]] = array(
					'creditshigher' => isset($creditoldkey[$i - 1]) ? $creditoldkey[$i] : -999999999,
					'creditslower' => isset($creditoldkey[$i + 1]) ? $creditoldkey[$i + 1] : 999999999
				);
			}
			foreach ($creditnew as $id => $credits)
			{
				if($id<=$maxid)
				{
					$this->group->update('group', $credits, array('id' => intval($id)));
				}
				else
				{
					$data['creditshigher']=$credits['creditshigher'];
					$data['creditslower']=$credits['creditslower'];
				}
			}
		}
		if($data)
		{
			$result = $this->group->create('group', $data);
		}
		if($purview &&is_array($purview)&&$result)
		{
			foreach ($purview as $key=>$val)
			{
				$purview_bind = array('gid'     => intval($result['id']),
					             'pid' => intval($val));				
				$this->group->create('purview_bind',$purview_bind);
			}
		}
		elseif($purview&&$result)
		{
			$purview_bind = array('gid'     => intval($result['id']),
					         'pid' => intval($purview));
			 	 $this->group->create('purview_bind',$purview_bind);
		}
		if($result)
		{
			if($this->input['showcredit'])
			{
				$credits_rules_diy=array();
				$credits_rules_diy=$this->input['credits_rules_diy'];
				$this->Members->credits_rules_diy_group($result['id'],$credits_rules_diy);	
			}
		}

		$this->addItem($result);
		$this->output();
	}
	
	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);

		if(is_array($this->input['purview'])&&!empty($this->input['purview']))
		{
			$purview=array_filter($this->input['purview'],"clean_array_null");
		}
		else $purview=$this->input['purview'];

		$info=$this->group->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['name']!=$info['name'])
		{
			//验证名称是否重复
			$checkResult = $this->group->verify(array('name' => $data['name']));
			if ($checkResult) $this->errorOutput('名称重复');
		}

		//分组积分规划
		$sql='SELECT id,creditshigher FROM '.DB_PREFIX.'group WHERE isupdate=0 AND id !='.$info['id'];
		$query=$this->db->query($sql);
		while ($res = $this->db->fetch_array($query))
		{
				if($data['creditshigher']==$res['creditshigher'])
				{
					$this->errorOutput('上限不能等于下限');
				}
			$creditold[$res['creditshigher']]=$res['id'];
		}
		if(empty($data['isupdate']))
		{
			$creditold[$data['creditshigher']]=$info['id'];
		}
		if($data['creditshigher']==$info['creditslower'])
		{
			$this->errorOutput('上限不能等于下限');
		}
		if(empty($creditold[0]) || min(array_flip($creditold)) >= 0) {
			$this->errorOutput('缺少积分为0或者为负数的会员组');
		}
		ksort($creditold);
		$creditoldkey=array_keys($creditold);
		$creditnew=array();
		for($i = 0; $i < count($creditoldkey); $i++) {
			$creditnew[$creditold[$creditoldkey[$i]]] = array(
					'creditshigher' => isset($creditoldkey[$i - 1]) ? $creditoldkey[$i] : -999999999,
					'creditslower' => isset($creditoldkey[$i + 1]) ? $creditoldkey[$i + 1] : 999999999
			);
		}
		if(empty($info['isupdate'])&&!empty($data['isupdate']))
		{
			$update[$info['id']]['creditshigher'] = 0;
			$update[$info['id']]['creditslower'] = 	0;
			$update[$info['id']]['isupdate']=0;
		}
		elseif(!empty($info['isupdate'])&&!empty($data['isupdate']))
		{
				$update[$info['id']]	= $data;
		}
		foreach ($creditnew as $id => $credits)
		{
			
			if($id!=$info['id'])
			{
				$update[$id]=$credits;
			}
			else
			{	$update[$id]=$data;
				$update[$id]['creditshigher']	= $credits['creditshigher'];
				$update[$id]['creditslower']	= $credits['creditslower'];
			}
		}
		$purviewinfo=$info['pid'];
		if($purviewinfo&&$purview)
		{
			$create_purview=array_diff($purview, $purviewinfo);
			$delete_purview=array_diff($purviewinfo,$purview);
		}
		elseif (empty($purviewinfo)&&$purview)
		{
			$create_purview=$purview;
		}
		elseif($purviewinfo&&!$purview)
		{
			$delete_purview=$purviewinfo;
		}
		if($delete_purview)
		{
			$this->group->delete('purview_bind', array('pid' => implode(',', $delete_purview),'gid'=>intval($info['id']))); //删除绑定权限
		}
		if($create_purview && is_array($create_purview))
		{
			foreach ($create_purview as $key=>$val)
			{
				$purview_bind = array('gid'     => intval($info['id']),
					             'pid' => intval($val));				
				$this->group->create('purview_bind',$purview_bind);
			}
		}
		elseif($create_purview)
		{
			$purview_bind = array('gid'     => intval($info['id']),
					         'pid' => intval($create_purview));
			$this->group->create('purview_bind',$purview_bind);
		}
		if ($update)
		{
			foreach ($update as $id => $newgroup)
			{
				$result[] = $this->group->update('group', $newgroup, array('id' => intval($id)));
			}
		}
		if($result)
		{
			if($this->input['showcredit'])
			{
				$credits_rules_diy=$this->input['credits_rules_diy'];
			}
			else 
			{
				$credits_rules_diy=array();
			}
			$this->Members->credits_rules_diy_group($info['id'],$credits_rules_diy);
		}
		$this->addItem($result);
		$this->output();

	}


	/**
	 * 删除分组
	 */
	public function delete()
	{
		$id = trim($this->input['id']);
		$ids = str_replace('，' , ',' , $id);
		$id_array = explode(',' , $ids);
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		if(empty($id_array)) return false;
		$delete_id = implode(',' , $id_array);
		//验证 是否是系统组,系统默认组禁止删除。
		$condition = " AND id in (".$delete_id.")";
		$sql = "SELECT id,name,issystem,isupdate,creditshigher,rules FROM " . DB_PREFIX . "group ";
		$sql.= " WHERE 1 " . $condition;
		$group  = $this->db->fetch_all($sql);
		if (empty($group))
		{
			$this->errorOutput('分组不存在');
		}
		else {
			$delete_id=array();
			$isupdate=1;
			$rules = array();
			foreach ($group as $val)
			{
				if($val['issystem'])
				{
					$this->errorOutput("系统“默认”用户组禁止删除");
				}
				else {
					$delete_id[]=$val['id'];
				}
				if(empty($val['isupdate']))
				{
					if($val['creditshigher']<=0)
					{
						$this->errorOutput('禁止删除积分少于0的用户组');
					}
					$isupdate=0;
				}
				if($val['rules'])
				{
					$rules[$val['id']] = maybe_unserialize($val['rules']);
				}
			}
			if($delete_id)
			{
				$delrules = array();
				if(is_array($rules))
				foreach ($rules as $k => $v)
				{
					if(is_array($v))
					foreach ($v as $kk => $vv)
					{
						$delrules[$kk][] = $k;
					}
				}
				include CUR_CONF_PATH . 'lib/member_credit_rules.class.php';
				$CreditRules = new creditrules();
				if(is_array($delrules))
				foreach ($delrules as $k => $v){
					$CreditRules->creditrules_diy_unset($k, $v, array());
				}
				$ret = $this->group->delete('group',array('id'=>implode(',',$delete_id)));
			}
		}
		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}
		else {
			if(empty($isupdate))
			{
			//分组积分规划
			$sql='SELECT id,creditshigher FROM '.DB_PREFIX.'group WHERE isupdate=0';
			$query=$this->db->query($sql);
			while ($res = $this->db->fetch_array($query))
			{
				$creditold[$res['creditshigher']]=$res['id'];
			}
			if(empty($creditold[0]) || min(array_flip($creditold)) >= 0) {
				$this->errorOutput('缺少积分为0或者为负数的会员组,请重新添加起始积分为负数和0的组');
			}
			ksort($creditold);
			$creditoldkey=array_keys($creditold);
			$creditnew=array();
			for($i = 0; $i < count($creditoldkey); $i++) {
				$creditnew[$creditold[$creditoldkey[$i]]] = array(
					'creditshigher' => isset($creditoldkey[$i - 1]) ? $creditoldkey[$i] : -999999999,
					'creditslower' => isset($creditoldkey[$i + 1]) ? $creditoldkey[$i + 1] : 999999999
				);
			}
			foreach ($creditnew as $idc => $credits)
			{
				$this->group->update('group', $credits, array('id' => intval($idc)));
			}
		}
			$this->group->delete('purview_bind',array('gid'=>implode(',',$delete_id)));//删除分组同时删除权限绑定表
		}
		$this->update_old_user_group($delete_id);
		$this->addItem($id);
		$this->output();
	}
	
	/**
	 * 
	 * 将删除掉的用户组从用户信息中移除并给用户重新赋予新组 ...
	 * @param unknown_type $id
	 */
	private function update_old_user_group($id)
	{
		$member_id = array();
		$member_id  = $this->Members->gid_to_uid($id);
		if($member_id&&is_array($member_id))
		{
			foreach ($member_id as $v)
			{
				$this->Members->updategroup($v,0);
			}
			return true;
		}
		return false;
	}

	public function audit()
	{
		//
	}
	
	/**
	 * 用户组是否启用
	 */
	public function enable()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->group->enable($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function sort()
	{
		$this->addLogs('更改会员分组排序', '', '', '更改会员分组排序');
		$ret = $this->drag_order('group', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		//
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$name = isset($this->input['name']) ? trim(urldecode($this->input['name'])) : '';
		$description = isset($this->input['description']) ? trim(urldecode($this->input['description'])) : '';
		$isupdate = isset($this->input['isupdate']) ? intval($this->input['isupdate']) : 1;
		$creditshigher=isset($this->input['creditshigher']) ? intval($this->input['creditshigher']) : '0';
		$starnum=isset($this->input['starnum']) ? intval($this->input['starnum']) : 0;
		$usernamecolor = isset($this->input['usernamecolor']) ? trim(urldecode($this->input['usernamecolor'])) : '#000000';
		$enable = isset($this->input['enable']) ? intval($this->input['enable']) : '1';  //前台是否启用
		
		if (empty($name)) $this->errorOutput('用户组名称不能为空');
		if($this->input['showcredit'])
		{
			if(empty($this->input['credits_rules_diy']))
			{
				 $this->errorOutput('如无需自定义规则,请<积分规则>选择否!');
			}
		}
		if(!empty($usernamecolor))//颜色合法值判断
		{
			 $_march = '/[^a-zA-Z]/is';
			if(!preg_match($_march, $usernamecolor))
			{
				 $usernamecolor=color($usernamecolor);
			}
			$colorarr=str_split($usernamecolor);
			if(count($colorarr)>7)
			{
				 $this->errorOutput('十六进制色值不合法,大于7位');
			}
			if($colorarr[0]!='#')
			{
				 $this->errorOutput('十六进制色值不合法,开头应该是以#');
			}
			unset($colorarr[0]);
			foreach ($colorarr as $val)
			{
				if(($val>='A'&&$val<='F') || ($val>='0'&&$val<='9')||($val>='a'&&$val<='f'))
				{
					continue;
				}
				else  $this->errorOutput('十六进制色值不合法,参考:#FF0000');
			}
		}
		$data = array(
			'name'    => $name,
			'description' => $description,
			'isupdate'=>$isupdate,
			'starnum'=>$starnum,
			'usernamecolor' => $usernamecolor,
			'enable' => $enable,      	
		);
		if(empty($isupdate))//如果可升级则添加升级上下限
		{
			$data['creditshigher'] = $creditshigher;
		}
		if($_FILES['icon'])//如果有图片,则添加图片数据
		{
			$img['Filedata']=$_FILES['icon'];
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装！');
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($img);
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);
			$data['icon']=maybe_serialize($img_data);
		}
		elseif ($this->input['icondel'])//如果为真.则删除用户组图标
		{
			$data['icon']='';
		}
		return $data;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new membergroupUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>