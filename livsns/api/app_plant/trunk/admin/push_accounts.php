<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 后台推送账号查询接口
 **************************************************************************/
define('MOD_UNIQUEID','push_accounts');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/push_accounts_mode.php');
class push_accounts extends adminReadBase
{
    private $mode;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new push_accounts_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index(){}

    /**
     * 显示数据
     *
     * @access public
     * @param  offset | count
     * @return array
     */
    public function show()
    {
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $condition = $this->get_condition();
        $orderby = '  ORDER BY order_id DESC,id DESC ';
        $limit = ' LIMIT ' . $offset . ' , ' . $count;
        $ret = $this->mode->show($condition,$orderby,$limit);
        if(!empty($ret))
        {
            foreach($ret as $k => $v)
            {
                $this->addItem($v);
            }
            $this->output();
        }
    }

    /**
     * 根据条件获取客户端类型的个数
     *
     * @access public
     * @param  无
     * @return array 例如：array('total' => 20)
     */
    public function count()
    {
        $condition = $this->get_condition();
        $info = $this->mode->count($condition);
        echo json_encode($info);
    }

    /**
     * 获取查询条件
     *
     * @access public
     * @param  $this->input
     * @return String
     */
    public function get_condition()
    {
        $condition = '';
        if($this->input['id'])
        {
            $condition .= " AND id IN (".($this->input['id']).")";
        }

        if($this->input['plant_type'])
        {
            $condition .= " AND plant_type = '" .$this->input['plant_type']. "' ";
        }

        if($this->input['k'] || trim(($this->input['k']))== '0')
        {
            $condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
        }

        if($this->input['start_time'])
        {
            $start_time = strtotime(trim(($this->input['start_time'])));
            $condition .= " AND create_time >= '".$start_time."'";
        }

        if($this->input['end_time'])
        {
            $end_time = strtotime(trim(($this->input['end_time'])));
            $condition .= " AND create_time <= '".$end_time."'";
        }

        if($this->input['date_search'])
        {
            $today = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
            switch(intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
                    $condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
                    break;
                default://所有时间段
                    break;
            }
        }

        return $condition;
    }

    /**
     * 查询某一条消息的详情
     *
     * @access public
     * @param  id:消息id
     * @return array
     */
    public function detail()
    {
        if($this->input['id'])
        {
            $ret = $this->mode->detail($this->input['id']);
            if($ret)
            {
                $this->addItem($ret);
                $this->output();
            }
        }
    }
}

$out = new push_accounts();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'show';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();