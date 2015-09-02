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
define('MOD_UNIQUEID','mark_auto');//模块标识
require('global.php');
class topicMarkSysAutoApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/video.class.php');
		$this->obj = new video();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}

	public function update()
	{
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		include_once(ROOT_PATH . 'lib/class/mark.class.php');
		$obj_mark = new mark();
		$ret = $obj_mark->get_hot_mark(array('source'=>'topic','count'=>-1));
		$info = array();
		$i = array();
		foreach($ret as $k => $v)
		{
			$i[$v['parent_id']]++;
			if($i[$v['parent_id']] > $count)
			{
				continue;
			}
			$info[$v['parent_id']][] = $v['mark_name'];		
		}
		$sql = "UPDATE " . DB_PREFIX . "team SET ";
		foreach($info as $k => $v)
		{
			$extra = "hot_topic_tags='" . implode(',',$v) . "' WHERE team_id=" . $k ;
			$this->db->query($sql . $extra);
		}
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new topicMarkSysAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();

?>	