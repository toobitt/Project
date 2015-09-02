<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_report_m');//模块标识

class helpshowApi extends BaseFrm
{
	/**
	 * 
	 * Enter description here ...
	 */
	function __construct()
	{
		parent::__construct();
		require_once  'lib/helpLib.class.php';
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	
	public function getCondition()
	{
		$data = array();
		if(isset($this->input['sort_id']))
		{
			$data['sort_id'] = trim($this->input['sort_id']);
		}
		if(isset($this->input['id']))
		{
			$data['id'] = trim($this->input['id']);
		}
		if(isset($this->input['subject']))
		{
			$data['subject'] = trim(htmlspecialchars_decode(urldecode($this->input['subject'])));
		}
		if(isset($this->input['content']))
		{
			$data['content'] = trim(htmlspecialchars_decode(urldecode($this->input['content'])));
		}
		/*
		if(isset($this->input['source_url']))
		{
			$data['source_url'] = trim(htmlspecialchars_decode(urldecode($this->input['source_url'])));
		}
		if(isset($this->input['source_id']))
		{
			$data['source_id'] = trim($this->input['source_id']);
		}
		if(isset($this->input['comtent']))
		{
			$data['comtent'] = trim(htmlspecialchars_decode(urldecode($this->input['comtent'])));
		}
		*/
		return $data;
	}
	
	/**
	 * 返回列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
		
		$data = array();
		$data = $this->getCondition();
		$func['t.sort_id'] = '=g.sort_id';
		$result = array();
		//初始化方法
		$this->libhelp = new helpLib();
		$result = $this->libhelp->get(array('help'=>'t','help_sort'=>'g'),'t.id,t.subject,t.sort_id,t.create_time,t.content,t.related_num,t.key_num,g.sort_name', $data, $offset, $count, array(), array(), $func);
		if($result)
		{
			foreach($result as $k)
			{
				if(NEED_TIME_TO_UNIX)
				{
					if($k['create_time'] && is_numeric($k['create_time']))
					{
						$k['create_time'] = date("Y-m-d H:i:s", $k['create_time']);
					}
					if($k['update_time'] && is_numeric($k['update_time']))
					{
						$k['update_time'] = date("Y-m-d H:i:s", $k['update_time']);
					}
				}
				$this->addItem_withkey($k['id'], $k);
			}
		}
		$this->output();
	}
	
	/**
	 * 返回对应总数
	 */
	public function count()
	{
		$data = array();
		$data = $this->getCondition();
		
		$result = 0;
		$this->libhelp = new helpLib();
		$result = $this->libhelp->get('help','count(id) as total', $data, 0, 1, array(), array(), array());
		$this->addItem_withkey('total', $result);
		$this->output();
	}
	
	/**
	 * 显示具体某条举报
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$result = array();
			$func['t.sort_id'] = '=g.sort_id';
			$this->libhelp = new helpLib();
			$result = $this->libhelp->get(array('help'=>'t','help_sort'=>'g'),'t.*,g.sort_name', array('id'=>$id), 0, 1, array(), array(), $func);
			if($result && is_array($result))
			{

				if(NEED_TIME_TO_UNIX)
				{
					$result['create_time'] = date('Y-m-d H:i:d' , $result['create_time']);
					$result['update_time'] = date('Y-m-d H:i:d' , $result['update_time']);
				}
			}
			$this->addItem($result);
			$this->output();
		}
		else 
		{
			$this->errorOutput('未传入查询ID');
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BaseFrm::__destruct()
	 */
	function __destruct()
	{
		parent::__destruct();
	}
}
/**
 *  程序入口
 */
$out = new helpshowApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action();
?>
