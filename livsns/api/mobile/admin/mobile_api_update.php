<?php
define('SCRIPT_NAME', 'mobile_api_update');
define('MOD_UNIQUEID','api');
require_once('./global.php');
require(CUR_CONF_PATH."lib/functions.php");

class mobile_api_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}

	function create()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
	    
	    //请求多接口开关
	    $extend_api_switch = intval($this->input['extend_api_switch']);
	    
	    
		if(!$this->input['file_name'] && !$extend_api_switch)
		{
			$this->errorOutput('请输入文件名');
		}
	    
	    //参数处理
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
			$argument['ident_input'] = $this->input['ident_input'];
			
			if(is_array($this->input['value']))
			{
				foreach($this->input['value'] as $k=>$v)
				{
					$argument['value'][$k] = urldecode($this->input['value'][$k]);
				}
			}
			$argument['val_type'] = $this->input['val_type'];

			$argument['add_status'] = $this->input['add_status'];
			
			$argument = serialize($argument);
		}
		$argument = $argument ? $argument : '';
		
		
		//返回值替换
		$map_val = array();
		if($this->input['map_val_key'])
		{
			foreach ($this->input['map_val_key'] as $k => $v)
			{
				$map_val[$v] = trim($this->input['map_val'][$k]);
			}
			
			$map_val = serialize($map_val);
		}
		$map_val = $map_val ? $map_val : '';
		
		
		//添加访问接口
		$extend_api = array();
		if($this->input['api_key'])
		{
			foreach ($this->input['api_key'] as $k => $v)
			{
				$extend_api[$v] = trim($this->input['api_name'][$k]);
			}
			
			$extend_api = serialize($extend_api);
		}
		$extend_api = $extend_api ? $extend_api : '';
		
		//访问路径
		if($this->input['dir'])
		{
			$dir = rtrim(urldecode($this->input['dir']),'/').'/';
		}
		else
		{
			$dir = '';
		}
		
		$bundle = $this->input['bundle'];
		if($bundle == -1 && !$extend_api_switch)
		{
			$bundle = '';
			if(!$this->input['host'] || !$dir)
			{
				$this->errorOutput('请填写url路径');
			}
		}
		
		if(!$bundle && !$extend_api_switch)
		{
			if(!$this->input['host'] || !$dir)
			{
				$this->errorOutput('请填写url路径');
			}
		}
		
		
		$sort_id = intval($this->input['sort_id']);
		if(!$sort_id)
		{
			$this->errorOutput('请选择分组');
		}
		
		$file_name = trim($this->input['file_name']);
		//判断分类下file_name是否存在
		$res = $this->check_file_name($sort_id, $file_name);
		if($res)
		{
			$this->errorOutput('文件名已经存在');
		}
		
		
		$data = array(
			'file_name'			=>	$file_name,
			'brief'				=>	urldecode($this->input['brief']),	
			'request_file'		=>	trim($this->input['request_file']),
			'protocol'			=>	intval($this->input['protocol']),	
			'host'				=>	rtrim(urldecode($this->input['host']), '/'),	
			'dir'				=>	$dir,	
			'data_format'		=>	urldecode($this->input['data_format']),
			'data_node'			=>	urldecode($this->input['data_node']),
			'direct_return'		=>	intval($this->input['direct_return']),
			'argument'			=>	$argument,
			'map_val'			=>	$map_val,
			'extend_api'		=> 	$extend_api,
			'extend_api_switch' =>  $extend_api_switch,
		
			'uname'				=>	$this->input['uname'],	
			'pwd'				=>	urldecode($this->input['pwd']),	
			'token'				=>	urldecode($this->input['token1']),	
			'codefmt'			=>	urldecode($this->input['codefmt']),	
			'status'			=>	intval($this->input['status']),
			'request_type'		=>	intval($this->input['request_type']),
			'sort_id' 			=> 	$sort_id,
			'bundle'			=>	$bundle,
			'static_cache'		=>	intval($this->input['static_cache']),
			'ret_code'			=> trim($this->input['ret_code']),
			'param_code'		=> trim($this->input['param_code']),
		);
		
		$sql = 'INSERT INTO '.DB_PREFIX.'mobile_deploy SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$this->db->query(rtrim($sql, ','));
		$data['id'] = $this->db->insert_id();
		
		//入库成功，直接生成文件
		if($data['id'])
		{
			$id = $data['id'];
			$this->create_mobile_api_file($id);
			
			if($this->input['id'])
			{
				//复制，复制后是否复制映射待确定
			}
		}
		
		$this->addItem($data);
		$this->output();
	}
	
	function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
	    $id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		//请求多接口开关
	    $extend_api_switch = intval($this->input['extend_api_switch']);
	    
		//参数处理
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
			$argument['ident_input'] = $this->input['ident_input'];
			
			if(is_array($this->input['value']))
			{
				foreach($this->input['value'] as $k=>$v)
				{
					$argument['value'][$k] = urldecode($this->input['value'][$k]);
				}
			}
			$argument['val_type'] = $this->input['val_type'];
			$argument['add_status'] = $this->input['add_status'];
			$argument = serialize($argument);
		}
		$argument = $argument ? $argument : '';
		
		
		//返回值替换
		$map_val = array();
		if($this->input['map_val_key'])
		{
			foreach ($this->input['map_val_key'] as $k => $v)
			{
				$map_val[$v] = trim($this->input['map_val'][$k]);
			}
			
			$map_val = serialize($map_val);
		}
		$map_val = $map_val ? $map_val : '';
		
		//添加访问接口
		$extend_api = array();
		if($this->input['api_key'])
		{
			foreach ($this->input['api_key'] as $k => $v)
			{
				$extend_api[$v] = trim($this->input['api_name'][$k]);
			}
			
			$extend_api = serialize($extend_api);
		}
		$extend_api = $extend_api ? $extend_api : '';
		
		//访问路径
		if($this->input['dir'])
		{
			$dir = rtrim(urldecode($this->input['dir']),'/').'/';
		}
		else
		{
			$dir = '';
		}
		
		$bundle = $this->input['bundle'];
		if($bundle == -1 && !$extend_api_switch)
		{
			$bundle = '';
			if(!$this->input['host'] || !$dir)
			{
				$this->errorOutput('请填写url路径');
			}
		}
		
		if(!$bundle && !$extend_api_switch)
		{
			if(!$this->input['host'] || !$dir)
			{
				$this->errorOutput('请填写url路径');
			}
		}
		
		$sort_id = intval($this->input['sort_id']);
		if(!$sort_id)
		{
			$this->errorOutput('请选择分组');
		}
		
		//查询配置编辑前文件名
		$sql = "SELECT file_name FROM " . DB_PREFIX . "mobile_deploy WHERE id = " . $id;
		$res = $this->db->query_first($sql);

		$file_name = trim($this->input['file_name']);
		if($res['file_name'] != $file_name)
		{
			$res = $this->check_file_name($sort_id, $file_name);
			if($res)
			{
				$this->errorOutput('文件名已经存在');
			}
		}
		
		$data = array(
			'file_name'			=>	$file_name,
			'brief'				=>	urldecode($this->input['brief']),	
			'request_file'		=>	trim($this->input['request_file']),
			'protocol'			=>	intval($this->input['protocol']),	
			'host'				=>	rtrim(urldecode($this->input['host']), '/'),	
			'dir'				=>	$dir,		
			'data_format'		=>	urldecode($this->input['data_format']),
			'data_node'			=>	urldecode($this->input['data_node']),
			'direct_return'		=>	intval($this->input['direct_return']),
			'codefmt'			=>	urldecode($this->input['codefmt']),	
			'argument'			=>	$argument,
			'map_val'			=>	$map_val,
			'extend_api'		=> 	$extend_api,
			'extend_api_switch' =>  $extend_api_switch,
			
			'uname'				=>	urldecode($this->input['uname']),	
			'pwd'				=>	urldecode($this->input['pwd']),	
			'token'				=>	urldecode($this->input['token1']),	
			'status'			=>	intval($this->input['status']),
			'request_type'		=>	intval($this->input['request_type']),
			'sort_id' 			=> 	$sort_id,
			'cache_update' 		=> 	intval($this->input['cache_update']),
			'bundle'			=>	$bundle,
			'static_cache'		=>	intval($this->input['static_cache']),
			'ret_code'			=> trim($this->input['ret_code']),
			'param_code'		=> trim($this->input['param_code']),
		);
		
		$sql = "UPDATE ".DB_PREFIX."mobile_deploy SET ";
		foreach($data as $k=>$v)
		{
			$sql .= "`".$k . "`='" . $v . "',";
		}
		
		$sql = rtrim($sql,',');
		$sql = $sql.' WHERE id = '.$id;
		$this->db->query($sql);
		
		//$res = $this->db->affected_rows();
		
		//配置修改后，重新生成文件
		//if($res)
		//{
			$this->create_mobile_api_file($id);
		//}
		
		$this->addItem('success');
		
		$this->output();
	}
	
	function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = trim(urldecode($this->input['id']));
		$sql = "SELECT * FROM " . DB_PREFIX ."mobile_deploy WHERE id IN(" . $ids .")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
			$data[$row['id']] = array(
				'title' => $row['file_name'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['mobile_deploy'] = $row;
		}
		
		//放入回收站
		$recycle_ret = true;
		if(!empty($data) && $this->settings['App_recycle'])
		{
			include_once(ROOT_PATH . 'lib/class/recycle.class.php');
			$this->recycle = new recycle();
			foreach($data as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				$recycle_ret = $ret['sucess'];
			}
		}
		//判断传入的信息是否完整
		//if($recycle_ret)		
		{
			//删除生成的文件
			$this->del_api_file($ids);
			
			//删除记录
			$sql = 'DELETE FROM '.DB_PREFIX.'mobile_deploy WHERE id in('.$ids.')';
			$this->db->query($sql);
			
			$this->addItem('success');
			$this->output();
		}
		//else
		{
			$this->errorOutput('删除失败，信息不完整');
		}
		
	}
	public function delete_comp()
	{
		return true;
	}
	/**
	 * 
	 * Enter description here ...
	 * 删除api接口时，删除接口生成的文件
	 */
	function del_api_file($ids)
	{
		if(!$ids)
		{
			return ;
		}
		//查询文件配置
		$sql = "SELECT m.file_name,s.sort_dir FROM " . DB_PREFIX . "mobile_deploy m 
				LEFT JOIN ".DB_PREFIX."mobile_sort s
					ON m.sort_id=s.id 
				WHERE m.id IN (".$ids.")";
		
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$file = '';
			$file = DATA_DIR . $r['sort_dir'] . $r['file_name'];
			if(file_exists($file))
			{
				unlink($file);
			}
		}
	}
	/**
	 *查询生成文件api的参数 
	 * 
	 * @param int $id api文件id
	 */
	private function create_mobile_api_file($id)
	{
		//模板文件路径
		if(!defined('MOBILE_API_TPL'))
		{
			define('MOBILE_API_TPL','../api/apitpl.php');
		}
		$tpl = MOBILE_API_TPL;
		if(!is_readable($tpl))
		{
			$this->errorOutput(NOT_ALLOW_READ);
		}
		$tpl_str = '';
		//获取模板文件
		$tpl_str = @file_get_contents($tpl);
		if(!$tpl_str)
		{
			$this->errorOutput(NOT_ALLOW_READ);
		}
		//查询文件配置
		$sql = "SELECT m.*,s.sort_dir FROM " . DB_PREFIX . "mobile_deploy m 
				LEFT JOIN ".DB_PREFIX."mobile_sort s
					ON m.sort_id=s.id 
				WHERE m.id = " . $id;
		$setting = $this->db->query_first($sql);
					
		//生成文件
		mobile_build_file($setting, $tpl_str);
	}
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		if(!empty($content['mobile_deploy']))
		{
			$sql = "insert into " . DB_PREFIX . "mobile_deploy set ";
			$space='';
			foreach($content['mobile_deploy'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}
		return true;
	}*/
	function check_file_name($sort_id,$file_name)
	{
		$sql = "SELECT file_name FROM " . DB_PREFIX . "mobile_deploy WHERE sort_id = " . $sort_id . " AND file_name = '" . $file_name . "'";
		$res = $this->db->query_first($sql);
		if($res['file_name'])
		{
			return TRUE;
		}
		return FALSE;
	}
	function sort(){}
	function audit(){}
	function publish(){}
}
include(ROOT_PATH . 'excute.php');
