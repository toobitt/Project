<?php

require('global.php');
define('MOD_UNIQUEID', 'block'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/data_source.class.php');

class block_updateApi extends adminBase
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
                'name' => '栏目',
                'node_uniqueid' => 'cloumn_node',
            ),
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
    
    /**
     * insert区块（魔力视图）
     */
    public function create()
    {
        $data     = array(
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
            'datasource_argument' => $this->input['datasource_argument'],
        );
        include_once(CUR_CONF_PATH . 'lib/common.php');
        $response = common::insert_block($data, false);
        $this->addItem($response);
        $this->output();
    }
    
    /**
     * update区块（魔力视图）
     */
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
            'datasource_argument' => $this->input['datasource_argument'] ? serialize($this->input['datasource_argument']) : '',
        );

        $old_block_data = $this->obj->get_block_first($id);

        $this->obj->update($data, $id);

        $line_data = array(
            'block_id' => $id,
        );
        $this->block_set->insert_line($data['line_num'], $line_data);

        if ($data['datasource_id'])
        {
            if (!$old_block_data['datasource_id'] || $old_block_data['datasource_id'] != $data['datasource_id'])
            {
                include_once(ROOT_PATH . 'lib/class/data_source.class.php');
                $data_source       = new dataSource();
                $content_data      = $data_source->get_content_by_datasource($data['datasource_id'], $data['datasource_argument']);
                $new_content_idarr = array();
                foreach ($content_data as $kkk => $vvv)
                {
                    $vvv['id'] && ($new_content_idarr[] = $vvv['id']);
                }

                //查询出这个区块的现有所有内容id
                $new_content_idstr = implode(',', $new_content_idarr);
                $contentidarr      = array();
                if ($new_content_idstr)
                {
                    $contentidarr = $this->block_set->get_content_by_content_ids($id, $new_content_idstr);
                }
                foreach ($content_data as $kkk => $vvv)
                {
                    if ($contentidarr[$vvv['id']])
                    {
                        unset($content_data[$kkk]);
                    }
                }
                if (is_array($content_data) && $content_data)
                {
                    $this->block_set->insert_content($data['line_num'], $id, $content_data);
                }
            }
        }
        
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache  = new Cache();
        $cache->initialize(BLOCK_CACHE);
        $cache->delete($id);

        $data                 = $this->obj->get_block_first($id);
        $datasource_info_data = array();
        
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
    
    /**
     * 删除区块（魔力视图）
     */
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
        $data['content_id'] = intval($getdata['content_id']);
        $data['title']      = $getdata['title'];
        $data['brief']      = $getdata['brief'];
        $data['outlink']    = $getdata['outlink'];
        $data['indexpic']   = serialize($getdata['indexpic']);
        $block_id_arr       = explode(',', $block_id);
        foreach ($block_id_arr as $v)
        {
            $insert_data['line']       = 1;
            $insert_data['child_line'] = 1;
            $insert_data['block_id']   = $v;
            $this->block_set->insert_child_content($data + $insert_data, true);
        }
        //更新这条内容
        $this->block_set->update_content($data['content_id'], $data, false);
        $this->addItem('success');
        $this->output();
    }

    public function update_block_content()
    {
        $getdata = $this->input['data'];
        $data    = array(
            'content_id' => $getdata['content_id'],
            'title' => $getdata['title'],
            'brief' => $getdata['brief'],
            'indexpic' => serialize($getdata['indexpic']),
            'outlink' => $getdata['outlink'],
        );
        //更新这条内容
        $this->block_set->update_content($data['content_id'], $data, false);
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
        $this->addItem('success');
        $this->output();
    }
    
    public function update_indexpic()
    {
        $block_id = intval($this->input['block_id']);
        $site_id = intval($this->input['site_id']);
        $page_id = intval($this->input['page_id']);
        $page_data_id = intval($this->input['page_data_id']);
        $content_type = intval($this->input['content_type']);
        $client_type  = intval($this->input['client_type']);
        $indexpic = $this->input['indexpic'];
        if($indexpic)
        {
            include_once(ROOT_PATH.'lib/class/material.class.php');
            $mate = new material();
            $pic = $mate->imgdata2pic($indexpic);
            $pic = $pic[0];
            if($pic && is_array($pic))
            {
                $p['host'] = $pic['host'];
                $p['dir'] = $pic['dir'];
                $p['filepath'] = $pic['filepath'];
                $p['filename'] = $pic['filename'];
            }
        }
        if(!$p)
        {
            $this->addItem('false');
            $this->output();
        }
        if ($block_id)
        {
            $this->obj->update(array('indexpic'=>serialize($p)), $block_id, 'block');
        }
        else if($site_id)
        {
            $this->obj->update_block_relation(array('indexpic'=>serialize($p)),$site_id,$page_id,$page_data_id,$content_type,$client_type);
        }
        $this->addItem('true');
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

$out    = new block_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'check_update_content';
}
$out->$action();
?>