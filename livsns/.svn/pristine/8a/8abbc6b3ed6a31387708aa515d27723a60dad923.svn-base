<?php

/* * *****************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :mkpublish_1.php
 * package  :package_name
 * Created  :2013-6-19,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 * **************************************************************** */
require('global.php');
define('MOD_UNIQUEID', 'mkpublish_plan'); //模块标识
class mkpublish_plan extends adminBase
{

    function __construct()
    {
        parent::__construct();
        include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
        $this->pub_sys = new publishsys();
        include(CUR_CONF_PATH . 'lib/mkpublish.class.php');
        $this->obj         = new mkpublish();
    }

    public function __destruct()
    {
        
    }

    public function show()
    {
        $site_id = $this->input['site_id'] ? intval($this->input['site_id']) : 1;
        $offset       = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count        = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $plan = $this->obj->get_mkplan($this->get_condition(), $offset, $count);

        
        $this->addItem($plan);
        $this->output();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mkpublish_plan WHERE 1 " . $this->get_condition();
        echo json_encode($this->db->query_first($sql));
    }

    private function get_condition()
    {
        $condition = '';
        return $condition;
    }
    
    public function form()
    {
        $id = intval($this->input['id']);
        if($id)
        {
            $data = $this->obj->get_mkplan_first($id);
        }
        $this->addItem($data);
        $this->output();
    }

    public function create()
    {
        $title = ($this->input['title']);
        $site_id = intval($this->input['site_id']);
        $page_id = intval($this->input['page_id']);
        $page_data_id = intval($this->input['page_data_id']);
        $content_type = intval($this->input['content_type']);
        $client_type = intval($this->input['client_type']);
        $mk_time = intval($this->input['mk_time']);
        $is_open = intval($this->input['is_open']);
        
        $insert_data = array(
            'title' => $title,
            'site_id' => $site_id,
            'page_id' => $page_id,
            'page_data_id' => $page_data_id,
            'content_type' => $content_type,
            'client_type' => $client_type,
            'mk_time' => $mk_time,
            'create_user' => $this->user['user_name'],
            'next_time' => TIMENOW,
            'is_open' => $is_open,
        );
        $this->obj->insert('mkpublish_plan',$insert_data);
        $this->addItem(true);
        $this->output();
    }
    
    public function update()
    {
        $id = intval($this->input['id']);
        $title = ($this->input['title']);
        $site_id = intval($this->input['site_id']);
        $page_id = intval($this->input['page_id']);
        $page_data_id = intval($this->input['page_data_id']);
        $content_type = intval($this->input['content_type']);
        $client_type = intval($this->input['client_type']);
        $mk_time = intval($this->input['mk_time']);
        $is_open = intval($this->input['is_open']);
        if(!$id)
        {
            $this->errorOutput('NO_ID');
        }
        $update_data = array(
            'title' => $title,
            'site_id' => $site_id,
            'page_id' => $page_id,
            'page_data_id' => $page_data_id,
            'content_type' => $content_type,
            'client_type' => $client_type,
            'mk_time' => $mk_time,
            'is_open' => $is_open,
        );
        $this->obj->update('mkpublish_plan','id = '.$id,$update_data);
        $this->addItem(true);
        $this->output();
    }

    public function delete()
    {
        $id = ($this->input['id']);
        if(!$id)
        {
            $this->errorOutput('NO_ID');
        }
        $this->obj->delete('mkpublish_plan','id in('.$id.')');
        $this->addItem(true);
        $this->output();
    }
    
}

$out    = new mkpublish_plan();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
