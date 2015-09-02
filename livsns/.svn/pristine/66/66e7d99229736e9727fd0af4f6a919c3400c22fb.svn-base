<?php
define('MOD_UNIQUEID','member_purview');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_purview.class.php';
require_once CUR_CONF_PATH . 'core/membersql.core.php';
class memberpurviewUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->purview = new purview();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		
	}
	
	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);

		$info=$this->purview->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['pname']!=$info['pname'])
		{
			//验证名称是否重复
			$checkResult = $this->membersql->verify(array('pname' => $data['pname']));
			if ($checkResult) $this->errorOutput('名称重复');
		}
		if ($data)
		{
			$result = $this->membersql->update('purview', $data, array('id' => intval($id)));
		}
				
		$this->addItem($result);
		$this->output();
	}


	/**
	 * 删除权限
	 */
	public function delete()
	{

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
		$pname = isset($this->input['pname']) ? trim(urldecode($this->input['pname'])) : '';
		if (empty($pname)) $this->errorOutput('权限名不能为空');
		$allow=isset($this->input['allow']) ? intval($this->input['allow']) : 0;		
		$data = array(
			'pname'   => $pname,
			'allow'	  => $allow
		);
		return $data;
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new memberpurviewUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>