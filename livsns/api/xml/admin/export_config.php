<?php
define('MOD_UNIQUEID','export_config');
require_once('global.php');
//require_once(CUR_CONF_PATH . 'lib/xml_mode.php');
class export_config extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		//$this->mode = new xml_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = "SELECT * FROM " . DB_PREFIX . "export_config  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['config'] = unserialize($r['config']);
			foreach((array)$r['config'] as $ke => $va)
			{
				$r[$ke] = $va;
			}
			$info[] = $r;
		}
		foreach((array)$info as $k => $v)
		{
			
		}
		if(!empty($info))
		{
			foreach($info as $k => $v)
			{
				unset($v['config']);
				$v['create_time'] = date('Y-m-d H:i',$v['create_time']);
				$v['vod_sort_name'] = $this->get_sort_name($v['vod_sort_id']);
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		if ($this->input['status'])
        {
            $condition .= " AND status IN (" . ($this->input['status']) . ")";
        }
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND weight <= " . $this->input['end_weight'];
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				//$ret['content'] = htmlentities($ret['content']);
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	/*
	 * 获取分类id与类别名称的对应关系
	 */
	public function get_sort_name($vod_sort_id = '')
	{
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->vodcurl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
		$this->vodcurl->setSubmitType('post');
		$this->vodcurl->initPostData();
		$this->vodcurl->addRequestData('html', 'true');
		$this->vodcurl->addRequestData('id', $vod_sort_id);
		$this->vodcurl->addRequestData('a', 'getSelectedNodes');
		$ret = $this->vodcurl->request('admin/vod_media_node.php');
		$str = '';
		
		foreach((array)$ret[0] as $k => $v)
		{
			$str .= $v . ',';
		}
		$str = trim($str,',');
		return $str;
	}
	
	/*
	 * 获取导出进度
	 */
	public function get_percent()
	{
		$sql = "SELECT percent FROM " .DB_PREFIX. "export_config WHERE is_default = 1";
		$q = $this->db->query_first($sql);
		$this->addItem($q['percent']);
		$this->output();
	}
	
	/*
	 * 模板编辑页预览功能(预览根据该模板替换后的xml内容)
	 */
	public function preview()
	{
		//接收参数
		//$xml = preg_replace("/[\r\n]+/", '<br/>', trim(html_entity_decode(stripcslashes($this->input['xml_str'])),'"'));
		$config_id = $this->input['config_id']; //配置id
		if(!$config_id)
		{
			$this->errorOutput('缺少配置id');
		}
		//取配置参数
		$sql = "SELECT * FROM " . DB_PREFIX . "export_config  WHERE id = " . $config_id;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['config'] = unserialize($r['config']);
			foreach((array)$r['config'] as $ke => $va)
			{
				$r[$ke] = $va;
			}
			$info[] = $r;
		}
		//取模板数据
		$xmlinfo = $this->getxmlinfo($config['xml_id']);
		
		//取一条视频数据 (符合条件的)
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
		$vodcurl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
		$vodcurl->setSubmitType('post');
		$vodcurl->initPostData();
		foreach((array)$info as $k => $v)
		{
			$vodcurl->addRequestData($k, $v);
		}
		$vodcurl->addRequestData('html', 'true');
		$vodcurl->addRequestData('offset', '0');
		$vodcurl->addRequestData('count', '1');
		$vodcurl->addRequestData('a', 'show');
		$ret = $vodcurl->request('vod.php');
		$vodinfo = $ret[0];
		if(!$vodinfo)
		{
			$this->errorOutput('没有符合条件的视频数据');
		}
		
		//生成预览数据
		foreach((array)$vodinfo as $k => $v)
		{
			$map['{$'.$k.'}'] = $v;
		}
		foreach((array)$xmlinfo as $ke => $va)
		{
			//$xml = $xmlstr;	//重新赋值
			$xml = $va['content'];
			foreach((array)$map as $k => $v)
			{
				$xml = str_replace($k,$v,$xml);
			}
			$xmlstr[] = str_ireplace('<br/>', "\n", $xml);
		}
		
		//返回
		$this->addItem($xmlstr);
		$this->output();
	}
	
	/*
	 * 获取xml模板
	 */
	public function getxmlinfo($id = '')
	{
		$sql = "SELECT id,content FROM " .DB_PREFIX. "xml WHERE id IN (" .$id. ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$xml[] =array(
				'id' => $row['id'],
				'content' => $row['content'],
			);
		}
		return $xml;
	}
}

$out = new export_config();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>