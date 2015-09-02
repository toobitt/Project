<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|getBitrate|getBeiboUrl|detail|count
* @private function get_condition
* 
* $Id: stream.php 
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class streamApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include curl.class.php
	 */
	private $curl;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['vodapi']['host'],$this->settings['vodapi']['dir']);
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 频道信号列表显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @return $row array 所有频道信号内容信息
	 */
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "select * from " . DB_PREFIX . "stream ";		
		$sql .= ' where 1 ' . $condition . " ORDER BY id DESC " . $data_limit;
		$q = $this->db->query($sql);

		$this->setXmlNode('stream' , 'info');
		while($row = $this->db->fetch_array($q))
		{
			$row['uri'] = unserialize($row['uri']);
			$row['other_info'] = unserialize($row['other_info']);
			foreach($row['other_info'] as $key => $value)
			{
				$row['out_uri'][$value['name']] = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $row['ch_name'], 'stream_name' => $value['name']), 'channels', 'http://');
				$row['stream_name'][] = $value['name'];
				
			}
			if(!$row['s_status'])
			{
				$row['s_status'] = "已停止";
			}
			else 
			{
				$row['s_status'] = "已启动";
			}
			$this->addItem($row);
			//hg_pre($row);
		}
		$this->output();
		
	}
	
	/**
	 * 获取码流
	 * @name getBitrate
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $uri string 流地址
	 * @param $stream_id int 信号流ID
	 * @return $ret array 该信号信息(信号流ID，流地址，码流)
	 */
	public function getBitrate()
	{
		$uri = $this->input['uri'];
		if(!$uri)
		{
			$this->errorOutput('流地址不存在');
		}
		$stream_id = intval($this->input['stream_id']);
		if(!$stream_id)
		{
			$this->errorOutput('流ID不存在');
		}
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('auth',$this->settings['vodapi']['token']);
		$this->curl->addRequestData('stream',urldecode($uri));
		$this->curl->addRequestData('stream_id',$stream_id);
		$ret = $this->curl->request('get_bitrate.php');
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 获取备播文件地址
	 * @name getBeiboUrl
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $stream_beibourl string json串 
	 */
	public function getBeiboUrl()
	{
		$sql = "select id,title,newname from " . DB_PREFIX . "backup WHERE 1";
		$q = $this->db->query($sql);
		$stream_beibourl =  array();
		while($row = $this->db->fetch_array($q))
		{
			$stream_beibourl[$row['id']]['title'] = $row['title'];
			$stream_beibourl[$row['id']]['uri'] = UPLOAD_BACKUP_MMS_URL.$row['newname'];
		}
		echo json_encode($stream_beibourl);
	}
	
	/**
	 * 取单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道信号ID
	 * @return $row array 单条频道信号信息
	 */
	function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in(' . $id .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "stream " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('stream' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['other_info'] = unserialize($row['other_info']);
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('信号流不存在');	
		} 	
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $ret string 总数，json串
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stream AS v WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$ret = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($ret);
	}
		
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and s_name like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if(isset($this->input['s_status']))
		{
			$condition .= ' AND s_status=' . intval($this->input['s_status']);
		}
		return $condition;
	}
}

$out = new streamApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>