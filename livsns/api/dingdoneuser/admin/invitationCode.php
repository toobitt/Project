<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/invitationCode.class.php';
define('MOD_UNIQUEID','dingdone_user');//模块标识

class invitationCode extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new invitationCodeClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $this->condition()
		);
		$code_info = $this->api->show($data);
		$this->setXmlNode('code_info', 'code');
		if ($code_info)
		{
			foreach ($code_info as $code)
			{
				$this->addItem($code);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->condition();
		$info = $this->api->count($condition);
		echo json_encode($info);
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$code_info = $this->api->detail('invitation_code', array('id' => $id));
		$this->addItem($code_info);
		$this->output();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
	    $data = array();
	    if (isset($this->input['send_id']))
	    {
	        $data['send_id'] = intval($this->input['send_id']);
	    }
	    if (isset($this->input['uid']))
	    {
	        $data['user_id'] = intval($this->input['uid']);
	    }
		return $data;
	}
	
	/**
	 * 创建邀请码
	 */
	public function create()
	{
	    $num = intval($this->input['num']);
	    if ($num <= 0) $num = 1;
	    if ($num > 10) $num = 10;
	    $insertData = array(
	        'add_user_id' => $this->user['user_id'],
	        'add_user_name' => $this->user['user_name'],
	        'org_id' => $this->input['org_id'],
	        'create_time' => TIMENOW,
	        'ip' => hg_getip()
	    );
	    if ($num > 1)
	    {
	        for ($i = 0; $i < $num; $i++)
	        {
	            $insertData['code'] = uniqid();
	            $result = $this->api->create('invitation_code', $insertData);
	        }
	    }
	    else
	    {
	        $insertData['code'] = uniqid();
	        $result = $this->api->create('invitation_code', $insertData);
	    }
	    $this->addItem($result);
	    $this->output();
	}
	
	/**
	 * 删除数据
	 */
	public function delete()
	{
	    $id = trim(urldecode($this->input['id']));
	    $id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$result = $this->api->delete('invitation_code', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 设置邀请码发送
	 */
	public function sendTo()
	{
	    //$user_id = intval($this->input['sender_id']);
	    $code_id = intval($this->input['id']);
	    //验证用户和邀请码是否有效
	    $code_info = $this->api->detail('invitation_code', array('id' => $code_id));
	    if (!$code_info) $this->errorOutput(CODE_INVALID);
	    //TODO 先只模拟用户
	    $user = array(
	        'user_id' => -1,
	        'user_name' => 'dingdone'
	    );
	    $updateData = array(
	        'send_to_id' => $user['user_id'],
	        'send_to_name' => $user['user_name']
	    );
	    $where = array(
	        'id' => $code_id
	    );
	    $result = $this->api->update('invitation_code', $updateData, $where);
	    $this->addItem($result);
	    $this->output();
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$ouput = new invitationCode();

if (!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>