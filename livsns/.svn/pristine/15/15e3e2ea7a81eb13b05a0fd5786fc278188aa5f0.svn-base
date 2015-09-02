<?php
require_once('global.php');
define('MOD_UNIQUEID', 'news');
define('SCRIPT_NAME', 'config_update');
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
class config_update extends adminBase
{
	private $curd = null;
	public function __construct()
	{
		parent::__construct();
		$this->curd = new curd('template_config');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show(){}
	public function create()
	{
		$input = $this->initdata();
		if($id = $this->curd->create($input))
		{
			$input['id'] = $id;
			$this->addItem($input);
			$this->output();
		}
		$this->errorOutput('创建配置失败');
	}
	public function update()
	{
		$input = $this->initdata();
		if(!$input['id'])
		{
			$this->errorOutput("纪录不存在");
		}
		
		if($this->curd->update($input))
		{
			$this->addItem($input);
			$this->output();
		}
		$this->errorOutput('更新配置失败');
	}
	public function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput("纪录不存在");
		}
		if(!$this->curd->delete(0, ' and id in('.$id.')'))
		{
			$this->errorOutput("删除失败");
		}
		$this->addItem('success');
		$this->output();
	}
	protected  function initdata()
	{
		$data = array(
		'type'	=>$this->input['type'],
		'key'	=>$this->input['key'],
		'value'	=>$this->input['value'],
		'create_time'=>TIMENOW,
		'user_id'=>$this->user['user_id'],
		'user_name'=>$this->user['user_name'],
		'status'=>intval($this->input['status'])
		);
		if(intval($this->input['id']))
		{
			$data['id'] = intval($this->input['id']);
			unset($data['create_time']);
			
		}
		$erro_text = array(
		'type'	=>'配置类型错误',
		'key'	=>'配置标识错误',
		'value'	=>'值不能为空',
		);
		foreach($data as $key=>$val)
		{
			if(!$val && $erro_text[$key])
			{
				$this->errorOutput($erro_text[$key]);
			}
		}
		return $data;
	}
}

include ROOT_PATH . 'excute.php';
?>