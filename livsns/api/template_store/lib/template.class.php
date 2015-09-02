<?php
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
class template extends InitFrm
{
	protected $curd;
	public function __construct($table = '')
	{
		parent::__construct();
		$this->curd = new curd('templates');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function get_template_info_by_id($id = 0)
	{
		$data = array();
		if($id)
		{
			$data = $this->curd->detail($id);
			if($data['sort_id'])
			{
				$sql = 'SELECT name FROM ' . DB_PREFIX . 'template_sort WHERE id = '.$data['sort_id'];
				$sort_info = $this->db->query_first($sql);
				$data['sort_name'] = $sort_info['name'];
			}
			$data['create_time_format'] = date('Y-m-d H:i', $data['create_time']);
			if($data['video'])
			{
				$data['vodinfo'] = $this->get_video($data['video']);
			}
			$in = '';
			if($data['color'])
			{
				$in .= '"'.$data['color'].'",';
			}
			if($data['use'])
			{
				$in .= '"'.$data['use'].'",';
			}
			if($data['style'])
			{
				$in .= '"'.$data['style'].'",';
			}
			if($data['version'])
			{
				$in .= '"'.$data['version'].'",';
			}
			$in = trim($in,',');
			$sql = 'SELECT * FROM ' .DB_PREFIX . 'template_config WHERE `key` in('.$in.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				switch($row['type'])
				{
					case 'version' : $data['_version'] = $row['value'];break;
					case 'color' : $data['_color'] = $row['value'];break;
					case 'style' : $data['_style'] = $row['value'];break;
					case 'use' : $data['_use'] = $row['value'];break;
				}
			}
		}
		return $data;
	}
	public function get_video($vid = 0)
	{
		global $gGlobalConfig;
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$livmedia = new curl($gGlobalConfig['App_livmedia']['host'], $gGlobalConfig['App_livmedia']['dir'] . 'admin/');
		$livmedia->initPostData();
		$livmedia->addRequestData('id', $vid);
		$livmedia->addRequestData('a', 'detail');
		$vodinfo = $livmedia->request('vod.php');
		$vodinfo=$vodinfo[0];
		return is_array($vodinfo) && !empty($vodinfo) ? $vodinfo : array();
	}
}