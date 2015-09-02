<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', './');
define('WITH_DB',true);
define('SCRIPT_NAME', 'get_selected_nodes');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/column.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class get_selected_nodes extends uiBaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function show()
	{
		$column_id = $this->input['column_id'];
		$column = new column();
		$publish = array();
		$publish['sites'] = $column->getallsites();
		list($default_site, $default_name) = each($publish['sites']);
		reset($publish['sites']);
		$publish['items'] = $column->getAuthoredColumns($default_site);
		$publish['selected_ids'] = $column_id ? $column_id : '';
		$publish['selected_items'] = $column->get_selected_column_path($publish['selected_ids']);
		$publish['default_site'] = each($publish['sites']);
		$publish['pub_time'] = $this->input['pub_time'];
		
		$hg_print_selected = array();
		foreach ($publish['selected_items'] as $index => $item) 
		{
			$hg_print_selected[$index] = array();
			$current = &$hg_print_selected[$index];
			$current['showName'] = '';
			foreach ($item as $sub_item) 
			{
				if($sub_item['is_auth'])
				{
					$current['is_auth'] = 1;
				}
				$current['id'] = $sub_item['id'];
				$current['name'] = $sub_item['name'];
				$current['showName'] .= $sub_item['name'] . ' > ';
			}
			if(!$current['is_auth'])
			{
				$current['is_auth'] = 0;
			}
			$current['showName'] = $publish['default_site']['value'] . ' > ' . substr($current['showName'], 0, -3);
			$selected_names[] = $current['name'];
		}
		$publish['selected_items'] = $hg_print_selected;
		$publish['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';
		echo json_encode($publish);
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>