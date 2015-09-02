<?php
require('global.php');
define('MOD_UNIQUEID','template');//模块标识
class templateUpdateApi extends adminUpdateBase
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
		include(CUR_CONF_PATH . 'lib/template.class.php');
		$this->obj = new template();
		include(CUR_CONF_PATH . 'lib/template_analyse.php');
		include(CUR_CONF_PATH . 'lib/common.php');
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}


    /**
     * 新增模板页调用方法
     */
    public function create()
	{
		if(!$_FILES['Fileda'] && !$this->input['direct_create'])
		{
			$this->errorOutput("请上传模板文件");
		}
        else if ($this->input['direct_create'] && !$this->input['content'])
        {
            $this->errorOutput("模板内容不能为空");
        }
        else if ($this->input['direct_create'] && !$this->input['title'])
        {
            $this->errorOutput("模板名称不能为空");
        }
		if(!$sort_id =  intval($this->input['sort_id']))
		{
			$this->errorOutput("请选择模板分类");
		}
		$site_id = intval($this->input['site_id']);


        if ($this->input['global_template'])   //全局模板
        {
            $site_id = 0;
            $this->input['template_style'] = $this->settings['tem_style_default'];
            $this->input['app_uniqueid'] = APP_UNIQUEID;
        }

        if ($this->input['direct_create'])
        {
            $file_name = trim(urldecode($this->input['title']));
        }
        else
        {
            $file_name= $_FILES['Fileda']['name'];
        }
		$file_type = strtolower(strrchr($file_name,"."));
		$ftypes = $this->settings['file_types'];
		if(!$this->input['direct_create'] && $file_type == '.zip')
		{
			$error = $this->obj->unzip_info($_FILES['Fileda'],$site_id,$sort_id,$this->input['template_style'],$this->input['client']?$this->input['client']:2);
			
			if($error['error'])
			{
				$this->errorOutput($error['error']);
			}
			$this->addItem('ture');
			$this->output();
		}
		else
		{
			if(!in_array($file_type,$ftypes))
			{
				$this->errorOutput("模板文件类型错误，压缩包上传类型只能为zip");
			}
		}
		
		//获取模板名称
		$title = trim(urldecode($this->input['title']));
		if(!$title && !$this->input['direct_create'])
		{
			$title = $_FILES['Fileda']['name'];
		}
        else if (!$title && $this->input['direct_create'])
        {
            $this->errorOutput("模板名称不能为空");
        }
			
		//$signs = explode(".",$_FILES['Fileda']['name']);
        $signs = explode(".",$file_name);
		$client = $this->input['client']?$this->input['client']:2;

        if ($this->input['direct_create'])
        {
            $re_con = $this->input['content'];
        }
        else
        {
            $re_con = file_get_contents($_FILES['Fileda']['tmp_name']);
        }

        //验证文件内是不有PHP标签
        if (strpos($re_con, '<?') !== false) {
            $this->errorOutput("模板文件内含有非法字符");
        }
		$content = addslashes($re_con);
		$data = array(
			'title'				=> $title,
            'client'			=> $client,
            'sort_id'			=> $sort_id,
            'file_name'			=> $file_name,
            'sign'				=> $signs[0],
            'tag'				=> $this->input['tag'] ? $this->input['tag'] : 0,
		 	'site_id'			=> $site_id,
			'app_uniqueid'      => $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : APP_UNIQUEID,
			'content'			=> $content,
			'pic'           	=> $this->input['log'],
			'template_style'    => $this->input['template_style'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'user_id'	 		=> $this->user['user_id'],
			'user_name'	  		=> $this->user['user_name'],			 	
			'ip'          		=> $this->user['ip'],
			'org_id'			=> $this->user['org_id'],
		);
        if ($this->input['global_template'])   //全局模板
        {
            $data['content_type'] = $this->input['content_type'];
        }
		$sql = "select id from " . DB_PREFIX . "templates where title = '".$title."'"."  AND template_style = '".$data['template_style']. "'" ;
		$q = $this->db->query_first($sql);
		if($q)
		{
			$this->errorOutput("该套系下模板名已存在");
		}
		
		$tem_style_default = $this->settings['tem_style_default'];
		if($this->input['template_style'] !=$tem_style_default)
		{
			$sqll = "select id from " . DB_PREFIX . "templates where file_name = '".$file_name."'"."  AND template_style = '".$tem_style_default. "'" ;
			$ql = $this->db->query_first($sqll);
			if(!$ql)
			{
				$this->errorOutput("请先将该模板上传到默认套系");
			}
		}
		
		$sql_ = "select id from " . DB_PREFIX . "templates where 1 "."  AND sign = '".$signs[0]. "'"."  AND template_style = '".$data['template_style']. "'" ;
		$q_ = $this->db->query_first($sql_);
		if($q_['id'])
		{
			$this->errorOutput("该套系模板标识已存在");
		}
		
		include_once(CUR_CONF_PATH.'lib/cache.class.php');
		$this->cache = new cache();
		$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
		$sign = $data['site_id'].'_'.$tem_style_default.'_'.$signs[0];
		//$str = $this->cache->get($sign);
		$str = common::set_cache($sign,$re_con,$data['site_id'],$data['sort_id']);
		$history_material_id = $now_material_id = array();
		
		if(is_array($data['pic']) && count($data['pic']))
		{
			foreach($data['pic'] as $k => $v)
			{
				$v= json_decode(html_entity_decode($v),1);
				$material[$k] = $v[0];
				$now_material_id[] = $v[0]['id'];
			}
			$data['pic'] = addslashes(json_encode($material));
		}
		if(is_array($this->input['history']) && count($this->input['history']))
		{
			foreach($this->input['history'] as $k => $v)
			{
				$v = json_decode(html_entity_decode($v),1);
				$history_material_id[] = $v[0]['id'];
			}
			$del_material_id = array_diff($history_material_id,$now_material_id);
			if($del_material_id)
			{
				include_once(ROOT_PATH . 'lib/class/material.class.php');
				$this->mater = new material();	
				$del_material_id = implode(',',$del_material_id);
				$this->mater->delMaterialById($del_material_id,2);	
			}		
		}	
		$ret = $this->obj->create($data);
		$data['id'] = $ret;
		$this->addLogs('新增模板' , '' , $data , $data['title']);
		$cell_info = array(
			'template_id'		=> 	$ret,
            'template_sign'		=> 	$signs[0],
            'sort_id'			=> 	$sort_id,
		 	'site_id'			=> 	$data['site_id'],
		 	'template_style'	=> 	$data['template_style'],
		);
		
		$file_units = $this->obj->parse_templatecell($re_con);
		$re = $this->obj->insert_new_cell($file_units,$cell_info);
		$this->addItem($site_id);
		$this->output();
	}
	
	public function update()
	{	
		if(!$sort_id =  intval($this->input['sort_id']))
		{
			$this->errorOutput("请选择模板分类");
		}
		$data = array(
			'id'       			=> 	intval($this->input['id']),
			'title'				=> 	trim(urldecode($this->input['title'])),
			'pic'           	=> 	$this->input['log'],
            'client'			=> 	$this->input['client']?$this->input['client']:2,
			'sort_id'			=> 	$sort_id,
			'tag'				=>  $this->input['tag'] ? $this->input['tag'] : 0,
			'template_style'	=> 	$this->input['template_style'],
			'user_id'	 		=>  $this->user['user_id'],
			'user_name'	  		=>  $this->user['user_name'],			 	
			'ip'          		=>  $this->user['ip'],
			//'app_uniqueid'      => $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : APP_UNIQUEID,
			'update_time'		=>  TIMENOW,
		);
        //只有表单提交了app_uniqueid字段才更改此属性  防止非开发模式专题模板时 所属模块还原为模板
        isset($this->input['app_uniqueid']) && $data['app_uniqueid'] = $this->input['app_uniqueid'];

        if ($this->input['global_template'])   //全局模板
        {
            $data['site_id'] = 0;
            $data['template_style'] = $this->settings['tem_style_default'];
            $data['app_uniqueid'] = APP_UNIQUEID;
            $data['content_type'] = $this->input['content_type'];
            $data['tag'] = 0;
        }
		$sql = "SELECT * FROM " . DB_PREFIX . "templates WHERE id =" .$this->input['id'];
		$ret = $this->db->query_first($sql);
		if(is_array($data['pic']) && count($data['pic']) > 0)
		{
			foreach($data['pic'] as $k => $v)
			{
				$v= json_decode(html_entity_decode($v),1);
				$data['pic'][$k] = $v[0];
				$now_material_id[] = $v[0]['id'];
			}
			$data['pic'] = addslashes(json_encode($data['pic']));
		}
		
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();				
		if($ret['pic'])      //处理已经入库后删除的图片
		{
			$old_material = json_decode($ret['pic'],1);
			if(is_array($old_material) && count($old_material) > 0 )
			{
				foreach($old_material as $k => $v)
				{
					$old_material_id[] = $v['id'];
				}
			}
			$old_del_id = array_diff($old_material_id,$now_material_id);
			if($old_del_id)  
			{
				$old_del_id = implode(',',$old_del_id);
				$this->mater->delMaterialById($old_del_id,2);	
			}
		}
		if(is_array($this->input['history']) && count($this->input['history']))   //处理还没有入库 删除的图片
		{
			foreach($this->input['history'] as $k => $v)
			{
				$v = json_decode(html_entity_decode($v),1);
				$history_material_id[] = $v[0]['id'];
			}
			$del_material_id = array_diff($history_material_id,$now_material_id);
			if($del_material_id)
			{
				$del_material_id = implode(',',$del_material_id);
				$this->mater->delMaterialById($del_material_id,2);	
			}		
		}
		if($file = $_FILES['Fileda'])
		{
            //验证上传文件格式
            $file_type = strtolower(strrchr($file['name'], "."));
            $allow_type = array('.html', '.htm', '.js', '.css', '.swf', '.jpg', '.jpeg', '.gif', '.bmp', '.png');
            if ($this->settings['tem_allow_file_type']) {
                $allow_type = $this->settings['allow_file_type'];
            }
            if ( !in_array($file_type, (array)$allow_type) ) {
                $this->errorOutput('非法文件格式');
            }
			//创建目录存放解压文件
			$di = CUR_CONF_PATH.'data/template/'.$this->input['site_id']."/".$sort_id.'/';
			if('-1' == $this->input['fodder'])
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
		
		$s =  "SELECT * FROM " . DB_PREFIX . "templates WHERE id = " . $this->input['id'];
		$pre_data = $this->db->query_first($s);
		
		$content = html_entity_decode($this->input['new_content']);
		$data_te = array(
			'id'       			=> 	intval($this->input['id']),
			'title'				=> 	trim(urldecode($this->input['title'])),
			'pic'           	=> 	$this->input['log'][0],
            'client'			=> 	$this->input['client']?$this->input['client']:2,
			'sort_id'			=> 	$sort_id,
			'template_style'	=> 	$this->input['template_style'],
			'content'			=> 	addslashes($content),
		);
		$re = $this->obj->update($data_te,'templates');	
		
		if($this->input['upcell'])
		{
			$up_cell = unserialize($this->input['upcell']);
			if($up_cell['celladding'])
			{
				$this->obj->insert_new_cell($up_cell['add_cell'],$up_cell['cell_info']);//新增单元
			}
			
			if($up_cell['celldeling'])
			{
				$this->obj->delete_cell($up_cell['celldeling'],$this->input['id']); //删除单元
			}
		}
		$data['content']	=	addslashes($content);
		//$data['site_id']	=	$pre_data['site_id'];
		$data['sign']	=	$pre_data['sign'];

		$data['pic'] = $data_te['pic'];
		$ret = $this->obj->update($data,'templates');	
	
		$sq =  "SELECT * FROM " . DB_PREFIX . "templates WHERE id = " . $this->input['id'];
		$up_data = $this->db->query_first($sq);

        include_once(CUR_CONF_PATH.'lib/cache.class.php');
        $this->cache = new cache();
        $this->cache->initialize(CUR_CONF_PATH.'cache/templsate/');
        $sign = $up_data['site_id'].'_'.$up_data['template_style'].'_'.$up_data['sign'];
        //$str = $this->cache->get($sign);
        $str = common::set_cache($sign,$content,$up_data['site_id'],$up_data['sort_id']);
		
		$this->addLogs('更新模板' , $pre_data , $up_data , $pre_data['title']);
		$this->addItem($ret);

		$this->output();
	}
	
	public function update_tem()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(!$sort_id =  intval($this->input['sort_id']))
		{
			$this->errorOutput("请选择模板分类");
		}
		
		$info = array(
			'id'       			=> intval($this->input['id']),
            'client'			=> $this->input['client']?$this->input['client']:2,
            'sort_id'			=> $sort_id,
            'template_style'	=> $this->input['template_style'],
			'pic'           	=> $this->input['log'],
			'update_time'		=> TIMENOW,
		);
		//获取模板名称
		$title = trim(urldecode($this->input['title']));
		if(!$title)
		{
			if($_FILES['Fileda'])
			{
				$info['title'] = $_FILES['Fileda']['name'];
			}
		}
		else
		{
			$info['title'] = $title;
		}
		if($_FILES['Fileda'])
		{
			$info['file_name'] =  $_FILES['Fileda']['name'];
			$signs = explode(".",$_FILES['Fileda']['name']);
			$content = file_get_contents($_FILES['Fileda']['tmp_name']);
			$re = $this->cell_analyse($info['id'],$content);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "templates WHERE id =  ".$this->input['id'];
		$ret = $this->db->query_first($sql);
		if(is_array($info['pic']) && count($info['pic']) > 0)
		{
			foreach($info['pic'] as $k => $v)
			{
				$v= json_decode(html_entity_decode($v),1);
				$info['pic'][$k] = $v[0];
				$now_material_id[] = $v[0]['id'];
			}
			$info['pic'] = addslashes(json_encode($info['pic']));
		}
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();				
		if($ret['pic'])      //处理已经入库后删除的图片
		{
			$old_material = json_decode($ret['pic'],1);
			$old_material_id = array();
			$now_material_id = array();
			if(is_array($old_material) && count($old_material) > 0 )
			{
				foreach($old_material as $k => $v)
				{
					$old_material_id[] = $v['id'];
				}
			}
			$old_del_id = array_diff($old_material_id,$now_material_id);
			if($old_del_id)  
			{
				$old_del_id = implode(',',$old_del_id);
				$this->mater->delMaterialById($old_del_id,2);	
			}
		}
		if(is_array($this->input['history']) && count($this->input['history']))   //处理还没有入库 删除的图片
		{
			foreach($this->input['history'] as $k => $v)
			{
				$v = json_decode(html_entity_decode($v),1);
				$history_material_id[] = $v[0]['id'];
			}
			$del_material_id = array_diff($history_material_id,$now_material_id);
			if($del_material_id)
			{
				$del_material_id = implode(',',$del_material_id);
				$this->mater->delMaterialById($del_material_id,2);	
			}		
		}	
		$re['updata'] = $info;
		$this->addItem($re);
		$this->output();
	}
	
	
	public function edit_update()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		if($this->input['upcell'])
		{
			$up_cell = unserialize($this->input['upcell']);
			if($up_cell['celladding'])
			{
				$this->obj->insert_new_cell($up_cell['add_cell'],$up_cell['cell_info']);//新增单元
			}
			
			if($up_cell['celldeling'])
			{
				$this->obj->delete_cell($up_cell['celldeling'],$this->input['id']); //删除单元
			}
		}
		if($this->input['updata'])
		{
			$data = unserialize($this->input['updata']);
			$data['content']	=	addslashes(html_entity_decode(urldecode($this->input['content'])));
		}
		else
		{
			$data = array(
				'id'       		=> intval($this->input['id']),
				'content'		=> addslashes(html_entity_decode(urldecode($this->input['content']))),
			);	
		}
		$ret = $this->obj->update($data,'templates');
		if(!$template_id = intval($this->input['id']))
		{
			$template_id = $data['id'];
		}
		$sql = 'SELECT * 
				FROM '.DB_PREFIX.'templates WHERE id = '.$template_id;
		$re = $this->db->query_first($sql);
		
		include_once(CUR_CONF_PATH.'lib/cache.class.php');
		$this->cache = new cache();
		$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
		$sign = $re['site_id'].'_'.$re['template_style'].'_'.$re['sign'];
		//$str = $this->cache->get($sign);
		$str = common::set_cache($sign,$re['content'],$re['site_id'],$re['sort_id']);
		
		$this->addItem($ret);
		$this->output();
	}


    /**
     * 编辑页点击保存模板调用方法
     * 调用模板对比器
     *
     * @parma $_FILES['Fileda']
     * @param content
     */
    function edit_c()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		
		if($_FILES['Fileda'])
		{
			$file_name= $_FILES['Fileda']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			if($file_type== '.html'||$file_type== '.htm')
			{
				$content = file_get_contents($_FILES['Fileda']['tmp_name']);
			}
			else
			{
				$content = html_entity_decode($this->input['content'], ENT_QUOTES, 'UTF-8');
			}
		}
		else
		{
			$content = html_entity_decode($this->input['content'], ENT_QUOTES, 'UTF-8');
		}

        //验证文件内是不有PHP标签
        if (strpos($content, '<?') !== false) {
            $this->errorOutput("模板文件内含有非法字符");
        }

		if($content)
		{
			$re = $this->cell_analyse($id,$content);
		}
		
		//$re = array('as','dad');
		$this->addItem($re);
		$this->output();
	}
	
	function delete()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的模板");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "templates WHERE id IN (" . $ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$ret = $this->obj->delete($ids);
		if($ret)
		{
			$this->addLogs('删除模板' , $pre_data , '', '删除模板'.$ids);
		}
		
		$this->addItem($ret);
		$this->output();
		
	}
	function analyse_result($original , $target)
	{
		$return = analyse($original , $target);
		$table1 = draw_table($return[0] , 1 , 'org_');
		$table2 = draw_table($return[1] , 0 , 'tar_');
	
		$table1_fix = ($table2[2] > $table1[2] ? $table2[2]- $table1[2]:0);
		$table2_fix = ($table1[2] > $table2[2] ? $table1[2]- $table2[2]:0);
		$str =  '<style>
		.compare{
			width:480px;
			float:left;
			table-layout:fixed;
			overflow:auto;
			height:430px;
			float:left;
		}
	
		.code{
			width:40%;
			word-break:keep-all;
			white-space:nowrap;
			overflow:hidden;
			text-overflow:ellipsis;
		}
	
		.fix
		{
			background-color:#EADAEB;
		}
	
		.notsame
		{
			background-color:#DD5C67;
		}
	
		.span_diff
		{
			background-color:#DD5C67;
		}
	
		.span_same
		{
			background:transparent;
		}
	
		.blank
		{
			background-color:#FFFFFF;
		}
	
		.hover
		{
			background-color:#ffffce;
		}
		</style>
		<script>
			$(document).ready(
			function(){
				jQuery("tr").each(
					function(){
						$(this).mouseover(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).addClass("hover");
								$("#tar_" + id).addClass("hover");
							}
						);
						$(this).mouseout(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).removeClass("hover");
								$("#tar_" + id).removeClass("hover");
							}
						);
					}
				);
				jQuery(".compare").each(
					function(){
						$(this).scroll(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).scrollTop($(this).scrollTop());
								$("#tar_" + id).scrollTop($(this).scrollTop());
								$("#org_" + id).scrollLeft($(this).scrollLeft());
								$("#tar_" + id).scrollLeft($(this).scrollLeft());
							}
						);
					}
				);
			}
			);
		</script>';
		$str_1 =  '<div style="float:left;"><div class="comp-head"><div class="comp-thead">原模板内容</div><div class="comp-thead">新模板内容</div></div>';
			$str1 =$str. $str_1. '<div class="compare" id="org_container" ><table border="0" cellspacing="0" cellpadding="0" class="">';
			$str1 .=  $table1[0];
			$str3 = '';
			for($i = 1 ; $i<= $table1_fix ; $i++)
			{
				$str1 . '<tr class="fix" id="org_'.$table1[2].'"><td colspan="2">'.($i + $table1[1]) .'</td></tr>';
				$table1[2]++;
			}
			$str1 .=  '</table></div>';
			
			$str2 =$str. '<div class="compare" id="tar_container"><table border="0" cellspacing="0" cellpadding="0" class="">';
			$str2 .=  $table2[0];
			$str3 .= $table2[0];
			for($i = 1 ; $i<= $table2_fix ; $i++)
			{
				$str2 .= '<tr class="fix" id="tar_'.$table2[2].'"><td colspan="2">&nbsp;</td></tr>';
				$table2[2]++;
			}
			$str2 .= '</table></div></div>';
			$table = array($str1,$str2,$target);
			return $table;
	}
	
	public function show_opration()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if($id = intval($this->input['id']))
		{
			$ret = $this->get_account_by_id($id);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询失败');
		}
	}
	
	public function get_account_by_id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plat WHERE id=$id ";
		$ret = $this->db->query_first($sql);
		if(!empty($ret))
		{
			$ret['status'] = $this->settings['status'][$ret['status']];
			$ret['type'] = empty($this->settings['share_plat'][$ret['type']]['name_ch'])?'':$this->settings['share_plat'][$ret['type']]['name_ch'];
			return $ret;
		}
		else
		{
			return false;
		}
	}
	
	public function upload()
	{
		if($_FILES['Filedata'])
		{			
			include_once(ROOT_PATH . 'lib/class/material.class.php');
			$this->mater = new material();
			$material = $this->mater->addMaterial($_FILES); //插入各类服务器
			if(!empty($material))
			{
				$material['success'] = true;
			    $return = $material;
			}
			else
			{
				$return = array(
					'success' => false,
					'error' => '文件上传失败',
				);
			}			
		}
		else 
		{
			$return = array(
				'success' => false,
				'error' => '文件上传失败',
			);
		}
		$this->addItem($return);	
		$this->output();	
	}
	
	public function cell_analyse($id,$content)
	{	
		$temid = intval($this->input['id']);
		if(!$temid)
		{
			$temid = $id;
		}
		//获取上传文件内容并调用模板比较器
		$sql = "SELECT * FROM ". DB_PREFIX ."templates WHERE id = ".$temid;
		$formerly_template = $this->db->query_first($sql);	
		
		$table = $this->analyse_result($formerly_template['content'],$content);
		$table['content'] = $content;
		//$table['content'] = $this->replace($content);
		$file_units =  $this->obj->parse_templatecell($content);
		$units = $file_units[1];		
		$unitnum = count($units);
		
		$tp_exist_cell =  $this->obj->get_exist_cell($id);
		
		$celladding = array_diff($units,$tp_exist_cell); //将要增加的单元
		$celldeling = array_diff($tp_exist_cell,$units); //将要删除的单元
		foreach($celladding as $k=>$v)
		{
			$add_info[] = $file_units[0][$k] ;
		}
		$add_cell[] = $add_info;
		foreach($celladding as $k=>$v)
		{
			$add[] = $v;
		}
		
		$add_cell[] = $add;
		
		$table['celladding'] = implode(',',$celladding);
		$table['celldeling'] = implode(',',$celldeling);
		
		
		$cell_info = array(
			'template_id'		=> 	$id,
            'template_sign'		=> 	$formerly_template['sign'],
            'sort_id'			=> 	$formerly_template['sort_id'],
		 	'site_id'			=> 	$formerly_template['site_id'],
		 	'template_style'	=> 	$formerly_template['template_style'],
		);
		$re = array();
		$re = array(
			'upcell'		=>	array('celladding'			=>		$celladding,
									  'celldeling'			=>		$celldeling,
									  'add_cell'			=>		$add_cell,
									  'cell_info'			=>		$cell_info,),
			'table'			=>	$table,
		);
		return $re;
	}
	
	function create_block()
	{
		if(!$this->input['name'])
		{
			$this->errorOutput('noname');
		}
		$data = array(
				'site_id' => intval($this->input['site_id']),
				'column_id' => intval($this->input['column_id']),
				'name' => urldecode($this->input['name']),
				'update_time' => intval($this->input['update_time']),
				'update_type' => intval($this->input['update_type']),
				'datasource_id' => intval($this->input['datasource_id']),
				'width' => intval($this->input['width']),
				'height' => intval($this->input['height']),
				'line_num' => intval($this->input['line_num']),
				'father_tag' => urldecode($this->input['father_tag']),
				'loop_body' => urldecode($this->input['loop_body']),
				'next_update_time' => TIMENOW+intval($this->input['update_time']),
				'is_support_push' => intval($this->input['is_support_push']),
		);	
		$ret = common::insert_block($data);
		$this->addItem($ret);
		$this->output();	
	}
	
	public function replace($para)
	{
		$pregfind= array('&nbsp;');
		$pregreplace= array('#$23');
		$out_para = str_replace($pregfind, $pregreplace, $para);
		return $out_para;
	}
	
	public function check_template()
	{
		$id = intval($this->input['template_id']);
		$content = html_entity_decode($this->input['content']);
		$re = $this->cell_analyse($id,$content);
		$re['content'] = $content;
		$this->addItem($re);
		$this->output();
	}
	
	
	public function update_template()
	{
		$id = intval($this->input['template_id']);
		$content = html_entity_decode($this->input['content']);
		$re = $this->cell_analyse($id,$content);
		
		if($re && is_array($re))
		{
			$up_cell = $re['upcell'];
			if($up_cell['celladding'])
			{
				$this->obj->insert_new_cell($up_cell['add_cell'],$up_cell['cell_info']);//新增单元
			}
			
			if($up_cell['celldeling'])
			{
				$this->obj->delete_cell($up_cell['celldeling'],$id); //删除单元
			}
		}
		$data = array(
			'id'       		=> $id,
			'content'		=> addslashes($content),
		);	
		$ret = $this->obj->update($data,'templates');
		
		$sql = 'SELECT * 
				FROM '.DB_PREFIX.'templates WHERE id = '.$id;
		$re = $this->db->query_first($sql);
		
		include_once(CUR_CONF_PATH.'lib/cache.class.php');
		$this->cache = new cache();
		$this->cache->initialize(CUR_CONF_PATH.'cache/template/');
		$sign = $re['site_id'].'_'.$re['template_style'].'_'.$re['sign'];
		//$str = $this->cache->get($sign);
		$str = common::set_cache($sign,$re['content'],$re['site_id'],$re['sort_id']);
		
		$return = array(
       			'success'  =>	'success',
       	);
		$this->addItem($return);
		$this->output();
	}
	
	public function audit()
	{
	}
	public function sort()
	{
	}
	public function publish()
	{
	}
	
	/**
	 * 解析单元信息
	 *
	 * @param string $content
	 * @return array
	 */
	//function parse_templatecell($content = "")
	//{	
		
		/*//$eregtag = '/<div[\s]+(?:id|class)="livcms_cell".+?>liv_([\\s\\S]+?(?=<\/div>))<\/div>/is';*/
		/*$eregtag = '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_([\\s\\S]+?(?=<\/span>))<\/span>/is';*/
		//$eregtag = '/<span[\s]+id="livcms_cell".+?[\s]+name="(.+?)">([\\s\\S]+?(?=<\/span>))<\/span>/is';
	/*	preg_match_all( $eregtag, $content, $match );
		return $match;
	}*/
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new templateUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>