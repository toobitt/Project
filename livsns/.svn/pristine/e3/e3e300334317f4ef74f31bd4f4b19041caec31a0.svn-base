<?php

require('global.php');
define('MOD_UNIQUEID', 'block'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/data_source.class.php');

class blockApi extends adminBase
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
        $this->mPrmsMethods = array(
            'manage' => '管理',
            '_node' => array(
                'name' => '页面',
                'filename' => 'block_page_node.php',
                'node_uniqueid' => 'block',),
        );
        parent::__construct();
        $this->pub_content  = new publishcontent();
        $this->pub_config   = new publishconfig();
        $this->data_source  = new dataSource();
        include(CUR_CONF_PATH . 'lib/block.class.php');
        $this->obj          = new block();
        include(CUR_CONF_PATH . 'lib/block_set.class.php');
        $this->block_set    = new block_set();
        include(CUR_CONF_PATH . 'lib/block_sort.class.php');
        $this->block_sort   = new block_sort();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            if (!$node || !is_array($node))
            {
                $this->errorOutput("NO_PRIVILEGE");
            }
        }
        else
        {
            $node = 'all';
        }

        $offset      = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count       = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $client_type = $this->input['client_type'] ? intval(urldecode($this->input['client_type'])) : 2;

        $res = $this->obj->get_block_relation($client_type, $offset, $count, $node);

        //取内容类型
        $content_type = $this->pub_content->get_all_content_type();

        $r['page']         = $res['page'];
        $r['block']        = $res['block'];
        $r['content_type'] = $content_type;
        $this->addItem($r);
        $this->output();
    }

    public function get_block()
    {
        $columns    = array();
        $id         = intval($this->input['_id']);
        //查询出站点下模块的内容
        $offset     = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count      = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
        $con        = $this->get_condition();
        $block_data = $this->obj->get_block($con['condition'], $offset, $count);
        //查询栏目的名称
//		if($block_data['block_record'])
//		{
//			foreach($block_data['block_record'] as $v)
//			{
//				foreach($v as $vv)
//				{
//					$column_ids .= ','.$vv;
//				}
//			}
//			$columns = $this->pub_config->get_columnname_by_ids('id,name',trim($column_ids,','));
//		}
        //查出所有app标识
        $apps       = $this->pub_content->get_app();

        $result['block']   = $block_data;
        $result['column']  = $columns;
        $result['app']     = $apps;
        $result['sort_id'] = $id;
        $this->addItem($result);
        $this->output();
    }

    public function count()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            $node = is_array($node) ? $node : array();
            if (!$node || !is_array($node))
            {
                $this->errorOutput("NO_PRIVILEGE");
            }
        }
        else
        {
            $node = 'all';
        }
        $page        = array();
        $client_type = $this->input['client_type'] ? intval(urldecode($this->input['client_type'])) : 2;
        $sql         = "SELECT *,count(distinct site_id,page_id,page_data_id,content_type,client_type) as total FROM " . DB_PREFIX . "block_relation WHERE 1";
        if ($client_type)
        {
            $sql .= " AND client_type=" . $client_type;
        }
        $sql .= " group by site_id,page_id,page_data_id,content_type,client_type";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $idstr = $row['site_id'] . '_' . $row['page_id'] . '_' . $row['page_data_id'];
            if ($node != 'all')
            {
                if (!in_array($idstr, $node))
                {
                    continue;
                }
            }
            $page[] = $row;
        }
        echo json_encode(array('total' => count($page)));
    }

    /**
     * 

      private function get_condition()
      {
      if ($this->input['is_support_push'])
      {
      $condition = ' AND b.is_support_push=1 ';
      }
      else
      {
      $condition = ' AND b.group_id=b.id ';
      }
      $id = intval($this->input['_id']);
      if (!empty($id))
      {
      $sort = $this->block_sort->get_sort_by_id($id);
      if (empty($sort['childs']))
      {
      $this->errorOutput('请选择区块分类');
      }
      else
      {
      $condition .= " AND b.sort_id in (" . $sort['childs'] . ")";
      }
      }
      if ($keyword = urldecode($this->input['keyword']))
      {
      $condition .= " AND b.name like '%" . $keyword . "%'";
      }
      $result['condition'] = $condition;
      return $result;
      }
     * */
    private function get_condition()
    {
        if ($this->input['is_support_push'])
        {
            $condition = ' AND b.is_support_push=1 ';
        }
        else
        {
            $condition = ' AND b.group_id=b.id ';
        }
        $id = ($this->input['_id']);
        if ($id != '')
        {
            if (strstr($id, "site") !== false)
            {
                $site_id     = str_replace('site', '', $id);
                $get_page    = explode($this->settings['separator'], $site_id);
                $site_id     = $get_page[0];
                $expand_name = $get_page[1];
                $condition .= " AND r.site_id=" . $get_page[0];
            }
            else if (strstr($id, "page_id") !== false)
            {
                $page_id     = str_replace('page_id', '', $id);
                $get_page    = explode($this->settings['separator'], $page_id);
                $page_id     = $get_page[0];
                $expand_name = $get_page[1];
                $condition .= " AND r.page_id=" . $get_page[0];
            }
            else if (strstr($id, "page_data_id") !== false)
            {
                $page_data_id = str_replace('page_data_id', '', $id);
                $get_page     = explode($this->settings['separator'], $page_data_id);
                $page_id      = $get_page[0];
                $page_data_id = $get_page[1];
                $expand_name  = $get_page[2];
                $condition .= " AND r.page_id=" . $get_page[0] . " AND r.page_data_id in(" . $get_page[1] . ")";
            }
        }
        if ($keyword = urldecode($this->input['keyword']))
        {
            $condition .= " AND b.name like '%" . $keyword . "%'";
        }
        $condition .= " GROUP BY r.block_id";
        $result['condition']    = $condition;
        $result['site_id']      = $site_id;
        $result['page_id']      = $page_id;
        $result['page_data_id'] = $page_data_id;
        $result['expand_name']  = $expand_name;
        return $result;
    }

    public function block_form()
    {
        $data                 = $datasource_info_data = array();
        $id                   = intval($this->input['id']);
        $sort_id              = intval($this->input['sort_id']);
        if (empty($id))
        {
            if (!$sort_id)
            {
                $this->errorOutput('请选择区块分类');
            }
        }
        if ($id)
        {
            $data = $this->obj->get_block_first($id);

            if ($data['datasource_id'])
            {
                $datasource_info_data = $this->data_source->get_datasource_info($data['datasource_id']);
                if ($data['datasource_argument'])
                {
                    $data['datasource_argument'] = unserialize($data['datasource_argument']);
                }
            }
        }

        //取出数据源
        $datasource = $this->data_source->showDataSource();

        $data['datasource_data']      = $datasource['datasource_data'];
        $data['datasource_info_data'] = $datasource_info_data;
        $data['app_data']             = $datasource['app_data'];
        $data['sort_id']              = $sort_id;
        $this->addItem($data);
        $this->output();
    }

    public function create()
    {
        $data = array(
            'is_from_cell' => intval($this->input['is_from_cell']), //表示从前端部署来添加
            'block_id' => intval($this->input['block_id']),
            'sort_id' => intval($this->input['sort_id']),
            'site_id' => intval($this->input['site_id']),
            'page_id' => intval($this->input['page_id']),
            'page_data_id' => intval($this->input['page_data_id']),
            'content_type' => intval($this->input['content_type']),
            'client_type' => intval($this->input['client_type']),
            'expand_name' => ($this->input['expand_name']),
            'name' => urldecode($this->input['name']),
            'update_time' => intval($this->input['update_time']),
            'update_type' => intval($this->input['update_type']),
            'datasource_id' => intval($this->input['datasource_id']),
            'width' => intval($this->input['width']),
            'height' => intval($this->input['height']),
            'line_num' => intval($this->input['line_num']),
            'father_tag' => urldecode($this->input['father_tag']),
            'loop_body' => urldecode($this->input['loop_body']),
            'last_update_time' => TIMENOW,
            'next_update_time' => TIMENOW + intval($this->input['update_time']),
            'is_support_push' => intval($this->input['is_support_push']),
        );
        include_once(CUR_CONF_PATH . 'lib/common.php');
        common::insert_block($data, false);
    }

    public function update()
    {
        $id = intval($this->input['id']);
        if (!$id)
        {
            $this->errorOutput('更新失败');
        }
        $data = array(
            'name' => urldecode($this->input['name']),
            'update_time' => intval($this->input['update_time']),
            'update_type' => intval($this->input['update_type']),
            'datasource_id' => intval($this->input['datasource_id']),
            'width' => intval($this->input['width']),
            'height' => intval($this->input['height']),
            'line_num' => intval($this->input['line_num']),
            'father_tag' => urldecode($this->input['father_tag']),
            'loop_body' => urldecode($this->input['loop_body']),
            'is_support_push' => intval($this->input['is_support_push']),
            'last_update_time' => TIMENOW,
        );
        if (!$data['name'])
        {
            $this->errorOutput('信息未填全');
        }

        //判断有没有数据源id，如果有则取设定的参数
        if ($data['datasource_id'])
        {
            $datasource_info_data = $this->data_source->get_datasource_info($data['datasource_id']);
            $datasource_arg       = $datasource_info_data['argument'];
            foreach ($datasource_arg['ident'] as $k => $v)
            {
                $datasource_argarr[$v] = urldecode($this->input['argument_' . $v]);
            }
        }
        $data['datasource_argument'] = $datasource_argarr ? serialize($datasource_argarr) : '';

        $old_block_data = $this->obj->get_block_first($id);

        //根据栏目id查出站点id
        $this->obj->update($data, $id);

        $data                 = $this->obj->get_block_first($id);
        $datasource_info_data = array();
        if ($data['datasource_id'])
        {
            $datasource_info_data = $this->data_source->get_datasource_info($data['datasource_id']);
            if ($data['datasource_argument'])
            {
                $data['datasource_argument'] = unserialize($data['datasource_argument']);
            }
        }
        //取出数据源
        $datasource = $this->data_source->showDataSource();

        $data['site_id']              = intval($this->input['site_id']);
        $data['page_id']              = intval($this->input['page_id']);
        $data['page_data_id']         = intval($this->input['page_data_id']);
        $data['expand_name']          = $this->input['expand_name'];
        $data['datasource_data']      = $datasource['datasource_data'];
        $data['datasource_info_data'] = $datasource_info_data;
        $data['app_data']             = $datasource['app_data'];
        $this->addItem($data);
        $this->output();
    }

    public function delete()
    {
        $ids = urldecode($this->input['id']);
        if (!$ids)
        {
            $this->errorOutput('删除失败');
        }
        $this->obj->delete($ids);
        $this->outItem('success');
        $this->output();
    }

    public function get_datasource_info()
    {
        $id   = intval($this->input['id']);
        $data = $this->data_source->get_datasource_info($id);
        $this->addItem($data);
        $this->output();
    }

    /**
      public function get_block()
      {
      $data = array();



      //$data = $this->obj->get_all_block();
      $this->addItem($data);
      $this->output();
      }
     */

    /**
     * 取区块数据（魔力视图）
     */
    public function get_block_data_and_line_info()
    {
        $block_id = intval($this->input['block_id']);
        if (!$block_id)
        {
            $this->errorOutput('NO ID');
        }
        if (isset($this->input['url']))
        {
            include_once(CUR_CONF_PATH . 'lib/cache.class.php');
            $cache = new Cache();
            $cache->initialize(BLOCK_CACHE);
            $ret   = $cache->get($block_id);
            if ($ret == 'no_file_dir')
            {
                //取区块信息
                $block_data        = $this->obj->get_block_first($block_id);
                //取区块每行内容
                $ret               = array();
                $ret['content']    = $this->block_set->get_block_content($block_id, $block_data['line_num'], $this->input['url']);
                //取区块每行信息
                $ret['block_line'] = $this->block_set->get_block_line($block_id, $this->input['url']);
                $cache->initialize(BLOCK_CACHE);
                $cache->set($block_id, $ret);
            }
        }
        else
        {
            //取区块信息
            $block_data        = $this->obj->get_block_first($block_id);
            //取区块每行内容
            $ret               = array();
            $ret['content']    = $this->block_set->get_block_content($block_id, $block_data['line_num'], $this->input['url']);
            //取区块每行信息
            $ret['block_line'] = $this->block_set->get_block_line($block_id, $this->input['url']);
        }
        $this->addItem($ret);
        $this->output();
    }

    public function get_block_content()
    {
        $data     = array();
        $block_id = intval($this->input['block_id']);
        $line_num = intval($this->input['line_num']);
        if (!$line_num)
        {
            //取区块信息
            $block_data = $this->obj->get_block_first($block_id);
        }
        //取区块每行内容
        $content_data = $this->block_set->get_block_content($block_id, $line_num ? $line_num : $block_data['line_num']);
        $this->addItem($content_data);
        $this->output();
    }

    public function get_block_content_html()
    {
        $data       = array();
        $str        = '';
        $block_id   = intval($this->input['block_id']);
//		$line_num = intval($this->input['line_num']);
        $pic_width  = urldecode($this->input['pic_width']);
        $pic_height = urldecode($this->input['pic_height']);
        $title_num  = urldecode($this->input['title_num']);
        $brief_num  = urldecode($this->input['brief_num']);
        if (!$block_id)
        {
            $result = array(
                'error' => '未传区块',
            );
            $this->addItem($result);
            $this->output();
        }
        //取区块信息
        $block_data   = $this->obj->get_block_first($block_id);
        //取区块每行内容
        $content_data = $this->block_set->get_block_content($block_id, $block_data['line_num']);
        //取区块每行信息
        $line_data    = $this->block_set->get_block_line($block_id);

        //父标签
        $str = "<" . $block_data['father_tag'] . " style='";
        if ($block_data['width'])
        {
            $str .= "width:" . $block_data['width'] . "px;";
        }
        if ($block_data['height'])
        {
            $str .= "height:" . $block_data['height'] . "px;";
        }
        $str .= "'>";

        //每行
        foreach ($line_data as $k => $v)
        {
            if (!empty($content_data[$k]))
            {
                foreach ($content_data[$k] as $kk => $vv)
                {
                    $title   = $title_num ? hg_cutchars($vv['title'], $title_num, '') : $vv['title'];
                    $brief   = $brief_num ? hg_cutchars($vv['brief'], $brief_num, '') : $vv['brief'];
                    $outlink = $vv['outlink'];
                    if (!empty($vv['indexpic']))
                    {
                        $pic_data = unserialize($vv['indexpic']);
                        $indexpic = $pic_data['host'] . $pic_data['dir'];
                        if ($pic_width && !$pic_height)
                        {
                            $indexpic .= $pic_width . "x" . "0/";
                        }
                        else if ($pic_width && $pic_height)
                        {
                            $indexpic .= $pic_width . "x" . $pic_height . "/";
                        }
                        $indexpic .= $pic_data['filepath'] . $pic_data['filename'];
                    }
                    $loop_body = $v['loop_body'] ? $v['loop_body'] : $block_data['loop_body'];
//					print_r($loop_body);exit;
                    eval("\$li_data = \"$loop_body\";");
                    //生成样式
                    $style     = " style='";
                    if ($v['width'])
                    {
                        $style .= "width:" . $v['width'] . "px;";
                    }
                    if ($v['height'])
                    {
                        $style .= "height:" . $v['height'] . "px;";
                    }
                    $style .= "'";

                    if (!$insert_i = stripos($v['loop_body'], '>'))
                    {
                        $result = array(
                            'error' => '未找到区块样式的标识',
                        );
                        $this->addItem($result);
                        $this->output();
                    }

                    //插入样式
                    $str .= str_insert($li_data, $insert_i, $style);
                }
            }
        }
        $str .= "</" . $block_data['father_tag'] . ">";

        $this->addItem($str);
        $this->output();
    }

    //取区块列表(单元里取区块列表)
    public function get_block_list()
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "block  WHERE group_id=id ORDER BY id ASC";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $this->addItem($row);
        }
        $this->output();
    }

    //取区块详细信息(单元里取区块信息)
    public function get_block_info()
    {
        $block_id = intval($this->input['block_id']);
        $sql      = "SELECT * FROM " . DB_PREFIX . "block WHERE id = " . $block_id;
        $info     = $this->db->query_first($sql);
        $this->addItem($info);
        $this->output();
    }

    public function insert_block_content()
    {
        $getdata  = $this->input['data'];
        $block_id = $getdata['block_id'];
        if (!$block_id)
        {
            $this->errorOutput('NO_BLOCK_ID');
        }
        $data['content_id']     = intval($getdata['content_id']);
        $data['cid']            = intval($getdata['cid']);
        $data['content_fromid'] = intval($getdata['content_fromid']);
        $data['bundle_id']      = ($getdata['bundle_id']);
        $data['module_id']      = ($getdata['module_id']);
        $data['title']          = $getdata['title'];
        $data['brief']          = $getdata['brief'];
        $data['outlink']        = $getdata['outlink'];
        $data['indexpic']       = serialize($getdata['indexpic']);
        $block_id_arr           = explode(',', $block_id);
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache                  = new Cache();
        foreach ($block_id_arr as $v)
        {
            $insert_data['line']       = 1;
            $insert_data['child_line'] = 1;
            $insert_data['block_id']   = $v;
            $this->block_set->insert_child_content($data + $insert_data, true);

            $cache->initialize(BLOCK_CACHE);
            $cache->delete($v);
        }
        //更新这条内容
        //$this->block_set->update_content($data['content_id'], $data, false);
        $this->addItem('success');
        $this->output();
    }

    public function update_block_content()
    {
        $getdata   = $this->input['data'];
        $data = array(
        'content_id' => $getdata['content_id'],
        'cid' => $getdata['cid'],
        'content_fromid' => intval($getdata['content_fromid']),
        'bundle_id' => ($getdata['bundle_id']),
        'module_id' => ($getdata['module_id']),
        'title' => $getdata['title'],
        'brief' => $getdata['brief'],
        'indexpic' => serialize($getdata['indexpic']),
        'outlink' => $getdata['outlink'],
        );
        //更新这条内容
        $block_ids = $this->block_set->update_content($data['cid'], $data, false);

        if ($block_ids && is_array($block_ids))
        {
            include_once(CUR_CONF_PATH . 'lib/cache.class.php');
            $cache = new Cache();
            foreach ($block_ids as $v)
            {
                $cache->initialize(BLOCK_CACHE);
                $cache->delete($v);
            }
        }

        $this->addItem('success');
        $this->output();
    }

    public function delete_block_content()
    {
        $block_id   = $this->input['block_id'];
        $content_id = intval($this->input['content_id']);
        if (!$content_id)
        {
            $this->errorOutput('NO_CONTENT_ID');
        }
        $block_content = $this->block_set->get_block_content_by_con($block_id, $content_id);
        if ($block_content)
        {
            //删除当前区块下的这条内容
            foreach ($block_content as $v)
            {
                $this->block_set->delete_content($v);
            }
        }
        $block_id_arr = explode(',', $block_id);
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache        = new Cache();
        foreach ($block_id_arr as $v)
        {
            $cache->initialize(BLOCK_CACHE);
            $cache->delete($v);
        }
        $this->addItem('success');
        $this->output();
    }

    /**
     * 空方法
     * @name unknow
     * @access public
     * @author repheal
     * @category hogesoft
     * @copyright 	ho	gesoft
     */
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new blockApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>