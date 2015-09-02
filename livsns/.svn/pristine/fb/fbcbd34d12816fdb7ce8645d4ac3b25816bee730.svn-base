<?php

require('global.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
require_once(ROOT_PATH . 'lib/class/publishsys.class.php');
require_once(ROOT_PATH . 'lib/class/publishcms.class.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID', 'column'); //模块标识

class columnApi extends adminupdateBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include site.class.php
     */
    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . "lib/common.php");
        include(CUR_CONF_PATH . 'lib/column.class.php');
        $this->obj         = new column();
        include(CUR_CONF_PATH . 'lib/column_node.class.php');
        $this->col_node    = new column_node();
        include(ROOT_PATH . 'lib/class/material.class.php');
        $this->material    = new material();
        $this->pub_content = new publishcontent();
        $this->pub_sys     = new publishsys();
        $this->pub_cms     = new publishcms();
        //如果是从部署那边来访问，则切换input值
        if (!empty($this->input['pub_input']))
        {
            $this->input = $this->input['pub_input'];
        }
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function __getConfig()
    {
        $total = $this->obj->get_site('id');
        if (count($total) < 1)
        {
            $this->errorOutput('REDIRECT TO ' . APP_UNIQUEID . ' site');
        }
        parent::__getConfig();
    }

    public function show()
    {
        /*         * 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }
        $father_column = array();
        $site_id       = intval($this->input['site_id']);
        $column_id     = intval($this->input['column_id']);
        $num           = intval($this->input['selectnum']);
        $offset        = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count         = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;

        //查询出所有站点
//		$site_data = $this->obj->get_site();
        //查询出栏目
        $column_data = $this->obj->get_column(' id,name,is_last ', $this->get_condition(), $offset, $count);

        $alldata['column_data'] = $column_data;
//		$alldata['site_data'] = $site_data;
        $alldata['column_id']   = $column_id;
        $this->addItem($alldata);
        $this->output();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "column WHERE 1 " . $this->get_condition();
        echo json_encode($this->db->query_first($sql));
    }

    public function pub_count()
    {
        $sql   = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "column WHERE 1 " . $this->get_condition();
        $total = $this->db->query_first($sql);
        $this->addItem($total);
        $this->output();
    }

    private function get_condition()
    {
        $condition = '';

        $site_id   = intval($this->input['site_id']);
        $column_id = intval($this->input['column_id']);

        if ($site_id && $site_id != '-1')
        {
            $condition .= " AND site_id=" . $site_id;
        }

        if ($column_id && $column_id != '-1')
        {
            $condition .= " AND fid=" . $column_id;
        }
        else
        {
            $condition .= " AND fid=0";
        }

        if ($keyword = urldecode($this->input['keyword']))
        {
            $condition .= " AND name like '%" . $keyword . "%' ";
        }
        $condition .= " ORDER BY order_id ";
        return $condition;
    }

    public function column_form()
    {
        /*         * 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }
        $data['site_id'] = intval($this->input['site_id']);
        $column_id       = intval($this->input['id']);
        if ($column_id)
        {
            $icondata              = $this->obj->get_column_all_icon($column_id);
            $coldata               = $this->obj->get_column_first(' * ', $column_id);
            //栏目目录提取
            $col_dirarr            = explode('/', $coldata['column_dir']);
            $col_dirarr            = array_reverse($col_dirarr);
            $coldata['column_dir'] = $col_dirarr[0];

            //栏目下的内容命名格式
            if (!is_numeric($coldata['fileformat']))
            {
                $coldata['fileformat']      = substr($coldata['fileformat'], 2);
                $coldata['fileformatradio'] = 1;
            }

            $data['column']     = $coldata;
            $data['icondata']   = $icondata;
            $data['client_pic'] = unserialize($coldata['client_pic']);
            $data['column_id']  = $column_id;
            $data['site_id']    = $coldata['site_id'];
        }
        else
        {
            if (!$data['site_id'])
            {
                $this->errorOutput("未选择站点，请先选择站点");
            }
            $coldata['site_id'] = $data['site_id'];
        }
        $data['column_fid'] = intval($this->input['column_fid']);

        //栏目子域名
        $site_detail                   = $this->obj->get_site_by_id($coldata['site_id'], ' * ');
        $data['column']['site_weburl'] = preg_match(IP_REGULAR, $site_detail['weburl']) ? '' : $site_detail['weburl'];
        if ($data['column']['site_weburl'])
        {
            $data['column']['site_weburl'] = $data['column']['site_weburl'];
        }
        $data['column']['client_pic'] = unserialize($data['column']['client_pic']);
        $data['column']['pic']        = unserialize($data['column']['pic']);
        //获取站点支持的模块
//		if($site_detail['support_module'])
//		{
//			$data['module'] = common::get_app_data($site_detail['support_module']);
//		}
        //根据栏目支持的模块获取支持的内容类型
//		if($site_detail['support_content_type'])
//		{
//			$support_content_type_arr = explode(',',$site_detail['support_content_type']);
//			$support_content_type_by_module = $this->pub_content->get_content_type_by_colid($column_id);
//			if(is_array($support_content_type_by_module))
//			{
//				foreach($support_content_type_by_module as $k=>$v)
//				{
//					if(in_array($v['id'],$support_content_type_arr))
//					{
//						$data['content_type'][] = $v;
//					}
//				}
//			}
//		}

        $client             = $this->obj->get_client(' * ', 'id');
        $data['all_client'] = $client;

        //根据站点选择客户端，仅支持站点支持的客户端
        $site_detail                 = $this->obj->get_site_by_id($coldata['site_id'], $field                       = ' id,support_client');
        $data['site_support_client'] = empty($site_detail['support_client']) ? array() : explode(',', $site_detail['support_client']);
        $this->addItem($data);
        $this->output();
    }

    public function operate()
    {
        $fast_add_column = urldecode($this->input['fast_add_column']); //表示ajax快速增加栏目请求过来
        $column_id       = intval($this->input['column_id']);
        $column_fid      = intval($this->input['column_fid']);
        $site_id         = intval($this->input['site_id']);

        /*         * 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }

            $auth_node = $this->user['prms']['publish_prms'];
            $auth_node_str = $auth_node ? implode(',', $auth_node) : '';
            if(!$auth_node_str)
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
            if ($column_id)
            {
                $this->verify_column_prms($column_id);
            }
            if ($column_fid)
            {
                if ($column_id)
                {
                    $column = $this->obj->get_column_first('fid', $column_id);
                    if ($column['fid'] != $column_fid)
                    {
                        $this->verify_column_prms($column_fid);
                    }
                }
                else
                {
                    $this->verify_column_prms($column_fid);
                }
            }
            else
            {
                //非管理员不可创建首页栏目
                $this->errorOutput(NO_PRIVILEGE);
            }

        }
         
        $data            = array(
            'site_id' => $site_id,
            'shortname' => trim(urldecode($this->input['shortname'])),
            'childdomain' => trim(urldecode($this->input['childdomain'])),
            'is_outlink' => intval($this->input['is_outlink']),
            'linkurl' => urldecode($this->input['linkurl']),
            'keywords' => trim(urldecode($this->input['keywords'])),
            'content' => trim(urldecode($this->input['content'])),
            'colindex' => empty($this->input['colindex']) ? $this->settings['defalult_column_index_name'] : trim(urldecode($this->input['colindex'])),
            'maketype' => $this->input['maketype'] ? intval($this->input['maketype']) : 1,
            'col_con_maketype' => $this->input['col_con_maketype'] ? intval($this->input['col_con_maketype']) : 1,
            'suffix' => urldecode($this->input['suffix']),
            'column_dir' => trim(urldecode($this->input['column_dir'])),
            'contentfilename' => trim(urldecode($this->input['contentfilename'])),
            'folderformat' => $this->input['folderformat'] ? $this->input['folderformat'] : 'Y-m-d',
//			'fileformat' => urldecode($this->input['fileformat']),
            'titleformat' => urldecode($this->input['titleformat']),
            'needartstat' => urldecode($this->input['needartstat']),
            'needcolstat' => urldecode($this->input['needcolstat']),
            'needartadv' => urldecode($this->input['needartadv']),
            'article_maketype' => urldecode($this->input['article_maketype']),
            'support_module' => empty($this->input['support_module']) ? '' : implode(',', $this->input['support_module']),
            'support_content_type' => empty($this->input['support_content_type']) ? '' : implode(',', $this->input['support_content_type']),
            'support_client' => empty($this->input['support_client']) ? '' : implode(',', $this->input['support_client']),
            'custom_content_dir' => trim($this->input['custom_content_dir']),
            'column_file' => $this->input['column_file'] ? trim($this->input['column_file']) : 'index',
            'cssid' => $this->input['cssid']?intval($this->input['cssid']):0,
        );
        if ($this->input['client_top_pic'])
        {
            foreach ($this->input['client_top_pic'] as $k => $v)
            {
                $client_pic[$k] = unserialize(html_entity_decode($v));
            }
            $data['client_pic'] = serialize($client_pic);
        }
        else
        {
            $data['client_pic'] = '';
        }
        if ($_FILES['Filedata'])
        {
            $file['Filedata'] = $_FILES['Filedata'];
            $pic_info         = $this->material->addMaterial($file); //插入示意图
            if ($pic_info)
            {
                $arr         = array(
                    'id'   => $pic_info['id'],//保存图片的id
                    'host' => $pic_info['host'],
                    'dir' => $pic_info['dir'],
                    'filepath' => $pic_info['filepath'],
                    'filename' => $pic_info['filename'],
                );
                $data['pic'] = serialize($arr);
            }
        }
        if (intval($this->input['fileformatradio']) == 1)
        {
            $data['fileformat'] = '1-' . trim($this->input['fileformattext']);
        }
        else
        {
            $data['fileformat'] = $this->input['fileformat'] ? intval($this->input['fileformat']) : '1-';
        }
        $column_name = trim($this->input['column_name']);
        if (empty($column_name))
        {
            $this->errorOutput("填写信息不全");
        }

        if ($column_id)
        {
            //查出这个栏目更新前的状态
            $old_coldetail = $this->obj->get_column_first(' * ', $column_id);
        }

        //先查询这个站点跟目录是否被应用
        if ($data['childdomain'])
        {
            $domain_data = array(
                'type' => $this->settings['domain_type']['column'],
                'from_id' => $column_id,
                'sub_domain' => $data['childdomain'],
                'domain' => $this->input['childdomain_suffix'],
                'path' => $data['column_dir'],
            );
            if (!common::check_domain($domain_data))
            {
                $this->errorOutput("域名子域名已存在，请重新输入！");
            }
        }
        else if ($old_coldetail['childdomain'])
        {
            //删除这个栏目域名
            common::delete_domain($this->settings['domain_type']['column'], $column_id);
        }

        //获取栏目支持的客户端，如果为空，则取上级支持的客户端
        $data['support_client'] = $this->obj->get_column_support_client($data['support_client'], $site_id, $column_fid);
        if (!$data['support_client'])
        {
            $this->errorOutput("未取得客户端");
        }

        //取站点信息
        $site_detail = $this->obj->get_site_by_id($site_id);
        if ($column_id)
        {
            $id = $column_id;
            $dele_ids     = '';
            unset($data['site_id']);
            $data['name'] = $column_name;

            if (!$this->obj->update_column($column_id, $data))
            {
                $this->errorOutput("更新失败");
            }
            
            //查看栏目详细信息，看fid是否有改变，如果有改变，则调用节点方法更改
            $coldetail = $this->obj->get_column_first(' parents,fid ', $column_id);
            if ($coldetail['fid'] != $column_fid)
            {
                $this->col_node->update_node($column_id, $column_fid);
            }

            //计算出栏目的目录
            if ($column_fid)
            {
                $oth_coldetail = $this->obj->get_column_first(' column_dir,column_url,relate_dir ', $column_fid);
            }
            $column_self_dir = $data['column_dir'] ? $data['column_dir'] : ($this->settings['defalult_column_dir'] . $column_id);
            $col_dir         = (empty($oth_coldetail['column_dir']) ? '' : $oth_coldetail['column_dir']) . '/' . $column_self_dir;
            $this->obj->update_column($column_id, array('column_dir' => $col_dir));
            $now             = $this->obj->get_column_first(' * ', $column_id);
            $this->change_dir($old_coldetail, $now, $column_id, $column_self_dir);

            //更新服务器生成页面栏目目录
            if ($old_coldetail['column_dir'] != $col_dir)
            {
                include(ROOT_PATH . 'lib/class/mkpublish.class.php');
                $this->mkpublish = new mkpublish();
                $this->mkpublish->rename_folder(rtrim($site_detail['site_dir'], '/') . $old_coldetail['column_dir'], rtrim($site_detail['site_dir'], '/') . $col_dir);
            }

            //更新domain
            if ($data['childdomain'])
            {
                $domain_data['path'] = $col_dir;
                common::update_domain($domain_data);
            }

            if ($old_coldetail['childs'])
            {
                $childs_data = $this->obj->get_column_by_id(' * ', $old_coldetail['childs'], 'id');
                foreach (explode(',', $old_coldetail['childs']) as $cms_column_id)
                {
                    if ($cms_column_id)
                    {
                        //栏目插入到cms并保存cms栏目id
                        $cms_column_data = array(
                            'column_id' => $cms_column_id,
                            'name' => $childs_data[$cms_column_id]['name'],
                            'brief' => $childs_data[$cms_column_id]['content'],
                            'cms_fid' => $childs_data[$cms_column_id]['fid'],
                            'cms_siteid' => $childs_data[$cms_column_id]['site_id'],
                            'column_dir' => $childs_data[$cms_column_id]['column_dir'],
                            'relate_dir' => $childs_data[$cms_column_id]['relate_dir'],
                            'linkurl' => $childs_data[$cms_column_id]['linkurl'],
                            'childdomain' => $childs_data[$cms_column_id]['childdomain'],
                            'colindex' => $childs_data[$cms_column_id]['colindex'],
                        );
                        $this->pub_cms->update_cms_column($cms_column_data);
                    }
                }
            }
            $new_coldetail = $this->obj->get_column_first(' * ', $column_id);
            $this->addLogs('更新栏目',$old_coldetail,$new_coldetail,$column_name);
        }
        else
        {
            if ($column_fid)
            {
                $oth_coldetail = $this->obj->get_column_first(' column_dir,column_url,relate_dir,father_domain ', $column_fid);
            }

            //先插入节点
            $data['content_update_time'] = TIMENOW;
            $id                          = $this->col_node->insert_node($column_name, $column_fid, $data);
            //更新order_id 排序id
            $this->obj->update_column($id, array('order_id' => $id, 'father_domain' => $oth_coldetail['father_domain']));
            //计算出栏目的目录
            $column_self_dir             = $data['column_dir'] ? $data['column_dir'] : ($this->settings['defalult_column_dir'] . $id);
            $col_dir                     = (empty($oth_coldetail['column_dir']) ? '' : $oth_coldetail['column_dir']) . '/' . $column_self_dir;

            //插入子域名
            if ($data['childdomain'])
            {
                $domain_data['path'] = $col_dir;
                common::insert_domain($domain_data + array('from_id' => $id));
            }

            //计算出栏目的相对路径
            if ($data['childdomain'])
            {
                $relate_dir = '';
            }
            else
            {
                $relate_dir = (empty($oth_coldetail['relate_dir']) ? '' : $oth_coldetail['relate_dir']) . '/' . $column_self_dir;
            }

            $this->obj->update_column($id, array('column_dir' => $col_dir, 'relate_dir' => $relate_dir));

            //栏目插入到cms并保存cms栏目id
            $cms_column_data = array(
                'cms_columnid' => $id,
                'name' => $column_name,
                'brief' => $data['content'],
                'cms_fid' => $column_fid,
                'cms_siteid' => $site_id,
                'column_dir' => $col_dir,
                'linkurl' => $data['linkurl'],
                'orderid' => $id,
                'relate_dir' => $relate_dir,
                'childdomain' => $data['childdomain'],
                'colindex' => $data['colindex'],
            );
            $cms_column_id   = $this->pub_cms->insert_cms_column($cms_column_data);
            $new_coldetail = $this->obj->get_column_first(' * ', $id);
            $this->addLogs('新增栏目','',$new_coldetail,$column_name);
            
            //插入图片
            //$this->insert_pic($id, $data['support_client']);
        }
        $data['site_id']    = $data['site_id'];
        $data['column_id']  = $column_id;
        $data['column_fid'] = $column_fid;
        $data['id'] = $id;
        $data['column_name'] = $column_name;
        //获取栏目支持的模块 from table app
//		$data['module'] = common::get_module();
        
        if($this->settings['is_syn_clouds'])
        {
            $syn_data = array();
            $syn_data = $syn_data+$data;
            unset($syn_data['id'],$syn_data['column_id'],$syn_data['fid'],$syn_data['syn_id']);
            if(!$column_id)
            {
                if($column_fid)
                {
                    $father_column = $this->obj->get_column_first(' syn_id ',$column_fid);
                    $syn_data['column_fid'] = $father_column['syn_id'];
                }
                $syn_data['column_name'] = $column_name;
                $syn_data['site_id'] = $site_detail['syn_id'];
            }
            else
            {
                $sql = "select c.name,c.syn_id,cc.syn_id as f_syn_id from ".DB_PREFIX."column c left join ".DB_PREFIX."column cc on c.fid=cc.id where c.id=".$column_id;
                $newcolumn = $this->db->query_first($sql);
                $syn_data = $syn_data+$newcolumn;
                $syn_data['column_id'] = $newcolumn['syn_id'];
                $syn_data['column_fid'] = intval($newcolumn['f_syn_id']);
                $syn_data['column_name'] = $newcolumn['name'];
                $syn_data['site_id'] = $site_detail['syn_id'];
            }
            $this->syn_column($syn_data,$id);
        }
        if ($fast_add_column)
        {
            $this->addItem($id);
            $this->output();
        }
        
        $this->addItem($data);
        $this->output();
    }
    
    public function operate_syn()
    {
        /*         * 权限判断 
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }
		*/
         
        $fast_add_column = urldecode($this->input['fast_add_column']); //表示ajax快速增加栏目请求过来
        $column_id       = intval($this->input['column_id']);
        $column_fid      = intval($this->input['column_fid']);
        $site_id         = intval($this->input['site_id']);
        $data            = array(
            'site_id' => $site_id,
            'shortname' => trim(urldecode($this->input['shortname'])),
            'childdomain' => trim(urldecode($this->input['childdomain'])),
            'is_outlink' => intval($this->input['is_outlink']),
            'linkurl' => urldecode($this->input['linkurl']),
            'keywords' => trim(urldecode($this->input['keywords'])),
            'content' => trim(urldecode($this->input['content'])),
            'colindex' => empty($this->input['colindex']) ? $this->settings['defalult_column_index_name'] : trim(urldecode($this->input['colindex'])),
            'maketype' => $this->input['maketype'] ? intval($this->input['maketype']) : 1,
            'col_con_maketype' => $this->input['col_con_maketype'] ? intval($this->input['col_con_maketype']) : 1,
            'suffix' => urldecode($this->input['suffix']),
            'column_dir' => trim(urldecode($this->input['column_dir'])),
            'contentfilename' => trim(urldecode($this->input['contentfilename'])),
            'folderformat' => $this->input['folderformat'] ? $this->input['folderformat'] : 'Y-m-d',
//			'fileformat' => urldecode($this->input['fileformat']),
            'titleformat' => urldecode($this->input['titleformat']),
            'custom_content_dir' => trim($this->input['custom_content_dir']),
            'column_file' => $this->input['column_file'] ? trim($this->input['column_file']) : 'index',
        );
        if ($this->input['client_top_pic'])
        {
            foreach ($this->input['client_top_pic'] as $k => $v)
            {
                $client_pic[$k] = unserialize(html_entity_decode($v));
            }
            $data['client_pic'] = serialize($client_pic);
        }
        else
        {
            $data['client_pic'] = '';
        }
        if ($_FILES['Filedata'])
        {
            $file['Filedata'] = $_FILES['Filedata'];
            $pic_info         = $this->material->addMaterial($file); //插入示意图
            if ($pic_info)
            {
                $arr         = array(
                    'host' => $pic_info['host'],
                    'dir' => $pic_info['dir'],
                    'filepath' => $pic_info['filepath'],
                    'filename' => $pic_info['filename'],
                );
                $data['pic'] = serialize($arr);
            }
        }
        if (intval($this->input['fileformatradio']) == 1)
        {
            $data['fileformat'] = '1-' . trim($this->input['fileformattext']);
        }
        else
        {
            $data['fileformat'] = $this->input['fileformat'] ? intval($this->input['fileformat']) : '1-';
        }
        $column_name = trim($this->input['column_name']);
        if (empty($column_name))
        {
            $this->errorOutput("填写信息不全");
        }

        if ($column_id)
        {
            //查出这个栏目更新前的状态
            $old_coldetail = $this->obj->get_column_first(' * ', $column_id);
        }

        //先查询这个站点跟目录是否被应用
        if ($data['childdomain'])
        {
            $domain_data = array(
                'type' => $this->settings['domain_type']['column'],
                'from_id' => $column_id,
                'sub_domain' => $data['childdomain'],
                'domain' => $this->input['childdomain_suffix'],
                'path' => $data['column_dir'],
            );
            if (!common::check_domain($domain_data))
            {
                $this->errorOutput("域名子域名已存在，请重新输入！");
            }
        }
        else if ($old_coldetail['childdomain'])
        {
            //删除这个栏目域名
            common::delete_domain($this->settings['domain_type']['column'], $column_id);
        }

        //获取栏目支持的客户端，如果为空，则取上级支持的客户端
        $data['support_client'] = $this->obj->get_column_support_client($data['support_client'], $site_id, $column_fid);
        if (!$data['support_client'])
        {
            $this->errorOutput("未取得客户端");
        }

        //取站点信息
        $site_detail = $this->obj->get_site_by_id($site_id);
        if ($column_id)
        {
            $id = $column_id;
            $dele_ids     = '';
            unset($data['site_id']);
            $data['name'] = $column_name;

            if (!$this->obj->update_column($column_id, $data))
            {
                $this->errorOutput("更新失败");
            }
            
            //查看栏目详细信息，看fid是否有改变，如果有改变，则调用节点方法更改
            $coldetail = $this->obj->get_column_first(' parents,fid ', $column_id);
            if ($coldetail['fid'] != $column_fid)
            {
                $this->col_node->update_node($column_id, $column_fid);
            }

            //计算出栏目的目录
            if ($column_fid)
            {
                $oth_coldetail = $this->obj->get_column_first(' column_dir,column_url,relate_dir ', $column_fid);
            }
            $column_self_dir = $data['column_dir'] ? $data['column_dir'] : ($this->settings['defalult_column_dir'] . $column_id);
            $col_dir         = (empty($oth_coldetail['column_dir']) ? '' : $oth_coldetail['column_dir']) . '/' . $column_self_dir;
            $this->obj->update_column($column_id, array('column_dir' => $col_dir));
            $now             = $this->obj->get_column_first(' * ', $column_id);
            $this->change_dir($old_coldetail, $now, $column_id, $column_self_dir);

            //更新服务器生成页面栏目目录
            if ($old_coldetail['column_dir'] != $col_dir)
            {
                include(ROOT_PATH . 'lib/class/mkpublish.class.php');
                $this->mkpublish = new mkpublish();
                $this->mkpublish->rename_folder(rtrim($site_detail['site_dir'], '/') . $old_coldetail['column_dir'], rtrim($site_detail['site_dir'], '/') . $col_dir);
            }

            //更新domain
            if ($data['childdomain'])
            {
                $domain_data['path'] = $col_dir;
                common::update_domain($domain_data);
            }

            if ($old_coldetail['childs'])
            {
                $childs_data = $this->obj->get_column_by_id(' * ', $old_coldetail['childs'], 'id');
                foreach (explode(',', $old_coldetail['childs']) as $cms_column_id)
                {
                    if ($cms_column_id)
                    {
                        //栏目插入到cms并保存cms栏目id
                        $cms_column_data = array(
                            'column_id' => $cms_column_id,
                            'name' => $childs_data[$cms_column_id]['name'],
                            'brief' => $childs_data[$cms_column_id]['content'],
                            'cms_fid' => $childs_data[$cms_column_id]['fid'],
                            'cms_siteid' => $childs_data[$cms_column_id]['site_id'],
                            'column_dir' => $childs_data[$cms_column_id]['column_dir'],
                            'relate_dir' => $childs_data[$cms_column_id]['relate_dir'],
                            'linkurl' => $childs_data[$cms_column_id]['linkurl'],
                            'childdomain' => $childs_data[$cms_column_id]['childdomain'],
                            'colindex' => $childs_data[$cms_column_id]['colindex'],
                        );
                        $this->pub_cms->update_cms_column($cms_column_data);
                    }
                }
            }
            $new_coldetail = $this->obj->get_column_first(' * ', $column_id);
            $this->addLogs('更新栏目',$old_coldetail,$new_coldetail,$column_name);
        }
        else
        {
            if ($column_fid)
            {
                $oth_coldetail = $this->obj->get_column_first(' column_dir,column_url,relate_dir,father_domain ', $column_fid);
            }

            //先插入节点
            $data['content_update_time'] = TIMENOW;
            $id                          = $this->col_node->insert_node($column_name, $column_fid, $data);
            //更新order_id 排序id
            $this->obj->update_column($id, array('order_id' => $id, 'father_domain' => $oth_coldetail['father_domain']));
            //计算出栏目的目录
            $column_self_dir             = $data['column_dir'] ? $data['column_dir'] : ($this->settings['defalult_column_dir'] . $id);
            $col_dir                     = (empty($oth_coldetail['column_dir']) ? '' : $oth_coldetail['column_dir']) . '/' . $column_self_dir;

            //插入子域名
            if ($data['childdomain'])
            {
                $domain_data['path'] = $col_dir;
                common::insert_domain($domain_data + array('from_id' => $id));
            }

            //计算出栏目的相对路径
            if ($data['childdomain'])
            {
                $relate_dir = '';
            }
            else
            {
                $relate_dir = (empty($oth_coldetail['relate_dir']) ? '' : $oth_coldetail['relate_dir']) . '/' . $column_self_dir;
            }

            $this->obj->update_column($id, array('column_dir' => $col_dir, 'relate_dir' => $relate_dir));

            //栏目插入到cms并保存cms栏目id
            $cms_column_data = array(
                'cms_columnid' => $id,
                'name' => $column_name,
                'brief' => $data['content'],
                'cms_fid' => $column_fid,
                'cms_siteid' => $site_id,
                'column_dir' => $col_dir,
                'linkurl' => $data['linkurl'],
                'orderid' => $id,
                'relate_dir' => $relate_dir,
                'childdomain' => $data['childdomain'],
                'colindex' => $data['colindex'],
            );
            $cms_column_id   = $this->pub_cms->insert_cms_column($cms_column_data);
            $new_coldetail = $this->obj->get_column_first(' * ', $id);
            $this->addLogs('新增栏目','',$new_coldetail,$column_name);
            
            //插入图片
            //$this->insert_pic($id, $data['support_client']);
        }
        $data['site_id']    = $data['site_id'];
        $data['column_id']  = $column_id;
        $data['column_fid'] = $column_fid;
        $data['id'] = $id;
        $data['column_name'] = $column_name;
        //获取栏目支持的模块 from table app
//		$data['module'] = common::get_module();
        
        if($this->settings['is_syn_clouds'])
        {
            if(!$column_id)
            {
                if($column_fid)
                {
                    $father_column = $this->obj->get_column_first(' syn_id ',$column_fid);
                    $syn_data['column_fid'] = $father_column['syn_id'];
                }
                $syn_data['column_name'] = $column_name;
                $syn_data['site_id'] = $site_detail['syn_id'];
            }
            else
            {
                $sql = "select c.name,c.syn_id,cc.syn_id as f_syn_id from ".DB_PREFIX."column c left join ".DB_PREFIX."column cc on c.fid=cc.id where c.id=".$column_id;
                $newcolumn = $this->db->query_first($sql);
                $syn_data = $newcolumn;
                $syn_data['column_id'] = $newcolumn['syn_id'];
                $syn_data['column_fid'] = intval($newcolumn['f_syn_id']);
                $syn_data['column_name'] = $newcolumn['name'];
                $syn_data['site_id'] = $site_detail['syn_id'];
            }
            $this->syn_column($syn_data,$id);
        }
        if ($fast_add_column)
        {
            $this->addItem($id);
            $this->output();
        }
        
        $this->addItem($data);
        $this->output();
    }

    public function change_dir($last, $now, $column_id, $column_self_dir = '')
    {
        //父级fid变化
        if ($last['fid'] != $now['fid'])
        {
            $fid_columns = $this->obj->get_column_by_id(' * ', $last['fid'] . ',' . $now['fid'], 'id');
            if (!empty($fid_columns[$last['id']]))
            {
                $sql = "UPDATE " . DB_PREFIX . "column SET column_dir=RIGHT(column_dir,LENGTH(column_dir)-" . strlen($fid_columns[$last['id']]['column_dir']) . ") WHERE id in (" . $last['childs'] . ")";
                $this->db->query($sql);
            }
            if (!empty($fid_columns[$now['id']]))
            {
                $sql = "UPDATE " . DB_PREFIX . "column SET column_dir=CONCAT('" . $fid_columns[$now['id']]['column_dir'] . "',column_dir) WHERE id in (" . $last['childs'] . ")";
                $this->db->query($sql);
            }
        }

        $change_ids     = $this->change_id($last, $column_id);
        $change_ids_str = implode(',', $change_ids);
        $s_column       = $this->obj->get_column_first(' * ', $column_id);
        $father_column  = $this->obj->get_column_first(' * ', $now['fid']);

        //更新column_dir
        if ($last['column_dir'] != $s_column['column_dir'])
        {
            $sql = "UPDATE " . DB_PREFIX . "column SET column_dir=RIGHT(column_dir,LENGTH(column_dir)-" . strlen($last['column_dir']) . ") WHERE id in (" . $last['childs'] . ") AND id!=" . $column_id;
            $this->db->query($sql);
            $sql = "UPDATE " . DB_PREFIX . "column SET column_dir=CONCAT('" . $s_column['column_dir'] . "',column_dir) WHERE id in (" . $last['childs'] . ") AND id!=" . $column_id;
            $this->db->query($sql);
            if (!$last['childdomain'])
            {
                //if ($father_column['childdomain'])
                {
                    $sql = "UPDATE " . DB_PREFIX . "column SET relate_dir=concat('" . rtrim(($father_column['relate_dir'] . '/' . $column_self_dir), '/') . '\',SUBSTRING(relate_dir, ' . (strlen($last['relate_dir']) + 1) . ')' . ") WHERE id in (" . $change_ids_str . ")";
                    $this->db->query($sql);
                }
                /**
                else
                {
                    $sql = "UPDATE " . DB_PREFIX . "column SET relate_dir=column_dir WHERE id in (" . $change_ids_str . ")";
                    $this->db->query($sql);
                }
                 * 
                 */
            }
        }
        $s_column = $this->obj->get_column_first(' * ', $column_id);
        //更新relate_dir    
        //计算出栏目的相对路径
        if (($now['childdomain'] && !$last['childdomain']) || (!$now['childdomain'] && $last['childdomain']))
        {
            //处理这个栏目的子级栏目相对路径
            //查询出子级栏目是否有支持子级域名的
            if ($now['childdomain'] && !$last['childdomain'])
            {
                $sql = "UPDATE " . DB_PREFIX . "column SET relate_dir=RIGHT(relate_dir,LENGTH(relate_dir)-" . strlen($s_column['relate_dir']) . ") WHERE id in (" . $change_ids_str . ")";
                $this->db->query($sql);
            }
            else if (!$now['childdomain'] && $last['childdomain'])
            {
                //判断之前有子级域名，后来没有，这时子级栏目要加上前面栏目目录，先查出此栏目父级栏目
                $sql = "UPDATE " . DB_PREFIX . "column SET relate_dir=concat('" . $father_column['relate_dir'] . '/' . $column_self_dir . "',relate_dir) WHERE id in (" . $change_ids_str . ")";
                $this->db->query($sql);
            }
        }
        $father_domain = $this->get_column_father_domain($column_id, $now['parents']);
        $sql           = "UPDATE " . DB_PREFIX . "column SET father_domain='" . $father_domain . "' WHERE id in (" . $change_ids_str . ")";
        $this->db->query($sql);
    }

    public function get_column_father_domain($column_id = '', $father_id = '')
    {
        $result         = '';
        $father_data    = $this->obj->get_column_by_id(' id,relate_dir,childdomain,father_domain,childs ', $father_id, 'id');
        $column_parents = $this->col_node->getMergeParents($column_id);
        foreach ($column_parents as $k => $v)
        {
            $col_parents[] = $k;
        }
        if ($col_parents)
        {
            foreach (array_reverse($col_parents) as $v)
            {
                if (!empty($father_data[$v]['childdomain']))
                {
                    $result = $father_data[$v]['childdomain'];
                    break;
                }
            }
        }
        return $result;
    }

    public function change_id($old_coldetail, $column_id)
    {
        $change_ids     = array();
        $cur_child_data = $this->obj->get_column_by_id(' id,relate_dir,childdomain,childs ', $old_coldetail['childs']);
        foreach ($cur_child_data as $ccd)
        {
            if ($ccd['childdomain'] && $ccd['id'] != $column_id)
            {
                $idnotchilddomain .= ',' . $ccd['childs'];
            }
        }
        $idnotchilddomain     = trim($idnotchilddomain, ',');
        $idnotchilddomain_arr = $idnotchilddomain ? explode(',', $idnotchilddomain) : array();
        $child_arr            = explode(',', $old_coldetail['childs']);
        foreach ($child_arr as $vvv)
        {
            if (!in_array($vvv, $idnotchilddomain_arr))
            {
                $change_ids[] = $vvv;
            }
        }
        return $change_ids;
    }

    public function upload()
    {
        $spe_mater = $material  = array();
        $material  = $this->material->addMaterial($_FILES);
        if ($material)
        {
            $material['pic'] = array(
                'host' => $material['host'],
                'dir' => $material['dir'],
                'filepath' => $material['filepath'],
                'filename' => $material['filename'],
            );
            $material['pic'] = serialize($material['pic']);

            $return = array(
                'success' => true,
                'id' => $material['id'],
                'pic' => $material['pic'],
            );
        }
        else
        {
            $return = array(
                'error' => '文件上传失败',
            );
        }

        $this->addItem($return);
        $this->output();
    }

    /* public function insert_pic($id,$client)
      {
      include_once ROOT_PATH . 'lib/class/material.class.php';
      $this->mMaterial = new material();
      $client = explode(',',$client);
      foreach($client as $k=>$v)
      {
      if ($_FILES[$v.'-default']['tmp_name'])
      {
      $file['Filedata'] = $_FILES[$v.'-default'];
      $default = $this->mMaterial->addMaterial($file, $id);
      }
      if ($_FILES[$v.'-activation']['tmp_name'])
      {
      $file['Filedata'] = $_FILES[$v.'-activation'];
      $activation = $this->mMaterial->addMaterial($file, $id);
      }
      if ($_FILES[$v.'-no_activation']['tmp_name'])
      {
      $file['Filedata'] = $_FILES[$v.'-no_activation'];
      $no_activation = $this->mMaterial->addMaterial($file, $id);
      }
      if($default || $activation || $no_activation)
      {
      $this->obj->insert_column_icon($id,$v,$default,$activation,$no_activation);
      }
      }
      }

      public function update_pic($column_id,$client)
      {
      include_once ROOT_PATH . 'lib/class/material.class.php';
      $this->mMaterial = new material();
      $dele_ids = '';
      $clientarr = explode(',',$client);
      //更新图片
      foreach($clientarr as $k=>$v)
      {
      $update_data = array();
      $dele_ids = '';
      $icon = $this->obj->get_column_icon($column_id,$v);
      if ($_FILES[$v.'-default']['tmp_name'])
      {
      $file['Filedata'] = $_FILES[$v.'-default'];
      $default = $this->mMaterial->addMaterial($file, $column_id);
      $update_data['icon_default'] = serialize($default);

      if($icon['icon_default'])
      {
      $iconobj_icon_default = unserialize($icon['icon_default']);
      $dele_ids .= $iconobj_icon_default['id'].',';
      }
      }
      if ($_FILES[$v.'-activation']['tmp_name'])
      {
      $file['Filedata'] = $_FILES[$v.'-activation'];
      $activation = $this->mMaterial->addMaterial($file, $column_id);
      $update_data['activation'] = serialize($activation);
      if($icon['activation'])
      {
      $iconobj_activation = unserialize($icon['activation']);
      $dele_ids .= $iconobj_activation['id'].',';
      }
      }
      if ($_FILES[$v.'-no_activation']['tmp_name'])
      {
      $file['Filedata'] = $_FILES[$v.'-no_activation'];
      $no_activation = $this->mMaterial->addMaterial($file, $column_id);
      $update_data['no_activation'] = serialize($no_activation);
      if($icon['no_activation'])
      {
      $iconobj_no_activation = unserialize($icon['no_activation']);
      $dele_ids .= $iconobj_no_activation['id'].',';
      }
      }
      if(!empty($update_data))
      {
      //判断之前有没有插入过图片，如果有，则更新，没有则添加
      if(!empty($icon))
      {
      $this->obj->update_column_icon($column_id,$v,$update_data);
      }
      else
      {
      $this->obj->insert_column_icon($column_id,$v,$default,$activation,$no_activation);
      }
      }
      if($dele_ids = trim($dele_ids,','))
      {
      $this->mMaterial->deleteMaterialState($dele_ids);
      }
      }


      } */

    public function column_sort()
    {
        /*         * 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }

        $sort = json_decode(html_entity_decode($this->input['sort']), true);
        if (!empty($sort))
        {
            foreach ($sort as $k => $v)
            {
                $k = intval($k);
                $v = intval($v);
                $data = array(
                    'order_id' => $v,
                );
                if ($k && $v)
                {
                    $this->obj->update_column($k, $data);
                    $ids .= $tag.$k;
                    $tag = ',';
                }
            }
            
            if ($this->settings['is_syn_clouds'] && $ids)
            {
                $columns = $this->obj->get_column_by_id(' id,syn_id ',$ids,'id');
                foreach ($sort as $k => $v)
                {
                    if(!$columns[$k]['syn_id'])
                    {
                        continue;
                    }
                    $syn_column_sort[$columns[$k]['syn_id']] = $v;
                }
                if($syn_column_sort)
                {
                    $this->syn_column($syn_column_sort,'','sort');
                }
                
            }
            
            $this->pub_cms->column_sort($this->input['sort']);
        }
        $this->addItem('success');
        $this->output();
    }

    //删除栏目，只会一个一个删
    public function delete()
    {
        /***/
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }

        $ids = urldecode($this->input['id']);
        $tag = 'success';
        if (empty($ids))
        {
            $tag = '没有要删除的栏目';
        }

        /**非管理员验证权限*/
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $this->verify_column_prms($ids);
        }
        /*非管理员验证权限*/

        $now = $this->obj->get_column_first(' * ', $ids);


        $this->col_node->delete_node($ids);
        
        $this->addLogs('删除栏目',$now,'',$now['name']);
        
        if ($now['childdomain'])
        {
            //删除这个栏目域名
            common::delete_domain($this->settings['domain_type']['column'], $ids);
        }
        if($this->settings['is_syn_clouds'])
        {
            $this->syn_column($now,'','delete');
        }
        //删除cms里的对应的栏目
        if ($ids)
        {
            $this->pub_cms->delete_cms_column($ids);
        }
        //删除对应前台栏目
        $page['site_id'] = $now['site_id'];
        $page_type = $this->pub_sys->get_page_by_sign('column',$now['site_id']);
        $page['page_id'] = $page_type['id'];
        $page['page_data_id'] = $now['id'];
        include_once(ROOT_PATH . 'lib/class/mkpublish.class.php');
        $this->mkpublish = new mkpublish();
        $this->mkpublish->del_publish($page);
        
        $this->addItem($tag);
        $this->output();
    }

    public function check_domain()
    {
        $domain_data = array(
            'type' => $this->settings['domain_type']['column'],
            'from_id' => $this->input['column_id'],
            'sub_domain' => trim($this->input['sub_weburl'], '/'),
            'domain' => trim($this->input['weburl'], '/'),
            'path' => trim($this->input['column_dir'], '/'),
        );
        $result      = 1;
        if (!common::check_domain($domain_data))
        {
            $result = 0;
        }
        $this->addItem($result);
        $this->output();
    }

    public function get_column()
    {
        $field     = urldecode($this->input['field']) ? urldecode($this->input['field']) : ' * ';
        $condition = urldecode($this->input['condition']);
        $column    = $this->obj->get_column_by_con($field, $condition);
        $this->addItem($column);
        $this->output();
    }

    public function get_column_first()
    {
        $field     = urldecode($this->input['field']) ? urldecode($this->input['field']) : ' * ';
        $column_id = intval($this->input['column_id']);
        $column    = $this->obj->get_column_first($field, $column_id);
        if (!empty($column['site_id']))
        {
            $site                 = $this->obj->get_site_by_id($column['site_id'], ' id,site_name,sub_weburl,weburl ');
            $column['site_data']  = $site;
            $column['column_url'] = mk_column_url($column + array('sub_weburl' => $site['sub_weburl'], 'weburl' => $site['weburl']));
        }
        $this->addItem($column);
        $this->output();
    }

    //此方法已被发布内容到发布系统显示栏目使用，无法删除替换
    public function get_columnname_by_ids()
    {
        $result     = array();
        $field      = urldecode($this->input['field']) ? urldecode($this->input['field']) : ' * ';
        $column_ids = urldecode($this->input['column_ids']);
        if ($column_ids)
        {
            $sql  = "SELECT " . $field . " FROM " . DB_PREFIX . "column WHERE id in(" . $column_ids . ")";
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $result[$row['id']] = $row['name'];
            }
        }
        $this->addItem($result);
        $this->output();
    }

    //取多个栏目信息用此方法
    public function get_column_by_ids()
    {
        $result     = array();
        $field      = urldecode($this->input['field']) ? urldecode($this->input['field']) : ' * ';
        $column_ids = urldecode($this->input['column_ids']);
        if ($column_ids)
        {
            $result = $this->obj->get_column_by_id($field, $column_ids, 'id');
        }

        $this->addItem($result);
        $this->output();
    }

    public function get_column_site_by_ids()
    {
        $result     = array();
        $field      = urldecode($this->input['field']) ? urldecode($this->input['field']) : ' * ';
        $column_ids = urldecode($this->input['column_ids']);
        if ($column_ids)
        {
            $sql  = "SELECT c." . $field . ",s.sub_weburl,s.weburl,s.custom_content_dir FROM " . DB_PREFIX . "column c LEFT JOIN " . DB_PREFIX . "site s ON c.site_id=s.id WHERE c.id in(" . $column_ids . ")";
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $row['column_url']  = mk_column_url($row);
                $result[$row['id']] = $row;
            }
        }

        $this->addItem($result);
        $this->output();
    }

    public function get_pub_column()
    {
        if($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $auth_node = $this->user['prms']['publish_prms'];
            $auth_node_str = $auth_node ? implode(',', $auth_node) : '';
            if(!$auth_node_str)
            {
                $this->output();
            }
            $auth_node_parents = array();
            if($auth_node_str)
            {
                $sql = 'SELECT id,parents FROM '.DB_PREFIX.'column WHERE id IN('.$auth_node_str.')';
                $query = $this->db->query($sql);
                while($row = $this->db->fetch_array($query))
                {
                    $auth_node_parents[$row['id']] = explode(',', $row['parents']);
                }
            }
        }

        $data = json_decode(urldecode($this->input['data']), true);
//		if($data['bundle_id'] && $data['module_id'])
//		{
//			$sql = "SELECT id FROM ".DB_PREFIX."app a1 LEFT JOIN ".DB_PREFIX."app a2 ON a1.father=a2.id WHERE a2.father=0 AND a2.bundle='".$data['bundle_id']."' AND a1.bundle='".$data['module_id']."'";
//			$app_data = $this->db->query_first($sql);
//		}
        $sql  = "SELECT id,site_id,name,fid,is_last,depath FROM " . DB_PREFIX . "column WHERE 1 ";

        if ($data['site_id'])
        {
            $sql .= " AND site_id=" . $data['site_id'];
        }
        if (!empty($data['condition']))
        {
            $sql .= $data['condition'];
        }
        if (!empty($data['data_limit']))
        {
            $sql .= $data['data_limit'];
        }
        $info = $this->db->fetch_all($sql);

        if ( $this->user['group_type'] > MAX_ADMIN_TYPE )
        {
            foreach ((array)$info as $key => $row)
            {
                ###############非管理员用户数据过滤开始
                $row['is_auth'] = 0;
                //节点自身显示
                if(in_array($row['id'], (array)$auth_node))
                {
                    $row['is_auth'] = 1;
                }
                //
                if(!$row['is_auth'] && (array)$auth_node_parents)
                {
                    //父级节点显示
                    foreach ($auth_node_parents as $auth_node_id=>$auth_node_parent)
                    {
                        if(in_array($row['id'], (array)$auth_node_parent))
                        {
                            $row['is_auth'] = 2;
                            break;
                        }
                    }
                    //孩子节点显示
                    if(array_intersect(explode(',', $row['parents']), $auth_node))
                    {
                        $row['is_auth'] = 3;
                    }
                }
                if($this->user['prms']['site_prms'] && in_array($row['site_id'], $this->user['prms']['site_prms']))
                {
                    $row['is_auth'] = 1;
                }
                if(!$row['is_auth'])
                {
                    unset($info[$key]);
                }
                else
                {
                    $info[$key]['is_auth'] = $row['is_auth'];
                }
                ###############非管理员用户数据过滤结束
            }
        }

//		if(!empty($app_data))
//		{
//			foreach($info as $k=>$v)
//			{
//				if(!in_array($app_data['id'],explode(',',$v['support_module'])))
//				{
//					unset($info[$k]);
//				}
//			}
//		}
        $this->addItem($info);
        $this->output();
    }

    public function get_pub_column_by_id()
    {
        $ids   = urldecode($this->input['id']);
        $sql   = "SELECT id,site_id,name,fid,is_last,depath FROM " . DB_PREFIX . "column WHERE id in (" . $ids . ")";
        $query = $this->db->query($sql);
        while ($row   = $this->db->fetch_array($query))
        {
            $this->addItem($row);
        }
        $this->output();
    }

    public function get_col_parents()
    {
        $child_ids = '';
        $result    = array();
        $col_ids   = urldecode($this->input['col_ids']);
        if (!$col_ids)
        {
            $this->addItem('没有获取栏目id');
            $this->output();
        }
        $sql  = "SELECT id,name,fid,parents FROM " . DB_PREFIX . "column WHERE id in(" . $col_ids . ")";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            //查看父级的name
            $sql   = "SELECT id,name,fid,parents FROM " . DB_PREFIX . "column WHERE id in(" . $row['parents'] . ")";
            $info2 = $this->db->query($sql);
            $tag   = '';
            while ($row2  = $this->db->fetch_array($info2))
            {
                $result[$row['id']] .= $tag . $row2['name'];
                $tag = '/';
            }
        }

        $this->addItem($result);
        $this->output();
    }

    public function make_content_dir($content_id, $create_time, $folderformat, $fileformat)
    {
        $dir = date($folderformat, $create_time);
        switch ($fileformat)
        {
            case 1:
                $contentname = 'content' . $content_id;
                break;

            case 2:
                $contentname = date('Y-m-d', $create_time) . '-' . $content_id;
                break;

            case 3:
                $contentname = date('Y_m_d', $create_time) . '_' . $content_id;
                break;

            case 4:
                $contentname = date('Ymd', $create_time) . $content_id;
                break;

            case 5:
                $contentname = md5($content_id);
                break;
        }

        return $dir . '/' . $contentname;
    }

    //权限需要使用方法 zhulidong
    function get_column_site()
    {
        $column_id = $this->input['column_id'];
        $sql       = 'SELECT site_id,id FROM ' . DB_PREFIX . 'column WHERE id IN(' . $column_id . ')';
        $query     = $this->db->query($sql);
        $column    = array();
        while ($row       = $this->db->fetch_array($query))
        {
            $column[$row['site_id']][] = $row['id'];
        }
        $this->addItem($column);
        $this->output();
    }

    //权限中使用栏目节点公共方法
    function get_all_columns()
    {
        $sql    = 'SELECT c.site_id,c.id,c.name,s.site_name FROM ' . DB_PREFIX . 'column c LEFT JOIN ' . DB_PREFIX . 'site s ON c.site_id = s.id WHERE c.fid = 0 ';
        $query  = $this->db->query($sql);
        $column = array();
        while ($row    = $this->db->fetch_array($query))
        {
            $column[$row['site_id']][] = $row;
        }
        $this->addItem($column);
        $this->output();
    }

    public function get_clients()
    {
        require_once(ROOT_PATH . 'lib/class/curl.class.php');
        $curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'effective_app');
        $ret  = $curl->request('get_app_info.php');
        foreach ($ret[0] as $k => $v)
        {
            $clients[$v['appid']] = $v['custom_name'];
        }
        $this->addItem($clients);
        $this->output();
    }
    
    public function syn_column($data,$id,$type='')
    {
        include_once(CUR_CONF_PATH.'lib/publishcontent_syn.class.php');
        $publishcontent_syn = new publishcontent_syn();
        if($type=='delete')
        {
            if($data['syn_id'])
            {
                $result = $publishcontent_syn->syn_column_delete(array('id'=>$data['syn_id']));
            }
            return true;
        }
        else if($type=='sort')
        {    
            $publishcontent_syn->syn_column_sort(array('sort'=>json_encode($data)));
            return true;
        }
        else
        {
            $result = $publishcontent_syn->syn_column($data);
        }
        
        
        if(!$result['id'])
        {
            $this->input['id']=$id;
            $this->delete();
            $this->errorOutput('NOT_SYN'.var_export($result,1));
        }
        if(!$data['column_id'])
        {
            $this->obj->update_column($id,array('syn_id'=>$result['id']));
        }
        
    }


    protected function verify_column_prms ($ids)
    {
        if (!$ids)
        {
            return;
        }
        if ($this->user['group_type'] <= MAX_ADMIN_TYPE)
        {
            return;
        }
        $auth_node = $this->user['prms']['publish_prms'];
        $auth_node_str = $auth_node ? implode(',', $auth_node) : '';
        if(!$auth_node_str)
        {
            $this->errorOutput(NO_PRIVILEGE);
        }
        $columns = $this->obj->get_column_by_id('id, parents', $ids);
        foreach ((array)$columns as $k => $v)
        {
            //授权节点本身 可以操作
            if (!in_array($v['id'], (array)$auth_node))
            {
                //授权节点孩子节点 可以操作
                if(!array_intersect(explode(',', $v['parents']), (array)$auth_node))
                {
                    $this->errorOutput(NO_PRIVILEGE);
                }
            }
        }
    }
	
    /**
     * 子栏目排序
     */
    public function childColumnsSort()
    {
    	$newColumnIds = trim($this->input['newColumnIds']);
    	$parentId = intval($this->input['parentId']);
    	//先得到现有排序的信息
    	$info = $this->obj->getColumnsByfid($parentId);
    	$newCoulumnIdArray = explode(',', $newColumnIds);
    	//update
		$result = 1;
    	foreach($info as $k => $v)
    	{
    		$orderId = $v['order_id'];
    		$id = intval($newCoulumnIdArray[$k]);
    		$ret = $this->obj->update_column($id, array('order_id'=>$orderId));
    		if(!$ret)
    		{
    			$result = 0;
    		}
    	}
    	$this->addItem($result);
    	$this->output($result);
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

    public function create()
    {
        
    }

    public function update()
    {
        
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

}

$out    = new columnApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
