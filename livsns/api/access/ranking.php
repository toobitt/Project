<?php
require('global.php');
define('MOD_UNIQUEID','ranking');
define(SCRIPT_NAME,'Ranking');
class Ranking extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		$id = intval($this->input['sort_id']);
		$column_id = ($this->input['column_id']);
		$exclude_column_id = ($this->input['exclude_column_id']);
		if($id)
		{
			$sql = "SELECT status,output_type,column_id FROM ".DB_PREFIX."ranking_sort WHERE id = " . $id;
			$sort = $this->db->query_first($sql);
			if(empty($sort) || $sort['status'] != 1)
			{
				$this->errorOutput("unexist or unaudited");
			}

            include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
            $this->publishcontent = new publishcontent();
            $content_type = $this->publishcontent->get_all_content_type();
            $pub_content_bundle = array();
            foreach ((array)$content_type as $k => $v)
            {
                $pub_content_bundle[] = $v['bundle_id'];
            }

			$offset = intval($this->input['offset']) ? intval($this->input['offset']) : 0;
			$count  = intval($this->input['count']) ? intval($this->input['count']) : 30;
			$data_limit = " LIMIT " . $offset . ", " . $count;
			$sql = "SELECT * FROM ".DB_PREFIX."ranking_cont WHERE sort_id = " . $id . " ORDER BY count DESC " . $data_limit;
			$q = $this->db->query($sql);
			$cidArr = array();
			$conArr = array();
            $other_content = array();
			while($row= $this->db->fetch_array($q))
			{
                if ( $row['app_bundle'] && !in_array($row['app_bundle'], $pub_content_bundle) )
                {
                    $row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
                    $row['content_url'] = $row['url'];
                    $other_content[] = $row;
                }
                else
                {
				    $cidArr[] = $row['cid'];
				    $conArr[$row['cid']] = $row['count'];
                }
			}
			$cidStr = implode(',',$cidArr);
            $blDirectReturn = ($this->input['direct_return'] && ($this->input['direct_return'] != 'false')) ? 1 : 0; 
            if ($blDirectReturn) {
                $this->addItem($cidStr);
            } else {
                if ($cidStr) {
                    if ($sort['output_type'] == 1) {
                        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
                        $this->publishconfig = new publishconfig();
                        $ret = $this->publishconfig->get_column_info_by_ids($cidStr);
                    }
                    else {
                        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                        $this->publishtcontent = new publishcontent();
                        $useColumn = array();
                        if ($column_id)
                        {
                        	$sort['column_id'] = $column_id;
                        }
                        if ($exclude_column_id)
                        {
                        	$exclude_column_id = explode(',', $exclude_column_id);
                        	$sort['column_id'] = explode(',', $sort['column_id']);
                        	$sort['column_id'] = array_diff($sort['column_id'], $exclude_column_id);
                        	$sort['column_id'] = implode(',', $sort['column_id']);
                        }
                        foreach($cidArr as $cv){
                            $useColumn[$cv] = $sort['column_id'] ;
                        }
                        $ret = $this->publishtcontent->get_content_by_cid($cidStr, $useColumn);
                    }
                    $arExistIds = array();
                    if (is_array($ret) && count($ret) > 0) {
                        foreach($ret as $k => $v) {
                            $arExistIds[] = ($sort['output_type'] == 1) ? $v['id'] : $v['cid'];
                            //栏目时统计记录的是id  内容统计记录的是cid
                            $ret[$k]['count'] = ($sort['output_type'] == 1) ? $conArr[$v['id']] : $conArr[$v['cid']]; 
                        }
                    }
                    
                    //发布库删除没有更新统计时条数不准确 下面代码为解决此bug
                    //对比cid差集
                    $cidStr = explode(',', $cidStr);
                    $delCid = array_diff($cidStr, $arExistIds);
                    //更新已经不存在的内容
                    if (0 && !empty($delCid)) {
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
                        $sql = "DELETE FROM " .DB_PREFIX."ranking_cont WHERE sort_id=" . $id . " AND cid IN(".$cid.")";
                        $this->db->query($sql);                     
                    }
                    //
                    
                    $ret = (array)$ret;
                    $ret = array_merge($other_content, $ret);
                    if (is_array($ret) && count($ret)) {
                        $ret = hg_array_sort($ret,'count','DESC');                  
                        foreach($ret as $k => $v) {
                            if ($sort['output_type'] == 1) {
                                $v['title'] = $v['name'];
                            }
                            $this->addItem($v);
                        }
                    }                                                   
                }                
            }
		}
		else
		{
			$sql = "SELECT * FROM ".DB_PREFIX."ranking_sort WHERE status = 1 ";
			$q = $this->db->query($sql);
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				$ret[] = $row;
			}
			if($ret)
			{
				foreach($ret as $k => $v)
				{
					$this->addItem($v);
				}
			}
		}
		$this->output();		
	}
	
	public function detail(){}
	public function count(){}
}
require(ROOT_PATH . 'excute.php');
?>
