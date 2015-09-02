<?php

//模板的数据库操作

class special extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //新增模板
    public function create($info)
    {
        //插入数据操作
        $sql       = "INSERT INTO " . DB_PREFIX . "special SET ";
        $sql_extra = $space     = '';
        foreach ($info as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    //更新模板相关信息
    public function update($info)
    {
        //更新数据操作
        $sql       = "UPDATE " . DB_PREFIX . "special SET ";
        $sql_extra = $space     = '';
        foreach ($info as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id =" . $info['id'];
        $this->db->query($sql);
    }

    //删除专题
    public function delete($ids)
    {
    	$sqll = "select * from " . DB_PREFIX . "special where id in(" . $ids .")";
		$ret = $this->db->query($sqll);
		while($row = $this->db->fetch_array($ret))
		{
			$pre[] = $row;
		}
		
        $sql = "DELETE FROM " . DB_PREFIX . "special WHERE id IN (" . $ids . ")";
        $this->db->query($sql);

        $sql_ = "DELETE FROM " . DB_PREFIX . "special_columns WHERE special_id IN (" . $ids . ")";
        $this->db->query($sql_);
        
        if(count($pre) >1)
        {
        	$this->addLogs('删除专题',$pre,'', '删除专题  ' . $ids);
        }
        else
        {
        	$name = $pre[0]['name'];
        	$this->addLogs('删除专题',$pre,'', '删除专题  ' . $name);
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    //根据条件查询专题
    public function show($condition, $limit, $appid = '', $need_summary=0, $need_material=0,$str='')
    {
        $sql   = "SELECT a.*,b.name as sort_name
				FROM  " . DB_PREFIX . "special  a
				LEFT JOIN " . DB_PREFIX . "special_sort b
				ON a.sort_id=b.id WHERE 1" . $condition . $str. $limit;
        $q     = $this->db->query($sql);

        while ($row = $this->db->fetch_array($q))
        {
        	$id = $row['id'];
        	$special_idarr[] = $id;
        	
            if (!$row['sort_name'])
            {
                $row['sort_name'] = '未分类';
            }
            $row['create_time_show'] = date("Y-m-d H:i", $row['create_time']);
            $row['update_time_show'] = date("Y-m-d H:i", $row['update_time']);
            if ($row['pub_time'])
            {
                $row['pub_time'] = date("Y-m-d H:i", $row['pub_time']);
            }
            if ($row['column_id'])
            {
                $row['pub'] = unserialize($row['column_id']);
            }
            if ($row['column_url'])
            {
                $row['pub_url'] = unserialize($row['column_url']);
            }
            if ($row['client_pic'])
            {
                $client_pic = unserialize($row['client_pic']);
                if ($appid)
                {
                    $app_pic = $client_pic[$appid];
                }
                if ($app_pic)
                {
                    $row['pic'] = serialize($app_pic);
                }
            }
            if ($row['pic'])
            {
                $row['pic'] = unserialize($row['pic']);
                $row['indexpic'] = $row['pic'];
            }
            unset($row['client_pic']);
            if ($row['client_top_pic'])
            {
                $client_top_pic = unserialize($row['client_top_pic']);
                if ($appid && isset($client_top_pic[$appid]))
                {
                    $app_top_pic = $client_top_pic[$appid];
                }
                if ($app_top_pic)
                {
                    $row['top_pic'] = serialize($app_top_pic);
                }
            }
            if ($row['top_pic'])
            {
                $row['top_pic'] = unserialize($row['top_pic']);
                $row['icon']['icon_1']['default'] = $row['top_pic'];
                $row['icon']['icon_1']['activation'] = $row['top_pic'];
            }
            
            $row['content'] = $row['brief'] = strip_tags(htmlspecialchars_decode($row['brief']));;
            unset($row['client_top_pic']);
            switch ($row['state'])
            {
                case 0 :
                    $row['audit']  = $row['status'] = '待审核';
                    break;
                case 1 :
                    $row['audit']  = $row['status'] = '已审核';
                    break;
                default:
                    $row['audit']  = $row['status'] = '已打回';
                    break;
            }
            $ret[] = $row;
        }
        if($special_idarr && is_array($special_idarr))
        {
        	 $specialids = implode(',',$special_idarr);
        }
    	if($need_summary && $specialids)
        {
        	$sqs      = 'SELECT * FROM ' . DB_PREFIX . 'special_summary  WHERE special_id in(' . $specialids . ') AND del=0';
	        $qs_      = $this->db->query($sqs);
	        $summary = array();
	        while ($ro      = $this->db->fetch_array($qs_))
	        {
	            $summary[$ro['special_id']][] = $ro;
	        }
        }
        if($need_material && $specialids)
        {
        	$sqlm_     = 'SELECT * FROM ' . DB_PREFIX . 'special_material  WHERE special_id in(' . $specialids . ') AND del =0';
	        $mq        = $this->db->query($sqlm_);
	        $video = $material = $met = array();
	        while ($mro     = $this->db->fetch_array($mq))
	        {
	            if ($mro['mark'] != 'video')
	            {
	                $mro['filesize'] = hg_bytes_to_size($mro['filesize']);
	                $mro['material'] = unserialize($mro['material']);
	                $material[]      = $mro;
	            }
	            else
	            {
	                $video[$mro['id']] = unserialize($mro['material']);
	            }
	            if($material)
		        {
		        	$met[$mro['special_id']]['mer'] = $material;
		        }
		      	if($video)
		      	{
		      		$met[$mro['special_id']]['video'] = $video;
		      	}
		    }
        }
        if($specialids)
        {
        	$sq  = "select distinct special_id from " . DB_PREFIX . "special_content where special_id in(" . $specialids . ")";
                if (isset($this->input['content_weight']) && $this->input['content_weight'] !== '' && $this->input['content_weight'] != -1)
                {
                    $sq .= " AND weight=".intval($this->input['content_weight']);
                }
	      	$ss = $this->db->query($sq);
	      	while ($sps     = $this->db->fetch_array($ss))
	        {
	        	$specials[$sps['special_id']] = $sps;
	        }
        }
      	
      	if($ret)
      	{
      		foreach($ret as $k=>$v)
      		{
      			if($specials[$v['id']])
      			{
      				$v['is_content'] = '1';
      			}
      			 else
			    {
			        $v['is_content'] = '0';
			    }
			    if($summary[$v['id']])
			    {
			    	$v['summary'] = $summary[$v['id']];
			    }
			    if($met[$v['id']])
			    {
			    	$v['material'] = $met[$v['id']];
			    }
			    $return[] = $v;
      		}
      	}
      	
        return $return;
    }

    //根据条件查询专题相关信息
    public function show_special($sort_id = '', $special_id = '', $limit)
    {
        if (!$sort_id && !$special_id)
        {
            $sql = "SELECT *
					FROM  " . DB_PREFIX . "special_sort
					WHERE 1 AND fid =0  ORDER BY id DESC " . $limit;
            $q   = $this->db->query($sql);
            while ($row = $this->db->fetch_array($q))
            {
                $childs = $row['childs'];
                $s      = "SELECT * 	
					  FROM  " . DB_PREFIX . "special
					  WHERE sort_id in ( " . $childs . ")";
                $sp     = $this->db->query_first($s);

                if ($sp)
                {
                    $row['is_last'] = 0;
                }
                $row['id'] = $row['id'] . 'sort';
                $ret[]     = $row;
            }
            $ret[] = array(
                'id' => '-1' . 'sort',
                'name' => '全部专题',
                'is_last' => '0',
                'can_select'=>'1',
            );
        }
        elseif ($sort_id && !$special_id)
        {
            $condition = '';
            if ($sort_id != '-1')
            {
                $sl  = "SELECT childs
					   FROM  " . DB_PREFIX . "special_sort
					   WHERE id =" . $sort_id;
                $ch  = $this->db->query_first($sl);
                $chs = $ch['childs'];

                $condition = ' AND sort_id in(' . $chs . ')';
            }
            $sql_ = "SELECT *
					 FROM  " . DB_PREFIX . "special
					 WHERE 1" . $condition . " ORDER BY id DESC " . $limit;
            $q_   = $this->db->query($sql_);
            while ($row_ = $this->db->fetch_array($q_))
            {
                $row_['id']      = $row_['id'] . 'spe';
                $row_['is_last'] = '0';
                $row_['can_select'] = '1';
                $ret[]           = $row_;
            }
        }
        else
        {
            $sqll = "SELECT *
					 FROM  " . DB_PREFIX . "special_columns
					 WHERE special_id = " . $special_id . " ORDER BY id DESC " . $limit;
            $ql   = $this->db->query($sqll);
            while ($ro   = $this->db->fetch_array($ql))
            {
                $ro['name'] = $ro['column_name'];
                $ro['id']   = $ro['id'] . 'col';
                $ret[]      = $ro;
            }
        }

        return $ret;
    }

    //根据条件查询专题
    public function get_special_column($special_id)
    {
        /* $sql = "SELECT *
          FROM  " . DB_PREFIX ."special
          WHERE 1".$condition." ORDER BY create_time DESC ".$limit;
          $q = $this->db->query($sql);
          $sql_ = "select name,id from " . DB_PREFIX . "special_sort where 1";
          $sorts = $this->db->fetch_all($sql_);

          $sq = "select distinct special_id from " . DB_PREFIX . "special_content where 1";
          $specials = $this->db->fetch_all($sq);

          while($row = $this->db->fetch_array($q))
          {

          } */
    }

	//每一级分类
	public function get_special_sort($fid=0)
	{
	
		$sql = 'SELECT * FROM '.DB_PREFIX.'special_sort WHERE fid=' . intval($fid) .' ORDER BY order_id ASC';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$re[] = $row;
		}
		return $re;
	}
	
    /* 删除专题栏目
     * @param array  $colunmns
     * @param int $special_id
     */

    function delete_special_colunmns($special_id, $colunmn_ids)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "special_columns WHERE special_id = " . $special_id . " AND id in(" . $colunmn_ids . ")";
        $this->db->query($sql);
    }

    /* 插入专题栏目
     * @param array  $colunmns
     * @param int $special_id
     */

    function insert_special_colunmns($special_id, $colunmns)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "special_columns(
					special_id,
					column_name)VALUES";
        for ($i = 0; $i < count($colunmns); $i++)
        {
            $sql .="   (
					'$special_id',
					'{$colunmns[$i]}'),";
        }
        $sql_ = substr("$sql", 0, -1);
        $this->db->query($sql_);
        return $this->db->insert_id();
    }

    function insert_special_summary($special_id, $title, $content)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "special_summary(
					special_id,
					title,
					content)VALUES";
        for ($i = 0; $i < count($title); $i++)
        {
            $sql .="   (
					'$special_id',
					'{$title[$i]}',
					'{$content[$i]}'),";
        }
        $sql_ = substr("$sql", 0, -1);
        $this->db->query($sql_);
        return $this->db->insert_id();
    }

    //更新专题栏目
    public function update_special_colunmns($info)
    {
        //更新数据操作
        $sql       = "UPDATE " . DB_PREFIX . "special_columns SET ";
        $sql_extra = $space     = '';
        foreach ($info as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id =" . $info['id'];
        $this->db->query($sql);
    }

    public function get_special_list($cond, $field = "*")
    {
        $sql = "SELECT " . $field . " FROM " . DB_PREFIX . "special WHERE 1 AND " . $cond;
        $q   = $this->db->fetch_all($sql);
        return $q;
    }

    public function get_special($cond, $field = '*')
    {
        $sql  = "SELECT " . $field . " FROM " . DB_PREFIX . "special WHERE 1 AND " . $cond;
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function update_special($data, $table, $where = '')
    {
        if ($table == '' or $where == '')
        {
            return false;
        }
        $where = ' WHERE ' . $where;
        $field = '';
        if (is_string($data) && $data != '')
        {
            $field = $data;
        }
        elseif (is_array($data) && count($data) > 0)
        {
            $fields = array();
            foreach ($data as $k => $v)
            {
                $fields[] = $k . "='" . $v . "'";
            }
            $field = implode(',', $fields);
        }
        else
        {
            return false;
        }
        $sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $field . $where;
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    public function insert_data($data, $table)
    {
        if (!$table)
        {
            return false;
        }
        $sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
        if (is_array($data))
        {
            $sql_extra = $space     = ' ';
            foreach ($data as $k => $v)
            {
                $sql_extra .=$space . $k . "='" . $v . "'";
                $space = ',';
            }
            $sql .=$sql_extra;
        }
        else
        {
            $sql .= $data;
        }
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    public function get_special_by_id($special_id,$appid=0)
    {
        $sql      = "SELECT * FROM " . DB_PREFIX . "special WHERE id =  " . $special_id;
        $r        = $this->db->query_first($sql);
        $sql_     = 'SELECT * FROM ' . DB_PREFIX . 'special_material  WHERE special_id =' . $special_id . ' AND del =0';
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
        $sq      = 'SELECT * FROM ' . DB_PREFIX . 'special_summary  WHERE special_id =' . $special_id . ' AND del=0';
        $q_      = $this->db->query($sq);
        while ($ro      = $this->db->fetch_array($q_))
        {	
        	unset($ro['del']);
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
        $r['column_url']     = $r['column_url'] ? unserialize($r['column_url']) : array();
        
        if ($r['client_pic'])
        {
            $client_pic = unserialize($r['client_pic']);
            if ($appid)
            {
                $app_pic = $client_pic[$appid];
            }
            if ($app_pic)
            {
                $r['pic'] = serialize($app_pic);
            }
        }
        if ($r['pic'])
        {
            $r['pic'] = unserialize($r['pic']);
            $r['indexpic'] = $r['pic'];
        }
        unset($r['client_pic']);
        if ($r['client_top_pic'])
        {
            $client_top_pic = unserialize($r['client_top_pic']);
            if ($appid && isset($client_top_pic[$appid]))
            {
                $app_top_pic = $client_top_pic[$appid];
            }
            if ($app_top_pic)
            {
                $r['top_pic'] = serialize($app_top_pic);
            }
        }
        if ($r['top_pic'])
        {
            $r['top_pic'] = unserialize($r['top_pic']);
            $r['icon']['icon_1']['default'] = $r['top_pic'];
            $r['icon']['icon_1']['activation'] = $r['top_pic'];
        }
        unset($r['client_top_pic']);
            
        $r['material']       = $material;
        $r['video']          = $video;
        $r['summary']        = $summary;

        return $r;
    }

}

?>