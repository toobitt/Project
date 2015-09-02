<?php
//数据源的数据库操作
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class dataSource extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//新增数据源
	public function create($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."data_source SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	//更新模板相关信息
	public function update($data,$tablename = 'data_source')
	{			
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX .$tablename." SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id'];
		$this->db->query($sql);		
	}
	
	//删除数据源
	public function delete($ids)
	{	
		$sql="DELETE FROM " . DB_PREFIX . "data_source WHERE id IN(" . $ids . ")";
		$this->db->query($sql);
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//根据条件查询模板
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."data_source 
				WHERE 1".$condition.' ORDER BY id DESC'.$limit;
		$q = $this->db->query($sql);
		
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		$ap = $this->auth->get_app();
		if(is_array($ap))
		{
			foreach($ap as $k=>$v)
			{
				$apps[$v['bundle']] = $v['name'];
			}
		}
		$apps['0'] = '其他';
		while($row = $this->db->fetch_array($q))
		{		
			$row['app_name'] = $apps[$row['app_id']];
			
			$row['argument'] = $row['argument']?unserialize($row['argument']):array();
			$ret[] = $row;
		}
		$info[] = $ret;
		return $info;
	}
	
	public function get_app()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE father = 0";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function get_module()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE  father != 0";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function get_some_module($father)
	{
		$sql = "SELECT * FROM " .DB_PREFIX ."app WHERE  father =" . $father;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	//获取数据源参数配置
	public function get_datasource_info($id)
	{
		$info = array();
		if($id)
		{
			$sql = "SELECT id,name,app_id,argument FROM ". DB_PREFIX ."data_source WHERE id=".$id;
			$info = $this->db->query_first($sql);
			if($info['argument'])
			{
				$info['argument'] = unserialize($info['argument']);
			}
		}
		return $info;
	}
	
	//获取所有数据源相关配置
	function showDataSource()
	{	
		$sql = "SELECT id,name,app_id,argument
				FROM  " . DB_PREFIX ."data_source 
				WHERE 1";
		$q = $this->db->query($sql);
		$sql_ = "select name,id from " . DB_PREFIX . "app where 1";
		$apps = $this->db->fetch_all($sql_);
		foreach ($apps as $k=>$v){			
			$appInfo[$v[id]] = $v['name'];
		}
		while($row = $this->db->fetch_array($q))
		{				
	
			$dataInfo[$row['app_id']][$row['id']] = array(
					'name'			=>		$row['name'],
					'argument'		=>		unserialize($row['argument']),
			
			);
		}
		$ret['datasource_data'] = $dataInfo;
		$ret['app_data'] = $appInfo;
		return $ret;
	}
	
	function query($id,$data)
	{	
		$url = $this->settings['App_publishsys'];
		$file = 'http://'.$url['host'].'/'.$url['dir'].'cache/'.$id.'.php';
		
		if(!is_file(CUR_CONF_PATH . 'cache/'.$id.'.php'))
		{	
			$this->build_api($id);
		}
		include_once(CUR_CONF_PATH . 'cache/'.$id.'.php');
		$class = 'ds_'.$id;
		$this->data = new $class();
		
		$ret = $this->data->show($data);
		return $ret;
	}
	
	
	public function get_data_source_node($condition,$limit)
	{
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."out_variable WHERE 1 ".$condition.$limit;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[] = $row;
		}
		return $ret;
	}
	
	public function get_content_by_datasource($id,$data=array())
	{
		if(!is_file($this->settings['data_source_dir'].$id.'.php'))
		{	
			self::build_api($id);
		}
		include_once($this->settings['data_source_dir'].$id.'.php');
		$class = 'ds_'.$id;
		$this->data = new $class();
		
		$sql = "SELECT * FROM ".DB_PREFIX."data_source  WHERE id=".$id;
		$r =  $this->db->query_first($sql);
		$r['argument'] = $r['argument'] ? unserialize($r['argument']) : array();
		if($r['argument'])
		{
			foreach($r['argument']['ident'] as $ke=>$va)
			{
				if(!$data[$va])
				{
					$data[$va] = $r['argument']['value'][$ke];
				}
			}
		}
		
		$ret = $this->data->show($data);
		return $ret;
	}
	
	public function get_node_by_id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."out_variable WHERE id=".$id;
		return $this->db->query_first($sql);
	}
	
	public function update_out_variable($datasource_id,$ids)
	{
		$sql = "UPDATE ".DB_PREFIX."out_variable SET expand_id=".$datasource_id." WHERE id IN(".$ids.")";
		$this->db->query($sql);
	}
	
	public function create_out_argument($name,$fid,$data_source_id,$title='',$value='')
	{
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		$curl->addRequestData('fid',$fid);
		$curl->addRequestData('name',$name);
		$curl->addRequestData('data_source_id',$data_source_id);
		$curl->addRequestData('title',$title);
		$curl->addRequestData('value',$value);
		$fid = $curl->request('data_source_node.php');
		return $fid[0];
	}
	
	public function delete_out_argument($id)
	{
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','delete');
		$curl->addRequestData('id',$id);
		$fid = $curl->request('data_source_node.php');
		return $fid[0];
	}
	
	public function update_out_argument($data)
	{
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','update');
		$curl->addRequestData('id',$data['id']);
		$curl->addRequestData('name',$data['name']);
		$curl->addRequestData('title',$data['title']);
		$curl->addRequestData('value',$data['value']);
		$fid = $curl->request('data_source_node.php');
		return $fid[0];
	}
	
	//生成API文件
	public function build_api_file($ids)
	{	
		//$ids = urldecode($this->input['id']);
		$tpl = '../api/apitpl.php';
		if(!is_readable($tpl))
		{
			$this->errorOutput(NOT_ALLOW_READ);
		}
		$tpl_str = '';
		$tpl_str = @file_get_contents($tpl);
		if(!$tpl_str)
		{
			$this->errorOutput(NOT_ALLOW_READ);
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "data_source WHERE id in (".$ids.")";
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			if($j['app_id'])
			{
				$app = 'App_'.$j['app_id'];
				$j['host'] = $this->settings[$app]['host'];
				$j['dir'] = $this->settings[$app]['dir'];
			}
			$return[] = $j;
		}
		$sum = count($return);
		/*if(!($args = unserialize($setting['argument'])))
		{
			$this->errorOutput(NO_ARGUMENTS);
		}
		if(!($maps = unserialize($setting['map'])))
		{
			$this->errorOutput(NO_MAPS);
		}*/
		//批量生成文件
		if(is_array($return) && $sum>1 && $sum<20)
		{
			foreach($return as $k=>$v)
			{
				$setting = array();
				$setting = $v;
				$curl_settings = $setting;
				unset($curl_settings['map']);
				unset($curl_settings['args']);
				$class_name = 'ds_'.$ids;
				$curl_settings = serialize($curl_settings);
				$handler = array();
				$handler = array(
				'{$file_name}',
				'{$class_name}',
				'{$args}',
				'{$maps}',
				'{$settings}'
				);
				$replace_value = array();
				$replace_value = array(
				$setting['request_file'],
				$class_name,
				$setting['argument'],
				$setting['map'],
				$curl_settings,
				);
				$tpl_strs = '';
				$tpl_strs = str_replace($handler, $replace_value, $tpl_str);
				hg_mkdir($this->settings['data_source_dir']);
				@file_put_contents($this->settings['data_source_dir'].$ids.'.php', $tpl_str);
				}
		}
		else//生成单个文件
		{	
			$setting = $return[0];
			$sql_ = "SELECT * FROM ". DB_PREFIX . "out_variable  WHERE mod_id =1 AND depath =3  AND expand_id =  " .$setting['id'];
			$q = $this->db->query($sql_);
			while ($re = $this->db->fetch_array($q))
			{
				$out_arment['sign'][$re['id']] = $re['sign'];
				$out_arment['value'][$re['id']] = $re['value'];
			}
			$curl_settings = $setting;
			/*unset($curl_settings['map']);*/
			unset($curl_settings['args']);
			$class_name= explode('.', $setting['request_file']);
			$class_name = 'ds_'.$ids;
			$curl_settings = serialize($curl_settings);
			$handler = array(
			'{$file_name}',
			'{$class_name}',
			'{$args}',
			'{$settings}'
			);
			$replace_value = array(
			$setting['request_file'],
			$class_name,
			$setting['argument'],
			$curl_settings,
			);
			$tpl_str = str_replace($handler, $replace_value, $tpl_str);
			hg_mkdir($this->settings['data_source_dir']);
			@file_put_contents($this->settings['data_source_dir'].$ids.'.php', $tpl_str);
		}
		$this->addItem('success');
		$this->output();
		
	}
	
	public function array_to_add($data)
	{
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if(is_array($vv))
				{
					$this->array_to_add($vv);
				}
				else
				{
					$curl->addRequestData($kk, $vv);
				}
			}
		}
	}
	
	function import_datasource_info($data,$table)
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
	
	public function delete_datasource_para($conds = array(),$table)
	{
		if($conds)
		{
			$sql = '';
			$sql .= "delete from " . DB_PREFIX .  $table . " ";
			
			$sql .= " where 1 ";
			if($conds)
			{
				foreach ($conds as $k => $v)
				{
					
					$sql .= " and " . $k . " = '" . $v . "'";
					
				}
			}
			$this->db->query($sql);
		}
		//return $result;
	}
	
}
	


?>