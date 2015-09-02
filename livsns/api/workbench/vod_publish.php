<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class vodPublish extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		//echo 123;
	}
	
	function get_column_siteid($columns)
	{
		$return = array();
		if(empty($columns[0]) || !$columns)
		{
			return $return;
		}
		$columns = is_array($columns) ? implode(',', $columns) : $columns;
		$sql = 'SELECT siteid,id FROM ' . DB_PREFIX . 'columns WHERE id IN('.$columns.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$return[$row['id']] = $row['siteid']; 
		}
		return $return;
	}
	
	function update()
	{
		$info = array(
			'moduleid' => $this->input['moduleid'],
			'conid' => $this->input['conid'],
			'new_colid' => urldecode($this->input['new_colid']),
			'admin_name' => urldecode($this->input['admin_name']),
		);
		if(!$info['moduleid'] || !$info['conid'])
		{
			$this->errorOutput('内容不为空');
		}
		if(!is_array($info['new_colid']) && !empty($info['new_colid']))
		{
			$info['new_colid'] = explode(',', $info['new_colid']);
		}
					
		if(!empty($info['new_colid']))
		{
			$siteids = $this->get_column_siteid($info['new_colid']);
			$pid = $space = "";
			foreach($info['new_colid'] as $colid)
			{
				if($colid && $siteids[$colid])
				{
					$sql = 'INSERT INTO ' .DB_PREFIX . 'publish SET ';
					$sql .= 'siteid = "' . $siteids[$colid] . '", ';
					$sql .= 'mid = "' . $info['moduleid'] . '", ';
					$sql .= 'conid = "' . $info['conid'] . '", ';
					$sql .= 'colid = "' . $colid . '", ';
					$sql .= 'status = 0, ';
					$sql .= 'cms_contentmap_id = 0, ';
					$sql .= 'ip = "'.hg_getip().'", ';
					$sql .= 'user_name = "' . $info['admin_name'] . '"';
					$this->db->query($sql);
					$pid .= $space . $this->db->insert_id();
					$space = ",";
				}
			}
			if($pid)
			{
				$sql = 'REPLACE INTO ' . DB_PREFIX . 'queue(pid,type,update_time) values';
				$sql_extra = $space = '';
				if(!empty($pid))
				{
					$pid = explode(',',$pid);
					foreach($pid as $i)
					{
						$sql_extra .= $space . "(" . intval($i) . ",0," . TIMENOW . ")";
						$space = ',';
					}
					$sql .= $sql_extra;
					$this->db->query($sql);
				}
			    $this->addItem(array('success' => 1));
			}
			else
			{
			    $this->addItem(array('success' => 0));
			}
			$this->output();
		}
	}
}
$out = new vodPublish();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action(); 