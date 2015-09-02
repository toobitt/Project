<?php
define('MOD_UNIQUEID','template');//模块标识
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class templateApi extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
            'manage' => '管理',
            '_node' => array(
                'name' => '模板应用',
                'filename' => 'publishsys_node.php',
                'node_uniqueid' => 'publishsys_node',
            ),
        );
        
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/template.class.php');
		$this->obj = new template();
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		require_once(ROOT_PATH . 'lib/class/publishsys.class.php');
		$this->pus = new publishsys();
		include(CUR_CONF_PATH . 'lib/common.php');
		include_once(CUR_CONF_PATH.'lib/cache.class.php');
		$this->cache = new cache();
		include(CUR_CONF_PATH . 'lib/download.class.php');
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
        $this->auth = new Auth();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		$flag = '';

		$c = $this->input['c'];
		$tem = '';
		if($c == 'download')
		{	
			$tem = $this->download($this->input['d']);
		}
		$site_id = isset($this->input['site_id']) ? $this->input['site_id'] :1;
		$condition = $this->get_condition($site_id);
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit,$flag);	
		
		$template_styles = common::get_template_style($this->input['site_id']);
		foreach($template_styles as $ke =>$va)
		{
			$styles[$va['mark']] = $va['title'];
		}
		$ret['template_styles'] = $styles;
		$ret['c'] = $tem ;
		$ret['auth'] = $flag; 
		$this->addItem($ret);	
		$this->output();		
	}

	public function detail()
	{
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'templates WHERE id = '.$this->input['id'];
		$ret = $this->db->query_first($sql);

        if (!empty($ret))
        {
            $sq = "select name from " . DB_PREFIX . "template_sort where id = ".$ret['sort_id'];
            $sort_name = $this->db->query_first($sq);
            $ret['sort_name'] = $sort_name['name'];
            if($ret['pic'])
                {
                    $ret['pic'] = json_decode($ret['pic'],1);
                    if(is_array($ret['pic']) && count($ret['pic'])>0)
                    {
                        $ret['pic_json'] = array();
                        foreach($ret['pic'] as $k => $v)
                        {
                            $tmp = array();
                            $tmp[0] = $v;
                            $ret['pic_json'][$k] = htmlspecialchars(json_encode($tmp));
                        }
                    }
                }
            /*$dir = opendir($r['file_path']);
            while (($file = readdfir($dir)) != false)
              {
                if($file != '.' && $file !='..')
                  {
                    //$file = "/Users/gaoyuan/web/livsns/api/publishsys/template__/1.jpg";
                    $r['material'] = "<img src=".PI." width='200' height='300'><br />";
                  }
              }
            closedir($dir);*/
            //取终端
            $ret['content'] = str_replace("#$23", '&nbsp;', $ret['content']);
        }
		$html = $this->editer('content' , $ret['content'] , array('cols' => 60 , 'rows' =>20));
		//file_put_contents('0',$html);
		$ret['html'] = $html;
		$re[] = $ret;
		$this->addItem($re);
		$this->output();
	}
	
	
	
	public function show_tem()
	{
		$temp = '';
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'templates WHERE id = '.$this->input['id'];
		$ret = $this->db->query_first($sql);
		
		$sign = $ret['site_id'].'_'.$ret['template_style'].'_'.$ret['sign'];
		$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
		$str = common::set_cache($sign,$ret['content'],$ret['site_id'],$ret['sort_id']);
		$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
		$temp = $this->cache->get($sign);
		
		$template = str_replace('{$image_url}',$this->settings['template_image_url'],$temp);
		$path = '../data/template/'.$ret['site_id'].'/'.$ret['sort_id'].'/'.$ret['file_name'];
		//$path = 'http://api.dev.hogesoft.com/publishsys/data/template/'.$ret['site_id'].'/'.$ret['sort_id'].'/'.$ret['file_name'];
		//$real_path = 'http://localhost/livsns/api/publishsys/data/template/'.$ret['site_id'].'/'.$ret['sort_id'].'/'.$ret['file_name'];
		$real_path = $this->settings['template_image_url'].'/'.$ret['site_id'].'/'.$ret['sort_id'].'/'.$ret['file_name'];
		$dir = CUR_CONF_PATH.'data/template/'.$ret['site_id']."/".$ret['sort_id'];
		hg_mkdir($dir);
		
		file_put_contents($path,$template);
		
		$re[] = $real_path;
		$this->addItem($re);
		$this->output();
	}
	
	
	public function edit()
	{	
		if (!($this->input['id']))
		{	
			return false;
		}
		$sql = 'SELECT content 
				FROM '.DB_PREFIX.'templates WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		if(empty($r['content']))
		{
			$this->errorOutput('模板数据不存在！');
		}
		$html = $this->editer('content' , $r['content'] , array('cols' => 60 , 'rows' =>20));
		
		$r['html'] = $html;
		$r['template_types'] = $this->settings['template_types'];
		unset($r['content']);
		$this->addItem($r);
		$this->output();
	}
	
	public function edit_cell()
	{	
		if (!($this->input['id']))
		{	
			return false;
		}
		$sql = 'SELECT * 
				FROM '.DB_PREFIX.'cell WHERE id = '.$this->input['id'];
		$cell = $this->db->query_first($sql);
		
		if($cell)
		{
			$cell['param'] = unserialize($cell['param_asso']);
		}
		if($cell['cell_mode'] && $cell['data_source'])
		{
			$cell_mode_param = common::get_mode_variable($cell['cell_mode']);
			if($cell_mode_param['mode_param'])
			{
				foreach ($cell_mode_param['mode_param'] as $k => $v)
				{
					$mode_param[$v['sign']] = $v;
				}
				$cell_mode_param['mode_param'] = $mode_param;
			}			
			$data_source_param = common::get_datasource_info($cell['data_source']);
			if($data_source_param['input_param'])
			{
				foreach ($data_source_param['input_param'] as $k => $v)
				{
					$input_param[$v['sign']] = $v;
				}
				$data_source_param['input_param'] = $input_param;
			}
		}
		$block = common::get_block_list();
		$ret = array('cell' => $cell,'cell_mode_param' => $cell_mode_param,'data_source_param' => $data_source_param,'block' => $block);
		$this->addItem($ret);
		$this->output();
	}
	
	
	
	public function editer($id , $content , $conf = array())
	{
		!$conf && $conf = array('rows' => 20);
		$html = <<<EOT
<div id="{$id}_container" class="editor_container clearfix">
	<textarea id="{$id}_line" class="editor_line" cols="5" rows="{$conf['rows']}" disabled="disabled"></textarea>
	<textarea id="{$id}" name="{$id}" class="editor" cols="{$conf['cols']}" rows="{$conf['rows']}">{$content}</textarea>
</div>
	<script>
		$("#{$id}").bind('focus' , function(){return editor.focus(this.id);});
	</script>
EOT;
		return $html;
	}
	
	public function download()
	{	
		$id = $this->input['id'];
		$sql = "SELECT *
				FROM " . DB_PREFIX . "templates
				WHERE id = " . $id;		
		$templateinfo = $this->db->query_first($sql);
		
		$site_id = $templateinfo['site_id'];
		$sort_id = $templateinfo['sort_id'];
		$dir = '../data/template/'.$site_id.'/'.$sort_id;
		
		if (!is_dir($dir))
		{
			hg_mkdir($dir);
		}
		//$zip = new zipfile();
		$filename = $templateinfo['file_name']; //下载的默认文件名
		
		@unlink($dir . '/' . $filename);
		
		$fp = fopen($dir.'/' . $filename, "w");
		fwrite($fp,$templateinfo['content']);
		fclose($fp);
		
		//$url =  'http://' . $this->settings['App_publishsys']['host'].':8080/'.$this->settings['App_publishsys']['dir'].'data/template/'.$site_id.'/'.$sort_id;
		header("Content-type: application/octet-stream");
		header("Accept-ranges: bytes");
		header("Accept-length: ".@filesize($dir . '/' . $filename));
		header("Content-disposition: attachment; filename='".$filename."'");
		//echo file_get_contents($dir . '/' . $filename);
		//@readfile($url . '/' . $filename);
        echo $templateinfo['content'];
		
		exit();
	}
	
	
	public function zip_download()
	{

		$site_id = $this->input['site_id'];
		$sort_id = $this->input['sort_id'];
		$sql = "SELECT *
				FROM " . DB_PREFIX . "template_sort
				WHERE id = " . $sort_id;		
		$sort = $this->db->query_first($sql);
		$templatedir  = TEMPLATES_DIR. '/'.$site_id.'/'.$sort_id.'/';
		if(!is_dir($templatedir))
		{
			hg_mkdir($templatedir);
		}
		if($sort['folders'])
		{
			$folder = explode(',',$sort['folders']);
			foreach($folder as $k=>$v)
			{
				if(!is_dir($templatedir.$v))
				{
					hg_mkdir($templatedir.$v);
				}
			}
		}
		$folder = explode(',',$sort['folders']);
		//$source_folders = array();//该分类下的所有素材目录
		$sortname = $sort['name'];
		
		$sql_ = "SELECT *
				FROM  " . DB_PREFIX ."templates 
				WHERE  sort_id = ".$sort_id .' AND site_id = ' .$site_id;
		$q_ = $this->db->query($sql_);
		while($row = $this->db->fetch_array($q_))
		{	
			if (function_exists('iconv'))
			{
				$filename = iconv("utf-8","gb2312",$row['file_name']);
			}
			else
			{
				$filename = 'tpl_' . $row['id'] . strrchr($row['file_name'], '.');
			}
			$content = $row['content'];
			$fp = fopen($templatedir . $filename, "w");
			fwrite($fp,$content);
			fclose($fp);
		}	
		
		$dir = CUR_TEMPLATE_PATH . $site_id.'/'.$sort_id;
		if (!is_dir($dir))
		{
			hg_mkdir($dir);
		}
		
		//$sortname = '1';
		$cmd  = "cd " .  CUR_TEMPLATE_PATH . $site_id . "\n";
		$cmd .= 'zip -r ' . $sort_id . '.zip ' . $sort_id . '/';
		exec($cmd);
		
		$url =  DATA_URL . '/template/'.$site_id.'/'.$sort_id.'.zip';
		$this->addItem($url);
		$this->output();
	}
		//$cmd .= 'zip -r ' . CUR_TEMPLATE_PATH . $site_id . '/' . $sortname . '.zip ' . $dir . '/';
		/*$filename = $sortname . '.zip ';
		$file_path  = CUR_TEMPLATE_PATH . $site_id . '/' . $sortname . '.zip ';  
		header("Cache-Control: public");   
		header("Content-Description: File Transfer");   
		header("content-disposition: attachment; filename='".$filename."'");
		header("Content-Type: application/zip"); //zip格式的  
		header("Content-Transfer-Encoding: binary");    //告诉浏览器，这是二进制文件   
		header('Content-Length: '. filesize($file_path));    //告诉浏览器，文件大小  
		@readfile($file_path);*/  
		
	
		/*$zip = new zipfile();
		$filename = $sortname.'.zip'; //下载的默认文件名
		@unlink($dir . '/' . $filename);
		$zip->add_path($dir);
		
		$zip->output($dir.'/'.$filename);
		
		$data = array(
				'zipname'=>$filename,
				'zipfilename'=>DATA_URL.'template/'.$site_id.'/'.$sort_id.'/'.$filename,
			);*/
	
		/*
		if (!$record_filename)
		{
			$this->errorOutput('该分类下尚无添加任何模板,无法下载');
		}
		$all_templates = ' ' . implode(' ', $record_filename);
		
		$tem= './';
		if(!is_dir($tem))
		{
			hg_mkdir($tem);
		}
		$this->obj->recurse_copy(TEMPLATES_DIR.$site_id.'/'.$sort_id.'/',$tem);
		
		/*if(PATH_SEPARATOR==':') 
		{
			$operateSystem =  'linux';  
		}
		else {
			$operateSystem =  'windows'; 
		}
		if($operateSystem == "windows")
		{
			$source_folders = " " . './' . str_replace(','," ./",$source_folders);
			$rar_sortname = $sortname . '.rar';
			$cmd = "../../../../common/command/windows/winrar/winrar.exe  a -y  ./" . $sortname . ".rar " . $source_folders . $all_templates;	//压缩命令
			$cmd = eregi_replace('/','\\',$cmd);
		}*/
		
		/*if($sort['folders'])
		{
			$source_folders = " " . './' . str_replace(','," ./",$sort['folders']);
			$folders_arr = explode(' ',$source_folders);
			unset($folders_arr[0]);
		}
		if($all_templates)
		{
			$all_templates =  str_replace(' ',"  ./",$all_templates);
			$tem_arr = explode('  ',$all_templates);
			unset($tem_arr[0]);
		}
		$rar_sortname = $sortname . '.tar.gz';
		$cmd = "tar -zcf  ./" . $sortname . ".tar.gz  " .$source_folders .$all_templates;	//压缩命令
		exec($cmd);
		foreach($folders_arr as $k=>$v)
		{
			$this->obj->del_file($v);
		}
		foreach($tem_arr as $k=>$v)
		{
			 @unlink($v);
		}
		
		$data = array(
				'zipname'=>$sortname . ".tar.gz",
				'zipfilename'=>$this->settings['zip']['protocol'].$this->settings['zip']['host'].'/'.$this->settings['zip']['dir'].'/'.$sortname . ".tar.gz",
			);
		$this->addItem($data);
		$this->output();*/
	
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$site_id = isset($this->input['site_id']) ? $this->input['site_id'] :1;
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'templates WHERE 1 '.$this->get_condition($site_id);
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
	}
	
	 //获取应用
    public function get_app()
    {
        $apps = $this->auth->get_app();
        if (is_array($apps))
        {
            foreach ($apps as $k => $v)
            {
                $ret[$v['bundle']] = $v['name'];
            }
        }
        $ret['0'] = '其他';
        $this->addItem($ret);
        $this->output();
    }
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function get_condition($site_id)
	{		
		$condition = '';
		//查询应用分组
		if($site_id)
		{	
			$condition .=" AND site_id IN(0, " . $site_id . ")";
		}
		
		if($this->input['sign'])
		{	
			$condition .=" AND sign !=''";
		}
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim($this->input['k']).'%"';
		}
		if ($this->input['sort_id']!='' && $this->input['sort_id']!= -1)
		{	
			$condition .= " AND sort_id = ". $this->input['sort_id'];		
		}
		if ($this->input['app_uniqueid'] && $this->input['app_uniqueid']!= -1)
		{	
			$condition .= " AND app_uniqueid = '". $this->input['app_uniqueid'] ."'";		
		}
		return $condition;
	}
	
	//获取模板分类名称
	public function get_sort_name()	
	{	
		$sql = "select id,name from " . DB_PREFIX . "template_sort where 1";	
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['name'];
		}
		$this->addItem($ret);
		$this->output();
	}	
	
	
	
	/**
     * 获取模板分类
     * */
    public function get_tem_sort()
    {
        //$fid  = $this->input['fid'];

        $ret = $this->obj->get_tem_sort();
        if ($ret)
        {
            foreach ($ret as $k => $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }
    
	//获取站点名称
	public function get_site()	
	{	
		$sites = $this->pub->get_site();
		foreach($sites as $k =>$v)
		{
			$site[$v['id']] = $v['site_name'];
		}
		$this->addItem($site);
		$this->output();
	}	
	
	//获取客户端名称
	public function get_site_client()	
	{	
		$clients = $this->pub->get_site_client($this->input['site_id']);
		if(is_array($clients))
		{
			foreach($clients['client'] as $ke =>$va)
			{
				$client[$va['id']] = $va['name'];
			}
		}
		$this->addItem($client);
		$this->output();
	}	
	
	//获取套系名称
	public function get_template_style()	
	{	
		$template_styles = common::get_template_style($this->input['site_id']);
		foreach($template_styles as $ke =>$va)
		{
			$styles[$va['mark']] = $va['title'];
		}
		$this->addItem($styles);
		$this->output();
	}	
	
	//获取样式
	public function get_cell_mode()
	{
		$cell_mode = common::get_mode();
		$this->addItem($cell_mode);
		$this->output();
	}
	//获取数据源
	public function get_data_source()
	{
		$data_source = common::get_data_source();
		$this->addItem($data_source);
		$this->output();
	}
	
	//获取样式和数据源的参数
	public function get_cell_data_param()
	{
		$cell_mode_id = intval($this->input['cell_mode_id']);
		$data_source_id = intval($this->input['data_source_id']);
		if(!$cell_mode_id || !$data_source_id)
		{
			$this->errorOutput('noid');
		}
		$cell_mode_param = common::get_mode_variable($cell_mode_id);
		$data_source_param = common::get_datasource_info($data_source_id);
		$ret = array('cell_mode_param' => $cell_mode_param,'data_source_param' => $data_source_param);
		$this->addItem($ret);
		$this->output();
	}
	
	function create_block_form()
	{
		$data_source = common::get_data_source();
		include_once(ROOT_PATH.'lib/class/publishconfig.class.php');
		$this->pub_config= new publishconfig();
		$column = $this->pub_config->get_column(' id,name ');
		$ret = array('data_source' => $data_source, 'column' => $column);
		$this->addItem($ret);
		$this->output();
	}	
	
	public function get_datasource_info()
	{
		$data_source_id = intval($this->input['id']);
		$data_source = common::get_datasource_info($data_source_id);
		$this->addItem($data_source);
		$this->output();
	}
	
	public function get_template_content()
	{
		$template_id = $this->input['template_id'];
		$sql = "select * from " . DB_PREFIX . "templates where id = ".$template_id;	
		$re = $this->db->query_first($sql);
		
		$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
		$sign = $re['template_style'].'_'.$re['sign'];
		$str = $this->cache->get($sign);
		if(!$str)
		{
			$str =  common::set_cache($sign,$re['content'],$re['site_id'],$re['sort_id']);
		}
		$sql_ = "select * from " . DB_PREFIX . "cell where template_id = ".$template_id." AND original_id = 0 AND del = 0";	
		$q = $this->db->query($sql_);
		$ret = $data = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		$data[] =	$ret; 
		$data['template_content'] =	$str; 
		$data['data_source'] = common::get_data_source();
		$data['mode'] = common::get_mode();
		$this->addItem($data);
		$this->output();
	}
	
	public function get_template_sort()
	{
		$site_id = $this->input['site_id'];
		$con = '';
		if(!$site_id)
		{
			$site_id = $this->settings['site_default'];
			
		}
		$con = ' AND site_id = '.$site_id;
		$sql_ = "select id,name from " . DB_PREFIX . "template_sort  where 1".$con;	
		$q_ = $this->db->query($sql_);
		while($r = $this->db->fetch_array($q_))
		{
			$ret[$r['id']] = $r['name'];
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_template_folder()
	{
		$folder = $folder_arr = array();
		$sort_id = $this->input['sort_id'];
		if($sort_id)
		{
			$sql = "select folders from " . DB_PREFIX . "template_sort where id=".$sort_id;	
			$q = $this->db->query_first($sql);
			$folder = explode(',',$q['folders']);
			foreach($folder as $k=>$v)
			{
				$folder_arr[$v] = $v;
			}
		}
		$this->addItem($folder_arr);
		$this->output();
	}
	
	public function get_debug_mode()
	{
		if(defined('DEVELOP_MODE') && DEVELOP_MODE)
		{
			$re = DEVELOP_MODE;
		}
		else
		{
			$re = 0;
		}
		$this->addItem($re);
		$this->output();
	}
	
	public function get_record()
	{
		$template_id = $this->input['id'];
		$site_id = $this->input['site_id'];
		$sql = "select * from " . DB_PREFIX . "templates where id=".$template_id;	
		$q = $this->db->query_first($sql);
		$site_info = $this->pub->get_site_first('*',$site_id);
		if($q['template_style'] == $site_info['tem_style'])
		{
			$sqll = "select * from " . DB_PREFIX . "deploy_template where template_sign = '".$q['sign'] ."'";	
			$q_ = $this->db->query($sqll);
			while($r = $this->db->fetch_array($q_))
			{
				$ret[] = $r['title'];
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	//导出模板
	public function export_teminfo()
	{
		$signs_str = implode('","', explode(',', urldecode($this->input['sign'])));
       	$sql       = 'select 	*  from  ' . DB_PREFIX . 'templates   WHERE sign IN("' . $signs_str . '")';
		$mq = $this->db->query($sql);
		while($rm = $this->db->fetch_array($mq))
		{	
			$match[$rm['sign']] = $this->obj->get_preg_match($rm['content'],$rm['site_id'],$rm['sort_id']);
			//file_put_contents('0a',var_export($match,1));exit;
			$rm['content'] = htmlspecialchars($rm['content'],ENT_QUOTES);
			$tinfo[$rm['id']] = $rm;
			$tid_arr[] = $rm['id'];
		}
		$sqlc = "select id,sign  from " . DB_PREFIX . "cell_mode  where sign !=''";		
		$qc = $this->db->query($sqlc);
		while($rc = $this->db->fetch_array($qc))
		{
			if($rc['sign'])
			{
				$cell_sign[$rc['id']] = $rc['sign'];
			}
		}
		$sqld = "select id,sign  from " . DB_PREFIX . "data_source  where sign !=''";	
		$qd = $this->db->query($sqld);
		while($rd = $this->db->fetch_array($qd))
		{
			if($rd['sign'])
			{
				$dataso_sign[$rd['id']] = $rd['sign'];
			}
		}
		
		$cssql = "select id,sign  from " . DB_PREFIX . "cell_mode_code  where sign !='' AND type ='css' ";	
		$cssqd = $this->db->query($cssql);
		while($csrd = $this->db->fetch_array($cssqd))
		{
			if($csrd['sign'])
			{
				$css_sign[$csrd['id']] = $csrd['sign'];
			}
		}
		$tid_str = implode(',',$tid_arr);
		if($tid_str)
		{
			$sql_ = "select *  from " . DB_PREFIX . "cell  where template_id in ( ".$tid_str.")";	
			$qq = $this->db->query($sql_);
			while($r = $this->db->fetch_array($qq))
			{
				if($r['cell_mode'])
				{
					$r['cell_sign'] = $cell_sign[$r['cell_mode']];
				}
				if($r['data_source'])
				{
					$r['dataso_sign'] = $dataso_sign[$r['data_source']];
				}
				if($r['css_id'])
				{
					$r['css_sign'] = $css_sign[$r['css_id']];
				}
				$cell_info[$r['template_id']][$r['sign']] = $r;
			}
		}
		$host  = $this->settings['App_appstore']['host'];
        $dir   = $this->settings['App_appstore']['dir'];
        $curl  = new curl($host, $dir);
		$curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'publish_version');
        
		if($tinfo && is_array($tinfo))
		{
			foreach($tinfo as $k=>$v)
			{
				$liv_teminfo = array(
		            'tem_info'				=>		$v,
					'cell_info'				=>		$cell_info[$k],
        			);
        		//file_put_contents('../cache/0ce.txt',var_export($liv_teminfo,1));
        		if($match[$v['sign']])
        		{
        			//file_put_contents('../cache/0a',var_export($match[$v['sign']],1));
        			$material = serialize($match[$v['sign']]);
        		}
        		
        		$material_dir = 'data/template/'.$v['site_id'].'/'.$v['sort_id'].'/';
        		$teminfo_str = serialize($liv_teminfo);
        		$curl->addRequestData('sign', $v['sign']);
        		$curl->addRequestData('title', $v['title']);
        		$curl->addRequestData('data', $teminfo_str);
        		$curl->addRequestData('material', $material);
        		$curl->addRequestData('app_unique', 'publishsys');
        		$curl->addRequestData('material_dir', $material_dir);
        		$curl->addRequestData('html', '1');
		        $curl->addRequestData('type', '3');
		      	$data_source_info = $curl->request('pub_template.php');
			}
		}
		
	}
	
	
	//导入模板
	public function import_teminfo()	
	{
		$file = $this->input['file'];
		if($this->input['sort_id'])
		{
			$sort_id = $this->input['sort_id'];
			$sqll   = 'select 	*  from  ' . DB_PREFIX . 'template_sort  WHERE id =' . $sort_id ;
			$mql   = $this->db->query_first($sqll);
			$site_id = $mql['site_id']; 
		}
		
		if(is_array($file))
		{
			$sign_ar = array_keys($file);
    		$signs_str = implode('","',$sign_ar);
		}
    	$tsign = $tid_arr = array();
    	if($signs_str)
    	{
    		if($site_id)
			{
				$sql   = 'select 	*  from  ' . DB_PREFIX . 'templates  WHERE sign IN("' . $signs_str . '") AND site_id = '.$site_id;
			}
			else
			{
				$sql   = 'select 	*  from  ' . DB_PREFIX . 'templates  WHERE sign IN("' . $signs_str . '")';
			}
	        $mq    = $this->db->query($sql);
	        while ($rm   = $this->db->fetch_array($mq))
	        {
	            if ($rm['sign'])
	            {
	                $tsign[$rm['sign']] = $rm['id'];
	                $tsort[$rm['sign']] = $rm['sort_id'];
	                $tid_arr[] = $rm['id'];
	            }
	        }
    	}
    	
        $tid_str = implode(',',$tid_arr);
        if($tid_str)
        {
        	$sql_ = "select *  from " . DB_PREFIX . "cell  where template_id in ( ".$tid_str.")";	
			$qq = $this->db->query($sql_);
			while($r = $this->db->fetch_array($qq))
			{
				$cell_info[$r['template_id']][] = $r;
				$temsign[$r['template_id']][] = $r['sign'];
				$tcid[$r['template_id']][$r['sign']] = $r['id'];
			}
        }
        $sqlc = "select id,sign  from " . DB_PREFIX . "cell_mode  where sign !=''";		
		$qc = $this->db->query($sqlc);
		while($rc = $this->db->fetch_array($qc))
		{
			$cell_sign[$rc['sign']] = $rc['id'];
		}
		$sqld = "select id,sign  from " . DB_PREFIX . "data_source where sign !=''";		
		$qd = $this->db->query($sqld);
		while($rd = $this->db->fetch_array($qd))
		{
			$dataso_sign[$rd['sign']] = $rd['id'];
		}
		
		$cssql = "select id,sign  from " . DB_PREFIX . "cell_mode_code  where sign !='' AND type ='css' ";	
		$cssqd = $this->db->query($cssql);
		while($csrd = $this->db->fetch_array($cssqd))
		{
			if($csrd['sign'])
			{
				$css_sign[$csrd['sign']] = $csrd['id'];
			}
		}
		
		if($file && is_array($file))
		{
			foreach($file as $k=>$val)
			{
				$teminfos      		= unserialize($val['data']);
				//file_put_contents('02',var_export($teminfos,1));
				$tem_info 			= $teminfos['tem_info'];
				$cell_info			= $teminfos['cell_info'];
				//file_put_contents('0',var_export($cell_info,1));
				//file_put_contents('01',count($cell_info));
		        if($tsign[$k])
		        {
			
					$sqll   = 'select 	*  from  ' . DB_PREFIX . 'templates  WHERE id =' . $tsign[$k];
					$mql   = $this->db->query_first($sqll);
					$site_id = $mql['site_id']; 
					
		        	$tem_info['sort_id']       = $tsort[$k];
		        	$tem_info['id']       = $tsign[$k];
		        	$tem_info['content'] = addslashes(htmlspecialchars_decode($tem_info['content'],ENT_QUOTES));
	                $tem_info['update_time']  	= TIMENOW;
					$tem_info['user_id'] 		= $this->user['user_id'];
					$tem_info['user_name'] 		= $this->user['user_name'];
					unset($tem_info['site_id']);
					if($tem_info['pic'])
			        {
			        	$indexpic = json_decode($tem_info['pic'],1);
						$indexpic = $indexpic['0'];
			        	if(strstr($indexpic['host'],"img.dev.hogesoft.com")!==false)
					    {
					    	$url = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
				        	$pic = file_get_contents($url);
							if($pic)
							{
								$dir = CUR_CONF_PATH.'data/template/pic/';
								hg_mkdir($dir);
								file_put_contents($dir.$indexpic['filename'],$pic);
							}
							$index_pic[0]  = array(
									'host'			=>	$this->settings['template_image_url']."/",
									'dir'			=>	'pic/',
									'filepath'		=>	'',
									'filename'		=>	$indexpic['filename'],
							);
							$picurl =  $index_pic[0]['host'].$index_pic[0]['dir'].$index_pic[0]['filepath'].$index_pic[0]['filename'];
							$pic_info = $this->material->localMaterial($picurl);//插入图片服务器
							if($pic_info[0])
							{
								$arr[0] = array(
									'host'			=>$pic_info[0]['host'],
									'dir'			=>$pic_info[0]['dir'],
									'filepath'		=>$pic_info[0]['filepath'],
									'filename'		=>$pic_info[0]['filename'],
								);
								$tem_info['pic'] = json_encode($arr);
							}	
					    }
			        }
	                $this->obj->update($tem_info, 'templates');
	                
	                $this->addLogs('商店更新模板' , $mql , $tem_info, '商店更新模板'.$tem_info['title']);
	                //缓存更新
					include_once(CUR_CONF_PATH.'lib/cache.class.php');
					$this->cache = new cache();
					$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
					$sign = $site_id.'_'.$tem_info['template_style'].'_'.$tem_info['sign'];
					//$str = $this->cache->get($sign);

					$sq   = 'select *  from  ' . DB_PREFIX . 'templates  WHERE id =' . $tsign[$k];
					$m   = $this->db->query_first($sq);
					$str = common::set_cache($sign,$m['content'],$site_id,$tem_info['sort_id']);
					//
					
	                $temid = $tsign[$k];
	                $sort_id = $tem_info['sort_id'];
	                if(is_array($cell_info))
					{
						foreach($cell_info as $ka=>$va)
						{
							if($va['cell_sign'])
							{
								if($cell_sign[$va['cell_sign']])
								{
									$va['cell_mode'] = $cell_sign[$va['cell_sign']];
								}
								else
								{
									$rerurn = array();
									$rerurn = array(
										'sign'		=>	$va['cell_sign'],
										'type'		=>	2,
										'tem'		=>  $tem_info['sign'],
									);
									echo json_encode($rerurn) ;exit;
								}
							}
							
							if($va['dataso_sign'])
							{
								if($dataso_sign[$va['dataso_sign']])
								{
									$va['data_source'] = $dataso_sign[$va['dataso_sign']];
								}
								else
								{
									$rerurn = array();
									$rerurn = array(
										'sign'		=>	$va['dataso_sign'],
										'type'		=>	1,
										'tem'		=>  $tem_info['sign'],
									);
									echo json_encode($rerurn) ;exit;
								}
							}
							
							if($va['css_sign'])
							{
								$va['css_id'] = $css_sign[$va['css_sign']];
							}
							unset($va['cell_sign']);
							unset($va['dataso_sign']);
							unset($va['css_sign']);
							
							$va['param_asso'] = addslashes($va['param_asso']);
							$va['cell_code'] = addslashes($va['cell_code']);
							
							if(is_array($temsign[$temid]))
							{
								if(in_array($va['sign'],$temsign[$temid]))
								{
									$va['id'] = $tcid[$temid][$va['sign']];
									$va['template_id']  = $temid;
									$va['update_time']  = TIMENOW;
									$va['user_id'] 		= $this->user['user_id'];
									$va['user_name'] 	= $this->user['user_name'];
									$va['appid'] 		= $this->user['appid'];
									$va['appname'] 		= $this->user['display_name'];
									$va['sort_id']      = $sort_id;
									unset($va['site_id']);
									$this->obj->update($va, 'cell');
								}
								else
								{
									unset($va['id']);
									$va['template_id'] = $temid;
									$va['create_time']  = $va['update_time']  = TIMENOW;
									$va['user_id'] 		= $this->user['user_id'];
									$va['user_name'] 	= $this->user['user_name'];
									$va['appid'] 		= $this->user['appid'];
									$va['appname'] 		= $this->user['display_name'];
									$va['sort_id']      = $sort_id;
									$va['site_id']      = $site_id;
									$code_id = $this->obj->import_tem_info($va,'cell');
								}
							}
							else
							{
								unset($va['id']);
								$va['template_id'] = $temid;
								$va['create_time']  = $va['update_time']  = TIMENOW;
								$va['user_id'] 		= $this->user['user_id'];
								$va['user_name'] 	= $this->user['user_name'];
								$va['appid'] 		= $this->user['appid'];
								$va['appname'] 		= $this->user['display_name'];
								$va['sort_id']      = $sort_id;
								$va['site_id']      = $site_id;
								$code_id = $this->obj->import_tem_info($va,'cell');
							}
						}
					}
		        }
		        else
		        {
		        	if(is_array($tem_info))
					{
						unset($tem_info['id']);
                        $tem_info['sort_id'] = $sort_id;
						$tem_info['content'] = addslashes(htmlspecialchars_decode($tem_info['content'],ENT_QUOTES));
						$tem_info['create_time']  	= $tem_info['update_time']  = TIMENOW;
						$tem_info['user_id'] 		= $this->user['user_id'];
						$tem_info['user_name'] 		= $this->user['user_name'];
						$tem_info['site_id']        = $site_id;
						if($tem_info['pic'])
				        {
				        	$indexpic = json_decode($tem_info['pic'],1);
							$indexpic = $indexpic['0'];
				        	if(strstr($indexpic['host'],"img.dev.hogesoft.com")!==false)
					        {
					        	$url = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
					        	$pic = file_get_contents($url);
								if($pic)
								{
									$dir = CUR_CONF_PATH.'data/template/pic/';
									hg_mkdir($dir);
									file_put_contents($dir.$indexpic['filename'],$pic);
								}
								$index_pic[0]  = array(
										'host'			=>	$this->settings['template_image_url']."/",
										'dir'			=>	'pic/',
										'filepath'		=>	'',
										'filename'		=>	$indexpic['filename'],
								);
								$picurl =  $index_pic[0]['host'].$index_pic[0]['dir'].$index_pic[0]['filepath'].$index_pic[0]['filename'];
								$pic_info = $this->material->localMaterial($picurl);//插入图片服务器
								if($pic_info[0])
								{
									$arr[0] = array(
										'host'			=>$pic_info[0]['host'],
										'dir'			=>$pic_info[0]['dir'],
										'filepath'		=>$pic_info[0]['filepath'],
										'filename'		=>$pic_info[0]['filename'],
									);
									$tem_info['pic'] = json_encode($arr);
								}	
					        }
				        }
						$temid = $this->obj->import_tem_info($tem_info,'templates');
						
						$this->addLogs('商店安装模板' , '' , $tem_info, '商店安装模板'.$tem_info['title']);
						//生成更新
						include_once(CUR_CONF_PATH.'lib/cache.class.php');
						$this->cache = new cache();
						$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
						$sign = $site_id.'_'.$tem_info['template_style'].'_'.$tem_info['sign'];
						//$str = $this->cache->get($sign);
						
						$sq   = 'select *  from  ' . DB_PREFIX . 'templates  WHERE id =' . $temid;
						$m   = $this->db->query_first($sq);
						$str = common::set_cache($sign,$m['content'],$site_id,$tem_info['sort_id']);
						//
					}
					if(is_array($cell_info))
					{
						foreach($cell_info as $k=>$v)
						{
							if($v['cell_sign'])
							{
								if($cell_sign[$v['cell_sign']])
								{
									$v['cell_mode'] = $cell_sign[$v['cell_sign']];
								}
								else
								{
									$rerurn = array();
									$rerurn = array(
										'sign'		=>	$v['cell_sign'],
										'type'		=>	2,
										'tem'		=>  $tem_info['sign'],
									);
									echo json_encode($rerurn) ;exit;
								}
							}
							
							if($v['dataso_sign'])
							{
								if($dataso_sign[$v['dataso_sign']])
								{
									$v['data_source'] = $dataso_sign[$v['dataso_sign']];
								}
								else
								{
									$rerurn = array();
									$rerurn = array(
										'sign'		=>	$v['dataso_sign'],
										'type'		=>	1,
										'tem'		=>  $tem_info['sign'],
									);
									echo json_encode($rerurn) ;exit;
								}
							}
							
							if($v['css_sign'])
							{
								$v['css_id'] = $css_sign[$v['css_sign']];
							}
							unset($v['css_sign']);
							unset($v['cell_sign']);
							unset($v['dataso_sign']);
							unset($v['id']);
							$v['template_id'] = $temid;
							$v['param_asso'] = addslashes($v['param_asso']);
							$v['cell_code'] = addslashes($v['cell_code']);
							$v['create_time']  	= $v['update_time']  = TIMENOW;
							$v['user_id'] 		= $this->user['user_id'];
							$v['user_name'] 	= $this->user['user_name'];
							$v['appid'] 		= $this->user['appid'];
							$v['appname'] 		= $this->user['display_name'];
							$v['sort_id'] 		= $sort_id;
							$v['site_id']       = $site_id;
							$code_id = $this->obj->import_tem_info($v,'cell');
						}
					}
				}
				if($val['material'])
				{
					//file_put_contents('../cache/0a',$v['material']);
					//file_put_contents('../cache/0b','../data/template/'.$tem_info['site_id'].'/'.$tem_info['sort_id']);
					$ma_zip = file_get_contents('http://'.$val['material']);
					if($ma_zip)
					{
						file_put_contents('../data/template/'.$site_id.'/'.$sort_id.'/material.zip',$ma_zip);
					}
					$path = CUR_CONF_PATH.'data/template/'.$site_id.'/'.$sort_id;
					$dir = $path.'/';
					$unzip_cmd = ' unzip ' . $dir. 'material.zip  -d ' . realpath($dir);
					exec($unzip_cmd);
					
					$from = $path.$val['replace_dir'];
					file_copy($from, $path);
					@unlink($dir. 'material.zip');
					$this->obj->del_file(realpath($dir.'web'));
				}
			}
		}
	}
	public function get_template_file()
	{
		$site_id 		= $this->input['site_id'];
		$sign 			= $this->input['template_sign'];
		if(empty($site_id) || empty($sign))
		{
			$ret = array(
       			'error'  =>	'请输入站点或者模板标识',
       		);
			$this->addItem($ret);
			$this->output();exit;
		}
		
		$sql    = "select 	*  from  " . DB_PREFIX . "templates   WHERE site_id =" .$site_id ." AND sign = '" .$sign ."'";
		$rm = $this->db->query_first($sql);
		$match = $ret = array();
		$match = $this->obj->get_preg_match($rm['content'],$rm['site_id'],$rm['sort_id']);
		if($match)
		{
			foreach($match as $k=>$v)
			{
				if(strstr($v,".css")!==false)
				{
					$ret[$rm['id']]['css'][] = $v; 
				}
				elseif(strstr($v,".js")!==false)
				{
					$ret[$rm['id']]['js'][] = $v; 
				}
				else
				{
					$tu = array();
					$tu['dir'] = $v;
					$tu['url'] = DATA_URL.'template/'.$rm['site_id'].'/'.$rm['sort_id'].'/'.$v;
					$ret[$rm['id']]['pic'][] = $tu; 
				}
			}
		}
		else
		{
			$ret[$rm['id']] = 	$rm['id'];
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_template_file_info()
	{
		$template_id 	= $this->input['template_id'];
		$dir 			= $this->input['dir'];
		if(empty($template_id) || empty($dir))
		{
			$ret = array(
       			'error'  =>	'请输入模板id或者文件路径',
       		);
			$this->addItem($ret);
			$this->output();exit;
		}
		
		$sql    = 'select 	*  from  ' . DB_PREFIX . 'templates   WHERE id =' .$template_id;
		$rm = $this->db->query_first($sql);
		$dir =  '../data/template/'.$rm['site_id'].'/'.$rm['sort_id'].'/'.$dir;
		
		$re = file_exists($dir);
		
		if(!$re)
		{
			$ret = array(
       			'error'  =>	'文件不存在',
       		);
			$this->addItem($ret);
			$this->output();exit;
		}
       	$file_info = file_get_contents($dir);
       	$ret = array(
       			'template_id'  =>	$template_id,
       			'file_info'    =>	$file_info,
       	);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update_template_file_info()
	{
		$template_id	= $this->input['template_id'];
		$file_info 		= $this->input['file_info'];
		$dir 			= $this->input['dir'];
		
		if(empty($template_id) || empty($dir))
		{
			$ret = array(
       			'error'  =>	'请输入模板id或者文件路径',
       		);
			$this->addItem($ret);
			$this->output();exit;
		}
		
		$sql    = 'select 	*  from  ' . DB_PREFIX . 'templates   WHERE id =' .$template_id;
		$rm = $this->db->query_first($sql);
		$dir =  '../data/template/'.$rm['site_id'].'/'.$rm['sort_id'].'/'.$dir;
       	$file = file_put_contents($dir,$file_info);
       	
       	$ret = array(
       			'success'  =>	'success',
       	);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update_template_pic()
	{
		$template_id	= $this->input['template_id'];
		$dir 			= $this->input['dir'];
		
		if(empty($template_id) || empty($dir))
		{
			$ret = array(
       			'error'  =>	'请输入模板id或者文件路径',
       		);
			$this->addItem($ret);
			$this->output();exit;
		}
		
		$sql    = 'select 	*  from  ' . DB_PREFIX . 'templates   WHERE id =' .$template_id;
		$rm = $this->db->query_first($sql);
       	
       	if($file = $_FILES['Filedata'])
		{
			//创建目录存放解压文件
			$di = CUR_CONF_PATH.'data/template/'.$rm['site_id']."/".$rm['sort_id'].'/';
			if('-1' == $this->input['dir'])
			{
				$dir = $di;
			}
			else
			{
				$dir = $di.$this->input['fodder'].'/';
			}
			if (!hg_mkdir($dir) || !is_writeable($dir))
			{
				$this->errorOutput($dir . '目录不可写');
			}
			
			if(!move_uploaded_file($file['tmp_name'], $dir . $file['name']))
			{
				$this->errorOutput('文件移动失败');
			}
		}
       	$ret = array(
       			'success'  =>	'success',
       	);
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_template()
	{
		$return = array();
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'templates WHERE id = '.$this->input['template_id'];
		$ret = $this->db->query_first($sql);
		
		$return['content'] = str_replace("#$23", '&nbsp;', $ret['content']);
		
		$this->addItem($return);
		$this->output();
	}
	
	
	public function get_template_tag()
    {
    	$offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit = ' LIMIT ' . $offset . ', ' . $count;
        
        $sql  = "select * from " . DB_PREFIX . "template_tag where 1" .$data_limit;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $data[$row['id']] = $row['name'];
        }
        $this->addItem($data);
        $this->output();
    }


    public function get_content_type()
    {
        include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
        $this->pub_content= new publishcontent();
        $set_type_content = array();
        //有内容，查出内容类型
        $content_type = $this->pub_content->get_all_content_type();
        if(is_array($content_type))
        {
            foreach($content_type as $k=>$v)
            {
                $set_type_content[$v['id']] = $v['content_type'];
            }
        }
        $content_type = $this->settings['site_col_template'] + $set_type_content;
        $this->addItem($content_type);
        $this->output();
    }
	
	public function index()
	{	
	}
}

$out = new templateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
