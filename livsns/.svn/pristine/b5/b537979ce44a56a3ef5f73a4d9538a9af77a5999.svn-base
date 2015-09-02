<?php

define('MOD_UNIQUEID', 'block');
require('global.php');

class block_page_node extends adminReadBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index()
    {
        
    }

    public function detail()
    {
        
    }

//默认载入第一维数据
    public function show()
    {
        $offset = 0;
        $count  = 10000;
        $sql    = "SELECT *,count(distinct site_id,page_id,page_data_id) as total FROM " . DB_PREFIX . "block_relation WHERE 1";
        $sql .= " group by site_id,page_id,page_data_id order by id DESC limit $offset,$count";
        $info   = $this->db->query($sql);
        $i=1;
        while ($row    = $this->db->fetch_array($info))
        {
            $r['id']      = $row['site_id'] . '_' . $row['page_id'] . '_' . $row['page_data_id'];
            $r['name']    = $row['expand_name'];
            $r['fid']     = 0;
            $r['childs']  = $i;
            $r['parents'] = $i;
            $r['depath']  = 1;
            $r['is_last'] = 1;
            $i++;
            $this->addItem($r);
        }
        $this->output();
    }

//获取选中的节点树状
    public function get_selected_node_path()
    {
        $ids = urldecode($this->input['id']);
        if (!$ids)
        {
            $this->errorOutput(NO_ID);
        }
        $idarr = explode(',', $ids);

        $offset = 0;
        $count  = 10000;
        $sql    = "SELECT *,count(distinct site_id,page_id,page_data_id) as total FROM " . DB_PREFIX . "block_relation WHERE 1";
        $sql .= " group by site_id,page_id,page_data_id order by id DESC limit $offset,$count";
        $info   = $this->db->query($sql);
        $i=1;
        while ($row    = $this->db->fetch_array($info))
        {
            $r['id'] = $row['site_id'] . '_' . $row['page_id'] . '_' . $row['page_data_id'];
            if (in_array($r['id'], $idarr))
            {
                $r['name']    = $row['expand_name'];
                $r['fid']     = 0;
                $r['childs']  = $i;
                $r['parents'] = $i;
                $r['depath']  = 1;
                $r['is_last'] = 1;
                $i++;
                $this->addItem($r);
            }
        }
        $this->output();
    }

//用于分页
    public function count()
    {
        parent::count($this->get_condition());
    }

}

$out    = new block_page_node();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>