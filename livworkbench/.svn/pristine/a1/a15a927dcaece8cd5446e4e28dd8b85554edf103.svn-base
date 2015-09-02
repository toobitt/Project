<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: index.php 2454 2013-03-26 08:03:23Z develop_tong $
 * ************************************************************************* */
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'tempstore');
require('./global.php');
require (ROOT_PATH . 'lib/class/curl.class.php');

class tempstore extends uiBaseFrm
{

    private $appstore;
    private $product_server;

    function __construct()
    {
        parent::__construct();
        /*if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $this->ReportError('对不起，您没有权限进入模板商店');
        }*/
        //$this->appstore->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
        $this->product_server  = array(
            'host' => 'upgrade.hogesoft.com',
            'port' => 233,
            'dir' => '',
        );
        $this->appstore_server = array(
            'host' => 'appstore.hogesoft.com:233',
            'port' => 233,
            'dir' => '',
        );
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function show()
    {
        
    }
    
    function temp_do($sig= '',$typ= '')
    {
        $sign = $this->input['sign'];
        $flag = $this->input['flag'];
        $type = $this->input['type'];
        $sort_id = $this->input['sort_id'];
        
        if($this->input['id'])
        {
        	$sign = $this->input['id'];
        }
        if($sig && $typ)
        {
        	$this->input = array();
        	$sign = $sig;
        	$type = $typ;
        }
        
        $curl = new curl($this->appstore_server['host'], $this->appstore_server['dir']);
        $curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
        //$curl->setClient(67, 'Bj2IRKxQgxV6XJVbTMb5lz8WwAQYorcP');
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_file');
        $curl->addRequestData('sign', $sign);
        $curl->addRequestData('flag', $flag);
        $curl->addRequestData('type', $type);
        $file = array();
        $file   = $curl->request('template.php');
        if (empty($file) || !is_array($file))
        {
            echo "未取到信息";
            exit;
        }
        if (!$this->settings['App_publishsys'])
        {
            echo "没有模板配置";
            exit;
        }
        
        $this->curl = new curl($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('html', 1);
        $this->curl->addRequestData('sort_id', $sort_id);
        $this->array_to_add('file', $file);
        switch ($type)
        {
            case 1:
                $this->curl->addRequestData('a', 'import_datasource');
                $result = $this->curl->request('admin/data_source.php');
                break;
            case 2:
                $this->curl->addRequestData('a', 'import_modeinfo');
                $result = $this->curl->request('admin/mode.php');
                break;
            case 3:
                $this->curl->addRequestData('a', 'import_teminfo');
                $result = $this->curl->request('admin/template.php');
                if($result['type'] && $result['sign'])
                {
                	$this->temp_do($result['sign'],$result['type']);
                }
                if($result['tem'])
                {
                	$this->temp_do($result['tem'],'3');
                }
                break;
            case 4:
                $this->curl->addRequestData('a', 'import_loutinfo');
                $result = $this->curl->request('admin/layout.php');
                if($result['type'] && $result['sign'])
                {
                	$this->temp_do($result['sign'],$result['type']);
                }
                if($result['layout'])
                {
                	$this->temp_do($result['layout'],'4');
                }
                break;
        }
    }


    //数据源的安装与更新
    function ds()
    {
        $sign = $this->input['sign'];
        $flag = $this->input['flag'];
        if($this->input['id'])
        {
        	$flag = $this->input['id'];
        }
        if (!$sign && !$flag)
        {
            echo "没有需要操作的数据源";
            exit;
        }
        $curl = new curl($this->appstore_server['host'], $this->appstore_server['dir']);
        $curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_data_source_info');
        $curl->addRequestData('sign', $sign);
        $curl->addRequestData('flag', $flag);
        $ds   = $curl->request('template.php');
        if (empty($ds[0]) || !is_array($ds[0]))
        {
            echo "未取到数据源信息";
            exit;
        }
        if (!$this->settings['App_publishsys'])
        {
            echo "没有模板配置";
            exit;
        }
        $this->curl = new curl($this->settings['App_publishsys']['host'], $this->settings['App_publishsys']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'import_datasource');
        if($flag =='2')
        {
        	$curl->addRequestData('flag', $flag);
        } 
        $this->curl->addRequestData('html', 1);
        $this->array_to_add('datasource', $ds[0]);
        $result     = $this->curl->request('admin/data_source.php');
    }

    public function show_datasource_()
    {
		$host  = $this->settings['App_publishsys']['host'];
		$dir   = $this->settings['App_publishsys']['dir'] . 'admin/';
		$curl  = new curl($host, $dir);
        $curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'show');
        $curl->addRequestData('count', '100');
        $data_source_info = $curl->request('data_source.php');
        if ($data_source_info[0][0] && is_array($data_source_info[0][0]))
        {
            foreach ($data_source_info[0][0] as $k => $v)
            {
                $datas[$v['id']] = $v;
            }
        }
        $list_fields = array(
            'id' => array('title' => 'ID', 'exper' => '$v[id]'),
            'name' => array('title' => '名称', 'exper' => '$v[name]'),
        );
        $op = array(
            'pub_setting' => array(
                'name' => '更新',
                'brief' => '',
                'attr' => ' onclick="return hg_ajax_post(this, \'更新\', 1);"',
                'link' => '?a=ds'
            ),
            /*'pub_setting_all' => array(
                'name' => '更新全部',
                'brief' => '',
                'attr' => ' onclick="return hg_ajax_post(this, \'更新\', 1);"',
                'link' => '?a=ds&flag=1'
            ),*/
        );
        $batch_op    = array(
            'ds' => array(
                'name' => '更新',
                'brief' => '',
                'attr' => ' onclick="return hg_ajax_batchpost(this, \'ds\', \'更新\', 1,\'\',\'\',\'ajax\');"',
            ),
            'create' => array(
                'name' => '新增',
                'brief' => '',
            ),
        );
        $str  = 'var gBatchAction = new Array();gBatchAction[\'ds\'] = \'?a=ds\';';
        hg_add_head_element('js-c', $str);
        $this->tpl->addHeaderCode(hg_add_head_element('echo'));
        $this->tpl->addVar('list_fields', $list_fields);
        $this->tpl->addVar('op', $op);
        $this->tpl->addVar('batch_op', $batch_op);
        $this->tpl->addVar('primary_key', 'sign');
        $this->tpl->addVar('list', $datas);
        $this->tpl->outTemplate('datasource');
    }
    
    public function show_datasource()
    {
        $curl = new curl($this->appstore_server['host'], $this->appstore_server['dir']);
        $curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
        //$curl->setClient(67, 'Bj2IRKxQgxV6XJVbTMb5lz8WwAQYorcP');
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_temp');
        $curl->addRequestData('type', 1);
        $data_source_info   = $curl->request('template.php');
        if ($data_source_info && is_array($data_source_info))
        {
            foreach ($data_source_info as $k => $v)
            {
                if($v['chg_status'] =='2')
                {
                	$v['op_name'] = '更新';
                }
                elseif($v['chg_status'] =='0')
                {
                	$v['op_name'] = '安装';
                }
                else
                {
                	$v['op_name'] = '';
                }
                $v['attr'] =  ' onclick="return hg_ajax_post(this, \'更新\', 1);"';
                $v['link'] =  '?a=temp_do&type=1';
                $datas[$v['id']] = $v;
            }
        }
        $list_fields = array(
            'id' => array('title' => 'ID', 'exper' => '$v[id]'),
            'name' => array('title' => '名称', 'exper' => '$v[title]'),
        );
       	$op = 1;
        $batch_op    = array(
            'temp_do' => array(
                'name' => '更新',
                'brief' => '',
                'attr' => ' onclick="return hg_ajax_batchpost(this, \'temp_do\', \'更新\', 1,\'\',\'\',\'ajax\');"',
            ),
            /*'create' => array(
                'name' => '新增',
                'brief' => '',
            ),*/
        );
        $str  = 'var gBatchAction = new Array();gBatchAction[\'temp_do\'] = \'?a=temp_do&type=1\';';
        hg_add_head_element('js-c', $str);
        $this->tpl->addHeaderCode(hg_add_head_element('echo'));
        $this->tpl->addVar('list_fields', $list_fields);
        $this->tpl->addVar('batch_op', $batch_op);
        $this->tpl->addVar('op',$op);
        $this->tpl->addVar('primary_key', 'sign');
        $this->tpl->addVar('type', '1');
        $this->tpl->addVar('list', $datas);
        $this->tpl->outTemplate('datasource');
    }


	public function show_mode()
    {
        $curl = new curl($this->appstore_server['host'], $this->appstore_server['dir']);
        $curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
        //$curl->setClient(67, 'Bj2IRKxQgxV6XJVbTMb5lz8WwAQYorcP');
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_temp');
        $curl->addRequestData('type', 2);
        $mode_info   = $curl->request('template.php');
        
        if ($mode_info && is_array($mode_info))
        {
            foreach ($mode_info as $k => $v)
            {
                if($v['chg_status'] =='2')
                {
                	$v['op_name'] = '更新';
                }
                elseif($v['chg_status'] =='0')
                {
                	$v['op_name'] = '安装';
                }
                else
                {
                	$v['op_name'] = '';
                }
                 //$v['attr'] =  ' onclick="return hg_ajax_post(this, \'更新\', 1);"';
                $v['link'] =  '?a=temp_do&type=2';
                $datas[$v['id']] = $v;
            }
        }
        
        $list_fields = array(
            'id' => array('title' => 'ID', 'exper' => '$v[id]'),
            'name' => array('title' => '名称', 'exper' => '$v[title]'),
        );
       	$op = 1;
        $batch_op    = array(
            'temp_do' => array(
                'name' => '更新',
                'brief' => '',
                'attr' => ' onclick="return hg_ajax_batchpost(this, \'temp_do\', \'更新\', 1,\'\',\'\',\'ajax\');"',
            ),
            /*'create' => array(
                'name' => '新增',
                'brief' => '',
            ),*/
        );
        $str  = 'var gBatchAction = new Array();gBatchAction[\'temp_do\'] = \'?a=temp_do&type=2\';';
        hg_add_head_element('js-c', $str);
        $this->tpl->addHeaderCode(hg_add_head_element('echo'));
        $this->tpl->addVar('list_fields', $list_fields);
        $this->tpl->addVar('batch_op', $batch_op);
        $this->tpl->addVar('op',$op);
        $this->tpl->addVar('primary_key', 'sign');
        $this->tpl->addVar('type', '2');
        $this->tpl->addVar('list', $datas);
        $this->tpl->outTemplate('mode');
    }
    
    
    public function show_template()
    {
        $curl = new curl($this->appstore_server['host'], $this->appstore_server['dir']);
        $curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
        //$curl->setClient(67, 'Bj2IRKxQgxV6XJVbTMb5lz8WwAQYorcP');
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_temp');
        $curl->addRequestData('type', 3);
        $mode_info   = $curl->request('template.php');
        
        if ($mode_info && is_array($mode_info))
        {
            foreach ($mode_info as $k => $v)
            {
                if($v['chg_status'] =='2')
                {
                	$v['op_name'] = '更新';
                }
                elseif($v['chg_status'] =='0')
                {
                	$v['op_name'] = '安装';
                }
                else
                {
                	$v['op_name'] = '';
                }
                $v['attr'] =  ' onclick="return hg_ajax_post(this, \'更新\', 1);"';
                $v['link'] =  '?a=temp_do&type=3';
                $datas[$v['id']] = $v;
            }
        }
        
        $host  = $this->settings['App_publishsys']['host'];
		$dir   = $this->settings['App_publishsys']['dir'] . 'admin/';
		$curl  = new curl($host, $dir);
        $curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_tem_sort');
        $tem_sort = $curl->request('template.php');
        
        $list_fields = array(
            'id' => array('title' => 'ID', 'exper' => '$v[id]'),
            'name' => array('title' => '名称', 'exper' => '$v[title]'),
        );
       	$op = 1;
        $batch_op    = array(
            'temp_do' => array(
                'name' => '更新',
                'brief' => '',
                'attr' => ' onclick="return hg_ajax_batchpost(this, \'temp_do\', \'更新\', 1,\'\',\'\',\'ajax\');"',
            ),
            /*'create' => array(
                'name' => '新增',
                'brief' => '',
            ),*/
        );
        $str  = 'var gBatchAction = new Array();gBatchAction[\'temp_do\'] = \'?a=temp_do&type=3\';';
        hg_add_head_element('js-c', $str);
        $this->tpl->addHeaderCode(hg_add_head_element('echo'));
        $this->tpl->addVar('list_fields', $list_fields);
        $this->tpl->addVar('batch_op', $batch_op);
        $this->tpl->addVar('op',$op);
        $this->tpl->addVar('primary_key', 'sign');
        $this->tpl->addVar('type', '3');
        $this->tpl->addVar('list', $datas);
        $this->tpl->addVar('tem_sort', $tem_sort);
        $this->tpl->outTemplate('template');
    }
    
    
     public function show_layout()
    {
        $curl = new curl($this->appstore_server['host'], $this->appstore_server['dir']);
        $curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
        //$curl->setClient(67, 'Bj2IRKxQgxV6XJVbTMb5lz8WwAQYorcP');
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_temp');
        $curl->addRequestData('type', 4);
        $mode_info   = $curl->request('template.php');
        
        if ($mode_info && is_array($mode_info))
        {
            foreach ($mode_info as $k => $v)
            {
                if($v['chg_status'] =='2')
                {
                	$v['op_name'] = '更新';
                }
                elseif($v['chg_status'] =='0')
                {
                	$v['op_name'] = '安装';
                }
                else
                {
                	$v['op_name'] = '';
                }
                $v['attr'] =  ' onclick="return hg_ajax_post(this, \'更新\', 1);"';
                $v['link'] =  '?a=temp_do&type=4';
                $datas[$v['id']] = $v;
            }
        }
        $list_fields = array(
            'id' => array('title' => 'ID', 'exper' => '$v[id]'),
            'name' => array('title' => '名称', 'exper' => '$v[title]'),
        );
       	$op = 1;
        $batch_op    = array(
            'temp_do' => array(
                'name' => '更新',
                'brief' => '',
                'attr' => ' onclick="return hg_ajax_batchpost(this, \'temp_do\', \'更新\', 1,\'\',\'\',\'ajax\');"',
            ),
            /*'create' => array(
                'name' => '新增',
                'brief' => '',
            ),*/
        );
        $str  = 'var gBatchAction = new Array();gBatchAction[\'temp_do\'] = \'?a=temp_do&type=4\';';
        hg_add_head_element('js-c', $str);
        $this->tpl->addHeaderCode(hg_add_head_element('echo'));
        $this->tpl->addVar('list_fields', $list_fields);
        $this->tpl->addVar('batch_op', $batch_op);
        $this->tpl->addVar('op',$op);
        $this->tpl->addVar('primary_key', 'sign');
        $this->tpl->addVar('type', '4');
        $this->tpl->addVar('list', $datas);
        $this->tpl->outTemplate('layout');
    }
    
    
    function array_to_add($str, $data)
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

}

include (ROOT_PATH . 'lib/exec.php');
?>