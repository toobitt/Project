<?php
/*******************************************************************
 * filename :member_myData_update.php
 * 我的数据储存数据展示接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','memberMyData');//模块标识
require('./global.php');
class memberMyDataUpdateAdmin extends adminBase
{
	private $memberMyData = null;
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->memberMyData = new memberMyData();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function delete()
	{
		try {
			if(($TParams = $this->memberMyData->setManyMdid($this->input['id'])))
			{
				if($TParams == -9)
				{
					$this->errorOutput(NO_DATA);
				}
				elseif($TParams == -10)
				{
					$this->errorOutput(NO_INPUT_MYDATA_ID);
				}
			}			
			$ret = $this->memberMyData->paramProcess('delete')->delete();//数据处理
			$redata = array(
				'status'=> $ret?1:0,
				'copywriting' => $ret?'删除成功':'删除失败',
				) ;
			$this->addItem($redata);
			$this->output();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new memberMyDataUpdateAdmin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>