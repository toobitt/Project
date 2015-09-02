<?php
define('MOD_UNIQUEID','xml');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/xml_mode.php');
class xml extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new xml_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		/*
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$v['create_time'] = date('Y-m-d H:i',$v['create_time']);
			}
			$this->addItem($ret);
			$this->output();
		}
		*/
		if($this->input['trigger_mod_uniqueid'] != 'xml')
		{
			$condition = " AND is_open = 1 ";
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "xml_type WHERE 1" .$condition;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['id'] = '_'.$row['id'];
			$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			$row['is_group'] = '1';
			$out[] = $row;
		}
		$sql = "SELECT id,title,is_open,user_name,create_time FROM " .DB_PREFIX. "xml WHERE type_id = 0" .$condition;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['is_group'] = '0';
			$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			$out[] = $row;
		}
		$this->addItem($out);
		$this->output();
	}
	/*
	 * 获取分类下模板数据
	 */
	public function show_xml()
	{
		$type_id = trim($this->input['id'],'_');
		$sql = "SELECT * FROM " .DB_PREFIX. "xml WHERE type_id = " .$type_id;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			$re[] = $row;
		}
		$this->addItem($re);
		$this->output();
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
			$id = trim($this->input['id']);
			if(substr($id,0,1) == '_')
			{
				$condition .= " AND type_id IN (".trim($id,'_').")";
			}
			else
			{
				$condition .= " AND id IN (".$id.")";
			}
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
		if($this->input['type_id'])
		{
			$condition .= " AND type_id = '".intval($this->input['type_id'])."'";
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
		
		//如果别的地方调用数据,只输出"已审核"数据
		if($this->input['trigger_mod_uniqueid'] != 'xml')
		{
			$condition .= " AND is_open = 1 ";
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
	 * 模板编辑页预览功能(预览根据该模板替换后的xml内容)
	 */
	public function preview()
	{
		//接收参数
		$xml = preg_replace("/[\r\n]+/", '<br/>', trim(html_entity_decode(stripcslashes($this->input['xml_str'])),'"'));
		if(!$xml)
		{
			$this->errorOutput('缺少参数xml_str');
		}
		
		//取一条视频数据
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
		$vodcurl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
		$vodcurl->setSubmitType('post');
		$vodcurl->initPostData();
		//$vodcurl->addRequestData($k, $v);
		$vodcurl->addRequestData('html', 'true');
		$vodcurl->addRequestData('offset', '0');
		$vodcurl->addRequestData('count', '1');
		$vodcurl->addRequestData('a', 'show');
		$ret = $vodcurl->request('vod.php');
		$vodinfo = $ret[0];
		//生成预览数据
		foreach((array)$vodinfo as $k => $v)
		{
			$map['{$'.$k.'}'] = $v;
		}
		foreach((array)$map as $k => $v)
		{
			$xml = str_replace($k,$v,$xml);
		}
		//返回
		$this->addItem(array('xml_str' => str_ireplace('<br/>', "\n", $xml)));
		$this->output();
	}
}

$out = new xml();
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