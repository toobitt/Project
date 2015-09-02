<?php
define('ROOT_PATH','../../../');
class news extends InitFrm
{
    protected $outpush;
	public function __construct()
	{
		parent::__construct();
        require_once(ROOT_PATH.'lib/class/outpush.class.php');
        $this->outpush = new outpush();
    }

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_news_list($cond, $pub_column = 0,$access_token)
	{
        //查询outpush开关状态
        $appinfo = $this->outpush->getOutpushInfoByAppid(APPLICATION_ID,$access_token);

	    if (!$pub_column) {   //没有按发布库栏目搜索时不需要关联pub_column表
    		$sql = "SELECT a.* ,m.pic,s.name sort_name FROM " . DB_PREFIX . "article a 
    				LEFT JOIN " .DB_PREFIX . "sort s 
    					ON a.sort_id=s.id 
    				LEFT JOIN " . DB_PREFIX . "material m 
    					ON a.indexpic=m.material_id 
    				WHERE 1 " . $cond; //未删除的
        }
        else {
    		$sql = "SELECT a.*, m.pic, s.name sort_name FROM ". DB_PREFIX ."article a
    		        LEFT JOIN " . DB_PREFIX ."sort s
    		            ON a.sort_id = s.id
    		        LEFT JOIN ".DB_PREFIX."material m
    		            ON a.indexpic=m.material_id
    		        LEFT JOIN " . DB_PREFIX . "pub_column pc
    		            ON a.id = pc.aid    
                    WHERE 1 " . $cond;
        }      
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			if($row['indexpic'])
			{
				$row['pic'] = unserialize($row['pic']);
				$row['indexpic_url'] = hg_fetchimgurl($row['pic'],80,60);
			}
			if($row['catalog'])
			{
				$row['catalog'] = unserialize($row['catalog']);
			}			
			if ($row['column_id'])
			{
				$row['column_id'] = unserialize($row['column_id']);
			}
			else
			{
				$row['column_id'] = '';
			}			
			if ($row['column_url'])
			{
				$row['column_url'] = unserialize($row['column_url']);
			}
			else
			{
				$row['column_url'] = '';
			}
			$pub_column = array();
			if(is_array($row['column_id']))
			{
				foreach($row['column_id'] as $k => $v)
				{
					$pub_column[] = array(
						'column_id' => $k,
						'column_name' => $v,
						'pub_id' =>intval($row['column_url'][$k])
					);
				}
			}
			$row['pub_column'] = $pub_column;
			$row['create_time_show'] = date("Y-m-d H:i",$row['create_time']);
			$row['update_time_show'] = date("Y-m-d H:i",$row['update_time']);
			$row['pub_time']         = date("Y-m-d H:i",$row['pub_time']);
			$row['allpages'] = $row['content'];
			$row['pub'] = $row['column_id'];
			$row['pub_url'] = $row['column_url'];
			$row['block'] = $row['block']?unserialize($row['block']):array();
			switch ($row['state'])
			{
				case 0 :
					$row['audit'] = $row['status'] = '待审核';
					break;
				case 1 :
					$row['audit'] = $row['status'] = '已审核';
					break;
				default:
					$row['audit'] = $row['status'] = '已打回';
					break;
			}
			$row['status_display'] = $row['state'];

            //outpush加入判断
            if(!empty($appinfo)) {
                $row['outpush_status'] = $appinfo[0]['status'];
            }
            $info[$row['id']] = $row;
		}
		return $info;
	}
		
	public function count($cond, $join_pub_column = 0)
	{
	    if (!$join_pub_column) {
		  $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article a 
				  WHERE 1 " . $cond;
        }
        else {
            $sql = "SELECT COUNT(*) AS total FROM (
                        SELECT a.id FROM ".DB_PREFIX."article a 
                        LEFT JOIN ".DB_PREFIX."pub_column pc 
                            ON a.id=pc.aid 
                        WHERE 1 " . $cond ."
                    ) aa";
        }        
		$f = $this->db->query_first($sql);
		return $f;
	}	

	public function get_content($cond)
	{
		$sql = "SELECT a.*,c.*,m.pic,s.name sort_name FROM " . DB_PREFIX . "article a 
				LEFT JOIN " . DB_PREFIX . "article_contentbody c 
					ON a.id = c.articleid
				LEFT JOIN " . DB_PREFIX . "material m 
					ON a.indexpic = m.material_id  AND a.id = m.cid
				LEFT JOIN ".DB_PREFIX."sort s
					ON a.sort_id = s.id 
				WHERE 1 " . $cond ; 
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_article($cond, $field = '*')
	{
		$sql = "SELECT ". $field ." FROM " . DB_PREFIX . "article WHERE 1 AND " . $cond;
		$info = $this->db->query_first($sql);
		return $info;
	}
	public function get_article_list($cond, $field="*")
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."article WHERE 1 AND " . $cond;
		$q = $this->db->fetch_all($sql);
		return $q;
	}
	
	public function getIndexpic($mid) {
	    if (!$mid) {
	        return false;
	    } 
	    $sql = "SELECT pic FROM ".DB_PREFIX."material WHERE material_id = '".$mid ."' AND isdel=1";
        $info = $this->db->query_first($sql);
        $info['pic'] = $info['pic'] ? unserialize($info['pic']) : array();
        return $info['pic'];
	}
	
	public function getMaterialById($cid)
	{	
		if(!$cid)
			return false;
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE cid=" . $cid . " AND isdel=1"; //1表示没删除
		$q = $this->db->query($sql);
		$info = array();
		while(false != ($ret = $this->db->fetch_array($q)))
		{
			if(empty($ret))
			{
				continue;
			}
			switch($ret['mark'])
			{
				case 'img':
					$ret['pic'] = unserialize($ret['pic']);
					$info[$ret['material_id']] = $ret;
					$info[$ret['material_id']]['url'] = hg_fetchimgurl($ret['pic'],100,75);
					break;
				case 'doc':
					$info[$ret['material_id']] = $ret;
					break;
				default:
					break;
			}
		}
		return $info;
	}

    public function getMaterialByMid($mid)
    {
         if(!$mid)
         {
         	return false;	
         }
         if(stripos($mid, ',') === false)
         {
         	$mid = (int)$mid;
         }
        $sql = "SELECT * FROM " . DB_PREFIX . "material WHERE material_id IN(" . $mid . ") AND isdel=1"; //1表示没删除
        $q = $this->db->query($sql);
        $info = array();
        while(false != ($row = $this->db->fetch_array($q)))
        {
            if(empty($row))
            {
                continue;
            }
            $info[$row['material_id']] = $row;
        }
        return $info;
    }

    public function insert_data($data,$table)
	{
		if(!$table)
		{
			return false;
		}
		$sql="INSERT INTO " . DB_PREFIX .$table. " SET ";		
		if(is_array($data))
		{
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
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
	
	public function update($data, $table, $where = '') 
	{
		if($table == '' or $where == '') 
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$field = '';
		if(is_string($data) && $data != '') 
		{
			$field = $data;
		} 
		elseif (is_array($data) && count($data) > 0) 
		{
			$fields = array();
			foreach($data as $k=>$v) 
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		} 
		else 
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET '.$field.$where;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function delete($table, $where) 
	{
		if ($table == '' || $where == '') 
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . $where;
		return $this->db->query($sql);
	}
	
	/**
	 * 获取父级id
	 * @param int $id
	 * @return string
	 */
	public function getSortByFather($id)
	{
		$sort = $id;
		$sql = "SELECT * FROM " . DB_PREFIX ."sort WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		$sort = $f['parents'];
		return $sort;
	}
    	
	public function get_deled_material_by_mid($ids)
	{
		$sql = "SELECT material_id 
				FROM " . DB_PREFIX . "material 
				WHERE material_id IN (" . $ids . ") AND isdel=0";
		$ret = $this->db->fetch_all($sql); 
		return $ret;	
	}
    
    //修改文稿发布栏目分发表
    public function update_pub_column($ids, $column_ids) {
        if (!$ids) {
            return false;
        }
        $sql = "DELETE FROM " . DB_PREFIX . "pub_column WHERE aid IN(" . $ids . ")";
        $this->db->query($sql);
        
        if ($column_ids) {
            $arr_ids = explode(',', $ids);
            $ar_column_ids = explode(',', $column_ids);
            
            $sql = "INSERT INTO " . DB_PREFIX . "pub_column (aid, column_id) VALUES";
            $space = '';
            foreach ($arr_ids as $k => $v) {
                foreach ($ar_column_ids as $kk => $vv) {
                    $sql .= $space . " ('" . $v . "', '" . $vv . "')";
                    $space = ',';
                }
            }
            $this->db->query($sql);            
        }
        return true;
    }

    public function get_last_draft($uid)
    {
        if (!$uid)
        {
            return false;
        }
        $sql = "SELECT * FROM ".DB_PREFIX."draft WHERE 1 AND user_id = " . $uid . " ORDER BY create_time DESC LIMIT 1";
        $draft = $this->db->query_first($sql);
        $articleInfo = $this->get_article(" user_id = ".$uid.' ORDER BY id DESC','create_time');//查询该用户最新创建文稿时间        
        if($draft && ($draft['create_time'] - $articleInfo['create_time']) <= 10)//如果创建的草稿在此用户最新提交文稿的10秒之内则认为是不合法草稿
        {
        	$this->del_draft('AND user_id = '.$uid.' AND create_time <='.$draft['create_time']);
        	unset($draft);
        }
        if ($draft) {
            $draft['content'] = unserialize($draft['content']);
            $draft = $this->process_draft($draft);
        }
        return $draft;
    }
	public function del_draft($cond)
	{
		 $sql = "DELETE FROM ".DB_PREFIX."draft WHERE 1 ".$cond;
         return $this->db->query($sql);
	}
    public function get_auto_draft($uid)
    {
        if (!$uid)
        {
            return false;
        }
        $sql = "SELECT id FROM ".DB_PREFIX."draft WHERE 1 AND user_id = " . $uid . " AND isauto = 1";
        $draft = $this->db->query_first($sql);
        return $draft;
    }

    public function draft_detail($id)
    {
        if (!$id)
        {
            return false;
        }
        $sql = "SELECT * FROM ".DB_PREFIX."draft WHERE 1 AND ID = " . $id . " LIMIT 1";;
        $draft = $this->db->query_first($sql);
        if ($draft)
        {
            $draft['content'] = unserialize($draft['content']);
            $draft = $this->process_draft($draft);
        }
        return $draft;
    }

    public function process_draft($draft)
    {
        if(!empty($draft['content']['indexpic']))
        {
            //查找索引图
            $draft['content']['indexpic_url'] = $this->getIndexpic($draft['content']['indexpic']);
        }
        else
        {
            $draft['content']['indexpic_url'] = '';
        }
        if (!empty($draft['content']['material_id'])) {
            $material_id = implode(',', $draft['content']['material_id']);
            $ret = $this->getMaterialByMid($material_id);

            if(!empty($ret))
            {
                foreach($ret as $k => $v)
                {
                    $v['filesize'] = hg_bytes_to_size($v['filesize']);
                    switch($v['mark'])
                    {
                        case 'img':
                            //将缩略图信息加入info数组
                            $draft['content']['material'][$v['id']] = $v;
                            $draft['content']['material'][$v['id']]['path'] = $v['host'] . $v['dir'];
                            $draft['content']['material'][$v['id']]['dir'] = $v['filepath'];
                            $draft['content']['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);
                            break;
                        case 'doc':
                            $draft['content']['material'][$v['id']] = $v;
                            $draft['content']['material'][$v['id']]['path'] = $v['host'] . $v['dir'];
                            $draft['content']['material'][$v['id']]['dir'] = $v['filepath'];
                            $draft['content']['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);
                            break;
                        case 'real':
                            $draft['content']['material'][$v['id']] = $v;
                            break;
                        default:
                            break;
                    }
                    //$url = $v['host'] . $v['dir'] . $v['filepath'] . $v['filename'];
                    //$draft['content']['material'][$v['id']]['code'] = str_replace(array('{filename}','{name}'), array($url, $v['name']), $support_type[$v['mark']][$v['type']]['code']);
                }
            }
        }
        return $draft;
    }
	
    /**
     * 根据id得到article中一条完整的信息
     * @param number $id
     * @return info array
     */
	public function getNewInfoById($id = 0)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "article WHERE 1 AND id = " . $id;
		$info = $this->db->query_first($sql);
		return $info;
	}

    public function curlData()
    {
    }
			
}
?>
