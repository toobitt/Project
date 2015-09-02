<?php
define('MOD_UNIQUEID','export_config');
require_once('global.php');
//require_once(CUR_CONF_PATH . 'lib/xml_mode.php');
class export_config_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		//$this->mode = new xml_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		$data = array(
			'title'				=> trim($this->input['title']),
			'create_time'		=> TIMENOW,
			'user_id'	 		=> $this->user['user_id'],
			'user_name'	  		=> $this->user['user_name'],	
		);
		//栏目id特殊处理
		if($this->input['column_id'])
		{
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $publishconfig = new publishconfig();
            $pub_column_id = $publishconfig->get_column_by_ids('id, childs', $this->input['column_id']);
            foreach((array)$pub_column_id as $k => $v)
            {
                $column_id[]= $v['childs'];
            }
            $column_id = implode("','", $column_id);
        }
		//xml_id特殊处理
		if($this->input['xml_id'])
		{
			if(substr(trim($this->input['xml_id']),0,1) == '_')
			{
				$type_id = trim($this->input['xml_id'],'_');
				$sql = "SELECT id FROM " .DB_PREFIX. "xml WHERE type_id = " .$type_id;
				$query = $this->db->query($sql);
				$xml_id = '';
				while($row = $this->db->fetch_array($query))
				{
					$xml_id .= $row['id'].',';
				}
				$xml_id = trim($xml_id,',');
			}
			else
			{
				$xml_id = $this->input['xml_id'];
			}
		}
		else
		{
			$this->errorOutput('请选择模板');
		}
		//
		$map = array (
			  0 => 
			  array (
			    'name' => 'key',
			    'value' => $this->input['key'],
			  ),
			  1 => 
			  array (
			    'name' => 'pub_column_id',
			    'value' => $column_id,
			  ),
			  2 => 
			  array (
			    'name' => 'add_user_name',
			    'value' => $this->input['add_user_name'],
			  ),
			  3 => 
			  array (
			    'name' => 'start_time',
			    'value' => $this->input['start_time'],
			  ),
			  4 => 
			  array (
			    'name' => 'end_time',
			    'value' => $this->input['end_time'],
			  ),
			  5 => 
			  array (
			    'name' => 'start_weight',
			    'value' => $this->input['start_weight'] ? $this->input['start_weight'] : '0',
			  ),
			  6 => 
			  array (
			    'name' => 'end_weight',
			    'value' => $this->input['end_weight'] ? $this->input['end_weight'] : '100',
			  ),
			  7 => 
			  array (
			    'name' => 'xml_id',
			    'value' => $xml_id,
			  ),
			  8 => 
			  array (
			    'name' => 'xml_name',
			    'value' => $this->input['xml_name'],
			  ),
			  9 => 
			  array (
			    'name' => 'need_file',
			    'value' => $this->input['need_file'] ? 1 : 0,
			  ),
			  10 =>
			  array (
			  	'name' => 'vod_sort_id',
			  	'value' => implode(',',$this->input['district_id']),
			  ),
			  11 =>
			  array(
			  	'name' => 'column_name',
			  	'value' => $this->input['column_name'],
			  ),
			  12 =>
			  array(
			  	'name' => 'file_num',
			  	'value' => $this->input['file_num'],
			  ),
		);
		foreach ((array)$map as $k => $v)
		{
        		$val[$v['name']]  = $v['value'];
        }
		//$data['tag_val'] = $val ? addslashes(serialize($val)) : '';
		$data['config'] = $val ? serialize($val) : '';
		//$sql = "INSERT INTO " .DB_PREFIX. "export_config SET ";
		//$vid = $this->mode->create($data);
		$vid = $this->db->insert_data($data, 'export_config');
		if($vid)
		{
			$sql = " UPDATE ".DB_PREFIX."export_config SET order_id = {$vid}  WHERE id = {$vid}";
			$this->db->query($sql);
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{}
	
	public function delete()
	{	
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//不可以删除默认配置
		$sql = "SELECT * FROM " .DB_PREFIX. "export_config WHERE id IN(" .$this->input['id']. ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['is_default'])
			{
				$this->errorOutput('不可删除默认配置');
			}
		}
		
		//开始删除
		$sql = "DELETE FROM " .DB_PREFIX. "export_config WHERE id IN(" .$this->input['id']. ")";
		$query = $this->db->query($sql);
		$row = $this->db->affected_rows($query);
		
		//如果勾选"同时删除文件"
		if(intval($this->input['is_delete_file']))
		{
			require_once(ROOT_PATH . 'lib/class/curl.class.php');
			$this->vodcurl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir']);
			$this->vodcurl->setSubmitType('post');
			$this->vodcurl->initPostData();
			$this->vodcurl->addRequestData('html', 'true');
			$this->vodcurl->addRequestData('id', $this->input['id']);
			$this->vodcurl->addRequestData('a', 'delete');
			$ret = $this->vodcurl->request('admin/xml.php');
		}
		
		if($row)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/*
	 * 设置默认配置
	 */
	public function set_default()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if($this->input['is_default']) //开启
		{
			$sql_close = "UPDATE " .DB_PREFIX. "export_config SET is_default = 0";
			$sql_open = "UPDATE " .DB_PREFIX. "export_config SET is_default = 1 WHERE id = " .$this->input['id'];
			$this->db->query($sql_close);
			$this->db->query($sql_open);
		}
		else //关闭
		{
			$sql = "UPDATE " .DB_PREFIX. "export_config SET is_default = 0 WHERE id = " .$this->input['id'];
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();		
	}
	
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new export_config_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>