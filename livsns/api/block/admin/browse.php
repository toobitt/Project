<?php

require('global.php');
define('MOD_UNIQUEID', 'publishcontent_block_set'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishsys.class.php');

class browseApi extends adminBase
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
        include(CUR_CONF_PATH . 'lib/block_set.class.php');
        $this->obj   = new block_set();
        include(CUR_CONF_PATH . 'lib/block.class.php');
        $this->block = new block();
        include(ROOT_PATH.'lib/class/publishplan.class.php');
        $this->pub_plan = new publishplan();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $this->pub_sys      = new publishsys();
        $datasource_info    = $block_content_info = $block_line_data    = array();
        $id                 = intval($this->input['id']);
        $line               = intval($this->input['line']);
        if (!$id)
        {
            $this->errorOutput('没有选择区块');
        }
        $offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count  = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;

        //查询区块详细信息
        $block_info = $this->block->get_block_first($id);

        if ($block_info['datasource_id'])
        {
            //查询数据源对应的参数
            $datasource_info = $this->pub_sys->get_datasource_info($block_info['datasource_id']);
        }
        if (urldecode($this->input['argument_to_search']))
        {
            //根据数据源参数查内容  argument_to_search表示从browse查询过来
            if (!empty($datasource_info['argument']['ident']))
            {
                foreach ($datasource_info['argument']['ident'] as $k => $v)
                {
                    $arg = urldecode($this->input['argument_' . $v]);
                    if ($arg)
                    {
                        $serach_data[$v] = $arg;
                    }
                }
            }
        }
        else
        {
            //根据数据库里设置的参数取数据源内容
            if ($block_info['datasource_argument'])
            {
                $serach_data                 = unserialize($block_info['datasource_argument']);
                $default_datasource_argument = $serach_data;
            }
        }

        //根据数据源获取内容
        $content_data            = $this->pub_sys->queryDataSource($block_info['datasource_id'], $serach_data);
        //根据数据源获取内容条数
        $serach_data['is_count'] = 1;
        $count                   = $this->pub_sys->queryDataSource($block_info['datasource_id'], $serach_data);

        if ($block_content_id = intval($this->input['block_content_id']))
        {
            $block_content_info = $this->obj->get_block_content_info($block_content_id);
            if (!empty($block_content_info['indexpic']))
            {
                $indexpic_arr                   = unserialize($block_content_info['indexpic']);
                $block_content_info['indexpic'] = $indexpic_arr['host'] . $indexpic_arr['dir'] . $indexpic_arr['filepath'] . $indexpic_arr['filename'];
            }
        }

        //查询行属性
        if ($line)
        {
            $block_line_data = $this->obj->get_block_line_first($id, $line);
        }

        $data['default_datasource_argument'] = empty($default_datasource_argument) ? array() : $default_datasource_argument;
        $data['datasource_info']             = $datasource_info;
        $data['content_data']                = $content_data;
        $data['count']                       = $count;
        $data['block_content_info']          = $block_content_info;
        $data['block_line_data']             = $block_line_data;
        $data['id']                          = $id;
        $data['line']                        = $line;
//		print_r($data);exit;
        $this->addItem($data);
        $this->output();
    }

    public function count()
    {
//		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."block WHERE 1 ".$this->get_condition();
//		echo json_encode($this->db->query_first($sql));
        echo json_encode(array('total' => 30));
    }

    private function get_condition()
    {
        $condition = '';
        return $condition;
    }
    
    public function content_create()
    {
        $data             = array(
            'title' => urldecode($this->input['title']),
            'brief' => trim(urldecode($this->input['brief'])),
            'outlink' => urldecode($this->input['outlink']),
            'indexpic' => urldecode($this->input['indexpic']),
            //对内容样式操作可能不添加字段  直接添加到内容里
            'font_color' => urldecode($this->input['font_color']),
            'font_b' => urldecode($this->input['font_b']),
            'font_size' => urldecode($this->input['font_size']),
            'font_border' => urldecode($this->input['font_border']),
            'font_backcolor' => urldecode($this->input['font_backcolor']),
        );
        if ($block_content_id = intval($this->input['id']))
        {
            //修改内容
            //如图片修改 则再次上传赋值
            $pic_data = $this->insert_pic();
            if (!empty($pic_data))
            {
                $data['indexpic'] = serialize($pic_data);
            }
            $this->obj->update_content($block_content_id, $data);
            $this->addItem('success');
            $this->output();
        }
        else
        {
            //增加内容
            $id                   = intval($this->input['block_id']);
            $line                 = intval($this->input['line']);
            $data['content_id']   = intval($this->input['content_id']);
            $data['appid']        = urldecode($this->input['appid']);
            $data['appname']      = urldecode($this->input['appname']);
            $data['publish_time'] = urldecode($this->input['publish_time']);
            if (!$line)
            {
                //表示新添加的内容，插入为第一行
                $line       = $child_line = $tag        = 1;
            }
            else
            {
                //计算出这行内容有几条内容，添加的内容的子行为+1
                $child_line = $this->obj->get_child_line($id, $line);
                $tag        = 0;
            }
            $data['block_id']   = $id;
            $data['line']       = $line;
            $data['child_line'] = $child_line;

            //插入图片
            $pic_data         = $this->insert_pic();
            $data['indexpic'] = empty($pic_data) ? '' : serialize($pic_data);
            $data['id']       = $this->obj->insert_child_content($data, $tag);
            
            $this->addItem($data);
            $this->output();
        }
    }

    public function insert_pic()
    {
        include_once ROOT_PATH . 'lib/class/material.class.php';
        $this->mMaterial = new material();
        $result          = array();
        if ($_FILES['indexpic'])
        {
            $file['Filedata']   = $_FILES['indexpic'];
            $default            = $this->mMaterial->addMaterial($file, '');
            $result['id']       = $default['id'];
            $result['host']     = $default['host'];
            $result['dir']      = $default['dir'];
            $result['filepath'] = $default['filepath'];
            $result['filename'] = $default['filename'];
        }
        return $result;
    }

    public function block_line_set()
    {
        $id   = intval($this->input['block_id']);
        $line = urldecode($this->input['line']);
        if (!$id || !$line)
        {
            $this->errorOutput('缺少参数');
        }
        if (isset($this->input['loop_body']))
        {
            $data['loop_body'] = urldecode($this->input['loop_body']);
        }
        if (isset($this->input['width']))
        {
            $data['width'] = urldecode($this->input['width']);
        }
        if (isset($this->input['height']))
        {
            $data['height'] = urldecode($this->input['height']);
        }
        if (isset($this->input['font_color']))
        {
            $data['font_color'] = urldecode($this->input['font_color']);
        }
        if (isset($this->input['font_b']))
        {
            $data['font_b'] = urldecode($this->input['font_b']);
        }
        if (isset($this->input['font_size']))
        {
            $data['font_size'] = urldecode($this->input['font_size']);
        }
        if (isset($this->input['font_border']))
        {
            $data['font_border'] = urldecode($this->input['font_border']);
        }
        if (isset($this->input['font_backcolor']))
        {
            $data['font_backcolor'] = urldecode($this->input['font_backcolor']);
        }
        if (isset($this->input['before_prefix']))
        {
            $data['before_prefix'] = urldecode($this->input['before_prefix']);
        }
        if (isset($this->input['back_prefix']))
        {
            $data['back_prefix'] = urldecode($this->input['back_prefix']);
        }
        if (empty($data))
        {
            $this->errorOutput('没有要更新的内容');
        }
        $this->obj->update_block_line($id, $line, $data);
        $this->addItem('success');
        $this->output();
    }

    public function delete_content()
    {
        $content_id = intval($this->input['content_id']);
        if ($content_id)
        {
            if (!$this->obj->delete_content($content_id))
            {
                $this->errorOutput('删除失败');
            }
        }
        $this->addItem('success');
        $this->output();
    }
    
    /**
     * update区块内容，包括区块（魔力视图）
     */
    public function update_block()
    {
        $data       = $this->input['data'];
        $block      = $data['block'];
        $block_line = $data['block_line'];
        $content    = $data['content'];
        if (!$block || !is_array($block) || !$block['block_id'])
        {
            $this->errorOutput('NO_BLOCK_DATA');
        }
        $block_id       = $block['block_id'];
        $old_block_data = $this->block->get_block_first($block_id);

        $block_data = array(
            'update_time' => intval($block['update_time']),
            'update_type' => intval($block['update_type']),
            'line_num' => intval($block['line_num']),
            'is_support_push' => intval($block['is_support_push']),
        );
        $this->block->update($block_data, $block_id);

        /* 区块行处理 */
        if ($block_line && is_array($block_line))
        {
            $this->update_block_line($block_id, $block_line);
        }

        /* 区块行处理 */

        /* 区块内容处理 */
        if ($block_line && is_array($block_line))
        {
            $block['line_num'] = $old_block_data['line_num']>$block_data['line_num']?$old_block_data['line_num']:$block_data['line_num'];
            $arr = array($block_id=>array('id'=>$block_id,'name'=>$old_block_data['name']));
            $this->update_block_content($block_id, $content, $block,$arr);
        }
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache  = new Cache();
        $cache->initialize(BLOCK_CACHE);
        $cache->delete($block_id);
        /* 区块内容处理 */
        $this->addItem('success');
        $this->output();
    }

    public function update_block_line($block_id, $block_line)
    {
        $sql = "delete from " . DB_PREFIX . "block_line where block_id=$block_id";
        $this->db->query($sql);
        foreach ($block_line as $kk => $vv)
        {
            $vv['id'] && $block_line_idarr[] = $vv['id'];
        }
        if ($block_line_idarr)
        {
            $sql            = "select id from " . DB_PREFIX . "block_line where id in(" . implode(',', $block_line_idarr) . ")";
            $block_line_all = $this->db->fetch_all($sql, 'id');
        }
        $relationMap = array(
            'block_id' => 'block_id',
            'line' => 'line',
            'attribute' => 'attribute',
            'loop_body' => 'loop_body',
            'father_tag' => 'father_tag',
            'font_color' => 'font_color',
            'font_b' => 'font_b',
            'font_size' => 'font_size',
            'font_border' => 'font_border',
            'font_backcolor' => 'font_backcolor',
            'before_prefix' => 'before_prefix',
            'back_prefix' => 'back_prefix',
            'width' => 'width',
            'height' => 'height',
            'before_wz' => 'before_wz',
            'before_img' => 'before_img',
            'before_link' => 'before_link',
            'after_wz' => 'after_wz',
            'after_img' => 'after_img',
            'after_link' => 'after_link',
        );
        foreach ($block_line as $kk => $vv)
        {
            if ($block_line_all[$vv['id']])
            {
                unset($vv['id']);
            }
            !$vv['block_id'] && ($vv['block_id']   = $block_id);
            $vv['before_img'] = $vv['before_img'] ? array('real_url' => $vv['before_img']['real_url'], 'url' => $vv['before_img']['url']) : '';
            $vv['after_img']  = $vv['after_img'] ? array('real_url' => $vv['after_img']['real_url'], 'url' => $vv['after_img']['url']) : '';
            $vv['before_img'] = $vv['before_img'] ? serialize($vv['before_img']) : '';
            $vv['after_img']  = $vv['after_img'] ? serialize($vv['after_img']) : '';
            $temArray         = array();
            foreach ($relationMap as $kkk => $vvv)
            {
                $temArray[$kkk] = $vv[$vvv];
            }
            $this->block->insert($temArray, 'block_line');
        }
    }

    public function update_block_content($block_id, $content, $block,$arr=array())
    {
        $b = array();
        $sql = "select id,content_fromid,block_id,bundle_id,module_id from " . DB_PREFIX . "block_content where block_id=" . $block_id . " and line<=" . $block['line_num'];
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $delid[] = $row['id'];
            $b[$row['bundle_id']]['module_id'] = $row['module_id'];
            $b[$row['bundle_id']]['content'][$row['content_fromid']] = array('module_id'=>$row['module_id'],'content_fromid'=>$row['content_fromid']);
        }
        //回调各自系统
        $arrdel                                   = $arr;
        $arrdel[$block_id]['del'] = 1;
        $this->pub_plan->update_block_content($arrdel,$b);
        
        if($delid)
        {
            $sql = "delete from " . DB_PREFIX . "block_content where id in (" . implode(',', $delid) . ")";
            $this->db->query($sql);
        }
        foreach ($content as $kk => $vv)
        {
            foreach($vv as $kkk=>$vvv)
            {
                $vvv['content_id'] && $block_content_idarr[] = $vvv['content_id'];
            }
        }
        if ($block_content_idarr)
        {
            $sql = "delete from " . DB_PREFIX . "block_content where content_id in(" . implode(',', $block_content_idarr) . ") and block_id=".$block_id;
            $this->db->query($sql);
        }
        $relationMap = array(
            'id' => 'id',
            'block_id' => 'block_id',
            'cid' => 'cid',                 //cid是content.id
            'content_id' => 'content_id',   //content_id是content_relation_id
            'content_fromid' => 'content_fromid',   //content_id是content_relation_id
            'bundle_id' => 'bundle_id',   //content_id是content_relation_id
            'module_id' => 'module_id',   //content_id是content_relation_id
            'title' => 'title',
            'brief' => 'brief',
            'outlink' => 'outlink',
            'indexpic' => 'indexpic',
            'appid' => 'appid',
            'appname' => 'appname',
            'publish_time' => 'publish_time',
            'line' => 'line',
            'child_line' => 'child_line',
            'font_color' => 'font_color',
            'font_b' => 'font_b',
            'font_size' => 'font_size',
            'font_border' => 'font_border',
            'font_backcolor' => 'font_backcolor',
            'before_wz' => 'before_wz',
            'before_img' => 'before_img',
            'before_link' => 'before_link',
            'after_wz' => 'after_wz',
            'after_img' => 'after_img',
            'after_link' => 'after_link',
        );
        $b = array();
        foreach ($content as $kk => $vv)
        {
            if (is_array($vv) && count($vv) > 0)
            {
                foreach ($vv as $kkk => $vvv)
                {
                    !$vvv['block_id'] && ($vvv['block_id']   = $block_id);
                    $vvv['indexpic']   = $vvv['indexpic'] ? serialize($vvv['indexpic']) : '';
                    $vvv['before_img'] = $vvv['before_img'] ? array('real_url' => $vvv['before_img']['real_url'], 'url' => $vvv['before_img']['url']) : '';
                    $vvv['after_img']  = $vvv['after_img'] ? array('real_url' => $vvv['after_img']['real_url'], 'url' => $vvv['after_img']['url']) : '';
                    $vvv['before_img'] = $vvv['before_img'] ? serialize($vvv['before_img']) : '';
                    $vvv['after_img']  = $vvv['after_img'] ? serialize($vvv['after_img']) : '';
                    $temArray          = array();
                    foreach ($relationMap as $kkkk => $vvvv)
                    {
                        $temArray[$kkkk] = $vvv[$vvvv];
                    }
                    unset($temArray['id']);
                    $this->block->insert($temArray, 'block_content');
                    $b[$temArray['bundle_id']]['module_id'] = $temArray['module_id'];
                    $b[$temArray['bundle_id']]['content'][$temArray['content_fromid']] = array('module_id'=>$temArray['module_id'],'content_fromid'=>$temArray['content_fromid']);
                }
            }
        }
        
        //回调各自系统
        $this->pub_plan->update_block_content($arr, $b);
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

$out    = new browseApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
