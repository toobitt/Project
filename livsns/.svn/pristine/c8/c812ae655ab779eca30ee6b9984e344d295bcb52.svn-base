<?php
require('global.php');
define('MOD_UNIQUEID','mood');
require_once(CUR_CONF_PATH . 'lib/mood_mode.php');
class mood_update extends outerUpdateBase
{
	private $mood;
	function __construct()
	{
		parent::__construct();
		$this->mood = new mood_mode();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		$rids = intval($this->input['rid']);   //发布库内容id
		$cids = intval($this->input['cid']);   //非发布库内容id
		$mood_style = intval($this->input['mood_style']);
		$mood_id = intval($this->input['mood']);
		if(!$mood_style)
		{
			$this->errorOutput('没有选择心情顶踩样式类型');
		}
		if(!$mood_id)
		{
			$this->errorOutput('没有选择心情顶踩');
		}
		
		if(!$rids)
		{
			if(!$cids && !$this->input['app_uniqueid']) //没有内容id 且没有应用名称
			{
				$this->errorOutput(NO_CONTENT);
			}
		}
		
		//判断库中是否有该心情
		$sql = "SELECT * FROM ".DB_PREFIX."mood_option WHERE id = '". $mood_id. "' AND style_id = '".$mood_style."'";
		$query = $this->db->query_first($sql);
		if(!$query || count($query)<1)    //如果数据库没有该内容数据
		{
			$this->errorOutput('心情顶踩样式错误');
		}
		
		//如果提交的是发布库id
		if($rids)
		{
			$content = $this->mood->get_publishcontent($rids);  //获取内容信息
			$content = $content[$rids];
		    if(!$content)
		    {
			    $this->errorOutput(NO_CONTENT);
		    }
		    $data = array(
			    'rid'              => $rids,   //发布库id
		        'title'            => $content['title'],
		        'app_uniqueid'     => $content['app_uniqueid'],
		        'module_uniqueid'  => $content['module_uniqueid'],
			    //'site_id'          => $content['site_id'],
			    //'column_id'        => $content['column_id'],
		        'column_name'      => $content['column_name'],
		        'is_indexpic'      => $content['is_indexpic'],   
		        'cid'              => $content['cid'],
			    'mood_style'       => $mood_style,
		        'create_time'     => $content['create_time'],
		        'create_user'     => $content['create_user'],
			);
			//检查数据库中是否有该内容记录
		    $sql = "SELECT * FROM ".DB_PREFIX."mood WHERE rid = '". $rids. "' AND cid = '" . $data['cid'] ."'";
		    $list = $this->db->query_first($sql);
		    if(!$list || count($list)<1)    //如果数据库没有该内容数据，就添加该内容
		    {
		    	$data['counts'] = 1;
		    	$ret = $this->mood->create($data);
		    	$data['id'] = $ret;
		    }
		    else
		    {
		    	$data['counts'] = $list['counts']+1;
		    	$condition = ' AND rid = '.$rids;
		    	$data = $this->mood->update_mood($data,$condition);
		    	//$data = $this->mood->update($rids);//如果已经有该条记录，则更新参与人数
		    }
		}
		else   //非发布库内容入库
		{
			$data = array(
			'rid'              => 0,   //发布库id
		    'title'            => $this->input['title'] ? $this->input['title'] : 'NO_TITLE',
		    'app_uniqueid'     => $this->input['app_uniqueid'],
		    'module_uniqueid'  => $this->input['module_uniqueid'],
		    'column_name'      => '其他',
		    'cid'              => $cids,
			'mood_style'       => $mood_style,
		    'create_time'      => TIMENOW,
		    'create_user'      => $this->user['name'],
			);
			$sql = "SELECT * FROM ".DB_PREFIX."mood WHERE rid = 0 AND cid = '" . $cids ."' AND app_uniqueid = '" . $data['app_uniqueid'] . "'" ;
		    $list = $this->db->query_first($sql);
		    if(!$list || count($list)<1)    //如果数据库没有该内容数据，就添加该内容
		    {
		    	$data['counts'] = 1;
		    	$ret = $this->mood->create($data);
		    	$data['id'] = $ret;
		    }
		    else
		    {
		    	$data['counts'] = $list['counts']+1;
		    	$condition = "AND rid = 0 AND cid = '" . $cids ."' AND app_uniqueid = '" . $data['app_uniqueid'] . "'";
			    $data = $this->mood->update_mood($data,$condition);	
		    }
		}
		
		//栏目入库并取栏目id
		$ret = $this->db->query_first("SELECT id FROM " . DB_PREFIX . "mood_node WHERE name like '".$data['column_name']."'");
		$node_id = $ret['id'];
		if(!$node_id)
		{
			$sql = "INSERT INTO " . DB_PREFIX . "mood_node SET name='" .$data['column_name']. "', depath = 1 , is_last = 1";
			$this->db->query($sql);
			$column_id = $this->db->insert_id();
			if($column_id)
			{
				$data['column_id'] = $column_id;
			}
		}
		else
		{
			$data['column_id'] = $node_id;
		}
		$this->mood->update_column($data['id'],$data['column_id']);

		//添加到记录表
		$record = array(
		    'list_id'       => $data['id'],
		    'style_id'      => $mood_style,
		    'mood_id'       => $mood_id,
		    'ip'            => hg_getip(),
		    'user_id'       => $this->user['user_id'],
		    'app_id'        => $this->user['app_id'],
		    'app_name'      => $this->user['display_name'] ? trim($this->user['display_name']) : '网页',
		    'create_time'   => TIMENOW,
		);
		$ret = $this->db->insert_data($record,'mood_record');
		
		//添加到统计表
		$count = array(
		    'list_id'       => $data['id'],
		    'style_id'      => $mood_style,
		    'mood_id'       => $mood_id,
		    'counts'         => 1,
		);
		$sql = "SELECT * FROM ".DB_PREFIX."mood_count  WHERE list_id = '" . $data['id'] . "' AND mood_id = '" . $mood_id . "'";
		$ret = $this->db->query_first($sql);
		if(!$ret || count($ret)<1) //如果还没用统计，则创建统计数据
		{
			$ret = $this->db->insert_data($count,'mood_count');
		}
		else   //否则更新统计数据
		{
			$sql = " UPDATE " . DB_PREFIX . "mood_count SET counts = counts + 1  WHERE list_id = '" . $data['id'] . "' AND mood_id = '" . $mood_id . "'";
			$this->db->query($sql);
		}
		$condition = " AND list_id = " . $data['id'];
		$mood_result = $this->mood->get_mood_result($condition, $mood_style);
		$data['total_count'] = $mood_result['total_count'];
	    if(is_array($mood_result['result']) && count($mood_result['result'])>0)
		{
			foreach ($mood_result['result'] as $v)
			{
				$data['result'][$v['mood_id']] = $v['counts'];
			}
		}
		//$data['result'] = $mood_result['result'];
		$this->addItem($data);
		$this->output();
	}
	
	function update()
	{}
	
	public function initialize_data()
	{
		$rids = intval($this->input['rid']);   //发布库内容id
		$cids = intval($this->input['cid']);   //非发布库内容id
		$mood_style = intval($this->input['mood_style']);
		if(!$mood_style)
		{
			$this->errorOutput('没有选择心情顶踩样式类型');
		}
		
		if(!$rids)
		{
			if(!$cids && !$this->input['app_uniqueid']) //没有内容id 且没有应用名称
			{
				$this->errorOutput(NO_CONTENT);
			}
		}
		
		//如果提交的是发布库id
		if($rids)
		{
			$content = $this->mood->get_publishcontent($rids);  //获取内容信息
			$content = $content[$rids];
		    if(!$content)
		    {
			    $this->errorOutput(NO_CONTENT);
		    }
		    $data = array(
			    'rid'              => $rids,   //发布库id
		        'title'            => $content['title'],
		        'app_uniqueid'     => $content['app_uniqueid'],
		        'module_uniqueid'  => $content['module_uniqueid'],
			    //'site_id'          => $content['site_id'],
			    //'column_id'        => $content['column_id'],
		        'column_name'      => $content['column_name'],
		        'is_indexpic'      => $content['is_indexpic'],   
		        'cid'              => $content['cid'],
			    'mood_style'       => $mood_style,
		        'create_time'     => $content['create_time'],
		        'create_user'     => $content['create_user'],
			);
			//检查数据库中是否有该内容记录
		    $sql = "SELECT * FROM ".DB_PREFIX."mood WHERE rid = '". $rids. "' AND cid = '" . $data['cid'] ."'";
		    $list = $this->db->query_first($sql);
		    if(!$list || count($list)<1)    //如果数据库没有该内容数据，就添加该内容
		    {
		    	$data['counts'] = 0;
		    	$ret = $this->mood->create($data);
		    	$data['id'] = $ret;
		    }
		    else
		    {
		    	if($mood_style != $list['mood_style'])
		    	{
		    		$data['counts'] = 0;
		    	}
		    	//$data['error'] = '该内容已添加过！';
		    	$ret = $this->mood->update_mood($data,' AND id='.$list['id']);
		    	$data['id'] = $list['id'];
		    }
		}
		else   //非发布库内容入库
		{
			$data = array(
			'rid'              => 0,   //发布库id
		    'title'            => $this->input['title'],
		    'app_uniqueid'     => $this->input['app_uniqueid'],
		    'module_uniqueid'  => $this->input['module_uniqueid'],
		    'column_name'      => '其他',
		    'cid'              => $cids,
			'mood_style'       => $mood_style,
		    'create_time'      => TIMENOW,
		    'create_user'      => $this->user['name'],
			);
			$sql = "SELECT * FROM ".DB_PREFIX."mood WHERE rid = 0 AND cid = '" . $cids ."' AND app_uniqueid = '" . $data['app_uniqueid'] . "'" ;
		    $list = $this->db->query_first($sql);
		    if(!$list || count($list)<1)    //如果数据库没有该内容数据，就添加该内容
		    {
		    	$data['counts'] = 0;
		    	$ret = $this->mood->create($data);
		    	$data['id'] = $ret;
		    }
		    else
		    {
		    	if($mood_style != $list['mood_style'])
		    	{
		    		$data['counts'] = 0;
		    	}
		    	//$data['error'] = '该内容已添加过！';
		    	$ret = $this->mood->update_mood($data,' AND id='.$list['id']);
		    	$data['id'] = $list['id'];
		    }
		}
		
		//栏目入库并取栏目id
		$ret = $this->db->query_first("SELECT id FROM " . DB_PREFIX . "mood_node WHERE name like '".$data['column_name']."'");
		$node_id = $ret['id'];
		if(!$node_id)
		{
			$sql = "INSERT INTO " . DB_PREFIX . "mood_node SET name='" .$data['column_name']. "', depath = 1 , is_last = 1";
			$this->db->query($sql);
			$column_id = $this->db->insert_id();
			if($column_id)
			{
				$data['column_id'] = $column_id;
			}
		}
		else
		{
			$data['column_id'] = $node_id;
		}
		$this->mood->update_column($data['id'],$data['column_id']);
		$data['count'] = 0;
		$condition = " AND list_id = " . $data['id'];
		$mood_result = $this->mood->get_mood_result($condition, $mood_style);
		$data['total_count'] = $mood_result['total_count'];
		if(is_array($mood_result['result']) && count($mood_result['result'])>0)
		{
			foreach ($mood_result['result'] as $v)
			{
				$data['result'][$v['mood_id']] = $v['counts'];
			}
		}
		//$data['result'] = $mood_result['result'];
		$this->addItem($data);
		$this->output();
	}
	
	
	function delete()
	{}
		
	function unknow()
	{
		$this->errorOutput('此方法不存在');
	}
}
$out = new mood_update();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>