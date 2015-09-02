<?php
require('global.php');
define('MOD_UNIQUEID', 'hotwords'); //模块标识

class HotwordsApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/hotwords.class.php');
        $this->obj	= new Hotwords();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function show()
    {
        $condition = $this->get_condition();
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $limit     = " limit {$offset}, {$count}";
        $ret       = $this->obj->show($condition,$limit);
        $this->addItem($ret);
        $this->output();
    }

    public function detail()
    {
        $id = intval($this->input['id']);
        $sql = 'SELECT *
				FROM '.DB_PREFIX.'hotwords WHERE id = '.$id;
		$r = $this->db->query_first($sql);
        $this->addItem($r);
        $this->output();
    }

    /**
     * 根据条件返回总数
     * @name count
     * @access public
     * @author gaoyuan
     * @category hogesoft
     * @copyright hogesoft
     * @return $info string 总数，json串
     */
    public function count()
    {
        $sql   = 'SELECT count(*) as total from ' . DB_PREFIX . 'hotwords a WHERE 1 ' . $this->get_condition();
        $hotwords_total = $this->db->query_first($sql);
        echo json_encode($hotwords_total);
    }

    /**
     * 检索条件应用，模块,操作，来源，用户编号，用户名
     * @name get_condition
     * @access private
     * @author gaoyuan
     * @category hogesoft
     * @copyright hogesoft
     */
    public function get_condition()
    {
        $condition = '';
        //查询
        if ($this->input['k'])
        {
            $condition .= " AND name LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
        }

        //查询创建的起始时间
        if ($this->input['start_time'])
        {
            $condition .= " AND create_time > " . strtotime($this->input['start_time']);
        }

        //查询创建的结束时间
        if ($this->input['end_time'])
        {
            $condition .= " AND create_time < " . strtotime($this->input['end_time']);
        }


        //查询发布的时间
        if ($this->input['date_search'])
        {
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday     = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  create_time > '" . $yesterday . "' AND create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > '" . $today . "' AND create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND create_time > '" . $last_threeday . "' AND create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  create_time > '" . $last_sevenday . "' AND create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }

        //查询文章的状态
        if (isset($this->input['state']))
        {
            switch (intval($this->input['state']))
            {
                case 1:
                    $condition .= " ";
                    break;
                case 2: //待审核
                    $condition .= " AND state= 0";
                    break;
                case 3://已审核
                    $condition .= " AND state = 1";
                    break;
                case 4: //已打回
                    $condition .=" AND state = 2";
                default:
                    break;
            }
        }
        
        if ($this->input['sort_type'] == 'ASC')
        {
            $condition .=" ORDER BY order_id  " . $this->input['sort_type'];
        }
        else
        {
            $condition .= " ORDER BY order_id DESC ";
        }
        return $condition;
    }

    public function index()
    {
        
    }

}

$out    = new HotwordsApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
