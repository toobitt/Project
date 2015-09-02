<?php
require('global.php');
define('MOD_UNIQUEID','data_source');//模块标识
class dataSourceUpdateApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/data_source.class.php');
		include(CUR_CONF_PATH . 'lib/common.php');
		$this->obj = new dataSource();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('data_source',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		
		$out_variable_ids = $this->input['out_variable_ids'];
		$argument = $out_argument = array();
		if($this->input['argument_name'] || $this->input['ident'] || $this->input['value'])
		{
			if(is_array($this->input['argument_name']))
			{
				foreach($this->input['argument_name'] as $k=>$v)
				{
					$argument['argument_name'][$k] = urldecode($this->input['argument_name'][$k]);
				}
			}
			$argument['ident'] = $this->input['ident'];
			if(is_array($this->input['value']))
			{
				foreach($this->input['value'] as $k=>$v)
				{
					$argument['value'][$k] = urldecode($this->input['value'][$k]);
				}
			}
			if(is_array($this->input['other_value']))
			{
				foreach($this->input['other_value'] as $k=>$v)
				{
					//$v = str_replace("\r\n",' ',trim(html_entity_decode($v)));
					$v =  $this->replace($v);
					$argument['other_value'][$k] = $v;
				}
			}
			$argument['add_status'] = $this->input['add_status'];
			$argument['add_request'] = $this->input['add_request'];
			$argument['type'] = $this->input['type'];
		}
		
		if($this->input['dir'])
		{
			$dir = rtrim(urldecode($this->input['dir']),'/').'/';
		}
		else
		{
			$dir = '';
		}
		$argument = serialize($argument);
		$out_argument = serialize($out_argument);
		$out_para = $this->replace($this->input['out_param']);
		$data = array(
			'app_id'				=>$this->input['app_id'],
			'name'					=>$this->input['name'],
			'sign'					=>uniqid(),
			'brif'					=>urldecode($this->input['brif']),	
			'request_file'			=>urldecode($this->input['request_file']),
			'protocol'				=>intval($this->input['protocol']),	
			'host'					=>rtrim(urldecode($this->input['host']), '/'),	
			'dir'					=>$dir,		
			'argument'				=>$argument,			
			'cache_update_time'		=>intval($this->input['cache_update_time']),
			'request_type'			=>intval($this->input['request_type']),
			'cache_update' 			=> intval($this->input['cache_update']),
			'data_format'			=>urldecode($this->input['data_format']),
			'data_node'				=>urldecode($this->input['data_node']),
			//'direct_return'		=>intval($this->input['direct_return']),
			'codefmt'				=>urldecode($this->input['codefmt']),
			'out_param'				=>	$out_para,
			'user_id'       		=>  $this->user['user_id'],
			'user_name'    		 	=>  $this->user['user_name'],
			'ip'       				=>  $this->user['ip'],
			'org_id'				=> 	$this->user['org_id'],
			'update_time'			=>  TIMENOW,
			'create_time'			=>  TIMENOW,
			'is_parameter'          =>  intval($this->input['is_parameter']),
		);
		$datasource_id = $this->obj->create($data);
		
		$datafid = $this->obj->create_out_argument('data','0',$datasource_id);
		$fid = $this->obj->create_out_argument('0',$datafid,$datasource_id);
		if($this->input['out_arname'])
		{
			foreach($this->input['out_arname'] as $k=>$v)
			{
				$this->obj->create_out_argument($v,$fid,$datasource_id,$this->input['out_artitle'][$k],$this->input['out_arvalue'][$k]);
			}
		}
		if($this->input['new_out_name'])
		{
			foreach($this->input['new_out_name'] as $ke=>$va)
			{
				$this->obj->create_out_argument($va,$fid,$datasource_id,$this->input['new_out_title'][$ke],$this->input['new_out_value'][$ke]);
			}
		}
		if($out_variable_ids)
		{
			$this->obj->update_out_variable($datasource_id,$out_variable_ids);
		}
		
		common::build_api($datasource_id);
		
		$re[] = $datasource_id;
		
		$data['id'] = $datasource_id;
		
		$this->addLogs('新增数据源' , '' , $data , $data['name']);
		
		$this->addItem($re);
		$this->output();
	}
	
	public function update()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('data_source',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
        
		$id = intval($this->input['id']);
		$out_variable_ids = $this->input['out_variable_ids'];
		$argument = $out_argument = array();
		if($this->input['argument_name'] || $this->input['ident'] || $this->input['value'])
		{
			if(is_array($this->input['argument_name']))
			{
				foreach($this->input['argument_name'] as $k=>$v)
				{
					$argument['argument_name'][$k] = urldecode($this->input['argument_name'][$k]);
				}
			}
			$argument['ident'] = $this->input['ident'];
			if(is_array($this->input['value']))
			{
				foreach($this->input['value'] as $k=>$v)
				{
					$argument['value'][$k] = urldecode($this->input['value'][$k]);
				}
			}
			if(is_array($this->input['other_value']))
			{
				foreach($this->input['other_value'] as $k=>$v)
				{
					//$v = str_replace("\r\n",' ',trim(html_entity_decode($v)));
					$v =  $this->replace($v);
					$argument['other_value'][$k] = $v;
				}
			}
			$argument['add_status'] = $this->input['add_status'];
			$argument['add_request'] = $this->input['add_request'];
			$argument['type'] = $this->input['type'];
		}
		if($this->input['dir'])
		{
			$dir = rtrim(urldecode($this->input['dir']),'/').'/';
		}
		else
		{
			$dir = '';
		}
		$argument = serialize($argument);
		$out_argument = serialize($out_argument);
		
		$out_para = $this->replace($this->input['out_param']);
		$data = array(
			'id'				=>$id,
			'app_id'			=>$this->input['app_id'],
			'name'				=>$this->input['name'],
			//'sign'			=>$this->input['sign'],
			'brif'				=>urldecode($this->input['brif']),	
			'request_file'		=>urldecode($this->input['request_file']),
			'protocol'			=>intval($this->input['protocol']),	
			'host'				=>rtrim(urldecode($this->input['host']), '/'),	
			'dir'				=>$dir,		
			'argument'			=>$argument,	
			'out_argument'		=>$out_argument,			
			'cache_update_time' =>intval($this->input['cache_update_time']),
			'request_type'		=>intval($this->input['request_type']),
			'cache_update' 		=> intval($this->input['cache_update']),
			'data_format'		=>urldecode($this->input['data_format']),
			'data_node'			=>urldecode($this->input['data_node']),
			//'direct_return'	=>intval($this->input['direct_return']),
			'codefmt'			=>	$this->input['codefmt'],
			'out_param'			=>	$out_para,
			'update_time'		=> TIMENOW,
			'is_parameter'      => intval($this->input['is_parameter']),
		);
		
		$s =  "SELECT * FROM " . DB_PREFIX . "data_source WHERE id = " . $this->input['id'];
		$pre_data = $this->db->query_first($s);
		
		$ret = $this->obj->update($data);
		
		$sq =  "SELECT * FROM " . DB_PREFIX . "data_source WHERE id = " . $this->input['id'];
		$up_data = $this->db->query_first($sq);
		
		$this->addLogs('更新数据源' , $pre_data , $up_data , $pre_data['name']);
		
		if($out_variable_ids)
		{
			$this->obj->update_out_variable($id,$out_variable_ids);
		}
		
		if($this->input['out_arname'])
		{
			foreach($this->input['new_out_name'] as $k=>$v)
			{
				if($v)
				{
					$this->obj->create_out_argument($v,$this->input['fid'],$id,$this->input['new_out_title'][$k],$this->input['new_out_value'][$k]);
				}
			}
		}
		if($this->input['new_out_ar'])
		{	
			$newoutids =  explode(',',$this->input['new_out_ar'][0]);
			$out_ids = array_values($newoutids);
			foreach($out_ids as $k=>$v)
			{
				if($v)
				{
					$data = array(
						'id'  		=> $v,
						'name' 		=> $this->input['out_arname'][$v],
						'title'  	=> $this->input['out_artitle'][$v],
						'value'  	=> $this->input['out_arvalue'][$v],
					);
					$this->obj->update_out_argument($data);
				}
			}
		}
		//file_put_contents('0',var_export($this->input,1));
		//file_put_contents('01',var_export($a,1));
		
		if($this->input['out_ar'])
		{
			$old_out_ids = array_values($this->input['out_ar']);
		}
		if($del_css_ids = array_diff($old_out_ids,$out_ids))
		{
			foreach($del_css_ids as $k=>$v)
			{
				$this->obj->delete_out_argument($v);
			}
		}
		
		common::build_api($id);
		
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function edit_update()
	{	
		$data['content'] = htmlspecialchars_decode(urldecode($this->input['content']));
		$data['id'] = $this->input['id'];
		$data['type'] = $this->input['type'];
		
		$ret = $this->obj->edit_update($data);
		$this->addItem($ret);
		$this->output();
	}
	
	public function h_update()
	{	
		$path = CUR_CONF_PATH."template_".intval($this->input['site_id'])."_".trim(urldecode($this->input['sort_name']));
		$data = array(
			'id'       		=> intval($this->input['id']),
			'title'			=> trim(urldecode($this->input['title'])),
			'file_name'		=> trim(urldecode($this->input['file_name'])),
            'source'		=> intval($this->input['source']),
            'sort_id'		=> intval($this->input['sort_id']),
			'site_id'		=> intval($this->input['site_id']),
			'file_path'     => $path,
			'content'		=> htmlspecialchars_decode(urldecode($this->input['content'])),
		);
		$ret = $this->obj->update($data);
		$this->addItem($ret);
		$this->output();
	}
	
	public function edit_c()
	{	
		if($this->input['flag'])
		{
			$id = intval($this->input['id']);
			
			//获取上传文件内容并调用模板比较器
			$sql = "SELECT content FROM ". DB_PREFIX ."templates WHERE id = ".$id;
			$formerly_template = $this->db->query_first($sql);	
			$content = file_get_contents($_FILES['file_data']['tmp_name']);
			$table = $this->analyse_result($formerly_template['content'],$content,'');
			
			//将模板相关内容封装
			//获取类型
			$table['source'] = $this->input['sources'];
			//获取模板分类id
			$table['sort_id'] = $this->input['sorts'];
			$sql_ = "select name,site_id from " . DB_PREFIX . "site_tem_sort where id = '".$table['sort_id'] ."'";
			$q_ = $this->db->query_first($sql_);
			$sort_id = $q_['id'];
			$table['site_id'] = $q_['site_id'];
			$table['sort_name'] = $q_['name'];
			
			$table['file_name'] = $_FILES['file_data']['name'];
			$table['title'] = trim(urldecode($this->input['title']));
			if(!$table['title'])
			{
				$table['title'] = $table['file_name'];
			}
			$this->addItem($table);
			$this->output();
		}
		else
		{
			//$content = (file_get_contents($_FILES['file_data']['tmp_name']));
			$new_template = htmlspecialchars_decode(urldecode($this->input['content']));
			//$new_template = '"'.$content.'"';
			$id = $this->input['id'];		
			$sql = "SELECT content FROM ". DB_PREFIX ."templates WHERE id = ".$id;
			$formerly_template = $this->db->query_first($sql);	
			//file_put_contents('4.txt',$new_template);
			$table = $this->analyse_result($formerly_template['content'],$new_template,$this->input['types']);
			$this->addItem($table);
			$this->output();
		}
		
	}
	
	public function delete()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('data_source',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$ids = $this->input['id'];
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的数据源");
		}
		
        $sqll =  "SELECT * FROM " . DB_PREFIX . "data_source WHERE id IN (" . $ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$ret = $this->obj->delete($ids);
		if($ret)
		{
			$this->addLogs('删除数据源' , $pre_data , '', '删除数据源'.$ids);
		}
		
		
		$this->addItem($ret);
		$this->output();
		
	}
	
	public function replace($para)
	{
		$pregreplace= array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "#&33", '$', "\r");
		$pregfind= array(' ', '&#60;&#60;&amp;#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', '\n', '&#036;', '');
		$out_para = str_replace($pregfind, $pregreplace, $para);
		return $out_para;
	}

	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new dataSourceUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>