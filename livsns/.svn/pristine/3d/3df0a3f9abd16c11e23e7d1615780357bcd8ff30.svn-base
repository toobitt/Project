<?php

require('global.php');
define('MOD_UNIQUEID', 'deploy_tem'); //模块标识

class deploy_temApi extends adminBase
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/common.php');
        include(CUR_CONF_PATH . 'lib/deploy.class.php');
        $this->obj        = new deploy();
        include(CUR_CONF_PATH . 'lib/template_classify.class.php');
        $this->temsort     = new templateClassify();
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->pub_config  = new publishconfig();
        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
        $this->pub_content = new publishcontent();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function show()
    {
        $offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count  = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
        $limit  = " limit {$offset}, {$count}";
        $sql    = "SELECT id,sign,title,template_style,file_name,client,pic 
				FROM  " . DB_PREFIX . "templates 
				WHERE 1" . $this->get_condition() . ' ORDER BY create_time DESC ' . $limit;
        $q      = $this->db->query($sql);
        while ($row    = $this->db->fetch_array($q))
        {
            $row['pic'] = $row['pic'] ? unserialize($row['pic']) : array();
            $ret[]      = $row;
        }

        //取出模板分类
        $sortlimit = " limit 0, 200";
        $sort_data = $this->temsort->show($sortlimit, '');

        $result['sort_data'] = $sort_data;
        $result['tem_data']  = $ret;

        $this->addItem($result);
        $this->output();
    }
    
    function get_deploy_tem()
    {
        $site_id = $this->input['site_id'];
        if(!$site_id)
        {
            $this->errorOutput('NO_SITE_ID');
        }
        $content_type = $this->pub_content->get_all_content_type();
        
        $ret['site'] = common::get_deploy_templates($site_id, 0, 0);
        
        $sql = "select * from ".DB_PREFIX."deploy_template where site_id=".$site_id." order by group_id DESC,content_type,id";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $use_content_type[$row['content_type']] = $row['content_type'];
            $page_title[$row['group_id']][] = $row['title'];
            if($row['content_type']>0)
            {
                $page[$row['group_id']][$row['content_type']] = array('content_type'=>$content_type[$row['content_type']],'dep'=>$row);
            }
            else
            {
                $page[$row['group_id']][$row['content_type']] = array('content_type'=>array('content_type'=>$row['content_type']===0?'首页':'列表页'),'dep'=>$row);
            }
        }
        $ret['page_title'] = $page_title;
        $ret['page'] = $page;
        $this->addItem($ret);
        $this->output();
    }

    public function count()
    {
        $sql                      = 'SELECT count(*) as total from ' . DB_PREFIX . 'templates WHERE 1 ' . $this->get_condition();
        ;
        $templates_classify_total = $this->db->query_first($sql);
        echo json_encode($templates_classify_total);
    }

    function get_condition()
    {
        $site_id     = isset($this->input['site_id']) ? intval($this->input['site_id']) : 1;
        $client_type = isset($this->input['client_type']) ? intval($this->input['client_type']) : 2;
        if ($site_id)
        {
            $condition .=" AND site_id=" . $site_id;
            //取出当前的套系
            $site = $this->pub_config->get_site_first('id,site_name,tem_style', $site_id);
            if (!$site['tem_style'])
            {
                $this->errorOutput('NO_TEM_STYLE');
            }
            $condition .= " AND template_style='" . $site['tem_style'] . "'";
        }
        if ($sort_id = $this->input['sort_id'])
        {
            $condition .=" AND sort_id in (" . $sort_id.")";
        }
        if ($client_type)
        {
            $condition .=" AND client = " . $client_type;
        }
        if ($this->input['k'])
        {
            $condition .= " AND file_name like '%" . urldecode($this->input['k']) . "%' ";
        }
        //时间段
        if (false)
        {
            
        }
        return $condition;
    }

    function get_tem_sort()
    {
        //取出模板分类
        $site_id     = isset($this->input['site_id']) ? intval($this->input['site_id']) : 1;
        $sortlimit = " limit 0, 200";
        $sql    = "SELECT * FROM  " . DB_PREFIX . "template_sort WHERE site_id=".$site_id." ORDER BY id " . $sortlimit;
        $q      = $this->db->query($sql);
        while($row = $this->db->fetch_array($q))
        {
            $this->addItem($row);
        }
        $this->output();
    }
    
    function get_tem()
    {
        $sort_id = intval($this->input['sort_id']);
        $k = $this->input['k'];
        if(!$sort_id && !$k)
        {
            $this->errorOutput('NO_SORT_ID');
        }
        if($sort_id)
        {
            $sql  = "SELECT * FROM  " . DB_PREFIX . "template_sort WHERE id=" . $sort_id;
            $sort = $this->db->query_first($sql);
            if (!$sort)
            {
                $this->errorOutput('NO_SORT');
            }
            $this->input['sort_id'] = $sort['parents'];
        }
        
        $offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count  = $this->input['count'] ? intval(urldecode($this->input['count'])) : 1000;
        $limit  = " limit {$offset}, {$count}";
        $sql    = "SELECT id,sign,title,template_style,file_name,client,pic 
				FROM  " . DB_PREFIX . "templates 
				WHERE 1" . $this->get_condition() . ' ORDER BY create_time DESC ' . $limit;
        $q      = $this->db->query($sql);
        while ($row    = $this->db->fetch_array($q))
        {
            $row['pic'] = $row['pic'] ? unserialize($row['pic']) : array();
            $this->addItem($row);
        }
        $this->output();
    }
    
    function update_tem()
    {
    	/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('deploy_tem',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/

        /*模板 权限验证预处理 start*/
        $need_auth = 0;
        //$auth_page_self存储授权页面本身、$auth_page_parents存储授权栏目父级页面
        $auth_site = $auth_site_self = $auth_page = $auth_column = $auth_page_self = $auth_page_parents = array();
        if ( $this->user['group_type'] > MAX_ADMIN_TYPE )
        {
            $need_auth = 1;
            $auth_node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            if ( (is_array($auth_node) ? implode(',',$auth_node) : $auth_node) == 1)
            {
                $need_auth = 0;  //1表示全选  不需要验证权限
            }
            $auth_node = is_array($auth_node) ? $auth_node : explode(',', $auth_node);
            if ($need_auth)
            {
                foreach ((array) $auth_node as $k => $v)
                {
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
		
        $data = json_decode(html_entity_decode($this->input['data']),true);
        if($data['title'] && is_array($data['title']))
        {
            include_once(CUR_CONF_PATH . "lib/rebuild_deploy.class.php");
            $rebuild_deploy = new rebuilddeploy();
            $group_id = intval($this->input['group_id']);
            if (!$group_id)
            {
                $sql                     = "select group_id from " . DB_PREFIX . "deploy_template order by group_id DESC limit 1";
                $groupdetail             = $this->db->query_first($sql);
                $group_id                = intval($groupdetail['group_id']) + 1;
            }
            else
            {
                $sql = "delete from ".DB_PREFIX."deploy_template where group_id={$group_id}";
                $this->db->query($sql);
            }
            foreach($data['title'] as $k=>$v)
            {
                $fid          = $v['id'];
                $page_id      = 0;
                $page_data_id = 0;
                if (strstr($fid, "site") !== false)
                {
                    $site_id = str_replace('site', '', $fid);
                }
                else if (strstr($fid, "page_id") !== false)
                {
                    $page_id     = intval(str_replace('page_id', '', $fid));
                    $page_detail = common::get_page_by_id($page_id);
                    $site_id     = $page_detail['site_id'];
                }
                else if (strstr($fid, "page_data_id") !== false)
                {
                    $page_data_id = str_replace('page_data_id', '', $fid);
                    $get_page     = explode($this->settings['separator'], $page_data_id);
                    $page_id      = $get_page[0];
                    $page_data_id = $get_page[1];
                    $page_detail  = common::get_page_by_id($page_id);
                    $site_id      = $page_detail['site_id'];
                }
                else
                {
                    continue;
                }
                $sql = "delete from ".DB_PREFIX."deploy_template where site_id={$site_id} and page_id={$page_id} and page_data_id={$page_data_id}";
                $this->db->query($sql);
                if ($data['page'] && is_array($data['page']))
                {
                    foreach ($data['page'] as $kk => $vv)
                    {
                        $this->update($site_id,$page_id,$page_data_id,$vv['content_type'],$vv['sign'],$v['title'],$v['full_title'],$group_id);
                    }
                }
                $rebuild_deploy->rebuild_deploy($site_id, $page_id, $page_data_id);
            }
        }
        
        $content_type = $this->pub_content->get_all_content_type();

        $ret['site'] = common::get_deploy_templates($site_id, 0, 0);
        if ( $need_auth )
        {
            if (!in_array($site_id, $auth_site_self))
            {
                $ret['no_auth_site'] = 1;   //是否已经授权站点
            }
        }

        $page_info = array();
        $sql  = "select * from " . DB_PREFIX . "deploy_template where site_id=" . $site_id . " and page_id!=0 order by group_id DESC,content_type,id";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            if ($need_auth)
            {
                //只显示授权节点和授权节点孩子节点

                if ($row['site_id'] && !$row['page_id'] && !$row['page_data_id'])
                {
                    //授权节点本身 显示
                    if ( !in_array($row['site_id'], $auth_site_self) )
                    {
                        continue;
                    }
                }
                if ($row['site_id'] && $row['page_id'] && !$row['page_data_id'])
                {
                    //授权节点本身或者孩子节点 显示
                    if (!in_array($row['page_id'], $auth_page_self) && !in_array($row['site_id'], $auth_site_self))
                    {
                        continue;
                    }
                }

                if ($row['site_id'] && $row['page_id'] && $row['page_data_id'])
                {
                    $cur_page_auth_column = isset($auth_column[$row['page_id']]) ? $auth_column[$row['page_id']] : array();
                    //授权节点本身或者孩子节点 显示
                    if ( !in_array($row['page_data_id'], $cur_page_auth_column)  && !in_array($row['page_id'], $auth_page_self) && !in_array($row['site_id'], $auth_site_self) )
                    {
                        $page_data = common::get_page_data($row['page_id'], 0, 1, 0, $page_info[$row['page_id']], $row['page_data_id']);
                        $page_info[$row['page_id']] = $page_data['page_info'];   //记录page信息 下次调用common::get_page_data方法时使用
                        foreach ((array)$page_data['page_data'] as $k => $v)
                        {
                            $auth_column_parents[$v['id']] = $v['parents'];
                        }
                        //栏目孩子节点显示
                        if(!array_intersect(explode(',', $auth_column_parents[$row['page_data_id']]), $cur_page_auth_column))
                        {
                            continue;
                        }
                    }
                }
            }

            $use_content_type[$row['content_type']] = $row['content_type'];
            if($row['site_id']&&!$row['page_id']&&!$row['page_data_id'])
            {
                continue;
                $page_title_id = 'site'.$row['site_id'];
            }
            else if($row['page_id']&&!$row['page_data_id'])
            {
                $page_title_id = 'page_id'.$row['page_id'];
            }
            else
            {
                $page_title_id = 'page_data_id'.$row['page_id'].'_'.$row['page_data_id'];
            }
            
            if(!isset($page_title_c[$row['group_id']][$row['site_id']][$row['page_id']][$row['page_data_id']]))
            {
                $page_title[$row['group_id']][]         = array('id' => $page_title_id, 'title' => $row['title'], 'full_title'=>$row['full_title']);
                $page_title_c[$row['group_id']][$row['site_id']][$row['page_id']][$row['page_data_id']]=$row['id'];
            }
            if ($row['content_type'] > 0)
            {
                $page[$row['group_id']][$row['content_type']] = array('content_type' => $content_type[$row['content_type']], 'dep' => $row);
            }
            else
            {
                $page[$row['group_id']][$row['content_type']] = array('content_type' => array('content_type' => !$row['content_type'] ? '首页' : '列表页'), 'dep' => $row);
            }
        }
        $ret['content_type'] = $content_type;
        $ret['page_title']   = $page_title;
        $ret['page']         = $page;
        $this->addItem($ret);
        $this->output();
    }
    
    function update($site_id=0,$page_id=0,$page_data_id=0,$content_type=0,$template_sign='',$title='',$full_title='',$group_id)
    {
        $client_type = $this->input['client_type']?$this->input['client_type']:2;
        $same_level_tem = intval($this->input['same_level_tem']);
        
        if (!$site_id)
        {
            $this->errorOutput('缺少对应信息');
        }

        $site = $this->pub_config->get_site_first(' * ', $site_id);
        if (!$site['tem_style'])
        {
            return ;
        }
        
        //查询模板信息
        $sql = "SELECT * FROM  " . DB_PREFIX . "templates WHERE sign='" . $template_sign."' and template_style='".$site['tem_style']."'";
        $t   = $this->db->query_first($sql);
        if (!$t)
        {
            return ;
        }
        $template_sign = ($t['sign']);
        $template_name = ($t['title']);
        $dep_tem       = $this->obj->get_deploy_template($site_id, $site['tem_style'], $page_id, $page_data_id);

        $data['site_id']       = $site_id;
        $data['page_id']       = $page_id;
        $data['page_data_id']  = $page_data_id;
        $data['client_id']     = $client_type;
        $data['content_type']  = $content_type;
        $data['template_sign'] = $template_sign;
        $data['template_name'] = $template_name;
        $data['title']         = $title;
        $data['full_title']         = $full_title;

        if(!$group_id)
        {
            $sql = "select group_id from ".DB_PREFIX."deploy_template order by group_id DESC limit 1";
            $groupdetail = $this->db->query_first($sql);
            $group_id = intval($groupdetail['group_id']);
            $group_id = $this->input['group_id'] = $group_id+1;
        }
        if (empty($dep_tem[$site_id][$page_id][$page_data_id][$client_type][$content_type]))
        {
            $data['group_id'] = $group_id;
            $this->obj->insert_col_tem($data);
        }
        else
        {
            $update_data = array('group_id'=>$group_id, 'template_sign' => $data['template_sign'], 'title' => $title,'full_title' => $full_title, 'template_name' => $data['template_name']);
            $dep_tem_id  = $dep_tem[$site_id][$page_id][$page_data_id][$client_type][$content_type]['id'];
            $this->obj->update('deploy_template', $dep_tem_id, $update_data);
        }

        if ($same_level_tem)
        {
            $page_data = common::get_page_data($page_id, 0, 10, '', array(), $page_data_id);
            if (!$page_data[$page_data_id])
            {
                exit;
            }
            $page_data_detail = $page_data[$page_data_id];
            $lve_page_data    = common::get_page_data($page_id, 0, 500, $page_data_detail['fid'], array(), '');

            if (!$lve_page_data || !is_array($lve_page_data))
            {
                exit;
            }
            foreach ($lve_page_data as $k => $v)
            {
                if ($k == $page_data_id)
                {
                    continue;
                }
                $dep_tem               = $this->obj->get_deploy_template($site_id, $site['tem_style'], $page_id, $page_data_id);
                $data['site_id']       = $site_id;
                $data['page_id']       = $page_id;
                $data['page_data_id']  = $page_data_id;
                $data['client_type']   = $client_type;
                $data['content_type']  = $content_type;
                $data['template_sign'] = $template_sign;
                $data['template_name'] = $template_name;
                $data['title']         = $title;
                if (empty($dep_tem[$site_id][$page_id][$page_data_id][$client_type][$content_type]))
                {
                    $this->obj->insert_col_tem($data);
                }
                else
                {
                    $update_data = array('template_sign' => $data['template_sign'], 'title' => $title);
                    $dep_tem_id  = $dep_tem[$site_id][$page_id][$page_data_id][$client_type][$content_type]['id'];
                    $this->obj->update('deploy_template', $dep_tem_id, $update_data);
                }
            }
        }

        
    }

}

$out    = new deploy_temApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>