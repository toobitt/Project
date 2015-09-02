<?php 
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('./lib/opinion.class.php');
class opinionApi extends outerReadBase
{	
	function __construct()
	{
		parent::__construct();
		$this->opinion = new opinion();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		
	}
	public function create()
	{
		if (!isset($this->input['rid']))
		{
			$this->errorOutput(NOID);
		}
		if (!isset($this->input['content']))
		{
			$this->errorOutput('content is empty');
		}
		//参数接收
		$data = array(
			'rid'=>intval(urldecode($this->input['rid'])),
			'content'=>addslashes(trim(urldecode($this->input['content']))),
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'ip'=>$this->user['ip'],
			'create_time'=>TIMENOW,
		);
		$data['user_id'] = $data['user_id'] ? $data['user_id'] : 0;
		$data['user_name'] = $data['user_name'] ? $data['user_name'] :'匿名用户';
		//内容表数据插入	
		$cid = $this->opinion->add_content($data);
		//插入主表数据
		$data = array(
			'app_mark'=>urldecode($this->input['app_mark']),
			'module_mark'=>urldecode($this->input['module_mark']),
			'rid'=>intval(urldecode($this->input['rid'])),
			'cid'=>$cid
		);
		$id = $this->opinion->add_opinion($data);
		$this->addItem($id);
		$this->output(); 
		
	}
	public function detail()
	{
		if (!$this->input['rid'])
		{
			$this->errorOutput(NOID);
		}
		$flag = intval(urldecode($this->input['flag']));
		$data = array(
			'app_mark'=>urldecode($this->input['app_mark']),
			'module_mark'=>urldecode($this->input['module_mark']),
			'rid'=>intval(urldecode($this->input['rid'])),			
		);
		$ret = $this->opinion->detail($data, $flag);
		$this->addItem($ret);
		$this->output();
	}
	public function update()
	{
		if (!$this->input['rid'])
		{
			$this->errorOutput(NOID);
		}
		if (!$this->input['content'])
		{
			$this->errorOutput('content is empty');
		}
		//参数接收
		$data = array(
			'app_mark'=>urldecode($this->input['app_mark']),
			'module_mark'=>urldecode($this->input['module_mark']),
			'rid'=>intval(urldecode($this->input['rid'])),

		);
		//获取内容id
		$cid = $this->opinion->get_content_id($data);
		$data = array(
			'rid'=>intval(urldecode($this->input['rid'])),
			'content'=>addslashes(trim(urldecode($this->input['content']))),
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'ip'=>$this->user['ip'],
			'create_time'=>TIMENOW,
		);	
		$data['user_id'] = $data['user_id'] ? $data['user_id'] : 0;
		$data['user_name'] = $data['user_name'] ? $data['user_name'] :'匿名用户';
		$this->opinion->update($data, $cid);
		$this->addItem($data);
		$this->output();
		
		
	}
	public function delete()
	{
		if (!$this->input['rid'])
		{
			$this->errorOutput(NOID);
		}
		$rid = urldecode($this->input['rid']);
		//参数接收
		$data = array(			
			'app_mark'=>urldecode($this->input['app_mark']),
			'module_mark'=>urldecode($this->input['module_mark'])
		);
		//删除内容表
		$this->opinion->deleteContent($rid);
		//删除主表
		$this->opinion->deleteOpinion($data,$rid);
		$this->addItem('sucess');
		$this->output();
	}
	public function unkown()
	{
		$this->errorOutput(NOMETHOD);
	}
	public function count()
	{
		
	}
}
$out = new opinionApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unkown';
}
$out->$action();
?>
