<?php
define('MOD_UNIQUEID', 'webvod');
require('global.php');
class webvodApi extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'	=>'管理',
		'_node'=>array(
			'name'=>'CUTV',
			'filename'=>'webvod_sort.php',
			'node_uniqueid'=>'webvod_sort',
			),
		);
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/webvod.class.php');
		$this->obj = new webvod();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);
		$this->addItem($ret);	
		$this->output();	
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'webvod WHERE 1 '.$this->get_condition();
		$webvod_total = $this->db->query_first($sql);
		echo json_encode($webvod_total);	
	}
	
	public  function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$sql = "SELECT *  FROM ".DB_PREFIX."webvod  WHERE program_id = '".intval($this->input['id'])."'";
		$return = $this->db->query_first($sql);
		$return['id'] = $return['program_id'];
		if (!$return)
		{
			$this->errorOutput('视频不存在或已被删除');
		}
		$return['format_duration'] = hg_timeFormatChinese($return['duration']*1000);//时长
		$return['bitrate'] = $return['bitrate'].'kbps';//码流

		$sql_ = "SELECT is_now,id
				 FROM  " . DB_PREFIX ."webvodpic
				 WHERE program_id = ".intval($this->input['id']);
		$q = $this->db->query($sql_);
		while($ro = $this->db->fetch_array($q))
		{
			if($ro['is_now'])
			{
				$flag = '1';			
			}
		}
		if(!$flag)
		{
			$index = $this->db->query_first($sql_);
			$return['indexpic'] = $index['id'];
		}
		$this->addItem($return);
		$this->output();

	}
	
	
	/*参数:WEB视频的id
	 *功能:进入编辑页面时显示该视频的一些基本信息
	 *返回值:$vod_result(数组)
	 **/
	public function recommend()
	{
		if($this->mNeedCheckIn && !$this->prms['update'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."webvod  WHERE program_id IN( " . $this->input['id'] . ")"; 
		$qs = $this->db->query($sql);
		while($vod_result = $this->db->fetch_array($qs))
		{
			
			$vod_result['column_id'] = unserialize($vod_result['column_id']);
			if(is_array($vod_result['column_id']) && $vod_result['column_id'])
			{
				$column_id = array();
				foreach($vod_result['column_id'] as $k => $v)
				{
					$column_id[] = $k;
				}
				$column_id = implode(',',$column_id);
				$vod_result['column_id'] = $column_id;
			}
			
			if($vod_result['duration'])
			{
				$vod_result['video_duration'] = hg_timeFormatChinese($vod_result['duration']*1000);//时长
			}
			else 
			{
				$vod_result['video_duration'] = '无';
			}
			$vod_result['time'] = TIMENOW;
			if($vod_result['status'] == 2)
			{
				$vod_result['pubstatus'] = 1;
			}
			else 
			{
				$vod_result['pubstatus'] = 0;
			}
			
			if($this->input['indexpic'])
			{
				$sql = "SELECT * FROM ".DB_PREFIX."webvodpic  WHERE id = ".$this->input['indexpic']; 
				$index = $this->db->query_first($sql);
				require_once(ROOT_PATH . 'lib/class/material.class.php');
				$this->ma = new material();
				$materials = $this->ma->localMaterial($index['url'],$this->input['id']);
				if($materials)
				{
					$arr = array(
						'host'			=>	$materials[0]['host'],
						'dir'			=>	$materials[0]['dir'],
						'filepath'		=>	$materials[0]['filepath'],
						'filename'		=>	$materials[0]['filename'],
					);
					$indexpic =	serialize($arr);
				}
				$sql_ = "UPDATE " . DB_PREFIX ."webvodpic SET is_now = 1,indexpic = '". addslashes($indexpic) ."' WHERE program_id = " .$this->input['id'] ." AND id = " .$this->input['indexpic'];
				$this->db->query($sql_);
			}
			$vod_result['pub_time']       = $vod_result['pub_time'] ? date("Y-m-d H:i", $vod_result['pub_time']) : date("Y-m-d H:i", TIMENOW);
			$this->addItem($vod_result);
		}
		$this->output();
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function get_condition()
	{		
		$condition = '';
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			if($this->user['prms']['app_prms']['webvod']['nodes'])
			{
				$cpids = '';
				$cpid_arr = $this->user['prms']['app_prms']['webvod']['nodes'];
				$cpids = implode(',',$cpid_arr);
			}
			$condition .= " AND cpid in  (".$cpids.")";	
		}
		//查询应用分组
		if($this->input['_id'])
		{
			$condition .=" AND cpid = ".$this->input['_id'];
		}
		else
		{
			if($this->input['para'])
			{
				$condition .=" AND category_id = ".$this->input['para'];
			}
		}
		
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim($this->input['k']).'%"';
		}
		return $condition;
	}
	
	public function  change_indexpic()
	{	
		$sql = "UPDATE " . DB_PREFIX ."webvodpic SET is_now = 0 WHERE program_id = " .$this->input['program_id'];
		$this->db->query($sql);	
		
		$sqll = "SELECT url FROM  " . DB_PREFIX ."webvodpic WHERE id = " .$this->input['pic_id'];
		$url = $this->db->query_first($sqll);	
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->ma = new material();
		$materials = $this->ma->localMaterial($url['url'],$this->input['program_id']);
		if($materials)
		{
			$arr = array(
				'host'			=>	$materials[0]['host'],
				'dir'			=>	$materials[0]['dir'],
				'filepath'		=>	$materials[0]['filepath'],
				'filename'		=>	$materials[0]['filename'],
			);
			$indexpic =	serialize($arr);
		}
		$sql_ = "UPDATE " . DB_PREFIX ."webvodpic SET is_now = 1,indexpic = '". addslashes($indexpic) ."' WHERE program_id = " .$this->input['program_id'] ." AND id = " .$this->input['pic_id'];
		$this->db->query($sql_);
			
		$this->addItem('success');
		$this->output();
	}
	
	function detail()
	{	
		$sql = 'SELECT * FROM '.DB_PREFIX.'webvod WHERE program_id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		
        $column_id = unserialize($r['column_id'])?unserialize($r['column_id']):array();
        $r['pub_time']       = $r['pub_time'] ? date("Y-m-d H:i", $r['pub_time']) : date("Y-m-d H:i", TIMENOW);
        if (is_array($column_id))
        {
        	$r['column_id'] = implode(',', array_keys($column_id));
        }
		$this->addItem($r);
		$this->output();
	}
	function index()
	{	
	}
}

$out = new webvodApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
