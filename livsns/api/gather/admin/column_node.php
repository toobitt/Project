<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'column_node');
define('MOD_UNIQUEID', 'column');

class column_node extends nodeFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //发布库节点获取的栏目
    public function show()
    {
        $con    = '';
        $fid    = intval($this->input['fid']);
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count  = $this->input['count'] ? $this->input['count'] : 1000;
        $sql    = "select * from " . DB_PREFIX . "sort where 1 ";
        if ($site_id)
        {
            $sql .= ' AND site_id=' . $site_id;
        }
        $sql .= ' AND fid=' . $fid;
        $sql .= " order by order_id ";
        $sql .= " limit $offset,$count ";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $r['id']      = $row['id'];
            $r['name']    = $row['name'];
            $r['fid']     = $row['fid'];
            $r['childs']  = $row['childs'];
            $r['parents'] = $row['parents'];
            $r['depath']  = $row['depath'];
            $r['is_last'] = $row['is_last'];
            $this->addItem($r);
        }
        $this->output();
    }

}

include(ROOT_PATH . 'excute.php');
?>
