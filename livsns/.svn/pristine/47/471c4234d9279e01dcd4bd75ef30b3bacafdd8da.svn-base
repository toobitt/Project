<?php
define('SCRIPT_NAME', 'mobile_sort_update');
define('MOD_UNIQUEID','api_sort');
require_once('./global.php');
require(CUR_CONF_PATH."lib/functions.php");
class mobile_sort_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
	
	}
	function update()
	{
	
	}
	function sort()
	{
	
	}
	function publish()
	{
	
	}
	function audit()
	{
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
		
		//先查询分类下面还有接口文件
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX ."mobile_deploy WHERE sort_id IN (".$ids.")";
		$num = $this->db->query_first($sql);
		 
		if($num['total'])
		{
			$this->errorOutput('请先删除分类下接口记录');
		}
		
		//查询被删除分类信息
		$sql = "SELECT * FROM " . DB_PREFIX ."mobile_sort WHERE id IN(" . $ids .")";
		$r = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($r))
		{
			$data[$row['id']] = array(
				'title' => $row['sort_name'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['mobile_sort'] = $row;			
		}
		if(!empty($data))
		{
			foreach($data as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		if($res['sucess'])
		{
			//删除分类的文件夹
			$sql = "SELECT sort_dir FROM ".DB_PREFIX."mobile_sort WHERE id IN (".$ids.")";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				rmdir(DATA_DIR.$r['sort_dir']);
			}
			
			//删除记录
			$sql = 'DELETE FROM '.DB_PREFIX.'mobile_sort WHERE id in('.$ids.')';
			$this->db->query($sql);
			
			$this->addItem('success');
			$this->output();
		}
		
	}

	public function delete_comp()
	{
		return true;
	}

	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		if(!empty($content['mobile_sort']))
		{
			$sql = "insert into " . DB_PREFIX . "mobile_sort set ";
			$space='';
			foreach($content['mobile_sort'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}
		return true;
	}*/
	
	public function copy_sort()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('分类id不存在');
		}
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if(!$this->input['sort_name'])
		{
			$this->errorOutput("请输入分组名称");
		}
		$data = array(
			'sort_name'=>urldecode($this->input['sort_name']),
			'sort_dir'=>trim(urldecode($this->input['sort_dir'])),
		);
		
		$sort_dir = substr($data['sort_dir'], -1,1);
		//如果结尾没有‘/’自动加上
		if($sort_dir != '/')
		{
			$data['sort_dir'] = $data['sort_dir'].'/';
		}
		else 
		{
			$data['sort_dir'] = $data['sort_dir'];
		}
		//判断分类目录是否存在
		if($data['sort_dir'])
		{
			$sql = "SELECT id FROM ".DB_PREFIX."mobile_sort WHERE sort_dir = '".$data['sort_dir']."'";
			$res = $this->db->query_first($sql);
			if($res['id'])
			{
				$this->errorOutput('目录已经存在');
			}
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'mobile_sort SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		if($this->db->query($sql))
		{
			$data['id'] = $this->db->insert_id();
		}
		
		//分类创建成功，复制分类下接口
		if($data['id'])
		{
			$sql = "SELECT * FROM ".DB_PREFIX."mobile_deploy WHERE sort_id = ".$id;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$r['sort_id'] = $data['id'];
				$info[] = $r;
			}
			if($info)
			{
				foreach ($info as $val)
				{
					unset($val['id']);
					$sql = "INSERT INTO ".DB_PREFIX."mobile_deploy SET ";
					foreach($val as $k=>$v)
					{
						$sql .= '`'.$k . "`='" . $v . "',";
					}
					$sql = rtrim($sql,',');
					$this->db->query($sql);
				}
				
				//模板文件路径
				if(!defined('MOBILE_API_TPL'))
				{
					define('MOBILE_API_TPL','../api/apitpl.php');
				}
				
				$tpl = MOBILE_API_TPL;
				
				//获取模板文件
				$tpl_str = @file_get_contents($tpl);
				if($tpl_str)
				{
					//生成文件，支持批量
					foreach($info as $v)
					{
						$v['sort_dir'] = $data['sort_dir'];
						mobile_build_file($v, $tpl_str);
					}	
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	
	//导入文件
	public function lead_file()
	{
		$sort_id = intval($this->input['sort_id']);
		
		if(!$sort_id)
		{
			$this->errorOutput('分类id不存在');
		}
		
		$sort_dir = $this->input['sort_dir'];
		if(!$sort_dir)
		{
			$sql = "SELECT sort_dir FROM ".DB_PREFIX."mobile_sort WHERE id = ".$sort_id;
			$res = $this->db->query($sql);
			
			$sort_dir = $res['sort_dir'];
		}
		
		if(!$sort_dir)
		{
			$this->errorOutput('sort_dir不存在');
		}
		
		if(!$_FILES['filedata']['tmp_name'])
		{
			$this->errorOutput(NO_FILE);
		}
				
		$original 	= urldecode($_FILES['filedata']['name']);
		$filetype 	= strtolower(strrchr($original, '.'));
		if(!in_array($filetype,array('.json')))
		{
			$this->errorOutput('此文件格式不支持');
		}
		
		$name = date('Y-m-d',TIMENOW) . '-' . TIMENOW . hg_rand_num(6);
		$filename = $name . $filetype;
		$filepath = DATA_DIR . $sort_dir;
		
		if (!hg_mkdir($filepath) || !is_writeable($filepath))
		{
			$this->errorOutput(NOWRITE);
		}
		
		if (!@move_uploaded_file($_FILES['filedata']['tmp_name'], $filepath . $filename))
		{
			$this->errorOutput(FAIL_MOVE);
		}
		
		$json = @file_get_contents($filepath.$filename);
		if($json)
		{
			$data = json_decode($json,1);
			if(!$data)
			{
				return false;
			}
			
			//模板文件路径
			if(!defined('MOBILE_API_TPL'))
			{
				define('MOBILE_API_TPL','../api/apitpl.php');
			}
			
			$tpl = MOBILE_API_TPL;
			
			//获取模板文件
			$tpl_str = @file_get_contents($tpl);
			
			foreach ($data as $val)
			{
				if(empty($val))
				{
					continue;
				}
				unset($val['id']);
				$val['sort_id'] = $sort_id;
				
				$sql = "INSERT INTO ".DB_PREFIX."mobile_deploy SET ";
				foreach ($val as $k => $v)
				{
					$sql .= '`'.$k . "`='" . $v . "',";
				}
				$sql = rtrim($sql,',');
				$this->db->query($sql);
				
				if($tpl_str)
				{
					$val['sort_dir'] = $sort_dir;
					mobile_build_file($val, $tpl_str);
				}
			}
			unlink($filepath.$filename);
			$this->addItem('success');
		}
		
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');