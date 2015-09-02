<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|getUriname|show_channel_info|detail|count|publish
* @private function get_condition
* 
* $Id: channel.php 9788 2012-08-23 08:11:16Z lijiaying $
***************************************************************************/
require('global.php');
class channelApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include channel.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/channel.class.php');
		$this->obj = new channels();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 频道列表显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @param $info array 频道的核心数据 
	 * @return $v array 所有频道内容信息
	 */
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$info = $this->obj->channelsInfo($condition,$offset,$count);
		if ($info)
		{
			foreach ($info as $v)
			{
				$v['ch_snap'] = MMS_CONTROL_LIST_PREVIEWIMG_URL . $v['ch_id'] . '/' . $v['main_stream_name'] . '/' . (TIMENOW*1000) . '/172x130.png';
				foreach ($v['streams'] AS $kk => $vv)
				{
					$v['stream_uri'][$vv['name']] = $vv['stream_uri'];
					$v['out_streams'][$vv['out_stream_name']] = $vv['uri'];
				}
				$this->addItem($v);
			//	hg_pre($v);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取主信号流名称
	 * @name getUriname
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 信号ID
	 * @return $uri_info string json串
	 */
	public function getUriname()
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$sql = "SELECT other_info FROM " . DB_PREFIX . "stream WHERE id=" . $id;
			$info = $this->db->query_first($sql);
		}
		
		$stream_name = unserialize($info['other_info']);
		$uri_info = array();
		foreach($stream_name as $key => $value)
		{
			$uri_info[$value['name']] = $value['uri'];
		}
		echo json_encode($uri_info);
	}
	
	/**
	 * 调出频道信息操作界面
	 * @name show_channel_info
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $row array 单条频道所涉及信息
	 */
	public function show_channel_info()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$this->detail();
	}
	
	/**
	 * 单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $row array 单条频道信息
	 */
	public function detail()
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
		$sql = "SELECT * FROM " . DB_PREFIX . "channel " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('channel' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			//串联单的模块id
			$row['relate_module_id'] = intval($this->input['relate_module_id']);
			
			$row['beibo'] = $row['beibo'] ? unserialize($row['beibo']) : array();
			//流信息
			$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . $id . ")";
			$f = $this->db->query($sql);
			$row['stream_uri'] = $row['out_streams'] = $row['out_streams_uri'] = $row['ts_uri'] = $row['out_stream_name'] = $row['stream_name'] = array();
			while($r = $this->db->fetch_array($f))
			{
				$row['stream_uri'][$r['stream_name']] =  hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $row['stream_mark'], 'stream_name' => $r['stream_name']));
				$row['out_streams'][$r['out_stream_name']] =  hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $row['code'], 'stream_name' => $r['out_stream_name']));
				$row['out_streams_uri'][] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $row['code'], 'stream_name' => $r['out_stream_name']));
				$row['ts_uri'][$r['out_stream_name']] =  hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $row['code'], 'stream_name' => $r['out_stream_name']), 'channels', 'http://', 'm3u8:');
				$row['out_stream_name'][] = $r['out_stream_name'];
				$row['stream_name'][] = $r['stream_name'];
				$row['count'] = count($row['stream_name']);
			}
			//信号流信息
			if($row['stream_id'])
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id=" .$row['stream_id'];
				$q = $this->db->query_first($sql);
				$other_info = unserialize($q['other_info']);
				if($other_info)
				{
					$row['stream_name_all'] = array();
					foreach($other_info as $key => $value)
					{
						$row['stream_name_all'][] = $value['name'];
					}
				}
				//logo显示
				$row['logo_info'] = unserialize($row['logo_info']);
				$row['logo'] = $row['logo_info']['filename'];
				if ($row['logo_info'])
				{
					$row['logo_url'] = hg_material_link($this->settings['material_server']['img4']['host'], $this->settings['material_server']['img4']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename']);
				}
			}
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('频道不存在');	
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "channel AS v WHERE 1 ";

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
			$condition .= ' and name like \'%'.urldecode($this->input['k']).'%\'';
		}
		if($this->input['id'])
		{
			$condition .= ' AND c.id IN('.trim(urldecode($this->input['id'])).')';
		}
		if(isset($this->input['stream_state']))
		{
			$condition .= ' AND stream_state=' . intval($this->input['stream_state']);
		}
		return $condition;
	}
	
	/**
	 * 发布操作
	 * @name publish
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param id int 频道ID
	 * @return $row array 单条频道所涉及信息
	 */
	public function publish()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$this->detail();
	}
}

$out = new channelApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>