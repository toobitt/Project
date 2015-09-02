<?php
class contribute_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "contribute WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            $r['publish_id'] = 0;
            $r['status_text'] = $this->settings['general_publish_status'][$r['status']];
            $r['content_image'] = unserialize($r['content_image']);
            $r['column_path'] = html_entity_decode(hg_clean_value($r['column_path']));
            if($r['status'] == 1)
            {
                $content_ids_arr[] = $r['content_id'];
            }
			$info[] = $r;
		}

        //投稿如果发布了 取出发布库的id
        if($content_ids_arr)
        {
            $content_ids = implode(",",$content_ids_arr);
            $sql = "SELECT id,publish_id FROM " . DB_PREFIX . "content WHERE id IN ({$content_ids})";
            $q = $this->db->query($sql);
            while($r = $this->db->fetch_array($q))
            {
                $content_info[] = $r;
            }
        }

        if($info && $content_info)
        {
            foreach($info as $k=>$v)
            {
                foreach($content_info as $ko=>$vo)
                {
                    if($v['content_id'] == $vo['id'])
                    {
                        $info[$k]['publish_id'] = $vo['publish_id'];
                    }
                }
            }
        }

		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "contribute SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."contribute SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}

    public function createContent($data = array())
    {
        if(!$data)
        {
            return false;
        }

        $sql = " INSERT INTO " . DB_PREFIX . "contribute_content SET ";
        foreach ($data AS $k => $v)
        {
            $sql .= " {$k} = '{$v}',";
        }
        $sql = trim($sql,',');
        $this->db->query($sql);
        $vid = $this->db->insert_id();
        return $vid;
    }
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "contribute WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "contribute SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}

    public function updateBycid($content_id,$data = array())
    {
        if(!$data || !$content_id)
        {
            return false;
        }
        //查询出原来
        $sql = " SELECT * FROM " .DB_PREFIX. "contribute WHERE content_id IN(" .$content_id. ")";
        $pre_data = $this->db->query_first($sql);
        if(!$pre_data)
        {
            return false;
        }

        //更新数据
        $sql = " UPDATE " . DB_PREFIX . "contribute SET ";
        foreach ($data AS $k => $v)
        {
            $sql .= " {$k} = '{$v}',";
        }
        $sql  = trim($sql,',');
        $sql .= " WHERE content_id IN ("  .$content_id. ")";
        $this->db->query($sql);
        return $pre_data;
    }
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT *,c.id as id FROM " . DB_PREFIX . "contribute  c LEFT JOIN " . DB_PREFIX . "contribute_content cc ON  c.id=cc.id WHERE c.id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
        $info['status_text'] = $this->settings['general_publish_status'][$info['status']];
        $info['content'] = $info['content'];
        $info['content_image'] = unserialize($info['content_image']);
        $info['column_path'] = html_entity_decode(hg_clean_value($info['column_path']));
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contribute WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "contribute WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "contribute WHERE id IN (" . $id . ")";
		$this->db->query($sql);
        $sql = " DELETE FROM " .DB_PREFIX. "contribute_content WHERE id IN (" . $id . ")";
        $this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '',$status)
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "contribute WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		$sql = " UPDATE " .DB_PREFIX. "contribute SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}

    public function auditBycid($content_id = '',$status)
    {
        if(!$content_id)
        {
            return false;
        }
        //查询出原来
        $sql = " SELECT * FROM " .DB_PREFIX. "contribute WHERE content_id IN (" .$content_id. ")";
        $pre_data = $this->db->query_first($sql);
        if(!$pre_data)
        {
            return false;
        }

        $sql = " UPDATE " .DB_PREFIX. "contribute SET status = '" .$status. "' WHERE content_id IN (" .$content_id. ")";
        $this->db->query($sql);
        return array('status' => $status,'id' => $content_id);
    }
}
?>