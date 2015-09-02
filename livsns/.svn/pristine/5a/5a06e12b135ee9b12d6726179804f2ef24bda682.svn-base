<?php
define('MOD_UNIQUEID','vod');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/vod_mode.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class vod extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new vod_mode();
		$this->vodcurl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		//搜索标签
        if ($this->input['searchtag_id']) {
            $searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
            foreach ((array)$searchtag['tag_val'] as $k => $v) {
                $this->input[$k] = $v;
            }
        }
		$condition = array(
			'id' 			=> $this->input['id'],
			'k' 				=> $this->input['k'],
			'user_name' 		=> $this->input['user_name'],
			'user_id' 		=> $this->input['user_id'],
			'title' 			=> $this->input['title'],
			//'status' 		=> '2',
			'is_forcecode' 	=> $this->input['is_forcecode'],
			'start_time' 	=> $this->input['start_time'],
			'end_time' 		=> $this->input['end_time'],
			'start_weight' 	=> $this->input['start_weight'],
			'end_weight' 	=> $this->input['end_weight'],
			'date_search' 	=> $this->input['date_search'],
			'end_weight' 	=> $this->input['end_weight'],
			'end_weight' 	=> $this->input['end_weight'],
			'end_weight' 	=> $this->input['end_weight'],
			//'trans_status' 	=> $this->input['trans_status'],
			'pub_column_id'	=> $this->input['pub_column_id'],
			'vod_sort_id'	=> $this->input['_id'],
			'offset'			=> $this->input['offset'] ? $this->input['offset'] : 0,
			'count'			=> $this->input['count'] ? intval($this->input['count']) : 20,
		);
		$this->vodcurl->setSubmitType('post');
		$this->vodcurl->initPostData();
		$this->vodcurl->addRequestData('_id', $this->input['_id']);
		foreach((array)$condition as $k => $v)
		{
			$this->vodcurl->addRequestData($k, $v);
		}
		$this->vodcurl->addRequestData('html', 'true');
		$this->vodcurl->addRequestData('a', 'show');
		$ret = $this->vodcurl->request('admin/vod.php');
		//查出已导数据
		$sql = "SELECT * FROM " .DB_PREFIX. "export";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$export[$row['vod_id']] = array(
				'export_dir' => $row['dir'],
				'need_file'	 => $row['need_file'],
			);
		}
		if(!empty($ret))
		{
			foreach((array)$ret as $k => $v)
			{
				if($export[$v['id']])
				{
					$v['export_dir'] = $export[$v['id']]['export_dir'];
					$v['need_file'] = $export[$v['id']]['need_file'];
					$v['is_export'] = 1;
				}
				$v['video_m3u8'] = $v['hostwork'].'/'.$v['video_path'].$v['video_filename'].'.m3u8';
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$this->vodcurl->setSubmitType('post');
		$this->vodcurl->initPostData();
		$this->vodcurl->addRequestData('html', 'true');
		$this->vodcurl->addRequestData('a', 'count');
		$ret = $this->vodcurl->request('vod.php');
		echo json_encode($ret[0]);
	}
	
	public function get_condition()
	{}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	/*
	 * 获取视频信息
	 */
	public function getvodinfo($id = '')
	{
		$this->vodcurl->setSubmitType('post');
		$this->vodcurl->initPostData();
		$this->vodcurl->addRequestData('id',$id);
		$this->vodcurl->addRequestData('html', 'true');
		$this->vodcurl->addRequestData('a', 'get_videos');
		$ret = $this->vodcurl->request('vod.php');
		return $ret;
	}
	
	/*
	 * 获取xml模板
	 */
	public function getxmlinfo($id = '', $type_id = '')
	{
		if($id)
		{
			$condition = " AND id IN (" .$id. ")";
		}
		if($type_id)
		{
			$condition = " AND type_id IN (" .$type_id. ")";
		}
		$sql = "SELECT id,type_id,content FROM " .DB_PREFIX. "xml WHERE 1 " . $condition;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$re[$row['type_id']][] = $row;
		}
		return $re;
	}
	
	/*
	 * 导出视频到XML
	 */
	public function xmlExport()
	{
		$vod_id = $this->input['vod_id']; //视频id
		$xml_id = $this->input['xml_id']; //xml模板id
		$xml_sort_id = $this->input['xml_sort_id']; //xml模板类别id
		$need_file = $this->input['need_file'] ? 1 : 0;	//是否需要文件
		if(!$xml_id && !$xml_sort_id)
		{
			$this->errorOutput('请选择模板');
		}
		//判断要导出的视频条数是否超过了限制
		$export_count = $this->settings['export_count']; //每次允许导出条数
		$vod_count = count(explode(',', $vod_id));
		if($vod_count > $export_count)
		{
			$this->errorOutput('导出视频数目超过限制');
		}
		//得到xml内容
		$xmlinfo = $this->getxmlinfo($xml_id, $xml_sort_id);
		//判断是否已经提交过了
		$sql = "SELECT vod_id FROM " .DB_PREFIX. "export";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$export[] = $row['vod_id'];
		}
		//提取视频信息到指定目录
		$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('id', $vod_id);
		$curl->addRequestData('xmlinfo', serialize($xmlinfo));
		$curl->addRequestData('xml_sort_id', $xml_sort_id ? $xml_sort_id : '0');
		$curl->addRequestData('xml_id', $xml_id);
		$curl->addRequestData('need_file', 1);
		$curl->addRequestData('html', 'true');
		$ret = $curl->request('xml.php');
		if($ret)
		{
			//将已经提交过的信息记录下来
			$insert = $ret[0];
			$sql = " INSERT INTO " . DB_PREFIX . "export(vod_id,dir,need_file) VALUES";
			foreach ($insert AS $k => $v)
			{
				$sql .= "('{$k}','{$v}','{$need_file}'),";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
		$this->addItem($insert);
		$this->output();
	}
}

$out = new vod();
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