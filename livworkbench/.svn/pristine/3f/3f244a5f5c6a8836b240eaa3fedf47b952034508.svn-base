<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/

define('ROOT_DIR', './');
define('SCRIPT_NAME', 'node');
require('./global.php');
require_once(ROOT_DIR . 'lib/class/curl.class.php');
class node extends uiBaseFrm
{	
	private $curl;
	function __construct()
	{
		global $gCache;
		parent::__construct();
		$this->cache = $gCache;
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show($message = '')
	{
		$this->cache->check_cache('modules');
		$modules = $this->cache->cache['modules'];
		$modules = $modules[intval($this->input['mid'])];
		if(!$modules)
		{
			$this->ReportError(UNKNOWN_MODULE);
		}
		$nodeid = intval($this->input['nid']);
		if ($nodeid)
		{
			$objname = $this->input['objname'];
			$depth = $this->input['depth'];
			$height = $this->input['height'];
			$level = $this->input['level'];
			$id = $this->input['fid'];
			$hg_callback = $this->input['node_callback'] ? $this->input['node_callback'] : 'hg_show_child_node_list';
			$_node_template = $this->input['node_template'] ? $this->input['node_template'] : '_nodelist';
			$multiple = $this->input['multiple'];
			$hg_attr['level'] = $level;
			$hg_attr['depth'] = $depth;
			$hg_attr['height'] = $height;
			$hg_attr['multiple'] = $multiple;
			include(hg_load_node($nodeid, $modules['mod_uniqueid']));
			if (!$hg_data)
			{
				$nodata = 1;
			}
			else
			{
				$nodata = 0;
			}
			$this->tpl->addVar('hg_name', $objname);
			$extlink = '&amp;infrm=1&amp;node_en=' . $this->input['node_en'];
			$this->tpl->addVar('_selfurl', 'run.php?mid=' . $this->input['mid'] . $extlink);
			$this->tpl->addVar('fid', $id);
			$this->tpl->outTemplate($_node_template, $hg_callback . ',' . $objname . ',' . $id . ',' . $depth . ',' . $nodata . ',' . $level);
		}
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>