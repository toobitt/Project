<?php
//模板的数据库操作

class template extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//新增模板
	public function create($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."templates SET ";
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
	public function update($data,$table)
	{	
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX ."$table SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id'];
		//file_put_contents('0s',$sql);
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	//更新模板相关信息
	public function cell_update($data)
	{	
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX ."cell SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id']." AND del=0 ";
		$this->db->query($sql);		
	}
	
	//删除模板
	public function delete()
	{	
		$sign = $id_arr = array();
		$ids = urldecode($this->input['id']);
		$sql_ = "SELECT distinct sort_id,site_id FROM  " . DB_PREFIX ."templates  WHERE id IN(" . $ids . ")";
		$q_ = $this->db->fetch_all($sql_);
		if($q_['1'])
		{
			//return  array('error'=>"批量删除请选择模板分类");
			//exit;
		}
		$sql = "SELECT * FROM  " . DB_PREFIX ."templates  WHERE id IN(" . $ids . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{		
			$sign[] = $row['site_id'].'_'.$row['template_style'].'_'.$row['sign'];	
		}
		if($sign)
		{
			foreach($sign as $k=>$v)
			{
				include_once(CUR_CONF_PATH.'lib/cache.class.php');
				$this->cache = new cache();
				$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
				//$this->cache->delete($v);
			}
		}
		
		
		$sq = "DELETE FROM " . DB_PREFIX . "templates WHERE id IN(" . $ids . ")";
		$this->db->query($sq);
		
		$scell = "UPDATE " . DB_PREFIX . "cell set del=1 WHERE template_id IN(" . $ids . ") AND original_id = 0";
		$this->db->query($scell);	
		
		$id_arr = explode(",",$ids);
		if($id_arr[1])
		{
			$path ='../data/template/'.$q_[0]['site_id'].'/'.$q_[0]['sort_id'] ;
			//$this->del_file($path);
		}
		
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//根据条件查询模板
	public function show($condition,$limit,$flag='0')	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."templates 
				WHERE 1".$condition.' ORDER BY id DESC '.$limit ;
		$q = $this->db->query($sql);
		$sql_ = "select name,id from " . DB_PREFIX . "template_sort where 1";
		$template_sorts = $this->db->fetch_all($sql_);
		//取终端类型
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		$clients = $this->pub->get_client();
		$sites = $this->pub->get_site();
		foreach($sites as $k =>$v)
		{
			$site[$v['id']] = $v['site_name'];
		}
		while($row = $this->db->fetch_array($q))
		{
			foreach ($template_sorts as $k=>$v){
				if( $v['id']== $row['sort_id']){
					$row['sort_name'] = $v['name'];
				}
			}
			foreach($clients as $k =>$v)
			{
				if( $v['id'] == $row['client']){
					$row['client'] = $v['name'];
				}
			}
            $row['site_name'] = $row['site_id'] ? $site[$row['site_id']] : '全局模板';
			$row['type'] = $this->settings['template_types'][$row['type']];
			
			if($row['pic'])
			{
				$row['pic'] = json_decode($row['pic'],1);
			}
			if($flag)
			{
				$row['auth'] = $flag;
			}
            unset($row['content']);
			$ret[] = $row;
			$sort_name[$row['sort_name']] = $row['sort_name'];
			
		}
		foreach($clients as $ke =>$va)
		{
			$client[$va['id']] = $va['name'];
		}
		$info[] = $ret;
		$info['sort'] = $sort_name;
		$info['site'] = $site;
		$info['client'] = $client;
		return $info;
	}
	
	//新增模板
	public function edit_update($data)
	{	
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX ."templates SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .= $space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=	$sql_extra;
		$sql .= " WHERE id = ".$data['id'];
		
		$this->db->query($sql);		
	
	}
	
	/**
	 * 插入新的单元
	 * @param array $cells
	 * @param int $columnid
	 * @param int $templateid
	 */
	function insert_new_cell($cells = array(),$cell_info)
	{
		$template_id =  $cell_info['template_id'];
		$template_sign = $cell_info['template_sign'];
		$template_style = $cell_info['template_style'];
		$sort_id = $cell_info['sort_id'];
		$site_id = $cell_info['site_id'];
		$cell_name = $cells[1];
		$cell_code = $cells[0];
		$create_time = TIMENOW;
		$sign = uniqid();
		if($cell_name && is_array($cell_name) && $cell_code && is_array($cell_code) )
		{
			$sql = "INSERT INTO " .DB_PREFIX."cell(
					template_id,
					template_sign,
					template_style,
					site_id,
					sort_id,
					sign,
					cell_name,		
			    	cell_code,
			    	create_time)VALUES";
			for($i = 0; $i < count($cell_name); $i++){		
			$sql .="   (
						'$template_id',
						'$template_sign',
						'$template_style',
						'$site_id',	
						'$sort_id',
						'$sign$i',
						'{$cell_name[$i]}',					
						'{$cell_code[$i]}',					
				    	'$create_time'),";
			}
			$sql_ = substr("$sql",0,-1);
			$this->db->query($sql_);
			return $this->db->insert_id();
		}
		return ture;
	}
	
	/**
	 * 删除单元
	 *
	 * @param array $cells
	 * @param int $templateid
	 */
	function delete_cell($cells = array(),$template_id)
	{
		$delcellname = implode("','",$cells);
		if ($delcellname)
		{
			$sql = "UPDATE ".DB_PREFIX."cell  SET del = 1 WHERE cell_name IN ('".$delcellname."') AND template_id =".$template_id;
			$this->db->query($sql);
		}
	}
	
	/**
	 * 取得数据库已存在单元
	 *
	 * @param int $templateid
	 * @return array
	 */
	function get_exist_cell($template_id)
	{	
		//$sql = "SELECT DISTINCT cellname FROM ".DB_PREFIX."cell WHERE siteid=".$_SESSION['liv_siteid']." AND template_id=".$template_id;
		$sql = "SELECT DISTINCT cell_name FROM ".DB_PREFIX."cell WHERE template_id =".$template_id . " AND del = 0";
		$res = $this->db->query($sql);
		$db_unit_arr = array();
		while($r = $this->db->fetch_array($res))
		{
			$db_unit_arr[] = $r['cell_name'];
		}
		return $db_unit_arr;
	}
	
	/**
	 * 解析单元信息
	 *
	 * @param string $content
	 * @return array
	 */
	function parse_templatecell($content = "")
	{
		$eregtag = '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_([\\s\\S]+?(?=<\/span>))<\/span>/is';
		//$eregtag = '/<span[\s]+id="livcms_cell".+?[\s]+name="(.+?)">([\\s\\S]+?(?=<\/span>))<\/span>/is';
		preg_match_all( $eregtag, $content, $match );
		return $match;
	}
	

	//上传示意图
	/*public function sketchpic($file,$siteid,$sort_id,$temid)
	{
		//创建临时目录存放解压文件
		$tmp_dir = CUR_CONF_PATH.'data/'.$siteid."/".$sort_id."/".$temid.'/sketchpic/';
		if (!hg_mkdir($tmp_dir) || !is_writeable($tmp_dir))
		{
			$this->errorOutput($tmp_dir . '目录不可写');
		}
		
		if(!move_uploaded_file($file['tmp_name'], $tmp_dir . 'pic.jpg'))
		{
			$this->errorOutput('示意图移动失败');
		}
	}*/
	
	//解压压缩包
	public function unzip_info($file,$siteid,$sort_id,$template_style,$client)
	{
        $data_arr = array(
            'site_id' 			=>	$siteid,
            'sort_id' 			=>	$sort_id,
            'template_style' 	=>	$template_style,
            'client' 			=>	$client,
        );
        //验证压缩包内文件格式
        //先解压到temp_template临时目录 方便删除文件
        //data/template  目录按分类存储模板 验证部通过不好删除
        $tmp_dir = CUR_CONF_PATH.'data/temp_template/'.$siteid . '/' . $sort_id . '/';
        if (!hg_mkdir($tmp_dir) || !is_writable($tmp_dir)) {
            $this->errorOutput($tmp_dir . '目录不可写');
        }
        if (!copy($file['tmp_name'], $tmp_dir . 'tem.zip')) {
            $this->errorOutput('zip包复制失败');
        }
        $unzip_cmd = ' unzip -o ' . $tmp_dir. 'tem.zip  -d ' . realpath($tmp_dir);
        exec($unzip_cmd);
        @unlink($tmp_dir . 'tem.zip');
        $error = $this->check_zip($data_arr, realpath($tmp_dir));
        $this->del_file(realpath($tmp_dir));
        if ($error) {
            return array('error' => '上传失败, ' . $error);
        }
        //验证压缩包内文件格式 验证结束


		//开始上传文件
		$dir = CUR_CONF_PATH.'data/template/'.$siteid;
		$tmp_dir = $data = array();
		$tmp_dir = array(
			'tem'		=> $dir.'/'.$sort_id.'/',
			'html'		=> $dir.'/',
		);
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
		$unzip_cmd = ' unzip -o ' . $tmp_dir['tem']. 'tem.zip  -d ' . realpath($uzip_dir);
		exec($unzip_cmd);
        @unlink($tmp_dir['tem'] . 'tem.zip');
		//解压后遍历读取文件,将文件路径存放倒数组中
		$img_arr = array();
		$img_info = array();//存放图片信息的数组

		$file_folders = $this->read_file($data_arr,$tmp_dir,realpath($tmp_dir['tem']),$img_arr);
		$error = $file_folders['error'];
		unset($file_folders['error']);
		if($file_folders['0'])
		{
			$folders = implode(',',$file_folders);
			$sql = "UPDATE ".DB_PREFIX."template_sort  SET folders = '".$folders."' WHERE id  =".$sort_id;
			$this->db->query($sql);
		}

		if($error)
		{
			return array('error'=>'压缩包中部分模板未上传成功') ;
		}

	}

    //验证zip包中文件格式
    private function check_zip($data_arr, $path) {
        if (empty($data_arr) || !$path) {
            return '';
        }
        $path = rtrim($path, '/') . '/';
        $error = '';
        if ($handle = opendir($path)) {
            while ($file = readdir($handle)) {
                if ($file != '.' && $file != '..' && $file[0] != '.') {
                    if ( is_dir($path . $file) ) {
                        $error = $this->check_zip($data_arr, $path  . $file);
                        if ($error) {
                            break;
                        }
                    }
                    else {
                        $ftype = $this->check_type($file);
                        if (!$ftype) {
                            $error = $file . '文件格式不允许上传';
                            break;
                        }
                        if($ftype == 'htm'||$ftype == 'html') {
                            $content = file_get_contents($path . $file);
                            if (strpos($content, '<?') !== false) {
                                $error = '文件' . $file . '内含有非法字符';
                                break;
                            }

                            $template_style = $data_arr['template_style'];
                            $tem_style_default = $this->settings['tem_style_default'];
                            //模板需要先上传到默认套系 验证默认套系中是否存在同名模板  不存在不允许上传
                            if($template_style !=$tem_style_default) {
                                $sql = "SELECT id FROM " . DB_PREFIX . "templates WHERE file_name = '".$file."'"."  AND template_style = '".$tem_style_default. "'" ;
                                $q = $this->db->query_first($sql);
                                if(!$q) {
                                    $error = '先将该模板'.$file.'上传到默认套系';
                                    break;
                                }
                            }

                            //同一套系下不允许有同名模板
                            $sql = "SELECT id FROM " . DB_PREFIX . "templates WHERE title = '".$file."'"."  AND template_style = '".$template_style. "'";
                            $q = $this->db->query_first($sql);
                            if ($q) {
                                $error =  '套系中' . $file . '模板名已存在';
                                break;
                            }
                            //同一套系下不允许有同标识模板
                            $signs = explode(".", $file);
                            $sql = "SELECT id FROM " . DB_PREFIX . "templates WHERE 1 AND sign = '".$signs[0]. "'"."  AND template_style = '".$template_style. "'";
                            $q = $this->db->query_first($sql);
                            if ( $q['id'] ) {
                                $error = '套系中与' . $file . '相同模板标识的模板已存在';
                                break;
                            }
                        }
                    }
                }
            }
            closedir($handle);
        }
        return $error;
    }
	
	//递归读取目录里面的所有文件
	private function read_file($data_arr,$tmp_path,$path1,&$img_arr)
	{
		$file_folders = $error_arr = array();
		if ($handle = opendir($path1))//打开路径成功  
        {
            while ($file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
            	if ($file != '.' && $file != '..'&&is_dir($path1."/".$file))
            	{
					$file_folders[] = $file;            		
            	}
            		
                if ($file != '.' && $file != '..' && $file[0] != '.')//排除当前路径和前一路径  
                {
                    if (is_dir($path1."/".$file))  
                    {
                        $error = $this->read_file($data_arr,$tmp_path,$path1 . '/' . $file,$img_arr);
                        $error_arr = array_merge($error_arr, $error['error']);
                    }
                    else  
                    {
                    	$ftype = $this->check_type($file);
                        if (!$ftype) {
                            $error_arr[$file] = '压缩包内含有非法格式文件';
                            continue;
                        }
                    	if($ftype == 'htm'||$ftype == 'html')
                    	{
                    		$content = file_get_contents($path1 . '/' .$file);
                            if (strpos($content, '<?') !== false) {
                                $error_arr[$file] = '文件' . $file . '内含有非法字符';
                                continue;
                            }
	                    	$this->insert_file($file,$content,$data_arr,$error_arr);
	                    	@unlink($path1 . '/' . $file);
                    	}
                    	if( $this->check_type($file) && $file[0] != '.')
                    	{
	                    	$file_path = $path1 . '/' . $file;
                			$img_arr[] = realpath($file_path);
                			if (is_file($file_path))
                			{
                				if (is_dir($tmp_path[$ftype]))
                				{
                					//@copy($file_path,$tmp_path[$ftype].$file);	
                				}
                			}	
                    	}
                    }  
                }  
            }
            closedir($handle);
        }
        $file_folders['error'] = $error_arr;
        return $file_folders;
	}
	
	public function insert_file($file,$content,$data_arr,&$error_arr)
	{
		$template_style = $data_arr['template_style'];
		$tem_style_default = $this->settings['tem_style_default'];
		$site_id = $data_arr['site_id'];
		if($template_style !=$tem_style_default)
		{
			$sqll = "select id from " . DB_PREFIX . "templates where file_name = '".$file."'"."  AND template_style = '".$tem_style_default. "'" ;
			$ql = $this->db->query_first($sqll);
			if(!$ql)
			{
				$error_arr[$file] = '先将该模板'.$file.'上传到默认套系';
			}
		}

		$sql = "select id from " . DB_PREFIX . "templates where title = '".$file."'"."  AND template_style = '".$template_style. "'";
		$q = $this->db->query_first($sql);
		if($q)
		{
			$error_arr[$file] = $template_style.'下'.$file.'模板名已存在';
		}
		$signs = explode(".",$file);
		$sql_ = "select id from " . DB_PREFIX . "templates where 1 "."  AND sign = '".$signs[0]. "'"."  AND template_style = '".$template_style. "'";
		$q_ = $this->db->query_first($sql_);
		if($q_['id'])
		{
			$error_arr[$file] = $template_style.'下与'.$file.'相同模板标识的模板已存在';
		}
		if(!$error_arr[$file])
		{
			$data = array(
				'title'				=> $file,
		        'sort_id'			=> $data_arr['sort_id'],
		        'file_name'			=> $file,
		        'sign'				=> $signs[0],
			 	'site_id'			=> $data_arr['site_id'],
			 	'client'			=> $data_arr['client'],
				'content'			=> addslashes($content),
				'template_style'    => $template_style,
				'user_id'       	=> $this->user['user_id'],
				'user_name'    		=> $this->user['user_name'],
				'ip'       			=> $this->user['ip'],
				'org_id'			=> $this->user['org_id'],
				'create_time'		=> TIMENOW,
				'update_time'		=> TIMENOW,
                'app_uniqueid'      => $this->input['app_uniqueid'],
                'content_type'      => $this->input['content_type'],
			);
			$ret = $this->create($data);

			include_once(CUR_CONF_PATH.'lib/cache.class.php');
			$this->cache = new cache();
			$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
			$sign = $data['site_id'].'_'.$data['template_style'].'_'.$data['sign'];
			$str = common::set_cache($sign,$content,$data['site_id'],$data['sort_id']);

			$cell_info = array(
				'template_id'		=> 	$ret,
		        'template_sign'		=> 	$signs[0],
		        'sort_id'			=> 	$data_arr['sort_id'],
			 	'site_id'			=> 	$data_arr['site_id'],
			 	'template_style'	=> 	$template_style,
			);
			$file_units = $this->parse_templatecell($content);
			$re = $this->insert_new_cell($file_units,$cell_info);
		}
		return $error_arr;
	}
	
	public function check_type($path)
	{
		$file_config = array();
		$file_config = array(
			'pic'	=>		$this->settings['pic_config'],
			'css'	=>		$this->settings['css_config'],
			'js'	=>		$this->settings['js_config'],
			'html'	=>		$this->settings['html_config'],
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
	
	//递归文件以及目录
	public function rm_file($path)
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
	                    //@unlink($path . '/' . $file);
                    }  
                }  
            }
            @rmdir($path);
            closedir($handle);
        }
	}
	
	//递归删除文件以及目录
	public function del_file($path)
	{
		if ($handle = opendir($path))//打开路径成功  
        {
            while ($file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
                if ($file != '.' && $file != '..')//排除当前路径和前一路径  
                {
                    if (is_dir($path."/".$file))  
                    {
                        $this->del_file($path . '/' . $file);
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

	public function recurse_copy($src,$dst) 
	{  // 原目录，复制到的目录
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
		    if (( $file != '.' ) && ( $file != '..' )) {
		        if ( is_dir($src . '/' . $file) ) {
		            $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
		        }
		        else {
		            copy($src . '/' . $file,$dst . '/' . $file);
		            }
		        }
		    }
		    closedir($dir);
		}

	/*public function zip($dir,$filename,$missfile=array(),$addfromString=array())
	{
		if(!file_exists($dir) || !is_dir($dir)){
			return array('error'=>' can not exists dir '.$dir);
		}
		if(strtolower(end(explode('.',$filename))) != 'zip'){
			return array('error'=>'only Support zip files');
		}
		$dir = str_replace('\\','/',$dir);
		$filename = str_replace('\\','/',$filename);
		if(file_exists($filename)){
			return array('error'=>'the zip file '.$filename.' has exists !');
		}
		$files = array();
		$this->getfi$dir,$files);
		if(empty($files)){
			return array('error'=>' the dir is empty');
		}
		
		$zip = new ZipArchive;
		$res = $zip->open($filename, ZipArchive::CREATE);
		if ($res === TRUE) 
		{
			foreach($files as $v){
				if(!in_array(str_replace($dir.'/','',$v),$missfile)){
					$zip->addFile($v,str_replace($dir.'/','./',$v));
				}
			}
			if(!empty($addfromString))
				{
				foreach($addfromString as $v){
					$zip->addFromString($v[0],$v[1]);
				}
			}
			$zip->close();
		} 
		else 
		{
			return array('error'=>'failed');
		}
	}*/

	public function getfiles($dir,&$files=array())
	{
		if(!file_exists($dir) || !is_dir($dir))
		{return;}
		if(substr($dir,-1)=='/'){
			$dir = substr($dir,0,strlen($dir)-1);
		}
		$_files = scandir($dir);
		foreach($_files as $v){
			if($v != '.' && $v!='..'){
				if(is_dir($dir.'/'.$v)){
					$this->getfiles($dir.'/'.$v,$files);
				}
				else
				{
					$files[] = $dir.'/'.$v;
				}
			}
		}
		return $files;
	}
	
	public function download($file_dir,$source,$filename)
	{
		if (!file_exists($file_dir . $source)) 
		{ 
			echo '文件不存在！';
			exit;
		}
		else
		{
			header("Cache-Control: public");   
			header("Content-Description: File Transfer");   
			header('Content-disposition: attachment; filename='.basename($filename)); //文件名  
			header("Content-Type: application/zip"); //zip格式的  
			header("Content-Transfer-Encoding: binary");    //告诉浏览器，这是二进制文件   
			header('Content-Length: '. filesize($source));    //告诉浏览器，文件大小  
			@readfile($source);
			@unlink($source);
			//@rmdir($path);
			//$this->del_all_dir($path);
		}
	
	}
	
	public function get($file_dir,$source,$filename)
	{
		if (!file_exists($file_dir . $source)) 
		{ 
			echo '文件不存在！';
			exit;
		}
		else
		{
			header("Cache-Control: public");   
			header("Content-Description: File Transfer");   
			header('Content-disposition: attachment; filename='.basename($filename)); //文件名  
			header("Content-Type: application/zip"); //zip格式的  
			header("Content-Transfer-Encoding: binary");    //告诉浏览器，这是二进制文件   
			header('Content-Length: '. filesize($source));    //告诉浏览器，文件大小  
			@readfile($source);
			@unlink($source);
			//@rmdir($path);
			//$this->del_all_dir($path);
		}
	
	}
	
	function import_tem_info($data,$table)
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
	
	
	function get_preg_match($content,$site_id='',$sort_id='')
	{
		$pregs         = array(
            '0'=>'/<link(.*?)href=(\'|\")(?!(\s*(http[s]?\:\/\/)?\s*www\.|\s*http[s]?\:\/\/))\s*([^"]+)\/([^"]+)\\2(.*?)[\/]?>/i',
            '1'=>'/src=(\'|\")(?!(\s*(http[s]?\:\/\/)?\s*www\.|\s*http[s]?\:\/\/))\s*([^"]+)\/([^"]+)\\1/i',
            '2'=>'/url\([\'|\"]?(?!(\s*(http[s]?\:\/\/)?\s*www\.|\s*http[s]?\:\/\/))\s*([^\'"]+?)[\'|\"]?\)/i'
        );
        preg_match_all($pregs[0], $content, $m);
        preg_match_all($pregs[1], $content, $ma);
       	preg_match_all($pregs[2], $content, $mac);
       	$link  = $src = $url = $pic_ =  array();
       			
       	if($m['5'] && is_array($m['5']))
       	{
       		$css_pic = $pic_ = array();
       		foreach($m['5'] as $k=>$v)
       		{
       			$link[] = $v.'/'.$m['6'][$k];
       			if($site_id && $sort_id)
       			{
       				$dir =  '../data/template/'.$site_id.'/'.$sort_id.'/'.$v.'/'.$m['6'][$k];
       				$re = file_exists($dir);
       				if($re)
       				{
       					$css = file_get_contents($dir);
       				}
       				else
       				{
       					$css = '';
       				}
       				
       				preg_match_all($pregs[2], $css, $cpic);
        			if($cpic['3'] && is_array($cpic['3']))
       				{
       					foreach($cpic['3'] as $kk => $vv)
       					{
   							$pic_[] = str_replace("../","",$vv);
       					}
       				}
       			}
       		}
       	}
       	
       	if($ma['4'] && is_array($ma['4']))
       	{
       		foreach($ma['4'] as $k=>$v)
       		{
       			$src[] = $v.'/'.$ma['5'][$k];
       		}
       	}
       	if($mac['3'] && is_array($mac['3']))
       	{
       		foreach($mac['3'] as $k=>$v)
       		{
       			$url[] = $v;
       		}
       	}
       	
       	$ar = array_merge($link,$src);
       	$return = array_merge($ar, $url);
       	$ret = array_merge($return, $pic_);
       	
       	return $ret ;
       	
	}
	
	//每一级分类
	public function get_tem_sort($fid=0)
	{
		//$sql = 'SELECT * FROM '.DB_PREFIX.'template_sort  WHERE fid=' . intval($fid) .' ORDER BY order_id ASC';
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'template_sort  WHERE 1 ORDER BY order_id ASC';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$re[] = $row;
		}
		return $re;
	}
	
}


?>