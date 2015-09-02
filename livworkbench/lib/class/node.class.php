<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

class nodeapi
{
	private $db;
	private $mNodedir;
	function __construct()
	{
		global $gCache;
		$this->db = hg_checkDB();
		$this->cache = $gCache;
		$this->mNodedir = CACHE_DIR . 'program/node/';
	}
	
	function __destruct()
	{
	}

	public function compile($node_id, $mod_uniqueid = '')
	{
		$node_id = intval($node_id);
		$sql = "SELECT * FROM " . DB_PREFIX . "node WHERE id=" . $node_id;
		
		$nodeapi = $this->db->query_first($sql);
		if (!$nodeapi)
		{
			$program  = '<?php	
			?>';
			if (hg_mkdir($this->mNodedir))
			{
				hg_file_write($this->mNodedir . '0.php', $program);
			}
			else
			{
				exit($this->mNodedir . '目录不可写');
			}
			return $this->mNodedir . '0.php';
		}
		$application = hg_check_application(intval($nodeapi['application_id']));
		return $this->compile_show($nodeapi, $application, $mod_uniqueid);
	}

	private function compile_show($nodeapi, $application, $mod_uniqueid='')
	{
		$api = $this->cal_api($application, $nodeapi);
		$program  = '<?php
			$this->curlNode = new curl(\'' . $api['host'] . '\', \'' . $api['dir'] . '\', \'' . $api['token'] . '\');
		';
		$nodeapi['return_var'] = $nodeapi['return_var'] ? $nodeapi['return_var'] : $nodeapi['template'];
		$nodeapi['primary_key'] = $nodeapi['primary_key'] ? $nodeapi['primary_key'] : '_id';
		$program  .= '
			$fid = $this->input[\'fid\'];
			$offset = $this->input[\'offset\'];
			$count = $this->input[\'count\'];
			$this->curlNode->setReturnFormat(\'' . $nodeapi['return_type'] . '\');
			$this->curlNode->initPostData();
			$this->curlNode->addRequestData(\'a\', \'' . $nodeapi['func_name'] . '\');
			if (!empty($offset))
			{
				$this->curlNode->addRequestData(\'offset\', $offset);
			}
			if (!empty($count))
			{
				$this->curlNode->addRequestData(\'count\', $count);
			}
			$this->curlNode->addRequestData(\'fid\', $fid);
			$this->curlNode->addRequestData(\'trigger_action\', \'show\');
			$this->curlNode->addRequestData(\'trigger_mod_uniqueid\', \''.$mod_uniqueid.'\');
			$hg_data = $this->curlNode->request(\'' . $nodeapi['file_name'] . $nodeapi['file_type'] . '\');
			$s = \'hg_' . $nodeapi['return_var'] . '_selected\';
			if ($$s)
			{
				if (!is_array($$s))
				{
					$$s = array($$s);
				}
				$hg_selected_node = implode(\',\', $$s);
				$this->curlNode->initPostData();
				$this->curlNode->addRequestData(\'a\', \'' . $nodeapi['func_name'] . '\');
				$this->curlNode->addRequestData(\'' . $nodeapi['primary_key'] . '\', $hg_selected_node);
				$hg_selected_data = $this->curlNode->request(\'' . $nodeapi['file_name'] . $nodeapi['file_type'] . '\');
				$this->tpl->addVar(\'hg_' . $nodeapi['return_var'] . '_selected\', $hg_selected_data);
			}
			$hg_node_template = \'' . $nodeapi['template'] . '\';
			$extlink = \'&amp;infrm=1\';
			$hg_attr[\'nodeapi\'] = \'fetch_node.php?nid=' . $nodeapi['id'] . '&amp;node_en=' . $nodeapi['node_uniqueid'] . '&amp;mid=\' . $this->input[\'mid\'] . $extlink;
			';
		$program  .= '
			$this->tpl->addVar(\'hg_' . $nodeapi['return_var'] . '\', $hg_data);
			$this->tpl->addVar(\'hg_' . $nodeapi['return_var'] . '_attr\', $hg_attr);
			$this->tpl->addVar(\'hg_data\', $hg_data);
			$this->tpl->addVar(\'hg_attr\', $hg_attr);
			';
		if (hg_mkdir($this->mNodedir))
		{
			hg_file_write($this->mNodedir . $nodeapi['id'] . '.php', $program);
		}
		else
		{
			exit($this->mNodedir . '目录不可写');
		}
		return $this->mNodedir . $nodeapi['id'] . '.php';
	}

	private function cal_api($application, $module)
	{
		$api = $application;
		if($module['host'])
		{
			$api['host'] = $module['host'];
		}
		if($module['dir'])
		{
			$api['dir'] = $module['dir'];
		}
		if($module['token'])
		{
			$api['token'] = $module['token'];
		}
		return $api;
	}
	
}
?>