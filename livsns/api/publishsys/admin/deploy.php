<?php

require('global.php');
define('MOD_UNIQUEID', 'deploy'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');

class deployApi extends adminBase
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
        include(CUR_CONF_PATH . "lib/common.php");
        include(CUR_CONF_PATH . 'lib/deploy.class.php');
        $this->obj         = new deploy();
        $this->pub_config  = new publishconfig();
        $this->pub_content = new publishcontent();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
//    	if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('deploy_tem',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}

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
		
        $site_id = $this->input['site_id']?$this->input['site_id']:1;
        if (!$site_id)
        {
            $this->errorOutput('NO_SITE_ID');
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

    public function show1()
    {
        $fid = ($this->input['_id']) ? ($this->input['_id']) : 'page_id8';
        if (strstr($fid, "site") !== false)
        {
            $site_id = str_replace('site', '', $fid);
        }
        else if (strstr($fid, "page_id") !== false)
        {
            $page_id         = intval(str_replace('page_id', '', $fid));
            $page_detail     = common::get_page_by_id($page_id);
            $site_id         = $page_detail['site_id'];
            $add_site_deploy = true;
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
            $this->errorOutput('NO_ID');
        }

//		$id = $this->input['id'];
//		if(!$id)
//		{
//			$this->errorOutput('NO_ID');
//		}
        //siteid1_pageid0_pagedataid0
//		$idarr = explode('_',$id);
//		if(!$idarr[0] || !$idarr[1] || !$idarr[2])
//		{
//			$this->errorOutput('NO_ID');
//		}
//		
//		$site_id	  = intval(str_replace('siteid','',$idarr[0]));
//		$page_id	  = intval(str_replace('pageid','',$idarr[1]));
//		$page_data_id	  = intval(str_replace('pagedataid','',$idarr[2]));
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count  = 1000;

        if (!$page_id)
        {
            $this->errorOutput('NO_PAGE_DATA_ID');
        }
        //查询出子级
        $data = common::get_page_data($page_id, $offset, $count, $page_data_id);
        foreach ($data['page_data'] as $k => $v)
        {
            $data['page_data'][$k]['id']     = 'siteid' . $site_id . '_pageid' . $page_id . '_pagedataid' . $v['id'];
            //查出当前页面数据部署情况
            $data['page_data'][$k]['deploy'] = common::get_deploy_templates($site_id, $page_id, $v['id']);
            if (is_array($data['page_data'][$k]['deploy']))
            {
                foreach ($data['page_data'][$k]['deploy'] as $kk => $vv)
                {
                    if (is_array($vv) && count($vv) > 0)
                    {
                        foreach ($vv as $kkk => $vvv)
                        {
                            if ($vvv['id'])
                            {
                                $ext                                               = "site_id=" . $vvv['site_id'] . "&page_id=" . $vvv['page_id'] . "&page_data_id=" . $vvv['page_data_id'] . "&content_type=" . $vvv['content_type'];
                                $ext                                               = urlencode($ext);
                                $data['page_data'][$k]['deploy'][$kk][$kkk]['ext'] = $ext;
                            }
                        }
                    }
                }
            }
        }
        if ($add_site_deploy)
        {
            $site_deploy['id']      = 'siteid' . $site_id . '_pageid0_pagedataid0';
            $site_deploy['name']    = '站点首页';
            $site_deploy['fid']     = 0;
            $site_deploy['is_last'] = 1;
            $site_deploy['deploy']  = common::get_deploy_templates($site_id, 0, 0);
            if ($site_deploy['deploy'])
            {
                foreach ($site_deploy['deploy'] as $kk => $vv)
                {
                    if (is_array($vv) && count($vv) > 0)
                    {
                        foreach ($vv as $kkk => $vvv)
                        {
                            if ($vvv['id'])
                            {
                                $ext                                     = "site_id=" . $vvv['site_id'] . "&page_id=" . $vvv['page_id'] . "&page_data_id=" . $vvv['page_data_id'] . "&content_type=" . $vvv['content_type'];
                                $ext                                     = urlencode($ext);
                                $site_deploy['deploy'][$kk][$kkk]['ext'] = $ext;
                            }
                        }
                    }
                }
            }
            if (is_array($data['page_data']))
            {
                array_unshift($data['page_data'], $site_deploy);
            }
        }
        //获取所有内容类型
        $content_type           = $this->pub_content->get_all_content_type();
        $result['page_data']    = $data['page_data'];
        $result['content_type'] = $content_type;
        $result['client_type']  = $content_type;
//		print_r($data['page_data']);
        $this->addItem($result);
        $this->output();
    }

    public function count()
    {
//		$total = $this->pub_config->get_column_count($this->input);
//		echo json_encode($total);
    }

    public function deploy_form()
    {
        $id = $this->input['id'];
        if (!$id)
        {
            $this->errorOutput('NO_ID');
        }

        //siteid1_pageid0_pagedataid0
        $idarr = explode('_', $id);
        if (!$idarr[0] || !$idarr[1] || !$idarr[2])
        {
            $this->errorOutput('NO_ID');
        }

        $site_id      = intval(str_replace('siteid', '', $idarr[0]));
        $page_id      = intval(str_replace('pageid', '', $idarr[1]));
        $page_data_id = intval(str_replace('pagedataid', '', $idarr[2]));
        $offset       = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count        = $this->input['count'] ? intval($this->input['count']) : 500;
        if ($site_id && !$page_id && !$page_data_id)
        {
            ;
        }
        else if ($site_id && $page_id && !$page_data_id)
        {
            $data = common::get_page_data($page_id, $offset, $count, 0);
            foreach ($data['page_data'] as $k => $v)
            {
                $data['page_data'][$k]['id'] = 'siteid' . $site_id . '_pageid' . $page_id . '_pagedataid=' . $v['id'];
            }
        }
        else if ($site_id && $page_id && $page_data_id)
        {
            $data = common::get_page_data($page_id, $offset, $count, $page_data_id);
            foreach ($data['page_data'] as $k => $v)
            {
                $data['page_data'][$k]['id'] = 'siteid' . $site_id . '_pageid' . $page_id . '_pagedataid=' . $v['id'];
            }
        }
        $this->addItem($data['page_data']);
        $this->output();
    }

//	public function deploy_form()
//	{	
//		$site_id	  = intval($this->input['site_id']);
//		$page_id 	  = intval($this->input['page_id']);
//		$page_data_id = intval($this->input['page_data_id']);
//		$page_data_fid = intval($this->input['page_data_fid']);
//		$deploy_name = $this->input['deploy_name'];
//		$set_type = $this->settings['site_col_template'];
//				
//		if($site_id && !$page_id && !$page_data_id)
//		{
//			//表示对站点进行部署
//			//有内容，查出内容类型
//			$content_type = $this->pub_content->get_all_content_type();
//			foreach($content_type as $k=>$v)
//			{
//				$set_type[$v['id']] = $v['content_type'];
//			}
//		}
//		else if($page_id)
//		{
//			//表示对页面类型进行部署 OR 表示对页面数据进行部署
//			$page_info = common::get_page_by_id($page_id);
//			$site_id   = $page_info['site_id'];
//			if($page_info['has_content'])
//			{
//				//有内容，查出内容类型
//				$content_type = $this->pub_content->get_all_content_type();
//				foreach($content_type as $k=>$v)
//				{
//					$set_type[$v['id']] = $v['content_type'];
//				}
//			}
//		}
//		else
//		{
//			$this->errorOutput('NO_PAGE_ID');
//		}
//		
//		//获取站点支持的客户端
//		$site_detail = $this->pub_config->get_site_client($site_id);
//		if(!$site_detail['site']['tem_style'])
//		{
//			$this->errorOutput('NOT_USE_TEM_STYLE');
//		}
//		
//		$support_client = explode(',',$site_detail['site']['support_client']);
//		//取出所有的客户端
//		foreach($site_detail['client'] as $k=>$v)
//		{
//			$clientarr[$v['id']] = $v['name'];
//		}
//		
//		//取出部署结果
//		$tem_data = $this->obj->get_deploy_template($site_id,$this->settings['tem_style_default'],$page_id,$page_data_id);
//
//		$result['set_type']	      	= $set_type;
//		$result['site_id'] 	  	  	= $site_id;
//		$result['page_id']  	  	= $page_id;
//		$result['page_data_id']   	= $page_data_id;
//		$result['page_data_fid']   	= $page_data_fid;
//		$result['support_client'] 	= $support_client;
//		$result['client']         	= $clientarr;
//		$result['deploy_name']         	= $deploy_name;
//		$result['tem_data'] = $tem_data;
//		
// 		$this->addItem($result);
//		$this->output();	
//	}

    public function browse()
    {
        $usetem       = array();
        $site_id      = intval($this->input['site_id']);
        $id           = intval($this->input['id']);
        $content_type = urldecode($this->input['content_type']);
        $client       = intval($this->input['client']);
        $search_tem   = urldecode($this->input['search_tem']);

        if (!$site_id)
        {
            $this->errorOutput("NO_SITE_ID");
        }

        $site_detail = $this->pub_config->get_site_first('id,site_name,tem_style', $site_id);

        if (empty($site_detail) || !is_array($site_detail))
        {
            $this->errorOutput("NO_SITE_DETAIL");
        }

        $tem_sort = common::get_template_sort('', $id, $search_tem);
        if ($search_tem || $id)
        {
            //取默认套系下面的模板
            $tem = common::get_template($this->settings['tem_style_default'], $id, $client, $search_tem);
        }

        $backdata = common::get_fsort($id);

        $data['site_id']      = $site_id;
        $data['sort_fid']     = $id;
        $data['backdata']     = $backdata;
        $data['content_type'] = $content_type;
        $data['client']       = $client;
        $data['use_tem']      = $usetem;
        $data['sort']         = $tem_sort;
        $data['template']     = $tem;
        $data['tem_style']    = $site_detail['tem_style'];

        $this->addItem($data);
        $this->output();
    }

    public function update()
    {
        $defaulttype   = array();
        $site_id       = intval($this->input['site_id']);
        $page_id       = intval($this->input['page_id']);
        $page_data_id  = intval($this->input['page_data_id']);
        $page_data_fid = intval($this->input['page_data_fid']);
        $title         = $this->input['title'];

        //获取站点支持的客户端
        $site_detail    = $this->pub_config->get_site_client($site_id);
        $support_client = explode(',', $site_detail['site']['support_client']);

        $set_type = $this->settings['site_col_template'];
        if ($site_id && !$page_id)
        {
            //有内容，查出内容类型
            $content_type = $this->pub_content->get_all_content_type();
            foreach ($content_type as $k => $v)
            {
                $set_type[$v['id']] = $v['content_type'];
            }
        }
        else if ($page_id)
        {
            //表示对页面类型进行部署 OR 表示对页面数据进行部署
            $page_info = common::get_page_by_id($page_id);
            $site_id   = $page_info['site_id'];
            if ($page_info['has_content'])
            {
                //有内容，查出内容类型
                $content_type = $this->pub_content->get_all_content_type();
                foreach ($content_type as $k => $v)
                {
                    $set_type[$v['id']] = $v['content_type'];
                }
            }
        }
        else
        {
            $this->errorOutput('NO_PAGE_ID');
        }

        $data = array(
            'site_id' => $site_id,
            'page_id' => $page_id,
            'page_data_id' => $page_data_id,
            'page_data_fid' => $page_data_fid,
            'title' => $title,
        );

        $dep_tem = $this->obj->get_deploy_template($site_id, $this->settings['tem_style_default'], $page_id, $page_data_id);

        foreach ($support_client as $kc => $vc)
        {
            foreach ($set_type as $k => $v)
            {
                //判断文本框里有没有值，没有值则代表没有选择模板，置模板id为0
                if (!urldecode($this->input['tem_' . $vc . '_' . $k]))
                {
                    if (!empty($dep_tem[$site_id][$page_id][$page_data_id][$vc][$k]))
                    {
                        //删除
                        $dep_tem_id = $dep_tem[$site_id][$page_id][$page_data_id][$vc][$k]['id'];
                        $this->obj->delete('deploy_template', $dep_tem_id);
                    }
                    continue;
                }
                else
                {
                    $data['template_sign'] = $this->input['temid_' . $vc . '_' . $k];
                    $data['client_id']     = $vc;
                }
                $data['content_type'] = $k;
                //先查看有没有此记录，没有则插入，有则更新
                if (empty($dep_tem[$site_id][$page_id][$page_data_id][$vc][$k]))
                {
                    $this->obj->insert_col_tem($data);
                }
                else
                {
                    $update_data = array('template_sign' => $data['template_sign'], 'page_data_fid' => $data['page_data_fid'], 'title' => $title);
                    $dep_tem_id  = $dep_tem[$site_id][$page_id][$page_data_id][$vc][$k]['id'];
                    $this->obj->update('deploy_template', $dep_tem_id, $update_data);
                }
            }
        }

        //重建缓存
        include_once(CUR_CONF_PATH . "lib/rebuild_deploy.class.php");
        $rebuild_deploy = new rebuilddeploy();
        $rebuild_deploy->rebuild_deploy($site_id, $page_id, $page_data_id, $site_detail['site']);

        $this->addItem(1);
        $this->output();
    }

    //当栏目内容类型更改时，删除column_template中记录
    public function delete_column_template()
    {
        $column_id = intval($this->input['column_id']);
        $typeids   = intval($this->input['typeids']);
        //不删除本身，子级，内容模板
        $typeids   = trim($typeids, ',') . ",'self','child','content'";
        $typeids   = trim($typeids, ',');
        $sql       = "DELETE FROM " . DB_PREFIX . "column_template WHERE column_id=" . $column_id . " AND type not in(" . $typeids . ")";
        $this->db->query($sql);
    }

    public function test()
    {
        include_once(CUR_CONF_PATH . "lib/rebuild_deploy.class.php");
        $rebuild_deploy = new rebuilddeploy();
        $data           = $rebuild_deploy->get_deploy_templates(1, 8, 1);
        print_r($data);
        exit;
    }

    public function get_content_type()
    {
        $set_type_content = array();
        //有内容，查出内容类型
        $content_type     = $this->pub_content->get_all_content_type();
        if (is_array($content_type))
        {
            foreach ($content_type as $k => $v)
            {
                $set_type_content[$v['id']] = $v['content_type'];
            }
        }
        return $set_type_content;
    }

    public function template_use_record()
    {
        $use_name = array();
        $site_id  = intval($this->input['site_id']);
        $tem_sign = urldecode($this->input['tem_sign']);
        if (!$site_id || !$tem_sign)
        {
            $this->errorOutput('NO_SITEID_OR_TEMSIGN');
        }
        $tem = $this->obj->get_deploy_by_sign($site_id, $tem_sign);
        foreach ($tem as $v)
        {
            $use_name[] = $v['title'];
        }
        $data = array('b' => 'aa');
        $this->addItem($use_name);
        $this->output();
    }

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

$out    = new deployApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
