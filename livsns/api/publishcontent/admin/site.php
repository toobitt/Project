<?php

require('global.php');
require_once(ROOT_PATH . 'lib/class/publishcms.class.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID', 'site'); //模块标识

class siteApi extends adminupdateBase
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
        include(CUR_CONF_PATH . 'lib/site.class.php');
        $this->obj         = new site();
        $this->pub_cms     = new publishcms();
        $this->pub_content = new publishcontent();
    }

    public function __destruct()
    {
        parent::__destruct();
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
        $condition = $this->get_condition();
        $condition .= $cond;
        $site_id  = '';
        $sitedata = array();
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
        $sitedata = $this->obj->get_site(' * ', $condition, $offset, $count);

        $this->addItem($sitedata);
        $this->output();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "site " . " WHERE 1 " . $this->get_condition();
        echo json_encode($this->db->query_first($sql));
    }

    private function get_condition()
    {
        $condition = '';
        if ($keyword   = urldecode($this->input['keyword']))
        {
            $condition = " AND site_name like '%" . $keyword . "%' ";
        }
        if ( $id = $this->input['id'] )
        {
            $id = is_array($id) ? implode(',', $id) : $id;
            $condition = " AND id IN(" . $id . ")";
        }
        /** 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $col  = $this->user['prms']['publish_prms'];
            $site = $this->user['prms']['site_prms'] ? $this->user['prms']['site_prms'] : array();
            if ($col)
            {
                $col   = implode(',', $col);
                $sql   = 'SELECT site_id FROM ' . DB_PREFIX . 'column WHERE id IN(' . $col . ')';
                $query = $this->db->query($sql);
                while ($row   = $this->db->fetch_array($query))
                {
                    if (!in_array($row['site_id'], $site))
                    {
                        array_push($site, $row['site_id']);
                    }
                }
            }
            $condition .= ' AND id IN(' . implode(',', $site) . ')';
        }
        return $condition;
    }

    public function site_form()
    {
        /*         * 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }

        $sitedata = array();
        if ($id       = intval($this->input['id']))
        {
            $sitedata = $this->obj->get_site_by_id($id);
        }
        //获取所有客户端
        $allclient = $this->obj->get_client();

        //获取所有模块
        //$data['module'] = common::get_module();

        $data['site']   = $sitedata;
        $data['client'] = $allclient;

        //获取站点可以支持的内容类型
        //$data['content_type'] = $this->pub_content->get_content_type_by_colid($id,'','1');
//		print_r($data);exit;
        $this->addItem($data);
        $this->output();
    }

    public function operate()
    {
        /*         * 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }
         

        $site_id = intval($this->input['site_id']);
        $data    = array(
            'support_client' => $this->input['client'],
            'site_name' => $this->input['site_name'],
            'site_keywords' => str_replace(' ', ',', trim($this->input['keywords'])),
            'content' => urldecode($this->input['content']),
            'sub_weburl' => trim(urldecode($this->input['sub_weburl']), '/'),
            'sub_wdir' => urldecode($this->input['sub_wdir']),
            'weburl' => trim(urldecode($this->input['weburl']), '/'),
            'site_dir' => urldecode(trim($this->input['site_dir'])),
            'produce_format' => urldecode($this->input['produce_format']),
            'indexname' => urldecode($this->input['indexname']),
            'suffix' => urldecode($this->input['suffix']),
//			'material_fmt' => urldecode($this->input['material_fmt']),
//			'material_url' => urldecode($this->input['material_url']),
            'tem_material_url' => urldecode($this->input['tem_material_url']),
            'tem_material_dir' => urldecode($this->input['tem_material_dir']),
            'program_dir' => $this->input['program_dir'] ? $this->input['program_dir'] : 'm2o',
            'program_url' => urldecode($this->input['program_url']),
            'jsphpdir' => urldecode($this->input['jsphpdir']),
            'jsphpurl' => urldecode($this->input['jsphpurl']),
            'support_module' => empty($this->input['support_module']) ? '' : implode(',', $this->input['support_module']),
            'support_content_type' => empty($this->input['support_content_type']) ? '' : implode(',', $this->input['support_content_type']),
            'is_video_record' => intval($this->input['is_video_record']),
            'user_email' => $this->input['user_email'],
            'custom_content_dir' => $this->input['custom_content_dir'] ? trim($this->input['custom_content_dir'], '/') . '/' : '',
            'custom_content_url' => trim($this->input['custom_content_url']),
        );
        if (empty($data['site_name']))
        {
            $this->errorOutput("填写信息不全,请检测站点名称,站点域名,站点子域名是否填写");
        }

        if ($site_id)
        {
            $old_site_detail = $this->obj->get_site_by_id($site_id);
        }

        //先查询这个站点跟目录是否被应用
        if ($data['domain'])
        {
            $domain_data = array(
                'type' => $this->settings['domain_type']['site'],
                'from_id' => $site_id,
                'sub_domain' => $data['sub_weburl'],
                'domain' => $data['weburl'],
                'path' => $data['site_dir'],
            );
            if (!common::check_domain($domain_data))
            {
                $this->errorOutput("域名子域名已存在，请重新输入！");
            }
        }
        else if ($old_site_detail['domain'])
        {
            //删除这个栏目域名
            common::delete_domain($this->settings['domain_type']['site'], $site_id);
        }

        if (empty($data['support_client']))
        {
            $allclient = $this->obj->get_client();
            foreach ($allclient as $k => $v)
            {
                $client_ids .= $v['id'] . ',';
            }
            $client_ids             = trim($client_ids, ',');
            $data['support_client'] = $client_ids;
        }
        else
        {
            $data['support_client'] = implode(',', $data['support_client']);
        }

        //站点百度视频收录处理
        if ($data['is_video_record'])
        {
            $data['is_video_record']       = 1;
            $data['video_record_count']    = empty($this->input['video_record_count']) ? 500 : intval($this->input['video_record_count']);
            //建立视频收录目录
//			if($data['video_record_url'])
//			{
//				hg_mkdir($data['video_record_url']);
//			}
            $data['video_record_url']      = $this->input['video_record_url'];
            $data['video_update_peri']     = intval($this->input['video_update_peri']);
            $data['video_record_filename'] = $this->input['video_record_filename'];
        }

        if ($site_id)
        {
            //更新
            if (!$site = $this->obj->update_site($site_id, $data))
            {
                $this->errorOutput("更新失败！");
            }

            //更新domain
            if ($data['domain'])
            {
                common::update_domain($domain_data);
            }

            //查询出站点详细信息
            //$site_detail = $this->obj->get_site_by_id($site_id);
            //站点插入到cms并保存cms站点id
            $cms_site_data = array(
                'cms_siteid' => $site_id,
                'site_name' => $data['site_name'],
                'content' => $data['content'],
                'sitedir' => $data['site_dir'],
                'weburl' => 'http://' . ($data['sub_weburl'] ? $data['sub_weburl'] . '.' : '') . rtrim($data['weburl'], '/') . '/',
                'site_keywords' => $data['site_keywords'],
                'produce_format' => $data['produce_format'],
                'indexname' => $data['indexname'],
                'suffix' => $data['suffix'],
                'material_fmt' => $data['material_fmt'],
                'material_url' => $data['material_url'],
                'tem_material_url' => $data['tem_material_url'],
                'tem_material_dir' => $data['tem_material_dir'],
                'program_dir' => $data['program_dir'],
                'program_url' => $data['program_url'],
                'jsphpdir' => $data['jsphpdir'],
            );
            $cms_site_id   = $this->pub_cms->update_cms_site($cms_site_data);

            $allclient = $this->obj->get_client();

            //获取所有模块
            //$data['module'] = common::get_module();
            //获取站点可以支持的内容类型
            //$data['content_type'] = $this->pub_content->get_content_type_by_colid($site_id,'','1');

            $data['site']   = $site;
            $data['client'] = $allclient;
            $this->addLogs('更新站点', $old_site_detail, $data, $old_site_detail['site_name']);
        }
        else
        {
            $data['create_time'] = TIMENOW;
            $data['user_id']     = $this->user['user_id'];
            $data['user_name']   = $this->user['user_name'];
            //插入
            if ($site_id             = $this->obj->insert_site($data))
            {
                common::insert_domain($data + array('type' => $this->settings['domain_type']['site'], 'from_id' => $site_id, 'path' => $data['site_dir']));

                //站点插入到cms并保存cms站点id
                $cms_site_data = array(
                    'site_id' => $site_id,
                    'site_name' => $data['site_name'],
                    'content' => $data['content'],
                    'sitedir' => $data['site_dir'],
                    'weburl' => 'http://' . ($data['sub_weburl'] ? $data['sub_weburl'] . '.' : '') . rtrim($data['weburl'], '/') . '/',
                    'site_keywords' => $data['site_keywords'],
                    'material_fmt' => $data['material_fmt'],
                    'material_url' => $data['material_url'],
                    'tem_material_url' => $data['tem_material_url'],
                    'program_dir' => $data['program_dir'],
                    'program_url' => $data['program_url'],
                    'jsphpdir' => $data['jsphpdir'],
                );
                $cms_site_id   = $this->pub_cms->insert_cms_site($cms_site_data);
                //$this->obj->update_site($site_id,array('cms_site_id'=>$cms_site_id));
                $this->addLogs('新增站点', '', $data, $data['name']);
            }
            else
            {
                $this->errorOutput("添加失败！");
            }

            $allclient       = $this->obj->get_client();
            $data['client']  = $allclient;
            $data['site_id'] = $site_id;
        }
        if ($this->settings['is_syn_clouds'])
        {
            if (!$this->input['site_id'])
            {
                $syn_data['site_name'] = $data['site_name'];
            }
            else
            {
                $syn_data            = $this->obj->get_site_by_id($site_id, ' id,syn_id,site_name ');
                $syn_data = $syn_data+$data;
                $syn_data['id']      = $syn_data['syn_id'];
                $syn_data['site_id'] = $syn_data['syn_id'];
            }
            $this->syn_site($syn_data, $site_id);
        }
        $data['id'] = $site_id;
        $data['site_id'] = $site_id;
        $this->addItem($data);
        $this->output();
    }

    public function delete()
    {
        /*         * 权限判断 */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }
        $site_id = intval($this->input['site_id']);
        if ($site_id)
        {
            $site_detail = $this->obj->get_site_by_id($site_id);
            $this->obj->delete($site_id);

            //删除CMS站点
            $this->pub_cms->delete_cms_site($site_id);

            $this->addLogs('删除站点', $site_detail, '', $site_detail['site_name']);
            
            if($this->settings['is_syn_clouds'])
            {
                $this->syn_site($site_detail,'','delete');
            }
            
            $this->addItem('success');
            $this->output();
        }
        else
        {
            $this->errorOutput("删除失败！");
        }
    }

    public function check_domain()
    {
        $domain_data = array(
            'type' => $this->settings['domain_type']['site'],
            'from_id' => $this->input['site_id'],
            'sub_domain' => trim($this->input['sub_weburl'], '/'),
            'domain' => trim($this->input['weburl'], '/'),
            'path' => trim($this->input['site_dir'], '/'),
        );
        $result      = 1;
        if (!common::check_domain($domain_data))
        {
            $result = 0;
        }
        $this->addItem($result);
        $this->output();
    }

    public function get_pub_site()
    {
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 1000;
        $field = urldecode($this->input['field']) ? urldecode($this->input['field']) : '*';
        $limit = " limit $offset,$count";
        $condition = $this->get_condition();
        $site  = $this->obj->get_all_site($field, $limit, $condition);
        $this->addItem($site);
        $this->output();
    }

    public function get_pub_site_first()
    {
        $id                       = intval($this->input['site_id']);
        $field                    = urldecode($this->input['field']) ? urldecode($this->input['field']) : '*';
        $site                     = $this->obj->get_site_by_id($id, $field);
        $site['site_info']['url'] = mk_site_url($site);
        $this->addItem($site);
        $this->output();
    }

    //取多个栏目信息用此方法
    public function get_site_by_ids()
    {
        $result   = array();
        $field    = urldecode($this->input['field']) ? urldecode($this->input['field']) : ' * ';
        $site_ids = urldecode($this->input['site_ids']);
        $con      = '';
        if ($site_ids)
        {
            $con = ' AND id in(' . $site_ids . ')';
        }
        $sql  = "SELECT " . $field . " FROM " . DB_PREFIX . "site WHERE 1" . $con;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $result[$row['id']] = $row;
        }

        $this->addItem($result);
        $this->output();
    }

    //获取站点详情以及支持的客户端信息
    public function get_site_client()
    {
        $site_id = intval($this->input['site_id']);
        $field   = $this->input['field'] ? $this->input['field'] : '*';
        if (!$site_id)
        {
            $this->errorOutput('NO_SITE_ID');
        }
        $site = $this->obj->get_site_by_id($site_id, $field);
        if (!$site)
        {
            $this->errorOutput('NO_SITE_DATA');
        }
        $client_data   = $this->obj->get_client('*', $site['support_client'], 'id');
        $ret['site']   = $site;
        $ret['client'] = $client_data;
        $this->addItem($ret);
        $this->output();
    }

    public function get_sites()
    {
        $sql   = 'SELECT id,site_name FROM ' . DB_PREFIX . 'site ';
        $query = $this->db->query($sql);
        $sites = array();
        while ($row   = $this->db->fetch_array($query))
        {
            $sites[$row['id']] = $row['site_name'];
        }
        $this->addItem($sites);
        $this->output();
    }

    //权限使用
    public function get_authorized_site()
    {
        $cond = '';
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $col  = $this->user['prms']['publish_prms'];
            $site = $this->user['prms']['site_prms'] ? $this->user['prms']['site_prms'] : array();
            if ($col)
            {
                $col   = implode(',', $col);
                $sql   = 'SELECT site_id FROM ' . DB_PREFIX . 'column WHERE id IN(' . $col . ')';
                $query = $this->db->query($sql);
                while ($row   = $this->db->fetch_array($query))
                {
                    if (!in_array($row['site_id'], $site))
                    {
                        array_push($site, $row['site_id']);
                    }
                }
            }
            $cond = 'where id IN(' . implode(',', $site) . ')';
        }
        $sql   = 'SELECT id,site_name FROM ' . DB_PREFIX . 'site ' . $cond . ' ORDER BY id';
        $query = $this->db->query($sql);
        while ($row   = $this->db->fetch_array($query))
        {
            $this->addItem($row);
        }
        $this->output();
    }

    //网站域名的验证唯一
    public function web_confirm()
    {
        $domain = $this->input['domain'];
        if ($domain)
        {
            $sql = "SELECT id FROM " . DB_PREFIX . "domain WHERE domain = " . "'" . $domain . "'";
        }

        $r = $this->db->query_first($sql);
        if ($r)
        {
            $re = array('error' => '域名已存在，请重新填写');
            $this->addItem($re);
            $this->output();
        }
    }

    public function syn_site($data, $id = '',$type='')
    {
        include_once(CUR_CONF_PATH . 'lib/publishcontent_syn.class.php');
        $publishcontent_syn = new publishcontent_syn();
        if($type=='delete')
        {
            if($data['syn_id'])
            {
                $result             = $publishcontent_syn->syn_site_delete(array('site_id'=>$data['syn_id']));
            }
            return true;
        }
        else
        {
            unset($data['support_client'],$data['client']);
            $result             = $publishcontent_syn->syn_site($data);
        }
        
        if (!$result['site_id'])
        {
            $this->input['site_id']=$id;
            $this->delete();
            $this->errorOutput('NO_SYN'.  var_export($result,1));
        }
        if(!$data['id'])
        {
            $this->obj->update_site($id, array('syn_id' => $result['site_id']));
        }
        
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

$out    = new siteApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>