<?php
require_once('global.php');
define('MOD_UNIQUEID', 'consumption_create');
define('SCRIPT_NAME', 'consumption_create');
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
class consumption_create extends adminBase
{
	private $curd = null;
	public function __construct()
	{
		parent::__construct();
		$this->curd = new curd('member_order');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show(){
		
		$data = array(
		'template_id'=>$this->input['template_id'],
		'member_id'=>$this->input['member_id'],
		'member_name'=>$this->input['member_name'],
		'ip'=>hg_getip(),
		'create_time'=>TIMENOW,
		'enterprise'=>$this->input['enterprise'],
		'cost'=>0,
		);
		$erro_text = array(
		'template_id'			=>'未知的模板',
		);
		$this->curd->set_table('templates');
		$template_info = $this->curd->detail($data['template_id']);
		if(!$template_info)
		{
			$this->errorOutput("模板不存在");
		}
		foreach(array('video_preview', 'index_pic', 'material') as $key)
		{
			$template_info[$key] = unserialize($template_info['material']);
		}
		//$template_info['material'] = unserialize($template_info['material']);
		foreach($data as $key=>$val)
		{
			if(!$val && $erro_text[$key])
			{
				$this->errorOutput($erro_text[$key]);
			}
		}
		$this->curd->set_table('member_order');
		if($id = $this->curd->create($data))
		{
			//模板下载计数
			$sql = 'UPDATE ' . DB_PREFIX . 'templates SET record=record+1 WHERE id='.$template_info['id'];
			$this->db->query($sql);
			$data['id'] = $id;
			$this->addItem($template_info);
			$this->output();
		}
		$this->errorOutput('下载失败');
	}
}
include ROOT_PATH . 'excute.php';
?>