<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('SCRIPT_NAME','Reranking');
define('MOD_UNIQUEID','reranking');
set_time_limit(0);
class Reranking extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '排行队列',
				'brief' => '访问统计排行队列',
				'space' => '3600',	//运行时间间隔，单位秒
				'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}	
	function show()
	{
		if ($this->input['id'])
		{
			$cond = ' AND id=' . intval($this->input['id']);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."ranking_sort WHERE status = 1{$cond} ORDER BY last_time ASC LIMIT 10";
		$qq = $this->db->query($sql);
		include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->publishcontent = new publishcontent();
        include(CUR_CONF_PATH . 'lib/access.class.php');
        $this->obj = new access();
		include_once(CUR_CONF_PATH . 'lib/cache.class.php');
		$cache = new CacheFile();
		$exists_table = $cache->get_cache('access_table_name');
		while($info = $this->db->fetch_array($qq))
		{
            $params = array(
                'start_time' => $info['start_time'],
                'duration'   => $info['duration'],
                'last_time'  => $info['last_time'],
                'column_id'  => $info['column_id'],
                'type'       => $info['type'],
                'output_type'=> $info['output_type'],
                'count'      => $info['limit_num'],
                'title'      => $info['k'],  //搜索关键字
                'publish_duration'      => $info['publish_duration'],  //发布时间间隔
            );
            $rankingCon = $this->obj->get_content($params);

			$sql = "DELETE FROM ".DB_PREFIX."ranking_cont WHERE sort_id = " . $info['id'];
			$this->db->query($sql);		
			if(!empty($rankingCon))
			{
                //查询发布到发布库的内容标识,用于判断内容是否是发布库数据
                $content_type = $this->publishcontent->get_all_content_type();
                $pub_content_bundle = array();
                foreach ((array)$content_type as $k => $v)
                {
                    $pub_content_bundle[] = $v['bundle_id'];
                }

				$rankingChunk = array_chunk($rankingCon,30,true);
				foreach($rankingChunk as $key => $val)
				{
					$sql = "INSERT INTO ".DB_PREFIX."ranking_cont(sort_id,cid,app_bundle,title,url,count) VALUES";
					$space = '';
					foreach($val as $k => $v)
					{
                        //非发布库数据标题使用库里存储内容
                        //从merge表统计出来如果没有title，到nums表查询标题
                        //merge表结构不好更改，更改后客户升级也比较麻烦 所以临时解决方案 到nums表再查询一次
                        if (!$v['title'] && $v['app_bundle'] && !in_array($v['app_bundle'], $pub_content_bundle))
                        {
                            $sql_nums = "SELECT title FROM ".DB_PREFIX."nums WHERE app_bundle = '".$v['app_bundle']."' AND cid=" . $v['cid'];
                            $nums_q = $this->db->query_first($sql_nums);
                            $v['title'] = $nums_q['title'];
                        }

                        !$v['url'] && $v['url'] = $v['refer_url'];
						$sql .= $space . "('{$info['id']}','{$v['cid']}','{$v['app_bundle']}','{$v['title']}','{$v['url']}','{$v['num']}')";
						$space = ',';
					}
					$this->db->query($sql);				
				}
			}
			$sql = "UPDATE ".DB_PREFIX."ranking_sort SET last_time = " . TIMENOW . " WHERE id = " . $info['id'];
			$this->db->query($sql);
			echo $info['title'] . "<br/>";
			flush();
   			ob_flush();					
		}
		exit();
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
require_once(ROOT_PATH . 'excute.php');
?>
