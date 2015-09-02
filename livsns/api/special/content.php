<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'special');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");

class contentApi extends adminBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function get_content()
    {
        $condition    = $this->get_condition();
        $offset       = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count        = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit   = ' LIMIT ' . $offset . ', ' . $count;
        $sql          = "SELECT id, pub_id, outlink,indexpic,title,brief,column_id,order_id,weight,user_id,user_name,create_time,update_time 
			             FROM " . DB_PREFIX . "special_content 
			             WHERE 1 " . $condition . $data_limit;
        $q            = $this->db->query($sql);
        $pub_ids      = $outlink_data = $content      = array();
        while ($row          = $this->db->fetch_array($q))
        {
            $row['content_url']        = $row['content_url'] ? $row['content_url'] : $row['outlink'];
            $row['publish_time_stamp'] = $row['publish_time_stamp'] ? $row['publish_time_stamp'] : $row['create_time'];
            $row['indexpic']           = $row['indexpic'] ? unserialize($row['indexpic']) : '';
            if ($row['pub_id'])
            {
                $pub_ids[] = $row['pub_id'];
            }
            else
            {
                $outlink_data[] = $row;
            }
            $data[] = $row;
        }
        $pub_ids = implode(',', $pub_ids);
        $pubcontent = array();
        if ($pub_ids)
        {
            if (!class_exists('publishcontent'))
            {
                include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                $this->pubcontent = new publishcontent();
                $param            = array(
                    'id' => $pub_ids,
                    'need_video' => 1,
                    'client_type' => $this->input['client_type'] ? intval($this->input['client_type']) : 2,
                );
                $content          = $this->pubcontent->get_content($param);
                foreach ((array)$content as $kk => $vv)
                {
                    $pubcontent[$vv['id']] = $vv;
                }
            }
        }
        $new_data = array();
        foreach ($data as $k => $v)
        {
            if ($pubcontent[$v['pub_id']])
            {
                $new_data[$k] = $pubcontent[$v['pub_id']];
            }
            else
            {
                $new_data[$k] = $v;
            }
        }
        if ($this->input['need_count'])
        {
            $totalcount = $this->get_count();
            $this->addItem_withkey('total', $totalcount['total']);
            $this->addItem_withkey('data', $new_data);
        }
        else
        {
            if (is_array($new_data) && count($new_data) > 0)
            {
                foreach ($new_data as $k => $v)
                {
                    $this->addItem($v);
                }
            }
        }
        $this->output();
    }

    public function count()
    {
        $ret = $this->get_count();
        echo json_encode($ret);
    }

    public function get_count()
    {
        $condition = $this->get_condition(true);
        $sql       = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "special_content WHERE 1 " . $condition;
        $ret       = $this->db->query_first($sql);
        return $ret;
    }

    public function get_condition($is_count = false)
    {
        $condition  = '';
        $condition .= " AND state = 1 ";
        if ($special_id = intval($this->input['special_id']))
        {   //专题 id
            $condition .= " AND special_id = " . $special_id;
        }
        if ($this->input['special_column'] && $this->input['special_column'] != 'column.id')
        {
            $special_column = urldecode($this->input['special_column']);
            $condition .= " AND column_id IN(" . $special_column . ")";
        }
        if ($this->input['start_weight'] && $this->input['start_weight'] != -1)
        {
            $condition .= " AND weight >= " . intval($this->input['start_weight']);
        }
        if ($this->input['end_weight'] && $this->input['end_weight'] != -1)
        {
            $condition .= " AND weight <= " . $this->input['end_weight'];
        }
        if ($this->input['except_weight'] && $this->input['except_weight'] !== '')
        {
            $condition .= " AND weight != " . intval($this->input['except_weight']);
        }
        if ($this->input['weight'] && $this->input['weight'] !== '')
        {
            $condition .= " AND weight = " . intval($this->input['weight']);
        }
        if (isset($this->input['is_have_indexpic']) && $this->input['is_have_indexpic'] !== '')
        {
            if ($this->input['is_have_indexpic'] == 1)
            {
                $condition .= " AND indexpic != '' AND indexpic != 'a:0:{}'";
            }
            if ($this->input['is_have_indexpic'] == 0)
            {
                $condition .= " AND (indexpic = '' OR indexpic = 'a:0:{}')";
            }
        }
//		if (isset($this->input['is_have_video'])) {
//			$condition .= " AND is_have_video = " . intval($this->input['is_have_video']);
//		}	
        //查询创建的时间
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
        //查询是否是外链
        if ($this->input['outlink'] == 1)
        {
            $condition .= " AND outlink != '' ";
        }
        if (!$is_count)
        {
            $condition .= ' ORDER BY ';
//			if ($sort_field = urldecode($this->input['sort_field'])) {
            $sort_field = urldecode($this->input['sort_field']);
            $sort_field = in_array($sort_field, array('weight', 'id', 'order_id', 'create_time', 'publish_time')) ? $sort_field : 'id';
            $condition .= $sort_field;
//			}
            if ($sort_type  = urldecode($this->input['sort_type']))
            {
                $sort_type = $sort_type == 'DESC' ? ' DESC' : ' ASC';
                $condition .= $sort_type;
            }
        }
        return $condition;
    }

    public function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new contentApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
