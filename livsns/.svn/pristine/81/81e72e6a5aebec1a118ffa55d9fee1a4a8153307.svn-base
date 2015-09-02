<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: message_send_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/

require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagesend.class.php';
define('MOD_UNIQUEID', 'message_send'); //模块标识


class messagesendUpdateApi extends outerUpdateBase
{
	private $messagesend;
	
	public function __construct()
	{
		parent::__construct();
		$this->messagesend = new messagesendClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->messagesend);
	}
	
	/**
	 * 增加短信信息
	 */
	public function create()
	{
		
	}


	/**
	** 信息更新操作
	**/
	public function update()
	{
		$id = intval($this->input['id']);
		if (empty($id)){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$id = trim($this->input['id']);
			$backstatus = (int)trim($this->input['backstatus']);
			$updateData = array();
			$updateData['backstatus'] = $backstatus;
			$updateData['update_time'] = TIMENOW;
			$result = $this->messagesend->update($updateData,$id);
			$this->addItem($updateData);
			$this->output();
		}			
	}

	public function delete()
	{
			
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
	
}

$out = new messagesendUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>