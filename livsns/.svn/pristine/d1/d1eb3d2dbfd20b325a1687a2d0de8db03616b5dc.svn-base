<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: live_backup.php
***************************************************************************/
define('MOD_UNIQUEID','live_backup');
require_once('./global.php');
class live_backup extends adminReadBase
{
	private $mBackup;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/backup.class.php';
		$this->mBackup = new backup();
			
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
		
	}
	/**
	 * 备播文件列表显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @return $backup array 所有备播文件内容信息
	 */
	function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		if ($this->settings['App_livmedia'])
		{
			$this->addItem_withkey('livmedia', 1);
		}
		
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
		$count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
		
		$info = $this->mBackup->show($condition, $offset, $count);
		
		if (!empty($info))
		{
			foreach ($info AS $backup)
			{
				$this->addItem($backup);
			}
		}
		//服务器配置信息
		if ($this->input['server_id'])
		{
			$server_condition = '';
			$server_field	  = ' id, name ';
			$server_info = $this->mServerConfig->show($server_condition, 0, 100, '', $server_field);
			$this->addItem_withkey('server_info', $server_info);
		}
		$this->output();
	}

	/**
	 * 直播控制 备播文件 分页
	 * Enter description here ...
	 */
	public function getBackupInfo()
	{
		$condition = $this->get_condition();
		$offset = intval($this->input['offset']);
		$count = intval($this->input['counts']);
		$width = 136;
		$height = 102;
		$total = $this->mBackup->count($condition);
		$info = $this->mBackup->show($condition, $offset, $count, $width, $height);
		$data = array(
			'channel_id' => intval($this->input['channel_id']),
			'backupInfo' => $info,
		);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title like \'%' . trim(urldecode($this->input['k'])) . '%\'';
		}
		
		if(isset($this->input['id']) && $this->input['id'])
		{
			$condition .= ' AND id IN('.trim(urldecode($this->input['id'])).')';
		}
		
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= " AND status = " . $this->input['status'];
		}
		
		if (isset($this->input['server_id']) && $this->input['server_id'] && $this->input['server_id'] != -1)
		{
			$condition .= " AND server_id = " . $this->input['server_id'];
		}
		return $condition;
	}
		
	/**
	 * 单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 备播文件ID
	 * @return $row array 单条频道信息
	 */
	function detail()
	{
		$info = $this->mBackup->detail(urldecode($this->input['id']));
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mBackup->count($condition);
		echo json_encode($info);
	}
}
$output= new live_backup();
if(!method_exists($output, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>