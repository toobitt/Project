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
		
		
		switch($this->input['orderby'])
		{
			case "create_desc" : 
				{
					$orderby = ' order by id desc ';
					break;
				}
			case "record_desc" :	
				{
					$orderby = ' order by record desc ';
					break;
				}
			case "record_asc" :	
				{
					$orderby = ' order by record asc ';
					break;
				}
			case "create_asc" :	
				{
					$orderby = ' order by id asc ';
					break;
				}
			case "weight_desc" : 
				{
					$orderby = ' order by weight desc ';
					break;
				}
			case "orderid_asc":
			default:
				{
					$orderby = ' order by order_id desc ';
					break;
				}
		}
		
		$data = $this->curd->show('*', $this->get_conditions(), $orderby);
		
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
			foreach ($data as $key=>$val)
			{
				if(!$val['index_pic'])
				{
					$val['index_pic'] = $val['video_preview'];
				}
				$val['sort_name'] = $sort_info[$val['sort_id']];
				$val = unserialize_template_record($val);
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
		}
		$this->addItem($data);
		$this->output();
	}

	public function get_transcode_progress()
	{
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$id = $this->input['id'];
		$mediaserver = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$mediaserver->initPostData();
		$mediaserver->addRequestData('id',$id);
		$mediaserver->addRequestData('a' ,'get_transcode_status');
		$ret = $mediaserver->request('video_transcode.php');
		print_r($ret);
	}
	public function count()
	{
		$total = $this->curd->count($this->get_conditions());
		exit(json_encode($total));
	}
	public function get_conditions()
	{
		$condition = ' AND status = 2 ';
		if($this->input['key'])
		{
			$condition .= ' AND title like "%'.$this->input['key'].'%"';
		}
		if($this->input['keywords'])
		{
			$condition .= ' AND keywords like "%'.$this->input['keywords'].'%"';
		}
		if($this->input['weight'])
		{
			$condition .= ' AND weight >= ' . intval($this->input['weight']);
		}
		if($this->input['color'])
		{
			$condition .= ' AND `color`="'.addslashes(urldecode($this->input['color'])).'"';
		}
		if($this->input['sort_id'])
		{
			$condition .= ' AND sort_id="'.$this->input['sort_id'].'"';
		}
		if($this->input['use'])
		{
			$condition .= ' AND `use`="'.addslashes(urldecode($this->input['use'])).'"';
		}
		if($this->input['style'])
		{
			$condition .= ' AND `style`="'.addslashes(urldecode($this->input['style'])).'"';
		}
		if($this->input['version'])
		{
			$condition .= ' AND `version`="'.addslashes(urldecode($this->input['version'])).'"';
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
	public function get_settings()
	{
		$this->curd->set_table('template_config');
		$data = $this->curd->show(' `type`,`key`,`value` ', ' and status = 1 ');
		$output = array();
		foreach($data as $val)
		{
			$output[$val['type']][$val['key']] = $val['value'];
		}
		$this->addItem($output);
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';
?>