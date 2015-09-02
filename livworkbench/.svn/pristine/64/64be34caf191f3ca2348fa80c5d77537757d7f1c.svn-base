<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'changenode');
require('./global.php');
class changenode extends uiBaseFrm
{	
	private $site;
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//注意此处没有做limit限制 也就是在子栏目很多的情况下可能会影响加载速度
	public function show()
	{
		$nodeid = intval($this->input['nid']);
		$nodetype = intval($this->input['node_type']);
		if ($nodeid)
		{
			$objname = $this->input['objname'];
			$depth = $this->input['depth'];
			$height = $this->input['height'];
			$level = $this->input['level'];
			$id = intval($this->input['fid']);
			$hg_callback = $this->input['node_callback'] ? $this->input['node_callback'] : 'hg_show_child_node_list';
			$_node_template = $this->input['node_template'] ? $this->input['node_template'] : '_nodedata';
			$multiple = $this->input['multiple'];
			$hg_attr['level'] = $level;
			$hg_attr['depth'] = $depth;
			$hg_attr['height'] = $height;
			$hg_attr['multiple'] = $multiple;
			$hg_data = array();
			$sql = 'SELECT col.id,col.name,col.fatherid as fid,col.is_last,col.parents FROM '.DB_PREFIX.'columns col LEFT JOIN '.DB_PREFIX.'column_type_map col_map ON col_map.columnid = col.id WHERE 1 AND col.fatherid='.$id.' OR  col.id='.$id;
			$conditions = ' ORDER BY col.id ASC ';
			$sql = $sql . $conditions;
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				if($row['id'] == $id)
				{
					$hg_attr['text'] = $row['name'];
				}
				else
				{
					$row['is_last'] = $row['is_last'] ? 0 : 1;
					$row['depth'] = count(explode(',', $row['parents']));
					$row['input_k'] = '_colid';
					$row['input_t'] = '_fid';
					$hg_data[] = $row;
				}
			}
			if (!$hg_data)
			{
				$nodata = 1;
			}
			else
			{
				$nodata = 0;
			}
			$extlink = '&amp;infrm=1&node_type='.$nodetype;
			$hg_attr['nodeapi'] = 'change_node.php?nid=1&amp;mid=' . $this->input['mid'] . $extlink;
			$this->tpl->addVar('hg_columns', $hg_data);
			$this->tpl->addVar('hg_columns_attr', $hg_attr);
			$this->tpl->addVar('hg_data', $hg_data);
			$this->tpl->addVar('hg_attr', $hg_attr);
			$this->tpl->addVar('hg_name', $objname);
			$this->tpl->addVar('_selfurl', 'run.php?mid=' . $this->input['mid'] . $extlink);
			$this->tpl->addVar('_parenturl', 'run.php?mid=' . $this->input['mid'] . $extlink.'&_colid='.$id);
			$this->tpl->addVar('fid', $id);
			$this->tpl->outTemplate($_node_template, $hg_callback . ',' . $objname . ',' . $id . ',' . $depth . ',' . $nodata . ',' . $level);
		}
	}
	private function get_conditions()
	{
		if($this->input['siteid'])
		{
			$conditions .= ' AND col.siteid = '.intval($this->input['siteid']);
		}
		if($this->input['fid'])
		{
			$conditions .= ' AND col.fatherid = '.intval($this->input['fid']);
		}
		else
		{
			$conditions .= ' AND col.fatherid = 0';
		}
		if($this->input['type'])
		{
			$conditions .= ' AND col_map.column_flag = '.intval($this->input['type']);
		}
		return $conditions;
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>