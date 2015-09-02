<?php

require('global.php');
define('MOD_UNIQUEID', 'data_source'); //模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
 
class dataSourceApi extends nodeFrm
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/data_source.class.php');
        $this->obj  = new dataSource();
        require_once(ROOT_PATH . 'lib/class/auth.class.php');
        $this->auth = new Auth();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function show()
    {
//    	if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('data_source',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		
        $condition = $this->get_condition();
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
        $limit     = " limit {$offset}, {$count}";
        $ret       = $this->obj->show($condition, $limit);
        $this->addItem($ret);
        $this->output();
    }

    public function detail()
    {
        $id            = intval($this->input['id']);
        $sql           = 'SELECT *
				FROM ' . DB_PREFIX . 'data_source WHERE id = ' . $id;
        $r             = $this->db->query_first($sql);
        $r['argument'] = $r['argument'] ? unserialize($r['argument']) : array();
        if (is_array($r['argument']['other_value']))
        {
            foreach ($r['argument']['other_value'] as $k => $v)
            {
                $r['argument']['other_value'][$k] = str_replace("#&33", '\n', $v);
            }
        }
        $r['out_param'] = str_replace("#&33", '\n', $r['out_param']);
        // $out_arment
        $sql_           = "SELECT * FROM " . DB_PREFIX . "out_variable  WHERE mod_id =1 AND depath =3  AND expand_id =  " . $id;
        $q              = $this->db->query($sql_);
        while ($re             = $this->db->fetch_array($q))
        {
            $out_arment['name'][$re['id']]  = $re['name'];
            $out_arment['title'][$re['id']] = $re['title'];
            $out_arment['value'][$re['id']] = $re['value'];
        }
        $sqll                    = "SELECT id FROM " . DB_PREFIX . "out_variable  WHERE mod_id =1 AND depath =2  AND expand_id =  " . $id;
        $fi                      = $this->db->query_first($sqll);
        $r['fid']                = $fi['id'];
        $r['out_arment']         = $out_arment;
        $ret[]                   = $r;
        $ret['data_source_node'] = $this->obj->get_data_source_node(' AND mod_id=1 AND expand_id=' . $id . ' AND fid=0 ORDER BY order_id ', '');
        $ret['data_source_id']   = $id;
        $this->addItem($ret);
        $this->output();
    }

    /* function edit()
      {
      if (!($this->input['id']))
      {
      return false;
      }
      $sql = 'SELECT content,type
      FROM '.DB_PREFIX.'templates WHERE id = '.$this->input['id'];
      $r = $this->db->query_first($sql);
      if(empty($r['content']))
      {
      $this->errorOutput('数据源数据不存在！');
      }
      $html = $this->editer('content' , $r['content'] , array('cols' => 60 , 'rows' =>20));

      $r['html'] = $html;
      $r['template_types'] = $this->settings['template_types'];
      unset($r['content']);
      $this->addItem($r);
      $this->output();
      } */

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
        $sql             = 'SELECT count(*) as total from ' . DB_PREFIX . 'data_source  WHERE 1 ' . $this->get_condition();
        $templates_total = $this->db->query_first($sql);
        echo json_encode($templates_total);
    }

    /**
     * 检索条件应用，模块,操作，来源，用户编号，用户名
     * @name get_condition
     * @access private
     * @author gaoyuan
     * @category hogesoft
     * @copyright hogesoft
     */
    public function get_condition()
    {
        $condition = '';
        //应用下数据源
        if (intval($this->input['_type']))
        {
            $sql = "SELECT id FROM " . DB_PREFIX . "app WHERE father = " . intval($this->input['_type']);
            $q   = $this->db->query($sql);
            while ($r   = $this->db->fetch_array($q))
            {
                $return[] = $r['id'];
            }
            $app_ids = implode(',', $return);
            $condition .= " AND app_id in (" . $app_ids . ")";
        }
        //模块下数据源
        if (intval($this->input['_id']))
        {
            $condition .= " AND app_id = " . intval($this->input['_id']);
        }
        return $condition;
    }

    //生成API文件
    function build_api_file()
    {
    	/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('data_source',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
    	
        $ids = urldecode($this->input['id']);
        $tpl = '../api/apitpl.php';
        if (!is_readable($tpl))
        {
            $this->errorOutput(NOT_ALLOW_READ);
        }
        $tpl_str = '';
        $tpl_str = @file_get_contents($tpl);
        if (!$tpl_str)
        {
            $this->errorOutput(NOT_ALLOW_READ);
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "data_source WHERE id in (" . $ids . ")";
        
        $g   = $this->db->query($sql);
        
        while ($j   = $this->db->fetch_array($g))
        {
            if ($j['app_id'])
            {
                $app       = 'App_' . $j['app_id'];
                $j['host'] = $this->settings[$app]['host'];
                $j['dir']  = $this->settings[$app]['dir'];
            }
            $return[] = $j;
        }
        
        $sum = count($return);
        /* if(!($args = unserialize($setting['argument'])))
          {
          $this->errorOutput(NO_ARGUMENTS);
          }
          if(!($maps = unserialize($setting['map'])))
          {
          $this->errorOutput(NO_MAPS);
          } */
        //批量生成文件
        if (is_array($return) && $sum > 1 && $sum < 20)
        {
            foreach ($return as $k => $v)
            {
                $setting       = array();
                $setting       = $v;
                $curl_settings = $setting;
                unset($curl_settings['map']);
                unset($curl_settings['args']);
                $class_name    = 'ds_' . $ids;
                $curl_settings = serialize($curl_settings);
                $handler       = array();
                $handler       = array(
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
                $tpl_strs      = '';
                $tpl_strs      = str_replace($handler, $replace_value, $tpl_str);
                hg_mkdir($this->settings['data_source_dir']);
                @file_put_contents($this->settings['data_source_dir'] . $ids . '.php', $tpl_str);
                
                hg_mkdir($this->settings['m2o_data_source_dir']);
                @file_put_contents($this->settings['m2o_data_source_dir'] . $ids . '.php', $tpl_str);
            }
        }
        else//生成单个文件
        {
            $setting = $return[0];
            $sql_    = "SELECT * FROM " . DB_PREFIX . "out_variable  WHERE mod_id =1 AND depath =3  AND expand_id =  " . $setting['id'];
            $q       = $this->db->query($sql_);
            while ($re      = $this->db->fetch_array($q))
            {
                $out_arment[$re['name']] = $re['value'];
            }
            $curl_settings = $setting;
            /* unset($curl_settings['map']); */
            unset($curl_settings['args']);
            $class_name    = explode('.', $setting['request_file']);
            $class_name    = 'ds_' . $ids;
            $curl_settings = serialize($curl_settings);
            $fieldreleate  = serialize($out_arment);
            $handler       = array(
                '{$file_name}',
                '{$class_name}',
                '{$args}',
                '{$settings}',
                '{$fieldreleate}'
            );
            $replace_value = array(
                $setting['request_file'],
                $class_name,
                $setting['argument'],
                $curl_settings,
                $fieldreleate,
            );
            $tpl_str       = str_replace($handler, $replace_value, $tpl_str);
            hg_mkdir($this->settings['data_source_dir']);
            @file_put_contents($this->settings['data_source_dir'] . $ids . '.php', $tpl_str);
            
            hg_mkdir($this->settings['m2o_data_source_dir']);
            @file_put_contents($this->settings['m2o_data_source_dir'] . $ids . '.php', $tpl_str);
                
        }
        $this->addItem('success');
        $this->output();
    }

    //获取所有数据源相关配置
    function showDataSource()
    {
        $sql  = "SELECT id,name,app_id,argument
				FROM  " . DB_PREFIX . "data_source 
				WHERE 1";
        $q    = $this->db->query($sql);
        $sql_ = "select name,id from " . DB_PREFIX . "app where 1";
        $apps = $this->db->fetch_all($sql_);
        foreach ($apps as $k => $v)
        {
            $appInfo[$v[id]] = $v['name'];
        }
        while ($row = $this->db->fetch_array($q))
        {

            $dataInfo[$row['app_id']][$row['id']] = array(
                'name' => $row['name'],
                'argument' => unserialize($row['argument']),
            );
        }
        $ret['datasource_data'] = $dataInfo;
        $ret['app_data']        = $appInfo;
        $this->addItem($ret);
        $this->output();
    }

    //获取数据源参数配置
    public function get_datasource_info()
    {
        $info = array();
        if ($id   = $this->input['id'])
        {
            $sql  = "SELECT id,name,app_id,argument FROM " . DB_PREFIX . "data_source WHERE id=" . $id;
            $info = $this->db->query_first($sql);
            if ($info['argument'])
            {
                $info['argument'] = unserialize($info['argument']);
            }
        }
        $this->addItem($info);
        $this->output();
    }

    //获取数据源得到的内容
    public function get_content_by_datasource()
    {
        $id      = $this->input['id'];
        $data    = $this->input['data'];
        $content = $this->obj->get_content_by_datasource($id, $data);
        $this->addItem($content);
        $this->output();
    }

    public function get_datasource_data()
    {
        $id = $this->input['id'];
        if ($id)
        {
            $sql  = "SELECT * FROM " . DB_PREFIX . "data_source WHERE id=" . $id;
            $info = $this->db->query_first($sql);
            $app  = 'App_' . $info['app_id'];
            $get_params = '?';
            if ($this->input['flag'])
            {
                if (is_array($this->input['bs']))
                {
                    foreach ($this->input['bs'] as $k => $v)
                    {
                    	if ($this->input['add_request'][$k] == 'post')
                    	{
                        	$data[$v] = $this->input['value'][$k];
                        }
                        else
                        {
                			$get_params .= "$v={$this->input['value'][$k]}&";
                        }
                    }
                }
            }
            else
            {
                $ar = unserialize($info['argument']);
                if (is_array($ar['ident']))
                {
                    foreach ($ar['ident'] as $k => $v)
                    {
                    	if ($ar['add_request'][$k] == 'post')
                    	{
                        	$data[$v] = $ar['value'][$k];
                        }
                        else
                        {
                			$get_params .= "$v={$ar['value'][$k]}&";
                        }
                    }
                }
            }

            require_once(ROOT_PATH . 'lib/class/curl.class.php');

            if ($info['app_id']) {
                $curl = new curl($this->settings[$app]['host'], $this->settings[$app]['dir']);
            }
            else {
                $curl = new curl($info['host'], $info['dir']);
            }


            $curl->setSubmitType('post');
            $curl->setReturnFormat('json');
            $curl->initPostData();
            if ($data)
            {
                foreach ($data as $k => $v)
                {
                    $curl->addRequestData($k, $v);
                }
            }
            $curl->addRequestData('html', true);
            $ret = $curl->request($info['request_file'] . $get_params);
            if (!$ret)
            {
                $this->addItem(array('error' => '没有数据'));
            }
            else
            {
                $this->addItem($ret);
            }
        }
        else
        {
            $this->addItem(array('error' => '请输入数据源id'));
        }

        $this->output();
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

    public function query()
    {
        if ($this->input['id'])
        {
            $id = intval($this->input['id']);
        }
        $url  = $this->settings['App_publishsys'];
        $file = 'http://' . $url['host'] . '/' . $url['dir'] . 'cache/' . $id . '.php';

        if (!is_file($this->settings['data_source_dir'] . $id . '.php'))
        {
            $this->obj->build_api($id);
        }
        include($this->settings['data_source_dir'] . $id . '.php');
        $class      = 'ds_' . $id;
        $this->data = new $class();

        $ret = $this->data->show($this->input['data']);
        $this->addItem($ret);
        $this->output();
    }

    public function check_sign()
    {
        $str = '';
        if ($this->input['id'])
        {
            $str .= " AND id!=" . $this->input['id'];
        }
        if ($this->input['sign'])
        {
            $str .= " AND sign='" . $this->input['sign'] . "'";
        }
        $sql    = "SELECT id FROM " . DB_PREFIX . "data_source WHERE 1";
        $sql .= $str;
        $info   = $this->db->query_first($sql);
        $result = 1;
        if ($info)
        {
            $result = 0;
        }
        $this->addItem($result);
        $this->output();
    }

    public function array_to_add($str, $data)
    {
        $str = $str ? $str : 'data';
        if (is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if (is_array($vv))
                {
                    $this->array_to_add($str . "[$kk]", $vv);
                }
                else
                {
                    $this->curl->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }

    public function export_datasource_()
    {
        if ($this->input['flag'])
        {
            $sql = "select 	*  from " . DB_PREFIX . "data_source  where sign !=''";
        }
        else
        {
            $signs_str = implode('","', explode(',', urldecode($this->input['sign'])));
            $sql       = 'select 	*  from  ' . DB_PREFIX . 'data_source  WHERE sign IN("' . $signs_str . '")';
        }

        $q = $this->db->query($sql);
        while ($d = $this->db->fetch_array($q))
        {
            $id_arr[]        = $d['id'];
            $dinfo[$d['id']] = $d;
        }

        $id_str = implode(',', $id_arr);
        $sl     = "select *  from " . DB_PREFIX . "out_variable  where mod_id =1  AND expand_id in ( " . $id_str . ")";
        $ql     = $this->db->query($sl);
        while ($da     = $this->db->fetch_array($ql))
        {
            $datas_out_variable[$da['expand_id']][] = $da;
        }
        $liv_datasource = array(
            'datasource_info' => $dinfo,
            'datasource_out_variable' => $datas_out_variable,
        );


        $this->addItem($liv_datasource);
        $this->output();
        /* $datasource_str = serialize($liv_datasource);

          $returnstr = "<?php\r\n";
          $returnstr .= "\$liv_datasource = array(";
          $returnstr .= 	 "'datasource'  => " . "'" . $datasource_str . "',";
          $returnstr .= ");\r\n?>";

          $filename = 'datasource.php';
          $name = '../data/'.$filename;
          file_put_contents($name,$returnstr); 
          
			    $filename = $v['sign'].'.php';
			    //开始解压
				$dir =	'../'.ROOT_PATH.'web/publish_product/';//解压后存放文件的目录
				if (!hg_mkdir($dir) || !is_writeable($dir))
				{
					$this->errorOutput($dir . '目录不可写');
				}
			    $name = $dir.$filename;
			    file_put_contents($name,$returnstr);*/
    }


 	public function export_datasource()
    {
        if ($this->input['flag'])
        {
            $sql = "select 	*  from " . DB_PREFIX . "data_source  where sign !=''";
        }
        else
        {
            $signs_str = implode('","', explode(',', urldecode($this->input['sign'])));
            $sql       = 'select 	*  from  ' . DB_PREFIX . 'data_source  WHERE sign IN("' . $signs_str . '")';
        }

        $q = $this->db->query($sql);
        while ($d = $this->db->fetch_array($q))
        {
            $id_arr[]        		= $d['id'];
            $sign_arr[$d['id']]     = $d['sign'];
            $dinfo[$d['sign']] 		= $d;
        }

        $id_str = implode(',', $id_arr);
        $sl     = "select *  from " . DB_PREFIX . "out_variable  where mod_id =1  AND expand_id in ( " . $id_str . ")";
        $ql     = $this->db->query($sl);
        while ($da     = $this->db->fetch_array($ql))
        {
            $datas_out_variable[$sign_arr[$da['expand_id']]][] = $da;
        }
		
		$host  = $this->settings['App_appstore']['host'];
        $dir   = $this->settings['App_appstore']['dir'];
        $curl  = new curl($host, $dir);
		$curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'publish_version');
		if($dinfo && is_array($dinfo))
		{
			foreach($dinfo as $k=>$v)
			{
				$liv_datasource = array(
		            'info' => $v,
		            'out_variable' => $datas_out_variable[$k],
        			);
        		//file_put_contents('0sa',var_export($liv_datasource,1));
        		$datasource_str = serialize($liv_datasource);
        		$curl->addRequestData('sign', $k);
        		$curl->addRequestData('title', $v['name']);
        		$curl->addRequestData('data', $datasource_str);
        		$curl->addRequestData('html', '1');
		        $curl->addRequestData('type', '1');
		      	$data_source_info = $curl->request('pub_template.php');
			}
		}
       
    }
    
    /* public function import()
      {
      require_once(ROOT_PATH. 'lib/class/curl.class.php');
      $curl = new curl($this->settings[$app]['host'],$this->settings[$app]['dir']);

      $curl->setSubmitType('post');
      $curl->setReturnFormat('json');
      $curl->initPostData();
      $curl->addRequestData('a','import_datasource');
      $curl->addRequestData('html',true);
      $ret = $curl->request('data_source.php');
      } */

    //导入样式
    public function import_datasource()
    {
    	$file = $this->input['file'];
    	$sign_ar = array_keys($file);
    	$signs_str = implode('","',$sign_ar);
    	if($signs_str)
    	{
	        $sql   = 'select 	*  from  ' . DB_PREFIX . 'data_source  WHERE sign IN("' . $signs_str . '")';
	        $mq    = $this->db->query($sql);
	        while ($rm   = $this->db->fetch_array($mq))
	        {
	            if ($rm['sign'])
	            {
	                $dasign[$rm['sign']] = $rm['id'];
	            }
	        }
    	}
		if($file && is_array($file))
		{
			foreach($file as $k=>$v)
			{
				$datasource      		 = unserialize($v['data']);
		        $datasource_info         = $datasource['info'];
		        $datasource_out_variable = $datasource['out_variable'];
		        if($dasign[$k])
		        {
		        	$datasource_info['id']       = $dasign[$k];
		        	$sq_   = 'select *  from  ' . DB_PREFIX . 'data_source   WHERE id =' . $dasign[$k];
					$dsinfo   = $this->db->query_first($sq_);
					
	                $this->obj->update($datasource_info, 'data_source');
	                
	                $this->addLogs('商店更新数据源' , $dsinfo , $datasource_info, '商店更新数据源'.$datasource_info['name']);
	                
                  	if (is_array($datasource_out_variable))
                    {
                    	$data_source_id = $dasign[$k];
                        $das_['expand_id'] = $data_source_id;
                        $das_['mod_id']    = '1';
                        $this->obj->delete_datasource_para($das_, 'out_variable');
                        $datafid	= 	$this->obj->create_out_argument('data', '0', $data_source_id);
                        $fid        = 	$this->obj->create_out_argument('0', $datafid, $data_source_id);
                        foreach ($datasource_out_variable as $key => $val)
                        {
                            if ($val['name'] != 'data' && $val['name'] != '0')
                            {
                                $this->obj->create_out_argument($val['name'], $fid, $data_source_id, $val['title'], $val['value']);
                            }
                        }
                    }
		        }
		        else
		        {
		        	unset($datasource_info['id']);
                    $data_source_id = $this->obj->import_datasource_info($datasource_info, 'data_source');
                    
                    $this->addLogs('商店安装数据源' , '' , $datasource_info, '商店安装数据源'.$datasource_info['name']);
	                
                    if (is_array($datasource_out_variable))
                    {
                        $das_['expand_id'] = $data_source_id;
                        $das_['mod_id']    = '1';
                        $this->obj->delete_datasource_para($das_, 'out_variable');
                        $datafid	=	$this->obj->create_out_argument('data', '0', $data_source_id);
                        $fid        = 	$this->obj->create_out_argument('0', $datafid, $data_source_id);
                        foreach ($datasource_out_variable as $key => $val)
                        {
                            if ($val['name'] != 'data' && $val['name'] != '0')
                            {
                                $this->obj->create_out_argument($val['name'], $fid, $data_source_id, $val['title'], $val['value']);
                            }
                        }
                    }
		        }
			}
			
		}
        

        $this->addItem(array('ture'));
        $this->output();
    }

}

$out    = new dataSourceApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
