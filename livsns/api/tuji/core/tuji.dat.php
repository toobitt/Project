<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
class tuji_data extends InitFrm
{
	function __construct() 
	{
		parent::__construct();
	}
	function __destruct() 
	{
		parent::__destruct();
	}
	function tuji_info($condition='', $orderby = ' t.create_time DESC ', $offset = 0, $count = 20, $pub_column = 0)
	{
		$limit = " limit {$offset}, {$count}";
        if (!$pub_column) {
    		$sql = "SELECT p.img_info as cover_img,t.*,s.id as sid,s.name as sort_name,s.brief,s.create_time as tuji_time,t.path  FROM "
    			.DB_PREFIX.'tuji as t LEFT JOIN '
    			.DB_PREFIX.'pics as p ON p.id = t.index_id LEFT JOIN '
    			.DB_PREFIX.'tuji_node as s ON t.tuji_sort_id=s.id WHERE 1 '
    			.$condition.$orderby.$limit;
        }
        else {
            $sql = "SELECT p.img_info as cover_img,t.*,s.id as sid,s.name as sort_name,s.brief,s.create_time as tuji_time,t.path  
                    FROM ".DB_PREFIX."tuji as t 
                    LEFT JOIN ".DB_PREFIX."pics as p 
                        ON p.id = t.index_id 
                    LEFT JOIN ".DB_PREFIX."tuji_node as s 
                        ON t.tuji_sort_id=s.id 
                    LEFT JOIN ".DB_PREFIX."pub_column pc
                         ON t.id = pc.aid
                    WHERE 1 " .$condition.$orderby.$limit;            
        }    
		//图集信息
		$tuji_info = array();
		$tuji_ids = array();
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$tuji_ids[] = $r['id'];
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:i:s',$r['update_time']);
			$r['tuji_time'] = date('Y-m-d h:i:s',$r['tuji_time']);
			$r['status_display'] = $r['status'];
			$r['status'] = $this->settings['image_upload_status'][$r['status']];
			$r['brief'] = $r['comment'];
			$r['cover_array'] = unserialize($r['cover_url']);
			$r['cover_url'] = hg_fetchimgurl($r['cover_array'], 160);
			$r['cover_img'] = hg_fetchimgurl(unserialize($r['cover_img']), 40,30);
			$r['img_count'] = $r['total_pic'];//获取每个图集里面对应的图片的个数,兼容原统计数
			$tuji_info[$r['id']] = $r;			
		}
		
		if($tuji_ids)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."pics WHERE tuji_id IN (".implode(',',$tuji_ids).")";
			$q_s = $this->db->query($sql);
			while($row = $this->db->fetch_array($q_s))
			{
				$tuji_info[$row['tuji_id']]['img_src'][] =  hg_fetchimgurl(unserialize($row['img_info']), 60, 45);
			}
		}
		return $tuji_info;
	}
	/**
	 * 
	 * 取图集描述信息
	 * @param array $tuji_id
	 */
	function tuji_description($tuji_id)
	{
		if($tuji_id&&is_array($tuji_id)){
		$tuji_ids = implode(',',$tuji_id);
		}
		elseif($tuji_id) {
		$tuji_ids = $tuji_id;
		}
		else {
			return array();
		}
		$sql = "SELECT id,comment FROM ".DB_PREFIX."tuji WHERE id IN (".$tuji_ids.")";
		$query = $this->db->query($sql);	
		while($row = $this->db->fetch_array($query))
		{
			$tuji_info[$row['id']]['description']=  $row['comment'];
		}
		return $tuji_info;
	}
	
	function news_refer_material($condition='', $orderby = ' t.create_time DESC ', $limit)
	{
		$sql = "SELECT t.*,s.id as sid,s.name as sort_name,s.brief,s.create_time as tuji_time,t.path  FROM "
			.DB_PREFIX.'tuji as t LEFT JOIN '
			.DB_PREFIX.'tuji_node as s ON t.tuji_sort_id=s.id WHERE 1 '
			.$condition.$orderby.$limit;
		//图集信息
		$tuji_info = array();
		$tuji_ids = array();
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
			$r['tuji_time'] = date('Y-m-d h:i:s',$r['tuji_time']);
			$r['time'] = hg_tran_time($r['update_time']);
			$r['status_display'] = $r['status'];
			$r['status'] = $this->settings['image_upload_status'][$r['status']];
			$r['img'] = unserialize($r['cover_url']);
			$r['brief'] = $r['comment'];
			$r['app_bundle'] = APP_UNIQUEID;
			$r['module_bundle'] = MOD_UNIQUEID;
			unset($r['cover_url']);
			$tuji_info[$r['id']] = $r;
		}
		return $tuji_info;
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
	
}
?>