<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: advert.php 6131 2012-03-21 01:50:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class advertApi extends BaseFrm
{
	private $mVideo;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 查询广告信息
	 * @return $info 广告信息
	 */
	function show(){
		
		$count = $this->input['count']? $this->input['count']:0;
		$page = $this->input['page']? $this->input['page']:0;
		if($count)
		{
			$offset = $count*$page;
			$end = " LIMIT $offset,$count";
		}
		$condition = $this->get_condition();
		$sql = "SELECT * FROM  ".DB_PREFIX."advertising where 1 " . $condition . $end;
		$q = $this->db->query($sql);
		
		$this->create_record();
		$this->setXmlNode('user','info');
		while($row = $this->db->fetch_array($q))
		{
			//$advert[] = $row;
			$row['create_time'] = date("Y-m-d H:i:s",$row['create_time']);
		//	$row['content'] = ($row['content']);
			$row['audit'] = $row['state'];
			$this->addItem($row);
		}
		$this->output();
	}
	
/**
	 * 获取搜索条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND mark LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND create_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND create_time < " . strtotime($this->input['end_time']);	
		}
		
		$orders = array('id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (!in_array($this->input['hgorder'], $orders))
		{
			$this->input['hgorder'] = 'create_time';
		}
		
		$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		return $condition . $orderby;
	}

	public function count()
	{	
		$sql = "SELECT count(*) as total FROM  ".DB_PREFIX."advertising";

		//获取查询条件
		$r = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($r);
	}
	
	/**
	 * 查询广告信息
	 * @return $info 广告信息
	 */
	function detail(){
		$sql = "SELECT * FROM  ".DB_PREFIX."advertising WHERE id=" . intval($this->input['id']);
		$q = $this->db->query_first($sql);
		$this->addItem($q);
		$this->output();
	}
	
	
	function create_record()
	{
		$mark = $this->input['mark']? urldecode($this->input['mark']):0;
		if($mark)//当$mark存在的时候 默认生成单一的广告文件
		{
			$sql = "SELECT * FROM  ".DB_PREFIX."advertising WHERE mark ='".$mark."' ORDER BY create_time DESC";
			$f = $this->db->query_first($sql);
			if($f && is_array($f))
			{
				$htmls = '<?php $advert = array(';
				$child = '';
				$c_s = '';
				$filename = '';
				foreach($f as $k => $v)
				{
					$child .= $c_s."'".$k."'=>'".$v."'";
					$c_s = ',';
					if($k == 'mark')
					{
						$filename = $v;
					}
				}
				$htmls .= $child.');'.' ?>';
				if(!is_file(ROOT_PATH."cache/".$filename.".php"))
				{
					file_put_contents(ROOT_PATH."cache/".$filename.".php", $htmls);
				}
			}
			else 
			{
				$htmls = '<?php $advert = array(); ?>';
				if(!is_file(ROOT_PATH."cache/".$mark.".php"))
				{
					file_put_contents(ROOT_PATH."cache/".$mark.".php", $htmls);
				}
			}
		}
		else 
		{
			$sql = "SELECT * FROM  ".DB_PREFIX."advertising ";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$htmls = '<?php $advert = array(';
				$child = '';
				$c_s = '';
				$filename = '';
				foreach($row as $k => $v)
				{
					$child .= $c_s."'".$k."'=>'".$v."'";
					$c_s = ',';
					if($k == 'mark')
					{
						$filename = $v;
					}
				}
				$htmls .= $child.');'.' ?>';
				if(!is_file(ROOT_PATH."cache/".$filename.".php"))
				{
					file_put_contents(ROOT_PATH."cache/".$filename.".php", $htmls);
				}
			}
		}
	}
	
	function get(){
		$mark = $this->input['mark']? urldecode($this->input['mark']):0;
		if(!is_file(ROOT_PATH."cache/".$mark.".php"))
		{
			$this->create_record();
			include(ROOT_PATH."cache/".$mark.".php");
		}
		else 
		{
			include(ROOT_PATH."cache/".$mark.".php");
			if(!$advert['content'] || !is_array($advert))
			{
				unlink(ROOT_PATH."cache/".$mark.".php");
				$this->create_record();
				include(ROOT_PATH."cache/".$mark.".php");
			}
		}
		$this->setXmlNode('advert','info');
		$this->addItem($advert);
		$this->output();
	}
}

$out = new advertApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>