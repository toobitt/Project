<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/body_tpl_mode.php');
define('MOD_UNIQUEID', 'body_tpl');

class body_tpl extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new body_tpl_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

    public function detail(){}
    
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition 	= '';
		$condition .= " AND status = 2 AND type = 1 ";//对外输出一定要经过审核的
		
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		return $condition;
	}
	
	//创建或者更新自定义模板
	public function diyTplOprate()
	{
		$id = $this->input['id'];//模板id
		$name 		= $this->input['name'];
		$body_html 	= $this->input['body_html'];
		
		if(!$name)
		{
			$this->errorOutput(NO_TPL_NAME);
		}
		
		if(!$body_html)
		{
			$this->errorOutput(NO_TPL_HTML);
		}
		
		//更新
		if($id)
		{
			$data = array(
				'name' 			=> $name,
				'body_html' 	=> $body_html,
				'update_time' 	=> TIMENOW,
				'status'		=> 2,//默认是已审核
			);
			
			$ret = $this->mode->update($id,$data);
			if($ret)
			{
				$this->addItem($data);
				$this->output();
			}
		}
		else //创建
		{
			$data = array(
				'name' 			=> $name,
				'body_html' 	=> $body_html,
				'type'			=> 2,//类型是自定义
				'status'		=> 2,//默认是已审核
				'create_time' 	=> TIMENOW,
				'update_time' 	=> TIMENOW,
			);
			$vid = $this->mode->create($data);
			if($vid)
			{
				$data['id'] = $vid;
				$this->addItem($data);
				$this->output();
			}
		}
	}
}

$out = new body_tpl();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>