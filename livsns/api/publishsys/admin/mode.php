<?php

require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID', 'mode'); //模块标识

class modeApi extends nodeFrm
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/mode.class.php');
        $this->obj = new mode();
        require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
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
//			if(!in_array('mode',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		
        //$site_id   = isset($this->input['site_id']) ? $this->input['site_id'] : 1;
        $condition = $this->get_condition();
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
        $limit     = " limit {$offset}, {$count}";
        $ret       = $this->obj->show($condition, $limit);

        $this->addItem($ret);
        $this->output();
    }

    function detail()
    {
        $sql      = 'SELECT *
				FROM ' . DB_PREFIX . 'cell_mode WHERE id = ' . intval($this->input['id']);
        $r        = $this->db->query_first($sql);
        $argument = unserialize($r['argument']);
        if (is_array($argument['type']))
        {
            foreach ($argument['type'] as $k => $v)
            {
                if ($v == 'select')
                {
                    $argument['other_value'][$k] = hg_string_to_array($argument['other_value'][$k]);
                    if (is_array($argument['other_value'][$k]))
                    {
                        foreach ($argument['other_value'][$k] as $ke => $va)
                        {
                            if (isset($ke) && isset($va))
                            {
                                $other_value[$ke] = $va;
                            }
                        }
                    }
                    $argument['other_value'][$k] = $other_value;
                }
                unset($other_value);
            }
        }
        if ($argument['sign'])
        {
            $sign_str              = implode(',', $argument['sign']);
            $argument['sign_name'] = $this->obj->get_code_para_name('html', $sign_str);
            $argument['sign_info'] = $this->obj->get_para_name('html', $sign_str);
        }

        $r['argument'] = $argument;
        $css           = $js            = array();
        $sqll          = "SELECT * FROM " . DB_PREFIX . "cell_mode_code WHERE mode_id = " . intval($this->input['id']) . " AND del =0";
        $sll           = $this->db->query($sqll);
        $css_sign      = array();
        while ($rowl          = $this->db->fetch_array($sll))
        {
            if ($rowl['type'] == 'js')
            {
                $js_argument = unserialize($rowl['para']);
                $jskey       = array('js_name', 'js_sign', 'js_flag', 'js_default_value', 'js_other_value', 'js_type');
                $js_arg      = hg_format_array($js_argument, $jskey);
                if (is_array($js_arg['js_type']))
                {
                    foreach ($js_arg['js_type'] as $k => $v)
                    {
                        if ($v == 'select')
                        {
                            $js_arg['js_other_value'][$k] = hg_string_to_array($js_arg['js_other_value'][$k]);
                            if (is_array($js_arg['js_other_value'][$k]))
                            {
                                foreach ($js_arg['js_other_value'][$k] as $ke => $va)
                                {
                                    if (isset($ke) && isset($va))
                                    {
                                        $jsother_value[$ke] = $va;
                                    }
                                }
                            }
                            $js_arg['js_other_value'][$k] = $jsother_value;
                        }
                        unset($jsother_value);
                    }
                }
                if ($js_arg['js_sign'])
                {
                    $js_sign_str            = implode(',', $js_arg['js_sign']);
                    $js_arg['js_sign_name'] = $this->obj->get_code_para_name('js', $js_sign_str);
                    $js_arg['js_sign_info'] = $this->obj->get_para_name('js', $js_sign_str);
                }

                $js['js_argument'] = $js_arg;
                $js['code']        = $rowl['code'];
                $js['js_title']    = $rowl['title'];
            }

            if ($rowl['type'] == 'css')
            {
                $css_argument = unserialize($rowl['para']);
                $csskey       = array('css_name', 'css_sign', 'css_flag', 'css_default_value', 'css_other_value', 'css_type');
                $css_ar       = hg_format_array($css_argument, $csskey);
                if (is_array($css_ar['css_type']))
                {
                    foreach ($css_ar['css_type'] as $k => $v)
                    {
                        if ($v == 'select')
                        {
                            $css_ar['css_other_value'][$k] = hg_string_to_array($css_ar['css_other_value'][$k]);
                            if (is_array($css_ar['css_other_value'][$k]))
                            {
                                foreach ($css_ar['css_other_value'][$k] as $ke => $va)
                                {
                                    if (isset($ke) && isset($va))
                                    {
                                        $cssother_value[$ke] = $va;
                                    }
                                }
                            }
                            $css_ar['css_other_value'][$k] = $cssother_value;
                        }
                        unset($cssother_value);
                    }
                }
                if ($css_ar['css_sign'])
                {
                    $css_sign_str            = implode(',', $css_ar['css_sign']);
                    $css_ar['css_sign_name'] = $this->obj->get_code_para_name('css', $css_sign_str);
                    $css_sign                = array_merge($css_sign, $css_ar['css_sign']);
                    //$css_ar['css_sign_info'] = $this->obj->get_para_name('css',$css_sign_str);
                }


                $css[$rowl['id']]['css_argument'] = $css_ar;
                $css[$rowl['id']]['code']         = $rowl['code'];
                $css[$rowl['id']]['css_title']    = $rowl['title'];
                $css[$rowl['id']]['css_indexpic'] = $rowl['indexpic'];
                $css[$rowl['id']]['default_css']  = $rowl['default_css'];
            }
        }
        $sql_ = "SELECT * FROM " . DB_PREFIX . "out_variable  WHERE mod_id =2 AND depath =3  AND expand_id =  " . $this->input['id'];
        $q    = $this->db->query($sql_);
        while ($re   = $this->db->fetch_array($q))
        {
            $out_arment['name'][]  = $re['name'];
            $out_arment['value'][] = $re['value'];
            $out_arment['flag'][]  = $re['flag'];
        }
        $sqll = "SELECT id FROM " . DB_PREFIX . "out_variable  WHERE mod_id =2 AND depath =2  AND expand_id =  " . $this->input['id'];
        $fi   = $this->db->query_first($sqll);
        $fid  = $fi['id'];
        if (!$fid)
        {
            $datafid = $this->obj->create_out_para('data', '0', $this->input['id']);
            $fid     = $this->obj->create_out_para('0', $datafid, $this->input['id']);
        }

        $r['fid']     = $fid;
        $pregfind     = array('&#60;&#60;&amp;#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
        $pregreplace  = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
        $r['content'] = str_replace($pregfind, $pregreplace, $r['content']);
        $r['content'] = htmlspecialchars($r['content']);

        $r['out_arment'] = $out_arment;
        $r['css']        = $css;
        $r['js']         = $js;
        $csign_str       = implode(',', $css_sign);
        $r['css_sign']   = $this->obj->get_para_name('css', $csign_str);
        if ($r['default_param'])
        {
            $r['default_param'] = unserialize($r['default_param']);
        }
        /*if($r['indexpic'])
        {
        	$indexpic = unserialize($r['indexpic']);
        	$url = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
        	$pic = file_get_contents($url);
			if($pic)
			{
				$dir = CUR_CONF_PATH.'data/template/pic/';
				hg_mkdir($dir);
				file_put_contents($dir.$indexpic['filename'],$pic);
			}
			$index_pic  = array(
					'host'			=>	$this->settings['template_image_url']."/",
					'dir'			=>	'pic/',
					'filepath'		=>	'',
					'filename'		=>	$indexpic['filename'],
			);
			$r['indexpic'] = serialize($index_pic);
        }*/
        $ret[] = $r;

        $ret['mode_node'] = $this->obj->get_mode_node(' AND mod_id=2 AND expand_id=' . $this->input['id'] . ' AND fid=0 ORDER BY order_id ', '');
        $ret['mode_id']   = $this->input['id'];
        $this->addItem($ret);
        $this->output();
    }

    /* 	function edit()
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
      $this->errorOutput('模板数据不存在！');
      }
      $html = $this->editer('content' , $r['content'] , array('cols' => 60 , 'rows' =>20));

      $r['html'] = $html;
      $r['template_types'] = $this->settings['template_types'];
      unset($r['content']);
      $this->addItem($r);
      $this->output();
      } */

    function editer($id, $content, $conf = array())
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
        //$site_id    = isset($this->input['site_id']) ? $this->input['site_id'] : 1;
        $sql        = 'SELECT count(*) as total from ' . DB_PREFIX . 'cell_mode WHERE 1 ' . $this->get_condition();
        $cell_total = $this->db->query_first($sql);
        echo json_encode($cell_total);
    }

    public function download()
    {
        $sql       = "SELECT *
				FROM " . DB_PREFIX . "cell_mode
				WHERE id = " . $this->input['id'];
        $mode_info = $this->db->query_first($sql);

        if ($mode_info['content'])
        {
            if (function_exists('iconv'))
            {
                $mode_info['file_name'] = iconv("utf-8", "gb2312", $mode_info['id'] . '.html');
            }
            else
            {
                $mode_info['file_name'] = 'tpl_' . $mode_info['id'] . $mode_info['id'] . '.html';
            }
            $this->addItem($mode_info);
            $this->output();
        }
        else
        {
            $this->errorOutput('样式不存在！');
        }
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
        $sort_id   = $this->input['sort_id'];
        if ($sort_id && '-1' != $sort_id)
        {
            $condition .= " AND sort_id = " . $sort_id;
        }
        /* if ('-1' != $site_id)
        {
            $condition .=" AND site_id =" . $site_id;
        }*/
        if ($this->input['sign'])
        {
            $condition .=" AND sign !=''";
        }
        if ($this->input['k'])
        {
            $condition .= ' AND title LIKE "%' . trim($this->input['k']) . '%"' . '  OR content  LIKE "%' . trim($this->input['k']) . '%"';
        }
        return $condition;
    }

    //获取样式名称
    public function get_mode_name()
    {
        //$site_id   = $this->input['site_id'];
        $condition = '';
        /*if (!$site_id)
        {
            $site_id   = $this->settings['site_default'];
            $condition = ' AND site_id = ' . $site_id;
        }
        if ($site_id && '-1' != $site_id)
        {
            $condition = ' AND site_id = ' . $site_id;
        }*/
        $sql = "select id,title from " . DB_PREFIX . "cell_mode  where 1" . $condition;
        $q   = $this->db->query($sql);
        $ret = array();
        while ($r   = $this->db->fetch_array($q))
        {
            $ret[$r['id']] = $r['title'];
        }
        $this->addItem($ret);
        $this->output();
    }

    //获取样式参数
    public function get_mode_variable()
    {
        $sql = "select 	*  from " . DB_PREFIX . "cell_mode_variable where cell_mode_id  = " . $this->input['mode_id'];

        $q   = $this->db->query($sql);
        $ret = array();
        while ($r   = $this->db->fetch_array($q))
        {
            $ret[] = $r;
        }
        //$ret = unserialize($ret['argument']);
        $this->addItem($ret);
        $this->output();
    }

    //获取样式分类名称
    public function get_sort_name()
    {
        //$site_id = $this->input['site_id'];
        $con     = '';
        /*if (!$site_id)
        {
            $site_id = $this->settings['site_default'];
        }
        $con = ' AND site_id = ' . $site_id;
		*/
        $sql = "select id,name from " . DB_PREFIX . "cell_mode_sort where 1" . $con;
        $q   = $this->db->query($sql);
        $ret = array();
        while ($r   = $this->db->fetch_array($q))
        {
            $ret[$r['id']] = $r['name'];
        }
        $this->addItem($ret);
        $this->output();
    }

    //获取样式函数
    public function get_fuctions()
    {
        $fuctions = include(CUR_CONF_PATH . 'conf/web_functions.conf.php');
        $this->addItem($fuctions);
        $this->output();
    }

    //获取样式类型
    public function get_mode_type()
    {
        $mode_type = $this->settings['mode_type'];

        $this->addItem($mode_type);
        $this->output();
    }

    //获取默认数据分类
    public function get_data_cate()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "data_cate  WHERE 1";
        $q   = $this->db->query($sql);
        $ret = array();
        while ($re  = $this->db->fetch_array($q))
        {
            $ret[$re['id']] = $re['name'];
        }

        $this->addItem($ret);
        $this->output();
    }

    //获取样式代码参数
    public function get_mode_code()
    {
        $mode_code = include(CUR_CONF_PATH . 'conf/dict.conf.php');

        $this->addItem($mode_code);
        $this->output();
    }

	public function get_mode_sort()
	{
		$site_id = $this->input['site_id'];
		$con = '';
		if(!$site_id)
		{
			$site_id = $this->settings['site_default'];
			
		}
		$con = ' AND site_id = '.$site_id;
		$sql_ = "select id,name from " . DB_PREFIX . "cell_mode_sort  where 1".$con;	
		$q_ = $this->db->query($sql_);
		while($r = $this->db->fetch_array($q_))
		{
			$ret[$r['id']] = $r['name'];
		}
		$this->addItem($ret);
		$this->output();
	}
	
    /* //获取样式代码参数
      public function get_code_para()
      {
      $code  = addslashes(html_entity_decode(urldecode($this->input['code']),ENT_QUOTES));
      $te = "/\\#([a-zA-Z_]+[0-9_]*)([\s|,|\+|'|\=|\)\}])/is";
      //preg_match_all('/\{(?:if\s+)?(?:else\s+if\s+)?\#(.*?)\}/',$code,$arr);
      preg_match_all($te, $code, $m);
      $para = array_unique($m[1]);
      $this->addItem($para);
      $this->output();
      }

      //获取样式代码$参数
      public function get_para()
      {
      $code  = addslashes(html_entity_decode(urldecode($this->input['code']),ENT_QUOTES));
      $te = "/\\$([a-zA-Z_]+[0-9_]*)([\s|,|\+|'|\=|\)\}])/is";
      //preg_match_all('/\{(?:if\s+)?(?:else\s+if\s+)?\#(.*?)\}/',$code,$arr);
      preg_match_all($te, $code, $m);
      $para = array_unique($m[1]);
      if(is_array($para))
      {
      foreach($para as $k=>$v)
      {
      if($v!='data')
      {
      $re_para[$k] = $v;
      }
      }
      }
      $this->addItem($re_para);
      $this->output();
      } */

    //获取代码参数名
    public function get_code_para_name()
    {

        $type   = $this->input['type'];
        $params = $this->input['params'];
        $pa_arr = explode(',', $params);

        $sql  = "select 	*  from " . DB_PREFIX . "cell_code_para_name  where type = '" . $type . "'";
        $ql   = $this->db->query($sql);
        $rett = array();
        while ($r    = $this->db->fetch_array($ql))
        {
            if (in_array($r['sign'], $pa_arr))
            {
                $rett[$r['sign']][$r['id']]['name']          = $r['name'];
                $rett[$r['sign']][$r['id']]['default_value'] = $r['default_value'];
                $rett[$r['sign']][$r['id']]['para_type']     = $r['para_type'];
                $rett[$r['sign']][$r['id']]['other_value']   = $r['other_value'] ? unserialize($r['other_value']) : array();
            }
        }
        if ($pa_arr)
        {
            foreach ($pa_arr as $k => $v)
            {
                if ($rett[$v])
                {
                    $data[$v] = $rett[$v];
                }
                else
                {
                    $data[$v] = array();
                }
            }
        }
        $this->addItem($data);
        $this->output();
    }

    //导出样式
    public function export_mode()
    {
        $mode_id = $this->input['id'];
        $sql     = "select 	*  from " . DB_PREFIX . "cell_mode  where id  = " . $mode_id;
        $minfo   = $this->db->query_first($sql);

        $sql_ = "select *  from " . DB_PREFIX . "cell_mode_code  where mode_id  = " . $mode_id;
        $qq   = $this->db->query($sql_);
        while ($r    = $this->db->fetch_array($qq))
        {
            $mode_code[] = $r;
        }
        $sqll = "select *  from " . DB_PREFIX . "cell_mode_variable  where cell_mode_id  = " . $mode_id;
        $qql  = $this->db->query($sqll);
        while ($re   = $this->db->fetch_array($qql))
        {
            $cell_mode_variable[] = $re;
        }

        $sl  = "select *  from " . DB_PREFIX . "out_variable  where mod_id =2 AND expand_id  = " . $mode_id;
        $ql  = $this->db->query($sl);
        while ($ret = $this->db->fetch_array($ql))
        {
            $cell_out_variable[] = $ret;
        }
        $liv_cellmode = array(
            'mode_info' => $minfo,
            'mode_code' => $mode_code,
            'cell_mode_variable' => $cell_mode_variable,
            'cell_out_variable' => $cell_out_variable,
        );
        $celmode_str  = serialize($liv_cellmode);

        $returnstr = "<?php\r\n";
        $returnstr .= "\$liv_cellmode = array(";
        $returnstr .= "'cellmode'  => " . "'" . $celmode_str . "',";
        $returnstr .= ");\r\n?>";

        if ($this->input['flag'])
        {
            $filename = $minfo['id'] . '.php';
            $name     = '../data/mode/' . $filename;
            file_put_contents($name, $returnstr);
        }
        else
        {
            $filename = $minfo['title'] . '.php';
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control:');
            header('Cache-Control: public');
            header('Content-Description: File Transfer');
            header('Content-Type: application/force-download');
            header("Content-Disposition: attachment; filename='" . $filename . "'");
            //header('Content-Disposition: attachment; filename="cellmode.php";');
            header('Content-Transfer-Encoding: binary');
            echo $returnstr;
            exit();
        }
    }

    //导入样式
    public function import_mode()
    {
        $file = array();
        $dir  = CUR_CONF_PATH . 'data/mode/';
        if ($id   = $this->input['id'])
        {
            $f = $dir . $id . '.php';
        }
        else
        {
            if ($file = $_FILES['Filedata'])
            {
                //创建目录存放解压文件
                if (!hg_mkdir($dir) || !is_writeable($dir))
                {
                    $this->errorOutput($dir . '目录不可写');
                }
                @unlink($dir . '/' . $file['name']);
                if (!move_uploaded_file($file['tmp_name'], $dir . $file['name']))
                {
                    $this->errorOutput('文件移动失败');
                }
                $f = $dir . $file['name'];
            }
        }
        if (!file_exists($f))
        {
            $this->errorOutput('文件未生成');
        }
        include($f);
        $cellmode           = unserialize($liv_cellmode['cellmode']);
        $mode_info          = $cellmode['mode_info'];
        $mode_code          = $cellmode['mode_code'];
        $cell_mode_variable = $cellmode['cell_mode_variable'];
        $cell_out_variable  = $cellmode['cell_out_variable'];

        if (is_array($mode_info))
        {
            unset($mode_info['id']);
            $mode_id = $this->obj->import_mode_info($mode_info, 'cell_mode');
        }
        if (is_array($mode_code))
        {
            foreach ($mode_code as $k => $v)
            {
                unset($v['id']);
                $v['mode_id'] = $mode_id;
                $code_id      = $this->obj->import_mode_info($v, 'cell_mode_code');
            }
        }
        if (is_array($cell_mode_variable))
        {
            foreach ($cell_mode_variable as $ke => $va)
            {
                unset($va['id']);
                $va['cell_mode_id'] = $mode_id;
                $mode_variable_id   = $this->obj->import_mode_info($va, 'cell_mode_variable');
            }
        }
        if (is_array($cell_out_variable))
        {
            foreach ($cell_out_variable as $key => $val)
            {
                unset($val['id']);
                $val['expand_id'] = $mode_id;
                $out_variable_id  = $this->obj->import_mode_info($val, 'out_variable');
            }
        }
        $this->addItem(array('ture'));
        $this->output();
    }

    //导出所有样式
    public function export()
    {
//    	if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if($action && is_array($action) && !in_array('mode',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		exit;
        $sql = "select 	*  from " . DB_PREFIX . "cell_mode  where mode_sign !=''";
        $mq  = $this->db->query($sql);
        while ($rm  = $this->db->fetch_array($mq))
        {
            $minfo[$rm['id']] = $rm;
            $mid_arr[]        = $rm['id'];
        }
        $mid_str = implode(',', $mid_arr);
        //exit;
        $sql_    = "select *  from " . DB_PREFIX . "cell_mode_code  where mode_id in ( " . $mid_str . ")";
        $qq      = $this->db->query($sql_);
        while ($r       = $this->db->fetch_array($qq))
        {
            $mode_code[$r['mode_id']][] = $r;
        }
        $sqll = "select *  from " . DB_PREFIX . "cell_mode_variable  where cell_mode_id in ( " . $mid_str . ")";
        $qql  = $this->db->query($sqll);
        while ($re   = $this->db->fetch_array($qql))
        {
            $cell_mode_variable[$re['cell_mode_id']][] = $re;
        }

        $sl  = "select *  from " . DB_PREFIX . "out_variable  where mod_id =2  AND expand_id in ( " . $mid_str . ")";
        $ql  = $this->db->query($sl);
        while ($ret = $this->db->fetch_array($ql))
        {
            $cell_out_variable[$ret['expand_id']][] = $ret;
        }

        $liv_cellmode = array(
            'mode_info' => $minfo,
            'mode_code' => $mode_code,
            'cell_mode_variable' => $cell_mode_variable,
            'cell_out_variable' => $cell_out_variable,
        );
        $celmode_str  = serialize($liv_cellmode);

        $returnstr = "<?php\r\n";
        $returnstr .= "\$liv_cellmode = array(";
        $returnstr .= "'cellmode'  => " . "'" . $celmode_str . "',";
        $returnstr .= ");\r\n?>";

        $filename = 'import.php';
        $name     = '../data/' . $filename;
        file_put_contents($name, $returnstr);
    }

    //导入样式
    public function import()
    {
        $file = array();
        $f    = CUR_CONF_PATH . 'data/mode/import.php';
        if (!file_exists($f))
        {
            $this->errorOutput('文件未生成');
        }

        $sql = "select 	*  from " . DB_PREFIX . "cell_mode  where 1 ";
        $mq  = $this->db->query($sql);
        while ($rm  = $this->db->fetch_array($mq))
        {
            if ($rm['mode_sign'])
            {
                $msign[$rm['id']] = $rm['mode_sign'];
            }
        }
        $msid = array_flip($msign);

        include($f);
        $cellmode           = unserialize($liv_cellmode['cellmode']);
        //file_put_contents('00',var_export($cellmode,1));exit;
        $mode_info          = $cellmode['mode_info'];
        $mode_code          = $cellmode['mode_code'];
        $cell_mode_variable = $cellmode['cell_mode_variable'];
        $cell_out_variable  = $cellmode['cell_out_variable'];
        //update
        if ($mode_info && is_array($mode_info))
        {
            foreach ($mode_info as $ks => $vs)
            {
                $moid = $vs['id'];
                if (is_array($vs))
                {
                    if (in_array($vs['mode_sign'], $msign))
                    {
                        $mode_id  = $msid[$vs['mode_sign']];
                        $vs['id'] = $mode_id;
                        $this->obj->update($vs, 'cell_mode');
                    }
                    else
                    {
                        unset($vs['id']);
                        $mode_id = $this->obj->import_mode_info($vs, 'cell_mode');
                    }
                    if (is_array($mode_code[$moid]))
                    {
                        //$da['mode_id'] = $mode_id;
                        //$this->obj->delete_mode_para($da,'cell_mode_code');
                        foreach ($mode_code[$moid] as $k => $v)
                        {
                            //unset($v['id']);
                            $da['id'] = $v['id'];
                            $re       = $this->obj->query_para($da, 'cell_mode_code');
                            if (!$re)
                            {
                                //unset($v['id']);
                                $v['mode_id'] = $mode_id;
                                $code_id      = $this->obj->import_mode_info($v, 'cell_mode_code');
                            }
                        }
                    }
                    if (is_array($cell_mode_variable[$moid]))
                    {
                        $da_['cell_mode_id'] = $mode_id;
                        //$this->obj->delete_mode_para($da_,'cell_mode_variable');
                        foreach ($cell_mode_variable[$moid] as $ke => $va)
                        {
                            $da_['id'] = $va['id'];
                            $re_       = $this->obj->query_para($da_, 'cell_mode_variable');
                            if (!$re_)
                            {
                                //($va['id']);
                                $va['cell_mode_id'] = $mode_id;
                                $mode_variable_id   = $this->obj->import_mode_info($va, 'cell_mode_variable');
                            }
                        }
                    }
                    if (is_array($cell_out_variable[$moid]))
                    {
                        $das_['expand_id'] = $mode_id;
                        //$this->obj->delete_mode_para($das_,'out_variable');
                        foreach ($cell_out_variable[$moid] as $key => $val)
                        {
                            $dal_['id'] = $val['id'];
                            $rea_       = $this->obj->query_para($dal_, 'out_variable');
                            if (!$rea_)
                            {
                                //unset($val['id']);
                                $val['expand_id'] = $mode_id;
                                $out_variable_id  = $this->obj->import_mode_info($val, 'out_variable');
                            }
                        }
                    }
                }
            }
        }

        $this->addItem(array('ture'));
        $this->output();
    }

    //导出所有样式
    public function export_modeinfo()
    {
    	$sqll = "select id,name from " . DB_PREFIX . "cell_mode_sort where 1";	
		$ql = $this->db->query($sqll);
		$msort = array();
		while($r = $this->db->fetch_array($ql))
		{
			if($r['id'])
			{
				$msort[$r['id']] = $r['name'];
			}
		}
		
        $signs_str = implode('","', explode(',', urldecode($this->input['sign'])));
        $sql       = 'select 	*  from  ' . DB_PREFIX . 'cell_mode  WHERE sign IN("' . $signs_str . '")';
        $mq        = $this->db->query($sql);
        while ($rm        = $this->db->fetch_array($mq))
        {
            $rm['content'] = htmlspecialchars($rm['content'],ENT_QUOTES);
            if($rm['sort_id'] && $msort[$rm['sort_id']])
            {
            	  $rm['sort_name'] = $msort[$rm['sort_id']];
            }
            $minfo[$rm['id']] = $rm;
            $mid_arr[]        = $rm['id'];
        }
        $mid_str = implode(',', $mid_arr);
        //exit;
        $sql_    = "select *  from " . DB_PREFIX . "cell_mode_code  where mode_id in ( " . $mid_str . ")";
        $qq      = $this->db->query($sql_);
        while ($r       = $this->db->fetch_array($qq))
        {
            $mode_code[$r['mode_id']][$r['sign']] = $r;
        }
        $sqll = "select *  from " . DB_PREFIX . "cell_mode_variable  where cell_mode_id in ( " . $mid_str . ")";
        $qql  = $this->db->query($sqll);
        while ($re   = $this->db->fetch_array($qql))
        {
            $cell_mode_variable[$re['cell_mode_id']][] = $re;
        }

        $sl  = "select *  from " . DB_PREFIX . "out_variable  where mod_id =2  AND expand_id in ( " . $mid_str . ")";
        $ql  = $this->db->query($sl);
        while ($ret = $this->db->fetch_array($ql))
        {
            $cell_out_variable[$ret['expand_id']][] = $ret;
        }

        $host = $this->settings['App_appstore']['host'];
        $dir  = $this->settings['App_appstore']['dir'];
        $curl = new curl($host, $dir);
        $curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'publish_version');

        if ($minfo && is_array($minfo))
        {
            foreach ($minfo as $k => $v)
            {
                $liv_cellmode     = array(
                    'mode_info' => $v,
                    'mode_code' => $mode_code[$k],
                    'cell_mode_variable' => $cell_mode_variable[$k],
                    'cell_out_variable' => $cell_out_variable[$k],
                );
                $modeinfo_str     = serialize($liv_cellmode);
                $curl->addRequestData('sign', $v['sign']);
                $curl->addRequestData('title', $v['title']);
                $curl->addRequestData('data', $modeinfo_str);
                $curl->addRequestData('html', '1');
                $curl->addRequestData('type', '2');
                $data_source_info = $curl->request('pub_template.php');
            }
        }
    }

    //导入样式
    public function import_modeinfo()
    {
        $file      = $this->input['file'];
        $sign_ar   = array_keys($file);
        $signs_str = implode('","', $sign_ar);
        if ($signs_str)
        {
            $sql = 'select *  from  ' . DB_PREFIX . 'cell_mode  WHERE sign IN("' . $signs_str . '")';
            $mq  = $this->db->query($sql);
            while ($rm  = $this->db->fetch_array($mq))
            {
                if ($rm['sign'])
                {
                    $msign[$rm['sign']] = $rm['id'];
                    $mid_arr[]          = $rm['id'];
                }
            }
            $mid_str = implode(',', $mid_arr);
        }
        if ($mid_str)
        {
            $sql_ = "select *  from " . DB_PREFIX . "cell_mode_code  where mode_id in ( " . $mid_str . ")";
            $qq   = $this->db->query($sql_);
            while ($r    = $this->db->fetch_array($qq))
            {
                $moco[$r['mode_id']][]             = $r['sign'];
                $mosign[$r['mode_id']][$r['sign']] = $r['id'];
            }
        }
        
        $sqll = "select id,name from " . DB_PREFIX . "cell_mode_sort where 1";	
		$ql = $this->db->query($sqll);
		$msort = array();
		while($r = $this->db->fetch_array($ql))
		{
			if($r['id'])
			{
				$msort[$r['name']] = $r['id'];
			}
		}
		

        if ($file && is_array($file))
        {
            foreach ($file as $k => $v)
            {
                $cellmode           = unserialize($v['data']);
                $mode_info          = $cellmode['mode_info'];
                $mode_code          = $cellmode['mode_code'];
                $cell_mode_variable = $cellmode['cell_mode_variable'];
                $cell_out_variable  = $cellmode['cell_out_variable'];
                $mode_info['content'] = htmlspecialchars_decode($mode_info['content'],ENT_QUOTES);
                if ($msign[$k])
                {
                    $mode_info['id'] = $msign[$k];
                    $sq_   = 'select *  from  ' . DB_PREFIX . 'cell_mode  WHERE id =' . $msign[$k];
					$mdinfo   = $this->db->query_first($sq_);
					
                    if($mode_info['indexpic'])
			        {
			        	$indexpic = unserialize($mode_info['indexpic']);
			        	if(strstr($indexpic['host'],"img.dev.hogesoft.com")!==false)
				        {
				        	$url = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
				        	$pic = file_get_contents($url);
							if($pic)
							{
								$dir = CUR_CONF_PATH.'data/mode/pic/';
								hg_mkdir($dir);
								file_put_contents($dir.$indexpic['filename'],$pic);
							}
							$index_pic  = array(
									'host'			=>	$this->settings['mode_image_url']."/",
									'dir'			=>	'pic/',
									'filepath'		=>	'',
									'filename'		=>	$indexpic['filename'],
							);
							$picurl =  $index_pic['host'].$index_pic['dir'].$index_pic['filepath'].$index_pic['filename'];
							$pic_info = $this->material->localMaterial($picurl);//插入图片服务器
							if($pic_info[0])
							{
								$arr = array(
									'host'			=>$pic_info[0]['host'],
									'dir'			=>$pic_info[0]['dir'],
									'filepath'		=>$pic_info[0]['filepath'],
									'filename'		=>$pic_info[0]['filename'],
								);
								$mode_info['indexpic'] =	serialize($arr);
							}	
				        }
			        }
        			
        			if($mode_info['effectpic'])
			        {
			        	$effectpic = unserialize($mode_info['effectpic']);
			        	if(strstr($effectpic['host'],"img.dev.hogesoft.com")!==false)
				        {
				        	$eurl = $effectpic['host'].$effectpic['dir'].$effectpic['filepath'].$effectpic['filename'];
				        	$epic = file_get_contents($eurl);
							if($epic)
							{
								$edir = CUR_CONF_PATH.'data/mode/pic/';
								hg_mkdir($edir);
								file_put_contents($edir.$effectpic['filename'],$epic);
							}
							$effect_pic  = array(
									'host'			=>	$this->settings['mode_image_url']."/",
									'dir'			=>	'pic/',
									'filepath'		=>	'',
									'filename'		=>	$effectpic['filename'],
							);
							$epicurl =  $effect_pic['host'].$effect_pic['dir'].$effect_pic['filepath'].$effect_pic['filename'];
							$epic_info = $this->material->localMaterial($epicurl);//插入图片服务器
							if($epic_info[0])
							{
								$earr = array(
									'host'			=>$epic_info[0]['host'],
									'dir'			=>$epic_info[0]['dir'],
									'filepath'		=>$epic_info[0]['filepath'],
									'filename'		=>$epic_info[0]['filename'],
								);
								$mode_info['effectpic'] =	serialize($earr);
							}	
				        }
			        }
			        if($mode_info['sort_name'])
			        {
			        	if($msort[$mode_info['sort_name']])
			        	{
			        		$mode_info['sort_id'] = $msort[$mode_info['sort_name']];
			        	}
			        }
			        unset($mode_info['sort_name']);
                    $this->obj->update($mode_info, 'cell_mode');
                    
                    $this->addLogs('商店更新样式' , $mdinfo , $mode_info, '商店更新样式'.$mode_info['title']);
                    
                    $mode_id         = $msign[$k];
                    if (is_array($mode_code))
                    {
                        foreach ($mode_code as $ka => $va)
                        {
                            if (in_array($va['sign'], $moco[$mode_info['id']]))
                            {
                                $va['id'] = $mosign[$mode_id][$va['sign']];
                                $va['mode_id'] = $mode_id;
                                $this->obj->update($va, 'cell_mode_code');
                            }
                            else
                            {
                                unset($va['id']);
                                $va['mode_id'] = $mode_id;
                                $code_id      = $this->obj->import_mode_info($va, 'cell_mode_code');
                            }
                        }
                    }

                    if (is_array($cell_mode_variable))
                    {
                    	$var['cell_mode_id'] = $mode_id;
                        $this->obj->delete_mode_para($var, 'cell_mode_variable');
                        foreach ($cell_mode_variable as $kea => $vaa)
                        {
                            unset($vaa['id']);
                            $vaa['cell_mode_id'] = $mode_id;
                            $mode_variable_id    = $this->obj->import_mode_info($vaa, 'cell_mode_variable');
                        }
                    }
                    if (is_array($cell_out_variable))
                    {
                        $mos_['expand_id'] = $mode_id;
                        $mos_['mod_id']    = '2';
                        $this->obj->delete_mode_para($mos_, 'out_variable');
                        $datafid           = $this->obj->create_out_para('data', '0', $mode_id);
                        $fid               = $this->obj->create_out_para('0', $datafid, $mode_id);
                        foreach ($cell_out_variable as $key => $val)
                        {
                            if ($val['name'] != 'data' && $val['name'] != '0')
                            {
                                $this->obj->create_out_para($val['name'], $fid, $mode_id, $val['title'], $val['value']);
                            }
                        }
                    }
                }
                else
                {
                    if (is_array($mode_info))
                    {
                        unset($mode_info['id']);
                        if($mode_info['indexpic'])
				        {
				        	$indexpic = unserialize($mode_info['indexpic']);
				        	if(strstr($indexpic['host'],"img.dev.hogesoft.com")!==false)
				        	{
				        		$url = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
					        	$pic = file_get_contents($url);
								if($pic)
								{
									$dir = CUR_CONF_PATH.'data/mode/pic/';
									hg_mkdir($dir);
									file_put_contents($dir.$indexpic['filename'],$pic);
								}
								$index_pic  = array(
										'host'			=>	$this->settings['mode_image_url']."/",
										'dir'			=>	'pic/',
										'filepath'		=>	'',
										'filename'		=>	$indexpic['filename'],
								);
								$picurl =  $index_pic['host'].$index_pic['dir'].$index_pic['filepath'].$index_pic['filename'];
								$pic_info = $this->material->localMaterial($picurl);//插入图片服务器
								if($pic_info[0])
								{
									$arr = array(
										'host'			=>$pic_info[0]['host'],
										'dir'			=>$pic_info[0]['dir'],
										'filepath'		=>$pic_info[0]['filepath'],
										'filename'		=>$pic_info[0]['filename'],
									);
									$mode_info['indexpic'] = serialize($arr);
								}	
				        	}
				        }
	        			
	        			if($mode_info['effectpic'])
				        {
				        	$effectpic = unserialize($mode_info['effectpic']);
				        	if(strstr($effectpic['host'],"img.dev.hogesoft.com")!==false)
				        	{
				        		$eurl = $effectpic['host'].$effectpic['dir'].$effectpic['filepath'].$effectpic['filename'];
					        	$epic = file_get_contents($eurl);
								if($epic)
								{
									$edir = CUR_CONF_PATH.'data/mode/pic/';
									hg_mkdir($edir);
									file_put_contents($edir.$effectpic['filename'],$epic);
								}
								$effect_pic  = array(
										'host'			=>	$this->settings['mode_image_url']."/",
										'dir'			=>	'pic/',
										'filepath'		=>	'',
										'filename'		=>	$effectpic['filename'],
								);
								$epicurl =  $effect_pic['host'].$effect_pic['dir'].$effect_pic['filepath'].$effect_pic['filename'];
								$epic_info = $this->material->localMaterial($epicurl);//插入图片服务器
								if($epic_info[0])
								{
									$earr = array(
										'host'			=>$epic_info[0]['host'],
										'dir'			=>$epic_info[0]['dir'],
										'filepath'		=>$epic_info[0]['filepath'],
										'filename'		=>$epic_info[0]['filename'],
									);
									$mode_info['effectpic'] =	serialize($earr);
								}	
				        	}
				        }
				        if($mode_info['sort_name'])
				        {
				        	if($msort[$mode_info['sort_name']])
				        	{
				        		$mode_info['sort_id'] = $msort[$mode_info['sort_name']];
				        	}
				        	else
				        	{
				        		$sort_data = array();
				        		$sort_data = array(
				        			'site_id'		=>$mode_info['site_id'],
									'ip'			=>hg_getip(),
									'create_time'	=>TIMENOW,
									'fid'			=>0,
									'update_time'	=>TIMENOW,
									'name'			=>$mode_info['sort_name'],
									'user_name'		=>trim(urldecode($this->user['user_name']))
								);
				        		$mode_sort_id = $this->obj->import_mode_info($sort_data, 'cell_mode_sort');
				        		if($mode_sort_id)
				        		{
				        			$sort = array();
				        			$sort = array(
				        				'id'		=>	$mode_sort_id,
				        				'order_id'	=>	$mode_sort_id,
				        			);
				        			$this->obj->update($sort, 'cell_mode_sort');
				        		}
				        		
				        		$mode_info['sort_id'] = $mode_sort_id;
				        	}
				        	
				        }
				        unset($mode_info['sort_name']);
				        $mode_info['mode_default'] = 1;
                        $mode_id = $this->obj->import_mode_info($mode_info, 'cell_mode');
                        
                        $this->addLogs('商店安装样式' , '' , $mode_info, '商店安装样式'.$mode_info['title']);
                    }
                    if (is_array($mode_code))
                    {
                        foreach ($mode_code as $k => $v)
                        {
                            unset($v['id']);
                            $v['mode_id'] = $mode_id;
                            $code_id      = $this->obj->import_mode_info($v, 'cell_mode_code');
                        }
                    }
                    if (is_array($cell_mode_variable))
                    {
                        foreach ($cell_mode_variable as $ke => $va)
                        {
                            unset($va['id']);
                            $va['cell_mode_id'] = $mode_id;
                            $mode_variable_id   = $this->obj->import_mode_info($va, 'cell_mode_variable');
                        }
                    }
                    if (is_array($cell_out_variable))
                    {
                        $das_['expand_id'] = $mode_id;
                        $das_['mod_id']    = '2';
                        $this->obj->delete_mode_para($das_, 'out_variable');
                        $datafid           = $this->obj->create_out_para('data', '0', $mode_id);
                        $fid               = $this->obj->create_out_para('0', $datafid, $mode_id);
                        foreach ($cell_out_variable as $key => $val)
                        {
                            if ($val['name'] != 'data' && $val['name'] != '0')
                            {
                                $this->obj->create_out_para($val['name'], $fid, $mode_id, $val['title'], $val['value']);
                            }
                        }
                    }
                }
            }
        }
    }

    /*     * argument
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

$out    = new modeApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
