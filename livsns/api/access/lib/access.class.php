<?php
class access extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $offset, $count)
	{
        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
        $this->publishcontent = new publishcontent();
        $content_type = $this->publishcontent->get_all_content_type();
        $pub_content_bundle = array();
        foreach ((array)$content_type as $k => $v)
        {
            $pub_content_bundle[] = $v['bundle_id'];
        }

        include_once(ROOT_PATH . 'lib/class/auth.class.php');
        $this->auth = new Auth();
        $app_info = $this->auth->get_app();
        $module = array();
        foreach ((array)$app_info as $k => $v)
        {
            if (!empty($v))
            {
                $module[$v['bundle']] = $v['name'];
            }
        }

		$limit = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT * FROM ".DB_PREFIX."nums WHERE 1 " . $condition . $limit;
		$q = $this->db->query($sql);
		$cidArr = array();
		$conArr = array();
        $other_content = array();
		while($row = $this->db->fetch_array($q))
		{
            if ( !in_array($row['app_bundle'], $pub_content_bundle) )
            {
                $row['bundle_name'] = $module[$row['app_bundle']];
                if (!$row['bundle_name'])
                {
                    $row['bundle_name'] = $this->settings["App_{$row['app_bundle']}"]['name'];
                }
                if (!$row['bundle_name'])
                {
                    $row['bundle_name'] = $row['app_bundle'];
                }
                $row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
                $row['content_url'] = $row['url'];
                $other_content[] = $row;
            }
            else
            {
			    $cidArr[] = $row['cid'];
			    $conArr[$row['cid']] = array('access_nums' => $row['access_nums'],'update_time' => date('Y-m-d H:i:s',$row['update_time']));
            }
		}
		$cidStr = implode(',',$cidArr);
//		include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
//		$this->publishcontent = new publishcontent();
		$ret = $this->publishcontent->get_content_by_cid($cidStr);
		if (!is_array($ret))
		{
			//return array();
		}
        $ret = (array)$ret;
        $arExistIds = array();
		foreach($ret as $k => $v)
		{
            $arExistIds[] = $v['cid'];
			$ret[$k]['bundle_name'] = $module[$v['bundle_id']];
            if (!$ret[$k]['bundle_name'])
            {
                $ret[$k]['bundle_name'] = $this->settings["App_{$v['bundle_id']}"]['name'];
            }
            if (!$ret[$k]['bundle_name'])
            {
                $ret[$k]['bundle_name'] = $v['bundle_id'];
            }
			$ret[$k] = array_merge($ret[$k],$conArr[$k]);
		}
        $ret = array_merge($ret, $other_content);
        //发布库删除没有更新统计时条数不准确 下面代码为解决此bug
        //对比cid差集
        $delCid = array_diff($cidArr, $arExistIds);
        //更新已经不存在的内容
        if (!empty($delCid))
        {
            $cid = implode(',', $delCid);
            $sql = "UPDATE ".DB_PREFIX."nums SET del = 1 WHERE cid IN(".$cid.")";
            $this->db->query($sql);
            include_once(CUR_CONF_PATH . 'lib/cache.class.php');
            $cache = new CacheFile();
            $table = $cache->get_cache('access_table_name');
            $table = convert_table_name($table);
            if($table)
            {
                $table_str = implode(',', $table);
            }
            $sql = "ALTER TABLE ".DB_PREFIX."merge UNION(".$table_str.")";
            $this->db->query($sql);
            $sql = "UPDATE ".DB_PREFIX."merge SET del = 1 WHERE cid IN(".$cid.")";
            $this->db->query($sql);
        }

//		switch($this->input['access_nums'])
//		{
//			case 1:
//				$info = hg_array_sort($ret,'access_nums','DESC');
//				break;
//			case 2:
//				$info = hg_array_sort($ret,'access_nums','ASC');
//				break;
//			default:
//				$info = hg_array_sort($ret,'update_time','DESC');
//		}
        $info = hg_array_sort($ret,'access_nums','DESC');
		return $info;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "nums WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);

		return $ret;
	}
	
    public function get_app($data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE father = 0" . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	public function get_module($data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE  father != 0" . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	public function get_some_module($father, $data_limit = '')
	{
		$sql = "SELECT * FROM " .DB_PREFIX ."app WHERE  father =" . $father . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}	
	
	/*
	public function add_access()
	{
		$data = unserialize(urldecode($this->input['data']));
		$app_bundle = $data['app_bundle'];
		$module_bundle = $data['module_bundle'];
		$cid = $data['cid'];
		$config = parse_ini_file("../conf/sql.ini");
		$cur_table = trim($config['table_name']) . trim($config['table_num']);
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . $cur_table;
		$r = $this->db->query_first($sql);
		$f = (int)$r['total'];
		$cur_table = $this->judge_cur_table($f,$app_bundle,$module_bundle);
		$sql = "INSERT INTO " . DB_PREFIX . $cur_table . " SET ";
		$space = '';
		foreach($data as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		$sql = "SELECT * FROM " . DB_PREFIX ."nums WHERE app_bundle = '" . $app_bundle . "' AND module_bundle = '" . $module_bundle . "' and cid = " . $cid;
		$info = $this->db->query_first($sql);
		$sql = '';
		if(empty($info))
		{
			$nums = array(
				'app_bundle' => $app_bundle,
				'module_bundle' => $module_bundle,
				'cid' => $cid,
				'access_nums' => 1,
				'is_sync' => 0,
				'last_sync_time' => $data['access_time'],
			);
			$sql = "INSERT INTO " . DB_PREFIX ."nums SET ";
			$space = '';
			foreach($nums as $k => $v)
			{
				$sql .= $space . $k ."='" . $v . "'";
				$space = ',';
			}
		}
		else
		{
			if($info['is_sync'])
			{
				$sql = "UPDATE " . DB_PREFIX . "nums SET access_nums = 1, is_sync = 0";
			}
			else
			{
				$sync_space = $this->get_sync_space($app_bundle,$module_bundle); 
				$sync_space *= 3600;
				if(TIMENOW-$info['last_sync_time'] >= $sync_space)
				{
					$this->sync($info['id']);
					$sql = "UPDATE " . DB_PREFIX ."nums SET is_sync = 1, last_sync_time =" . TIMENOW . " WHERE id IN(" . $info['id'] . ")";
				}
				else
				{
					$sql = "UPDATE " . DB_PREFIX . "nums SET access_nums = access_nums+1";
				}
			}
		}
		$this->db->query($sql);
		return true;
	}
	*/
	
    /*   判断是否需要创建新表并获取当前表   */
/*
	private function judge_cur_table($f,$app_bundle,$module_bundle)
	{
		$cur_table = '';
		$sql = "SELECT * FROM " . DB_PREFIX ."app_settings where bundle_id ='" . $app_bundle ."' AND module_id ='" . $module_bundle ."' AND var_name='MAX_RECORD'"; 
		$r = $this->db->query_first($sql);
		if(empty($r))
		{
			$sql="SELECT * FROM " . DB_PREFIX ."settings where var_name='MAX_RECORD'";
			$q = $this->db->query_first($sql);
			if((int)$q['value'] <= $f)
			{
				$config = parse_ini_file("../conf/sql.ini");
				$config['table_num'] = (int)trim($config['table_num'])+1;
				$config['start_num'] = (int)trim($config['start_num'])+$f;
				$table_name = DB_PREFIX . trim($config['table_name']);
				$search = array('table_name','###','start_num');
				$replace = array($table_name,$config['table_num'],$config['start_num']);
				$sql = str_replace($search,$replace,$config['sql']);
				$this->db->query($sql);
				write_ini_file($config,"../conf/sql.ini");
				$cur_table = trim($config['table_name']) . trim($config['table_num']);
			}
			else
			{
				$config = parse_ini_file("../conf/sql.ini");
				$cur_table = trim($config['table_name']) . trim($config['table_num']);
			}
		}
		else
		{
			if((int)$r['value']<=$f)
			{
				$config = parse_ini_file("../conf/sql.ini");
				$config['table_num'] = (int)trim($config['table_num'])+1;
				$config['start_num'] = (int)trim($config['start_num'])+$f;
				$table_name = DB_PREFIX . trim($config['table_name']);
				$search = array('table_name','###','start_num');
				$replace = array($table_name,$config['table_num'],$config['start_num']);
				$sql = str_replace($search,$replace,$config['sql']);
				$this->db->query($sql);
				write_ini_file($config,"../conf/sql.ini");
				$cur_table = trim($config['table_name']) . trim($config['table_num']);
			}
			else
			{
				$config = parse_ini_file("../conf/sql.ini");
				$cur_table = trim($config['table_name']) . trim($config['table_num']);
			}
		}
		return $cur_table;
	}
*/

    public function get_content($params = array(), $get_count = false)
    {
    	$timefield = 'create_time';
        if (!$params['start_time'] && !$params['duration'] && !$params['column_id'])
        {
            //不限制开始和时间和时长时表示所有查询所有内容 从汇总表 nums表查询
            if (!$get_count)
            {
                $sql = "SELECT cid, access_nums AS num, column_id, app_bundle, title, url FROM ".DB_PREFIX."nums WHERE 1 AND del = 0";
            }
            else
            {
                $sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."nums WHERE 1 AND del = 0";
            }
        }
        else
        {
        	$mergeall = false;
            if ($params['start_time'])
            {
                $start_time = $params['start_time'];
                if ($params['duration'])
                {
                    $time = $params['duration'] * 60;
                    $end_time = $params['start_time'] + $time;
                }
                else
                {
                	$timefield = 'create_time';
                    $end_time = TIMENOW;
                }

                if ($end_time < TIMENOW && $end_time < $params['last_time'])
                {
                    //continue;
                }
            }
            else if ($params['duration'])
            {
                $time = $params['duration'] * 60;
                $start_time = TIMENOW - $time;
                $end_time = $params['end_time'] ? $params['end_time'] : TIMENOW;
            }
            else
            {
            	$mergeall = true;
            }

            //查询存在的分表
            include_once(CUR_CONF_PATH . 'lib/cache.class.php');
            $cache = new CacheFile();
            $exists_table = $cache->get_cache('access_table_name');
            //查询存在的分表
			
			if (!$mergeall)
			{
				###根据起始时间关联merge表
				$start_year = intval(date('Y', $start_time));
				$start_month = intval(date('m', $start_time));
				$end_year = intval(date('Y', $end_time));
				$end_month = intval(date('m', $end_time));
				$table = array();
				$i = $start_year;
				$j = $start_month;
				while($i < $end_year || $j <= $end_month)
				{
					if ($i > $end_year) {  //年份大于等于当前年份时跳出循环
						break;
					}
					$j = strlen($j) != 2 ? '0' . $j : $j;
					$table_name = "record_" . $i . $j;
					if(in_array($table_name, $exists_table))
					{
						$table[] = $table_name;
					}
					if($j==12){
						$i++;$j=1;
					}else{
						$j++;
					}
				}
            }
            else
            {
            	$table = $exists_table;
            }

            $table = $this->convert_table_name($table);

            if($table)
            {
                $table_str = implode(',', $table);
            }
            $sql = "ALTER TABLE ".DB_PREFIX."merge UNION(".$table_str.")";
            $this->db->query($sql);
            ###关联结束
            if (!$get_count)
            {
                $sql = "SELECT cid,count(*) AS num, column_id, app_bundle, refer_url AS url FROM ".DB_PREFIX."merge WHERE 1 AND del = 0";
            }
            else
            {
                $sql = "SELECT COUNT(*) AS total FROM ( SELECT cid,count(*) AS num FROM ".DB_PREFIX."merge WHERE 1 AND del = 0";
            }
            if ($start_time)
            {
           		$sql .= " AND {$timefield} >= " . $start_time;
            }
            if ($end_time)
            {
           		$sql .= " AND {$timefield} <= " . $end_time;
            }
        }

        if($params['column_id'])
        {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $this->publish_column = new publishconfig();
            $ret = $this->publish_column->get_column_by_ids('id,childs',$params['column_id']);
            $idArr = array();
            if($ret && is_array($ret) )
            {
                foreach($ret as $k => $v)
                {
                    $idArr[] = $v['childs'];
                }
            }
            $idStr = implode(",",$idArr);
            if($idStr)
            {
                $sql .= " AND column_id IN(".$idStr.")";
            }
        }
        if($params['type'])
        {
            $params['type'] = explode(',', $params['type']);
            $params['type'] = implode("','", $params['type']);
            $sql .= " AND app_bundle IN('" . $params['type'] . "')";
        }

        if ($params['title'])
        {
            $sql .= ' AND title LIKE \'%'.trim(urldecode($params['title'])).'%\'';
        }

        if ($params['cid'])
        {
            $params['cid'] = explode(",", $params['cid']);
            $params['cid'] = implode("','", $params['cid']);
            $sql .= " AND cid IN('" . $params['cid'] . "')";
        }

        /** begin zbb 2015-4-20 加了一个筛选条件publish_duration **/
        /*
        if($params['publish_duration'] > 0){
            $sql .= ' AND publish_time > ' . (TIMENOW - $params['publish_duration'] * 60);
        }
        */
        /** end **/

        ###从merge表查询需要group by cid
        if ($params['output_type'] == 1)
        { //统计栏目
            $sql .= " GROUP BY column_id ";
        }
        else
        {
            $sql .= ($params['start_time'] || $params['duration'] || $params['column_id']) ? " GROUP BY cid " : "";
        }

        if (!$get_count)
        {
            $sql .= " ORDER BY num DESC ";
            if ($params['count'])
            {
                $sql .= " LIMIT ". intval($params['offset'])."," . intval($params['count']);
            }
            $q = $this->db->query($sql);
            $rankingCon = array();
            while($row = $this->db->fetch_array($q))
            {
                ($params['output_type'] == 1) && ($row['cid'] = $row['column_id']);  //排行类型为栏目时 记录column_id
                $rankingCon[] = $row;
            }
            return $rankingCon;
        }
        else
        {
            if ($params['start_time'] || $params['duration'])
            {
                $sql .= " ) AS a";
            }
            $q = $this->db->query_first($sql);
            return $q;
        }

    }

    function convert_table_name($tableName)
    {
        if(!$tableName)
        {
            return false;
        }
        if(is_array($tableName))
        {
            foreach($tableName as $k => $v)
            {
                $tableName[$k] = DB_PREFIX . $v;
            }
        }
        else
        {
            $tableName = DB_PREFIX . $tableName;
        }
        return $tableName;
    }
}
?>