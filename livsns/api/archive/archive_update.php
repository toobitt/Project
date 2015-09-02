<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/archive.class.php';
define('MOD_UNIQUEID','archive');//模块标识
class archiveUpdateApi extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->archive = new archive();			
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'app_mark'		=> $this->input['app_mark'],
			'module_mark'	=> $this->input['module_mark'],
			'time'			=> TIMENOW,
			'content'		=> json_decode($this->input['content'],true),
			'name'			=> $this->input['name'],
			'archive_user'	=> json_decode($this->input['archive_user'],true),
		);
		if (!$data['app_mark'] || !$data['module_mark'] || !$data['content'] || !$data['name'])
		{
			$res = array(
				'error'=>'missing_parameter',
				'error_code'=>'1',
				'error_description'=>'缺少参数',
				
			);
		}
		else 
		{
			if (OPEN_ARCHIVE)
			{
				$res = $this->archive->create($data);
				if (!$res)
				{
					$res = array(
						'error'=>'archive_failed',
						'error_code'=>'3',
						'error_description'=>'归档失败',
					);
				}
				else 
				{
					$res = array(
						'error'=>'archive_sucess',
						'error_code'=>'0',
						'error_description'=>'归档成功',
					);
				}
			}
			else
			{
				$res = array(
						'error'=>'archive_not_open',
						'error_code'=>'2',
						'error_description'=>'归档未开启',
				);
			} 	
		}
		$this->addItem($res);
		$this->output();
	}	
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		
	}
}
$ouput= new archiveUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>