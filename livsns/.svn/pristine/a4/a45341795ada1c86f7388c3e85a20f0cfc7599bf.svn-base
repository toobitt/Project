<?php
/*******************************************************************
 * filename :member_spread_update.php
 * 推广接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','memberSpread');//模块标识
require('./global.php');
class memberSpreadApi extends outerReadBase
{
	private $memberSpread = null;
	public function __construct()
	{
		parent::__construct();
		$this->memberSpread = new memberSpread();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
	}

    public function detail()
    {
    	try {
    		$this->memberSpread->setMemberId($this->user['user_id']);
    	}
    	catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
    	$this->addItem($this->memberSpread->detail()->outputData('detail'));
    	$this->output();
    }

    public function count()
    {
    	
    }
    

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new memberSpreadApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>