<?php
require_once('global.php');
require_once(CUR_CONF_PATH.'lib/fastInputSort.class.php');
define('MOD_UNIQUEID','reporter_fast_input_sort');
class  contribute_fastInput_sort_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sort = new fastInputSort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		
		/**************权限控制开始**************/
		$this->verify_content_prms();
		/**************权限控制结束**************/
		if (!$this->input['name'])
		{
			$this->errorOutput('请填写分类名');
		}
		//参数接受
		$data = array(
			'name'=>addslashes(trim(urldecode($this->input['name']))),
			'brief'=>addslashes(trim(urldecode($this->input['brief']))),
			'create_time'=>TIMENOW,
			'org_id'=>$this->user['org_id'],
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'ip'=>$this->user['ip'],
			'update_time'=>TIMENOW
		);
		$data['user_id'] = $data['user_id'] ? $data['user_id'] : 0;
		$data['user_name'] = $data['user_name'] ? $data['user_name'] : '匿名用户';
		$ret = $this->sort->check($data[name]);
		$ret = $this->sort->create($data);
		if ($ret)
		{
			$this->addItem('sucess');
		}
		
		$this->output();	
	}
	public function delete()
	{
		/**************权限控制开始**************/
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort WHERE id IN ('.$this->input['id'].')';
		$query = $this->db->query($sql);
		$conInfor = array();
		while ($row = $this->db->fetch_array($query))
		{
			$conInfor[] = $row;
		}
		//能否修改他人数据
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		/**************权限控制结束**************/
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = trim(urldecode($this->input['id']));
		$r = $this->sort->checkcon($id);
		if (!$r)
		{
			$this->errorOutput('此分类下有相关内容，请先删除相关内容');
		}
		$ret = $this->sort->delete($id);
		$this->addItem($id);
		$this->output();	
	}
	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//参数接收
		$data = array(
			'brief'=>addslashes(trim(urldecode($this->input['brief']))),
			'update_time'=>TIMENOW,
		);
		
		/**************权限控制开始**************/
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$arr = array(
					'id'=>$id,
					'user_id'=>$ret['user_id'],
					'org_id'=>$ret['org_id'],
				);
		$this->verify_content_prms($arr);
		/**************权限控制结束**************/
		$res = $this->sort->update($data, $id);
		$this->addItem($res);
		$this->output();
		
	}
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	public function audit()
	{
		
	}
	
	public function sort()
	{
		$this->verify_content_prms();
		$ret = $this->drag_order('fastInput_sort', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		
	}
}

$out = new contribute_fastInput_sort_update();
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