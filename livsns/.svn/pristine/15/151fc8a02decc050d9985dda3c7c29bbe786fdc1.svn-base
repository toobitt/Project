<?php
define('MOD_UNIQUEID','member_purview');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_purview.class.php';
class memberpurviewApi extends outerReadBase
{
	private $Members;
	public function __construct()
	{
		parent::__construct();
		$this->purview = new purview();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出权限信息
	 *
	 */
	public function show()
	{
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->purview->show($condition,$offset,$count);

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
	 * 取出单个权限信息
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput(NO_PURVIEW_ID);
		}
		$info = $this->purview->detail($id);
		$this->addItem($info);
		$this->output();
	}

	/**
	 * 获取分组操作权限
	 */
	public function showpurview()
	{
		$id = intval($this->input['gid']);
		if(empty($id))
		{
			$this->errorOutput(NO_GID);
		}
		$info = $this->Members->showpurview($id);
		$info=$info[$id];
		if($info&&is_array($info))
		{
			foreach ($info as $key=>$val)
			{
				$this->addItem_withkey($key, $val);
			}
		}
		else $this->addItem($info);
		$this->output();
	}
	/**
	 *
	 * 判断操作权限
	 * member_id
	 * operation 权限key
	 */
	public function purview()
	{
		$member_id=$this->user['user_id'];
		if(!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$gid=$this->Members->uid_to_gid($member_id);
		$operation=$this->input['operation']?trim($this->input['operation']):'';
		$purview=$this->Members->purview($gid, $operation);
		switch ($purview)
		{
			case 0:
				$this->errorOutput(NO_GID);
				break;
			case -1:
				$this->errorOutput(NO_PURVIEW_KEY);
				break;
			case -2:
				$this->errorOutput(NO_PURVIEW);
				break;
			case -3:
				$this->errorOutput(PURVIEW_ERROR);
				break;
		}
		if(is_array($purview)&&$purview)
		{
			foreach ($purview as $key=>$data)
			{
				$this->addItem_withkey($key, $data);
			}
		}
		else $this->addItem($purview);
		$this->output();
	}
	private function get_condition()
	{
		$condition = "";

		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$binary = '';//不区分大小些
			if(defined('IS_BINARY') && !IS_BINARY)//区分大小些
			{
				$binary = 'binary ';
			}
			$condition .= ' AND ' . $binary . ' pname like \'%'.trim($this->input['k']).'%\'';
		}

		if ($this->input['id'])
		{
			$condition .= " AND id IN (" . trim($this->input['id']) . ")";
		}

		return $condition;
	}
	public function count()
	{

	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new memberpurviewApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>