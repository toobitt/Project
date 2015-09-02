<?php
class layout extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$field = '*')
	{
		$sql = 'SELECT '.$field.' FROM ' . DB_PREFIX . 'layout WHERE 1 AND original_id = 0 ' . $condition;
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			isset($row['content']) && ($row['content'] = html_entity_decode($row['content']));
			isset($row['css']) && ($row['css'] = html_entity_decode($row['content'])); 
			isset($row['create_time']) && ($row['create_time_show'] = date('Y-m-d H:i', $row['create_time']));
			isset($row['update_time']) && ($row['update_time_show'] = date('Y-m-d H:i', $row['update_time']));
			isset($row['status']) && ($row['state'] = $this->settings['status_show'][$row['status']]);
			$row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array();
			$ret[] = $row;
		}
		return $ret;
	}
	
	public function detail($condition, $field = '*')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."layout WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function count($condition)
	{
		$sql = 'SELECT count(*) AS total FROM '.DB_PREFIX.'layout WHERE 1 AND original_id = 0 '.$condition;
		$totalNum = $this->db->query_first($sql);
		return $totalNum;		
	}
	
	public function joinLayoutTemplate($intLayoutId)
	{
		if (!$intLayoutId) {
			return '';
		}
		$sql = "SELECT * FROM ".DB_PREFIX."layout WHERE id = " . $intLayoutId;
		$layout_info = $this->db->query_first($sql);
		$layout_info['content'] = str_replace('<NS>', 'm2o-layout-class-' . $layout_id, html_entity_decode($layout_info['content']));
		$layout_info['css'] = str_replace('<NS>', '.m2o-layout-class-' . $layout_id, html_entity_decode($layout_info['css']));
		$template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
					"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html>
					<head>
					<title>'.$layout_info['title'].'</title>
					<link rel="stylesheet" type="text/css" href="'.DATA_URL.'/base.css" />
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta name="keywords" content="'.$layout_info['title'].'" />
					<meta name="description" content="'.$layout_info['title'].'" />
					<style type="text/css">'.$layout_info['css'].'</style>
					</head>
					<body>'.$layout_info['content'].'</body>
					</html>';		
		return $template;
	}


    public function getLayoutCell($intLayoutId) {
        if (!$intLayoutId) {
            return array();
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "layout_cell WHERE layout_id = " . $intLayoutId;
        $q = $this->db->query($sql);
        $cells = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['param_asso'] = unserialize($row['param_asso']);
            $cells[] = $row;
        }
        return $cells;               
    }
	
	public function layout_namespace_and_header_process($layout_info)
	{
		if (!$layout_info) {
			return array();
		}
        //专题栏目链接处理
        if (strpos($layout_info['more_href'], 'COLURL') !== false) {
            $intColumnId = intval(str_replace('COLURL', '', $layout_info['more_href']));
            if (!class_exists('special')) {
                include(ROOT_PATH . 'lib/class/special.class.php');
            }
            $objSpecial = new special(); 
            $layout_info['more_href'] = $objSpecial->get_special_col_url($intColumnId); 
        }     		
		$layout_info['content'] = $layout_info['content'] ? str_replace('<NS>', 'm2o-layout-class-' . $layout_info['id'], html_entity_decode($layout_info['content'])) : '';
		$layout_info['css']= $layout_info['css'] ? str_replace('<NS>', '.m2o-layout-class-' . $layout_info['id'], html_entity_decode($layout_info['css'])) : '';
        $layout_info['layout_css'] = $layout_info['css'];
		$layout_info['header'] = '';
		if ($layout_info['is_header']) {
			$find = array('{$header_text}', '{$more_href}', '{$more_text}');
			$replace = array($layout_info['header_text'], $layout_info['is_more'] ? $layout_info['more_href'] : '#', $layout_info['is_more'] ? '更多>>' : '');	
			$header = str_replace($find,$replace, $this->settings['header_dom']['layout']);
			$layout_info['header'] = $header;			
			if ($layout_info['content']) {
				$layout_info['content'] = strpos($layout_info['content'],'{$m2o_layout_title}') !== false ? str_replace('{$m2o_layout_title}',$header, $layout_info['content']) : preg_replace('/<div[^>]*m2o-layout-item[^>]*>/is', '\\0' . $header, $layout_info['content']);
			}
		}
		return $layout_info;			
	}	
	
	public function update($data,$table_name)
	{	
		//样式数据操作
		$sql = "UPDATE " . DB_PREFIX .$table_name." SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id'];
		$this->db->query($sql);	
		return $this->db->affected_rows();
	}
	
	function import_layout_info($data,$table)
	{
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX .$table." SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
}
?>