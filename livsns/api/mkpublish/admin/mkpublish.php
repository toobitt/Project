<?php

/* * *****************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :mkpublish_1.php
 * package  :package_name
 * Created  :2013-6-19,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 * **************************************************************** */
require('global.php');
require_once 'core.class.php';
define('MOD_UNIQUEID', 'mkpublish'); //模块标识

class mkpublish extends adminReadBase
{

    public $contribute = null;

    function __construct()
    {
        $this->mPrmsMethods = array(
            'manage' => '管理',
        );
        parent::__construct();
        include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
        $this->pub_sys = new publishsys();
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->pub_config = new publishconfig();
    }

    public function __destruct()
    {
        
    }

    public function detail()
    {
        
    }

    public function index()
    {
        
    }
    
    public function show()
    {
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 5000;
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $this->user['prms']['app_prms'][APP_UNIQUEID]['action'] = is_array($this->user['prms']['app_prms'][APP_UNIQUEID]['action'])?$this->user['prms']['app_prms'][APP_UNIQUEID]['action']:array();
            if(!in_array('manage',$this->user['prms']['app_prms'][APP_UNIQUEID]['action']))
            {
                $this->errorOutput("NO_PRIVILEGE");
            }
        }
        $siteid = intval($this->input['site_id']);
        if ((!$fid    = $this->input['fid']) && !$this->input['get_site']) //get_site有值时取站点信息
        {
            if ($siteid)
            {
                $fid = 'site' . $siteid;
            }
            else
            {
                $fid = 'site1';
            }
        }

        /*模板 权限验证预处理 start*/
        $need_auth = 0;
        //$auth_page_self存储授权页面本身、$auth_page_parents存储授权栏目父级页面
        $auth_site = $auth_site_self = $auth_page = $auth_column = $auth_page_self = $auth_page_parents = array();
        if ( $this->user['group_type'] > MAX_ADMIN_TYPE )
        {
            $need_auth = 1;
            $publishsys_auth_node = $this->user['prms']['app_prms']['publishsys']['nodes'];
            $mkpublishsys_auth_node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            $publishsys_auth_node = is_array($publishsys_auth_node) ? implode(',',$publishsys_auth_node) : $publishsys_auth_node;
            $mkpublishsys_auth_node = is_array($mkpublishsys_auth_node) ? implode(',',$mkpublishsys_auth_node) : $mkpublishsys_auth_node;
            if ($publishsys_auth_node == 1 || $mkpublishsys_auth_node == 1)
            {
                $need_auth = 0;    //1表示全选  不需要验证权限
            }
//            $auth_node = $this->user['prms']['app_prms']['publishsys']['nodes'];
//            if ( (is_array($auth_node) ? implode(',',$auth_node) : $auth_node) == 1)
//            {
//                $need_auth = 0;  //1表示全选  不需要验证权限
//            }
//            $auth_node = is_array($auth_node) ? $auth_node : explode(',', $auth_node);

            if ($need_auth)
            {
                $publishsys_auth_node = is_array($publishsys_auth_node) ? $publishsys_auth_node : explode(',', $publishsys_auth_node);
                $mkpublishsys_auth_node = is_array($mkpublishsys_auth_node) ? $mkpublishsys_auth_node : explode(',', $mkpublishsys_auth_node);
                $auth_node = array_merge($publishsys_auth_node, $mkpublishsys_auth_node);
                foreach ((array) $auth_node as $k => $v)
                {
                    if (!$v)
                    {
                        continue;
                    }
                    switch ($v)
                    {
                        case strstr($v, "site") !== false :
                            $v = str_replace("site", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $auth_site_self[] = $v[0];
                            break;
                        case strstr($v, "page_id") !== false :
                            $v = str_replace("page_id", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $v[0];
                            $auth_page[] = $auth_page_self[] = $v[1];
                            break;
                        case strstr($v, "page_data_id") !== false:
                            $v = str_replace("page_data_id", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $v[0];
                            $auth_page[] = $auth_page_parents[] = $v[1];
                            $auth_column[$v[1]][] = $v[2];
                            break;
                        default:
                            break;
                    }
                }
            }

        }
        /*模板 权限验证预处理 end*/

        if (empty($fid))
        {
//            if ($need_auth)
//            {
//                $auth_site_str = implode(',', array_unique($auth_site));
//            }
//            $sites = $this->pub_config->get_site('id,site_name', '', '', $auth_site_str);
            if ($need_auth)
            {
                $auth_site_str = implode(',', array_unique($auth_site));
                if ($auth_site_str)
                {
                    $sites = $this->pub_config->get_site('id, site_name', '', '', $auth_site_str);
                }
                else
                {
                    $sites = array();
                }
            }
            else
            {
                $sites = $this->pub_config->get_site('id, site_name');
            }
            foreach ($sites as $k => $v)
            {
                $m         = array('id' => 'site' . $v['id'] . $this->settings['separator'] . $v['site_name'], "name" => $v['site_name'], "fid" => 0, "depth" => 1);
                if ($this->input['need_normal_id'])
                {
                    $m['id'] = $v['id'];
                }
                $this->addItem($m);
            }
        }
        else
        {
            if (strstr($fid, "site") !== false)
            {
                //点击的站点
                $site_id   = str_replace('site', '', $fid);
                $get_page  = explode($this->settings['separator'], $site_id);
                $page_type = $this->pub_sys->get_page_manage($get_page[0]);
                foreach ($page_type as $k => $v)
                {
                    if ($need_auth)
                    {
                        $v['is_auth'] = 0;
                        $v['is_auth'] = $need_auth ? 0 : 1;
                        //授权页面本身 显示
                        if (in_array($v['id'], $auth_page_self))
                        {
                            $v['is_auth'] = 1;
                        }
                        if (!$v['is_auth'])
                        {
                            //授权栏目父级页面 显示
                            if (in_array($v['id'], array_diff($auth_page_parents, $auth_page_self)))
                            {
                                $v['is_auth'] = 2;
                            }

                            //授权站点子集页面 显示
                            if (in_array($v['site_id'], $auth_site_self))
                            {
                                $v['is_auth'] = 3;
                            }
                        }
                    }
                    if ($v['is_auth'] || !$need_auth)
                    {
                        $m         = array('id' => 'page_id' . $v['id'] . $this->settings['separator'] . $v['title'], "name" => $v['title'], "fid" => 'page_id' . $v['id'], "depth" => 1);
                        $page_data = $this->pub_sys->get_page_data($v['id'], 0, 1);
                        if (empty($page_data['page_data']))
                        {
                            $m['is_last'] = 1;
                        }
                        else
                        {
                            $m['is_last'] = 0;
                        }
                        if($v['sign']!='column')
                        {
                            $m['can_select'] = 1;
                        }
                        $this->addItem($m);
                    }
                }
            }
            else if ( ( ( $has_page_id = strstr($fid, "page_id") ) !== false) || ( ($has_page_data_id = strstr($fid, "page_data_id") ) !== false) )
            {
                //点击的页面类型
                $id = str_replace(array('page_id', 'page_data_id'), array('', ''), $fid);
                $get_page = explode($this->settings['separator'], $id);
                $page_data = $this->pub_sys->get_page_data($get_page[0], $offset, $count, $get_page[1]);
                if ($auth_column)
                {
                    //过滤部署与本页面的栏目ID
                    $auth_column = isset($auth_column[$get_page[0]]) ? $auth_column[$get_page[0]] : array() ;
                }
                foreach ((array)$page_data['page_data'] as $k => $v)
                {
                    if (in_array($v['id'], $auth_column))
                    {
                        $auth_column_parents[$v['id']] = explode(',', $v['parents']);
                    }
                }
                foreach ((array)$page_data['page_data'] as $k => $v)
                {
                    $v['is_auth'] = 0;
                    if ($need_auth)
                    {
                        $v['is_auth'] = 0;
                        $v['is_auth'] = $need_auth ? 0 : 1;
                        //授权节点自身
                        if (in_array($v['id'], $auth_column))
                        {
                            $v['is_auth'] = 1;
                        }

                        if (!$v['is_auth'])
                        {
                            //父级节点  显示
                            foreach ((array)$auth_column_parents as $column_id => $column_parent)
                            {
                                if (in_array($v['id'], $column_parent))
                                {
                                    $v['is_auth'] = 2;
                                    break;
                                }
                            }
                            //栏目孩子节点显示
                            if(array_intersect(explode(',', $v['parents']), $auth_column))
                            {
                                $v['is_auth'] = 3;
                            }

                            //页面孩子节点显示
                            if (!$v['is_auth'] && in_array($get_page[0], $auth_page_self))
                            {
                                $v['is_auth'] = 3;
                            }

                            //站点孩子节点 显示
                            if (!$v['is_auth'] && in_array($v['site_id'], $auth_site_self))
                            {
                                $v['is_auth'] = 3;
                            }
                        }
                    }
                    if ($v['is_auth'] || !$need_auth)
                    {
                        $id_field = $page_data['page_info']['field'] ? $page_data['page_info']['field'] : 'id';
                        $name_field = $page_data['page_info']['name_field'] ? $page_data['page_info']['name_field'] : 'name';
                        $m_id         = 'page_data_id' . $page_data['page_info']['id'] . $this->settings['separator'] . $v[$id_field] . $this->settings['separator'] . $v[$name_field];
                        $m            = array('id' => $m_id, "name" => $v[$name_field], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1);
                        $m['is_last'] = $v['is_last'];
                        if ($has_page_id)
                        {
                            if(is_array($page_data['page_info']))
                            {
                                if ($page_data['page_info']['sign'] != 'column')
                                {
                                    $m['can_select'] = 1;
                                }
                            }
                        }
                        else if ($has_page_data_id)
                        {
                            $m['can_select'] = $v['can_select'];
                        }
                        $this->addItem($m);
                    }
                }
            }
//            else if (strstr($fid, "page_data_id") !== false)
//            {
//                //点击的页面数据
//                $page_data_id = str_replace('page_data_id', '', $fid);
//                $get_page     = explode($this->settings['separator'], $page_data_id);
//                $page_data    = $this->pub_sys->get_page_data($get_page[0], $offset, $count, $get_page[1]);
//                foreach ($page_data['page_data'] as $k => $v)
//                {
//                    $m_id         = 'page_data_id' . $page_data['page_info']['id'] . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['name'];
//                    $m            = array('id' => $m_id, "name" => $v['name'], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1,"can_select"=>$v['can_select']);
//                    $m['is_last'] = $v['is_last'];
//                    $this->addItem($m);
//                }
//            }
        }
        $this->output();
    }

    public function count()
    {
        include_once(ROOT_PATH . 'lib/class/curl.class.php');
        //$this->create_curl_obj('publishsys');

//		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."block WHERE 1 ".$this->get_condition();
//		echo json_encode($this->db->query_first($sql));
    }

    private function get_condition()
    {
        $condition = '';
        return $condition;
    }

//	public function mkpublish_form()
//	{
//		if(isset($this->input['site_id']))
//			$params['site_id'] = intval($this->input['site_id']);
//		
//		if(isset($this->input['page_id']))
//			$params['page_id'] = intval($this->input['page_id']);
//			
//		if(isset($this->input['page_data_id']))
//			$params['page_data_id'] = intval($this->input['page_data_id']);
//		
//		if(isset($this->input['deploy_name']))
//			$params['deploy_name'] = $this->input['deploy_name'];
//		
//		$params['a'] = 'mkpulbish_form';
//		$parmas['r'] = 'admin/mkpublish';
//		
//		$result = $this->get_common_datas($params);
//		
//		$this->addItem($result);
//		$this->output();
//	}


    public function mkpublish_form()
    {
        $fid = ($this->input['fid']) ? ($this->input['fid']) : 'site1';
        if (empty($fid))
        {
            $sites = $this->pub_config->get_site(' id,site_name ');
            ;
            foreach ($sites as $k => $v)
            {
                $m         = array('id' => 'site' . $v['id'] . $this->settings['separator'] . $v['site_name'], "name" => $v['site_name'], "fid" => 0, "depth" => 1);
                //获取页面类型
                $page_type = common::get_page_manage($v['id']);
                $page_type = $this->pub_sys->get_page_manage($v['id']);
                if (empty($page_type))
                {
                    $m['is_last'] = 1;
                }
                else
                {
                    $m['is_last'] = 0;
                }
                $this->addItem($m);
            }
        }
        else
        {
            if (strstr($fid, "site") !== false)
            {
                //点击的站点
                $site_id   = str_replace('site', '', $fid);
                $get_page  = explode($this->settings['separator'], $site_id);
                $page_type = $this->pub_sys->get_page_manage($get_page[0]);
                foreach ($page_type as $k => $v)
                {
                    $m         = array('id' => 'page_id' . $v['id'] . $this->settings['separator'] . $v['title'], "name" => $v['title'], "fid" => 'page_id' . $v['id'], "depth" => 1);
                    $page_data = $this->pub_sys->get_page_data($v['id'], 0, 1);
                    if (empty($page_data['page_data']))
                    {
                        $m['is_last'] = 1;
                    }
                    else
                    {
                        $m['is_last'] = 0;
                    }
                    if($v['sign']!='column')
                    {
                        $m['can_select'] = 1;
                    }
                    $this->addItem($m);
                }
            }
            else if (strstr($fid, "page_id") !== false)
            {
                //点击的页面类型
                $page_id   = str_replace('page_id', '', $fid);
                $get_page  = explode($this->settings['separator'], $page_id);
                $page_data = $this->pub_sys->get_page_data($get_page[0], 0, 100);
                foreach ($page_data['page_data'] as $k => $v)
                {
                    $m_id         = 'page_data_id' . $page_data['page_info']['id'] . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['name'];
                    $m            = array('id' => $m_id, "name" => $v['name'], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1,'can_select'=>$v['can_select']);
                    $m['is_last'] = $v['is_last'];
                    if($page_id!=8)
                    {
                        $m['can_select'] = 1;
                    }
                    $this->addItem($m);
                }
            }
            else if (strstr($fid, "page_data_id") !== false)
            {
                //点击的页面数据
                $page_data_id = str_replace('page_data_id', '', $fid);
                $get_page     = explode($this->settings['separator'], $page_data_id);
                $page_data    = $this->pub_sys->get_page_data($get_page[0], '', '', $get_page[1]);
                foreach ($page_data['page_data'] as $k => $v)
                {
                    $m_id         = 'page_data_id' . $page_data['page_info']['id'] . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['name'];
                    $m            = array('id' => $m_id, "name" => $v['name'], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1,'can_select'=>$v['can_select']);
                    $m['is_last'] = $v['is_last'];
                    $this->addItem($m);
                }
            }
        }
        $this->output();
    }

    public function open_url()
    {

        if (isset($this->input['site_id']))
            $params['site_id'] = intval($this->input['site_id']);

        if (isset($this->input['page_id']))
            $params['page_id'] = intval($this->input['page_id']);

        if (isset($this->input['page_data_id']))
            $params['page_data_id'] = intval($this->input['page_data_id']);

        if (isset($this->input['content_type']))
            $params['content_type'] = intval($this->input['content_type']);

        $params['a'] = 'open_url';
        $params['r'] = 'admin/mkpublish';

        $re = $this->get_common_datas($params);
        $this->addItem($re);
        $this->output();
    }

    public function get_node()
    {
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 5000;
        $siteid = intval($this->input['siteid']);
        if (!$fid    = $this->input['fid'])
        {
            if ($siteid)
            {
                $fid = 'site' . $siteid;
            }
        }
        if (empty($fid))
        {
            $fid = 'site1';
        }
        if (empty($fid))
        {
            $sites = $this->pub_config->get_site(' id,site_name ');
            ;
            foreach ($sites as $k => $v)
            {
                $m         = array('id' => 'site' . $v['id'] . $this->settings['separator'] . $v['site_name'], "name" => $v['site_name'], "fid" => 0, "depth" => 1);
                //获取页面类型
                $page_type = common::get_page_manage($v['id']);
                $page_type = $this->pub_sys->get_page_manage($v['id']);
                if (empty($page_type))
                {
                    $m['is_last'] = 1;
                }
                else
                {
                    $m['is_last'] = 0;
                }
                $this->addItem($m);
            }
        }
        else
        {
            if (strstr($fid, "site") !== false)
            {
                //点击的站点
                $site_id   = str_replace('site', '', $fid);
                $get_page  = explode($this->settings['separator'], $site_id);
                $page_type = $this->pub_sys->get_page_manage($get_page[0]);
                foreach ($page_type as $k => $v)
                {
                    $m         = array('id' => 'page_id' . $v['id'] . $this->settings['separator'] . $v['title'], "name" => $v['title'], "fid" => 'page_id' . $v['id'], "depth" => 1);
                    $page_data = $this->pub_sys->get_page_data($v['id'], 0, 1);
                    if (empty($page_data['page_data']))
                    {
                        $m['is_last'] = 1;
                    }
                    else
                    {
                        $m['is_last'] = 0;
                    }
                    if($v['sign']!='column')
                    {
                        $m['can_select'] = 1;
                    }
                    $this->addItem($m);
                }
            }
            else if (strstr($fid, "page_id") !== false)
            {
                //点击的页面类型
                $page_id   = str_replace('page_id', '', $fid);
                $get_page  = explode($this->settings['separator'], $page_id);
                $page_data = $this->pub_sys->get_page_data($get_page[0], $offset, $count);
                foreach ($page_data['page_data'] as $k => $v)
                {
                    $m_id         = 'page_data_id' . $page_data['page_info']['id'] . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['name'];
                    $m            = array('id' => $m_id, "name" => $v['name'], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1,"can_select"=>$v['can_select']);
                    $m['is_last'] = $v['is_last'];
                    if(is_array($page_data['page_info']))
                    {
                        if ($page_data['page_info']['sign'] != 'column')
                        {
                            $m['can_select'] = 1;
                        }
                    }
                    $this->addItem($m);
                }
            }
            else if (strstr($fid, "page_data_id") !== false)
            {
                //点击的页面数据
                $page_data_id = str_replace('page_data_id', '', $fid);
                $get_page     = explode($this->settings['separator'], $page_data_id);
                $page_data    = $this->pub_sys->get_page_data($get_page[0], $offset, $count, $get_page[1]);
                foreach ($page_data['page_data'] as $k => $v)
                {
                    $m_id         = 'page_data_id' . $page_data['page_info']['id'] . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['name'];
                    $m            = array('id' => $m_id, "name" => $v['name'], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1,"can_select"=>$v['can_select']);
                    $m['is_last'] = $v['is_last'];
                    $this->addItem($m);
                }
            }
        }
        $this->output();
    }
    
    public function rename_folder()
    {
        $oldfolder = $this->input['oldfolder'];
        $newfolder = $this->input['newfolder'];
        if(!$oldfolder || !$newfolder)
        {
            $this->errorOutput('NO_FOLDER');
        }
        if(!file_exists($oldfolder))
        {
            $this->errorOutput('NO_FOLDER');
        }
        rename($oldfolder,$newfolder);
    }
    
    public function file_in()
    {
        $filepath = $this->input['filepath'];
        $filename = $this->input['filename'];
        $content = $this->input['content'];
        if (!$filepath && !$filename) 
        {
            $this->errorOutput('NO PARAM');
        }
        if (!hg_mkdir($filepath))
        {
            $this->errorOutput('目录创建失败');
        }
        if (!hg_file_write($filepath . $filename, $content)) {
            $this->errorOutput('文件写入失败');
        }
        $ret = array(
            'msg' => 'sucess',
        );
        $this->addItem($ret);
        $this->output();
    }
    
}

$out    = new mkpublish();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
