<?php
/***************************************************************************
* $Id: verify_code_auto.php 36459 2014-04-17 02:13:51Z jiyuting $
***************************************************************************/
define('MOD_UNIQUEID','dynamic');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
include_once(ROOT_PATH . 'lib/class/curl.class.php');
include_once(CUR_CONF_PATH . 'lib/card.class.php');
set_time_limit(0);
class dynamicAutoApi extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		global $gGlobalConfig;
		$this->publishcontent = new publishcontent();
		if ($gGlobalConfig['App_publishcontent'])
		{
			$this->publish = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
		}
		if (!$this->publish)
		{
			$this->errorOutput('请求发布库错误');
		}
		$this->mode = new cardClass();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '自动更新卡片数据',	 
			'brief' => '自动更新卡片数据',
			'space' => '30',//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function run()
	{
		//取栏目id,卡片id
		$sql = "SELECT c.id as card_id,c.column_id,c.status,cc.id FROM " .DB_PREFIX. "card_content cc LEFT JOIN " .DB_PREFIX. "card c 
								ON c.id = cc.cardid WHERE c.is_dynamic = 1 AND c.column_id != 0 AND cc.active = 1 
								ORDER BY c.column_id DESC,cc.order_id DESC";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$map[$row['column_id']][] = $row['id'];
			if($row['status'] == 2)
			{
				$audit_id[$row['card_id']] = $row['card_id'];
			}
		}

		//根据栏目id取指定条目数据
		$column_idarr = array_keys($map);
		foreach((array)$column_idarr as $val)
		{
			$this->publish->setSubmitType('post');
			$this->publish->setReturnFormat('json');
			$this->publish->initPostData();
			$this->publish->addRequestData('count',count($map[$val]));
			$this->publish->addRequestData('column_id',$val);
			$this->publish->addRequestData('a','get_content');
			$this->publish->addRequestData('html',true);
			$r = $this->publish->request('content.php');
			if($r)
			{
				$content[$val] = $r;
			}
			else
			{
				continue;
			}
		}
		
		//更新卡片数据
		if($content)
		{
			foreach((array)$content as $key => $val)
			{
				foreach((array)$map[$key] as $k => $v)
				{
					$module_name = $this->publishcontent->get_content_type_by_app($val[$k]['bundle_id'],$val[$k]['module_id']);
					$val[$k]['childs_data'] ? $childs_data = @serialize($val[$k]['childs_data']) : $childs_data = '';
					$sql = "UPDATE " .DB_PREFIX. "card_content SET 
							content_id 	= " .$val[$k]['id']. ",
							module_id 	= '" .$val[$k]['module_id']. "',
							module_name = '" .$module_name['content_type']. "',
							title 		= '" .$val[$k]['title']. "',
							brief 		= '" .$val[$k]['brief']. "',
							indexpic 	= '" .@serialize($val[$k]['indexpic']). "',
							create_time = '" .TIMENOW. "',
							childs_data 	= '" .$childs_data. "'
							WHERE id 	= " .$v;
					$this->db->query($sql);
				}
			}
		}
		
		//更新卡片冗余数据
		if($audit_id && is_array($audit_id))
		{
			foreach($audit_id as $card_id)
			{
				$this->mode->audit($card_id,1);
			}
		}
		
		//删除缓存
		if(file_exists(CACHE_DIR . 'card.json'))
		{
			@unlink(CACHE_DIR . 'card.json');
		}
		
		$this->addItem('success');
		$this->output();
	}
}

$out = new dynamicAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>