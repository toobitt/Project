<?php
/**
 * 编目管理
 */
require('./global.php');
define('MOD_UNIQUEID','custom_manage');
include_once CUR_CONF_PATH . 'lib/manage.class.php';
include_once CUR_CONF_PATH . 'core/custom_manage.core.php';
class catalogSetUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->manage = new manage();
		$this->customcore = new customcore();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$authinfo=$this->input['authinfo'];
		$authinfo['status']=0;
		$authinfo['appkey']=guid('');
		$re=$this->customcore->create('authinfo', $authinfo);
		$custominfo=$this->input['custominfo'];
		$custominfo['appid']=$re['id'];
		$this->customcore->create('custominfo', $custominfo);
		$this->addItem('true');
		$this->output();
	}

	public function update()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('NO_ID');
		}
		$id['appid']=intval($this->input['id']);
		$authinfo=$this->input['authinfo'];
		$this->customcore->update('authinfo', $authinfo,$id);
		$custominfo=$this->input['custominfo'];
		$this->customcore->update('custominfo', $custominfo,$id);
		$this->addItem('true');
		$this->output();
	}


	/**
	 * 删除
	 */
	public function delete()
	{
		$ids = isset($this->input['id']) ? trim($this->input['id']) : '';
		if (empty($ids)) 
		{
			$this->errorOutput(PARAM_WRONG);
		}
		
		$result = $this->manage->delete('authinfo', array('appid' => $ids));
		if($result)
		{
			$this->manage->delete('custominfo', array('appid' => $ids));
		}
		$this->addItem($result);
		$this->output();
	}

	//编目开关
	public function open()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$switch = intval($this->input['is_on']);
		$switch = ($switch ==1) ? $switch : 0;
		$data = $this->customcore->display($ids,$switch,'authinfo');
		$this->addItem($data);
		$this->output();
	}
	
	public function audit()
	{
		$appid = trim($this->input['id']);
		
		if (!$appid)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		$sql='SELECT appid,status FROM '.DB_PREFIX."authinfo WHERE appid =".$appid;
		$authinfo=$this->db->query_first($sql);
		if (empty($authinfo))
		{
			$this->errorOutput('没有此客户');
		}
		
		$status = $authinfo['status'];
				
		$app_id = array(
			'appid'	=> $appid,
		);
		$ret['id']=$appid;
		if ($status==1) //启动
		{
			$update_data['status'] = 2;
			$ret['status'] = 2;
		}
		else	//停止
		{
			$update_data['status'] = 1;
			$ret['status'] = 1;
		}
		
		$this->customcore->update('authinfo',$update_data,$app_id);
		
		$this->addItem($ret);
		$this->output();
	}
	public function sort()
	{
		$this->addLogs('更改编目排序', '', '', '更改编目排序');
		$ret = $this->drag_order('field', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		//
	}


	private function get_form_style($id)
	{
		$sql = "SELECT zh_name as form_style_name FROM ".DB_PREFIX. "style WHERE id = " .$id;
		$data = $this->db->query_first($sql);
		return $data;
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new catalogSetUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>