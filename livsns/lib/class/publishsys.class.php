<?php

class publishsys
{

    function __construct()
    {
        global $gGlobalConfig;
        if (!$gGlobalConfig['App_publishsys'])
        {
            return false;
        }
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        $this->curl = new curl($gGlobalConfig['App_publishsys']['host'], $gGlobalConfig['App_publishsys']['dir']);
    }

    function __destruct()
    {
        
    }

    public function delete_column_template($column_id, $typeids)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete_column_template');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('column_id', $column_id);
        $this->curl->addRequestData('typeids', $typeids);
        $ret = $this->curl->request('admin/deploy.php');
        return $ret[0];
    }

    //新增数据源
    public function addDataSource($info)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'create');
        foreach ($info as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/data_source_update.php');
        return $ret[0];
    }

    //删除数据源
    public function deleteDataSource($ids)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete');
        $this->array_to_add('id', $ids);
        $ret = $this->curl->request('admin/data_source_update.php');
        return $ret[0];
    }

    //获取数据源内容
    public function queryDataSource($id, $data)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'query');
        $this->curl->addRequestData('id', $id);
        if ($data)
        {
            $this->array_to_add('data', $data);
        }
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/data_source.php');
        return $ret[0];
    }

    //获取数据源列表
    public function showDataSource()
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'showDataSource');

        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/data_source.php');
        return $ret[0];
    }

    public function get_datasource_info($id)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_datasource_info');
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/data_source.php');
        return $ret[0];
    }

    //生成API文件
    public function createAPI($id)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'build_api_file');
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/data_source.php');
        return $ret[0];
    }

    public function mk_publish($content_data, $column_id, $content_type)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'mk_content');
        $this->curl->addRequestData('html', true);
        $this->array_to_add('data', $content_data);
        $this->curl->addRequestData('page_sign', 'column');
        $this->curl->addRequestData('column_id', $column_id);
        $this->curl->addRequestData('content_type', $content_type);
        $ret = $this->curl->request('admin/mkpublish_update.php');
        return $ret[0];
    }

    public function insert_block_content($data)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'content_create');
        foreach ($data as $k => $v)
        {
            $this->curl->addRequestData($k, $v);
        }
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/browse.php');
        return $ret[0];
    }

    //取布局列表
    public function layout_list()
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'layout_node');
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('layout.php');
        return $ret[0];
    }

    //输出模板和模板中单元信息
    public function search_cell($page_id, $page_data_id, $content_type, $template_id)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'search_cell');
        $this->curl->addRequestData('page_id', $page_id);
        $this->curl->addRequestData('page_data_id', $page_data_id);
        $this->curl->addRequestData('content_type', $content_type);
        $this->curl->addRequestData('template_id', $template_id);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('publishsys_update.php');
        return $ret[0];
    }

    //还原单元
    public function cell_cancle($id)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'cell_cancle');
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('publishsys_update.php');
        return $ret[0];
    }

    //编辑单元
    public function cell_update($data)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'cell_update');
        $this->curl->addRequestData('html', true);
        $this->array_to_add('data', $data);
        $ret = $this->curl->request('publishsys_update.php');
        return $ret[0];
    }

    public function preview($site_id = '', $page_id = '', $page_data_id = '', $content_type = '', $template_id = '')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'preview');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('site_id', $site_id);
        $this->curl->addRequestData('page_id', $page_id);
        $this->curl->addRequestData('page_data_id', $page_data_id);
        $this->curl->addRequestData('content_type', $content_type);
        $this->curl->addRequestData('template_id', $template_id);
        $ret = $this->curl->request('publishsys_update.php');
        return $ret[0];
    }

    public function update_layout_title($layout_id, $is_header = '', $header_text = '', $is_more = '', $more_href = '')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_layout_title');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('layout_id', $layout_id);
        $this->curl->addRequestData('is_header', $is_header);
        $this->curl->addRequestData('header_text', $header_text);
        $this->curl->addRequestData('is_more', $is_more);
        $this->curl->addRequestData('more_href', $more_href);
        $ret = $this->curl->request('layout_update.php');
        return $ret[0];
    }

    public function get_all_icons()
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_all_icons');
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('publishsys_update.php');
        return $ret[0];
    }

    public function get_special_templates($offset, $count,$tag='')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'show');
        $this->curl->addRequestData('offset', $offset);
        $this->curl->addRequestData('count', $count);
        $this->curl->addRequestData('tag', $tag);
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('app_uniqueid', APP_UNIQUEID);
        $ret = $this->curl->request('template.php');
        return $ret[0];
    }

	public function get_template_tag($offset='', $count='')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_template_tag');
        if($offset)
        {
        	$this->curl->addRequestData('offset', $offset);
        }
        if($count)
        {
        	$this->curl->addRequestData('count', $count);
        }
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('template.php');
        return $ret[0];
    }
    
    public function get_template_info($template_id)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_template_info');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('template_id', $template_id);
        $ret = $this->curl->request('template.php');
        return $ret[0];
    }
    
    public function get_layout_preview($layout_id)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_layout_preview');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('layout_id', $layout_id);
        $ret = $this->curl->request('layout.php');
        return $ret[0];
    }

    public function update_special_layout($special_id, $layout_ids)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_special_layout');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('special_id', $special_id);
        $this->curl->addRequestData('layout_ids', $layout_ids);
        $ret = $this->curl->request('layout.php');
        return $ret[0];
    }

    public function get_page_by_sign($sign = 'column', $site_id = '')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_page_by_sign');
        $this->curl->addRequestData('sign', $sign);
        $this->curl->addRequestData('site_id', $site_id);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('publishsys.php');
        return $ret[0];
    }

    public function get_page_manage($site_id, $page_id = 0, $key = '')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_page_manage');
        $this->curl->addRequestData('site_id', $site_id);
        $this->curl->addRequestData('page_id', $page_id);
        $this->curl->addRequestData('key', $key);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('publishsys.php');
        return $ret[0];
    }

    public function get_page_data($page_id, $offset = '', $count = '', $fid = 0, $pinfo = array(), $page_data_id = '')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_page_data');
        $this->curl->addRequestData('page_id', $page_id);
        $this->curl->addRequestData('offset', $offset);
        $this->curl->addRequestData('count', $count);
        $this->curl->addRequestData('fid', $fid);
        $this->array_to_add('pinfo', $pinfo);
        $this->curl->addRequestData('page_data_id', $page_data_id);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('publishsys.php');
        return $ret[0];
    }

    public function get_content_by_datasource($id, $data)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_content_by_datasource');
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('data', $data);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/publishsys.php');
        return $ret[0];
    }

    public function mk($plan)
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'show');
        $this->curl->addRequestData('html', true);
        $this->array_to_add('plan', $plan);
        $ret = $this->curl->request('admin/mk.php');
        return $ret;
    }

    public function mk_include($dir, $request = array())
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'show');
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('file_result', $dir);
        foreach ($request as $k => $v)
        {
             if(is_array($v))
                        {
                            foreach($v as $kk=>$vv)
                            {
                                $this->curl->addRequestData($k.'['.$kk.']', $vv);
                            }
                        }
                        else
                        {
                            $this->curl->addRequestData($k, $v);
                        }
        }
        $ret = $this->curl->request('admin/mk_include.php');
        return $ret;
    }

    public function get_page_by_id($id, $in = false, $key = '')
    {
        if(!$this->curl)
        {
            return false;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_page_by_id');
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('in', $in);
        $this->curl->addRequestData('key', $key);
        $ret = $this->curl->request('publishsys.php');
        return $ret[0];
    }

    public function array_to_add($str, $data)
    {
        $str = $str ? $str : 'data';
        if (is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if (is_array($vv))
                {
                    $this->array_to_add($str . "[$kk]", $vv);
                }
                else
                {
                    $this->curl->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }

}

?>