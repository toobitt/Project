<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/auth.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');		
define('MOD_UNIQUEID','special_content');//模块标识
class specialContentApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/special_content.class.php');
		$this->obj = new specialContent();
		$this->auth = new Auth();
		$this->publishcontent = new publishcontent();
		$this->pubconfig = new publishconfig();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		$condition = $this->get_condition();
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$offset = intval(($pp - 1)*$count);
		$limit = " limit {$offset}, {$count}";
		
		$ret = $this->obj->show($condition,$limit,$this->input['speid'],$this->input['column_id']);	
		
		$return = $this->get_page_data($this->input['speid'],$count,$pp,$this->input['column_id']);
		
		$ret['page_info'] = $return;
		
		$this->addItem($ret);	
		$this->output();		
	}

	public function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'special_content WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		/*$app_arr = explode('.',$r['app_id']);
		$r['app_id'] = $app_arr[1];
		require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->pub = new publishcontent();
		$app_modules = $this->pub->get_app_module();
		$r['module_id'] = $app_modules['module'][$r['app_id']]['name'];*/
		
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}
	
	public function column_detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'special_columns  WHERE id = '.$this->input['column_id'];
		$r = $this->db->query_first($sql);
		
		$this->addItem($r);
		$this->output();
	}
	
	
	
	public function edit()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'special_content WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		$r['pic'] = json_encode(unserialize($r['indexpic']));
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}
	
	public function query()
	{	
		require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->puscont = new publishcontent();
		
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 10;
		$offset = intval(($pp - 1)*$count);			
		$data = array(
			'offset'	  		=> $offset,
			'count'		  		=> $count,
			'client_type'		=>	'2',
			'need_count'		=> '1',
		);
		if ($this->input['column_id'])
		{
			$data['column_id'] = intval($this->input['column_id']);
		}
		
		if($this->input['info'])
		{
			foreach($this->input['info'] as $k=>$v)
			{
				$info[$v['name']] = $v['value'];
			}
		}
		//查询
		if($info['special_modules'])
		{
			$data['bundle_id'] = $info['special_modules'];
		}
		
		//查询站点
		if($info['site_id'])
		{
			$data['site_id'] = $info['site_id'];
		}

		//查询时间
		if($info['special_date_search'])
		{
			$data['date_search'] = $info['special_date_search'];
		}
		
		//查询标题
		if($info['k'])
		{
			$data['k'] = $info['k'];
		}
		
		//查询创建的起始时间
		if($info['start_time'])
		{
			$data['starttime'] = $info['start_time'];
		}
		
		//查询创建的结束时间
		if($info['end_time'])
		{
			$data['endtime'] = $info['end_time'];
		}
		
		//发布人
		if($info['user_name'])
		{
			$data['publish_user'] = $info['user_name'];
		}
		//站点
		if($info['site_id'])
		{
			$data['site_id'] = $info['site_id'];
		}
		
		//是否有示意图
		if($info['is_have_indexpic'])
		{
			$data['is_have_indexpic'] = $info['is_have_indexpic'];
		}
		//是否有视频
		if($info['is_have_video'])
		{
			$data['is_have_video'] = $info['is_have_video'];
		}
		//查询权重
		if(isset($info['start_weight']) && intval($info['start_weight'])>=0)
		{
			$data['start_weight'] = $info['start_weight'];
		}
		if(isset($info['end_weight']) && intval($info['end_weight'])>=0)
		{
			$data['end_weight'] = $info['end_weight'];
		}
        
        if($info['exclude_special']) {
            $data['exclude_special'] = APP_UNIQUEID;
        }
        
        if($info['exclude_bundle']) {
            $data['exclude_bundle'] = $info['exclude_bundle'];
        } 
        if($info['need_group_cid']) {
            $data['need_group_cid'] = $info['need_group_cid'];
        }
            
		$re = $this->puscont->get_content($data);
		$return = $this->publishcontent->get_pub_content_type();
		if(is_array($return))
		{
			foreach($return as $k => $v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
		$columns = $this->get_column();
		if(is_array($re['data']))
		{
			foreach($re['data'] as $k=>$v)
			{
				$co_names = array();
				if($v['column_id'])
				{
					$co_arr = explode(" ",$v['column_id']);
					foreach($co_arr as $ke=>$va)
					{
						$co_names[] = $columns[$va];
					}
				}
				$v['column_name'] = implode(" ",$co_names);
				//$v['app_name']	=	$apps[$v['bundle_id']];
				$v['module_name']	=	$bundles[$v['bundle_id']];
				$v['pic'] = json_encode($v['indexpic']);
				$ret[] = $v;
			}
		}
		
		$total_num =$re['total'];//总的记录数
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$return['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$count) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $count;//每页显示的个数
		$return['current_page']  = $pp;//当前页码
		
		$retu['info'] = $ret;
		$retu['page_info'] = $return;
		
		$this->addItem($retu);
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
		$str = '';
		if($column_id = intval($this->input['column_id']))
		{
			$str .=" AND column_id =". $column_id;
		}
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'special_content WHERE 1 '.$this->get_condition().$str;
		$content_total = $this->db->query_first($sql);
		echo json_encode($content_total);	
	}
	
	public function  get_speconlist()
	{	
		
		$condition = $this->get_condition();
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		if($this->input['count'])
		{
			$count  = $this->input['count'];
		}
		if($this->input['offset'])
		{
			$count  = $this->input['offset'];
		}
		$count  = $count ? $count : 20;
		$offset = intval(($pp - 1)*$count);		
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit,$this->input['speid'],$this->input['column_id']);	
		
		$return = $this->get_page_data($this->input['speid'],$count,$pp,$this->input['column_id'],$condition);
		
		$ret['page_info'] = $return;
		
		$this->addItem($ret);	
		$this->output();		
	}
    
    public function get_spe_column_info()
    {
        $column_id = intval($this->input['id']);
        if (!$column_id) {
            $this->errorOutput('NOID');
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "special_columns WHERE 1 AND id = " . $column_id;
        $ret = $this->db->query_first($sql);
        $this->addItem($ret);
        $this->output();
    }
	
	
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function get_condition()
	{	
		$condition = '';
		$info = array();
		if($special_id = intval($this->input['speid']))
		{
			$condition .=" AND special_id =". $special_id;
		}
		if($column_id = intval($this->input['column_id']))
		{
			$condition .=" AND column_id =". $column_id;
		}
		if($this->input['info'])
		{
			foreach($this->input['info'] as $k=>$v)
			{
				$info[$v['name']] = $v['value'];
			}
		}
		//查询
		if($info['key'])
		{
			$condition .= " AND title LIKE '%" . trim(urldecode($info['key'])) . "%' ";
		}

		//查询创建的起始时间
		if($info['start_time'])
		{
			$condition .= " AND create_time > " . strtotime($info['start_time']);
		}
		
		//查询创建的结束时间
		if($info['end_time'])
		{
			$condition .= " AND create_time < " . strtotime($info['end_time']);	
		}
		
		//查询权重
		if($info['start_weight'] && $info['start_weight'] != -1)
		{
			$condition .=" AND weight >= " . $info['start_weight'];
		}
		if($info['end_weight'] && $info['end_weight'] != -1)
		{
			$condition .=" AND weight <= " . $info['end_weight'];
		}
		//添加人
		if ($info['user_name'])
        {
            $condition .=" AND user_name = '" . $info['user_name']."'";
        }
        
        //查询发布的时间
        if($info['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch(intval($info['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//查询文章的状态
		if (isset($info['state']))
		{
			switch (intval($info['state']))
				{
					case 1:
						$condition .= " ";
						break;
					case 2: //待审核
						$condition .= " AND state= 0";
						break;
					case 3://已审核
						$condition .= " AND state = 1";
						break;
					case 4: //已打回
						$condition .=" AND state = 2";
					default:
						break;
				}
		}
		
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (!$this->user['prms']['default_setting']['show_other_data'])
            {
                $condition .= ' AND user_id = ' . $this->user['user_id'];
            }
            else
            {
                //组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND org_id IN('.$this->user['slave_org'].')';
				}
            }
        }
		return $condition;
	}
	
	
	public function get_app()
	{	
		$appid = intval($this->input['app_id']);
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		$apps = $this->auth->get_app('bundle,name');
		if(is_array($apps))
		{
			foreach($apps as $k=>$v)
			{
				$apps_arr[$v['bundle']] = $v['name'];
			}
		}
		$this->addItem($apps_arr);
		$this->output();
	}
	//获取客户端名称
	public function get_client()	
	{	
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		$clients = $this->pub->get_client();
		foreach($clients as $ke =>$va)
		{
			$client[$va['id']] = $va['name'];
		}
		$this->addItem($client);
		$this->output();
	}	

	//获取应用模块
	public function get_app_module()	
	{	
		$return = $this->publishcontent->get_pub_content_type();
		if($return)
		{
			foreach($return as$k=>$v)
			{
				$modules[$v['bundle']] = $v['name'];
			}
		}
		$this->addItem($modules);
		$this->output();
	}	
	
	//获取客户端名称
	public function get_special()	
	{	
		$sql = "SELECT id,title
				FROM  " . DB_PREFIX ."special_content 
				WHERE 1";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$info[$row['id']] = $row['title'];
		}	
		$this->addItem($info);
		$this->output();
	}	
	
	//获取内容分类
	/*public function get_consort()	
	{	
		if($special_id = intval($this->input['speid']))
		{
			$str .=" AND special_id =". $special_id;
		}
		$sql = "SELECT id,name
				FROM  " . DB_PREFIX ."special_content_sort 
				WHERE 1".$str;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$info[$row['id']] = $row['name'];
		}
		if($this->input['a'] == 'get_consort')
		{
			$this->addItem($info);
			$this->output();
		}
		else
		{
			return 	$info;
		}
	}	*/
	
	public function get_special_columns()
	{
		$special_id = intval($this->input['speid']);
		$sql_ = "select * from " . DB_PREFIX . "special_columns  where special_id = ".$special_id.' ORDER BY order_id DESC';	
		$q = $this->db->query($sql_);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']]['column_name'] = $r['column_name'];
			$ret[$r['id']]['count'] = $r['count'];
			$ret[$r['id']]['order_id'] = $r['order_id'];
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_special_column_name()
	{
		$special_id = intval($this->input['speid']);
		$sql_ = "select * from " . DB_PREFIX . "special_columns  where special_id = ".$special_id;	
		$q = $this->db->query($sql_);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['column_name'];
		}
		$this->addItem($ret);
		$this->output();
	}
	
	//获取分页的一些参数
	public function get_page_data($special_id,$count,$page,$column_id='',$condition='')
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "special_content WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);
		$total_num = $ret['total'];//总的记录数
		$page_num = $count ? $count : 20;
		//总页数
		if(intval($total_num%$page_num) == 0)
		{
			$return['total_page']    = intval($total_num/$page_num);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$page_num) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $page_num;//每页显示的个数
		$return['current_page']  = $page;//当前页码
		
		return $return;
	}
	
	//获取栏目
	public function get_column()	
	{	
		$publish_columns = $this->pubconfig->get_column();
		foreach($publish_columns as $k=>$v)
		{
			$columns[$v['id']]	= $v['name'];
		}
		return $columns;
	}	
	
	public function get_scolumn()
	{
		$special_id = intval($this->input['speid']);
		$sql_ = "select * from " . DB_PREFIX . "special_columns  where special_id = ".$special_id.' ORDER BY order_id DESC';	
		$q = $this->db->query($sql_);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$re = array();
			$re['id'] = $r['id'];
			$re['column_name'] = $r['column_name'];
			$ret[] = $re;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function special()
	{
		
	}
	/*public function get_special_content_info()
	{
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$offset = intval(($pp - 1)*$count);			
		$data = array(
			'offset'	  => $offset,
			'count'		  => $count,
			'pp'		  => $pp,
			'k'	     	  => $this->input['title'],
		);

		$data['page']	 = $this->get_page_data();
		$this->addItem($data);
		$this->output();
	}
	
	public function get_publish_column()
	{	
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pubconfig = new publishconfig();
		$publish_columns = $this->pubconfig->get_column();
		foreach($publish_columns as $k=>$v)
		{
			$columns[$v['id']]	= $v['name'];
		}
		$this->addItem($columns);
		$this->output();
	}*/
}

$out = new specialContentApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
