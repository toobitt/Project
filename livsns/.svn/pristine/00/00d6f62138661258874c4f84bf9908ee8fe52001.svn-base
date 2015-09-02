<?php

require('global.php');
define('MOD_UNIQUEID', 'special'); //模块标识

class specialApi extends adminReadBase
{

    public function __construct()
    {
        $this->mPrmsMethods = array(
            'show' => '查看',
            'create' => '增加',
            'update' => '修改',
            'get_special_templates' => '快速专题',
            'delete' => '删除',
            'audit' => '审核',
            '_node' => array(
                'name' => '专题分类',
                'filename' => 'special_sort.php',
                'node_uniqueid' => 'special_sort',
            ),
        );
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/special.class.php');
        $this->obj          = new special();
        require_once(ROOT_PATH . 'lib/class/curl.class.php');
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function show()
    {
        $this->verify_content_prms();
        $condition = $this->get_condition();
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 12;
        $limit     = " limit {$offset}, {$count}";
        if($this->input['sort_type']=='ASC')
        {
        	$str = " ORDER BY a.order_id ".$this->input['sort_type'];
        }
        else
        {
        	$str = " ORDER BY a.order_id  DESC";
        }
        $ret       = $this->obj->show($condition, $limit, $this->user['appid'],'','',$str);
        $this->addItem($ret);
        $this->output();
    }

    public function detail()
    {
        #####
        $this->verify_content_prms(array('_action' => 'show'));
        #####

        $id = intval($this->input['id']);
        if ($id)
        {
            $data_limit = ' AND a.id=' . $id;
        }
        else
        {
            $data_limit = ' LIMIT 1';
        }
        $sql = 'SELECT a.*,b.name as sort_name
				FROM ' . DB_PREFIX . 'special a
				LEFT JOIN ' . DB_PREFIX . 'special_sort b
				ON  a.sort_id = b.id
			 	WHERE 1' . $data_limit;
        $r        = $this->db->query_first($sql);
        $sql_     = 'SELECT * FROM ' . DB_PREFIX . 'special_material  WHERE special_id =' . $id . ' AND del =0';
        $q        = $this->db->query($sql_);
        $summary  = $material = array();
        while ($row      = $this->db->fetch_array($q))
        {
            if ($row['mark'] != 'video')
            {
                $row['filesize'] = hg_bytes_to_size($row['filesize']);
                $row['material'] = unserialize($row['material']);
                $material[]      = $row;
            }
            else
            {
                $video[$row['id']] = unserialize($row['material']);
            }
        }
        $sq      = 'SELECT * FROM ' . DB_PREFIX . 'special_summary  WHERE special_id =' . $id . ' AND del=0';
        $q_      = $this->db->query($sq);
        $summary = array();
        while ($ro      = $this->db->fetch_array($q_))
        {
            $summary[] = $ro;
        }
        $r['column_id'] = unserialize($r['column_id']);
        if (is_array($r['column_id']))
        {
            $column_id = array();
            foreach ($r['column_id'] as $k => $v)
            {
                $column_id[] = $k;
            }
            $column_id      = implode(',', $column_id);
            $r['column_id'] = $column_id;
        }
        $r['pub_time']       = $r['pub_time'] ? date("Y-m-d H:i", $r['pub_time']) : date("Y-m-d H:i", TIMENOW);
        $r['pic']			 = $r['pic'] ? unserialize($r['pic']) : array();
        $r['top_pic']	 	 = $r['top_pic'] ? unserialize($r['top_pic']) : array();
        $r['client_pic']     = unserialize($r['client_pic']);
        $r['client_top_pic'] = unserialize($r['client_top_pic']);
        $r['material']       = $material;
        $r['video']          = $video;
        $r['summary']        = $summary;
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
        $sql           = 'SELECT count(*) as total from ' . DB_PREFIX . 'special a WHERE 1 ' . $this->get_condition();
        $special_total = $this->db->query_first($sql);
        echo json_encode($special_total);
    }

    /**
     * 检索条件应用，模块,操作，来源，用户编号，用户名
     * @name get_condition
     * @access private
     * @author gaoyuan
     * @category hogesoft
     * @copyright hogesoft
     */
    private function get_condition()
    {
        $condition = '';
        ####增加权限控制 用于显示####
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (!$this->user['prms']['default_setting']['show_other_data'])
            {
                $condition .= ' AND a.user_id = ' . $this->user['user_id'];
            }
            else
            {
                //组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND a.org_id IN('.$this->user['slave_org'].')';
				}
            }
            if ($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
            {
                $authnode_str = $authnode ? implode(',', $authnode) : '';
                if ($authnode_str === '0')
                {
                    $condition .= ' AND a.sort_id IN(' . $authnode_str . ')';
                }
                if ($authnode_str)
                {
                    $authnode_str   = intval($this->input['_id']) ? $authnode_str . ',' . $this->input['_id'] : $authnode_str;
                    $sql            = 'SELECT id,childs FROM ' . DB_PREFIX . 'special_sort WHERE id IN(' . $authnode_str . ')';
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
                        $condition .= ' AND a.sort_id IN(' . $authnode_str . ')';
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
                            $condition .= ' AND a.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
                        }
                    }
                }
            }
        }
        ####增加权限控制 用于显示####
        //查询应用分组
        if (intval($this->input['_id']) && intval($this->input['_id']) != '-1')
        {
            $sql_ = 'SELECT childs FROM ' . DB_PREFIX . 'special_sort WHERE id =' . intval($this->input['_id']);
            $q    = $this->db->query_first($sql_);
            $condition .=" AND a.sort_id  in (" . $q['childs'] . ")";
        }

        //查询
        if ($this->input['key'])
        {
            $condition .= " AND a.name LIKE '%" . trim(urldecode($this->input['key'])) . "%' ";
        }

        //查询分组
        if ($this->input['sort_id'] && $this->input['sort_id'] != -1)
        {
            $condition .= " AND  a.sort_id = '" . intval($this->input['sort_id']) . "'";
        }

        //查询创建的起始时间
        if ($this->input['start_time'])
        {
            $condition .= " AND a.create_time > " . strtotime($this->input['start_time']);
        }

        //查询创建的结束时间
        if ($this->input['end_time'])
        {
            $condition .= " AND a.create_time < " . strtotime($this->input['end_time']);
        }

        //查询权重
        if ($this->input['start_weight'] && $this->input['start_weight'] != -1)
        {
            $condition .=" AND a.weight >= " . $this->input['start_weight'];
        }
        if ($this->input['end_weight'] && $this->input['end_weight'] != -1)
        {
            $condition .=" AND a.weight <= " . $this->input['end_weight'];
        }

		if ($this->input['user_name'])
        {
            $condition .=" AND a.user_name = '" . $this->input['user_name']."'";
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
                    $condition .= " AND  a.create_time > '" . $yesterday . "' AND a.create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  a.create_time > '" . $today . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND a.create_time > '" . $last_threeday . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  a.create_time > '" . $last_sevenday . "' AND a.create_time < '" . $tomorrow . "'";
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
                    $condition .= " AND a.state= 0";
                    break;
                case 3://已审核
                    $condition .= " AND a.state = 1";
                    break;
                case 4: //已打回
                    $condition .=" AND a.state = 2";
                default:
                    break;
            }
        }
        return $condition;
    }

    //获取专题分类名称
    public function get_sort()
    {
        $sql  = "select id,name from " . DB_PREFIX . "special_sort where 1";
        $q    = $this->db->query($sql);
        $re   = $ret  = array();
        $sqll = "select sort_id from " . DB_PREFIX . "special where 1";
        $ql   = $this->db->query($sqll);
        while ($ar   = $this->db->fetch_array($ql))
        {
            $sorts[$ar['sort_id']] = $ar['sort_id'];
        }
        while ($r = $this->db->fetch_array($q))
        {
            $ret[$r['id']]['name'] = $r['name'];
            if ($sorts[$r['id']])
            {
                $ret[$r['id']]['is_last'] = '0';
            }
            else
            {
                $ret[$r['id']]['is_last'] = '1';
            }
        }
        $this->addItem($ret);
        $this->output();
    }

    //获取专题
    public function get_special()
    {
        $sort_id = $this->input['sort_id'];
        $sql     = "select id,name from " . DB_PREFIX . "special where sort_id = " . $sort_id;
        $q       = $this->db->query($sql);
        $ret     = array();
        $sqll    = "select distinct special_id from " . DB_PREFIX . "special_columns where 1";
        $ql      = $this->db->query($sqll);
        while ($ar      = $this->db->fetch_array($ql))
        {
            $specials[$ar['special_id']] = $ar['special_id'];
        }
        while ($r = $this->db->fetch_array($q))
        {
            $ret[$r['id']]['name'] = $r['name'];
            if ($specials[$r['id']])
            {
                $ret[$r['id']]['is_last'] = '0';
            }
            else
            {
                $ret[$r['id']]['is_last'] = '1';
            }
        }
        $this->addItem($ret);
        $this->output();
    }

    //using
    //获取专题栏目
    public function get_special_column()
    {
        $special_id = $this->input['special_id'];
        if (!$special_id) {
            $this->errorOutput(NO_SPECIALID);
        }
        $sql        = "select id,column_name from " . DB_PREFIX . "special_columns where special_id = " . $special_id;
        $q          = $this->db->query($sql);
        $ret        = array();
        while ($r = $this->db->fetch_array($q))
        {
            $ret[$r['id']] = array(
				'special_id' => $special_id,
                'column_name' => $r['column_name'],
                'id'		  => $r['id'],
                'name'		  => $r['column_name'],
                'is_last'	  => 1,	
            );
        }
        $this->addItem($ret);
        $this->output();
    }
    
    //using
    public function get_special_column_byid()
    {
    	$column_ids = $this->input['column_ids'];
    	if (!$column_ids) {
    		$this->errorOutput('NO ID');
    	}
    	$sql = "SELECT id,column_name,special_id 
    			FROM  " . DB_PREFIX . "special_columns
    			WHERE id IN(".$column_ids.")";
    	$q = $this->db->query($sql);
    	$ret = array();
    	while ($row = $this->db->fetch_array($q)) {
    		$ret[$row['id']] = array(
				'special_id'  => $row['special_id'],
                'column_name' => $row['column_name'],
                'id'		  => $row['id'],
                'name'		  => $row['column_name'],
                'is_last'	  => 1,	    		
    		);
    	}
    	$this->addItem($ret);
    	$this->output();
    }

    public function get_pub_special_by_id()
    {
        $ids_array = explode(',', $this->input['id']);
        foreach ($ids_array as $k => $v)
        {
            $sql = "SELECT a.id,a.column_name as column_name, b.name as special_name,c.name as sort_name FROM " . DB_PREFIX . "special_columns a 
					LEFT JOIN " . DB_PREFIX . "special b ON a.special_id = b.id
					LEFT JOIN " . DB_PREFIX . "special_sort c  ON  b.sort_id = c.id
					WHERE a.id = " . $v;
            $row = $this->db->query_first($sql);
            $this->addItem($row);
        }
        $this->output();
    }

    public function get_clients()
    {
        $curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
        $curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a', 'effective_app');
        $ret  = $curl->request('get_app_info.php');
        foreach ($ret[0] as $k => $v)
        {
            $clients[$v['appid']] = $v['custom_name'];
        }
        $this->addItem($clients);
        $this->output();
    }

    public function build_special()
    {
        $id     = $this->input['id'];
        $sql    = "select * from " . DB_PREFIX . "special where id = " . $id;
        $q      = $this->db->query_first($sql);
        $puburl = unserialize($q['column_url']);
        if (is_array($puburl))
        {
            foreach ($puburl as $k => $v)
            {
                $pub_ids[] = $v;
            }
        }
        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
        $this->puc = new publishcontent();
        if ($pub_ids && is_array($pub_ids))
        {
            foreach ($pub_ids as $v)
            {
                $this->puc->mk_content_by_rid($v);
            }
        }
    }

	/**
     * 获取专题信息
     * */
    public function get_special_node()
    {
        $condition = '';
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $limit     = " limit {$offset}, {$count}";
        $fid       = $this->input['fid'];

        if (strpos($fid, "sort") !== false)
        {
            $sort_id = intval($fid);
        }
        elseif (strpos($fid, "spe") !== false)
        {
            $special_id = intval($fid);
        }
        $ret = $this->special->show_special($sort_id, $special_id, $limit);
        if ($ret)
        {
            foreach ($ret as $k => $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }
    
    public function get_pubauth()
    {
        if($this->settings['App_publishsys'])
        {
        	$re['pubpath'] = '1';
        }
        else
        {
        	$re['pubpath'] = '';
        }
        
        if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			$re['spetem_auth'] = '1';
		}
		else
		{
			$actions  = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
			if($actions && is_array($actions))
			{
				if(!in_array('get_special_templates',$actions))
				{
					$re['spetem_auth'] = '';
				}
				else
				{
					$re['spetem_auth'] = '1';
				}
			}
		}
        $this->addItem($re); 
        $this->output();
    }
    
    
    public function get_spetem_auth()
	{
		/*if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			$re[] = '1';
		}
		else
		{
			$actions  = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
			if($actions && is_array($actions))
			{
				if(!in_array('get_special_templates',$actions))
				{
					$re[] = '';
				}
				else
				{
					$re[] = '1';
				}
			}
		}*/
		$re[] = '1';
		$this->addItem($re);
		$this->output();
	}
	
	public function get_module()
	{
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		/*$apps = $this->auth->get_app('bundle,name');
		if(is_array($apps))
		{
			foreach($apps as $k=>$v)
			{
				$apps_arr[$v['bundle']] = $v['name'];
			}
		}*/
		
		$modules = $this->auth->get_module('mod_uniqueid,name');
		if(is_array($modules))
		{
			foreach($modules as $k=>$v)
			{
				$modules_arr[$v['mod_uniqueid']] = $v['name'];
			}
		}
		$this->addItem($modules_arr);
		$this->output();
	}
	
    public function index()
    {
        
    }
    
    //工作量获取数据接口
    public function statistics()
    {
    	$return['static'] = 1;
   		$static_date = $this->input['static_date'];
    	if($static_date)
    	{
    		$date = strtotime($static_date);
    	}
    	else 
    	{
    		$date = strtotime(date("Y-m-d 00:00:00",strtotime("-1 day")));
    	}
    	$sql = 'SELECT id,state,user_id,user_name,org_id,column_id,expand_id FROM '.DB_PREFIX.'special where create_time >= '.$date .' and create_time < '. ($date+86400);
    	$query = $this->db->query($sql);
    	while($r = $this->db->fetch_array($query))
    	{
    		$ret[$r['user_id']]['org_id'] = $r['org_id'];
    		$ret[$r['user_id']]['user_name'] = $r['user_name'];
    		$ret[$r['user_id']]['count']++;
    		$r['state'] == 1 ? $ret[$r['user_id']]['statued']++ : false;
    		$r['state'] == 2 ? $ret[$r['user_id']]['unstatued']++ : false;
    		$r['column_id'] ? $ret[$r['user_id']]['publish']++ : false;
    		$r['expand_id'] ? $ret[$r['user_id']]['published']++ : false;
    		if($r['column_id'])
    		{
    			$columns = unserialize($r['column_id']);
    			if($columns)
    			foreach ($columns as $column_id => $column_name)
    			{
	    			$ret[$r['user_id']]['column'][$column_id]['column_name'] = $column_name;
	    			$ret[$r['user_id']]['column'][$column_id]['total']++;
    				if($r['expand_id'])
	    			{
	    				$ret[$r['user_id']]['column'][$column_id]['success']++;
	    			}
    			}
    		}
     	}
     	$return['data'] = $ret;
    	$this->addItem($return);
    	$this->output();
    }
}

$out    = new specialApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
