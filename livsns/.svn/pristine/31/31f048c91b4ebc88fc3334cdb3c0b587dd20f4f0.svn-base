<?php
/**
 **编目管理*
 */
require('./global.php');
define('MOD_UNIQUEID','catalog_set');
require_once(CUR_CONF_PATH . 'core/custom_manage.core.php');
include_once (CUR_CONF_PATH . 'lib/manage.class.php');

class catalogSet extends adminReadBase
{

	public function __construct()
	{
		parent::__construct();
		$this->manage = new manage();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
		//
	}

	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$sql = "SELECT ai.appid as id,ai.* FROM ".DB_PREFIX."authinfo as ai WHERE 1";
		//$condition = $this->get_condition();
		if ($condition) $sql .= $condition;
		$sql .= " ORDER BY ai.order_id DESC ";
		if($offset || $count)
		{
			$sql .= " LIMIT " . $offset . " , " . $count ;  //分页
		}
		 
		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			$data['install_type'] = $this->settings['install_type'][$data['install_type']];
			$data['source'] = $this->settings['source'][$data['source']];
			$data['tip_way'] = $this->settings['tip_way'][$data['tip_way']];
			$data['avatar'] = hg_fetchimgurl(maybe_unserialize($data['avatar']),'40','30');
			$data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
			$data['update_time'] = date('Y-m-d H:i:s',$data['update_time']);
			$this->addItem($data);
		}
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = 'SELECT COUNT(*) AS total FROM ' .DB_PREFIX. 'field f WHERE 1';
		if ($condition) $sql .= $condition;
		//exit($sql);
		exit(json_encode($this->db->query_first($sql)));
	}

	//获取某个编目的配置
	public function detail()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$sql = "SELECT ai.appid as id,ai.*,ci.* FROM ".DB_PREFIX."authinfo as ai LEFT JOIN ".DB_PREFIX."custominfo as ci ON ci.appid=ai.appid WHERE ai.appid =".$id;
		$data=$this->db->query_first($sql);
		$data['avatar'] = hg_fetchimgurl(maybe_unserialize($data['avatar']),'40','30');
		$data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
		$data['update_time'] = date('Y-m-d H:i:s',$data['update_time']);
		$this->addItem($data);
		$this->output();
	}

}

$out=new catalogSet();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>