<?php
require('./global.php');
define('MOD_UNIQUEID', 'formstyle'); //模块标识
require_once CUR_CONF_PATH . 'core/catalog.core.php';
class formstyleUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
	    	if(stripos($this->user['prms']['app_prms'][APP_UNIQUEID]['setting'],MOD_UNIQUEID)===false)
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->catalogcore = new catalogcore();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	//添加编目样式
	public function create()
	{
		if($this->input['zh_name'] == "" || $this->input['formhtml'] == "")
		{
			$this->errorOutput(NO_INPUT);
		}
		if($this->check_exist())
		{
			$this->errorOutput(STYLE_NAME_EXIST);
		}
		$type=$this->input['type'];
		if(is_numeric($type))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $type, $match))
		{
			$this->errorOutput('标识禁止使用或者含有汉字');
		}
		$data = array(
			'zh_name'		=> trim($this->input['zh_name']),
			'formhtml'		=> trim(addslashes(html_entity_decode($this->input['formhtml'],ENT_QUOTES))),
			'type'		=> trim($type)
		);
		$sql = 'INSERT INTO '. DB_PREFIX.'style SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$this->catalogcore->cache();//更新缓存
		$this->addItem($data);
		$this->output();
	}


	//修改编目样式
	public function update()
	{
		$id=intval($this->input['id']);
		/*
		 $type=$this->input['type'];
		 if(is_numeric($type))
		 {
			$this->errorOutput('标识禁止全数字');
			}
			elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $type, $match))
			{
			$this->errorOutput('标识禁止使用或者含有汉字');
			}
			*/
		$sql='SELECT distinct s.zh_name FROM '.DB_PREFIX.'style AS s LEFT JOIN '.DB_PREFIX.'field AS f ON f.form_style=s.id WHERE f.form_style IN ('.$id.')';
		$q=$this->db->query($sql);
		while($ret=$this->db->fetch_array($q))
		{
			$delstyle[]=$ret['zh_name'];
		}
		$delstyle=implode(' 、', $delstyle);
		if (!empty($delstyle))
		{
			$this->errorOutput($delstyle.',已被使用,防止系统错误,禁止修改.');
		}
		$data = array(
			'zh_name'		=> trim($this->input['zh_name']),
			'formhtml'		=> trim(addslashes(html_entity_decode($this->input['formhtml'],ENT_QUOTES)))
		);
		if($this->input['zh_name'] == "" || $this->input['formhtml'] == "")
		{
			$this->errorOutput(NO_INPUT);
		}
		if($this->check_exist() && $this->input['zh_name'] != $this->input['zh_name_old'])
		{
			$this->errorOutput(STYLE_NAME_EXIST);
		}
		if(!is_array($data))
		{
			return false;
		}
		$where = "id=" . $id;
		$res = $this->db->update_data($data, 'style', $where);
		$this->catalogcore->cache();//更新缓存
		$this->addItem($data);
		$this->output();
	}
	//删除编目样式
	public function delete()
	{
		$ids = $this->input['id'];
		$ids_arr = explode(',', $ids);
		$ids_arr = array_filter($ids_arr);
		$ids = implode(',', $ids_arr);
		if (!$ids_arr)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		else
		{
			$sql='SELECT distinct s.zh_name FROM '.DB_PREFIX.'style AS s LEFT JOIN '.DB_PREFIX.'field AS f ON f.form_style=s.id WHERE f.form_style IN ('.$ids.')';
			$q=$this->db->query($sql);
			while($ret=$this->db->fetch_array($q))
			{
				$delstyle[]=$ret['zh_name'];
			}
			$delstyle=implode(' 、', $delstyle);
			if (!empty($delstyle))
			{
				$this->errorOutput($delstyle.',已被使用,禁止删除');
			}
		}
		$sql = 'DELETE FROM ' .  DB_PREFIX.'style WHERE id IN(' . $ids . ')';
		$this->db->query($sql);
		$this->catalogcore->cache();//更新缓存
		$this->addItem($ids_arr);
		$this->output();
	}

	//检测是否插入重复记录
	public function check_exist()
	{
		$sql = "SELECT id FROM " .  DB_PREFIX."style WHERE zh_name='" . trim($this->input['zh_name']) . "'";
		$arr = $this->db->query_first($sql);
		$c_id = $arr['id'];
		return $c_id;
	}

	//
	public function sort()
	{

	}
	//
	public function audit()
	{

	}
	//
	public function publish()
	{

	}

	//空方法
	public function unknow()
	{

		$this->errorOutput("此方法不存在！");
	}

}

$out = new formstyleUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>