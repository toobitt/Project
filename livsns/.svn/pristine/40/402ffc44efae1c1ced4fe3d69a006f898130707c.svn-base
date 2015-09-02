<?php
/**
 * 附件配置
 */
require('./global.php');
define('MOD_UNIQUEID','settings');
class affixSettingApi extends adminReadBase
{
	/**
	* 构造函数
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @include affix_setting.class.php
	*/
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/affix_setting.class.php');
        $this->obj=new affixSetting();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
    /**
	* 根据条件检索附件配置信息
	* @name show
	* @access public
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @return $info array 附件配置信息
	*/
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition=$this->get_condition();
		$offset=$this->input['offset'] ? intval($this->input['offset']) : 0;
		$count=$this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit='LIMIT ' . $offset . ' , ' . $count;

		$ret=$this->obj->show($condition . $data_limit);
		$this->setXmlNode('affix_setting','setting');
        foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}


    /**
	* 根据条件返回总数
	* @name count
	* @access public
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @return $info array 总数json串
	*/
    public function count()
	{
		$condition=$this->get_condition();
		$info=$this->obj->count($condition);
		echo  json_encode($info);
	}
    
	/**
	* 根据ID查询单条数据
	* @name detail
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @return $info array 附件配置信息
	*/
	public function detail()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if($this->input['id'])
		{
			$data_limit=' AND aid=' . intval($this->input['id']);
		}
		else
		{
			$data_limit='LIMIT 1';
		}

		$ret=$this->obj->detail($data_limit);
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询数据失败!');
		}
	}


	/**
	* 获取检索条件
	* @name get_condition 
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @return $condition array 检索条件
	*/
	private function get_condition()
	{
		$condition=' ';
         
		 //查询ID
		 if($this->input['id'])
		{
			 $condition .='AND aid IN(' . intval($this->input["id"]) . ')';
		}

		//查询关键字
		if($this->input['k'])
		{
			$condition .='AND aname  LIKE \'%' . trim(urldecode($this->input["k"])) . '%\'';
		}

		//查询排序字段（默认为ID字段）
		$order=$this->input['order_field'] ? trim(urldecode($this->input['order_field'])) : 'aid';
        $condition.='ORDER BY ' . $order;

		//查询排序方式，默认为ASC
		$condition .=$this->input['descasc'] ? trim(urldecode($this->input['descasc'])) : ' ASC ';

		//返回
		return $condition;
	}

	public function is_open()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('ID不存在');
		}
		$id=intval($this->input['id']);
		$ret=$this->obj->is_open($id);
		$this->addItem($ret);
		$this->output();
	}
}

$out=new affixSettingApi();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();

?>