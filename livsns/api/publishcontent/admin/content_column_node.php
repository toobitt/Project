<?php

require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID', 'publishcontent'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
define('SCRIPT_NAME', 'column_node');

class column_node extends nodeFrm
{

    public function __construct()
    {
        parent::__construct();
        $this->pub_config = new publishconfig();
        include_once(CUR_CONF_PATH . 'lib/column.class.php');
        $this->column     = new column();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //默认列出顶级节点
    public function show()
    {
        $exclude_column = array();
        $sup_column_id  = '';
        $fid            = urldecode($this->input['fid']);
        $offset         = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count          = $this->input['count'] ? intval(urldecode($this->input['count'])) : 100;
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $new_sup_column_id = $this->get_support_column();
        }

        if (empty($fid))
        {
            $fid = 'site1';
        }
        if (strstr($fid, "site") !== false)
        {
            $site_id = str_replace('site', '', $fid);
            $con     = ' AND site_id=' . $site_id . ' AND fid=0 ';
            $depath  = 1;
        }
        else
        {
            $con = ' AND fid=' . $fid;
        }
        
        $con .= ' ORDER BY order_id limit ' . $offset . ',' . $count;
        $column = $this->column->get_column_by_con(' id,name,fid,childs,is_last ', $con);

        if (isset($new_sup_column_id))
        {
            $column_id = $new_sup_column_id ? explode(',', $new_sup_column_id) : 0;
            if ($column_id)
            {
                foreach ($column as $k => $v)
                {
                    $v_childs_arr = explode(',', $v['childs']);
                    if (array_intersect($column_id, $v_childs_arr))
                    {
                        $m = array('id' => $v['id'], "name" => $v['name'], "fid" => $v['id'], "depth" => 1, 'is_last' => $v['is_last']);
                        if ($depath)
                        {
                            $m['depath'] = 1;
                        }
                        $this->addItem($m);
                    }
                }
            }
            else
            {
                $m = array('id' => 0, "name" => '', "fid" => '', "depth" => 1, 'is_last' => 1);
                if ($depath)
                {
                    $m['depath'] = 1;
                }
                $this->addItem($m);
            }
        }
        else
        {
            foreach ($column as $k => $v)
            {

                $m = array('id' => $v['id'], "name" => $v['name'], "fid" => $v['id'], "depth" => 1, 'is_last' => $v['is_last']);
                if ($depath)
                {
                    $m['depath'] = 1;
                }
                $this->addItem($m);
            }
        }
        $this->output();
    }

    public function get_support_column()
    {
        //获取该用户支持的栏目
        if ($this->user['publish_col_prms'] && is_array($this->user['publish_col_prms']))
        {
            $exclude_column  = implode(',', $this->user['publish_col_prms']);
            $sup_column_data = $this->column->get_column_by_id(' id,childs ', $exclude_column, 'id');
            if (is_array($sup_column_data))
            {
                $tag = '';
                foreach ($sup_column_data as $k => $v)
                {
                    $sup_column_id .= $tag . $v['childs'];
                    $tag = ',';
                }
                $sup_column_id_arr     = explode(',', $sup_column_id);
                $new_sup_column_id_arr = array_unique($sup_column_id_arr);
                $new_sup_column_id     = implode(',', $new_sup_column_id_arr);
            }
        }
        return $new_sup_column_id;
    }

}

include(ROOT_PATH . 'excute.php');
?>
