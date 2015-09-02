<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH . 'global.php');
require(CUR_CONF_PATH . "lib/functions.php");
define('MOD_UNIQUEID', 'publishsys'); //模块标识

class publishsysApi extends adminBase
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
        include_once(CUR_CONF_PATH . 'lib/mkpublish.class.php');
        $this->obj = new mkpublish();
        include_once(CUR_CONF_PATH . 'lib/common.php');
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    public function show(){}

    public function get_page_manage()
    {
        $site_id = intval($this->input['site_id']);
        $page_id = $this->input['page_id'];
        $key     = ($this->input['key']);
        $result  = common::get_page_manage($site_id, $page_id, $key);
        $this->addItem($result);
        $this->output();
    }

    public function get_page_data()
    {
        $page_id      = ($this->input['page_id']);
        $offset       = ($this->input['offset']);
        $count        = ($this->input['count']);
        $fid          = ($this->input['fid']);
        $pinfo        = ($this->input['pinfo']);
        $page_data_id = ($this->input['page_data_id']);
        $result       = common::get_page_data($page_id, $offset, $count, $fid, $pinfo, $page_data_id);
        $this->addItem($result);
        $this->output();
    }

    public function merge_cell()
    {
        $site_id      = ($this->input['site_id']);
        $page_id      = ($this->input['page_id']);
        $content_type = ($this->input['content_type']);
        $site         = ($this->input['site']);
        $page_data_id = ($this->input['page_data_id']);
        $result       = common::merge_cell($site_id, $page_id, $page_data_id, $content_type, $site);
        $this->addItem($result);
        $this->output();
    }

    public function get_template_cache()
    {
        $site_id       = ($this->input['site_id']);
        $template_sign = ($this->input['template_sign']);
        $curr_style    = ($this->input['curr_style']);
        $material_url  = ($this->input['material_url']);
        $result        = common::get_template_cache($template_sign, $curr_style, $site_id, $material_url  = '');
        $this->addItem($result);
        $this->output();
    }

    public function get_mode_infos()
    {
        $ids    = ($this->input['ids']);
        $cssurl = ($this->input['cssurl']);
        $key    = ($this->input['key']);
        $result = common::get_mode_infos($ids, $cssurl = '', $key    = '');
        $this->addItem($result);
        $this->output();
    }

    public function get_mode_variable()
    {
        $ids    = ($this->input['ids']);
        $result = common::get_mode_variable($ids);
        $this->addItem($result);
        $this->output();
    }

    public function get_datasource_by_sign()
    {
        $type      = ($this->input['type']);
        $page_sign = ($this->input['page_sign']);
        $result    = common::get_datasource_by_sign($type, $page_sign);
        $this->addItem($result);
        $this->output();
    }

    public function get_content_by_datasource()
    {
        $id     = ($this->input['id']);
        $data   = ($this->input['data']);
        $result = common::get_content_by_datasource($id, $data);
        $this->addItem($result);
        $this->output();
    }

    public function get_cell_cache()
    {
        $id      = ($this->input['id']);
        $cell_id = ($this->input['cell_id']);
        $result  = common::get_cell_cache($cell_id);
        $this->addItem($result);
        $this->output();
    }

    public function datasource_param()
    {
        $datasourceid = ($this->input['datasourceid']);
        if (!$datasourceid)
        {
            $this->addItem(array());
            $this->output();
        }
        $ret      = $result   = $ds_datas = array();
        $sql      = "SELECT * FROM " . DB_PREFIX . "data_source  WHERE id in(" . implode(',', $datasourceid) . ")";
        $info     = $this->db->query($sql);
        while ($row      = $this->db->fetch_array($info))
        {
            $row['argument']      = $row['argument'] ? unserialize($row['argument']) : array();
            $ret[$row['id']]      = $row['argument'];
            $ds_datas[$row['id']] = $row;
        }
        if ($ret)
        {
            foreach ($ret as $k => $v)
            {
                if (!$v['ident'])
                {
                    continue;
                }
                foreach ($v['ident'] as $kk => $vv)
                {
                    $result[$k][$vv] = $v['value'][$kk];
                }
            }
        }
        $r['ds_params'] = $result;
        $r['ds_datas']  = $ds_datas;
        $this->addItem($r);
        $this->output();
    }

    public function get_page_by_sign()
    {
        $sign = $this->input['sign'];
        $site_id = intval($this->input['site_id']);
        $sql  = "SELECT * FROM " . DB_PREFIX . "page_manage WHERE sign = '" . $sign . "'";
        if($site_id)
        {
            $sql .= " AND site_id=".$site_id;
        }
        $ret  = $this->db->query_first($sql);
        $this->addItem($ret);
        $this->output();
    }

    public function get_page_by_id()
    {
        $id     = ($this->input['id']);
        $in     = ($this->input['in']);
        $key    = ($this->input['key']);
        $result = common::get_page_by_id($id, $in, $key);
        $this->addItem($result);
        $this->output();
    }

    public function get_cell_data()
    {
        $cell_id = intval($this->input['cell_id']);
        if(!$cell_id)
        {
            $this->errorOutput('NO_CELL_ID');
        }
        include_once(CUR_CONF_PATH . 'lib/cell.class.php');
        $cell    = new cell();
        $data = $cell->getCellData($cell_id);
        $this->addItem($data);
        $this->output();
    }


    public function pageNode()
    {
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->pub_config = new publishconfig();
        include_once(CUR_CONF_PATH . 'lib/common.php');
        $data = array();

        $fid = ($this->input['fid']) ? ($this->input['fid']) : '';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 1000;
        $data['item'] = $total = array();
        if (strstr($fid, "page_id") !== false)
        {
            //点击的页面类型
            $page_id   = str_replace('page_id', '', $fid);
            $get_page  = explode($this->settings['separator'], $page_id);
            $page_data = common::get_page_data($get_page[1], $offset, $count);
            foreach ($page_data['page_data'] as $k => $v)
            {
                $m_id         = 'page_data_id' . $get_page[0] . $this->settings['separator'] . $page_data['page_info']['id'] . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['name'];
                $page           = array('id' => $m_id, "name" => $v['name'], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1);
                $page['is_last'] = $v['is_last'];
                $data['item'][] = $page;
            }
        }
        else if (strstr($fid, "page_data_id") !== false)
        {
            //点击的页面数据
            $page_data_id = str_replace('page_data_id', '', $fid);
            $get_page     = explode($this->settings['separator'], $page_data_id);
            $page_data    = common::get_page_data($get_page[1], $offset, $count, $get_page[2]);
            foreach ($page_data['page_data'] as $k => $v)
            {
                $m_id         = 'page_data_id' . $get_page[0] . $this->settings['separator']  . $page_data['page_info']['id'] . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['name'];
                $column            = array('id' => $m_id, "name" => $v['name'], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1);
                $column['is_last'] = $v['is_last'];
                $data['item'][] = $column;
            }
        }
        else if ($fid)
        {
            //点击的站点
            $site_id = intval(str_replace('site', '', $fid));
            if (!$site_id)
            {
                $this->errorOutput('NO_SITE_ID');
            }
            $get_page  = explode($this->settings['separator'], $site_id);
            $page_type = common::get_page_manage($get_page[0]);

            foreach ($page_type as $k => $v)
            {
//                if ($v['sign'] == 'special')
//                {
//                    continue;
//                }
                $page = array('id' => 'page_id' . $site_id . $this->settings['separator'] . $v['id'] . $this->settings['separator'] . $v['title'], "name" => $v['title'], "fid" => 'page_id' . $v['id'], "depth" => 1);
                $page_data = common::get_page_data($v['id'], 0, 1);
                if (empty($page_data['page_data']))
                {
                    $page['is_last'] = 1;
                }
                else
                {
                    $page['is_last'] = 0;
                }
                $data['item'][] = $page;
            }
        }
        else
        {
            $sites = $this->pub_config->get_site(' id,site_name ', $offset, $count, '', $this->input['key']);
            if (is_array($sites) && count($sites) > 0)
            {
                foreach ((array)$sites as $k => $v)
                {
                    $site         = array('id' => 'site' . $v['id'] . $this->settings['separator'] . $v['site_name'], "name" => $v['site_name'], "fid" => 0, "depth" => 1);
                    //获取页面类型
                    $page_type = common::get_page_manage($v['id']);
                    if (empty($page_type))
                    {
                        $site['is_last'] = 1;
                    }
                    else
                    {
                        $site['is_last'] = 0;
                    }
                    $data['item'][] = $site;
                }
            }
            $total = $this->pub_config->get_site_count($this->input['key']);
        }
        if ($total)
        {
            $pagearr = array();
            $pagearr['total'] = isset($total['total']) ? $total['total'] : 0;
            $pagearr['page_num'] = $count;
            $pagearr['total_num'] = $pagearr['total'];
            $pagearr['total_page'] = ceil($pagearr['total']/$count);
            $pagearr['current_page'] = floor($offset/$count) + 1;
            $data['page'] = $pagearr;
        }
        $this->addItem($data);
        $this->output();
    }

}

$out    = new publishsysApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
