<?php
require('global.php');
define(MOD_UNIQUEID,'ranking');
class RankingDetail extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function detail(){}
	
	public function show()
	{
	    if (!$this->input['sort_id']) {
	        $this->errorOutput(NO_SORTID);
	    }
        
        $sql = "SELECT status,output_type,column_id FROM ".DB_PREFIX."ranking_sort WHERE id = " . $this->input['sort_id'];
        $sort = $this->db->query_first($sql);

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

		$condition = $this->get_condition();
        $condition .= $this->input['orderby'] ? " ORDER BY " . $this->input['orderby'] : " ORDER BY count";
        $condition .= $this->input['descasc'] ? " " . $this->input['descasc']: ' DESC';
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit = " LIMIT " . $offset . ", " . $count;
		$sql = "SELECT * FROM ".DB_PREFIX."ranking_cont WHERE 1 " . $condition . $data_limit;
		$q = $this->db->query($sql);
		$cidArr = array();
		$conArr = array();
        $other_content = array();
		while($row = $this->db->fetch_array($q))
		{
            if ( $row['app_bundle'] && !in_array($row['app_bundle'], $pub_content_bundle) )
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
			    $conArr[$row['cid']] = $row['count'];
            }
		}
		$cidStr = implode(',',$cidArr);

//		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE  father != 0" ;
//		$q = $this->db->query($sql);
//		$module = array();
//		while($row = $this->db->fetch_array($q))
//		{
//			$module[$row['bundle']] = $row['name'];
//		}

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
                foreach($cidArr as $cv){
                    $useColumn[$cv] = $sort['column_id'];
                }
                $ret = $this->publishtcontent->get_content_by_cid($cidStr, $useColumn);
            }  
        }      
		foreach((array)$ret as $k => $v)
		{
			$ret[$k]['bundle_name'] = $module[$v['bundle_id']];
            if (!$ret[$k]['bundle_name'])
            {
                $ret[$k]['bundle_name'] = $this->settings["App_{$v['bundle_id']}"]['name'];
            }
            if (!$ret[$k]['bundle_name'])
            {
                $ret[$k]['bundle_name'] = $v['bundle_id'];
            }
			$ret[$k]['count'] = ($sort['output_type'] == 1) ? $conArr[$v['id']] : $conArr[$v['cid']]; //栏目时统计记录的是id  内容统计记录的是cid
		}

        $ret = (array)$ret;
        $ret = array_merge($other_content, $ret);

		$ret = hg_array_sort($ret,'count','DESC');
		if(!empty($ret) && is_array($ret))
		{
			foreach ($ret as $k => $v)
			{
			    if ($sort['output_type'] == 1) {
			        $v['title'] = $v['name'];
			    }
				$this->addItem($v);
			}			
		}
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "ranking_cont WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);
		echo json_encode($ret);
	}
	
	private function get_condition()
	{
		$condition = '';
		if($this->input['sort_id'])
		{
			$condition .= " AND sort_id = " . intval($this->input['sort_id']);
		}
		return $condition;
	}
}

$out = new RankingDetail();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>