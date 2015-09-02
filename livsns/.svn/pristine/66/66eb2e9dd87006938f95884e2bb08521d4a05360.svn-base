<?php
define('MOD_UNIQUEID','subway');//模块标识
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class subwayApi extends adminReadBase
{
    public function __construct()
    {
         $this->mPrmsMethods = array(
            'show' => '查看',
            'operate' => '操作',
            'delete' => '删除',
            'audit' => '审核',
            '_node' => array(
                'name' => '地铁分类',
                'filename' => 'subway_sort.php',
                'node_uniqueid' => 'subway_sort',
            ),
        );
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/subway.class.php');
        $this->obj = new subway();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function  show()
    {   
        $this->verify_content_prms();
        
        $condition = $this->get_condition();
        $offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
        $count = $this->input['count']?intval(urldecode($this->input['count'])):10;
        $limit = " limit {$offset}, {$count}";
        $ret = $this->obj->show($condition,$limit); 
        if(!empty($ret))
        {
            foreach($ret as $k => $v)
            {
                $this->addItem($v);
            }
            $this->output();
        }       
    }

    public function detail()
    {   
        $re = array();
        $id = $this->input['id'];
        $sql = 'SELECT *
                FROM '.DB_PREFIX.'subway WHERE id = '.$id;
        $ret = $this->db->query_first($sql);
        
        $sq = "select name from " . DB_PREFIX . "subway_sort where id = ".$ret['sort_id'];
        $sort_name = $this->db->query_first($sq);
        $ret['sort_name'] = $sort_name['name'];
        $re['subway'] = $ret;
        
        $site_color = $this->obj->get_site_color();
        $direction = 'end';
        $sql = "SELECT b.id,b.title,b.egname,a.order_id FROM  " . DB_PREFIX ."subway_relation a " .
                "LEFT JOIN " . DB_PREFIX ."subway_site b ON a.site_id = b.id"  ." WHERE a.sub_id = ".$id ." AND a.direction = '".$direction ."' ORDER BY a.order_id ASC";
        $q = $this->db->query($sql);
        
        while($row = $this->db->fetch_array($q))
        {
            if($row['title'])
            {
                $subname = $site_color[$row['id']]['subname'];
                if(count($subname)>1)
                {
                    $row['sub_color'] = $site_color[$row['id']]['color'];
                    $row['subname'] = $subname;
                    $row['egname'] = $row['egname'];
                }
                $site[] = $row;
            }
        }
        $re['site'] = $site;
        $this->addItem($re);
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
        $sql = 'SELECT count(*) as total from '.DB_PREFIX.'subway WHERE 1 '.$this->get_condition();
        $templates_total = $this->db->query_first($sql);
        echo json_encode($templates_total); 
    }
    
    //获取应用
    public function get_app()
    {
        $apps = $this->auth->get_app();
        if (is_array($apps))
        {
            foreach ($apps as $k => $v)
            {
                $ret[$v['bundle']] = $v['name'];
            }
        }
        $ret['0'] = '其他';
        $this->addItem($ret);
        $this->output();
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
        
          ####增加权限控制 用于显示####
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (!$this->user['prms']['default_setting']['show_other_data'])
            {
                $condition .= ' AND user_id = ' . $this->user['user_id'];
            }
            else
            {
                //组织以内
                if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
                {
                    $condition .= ' AND org_id IN('.$this->user['slave_org'].')';
                }
            }
            if ($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
            {
                $authnode_str = $authnode ? implode(',', $authnode) : '';
                if ($authnode_str === '0')
                {
                    $condition .= ' AND sort_id IN(' . $authnode_str . ')';
                }
                if ($authnode_str)
                {
                    $authnode_str   = intval($this->input['_id']) ? $authnode_str . ',' . $this->input['_id'] : $authnode_str;
                    $sql            = 'SELECT id,childs FROM ' . DB_PREFIX . 'subway_sort WHERE id IN(' . $authnode_str . ')';
                    $query          = $this->db->query($sql);
                    $authnode_array = array();
                    while ($row            = $this->db->fetch_array($query))
                    {
                        $authnode_array[$row['id']] = explode(',', $row['childs']);
                    }
                    $authnode_str = '';
                    foreach ($authnode_array as $node_id => $n)
                    {
                        if ($node_id == intval($this->input['_id']))
                        {
                            $node_father_array = $n;
                            if (!in_array(intval($this->input['_id']), $authnode))
                            {
                                continue;
                            }
                        }
                        $authnode_str .= implode(',', $n) . ',';
                    }
                    $authnode_str = true ? $authnode_str . '0' : trim($authnode_str, ',');
                    if (!$this->input['_id'])
                    {
                        $condition .= ' AND sort_id IN(' . $authnode_str . ')';
                    }
                    else
                    {
                        $authnode_array = explode(',', $authnode_str);
                        if (!in_array($this->input['_id'], $authnode_array))
                        {
                            //
                            if (!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
                            {
                                $this->errorOutput(NO_PRIVILEGE);
                            }
                            //$this->errorOutput(var_export($auth_child_node_array,1));
                            $condition .= ' AND sort_id IN(' . implode(',', $auth_child_node_array) . ')';
                        }
                    }
                }
            }
        }
        ####增加权限控制 用于显示####
        
        if($this->input['k'] || trim(($this->input['k']))== '0')
        {
            $condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
        }
        if(($this->input['state'] || trim(($this->input['state']))== '0') && $this->input['state']!='-1')
        {
            $condition .= ' AND  state ='. intval($this->input['state']) ;
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
    
    public function detail_site()
    {   
        //$this->input['site_id'] = 7;
        $site_id = $this->input['site_id'];
        $re = $re_ = $ret = $service =  $gate = array();
        $sql = 'SELECT *
                FROM '.DB_PREFIX.'subway_site WHERE id = '.$site_id;
        $ret = $this->db->query_first($sql);
        
        //取所有的素材
        $sqlm = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$site_id .' AND cid_type = 2 ORDER BY order_id ASC';
        $qm = $this->db->query($sqlm);
        while ($rm = $this->db->fetch_array($qm))
        {
            if ($rm['mark'] == 'img')
            {
                $ret['indexpic'][] = array(
                    'id'=>$rm['id'],
                    'host'=>$rm['host'],
                    'dir'=>$rm['dir'],
                    'filepath'=>$rm['filepath'],
                    'filename'=>$rm['filename'],
                    'imgwidth'=>$rm['imgwidth'],
                    'imgheight'=>$rm['imgheight'],
                		'order_id' => $rm['order_id'],
                );
            }
        }
        
        $subs = $this->get_subway('1');
        $pre = $subs['sub'];
        $sql_ = "SELECT *
                FROM  " . DB_PREFIX ."subway_relation 
                WHERE site_id  = ".$site_id;
        $q = $this->db->query($sql_);
        
        while($row = $this->db->fetch_array($q))
        {
            $re_[$row['sub_id']]['sub_name'] = $pre[$row['sub_id']];
            if($pre[$row['sub_id']])
            {
                if($row['direction'] =='start')
                {
                    $re_[$row['sub_id']]['start'] = array('start'=>$row['start'],'end'=>$row['end']);
                }
                if($row['direction'] =='end')
                {
                    $re_[$row['sub_id']]['end'] = array('start'=>$row['start'],'end'=>$row['end']);
                }
            }
        }
        if($re_ && is_array($re_))
        {
            foreach($re_ as $ke=>$v)
            {
                $v['sub_id'] = $ke;
                $v['start_name'] = $subs['site'][$v['sub_id']][0];
                $v['end_name'] = $subs['site'][$v['sub_id']][1];
                $retrn[] =$v;
            }
            
        }
        $re['site_time']    = $retrn;
        $re['site_info']    = $ret;
        $types = $this->get_site_type('1');
        
        $sqlg = "SELECT *
                 FROM  " . DB_PREFIX ."subway_site_exinfo 
                 WHERE site_id  = ".$site_id .' ORDER BY order_id ASC';
        $qg = $this->db->query($sqlg);
        
        while($rowg = $this->db->fetch_array($qg))
        {
            
            if($rowg['gate_id'])
            {
                //出入口拓展信息
                //取所有的素材
                $indexpic_ = array();
                $sqlm_ = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$rowg['id'] .' AND cid_type = 3';
                
                $qm_ = $this->db->query($sqlm_);
                while ($rm_ = $this->db->fetch_array($qm_))
                {
                    if ($rm_['mark'] == 'img')
                    {
                        $indexpic_[] = array(
                            'id'=>$rm_['id'],
                            'host'=>$rm_['host'],
                            'dir'=>$rm_['dir'],
                            'filepath'=>$rm_['filepath'],
                            'filename'=>$rm_['filename'],
                            'imgwidth'=>$rm_['imgwidth'],
                            'imgheight'=>$rm_['imgheight'],
                        );
                    }
                }
                
                $ex[$rowg['gate_id']][] = array(
                    'type_id'       => $rowg['type_id'],
                    'brief'         => $rowg['brief'],
                    'indexpic'      => $indexpic_,
                    'station_id'    => $rowg['station_id'],
                    'station_name'  => $rowg['station_name'],
                    'type_name'     => $types[$rowg['type_id']]['title'],
                    'indexpic'      => $types[$rowg['type_id']]['indexpic'],
                    'sign'          => $rowg['sign'],
                );
                
            }
            if($rowg['type'] == 1)
            {
                //出入口信息
                $indexpica = array();
                //取所有的素材
                $sqlm = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$rowg['id'] .' AND cid_type = 3';
                $qm = $this->db->query($sqlm);
                while ($rm = $this->db->fetch_array($qm))
                {
                    if ($rm['mark'] == 'img')
                    {
                        $indexpica[] = array(
                            'id'=>$rm['id'],
                            'host'=>$rm['host'],
                            'dir'=>$rm['dir'],
                            'filepath'=>$rm['filepath'],
                            'filename'=>$rm['filename'],
                            'imgwidth'=>$rm['imgwidth'],
                            'imgheight'=>$rm['imgheight'],
                        );
                    }
                }
                $gate[] = array(
                    'id'        => $rowg['id'],
                    'sign'      => $rowg['sign'],
                    'title'     => $rowg['title'],
                    'brief'     => $rowg['brief'],
                    'longitude' => $rowg['longitude'],
                    'latitude'  => $rowg['latitude'],
                    'order_id'  => $rowg['order_id'],
                    'indexpic'  => $indexpica,
                );
                
            }
            if($rowg['type'] == 2)
            {
                //服务设施
                $indexpic = array();
                //取所有的素材
                $sqlm = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$rowg['id'] .' AND cid_type = 3';
                $qm = $this->db->query($sqlm);
                while ($rm = $this->db->fetch_array($qm))
                {
                    if ($rm['mark'] == 'img')
                    {
                        $indexpic[] = array(
                            'id'=>$rm['id'],
                            'host'=>$rm['host'],
                            'dir'=>$rm['dir'],
                            'filepath'=>$rm['filepath'],
                            'filename'=>$rm['filename'],
                            'imgwidth'=>$rm['imgwidth'],
                            'imgheight'=>$rm['imgheight'],
                        );
                    }
                }
                $service[] = array(
                    'type_id'       => $rowg['type_id'],
                    'brief'         => $rowg['brief'],
                    'indexpic'      => $indexpic,
                    'color'         => $rowg['color'],
                    'type_name'     => $types[$rowg['type_id']]['title'],
                    'indexpic'      => $types[$rowg['type_id']]['indexpic'],
                );
            }
            
        }
        if($gate && is_array($gate))
        {
            foreach($gate as $k=>$v)
            {
                $gate[$k]['expand'] = $ex[$v['id']] ? $ex[$v['id']] : array();
            }
        }
        
        $re['service_info'] = $service ;
        $re['gate_info']    = $gate;
        
        //file_put_contents('0',var_export($re,1));
        $this->addItem($re);
        $this->output();
    }
    
    public function get_site_type($flag='')
    {   
        
        if($flag)
        {
            $sql = "SELECT *
                    FROM  " . DB_PREFIX ."subway_site_type 
                    WHERE 1 ";
            $q = $this->db->query($sql);
            $re = array();
            while($row = $this->db->fetch_array($q))
            {
                $indexpic = array();
                $id = $row['id'];
                //取所有的素材
                $sqlm = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$id .' AND cid_type = 4';
                $qm = $this->db->query($sqlm);
                while ($rm = $this->db->fetch_array($qm))
                {
                    if ($rm['id'] && $rm['mark'] == 'img')
                    {
                        $indexpic[] = array(
                            'id'=>$rm['id'],
                            'host'=>$rm['host'],
                            'dir'=>$rm['dir'],
                            'filepath'=>$rm['filepath'],
                            'filename'=>$rm['filename'],
                            'imgwidth'=>$rm['imgwidth'],
                            'imgheight'=>$rm['imgheight'],
                        );
                    }
                }
                $row['indexpic'] = $indexpic;
                $re[$row['id']] = $row;
            }
            return $re;
        }
        else
        {
            $condition = '';
            if($this->input['type'])
            {
                $type = $this->input['type'];
                $condition .= ' AND type = '. $type;
            }
            $sql = "SELECT *
                    FROM  " . DB_PREFIX ."subway_site_type 
                    WHERE 1".$condition;
            $q = $this->db->query($sql);
            $re =array();
            while($row = $this->db->fetch_array($q))
            {
                $indexpic = array();
                $id = $row['id'];
                //取所有的素材
                $sqlm = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$id .' AND cid_type = 4';
                $qm = $this->db->query($sqlm);
                while ($rm = $this->db->fetch_array($qm))
                {
                    if ($rm['id'] && $rm['mark'] == 'img')
                    {
                        $indexpic[] = array(
                            'id'=>$rm['id'],
                            'host'=>$rm['host'],
                            'dir'=>$rm['dir'],
                            'filepath'=>$rm['filepath'],
                            'filename'=>$rm['filename'],
                            'imgwidth'=>$rm['imgwidth'],
                            'imgheight'=>$rm['imgheight'],
                        );
                    }
                }
                $row['indexpic'] = $indexpic;
                $re[] = $row;
            }
            $this->addItem($re);
            $this->output();
        }
    }
    
    //获取地铁线路
    public function get_subway($flag='')
    {
        $sites  = $this->obj->get_subway_site();
        $sqll = "select id,title,start,end from " . DB_PREFIX . "subway where  1 ";
        $ret = $this->db->query($sqll);
        while($row = $this->db->fetch_array($ret))
        {
            $pre[$row['id']] = $row['title'];
            $site[$row['id']] =  array('0'=>$sites[$row['start']],'1'=>$sites[$row['end']]);
            $return = array('sub'=>$pre,'site'=>$site);
        }
        if($flag)
        {
            return $return;
        }
        else
        {
            $this->addItem($return);
            $this->output();
        }
    }
    
    //无锡公交接口
    public function get_bus()
    {
        $lat = $this->input['latitude'];
        $lng = $this->input['longitude'];
        //$lat = '31.490620682283';
        //$lng = '120.310796499252';
        $ran = rand();
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        $this->curl = new curl();
        $url = $this->settings['bus']['host'].$this->settings['bus']['dir']."api.php?_trace_lat_lng=".$lat."_".$lng."_1&a=get_station&key=&lat=".$lat."&lng=".$lng."&nonce=".$ran."&rad=500.000000&secret=640c7088ef7811e2a4e4005056991a1f&type=1&version=0.1";
        
        $url_ = str_replace('http://app.wifiwx.com/bus/api.php?','',$url);
        $url_ = str_replace('&','',$url_);
        $url_ = str_replace('=','',$url_);
        $url_ = strtoupper($url_);
        $signature = sha1($url_);
        $url  = str_replace('&secret=640c7088ef7811e2a4e4005056991a1f','',$url);
        $url .= "&signature=".$signature;
        $re = $this->curl->post_files($url);
        $stas = json_decode($re,1);
        
        $this->addItem($stas);
        $this->output();
    }   
    
    /*public function get_subway_site()
    {   
        $subway_id = $this->input['subway_id'];
        $direction = 'end';
        $sql = "SELECT b.id,b.title FROM  " . DB_PREFIX ."subway_relation a " .
                "LEFT JOIN " . DB_PREFIX ."subway_site b ON a.site_id = b.id"  ." WHERE a.sub_id = ".$subway_id ." AND a.direction = '".$direction ."' ORDER BY a.order_id ASC";
        $q = $this->db->query($sql);
        $re = array();
        while($row = $this->db->fetch_array($q))
        {
            $re[] = $row;
        }
        
        $this->addItem($re);
        $this->output();
    }*/
    
    
    public function index()
    {   
    }
    

}

$out = new subwayApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();

?>
