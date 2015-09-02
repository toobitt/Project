<?php
require_once('global.php');
define('MOD_UNIQUEID', 'templates');
define('SCRIPT_NAME', 'templates');
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
class templates extends adminBase
{
	private $curd = null;
	public function __construct()
	{
		parent::__construct();
		$this->curd = new curd('templates');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show(){
		
		$data = $this->curd->show('*', $this->get_conditions(), ' order by order_id desc ');
		
		if($data)
		{
			$sort_id = array();
			foreach($data as $key=>$val)
			{
				$sort_id[] = $val['sort_id'];
			}
			if($sort_id)
			{
				$sql = 'SELECT id,name FROM ' . DB_PREFIX . 'template_sort WHERE id IN('.implode(',', $sort_id).')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$sort_info[$row['id']] = $row['name'];
				}
			}
			//$data = unserialize_template_record($data);
			foreach ($data as $key=>$val)
			{
				$val = unserialize_template_record($val);
				$val['sort_name'] = $sort_info[$val['sort_id']];
				if(!$val['index_pic'])
				{
					$val['index_pic'] = $val['video_preview'];
				}
				$this->addItem($val);
			}
		}
		$this->output();
		
	}
	public function detail()
	{
		require_once(CUR_CONF_PATH . 'lib/template.class.php');
		$template = new template();
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput("无效记录");
		}
		$data = $template->get_template_info_by_id($id);
		if($data)
		{
			$data = unserialize_template_record($data);
			if(!$data['index_pic'])
			{
				$data['index_pic'] = $data['video_preview'];
			}
		}
		$this->addItem($data);
		$this->output();
	}

	public function get_transcode_progress()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput("未知的视频");
		}
		
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$mediaserver = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$ret = array();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'attach where attach_id IN('.$id.')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$mediaserver->initPostData();
			$mediaserver->addRequestData('id',$row['attach_id']);
			$mediaserver->addRequestData('host',$row['host']);
			$mediaserver->addRequestData('port',$row['port']);
			$mediaserver->addRequestData('a' ,'get_transcode_status');
			$ret[$row['video']] = $mediaserver->request('video_transcode.php');
		}
		$output = array();
		foreach(explode(',',$id) as $val)
		{
			if(!$ret[$id])
			{
				$output[$id] = '100';
			}
			else
			{
				$output[$id] = ($ret[$id]['return'] == 'success') ? $ret[$id]['transcode_percent'] : "0";
			}
		}
		$this->addItem($output);
		$this->output();
	}
	public function count()
	{
		$total = $this->curd->count();
		exit(json_encode($total));
	}
	public function get_conditions()
	{
		$condition = '';
		if($this->input['key'])
		{
			$condition .= ' AND title like "%'.$this->input['key'].'%"';
		}
	
		if($this->input['_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX	. "template_sort WHERE id = " . intval($this->input['_id']);
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  sort_id in (" . $ret['childs'] . ")";
		}
		
		if(isset($this->input['template_status']) && $this->input['template_status']!=-2)
		{
			$condition .= ' AND status='.intval($this->input['template_status']);
		}
		if ($this->input['start_time'] == $this->input['end_time']) {
            $his = date('His', strtotime($this->input['start_time']));
            if (! intval($his)) {
                $this->input['start_time'] = date('Y-m-d', strtotime($this->input['start_time'])). ' 00:00';
                $this->input['end_time'] = date('Y-m-d', strtotime($this->input['end_time'])). ' 23:59';
            }
        }
        //查询创建的起始时间
        if($this->input['start_time'])
        {
        	$start_time = strtotime($this->input['start_time']);
            $condition .= " AND create_time > " . $start_time;
        }

        //查询创建的结束时间
        if($this->input['end_time'])
        {
        	$end_time = strtotime($this->input['end_time']);
            $condition .= " AND create_time < " . $end_time;
            $start_time > $end_time && $this->errorOutput('搜索开始时间不能大于结束时间');
        }
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
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
}
include ROOT_PATH . 'excute.php';
?>