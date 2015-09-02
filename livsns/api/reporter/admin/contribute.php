<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/contribute.class.php');
define('MOD_UNIQUEID','reporter_con');//模块标识
class contributeApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->con = new contribute();
		$this->mNodes = array(
			'reporter_node'	=> '记者列表',
		);
		$this->mPrmsMethods['back'] = array(
										'name' => '打回',
										'node' => true,
									);
		$this->mPrmsMethods['update_indexpic'] = array(
										'name' => '更新索引图',
										'node' => true,
										);									
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	
	}
	function show()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms();
		/*********权限验证结束*********/
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY c.order_id  DESC';
		$condition = $this->get_condition();
		$data = $this->con->show($condition,$orderby,$offset,$count);
		$suobei = $this->settings['App_suobei'];
		$arr = array(
			'data'=>$data,
			'suobei'=>$suobei,
		);
		$this->addItem($arr);
		$this->output();
	}
	
	function count()
	{
		$ret = $this->con->count($this->get_condition());
		echo json_encode($ret);
	}
	function get_condition()
	{
		$condition = '';
		/**************权限控制开始**************/
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND c.user_id = '.$this->user['user_id'];
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1)
			{
				$condition .= ' AND c.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($this->user['prms'][MOD_UNIQUEID]['show']['node'])
			{
				$authnode_str = '';
				foreach ($this->user['prms'][MOD_UNIQUEID]['show']['node'] as $nodevar=>$authnode)
				{
					$authnode_str = $authnode ? implode(',', $authnode) : '';
					if($authnode_str)
					{
						$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
						$sql = 'SELECT id,childs FROM '.DB_PREFIX.'sort WHERE id IN('.$authnode_str.')';
						$query = $this->db->query($sql);
						$authnode_array = array();
						while($row = $this->db->fetch_array($query))
						{
							$authnode_array[$row['id']]= explode(',', $row['childs']);
						}
						$authnode_str = '';
						foreach ($authnode_array as $node_id=>$n)
						{
							if($node_id == intval($this->input['_id']))
							{
								$node_father_array = $n;
								if(!in_array(intval($this->input['_id']), $authnode))
								{
									continue;
								}
							}
							$authnode_str .= implode(',', $n) . ',';
						}
						$authnode_str = in_array(0, $authnode) ? $authnode_str . '0' : trim($authnode_str,',');
						if(!$this->input['_id'])
						{
							$condition .= ' AND c.sort_id IN(' . $authnode_str . ')';
						}
						else
						{
							$authnode_array = explode(',', $authnode_str);
							if(!in_array($this->input['_id'], $authnode_array))
							{
								if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
								{
									
									$this->errorOutput(NO_PRIVILEGE);
								}
								$condition .= ' AND c.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
							}
							else
							{
								
							}
						}
					}
				}
			}
		}

		/**************权限控制结束**************/
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		//分类列表
		if ($this->input['contribute_sort'] && intval($this->input['contribute_sort'])!= -1)
		{
			$condition .= ' AND sort_id = '.$this->input['contribute_sort'] ; 
		}
		
		if ($this->input['contribute_sort_audit'] && $this->input['contribute_sort_audit'] != -1)
		{
			
			$condition .= ' AND audit = '.$this->input['contribute_sort_audit'] ; 
		}
		if ($this->input['_id'])
		{
			$condition .= ' AND sort_id = '.$this->input['_id'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND c.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND c.create_time <= ".$end_time;
		}
		if($this->input['contribute_sort_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['contribute_sort_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  c.create_time > ".$yesterday." AND c.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  c.create_time > ".$today." AND c.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  c.create_time > ".$last_threeday." AND c. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND c.create_time > ".$last_sevenday." AND c.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	function detail()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$data = $this->con->detail(urldecode($this->input['id']));
		$this->addItem($data);
		$this->output();
	}
	public function add(){
	
		$this->show();
			
	}
	
	public function output_sort()
	{
		$ret = $this->con->allsort();
		$this->addItem($ret);
		$this->output();
	}
	public function show_sort(){
		$referto = $this->input['referto'];
		$pos = strpos($referto,'&id=');
		$condition = '';
		
		/**************权限控制结束**************/
		/*
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//更新操作
			if ($pos)
			{
				if($this->user['prms'][MOD_UNIQUEID]['update']['node'])
				{
					$authnode_str = '';
					foreach ($this->user['prms'][MOD_UNIQUEID]['update']['node'] as $nodevar=>$authnode)
					{
						$authnode_str = $authnode ? implode(',', $authnode) : '';
						if($authnode_str)
						{
							$sql = 'SELECT id,childs FROM '.DB_PREFIX.'sort WHERE id IN('.$authnode_str.')';
							$query = $this->db->query($sql);
							$authnode_array = array();
							while($row = $this->db->fetch_array($query))
							{
								$authnode_array[$row['id']]= explode(',', $row['childs']);
							}
							$authnode_str = '';
							foreach ($authnode_array as $node_id=>$n)
							{
								$authnode_str .= implode(',', $n) . ',';
							}
							$authnode_str = in_array(0, $authnode) ? $authnode_str . '0' : trim($authnode_str,',');
							
							$authnode_array = explode(',', $authnode_str);
							$condition .= ' AND id IN(' . implode(',', $authnode_array) . ')';
						}
					}
				}
			}else {
			//增加操作
				if($this->user['prms'][MOD_UNIQUEID]['create']['node'])
				{
					$authnode_str = '';
					foreach ($this->user['prms'][MOD_UNIQUEID]['create']['node'] as $nodevar=>$authnode)
					{
						$authnode_str = $authnode ? implode(',', $authnode) : '';
						if($authnode_str)
						{
							$sql = 'SELECT id,childs FROM '.DB_PREFIX.'sort WHERE id IN('.$authnode_str.')';
							$query = $this->db->query($sql);
							$authnode_array = array();
							while($row = $this->db->fetch_array($query))
							{
								$authnode_array[$row['id']]= explode(',', $row['childs']);
							}
							$authnode_str = '';
							foreach ($authnode_array as $node_id=>$n)
							{
								$authnode_str .= implode(',', $n) . ',';
							}
							$authnode_str = in_array(0, $authnode) ? $authnode_str . '0' : trim($authnode_str,',');
							
							$authnode_array = explode(',', $authnode_str);
							$condition .= ' AND id IN(' . implode(',', $authnode_array) . ')';
						}
					}
				}
				
				
			}
		}
		*/	
		/**************权限控制结束**************/
		$ret = $this->con->allsort($condition);
		$this->addItem($ret);
		$this->output();
	}
	//内容详细页面
	public function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = intval(urldecode($this->input['id']));
		$data = $this->con->show_opration($id);
		$suobei = $this->settings['App_suobei'];
		$bounty = BOUNTY;
		$arr = array(
			'data'=>$data,
			'suobei'=>$suobei,
			'bounty'=>$bounty,
			'position'=>DEFAULT_POSITION,
		);
		$this->addItem($arr);
		$this->output();
	}

	function insert_config()
	{
		echo "ok";
	}
	
	//转发索贝
	public function forward_suobei()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->con->forward_suobei($id);
		$ids = explode(',',$id);
		$this->addItem($ids);
		$this->output();
	}
	public function show_position()
	{
		$position = DEFAULT_POSITION;
		$this->addItem($position);
		$this->output();
	}
	//检测转发信息
	public function check_sort()
	{
		$id = intval($this->input['id']);
		$data = $this->con->check_sort($id);
		$this->addItem($data);
		$this->output();
	}
	
}

$ouput= new contributeApi();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();