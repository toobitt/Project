<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|compPreview|getTopicsCategoryInfo|getAreaInfo|getCollegeInfo|getOpusInfo|unknow
* 
* $Id: competition.php 6719 2012-05-15 09:32:07Z lijiaying $
***************************************************************************/
require('global.php');
class competitionApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include competiton.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/competiton.class.php');
		$this->obj = new competiton();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 参赛作品列表显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info array 参赛作品信息
	 */
	public function show()
	{
		if (!$this->input['uid'])
		{
			$this->errorOutput('用户ID不存在');
		}
		$info = $this->obj->show();
		
		if (!$info)
		{
			$this->errorOutput('参赛作品信息不存在');
		}
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 参赛作品单个预览
	 * @name compPreview
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info array 参赛作品信息
	 */
	public function compPreview()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput('参赛作品ID不存在');
		}
		$info = $this->obj->show();
		
		if ($info)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "college c LEFT JOIN " . DB_PREFIX . "area a ON c.aid=a.aid ";
			$sql .= " WHERE c.cid = " . $info[$this->input['id']]['cid'] . " AND c.aid = " . $info[$this->input['id']]['aid'];
			$c_info[$this->input['id']] = $this->db->query_first($sql);
			
			$return = array();
			foreach ($info AS $k => $v)
			{
				if ($c_info[$k])
				{
					$return[$k] = @array_merge($info[$k],$c_info[$k]);
				}
			}
		}
		
		if (!$return)
		{
			$this->errorOutput('参赛作品不存在');
		}
		$this->addItem($return);
		$this->output();
	}
	/**
	 * 选题类别
	 * @name getTopicsCategoryInfo
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info array 各大赛区内容信息
	 */
	public function getTopicsCategoryInfo()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "topics_category WHERE 1";
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[$row['tid']] = $row['tname'];
		}

		$this->addItem($info);
		$this->output();
	}
	/**
	 * 各大赛区名
	 * @name getAreaInfo
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info array 各大赛区内容信息
	 */
	public function getAreaInfo()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "area WHERE 1";
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[$row['aid']] = $row['aname'];
		}

		$this->addItem($info);
		$this->output();
	}
	/**
	 * 各大赛区院校名
	 * @name getCollegeInfo
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info array 各大赛区内容信息
	 */
	public function getCollegeInfo()
	{
		$aid = urldecode($this->input['aid']);
		$sql = "SELECT * FROM " . DB_PREFIX . "college WHERE aid = '" . $aid . "'";
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[$row['cid']]['cid'] = $row['cid'];
			$info[$row['cid']]['cname'] = $row['cname'];
		}

		$this->addItem($info);
		$this->output();
	}
	/**
	 * 获取参赛作品顺序
	 * @name getOpusInfo
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info array 获取参赛作品顺序
	 */
	public function getOpusInfo()
	{
		$aid = urldecode($this->input['aid']);
		$cid = urldecode($this->input['cid']);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "opus WHERE aid = '" . $aid . "' AND cid = '" . $cid . "' ORDER BY opus_order DESC";
		$info = $this->db->query_first($sql);
	
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}

}

$out = new competitionApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>