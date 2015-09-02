<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/auth.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');		
define('MOD_UNIQUEID','vote');//模块标识
class PublishContentApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
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
	}

	public function detail()
	{
			
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
		if ($this->input['site_id'])
		{
			$data['site_id'] = intval($this->input['site_id']);
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
		
		//查询权重
		if(isset($info['start_weight']) && intval($info['start_weight'])>=0)
		{
			$data['start_weight'] = $info['start_weight'];
		}
		if(isset($info['end_weight']) && intval($info['end_weight'])>=0)
		{
			$data['end_weight'] = $info['end_weight'];
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
				$v['picture'] = hg_material_link($v['indexpic']['host'], $v['indexpic']['dir'], $v['indexpic']['filepath'], $v['indexpic']['filename']);
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
	
	public function count()
	{	
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
	
}

$out = new PublishContentApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
