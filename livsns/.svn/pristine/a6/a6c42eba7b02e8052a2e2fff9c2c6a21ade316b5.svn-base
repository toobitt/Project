<?php
require('global.php');
define('MOD_UNIQUEID','access');
class accessApi extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
			'manage'		=>'管理',
//			'_node'=>array(
//				'name'=>'访问统计节点',
//				'filename'=>'access_node.php',
//				'node_uniqueid'=>'access_node',
//				),
		);				
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/access.class.php');
		$this->obj = new access();
	}
	
	public function index(){}
	public function detail(){}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
				
		if (isset($this->input['_type']) && intval($this->input['_type']))
		{
			$this->addItem_withkey('_type_', intval($this->input['_type']));
		}
		
		//$nums = $this->obj->show($condition, $offset, $count);
      
        include_once(ROOT_PATH . 'lib/class/auth.class.php');
        $this->auth = new Auth();
        $app_info = $this->auth->get_app();
        foreach ((array)$app_info as $k => $v) {
            if(!empty($v))
            {
                $app_info[$k] = array('id' => $v['id'],'name' => $v['name'],'fid' =>0, 'depth' => 0,'is_last' =>1,'input_K' => '_id','_appid' => $v['bundle'],'_modid' => '');
            }
        }       
        
        $ret = array('content' => $nums, 'app_info' => $app_info);
        $this->addItem($ret);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$ret = $this->obj->count($condition);
		echo json_encode($ret);
	}

	function unknow()
	{
		$this->errorOutput("此方法不存在");
	}
	
	private function get_condition()
	{
		$condition = '';
		$condition .= ' AND del = 0 ';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title LIKE \'%'.trim(urldecode($this->input['k'])).'%\'';
		}
		if($this->input['_appid'])
		{
			$condition .= " AND app_bundle ='".urldecode($this->input['_appid'])."'";
		}
        if($this->input['app_uniqued'])
        {
            $condition .= " AND app_bundle ='".urldecode($this->input['app_uniqued'])."'";
        }        
		if($this->input['_modid'])
		{
			$condition .=" AND module_bundle ='" . urldecode($this->input['_modid']) . "'";
		}
        
        if ($this->input['id']) {
            $this->input['id'] = explode(",", $this->input['id']);
            $this->input['id'] = implode("','", $this->input['id']);
            $condition .= "  AND cid IN('" . $this->input['id'] . "')";
        }

        //查询发布的时间
        if($this->input['access_time'])
        {
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d',TIMENOW+24*3600);
            switch(intval($this->input['access_time']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = date('y-m-d',TIMENOW-24*3600);
                    $this->input['start_time'] = $yesterday;
                    $this->input['end_time'] = $today;
                    break;
                case 3://今天的数据
                    $this->input['start_time'] = $today;
                    $this->input['end_time'] = $tomorrow;
                    break;
                case 4://最近3天
                    $last_threeday = date('y-m-d',TIMENOW-2*24*3600);
                    $this->input['start_time'] = $last_threeday;
                    $this->input['end_time'] = $tomorrow;
                    break;
                case 5://最近7天
                    $last_sevenday = date('y-m-d',TIMENOW-6*24*3600);
                    $this->input['start_time'] = $last_sevenday;
                    $this->input['end_time'] = $tomorrow;
                    break;
                default://所有时间段
                    break;
            }
        }

        //查询创建的起始时间
        if($this->input['start_time'])
        {
            $this->input['start_time'] = strtotime($this->input['start_time']);
            $condition .= " AND update_time > " . $this->input['start_time'];
        }

        //查询创建的结束时间
        if($this->input['end_time'])
        {
            $this->input['end_time'] = strtotime($this->input['end_time']);
            $condition .= " AND update_time < " .  $this->input['end_time'];
        }

        if ($this->input['start_time'] || $this->input['end_time'])
        {
            $this->input['duration'] = $this->input['end_time'] - $this->input['start_time'];
        }
        
//        if($this->input['access_nums']) {
//            if ($this->input['access_nums'] == 1) {
//                $condition .= " ORDER BY access_nums DESC ";
//            }else if ($this->input['access_nums'] ==2) {
//                $condition .= " ORDER BY access_nums ASC";
//            }
//        }
//        else
//        {
//            //默认访问时间倒序排列
//            $condition .= " ORDER BY access_nums DESC";
//        }

        $condition .= " ORDER BY access_nums DESC";
	
		return $condition;
	}	
    
    function get_content() {

        if ($this->settings['cache_expire_time'])
        {
            /**先从缓存读取数据 缓存不存在或过期时再从表中查询*/
            include_once(ROOT_PATH . 'lib/class/cache/cache.class.php');
            $cache_factory = cache_factory::get_instance();
            $cache_type = $this->settings['cache_type'] ? $this->settings['cache_type'] : 'file';
            $cache_driver = $cache_factory->get_cache_driver($cache_type);
            $input = $this->input;
            unset($input['access_token'], $input['lpip']);
            $cache_id = md5(serialize($input));
            $data = $cache_driver->get($cache_id);
            if ($data)
            {
                $this->addItem($data);
                $this->output();
            }
            /**先从缓存读取数据 缓存不存在或过期时再从表中查询*/
        }

        //$condition = $this->get_condition();
        $offset = $this->input['page'] ? $this->input['page_num'] * ($this->input['page'] -1) : 0;          
        $count = $this->input['page_num'] ? intval($this->input['page_num']) : 20;

        $con = $con_count = $this->con_process();
        $con['offset'] = $offset;
        $con['count'] = $count;
        $content = $this->obj->get_content($con);
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

        $cidArr = array();
        $conArr = array();
        $other_content = array();
        foreach((array)$content as $row)
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
                $row['content_url'] = $row['url'];
                $row['access_nums'] = $row['num'];
                $other_content[] = $row;
            }
            else
            {
                $cidArr[] = $row['cid'];
                $conArr[$row['cid']] = array('access_nums' => $row['num']);
            }
        }

        $cidStr = implode(',',$cidArr);
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
        $ret = hg_array_sort($ret,'access_nums','DESC');


        $pagearr = $this->obj->get_content($con_count, 1);
        $pagearr['page_num'] = $count;
        $pagearr['total_num'] = $pagearr['total'];
        $pagearr['total_page'] = ceil($pagearr['total']/$count);
        $pagearr['current_page'] = floor($offset/$count) + 1;        
        
        $ret = array('content' => array_values($ret), 'page' => $pagearr);

        if ($this->settings['cache_expire_time'])
        {
            /*将数据写入缓存*/
            $cache_driver->set($cache_id, $ret, $this->settings['cache_expire_time']);
            /*将数据写入缓存*/
        }

        $this->addItem($ret);
        $this->output();
    }


    /**
     * 查询条件处理
     * @return array
     */
    function con_process()
    {
        $con = array();
        if(isset($this->input['k']) && !empty($this->input['k']))
        {
            $con['title'] = trim(urldecode($this->input['k']));
        }
        if($this->input['_appid'])
        {
            $con['type'] = urldecode($this->input['_appid']);
        }
        if($this->input['app_uniqued'])
        {
            $con['type'] = urldecode($this->input['app_uniqued']);
        }

        if ($this->input['id'])
        {
            $con['cid'] .= $this->input['id'];
        }
        //查询发布的时间
        if($this->input['access_time'])
        {
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d',TIMENOW+24*3600);
            switch(intval($this->input['access_time']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = date('y-m-d',TIMENOW-24*3600);
                    $this->input['start_time'] = $yesterday;
                    $this->input['end_time'] = $today;
                    break;
                case 3://今天的数据
                    $this->input['start_time'] = $today;
                    $this->input['end_time'] = $tomorrow;
                    break;
                case 4://最近3天
                    $last_threeday = date('y-m-d',TIMENOW-2*24*3600);
                    $this->input['start_time'] = $last_threeday;
                    $this->input['end_time'] = $tomorrow;
                    break;
                case 5://最近7天
                    $last_sevenday = date('y-m-d',TIMENOW-6*24*3600);
                    $this->input['start_time'] = $last_sevenday;
                    $this->input['end_time'] = $tomorrow;
                    break;
                default://所有时间段
                    break;
            }
        }
        //查询创建的起始时间
        if($this->input['start_time'])
        {
            $con['start_time'] =  strtotime($this->input['start_time']);
        }
        //查询创建的结束时间
        if($this->input['end_time'])
        {
            $con['end_time'] =  strtotime($this->input['end_time']);
        }
        if ($con['start_time'] || $con['end_time'])
        {
            if (!$con['end_time'])
            {
                $con['end_time'] = TIMENOW;
            }
            $con['duration'] = ($con['end_time'] - $con['start_time'])/60;
        }
        return $con;
    }

}

$out = new accessApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>