<?php
/**
 * 附件管理
 */
require('./global.php');
define('MOD_UNIQUEID','material');
class affixApi extends adminReadBase
{   
	/**
	* 构造函数
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @include affix.class.php
	*/
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'		=>'管理',
		);		
		parent::__construct();
		include(CUR_CONF_PATH.'lib/affix.class.php');
		$this->obj=new affix();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}
	
	/**
	* 根据条件检索附件信息
	* @name show
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @return $arr array 附件信息
	*/
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition = $this->get_condition();
		$offset=$this->input['offset'] ? intval($this->input['offset']) : 0;
		$count=$this->input['count'] ? intval($this->input['count']) : 10;
		$data_limit='LIMIT ' . $offset . ',' . $count;

		$ret=$this->obj->show($condition.$data_limit);
		$this->setXmlNode('affixs','affix');
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
	* @return $info array json串
	*/
	public function count()
	{
		$condition=$this->get_condition();
		$info=$this->obj->count($condition);
		echo json_encode($info);
	}

	/**
	* 根据ID检索附件，默认为第一条
	* @name detail
	* @access public
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @return $info array 一条附件信息
	*/
	public function detail()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if($this->input['id'])
		{
			$data_limit="AND id IN(" . intval($this->input['id']) . ")";
		}
		else
		{
			$data_limit="LIMIT 1";
		}

		$ret=$this->obj->detail($data_limit);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询失败');
		}
	}


	/**
	* 获取检索条件
	* @name get_condition
	* @access private
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @return $string string 检索条件
	*/
	private function get_condition()
	{
		$condition='';

		//查询关键字
		if($this->input['key'])
		{
			$condition .='AND name LIKE \'%' . trim(urldecode($this->input["key"])) . '%\'';
		}
		//查询创建的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND create_time > " . strtotime($this->input['start_time']);
		}
		
		//查询创建的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND create_time < " . strtotime($this->input['end_time']);	
		}

        //查询发布的时间
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

	    //查询附件的类型，jpg，gif，png等
		if(isset($this->input['pic_type']))
		{
			switch(intval($this->input['pic_type']))
			{
					case 1:
						$condition .= " ";
						break;
					case 2: 
						$condition .= " AND type= 'jpg'";
						break;
					case 3:
						$condition .= " AND type = 'jpeg'";
						break;
					case 4:
						$condition .= " AND type='png'";
						break;
					case 5:
						$condition .= " AND type='gif'";
					default:
						break;
			}
		}
		if($this->input['_modid'])
		{
			$condition .=" AND mid='" . urldecode($this->input['_modid']) . "'";
		}
		if($this->input['_appid'])
		{
			$condition .= " AND bundle_id='". urldecode($this->input['_appid'])."'";
		}		
		//查询排序字段(默认为ID字段)
		$order=$this->input['order_field'] ? trim(urldecode($this->input['order_field'])) : 'id';
		$condition .= ' ORDER BY ' . $order;

		//查询排序方式，默认为DESC
		$condition .= $this->input['descasc'] ? trim(urldecode($this->input['descasc'])) : ' DESC ';

		//返回检索条件
		return $condition;
	}
}

$out=new affixApi();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>