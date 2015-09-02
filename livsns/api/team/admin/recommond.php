<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 13202 2012-10-27 12:32:11Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','recommond');//模块标识
require('global.php');
class recommondApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/recommond.class.php');
		$this->obj = new recommond();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}
	
	public function index()
	{
		
	}

	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	

		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$content = $this->obj->show($condition . " ORDER BY orderid DESC " . $data_limit);
		$sql = "SELECT * FROM " . DB_PREFIX ."column WHERE 1 ";
		$q = $this->db->query($sql);
		$column = array();
		while($row = $this->db->fetch_array($q))
		{
			$column[$row['id']] = $row['name'];
		}
		$ret = array();
		$ret['content'] = $content;	
		$ret['column'] = $column;
		$this->addItem($ret);
		$this->output();
	}
	
	public function recommond_show()
	{
		if(!$this->input['column_id'])
		{
			$this->errorOutput('未传入栏目ID');
		}
		$columnd_id = $this->input['column_id'];
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$file_name_prex = 'recommond-' . $columnd_id . '-';
		$dir = RECOMMOND_CACHE;
		$ret = array();
		if(hg_mkdir($dir))
		{
			$file_name = $file_name_prex . $count . '-' . $offset . '.tmp';
			if(file_exists($dir . $file_name))
			{
				$ret = json_decode(file_get_contents($dir . $file_name),true);
			}
			else
			{
				$condition = $this->get_condition();
				$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
				$ret = $this->obj->show($condition . " ORDER BY orderid DESC " . $data_limit);
				file_put_contents($dir . $file_name,json_encode($ret));
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 检索条件 关键字，时间，状态,标题，发布时间，图片，附件，视频
	 * @name get_condition
	 * @access private
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if($this->input['key'])
		{
			$condition .= 'AND title LIKE "%'.trim($this->input['key']).'%"';
		}
		if($this->input['column_id'])
		{
			$condition .= ' AND column_id=' . $this->input['column_id'];
		}

		//查询创建的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND pubtime > " . strtotime($this->input['start_time']);
		}
		
		//查询创建的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND pubtime < " . strtotime($this->input['end_time']);	
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
					$condition .= " AND  pubtime > '".$yesterday."' AND pubtime < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  pubtime > '".$today."' AND pubtime < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  pubtime > '".$last_threeday."' AND pubtime < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  pubtime > '".$last_sevenday."' AND pubtime < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;	
	}


	/**
	 * 显示单篇文章 文章ID不存在默认为最新第一条
	 * @name detail
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param int $id 文章ID
	 * @return $info array 新闻内容
	 */
	public function detail()
	{
		if($this->input['id'])
		{
			$data_limit = ' and id=' . intval($this->input['id']);
		}
		else
		{
			$data_limit = ' LIMIT 1';
		}		
		$ret = $this->obj->detail($data_limit);
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
	
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new recommondApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	