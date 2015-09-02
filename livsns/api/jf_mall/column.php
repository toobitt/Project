<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-5-7
 * Time: 下午10:58
 */
require('global.php');
define('MOD_UNIQUEID', 'jf_mall');
class Column extends outerReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/good.class.php';
        $this->good_mode = new GoodMode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show(){}
    public function detail(){}
    public function count(){}

    public function get_column() {
        $condition = $this->get_condition();
        $order = ' order_id ';
        $order .= ($this->input['descasc'] && in_array($this->input['descasc'], array('DESC', 'ASC'))) ? $this->input['descasc'] : 'DESC ';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = $offset . ', ' . $count;

        $where = $condition == '' ? '' : ' WHERE 1 ' . $condition;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT * FROM '.DB_PREFIX.'node ' . $where . $order . $limit;
        $q = $this->db->query($sql);
        $ret = array();
        while (($row = $this->db->fetch_array($q)) != false) {
            $ret[] = $row;
        }

        if ($this->input['need_count'])
        {
            $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'node WHERE 1 ' . $where;
            $totalcount = $this->db->query_first($sql);
            $this->addItem_withkey('total', $totalcount['total']);
            $this->addItem_withkey('data', $ret);
        }
        else
        {
            foreach ((array)$ret as $k => $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    private function get_condition() {
        $condition = '';

        if ($this->input['id']) {
            $id = explode(',', $this->input['id']);
            $id = implode("','", $id);
            $condition .= ' AND id IN(\''.$id.'\')';
        }

        if ($this->input['fid']) {
            $fid = explode(',', $this->input['fid']);
            $fid = implode("','", $fid);
            $condition .= ' AND fid IN(\''.$fid.'\')';
        }
        return $condition;
    }

    public function unknow() {
        $this->errorOutput('方法不存在');
    }
}

$out = new Column();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();

/* End of file goods.php */
 