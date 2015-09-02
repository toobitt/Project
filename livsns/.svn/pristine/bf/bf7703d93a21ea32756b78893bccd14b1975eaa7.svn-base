<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z wangleyuan $
***************************************************************************/
define('MOD_UNIQUEID','recycle');
require('global.php');
class recycleApi extends adminReadBase
{
	/**
	 * 构造函数
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include recycle.class.php
	 */
	public function __construct()
	{
		$this->mPrmsMethods = array(
			'manage'		=>'管理',
			'_node'=>array(
				'name'=>'回收站节点',
				'filename'=>'recycle_node.php',
				'node_uniqueid'=>'recycle_node',			
				),
		);				
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/recycle.class.php');
		$this->obj = new recycle();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function detail(){}

	/**
	 * 根据条件检索
	 * @name show
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info array 
	 */
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	
				
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->obj->show($condition . $data_limit);
        if($ret)
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
		
	}

	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 检索条件 
	 * @name get_condition
	 * @access private
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		//关键字
		if($this->input['key'])
		{
			$condition .=" and r.title like '%" . trim(urldecode($this->input['key'])) . "%'" ;
		}

	    //查询创建的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND r.time > " . strtotime($this->input['start_time']);
		}
		
		//查询创建的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND r.time < " . strtotime($this->input['end_time']);	
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
					$condition .= " AND  r.time > '".$yesterday."' AND r.time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  r.time > '".$today."' AND r.time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  r.time > '".$last_threeday."' AND r.time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  r.time > '".$last_sevenday."' AND r.time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['_appid'])
		{
			$condition .=" AND r.app_mark ='" . urldecode($this->input['_appid']) . "'";
		}
		if($this->input['_modid'])
		{
			$condition .=" AND r.module_mark ='" . urldecode($this->input['_modid']) . "'";
		}

		$condition .=" ORDER BY r.time  ";
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
		
		return $condition;	
	}

	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}


$out = new recycleApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			