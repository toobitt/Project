<?php

class common extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //根据id获取应用
    public function get_app()
    {
        include_once(ROOT_PATH . 'lib/class/auth.class.php');
        $auth = new auth();
        return $auth->get_app();
    }

    //获取模块
    public function get_module($application_id)
    {
        include_once(ROOT_PATH . 'lib/class/auth.class.php');
        $auth = new auth();
        return $auth->get_module($application_id);
    }

    /**
     * 'site_id' => intval($this->input['site_id']),
      'column_id' => intval($this->input['column_id']),
      'name' => urldecode($this->input['name']),
      'update_time' => intval($this->input['update_time']),
      'update_type' => intval($this->input['update_type']),
      'datasource_id' => intval($this->input['datasource_id']),
      'width' => intval($this->input['width']),
      'height' => intval($this->input['height']),
      'line_num' => intval($this->input['line_num']),
      'father_tag' => urldecode($this->input['father_tag']),
      'loop_body' => urldecode($this->input['loop_body']),
      'next_update_time' => TIMENOW+intval($this->input['update_time']),
      'is_support_push' => intval($this->input['is_support_push']),
     * */
    public function insert_block($data)
    {
        if ($data['block_id'])
        {
            include_once(CUR_CONF_PATH . 'lib/block.class.php');
            $block = new block();
            return $block->check_block_relation($data['site_id'], $data['block_id'], $data['page_id'], $data['page_data_id'], $data['expand_name']);
        }
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $pub_config  = new publishconfig();
        include_once(CUR_CONF_PATH . 'lib/block.class.php');
        $block       = new block();
        include_once(CUR_CONF_PATH . 'lib/block_set.class.php');
        $block_set   = new block_set();
        include_once(ROOT_PATH . 'lib/class/data_source.class.php');
        $data_source = new dataSource();
        $block_data  = array(
            'site_id' => $data['site_id'],
            'sort_id' => $data['sort_id'],
            'name' => $data['name'],
            'site_id' => $data['site_id'],
            'datasource_id' => $data['datasource_id'],
            'width' => $data['width'],
            'height' => $data['height'],
            'line_num' => $data['line_num'],
            'father_tag' => $data['father_tag'],
            'loop_body' => $data['loop_body'],
            'next_update_time' => $data['update_time'],
            'is_support_push' => $data['is_support_push'],
            'datasource_argument' => $data['datasource_argument']?serialize($data['datasource_argument']):'',
        );
        //根据栏目id查出站点id
        $insert_id = $block->insert($block_data);
        $block->update(array('group_id' => $insert_id), $insert_id);

        //插入到关联表
        //if ($data['is_from_cell'])
        {
            $block->check_block_relation($data['site_id'], $insert_id, $data['page_id'], $data['page_data_id'], $data['content_type'], $data['client_type'], $data['expand_name']);
        }

        $line_data    = array(
            'block_id' => $insert_id,
        );
        //根据数据源取对应内容
        $content_data = array();
        if ($data['datasource_id'])
        {
            $content_data = $data_source->get_content_by_datasource($data['datasource_id'], $data['datasource_argument']);
        }
        $block_set->insert_line($data['line_num'], $line_data);
        if (is_array($content_data) && $content_data)
        {
            $block_set->insert_content($data['line_num'], $insert_id, $content_data);
        }
        $data['id'] = $insert_id;
        return $data;
    }
    
    /**
     public function insert_block($data)
    {
        if ($data['block_id'])
        {
            include_once(CUR_CONF_PATH . 'lib/block.class.php');
            $block = new block();
            return $block->check_block_relation($data['site_id'], $data['block_id'], $data['page_id'], $data['page_data_id'], $data['expand_name']);
        }
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $pub_config  = new publishconfig();
        include_once(CUR_CONF_PATH . 'lib/block.class.php');
        $block       = new block();
        include_once(CUR_CONF_PATH . 'lib/block_set.class.php');
        $block_set   = new block_set();
        include_once(ROOT_PATH . 'lib/class/data_source.class.php');
        $data_source = new dataSource();
        $block_data  = array(
            'site_id' => $data['site_id'],
            'sort_id' => $data['sort_id'],
            'name' => $data['name'],
            'site_id' => $data['site_id'],
            'datasource_id' => $data['datasource_id'],
            'width' => $data['width'],
            'height' => $data['height'],
            'line_num' => $data['line_num'],
            'father_tag' => $data['father_tag'],
            'loop_body' => $data['loop_body'],
            'next_update_time' => $data['update_time'],
            'is_support_push' => $data['is_support_push'],
            'datasource_argument' => $data['datasource_argument'],
        );
        //判断有没有数据源id，如果有则取设定的参数
        if ($data['datasource_id'])
        {
            $datasource_info_data = $data_source->get_datasource_info($data['datasource_id']);
            $datasource_arg       = $datasource_info_data['argument'];
            foreach ($datasource_arg['ident'] as $k => $v)
            {
                $datasource_argarr[$v] = urldecode($this->input['argument_' . $v]);
            }
        }
        $data['datasource_argument'] = $datasource_argarr ? serialize($datasource_argarr) : '';

        //根据栏目id查出站点id
        $insert_id = $block->insert($block_data);
        $block->update(array('group_id' => $insert_id), $insert_id);

        //插入到关联表
        if ($data['is_from_cell'])
        {
            $block->check_block_relation($data['site_id'], $insert_id, $data['page_id'], $data['page_data_id'], $data['expand_name']);
        }

        $line_data    = array(
            'block_id' => $insert_id,
        );
        //根据数据源取对应内容
        $content_data = array();
        if ($data['datasource_id'])
        {
            $content_data = $data_source->get_content_by_datasource($data['datasource_id'], $datasource_argarr);
        }
        $block_set->insert_line($data['line_num'], $line_data);
        if (is_array($content_data) && $content_data)
        {
            $block_set->insert_content($data['line_num'], $insert_id, $content_data);
        }
        $data['id'] = $insert_id;
        return $data;
    }
     */

}

?>
