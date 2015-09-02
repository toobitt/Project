<?php
define('MOD_UNIQUEID','member_grade');//模块标识
require('./global.php');
class member_gradeUpdate extends adminUpdateBase
{
	private $grade;
	private $Members;
	private $membersql;
	public function __construct()
	{
		parent::__construct();
		$this->grade = new grade();
		$this->Members = new members();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据

		//验证名称是否重复
		$checkResult = $this->membersql->verify('grade',array('name' => $data['name']));
		if ($checkResult) $this->errorOutput(NAME_EXISTS);
		//等级经验规划
		$sql='SELECT id,creditshigher FROM '.DB_PREFIX.'grade';
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
		if(empty($creditold[0])) {
			$this->errorOutput('缺少经验为0的等级');
		}
		ksort($creditold);
		$creditoldkey=array_keys($creditold);
		$creditnew=array();
		for($i = 0; $i < count($creditoldkey); $i++) {
			$creditnew[$creditold[$creditoldkey[$i]]] = array(
					'creditshigher' => isset($creditoldkey[$i - 1]) ? $creditoldkey[$i] : 0,
					'creditslower' => isset($creditoldkey[$i + 1]) ? $creditoldkey[$i + 1] : 999999999
			);
		}
		$i = 0;
		foreach ($creditnew as $id => $credits)
		{
			$i++;
			if($id<=$maxid)
			{
				$credits['digital'] = $i;
				$this->membersql->update('grade', array('digital'=> 0 ), array('digital' => $i));//先把将要冲突的数据置0
				$digital = $this->grade->detail($id,'digital');//读取将要修改的数据
				$this->membersql->update('grade', $credits, array('id' => intval($id)));//修改为新的数据
				$this->membersql->update('grade', array('digital' => $digital['digital']), array('digital' => 0));//将修改掉的数据赋给0数据，因为不会重复
			}
			else
			{
				$data['digital'] = $i;
				$data['creditshigher']=$credits['creditshigher'];
				$data['creditslower']=$credits['creditslower'];
			}
		}
		if($data)
		{
			$result = $this->membersql->create('grade',$data,false);
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

		$info = $this->grade->detail($id);
		if (!$info) $this->errorOutput(NO_DATA);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['name']!=$info['name'])
		{
			//验证名称是否重复
			$checkResult = $this->membersql->verify('grade',array('name' => $data['name']));
			if ($checkResult) $this->errorOutput(NAME_EXISTS);
		}

		//分组积分规划
		$sql='SELECT id,creditshigher FROM '.DB_PREFIX.'grade WHERE id !='.$info['id'];
		$query=$this->db->query($sql);
		while ($res = $this->db->fetch_array($query))
		{
			if($data['creditshigher']==$res['creditshigher'])
			{
				$this->errorOutput('上限不能等于下限');
			}
			$creditold[$res['creditshigher']]=$res['id'];
		}
		$creditold[$data['creditshigher']]=$info['id'];
			
		if($data['creditshigher']==$info['creditslower'])
		{
			$this->errorOutput('上限不能等于下限');
		}
		if(empty($creditold[0])) {
			$this->errorOutput('缺少经验为0的等级');
		}
		ksort($creditold);
		$creditoldkey=array_keys($creditold);
		$creditnew=array();
		for($i = 0; $i < count($creditoldkey); $i++) {
			$creditnew[$creditold[$creditoldkey[$i]]] = array(
					'creditshigher' => isset($creditoldkey[$i - 1]) ? $creditoldkey[$i] : 0,
					'creditslower' => isset($creditoldkey[$i + 1]) ? $creditoldkey[$i + 1] : 999999999
			);
		}
		$update[$info['id']]	= $data;
		$i = 0;
		foreach ($creditnew as $id => $credits)
		{
			$i++;
			if($id!=$info['id'])
			{
				$credits['digital'] = $i;
				$update[$id]=$credits;
			}
			else
			{	
			$update[$id]=$data;
			$update[$id]['digital'] = $i;
			$update[$id]['creditshigher']	= $credits['creditshigher'];
			$update[$id]['creditslower']	= $credits['creditslower'];
			}
		}

		if ($update)
		{
			foreach ($update as $id => $newgroup)
			{
				$result[] = $this->membersql->update('grade', $newgroup, array('id' => intval($id)));
			}
		}
		$this->addItem($result);
		$this->output();

	}


	/**
	 * 删除
	 */
	public function delete()
	{

		$id = trim($this->input['id']);
		$ids = str_replace('，' , ',' , $id);
		$id_array = explode(',' , $ids);
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		if(empty($id_array))
		{
			$this->errorOutput('请传等级id');	
		}
		$delete_id = implode(',' , $id_array);
		//验证 是否是系统组,系统默认组禁止删除。
		$condition = " AND id in (".$delete_id.")";
		$sql = "SELECT id,name,issystem FROM " . DB_PREFIX . "grade ";
		$sql.= " WHERE 1 " . $condition;
		$grade  = $this->db->fetch_all($sql);
		if (empty($grade))
		{
			$this->errorOutput('等级不存在');
		}
		else {
			$delete_id=array();
			foreach ($grade as $val)
			{
				if($val['issystem'])
				{
					$this->errorOutput("您删除的等级，含有系统默认数据删除");
				}
				else {
					$delete_id[]=$val['id'];
				}
			}
			if($delete_id)
			{
				$ret = $this->membersql->delete('grade',array('id'=>$delete_id));
			}
		}
		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}
		else {
				//等级重新规划
				$sql='SELECT id,creditshigher FROM '.DB_PREFIX.'grade';
				$query=$this->db->query($sql);
				while ($res = $this->db->fetch_array($query))
				{
					$creditold[$res['creditshigher']]=$res['id'];
				}
				if(empty($creditold[0])) {
					$this->errorOutput('缺少积分为0');
				}
				ksort($creditold);
				$creditoldkey=array_keys($creditold);
				$creditnew=array();
				for($i = 0; $i < count($creditoldkey); $i++) {
					$creditnew[$creditold[$creditoldkey[$i]]] = array(
					'creditshigher' => isset($creditoldkey[$i - 1]) ? $creditoldkey[$i] : 0,
					'creditslower' => isset($creditoldkey[$i + 1]) ? $creditoldkey[$i + 1] : 999999999
					);
				}
				$i = 0;
				foreach ($creditnew as $idc => $credits)
				{
					$credits['digital'] = ++$i;
					$this->membersql->update('grade', $credits, array('id' => intval($idc)));
				}

		}
		$this->update_old_user_gra($delete_id);
		$this->addItem($id);
		$this->output();
	}
	
	/**
	 * 
	 * 将删除掉的用户等级从用户信息中移除并给用户重新赋予新等级 ...
	 * @param unknown_type $id
	 */
	private function update_old_user_gra($id)
	{
		$member_id = array();
		$member_id  = $this->Members->gradeid_to_uid($id);
		if($member_id&&is_array($member_id))
		{
			foreach ($member_id as $v)
			{
				$this->Members->updategrade($v);
			}
			return true;
		}
		return false;
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
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
		$creditshigher=isset($this->input['creditshigher']) ? intval($this->input['creditshigher']) : '0';

		if (empty($name)) $this->errorOutput('用户等级名称不能为空');

		$data = array(
			'name'    => $name,
			'description' => $description,
		);
		if($creditshigher<0)
		{
			$this->errorOutput('经验值不能小于0');
		}
		$data['creditshigher'] = $creditshigher;
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
		$this->errorOutput(NO_ACTION);
	}

}

$out = new member_gradeUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>