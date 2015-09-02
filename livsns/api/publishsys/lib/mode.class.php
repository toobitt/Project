<?php
//样式的数据库操作
class mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//新增样式
	public function create($data)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."cell_mode SET ";
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
	
	//更新样式和样式参数相关信息
	public function update($data,$table_name)
	{	
		//样式数据操作
		$sql = "UPDATE " . DB_PREFIX .$table_name." SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id'];
		$this->db->query($sql);	
		return $this->db->affected_rows();
	}
	
	public function update_css_code($data, $table, $where = '') 
	{
		if($table == '' or $where == '') 
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$field = '';
		if(is_string($data) && $data != '') 
		{
			$field = $data;
		} 
		elseif (is_array($data) && count($data) > 0) 
		{
			$fields = array();
			foreach($data as $k=>$v) 
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		} 
		else 
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET '.$field.$where;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function update_cell_var($id,$data=array(),$flag='')
	{
		$sqll="DELETE FROM " . DB_PREFIX . "cell_mode_variable WHERE cell_mode_id  =".$id;
		if($flag)
		{
			$sqll .= " AND data_param = ".$flag;
		}
		
		$this->db->query($sqll);
		
		if(!$data)
		{
			return 1 ;
		}
		$name = $sign = $value = $default_value = $other_value = $type = array();
		$name =  $data['name'];
		$sign =  $data['sign'];
		$value =  $data['value'];
		$default_value =  $data['default_value'];
		$other_value =  $data['other_value'];
		$type =  $data['type'];
		if(!$flag)
		{
			$sql = "INSERT INTO " .DB_PREFIX."cell_mode_variable(
						cell_mode_id,
						name,
						sign,
						value,
						default_value,
						other_value,
						type)VALUES";
			for($i = 0; $i < count($name); $i++){		
			$sql .="   (
						'$id',
						'{$name[$i]}',	
						'{$sign[$i]}',					
						'{$value[$i]}',					
				    	'{$default_value[$i]}',
				    	'{$other_value[$i]}',
				    	'{$type[$i]}'),";
			}
		}
		else
		{
			$sql = "INSERT INTO " .DB_PREFIX."cell_mode_variable(
						cell_mode_id,
						name,
						sign,
						value,
						default_value,
						other_value,
						type,
						data_param)VALUES";
			for($i = 0; $i < count($name); $i++){		
			$sql .="   (
						'$id',
						'{$name[$i]}',	
						'{$sign[$i]}',					
						'{$value[$i]}',					
				    	'{$default_value[$i]}',
				    	'{$other_value[$i]}',
				    	'{$type[$i]}',
				    	'$flag'),";
			}
		}
		$sql_ = substr("$sql",0,-1);
		$this->db->query($sql_);
		return $this->db->insert_id();
	}
	
	//新增样式css、js
	public function create_code($data)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."cell_mode_code  SET ";
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
	
	//更新样式和样式参数相关信息
	public function update_js_code($data)
	{	
		$sql = "SELECT * FROM ".DB_PREFIX."cell_mode_code WHERE mode_id=".$data['mode_id'] ." AND type = '".$data['type'] ."'";
		$re =  $this->db->query_first($sql);
		if($re)
		{
			unset($data['sign']);
			$sql = "UPDATE " . DB_PREFIX ."cell_mode_code  SET ";
			$sql_extra = $space ='';
			foreach($data as $k=>$v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
			$sql .= " WHERE mode_id =".$data['mode_id']." AND type = '".$data['type'] ."'";
			$this->db->query($sql);	
			return $this->db->affected_rows();
		}
		else
		{
			$ret = $this->create_code($data);
		}
		
		
	}
	
	//删除样式
	public function delete($ids)
	{	
		$sq = "DELETE FROM " . DB_PREFIX . "cell_mode  WHERE id IN(" . $ids . ")";
		$this->db->query($sq);
		
		$sql = "DELETE FROM " . DB_PREFIX . "cell_mode_code  WHERE mode_id IN(" . $ids . ")";
		$this->db->query($sql);
		
		$sqll = "DELETE FROM " . DB_PREFIX . "cell_mode_variable  WHERE cell_mode_id IN(" . $ids . ")";
		$this->db->query($sqll);
		
		$s = "DELETE FROM " . DB_PREFIX . "out_variable  WHERE mod_id =2 AND expand_id IN(" . $ids . ")";
		$this->db->query($s);
		
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//样式上传
	public function upload($info)
	{	
		$sql = "UPDATE " . DB_PREFIX ."cell_mode SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);	
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	
	//根据条件查询样式
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."cell_mode 
				WHERE 1".$condition.' ORDER BY id DESC'.$limit;
		$q = $this->db->query($sql);
		$sqll = "select id,name from " . DB_PREFIX . "cell_mode_sort where 1";	
		$ql = $this->db->query($sqll);
		$rett = array();
		while($r = $this->db->fetch_array($ql))
		{
			$rett[$r['id']] = $r['name'];
		}
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		$sites = $this->pub->get_site();
		foreach($sites as $k =>$v)
		{
			$site[$v['id']] = $v['site_name'];
		}
		while($row = $this->db->fetch_array($q))
		{	
			$row['create_time'] = date('Y-m-d H:i',$row['update_time']);					
			$row['sort_name']  = $rett[$row['sort_id']];
			//$row['site_name'] = $site[$row['site_id']];
			$row['indexpic'] =	unserialize($row['indexpic']);
			if('1' == $row['is_default'])
			{
				$row['is_default'] = '是';
			}
			else
			{
				$row['is_default'] = '否';
			}
			$row['export_url'] = 'http://' . $this->settings['App_publishsys']['host'].'/'.$this->settings['App_publishsys']['dir']. 'admin/mode.php?a=export_mode&id=' . $row['id'] . '&access_token=' . $this->user['token'];		
			$ret[] = $row;
		}
		$info[] = $ret;
		return $info;
	}
	
	public function get_mode_node($condition,$limit)
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
	
	//解压压缩包
	public function unzip_info($file,$mode_id='')
	{
		//创建临时目录存放解压文件
		$tmp_dir = array();
		$tmp_dir = array(
			'tem'		=> CUR_CONF_PATH."data/mode/tem/",
		);
		if($mode_id)
		{
			$tmp_dir['pic'] = CUR_CONF_PATH."data/mode/".$mode_id."/";
		}
		else
		{
			$tmp_dir['pic'] = CUR_CONF_PATH."data/mode/default/";
		}
		foreach($tmp_dir as $k=>$v)
		{
			if (!hg_mkdir($v) || !is_writeable($v))
			{
				$this->errorOutput($v . '目录不可写');
			}
		}
		
		if(!move_uploaded_file($file['tmp_name'], $tmp_dir['tem'] . 'tem.zip'))
		{
			$this->errorOutput('zip包移动失败');
		}
		//开始解压
		$uzip_dir = $tmp_dir['tem'];//解压后存放文件的目录
		if (!hg_mkdir($uzip_dir) || !is_writeable($uzip_dir))
		{
			$this->errorOutput($uzip_dir . '目录不可写');
		}
		$unzip_cmd = ' unzip ' . $tmp_dir['tem']. 'tem.zip  -d ' . realpath($uzip_dir);
		//file_put_contents('03as',$unzip_cmd);
		exec($unzip_cmd);
		//解压后遍历读取文件,将文件路径存放倒数组中
		$img_arr = array();
		$img_info = array();//存放图片信息的数组
		@unlink($tmp_dir['tem'] . 'tem.zip');
		$this->read_file($tmp_dir,realpath($tmp_dir['tem']),$img_arr);
		$this->rm_file(realpath($tmp_dir['tem']));//删除临时文件夹tem
		
		return 1 ;
	}
	
	//递归读取目录里面的所有文件
	public function read_file($pic_dir,$path,&$img_arr)
	{
		if ($handle = opendir($path))//打开路径成功  
        {
            while ($file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
                if ($file != '.' && $file != '..')//排除当前路径和前一路径  
                {
                    if (is_dir($path."/".$file))  
                    {
                        $this->read_file($pic_dir,$path . '/' . $file,$img_arr);
                    }  
                    else  
                    {
                    	$ftype = $this->check_type($file);
                    	if( $this->check_type($file) && $file[0] != '.')
                    	{
	                    	$file_path = $path . '/' . $file;
                			$img_arr[] = realpath($file_path);
                			if (is_file($file_path))
                			{
                				if (is_dir($pic_dir[$ftype]))
                				{
                					@copy($file_path,$pic_dir[$ftype].$file);	
                				}
                			}	
                    	}
                    }  
                }  
            }
            closedir($handle);
        }
	}

	
	private function check_type($path)
	{
		$file_config = array();
		$file_config = array(
			'pic'	=>		$this->settings['pic_config'],
		);
		$typetmp = explode('.',$path);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		foreach($file_config as $k =>$v)
		{
			if(in_array($filetype,$v))
			{
				return $k;
			}
		}
	}
	
	//递归删除文件以及目录
	private function rm_file($path)
	{
		if ($handle = opendir($path))//打开路径成功  
        {
            while ($file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
                if ($file != '.' && $file != '..')//排除当前路径和前一路径  
                {
                    if (is_dir($path."/".$file))  
                    {
                        $this->rm_file($path . '/' . $file);
                    }  
                    else  
                    {
	                    @unlink($path . '/' . $file);
                    }  
                }  
            }
            @rmdir($path);
            closedir($handle);
        }
	}
	
	//获取样式代码$参数
	public function get_para($code)	
	{	
		$te = "/\\$([a-zA-Z_]+[0-9_]*)([\s|,|\+|'|\=|\)\}])/is";
		//preg_match_all('/\{(?:if\s+)?(?:else\s+if\s+)?\#(.*?)\}/',$code,$arr);
		preg_match_all($te, $code, $m);
		$para = array_unique($m[1]);
		return $para;
	}
	
	public function create_out_para($name,$fid,$mode_id,$flag='0')
	{
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		$curl->addRequestData('fid',$fid);
		$curl->addRequestData('name',$name);
		$curl->addRequestData('mode_id',$mode_id);
		$curl->addRequestData('flag',$flag);
		$fid = $curl->request('mode_node.php');
		return $fid[0];
	}
	
	public function delete_out_para($id)
	{
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','delete');
		$curl->addRequestData('id',$id);
		$fid = $curl->request('mode_node.php');
		return $fid[0];
	}
	
	function import_mode_info($data,$table)
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
	
	function query_para($data,$table)
	{
		if(is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$sql = "SELECT * FROM ".$table."WHERE ".$k ."=".$v;
			}
			return $this->db->query_first($sql);
		}
	}
	
	public function delete_mode_para($conds = array(),$table)
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
	
			
	/**
	 * 公共入库方法 ...
	 * @param array $data 数据
	 * @param string $dbName  数据库名
	 */
	public function insert_para_name($data,$dbName,$flag=0)
	{		
		if (!$data || !is_array($data) || !$dbName)
		{
			return false;
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.$dbName.' SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		if ($flag)
		{
			return $this->db->insert_id();
		}
		return true;
	}
	
	//获取代码参数名
	public function get_code_para_name($type,$params)	
	{	
		$pa_arr = explode(',',$params);
		
		$sql = "select 	*  from " . DB_PREFIX . "cell_code_para_name  where type = '".$type."'";	
		$ql = $this->db->query($sql);
		$rett = array();
		while($r = $this->db->fetch_array($ql))
		{
			if(in_array($r['sign'],$pa_arr))
			{
				$rett[$r['sign']][$r['id']] = $r['name'];
			}
		}
		if($pa_arr)
		{
			foreach($pa_arr as $k=>$v)
			{
				if($rett[$v])
				{
					$data[$v] = $rett[$v];
				}
				else
				{
					$data[$v] = array();
				}
			}
		}
		return $data;
	}
	
	
	//获取代码参数信息
	public function get_para_name($type,$sign)	
	{	
		$sign_str = implode('","', explode(',', $sign));
		$sql = 'SELECT * FROM  '.DB_PREFIX.'cell_code_para_name  WHERE sign IN("'.$sign_str.'") AND type = "'.$type.'"';
		
		$ql = $this->db->query($sql);
		$rett = array();
		while($r = $this->db->fetch_array($ql))
		{
			$rett[$r['sign']][$r['id']]['name'] = $r['name'];
			$rett[$r['sign']][$r['id']]['default_value'] = $r['default_value'];
			$rett[$r['sign']][$r['id']]['para_type'] = $r['para_type'];
			$rett[$r['sign']][$r['id']]['other_value'] = $r['other_value']?unserialize($r['other_value']):array();
		}
		return $rett;
	}
	
	public function  insert_para()
	{
		//更新评论索引表
			$sql = "REPLACE INTO " . DB_PREFIX . "comment_index ( cmid, com_type, sort_id, comment_count, create_time, update_time) VALUES 
					( '{$cmid}', '{$com_type}','{$data['groupid']}',  " . $count . ",  '{$res['create_time']}', " . TIMENOW . ")";
			$this->db->query($sql);
			return $data;
			
			/*$sql = "INSERT INTO " .DB_PREFIX."cell_mode(
						special_id,
						title,
						content)VALUES";
			for($i = 0; $i < count($mode_info); $i++){		
			$sql .="   (
						'$special_id',
						'{$title[$i]}',
						'{$content[$i]}'),";
			}
			$sql_ = substr("$sql",0,-1);
			$this->db->query($sql_);
			return $this->db->insert_id();*/
	}
}


?>