<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/contribute.class.php');
define('MOD_UNIQUEID','contribute');//模块标识
class contributeApi extends adminReadBase
{
	
	function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'		=>'管理',
		'_node'=>array(
			'name'=>'报料分类',
			'filename'=>'contribute_node.php',
			'node_uniqueid'=>'contribute_node',
			),
		);
		parent::__construct();
		$this->con = new contribute();
	}
	
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	
	function index()
	{
	
	}
	
    function user_show()
	{   
	    $audit_state = $this->settings['audit_state'];
	 
       $edit_audit = $this->settings['wuhan_state'];
	    
	    $space = "";
	    foreach($edit_audit as $key => $vo)
	    {
		    $other_audit_id .= $space.$key;
		    $space =",";
	    }
       //$other_audit_id = "2,5";
     
	    $get_child_nodes = $this->input['get_node_id'] ?  $this->input['get_node_id'] : '';
	    $get_nodes = $this->input['get_nodes'] ?  $this->input['get_nodes'] : '';
	    $get_father_nodes =$this->input['get_father_nodes'] ?  $this->input['get_father_nodes'] : '';
	   
	    $orign_ids = $this->settings['org_to_sort'][$this->user['org_id']] ;
		if(!empty($orign_ids))
		{
		    if(is_array($orign_ids))
		    {   
				$my_nod_ids =implode(",",$orign_ids);
			}
			else
			{
				$my_nod_ids = $orign_ids ;//权限范围内的分类
			}
		}
		$sq = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and id in ( ".$my_nod_ids.")" ;
	    $qe = $this->db->query($sq);
	    $space = "";
	    while($r = $this->db->fetch_array($qe))
	    {    
	         $depath[] = $r['depath'] ; // 得到分类的深度
			 $my_ids .=  $space.$r['childs'] ;  //  1,2,4,5
			 $space = ",";
	    }
	   
	    $my_ids=explode(",",$my_ids); //array(1,2,4,5)
	  
         sort($depath); 
	     $min_depath = $depath[0];
	   
	    // 下级  同级 上级
	    if(!empty($get_child_nodes))
	    {
		    $sql_first = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and fid = ".$get_child_nodes;
		    $q = $this->db->query($sql_first);
		    while($r = $this->db->fetch_array($q))
		    {   
		     	$node_ids[] =  $r['id'];
		    }
	    }
	    elseif(!empty($get_nodes))
	    {
		    $sql_first = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and id = ".$get_nodes;
		    $qe = $this->db->query_first($sql_first);
		    if($qe['depath'] >1)
		    {
			     $sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1  and fid = ".$qe['fid'] ;
			     $q = $this->db->query($sql);
				 while($r = $this->db->fetch_array($q))
				 {   
				     $nodes_ids[] =  $r['id'];
				 }
				 foreach($nodes_ids as $key => $vo)
				 {
					if(in_array($vo,$my_ids))
					{
						$node_ids[] = $vo;
					}
				}
		    }
		    else
		    {
		        $node_ids = $this->settings['org_to_sort'][$this->user['org_id']] ;
		    }
		    
		    $sq = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and id in (".$get_nodes.")";
		    $q = $this->db->query($sq);
		    while($r = $this->db->fetch_array($q))
		    {
			   $children_id[] = $r['childs'];
		    }
	    }
	    elseif(!empty($get_father_nodes))
	    {
		    $sql_first = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and id = ".$get_father_nodes;
		    $qe = $this->db->query_first($sql_first);
		    if($qe['depath'] >2)
		    {    
		         $sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and id = ".$qe['fid'] ;
		         $re = $this->db->query_first($sql); // 上一级的消息
		         
			     $sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and fid = ".$re['fid'] ;
			     $q = $this->db->query($sql);
				 while($r = $this->db->fetch_array($q))
				 {   
				     $nodes_ids[] =  $r['id'];
				 }				
				
                foreach($nodes_ids as $key => $vo)
				{
					if(in_array($vo,$my_ids))
					{
						$node_ids[] = $vo;
					}
				}

		    }
		    else
		    {
		        $node_ids = $this->settings['org_to_sort'][$this->user['org_id']] ;
		    }
	    }
	    else
	    {
		    $node_ids = $this->settings['org_to_sort'][$this->user['org_id']] ;
		}
		
		
		if(!empty($node_ids))
		{
		    if(is_array($node_ids))
		    {
			    $space = "" ;
				foreach($node_ids as $key => $vo )
				{
					$nod_ids .= $space.$vo ;
					$space = "," ;
				}
			}
			else
			{
				$nod_ids = $node_ids ;
			}
		}

	    $sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 and id in (".$nod_ids.")";
		
		$query = $this->db->query($sql);
		$nodes =array();
		while($row = $this->db->fetch_array($query))
		{
			
			if($row['childs'] != $row['id'])
			{
				$row['has_childs'] = "yse";
			}
			$nodes[] = $row ; // 得到分类
			
			if(empty($get_nodes))  
		    { 
			    $children_id[] = $row['childs'];
			}
		}
		
		//$children_id 是列表要显示的报料的所有分类 包含顶级分类和下级分类
		if(!empty($children_id))
		{
		    $space = "" ;
			foreach($children_id as $key => $vo )
			{
				$children_nod_ids .= $space.$vo ;
				$space = "," ;
			}
		}
		
		$arr = array(
			'node' => $nodes,
			'sort_ids' => $children_nod_ids ,
			'audit_state' => $audit_state ,
			'edit_audit' => $edit_audit ,
			'min_depath' => $min_depath ,
			'other_audit_id' => $other_audit_id,
		);

		$this->addItem($arr);
		$this->output();
		
	}
	
	function show()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'manage'));
		/*********权限验证结束*********/
		$offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
		$count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
		$orderby = ' ORDER BY c.order_id DESC';
		$condition = $this->get_condition();
		
		$total = $this->con->count($this->get_condition());
		$data = $this->con->show($condition,$orderby,$offset,$count);
		$suobei = $this->settings['App_suobei'];
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				if (CLAIM && $this->user['group_type'] > MAX_ADMIN_TYPE && $val['claim_org_id'] != $this->user['org_id'] && $val['claim_org_id'])
				{
					if ($this->user['org_id'] != $val['org_id'])
					{
						$data[$key]['forbid_op'] = 1;
					}
				}
				if (CLAIM && $this->user['group_type'] <= MAX_ADMIN_TYPE &&  $val['claim_org_id'])
				{
					$data[$key]['off_claim'] = 1;
				}
			}
		}
		$arr = array(
			'data'=>$data,
			'suobei'=>$suobei,
			'total'=> $total['total'],
			'claim'=>CLAIM,
		);
		if (CLAIM && $this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			$arr['off_claim'] = true;
		}
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
		//大小新闻移动客户端,记者发稿
		$data = file_exists(DATA_DIR.'reporter.txt') ? file_get_contents(DATA_DIR.'reporter.txt') : '';
		$dataarray = (explode("\n",$data));
		foreach ($dataarray as $value) {
			$newdata = explode(',',$value);
			$reporterid .= $newdata['0'] . ',';
		}
		$reporterid = rtrim($reporterid,',');
		
		$condition = '';
		//搜索标签
        if ($this->input['searchtag_id']) {
            $searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
            foreach ((array)$searchtag['tag_val'] as $k => $v) {
                if ( in_array( $k, array('_id') ) )
                {
                    //防止左边栏分类搜索无效
                    continue;
                }
                $this->input[$k] = hg_clean_value($v);
            }
        }
        //搜索标签    
		/**************权限控制开始**************/
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND c.user_id = '.$this->user['user_id'];//不允许查看他人数据
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
			{
				$condition .= ' AND c.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = '';
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if ($authnode_str === '0')
				{
					$condition .= ' AND c.sort_id IN(' . $authnode_str . ')';
				}
				if ($authnode_str)
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
						$authnode_str .= implode(',', $n) .',';
					}
					$authnode_str = in_array('0', $authnode) ? $authnode_str .'0' : trim($authnode_str,',');
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
					}
				}
				
			}
		}

		/**************权限控制结束**************/
		
		if($this->input['key'])
		{
			$condition .= ' AND c.title LIKE "%'.trim(urldecode($this->input['key'])).'%"';
		}
		if($this->input['user_name'])
		{
			$condition .= ' AND c.user_name = "'.trim($this->input['user_name']).'"';
		}
		//分类列表
		if ($this->input['contribute_sort'] && intval($this->input['contribute_sort'])!= -1)
		{
			$condition .= ' AND c.sort_id = '.$this->input['contribute_sort'] ; 
		}
		
		if ($this->input['contribute_sort_audit'] && $this->input['contribute_sort_audit'] != -1)
		{
			
			$condition .= ' AND c.audit = '.$this->input['contribute_sort_audit'] ; 
		}
		if ($this->input['_id'])
		{
			$condition .= ' AND c.sort_id = '.$this->input['_id'] ; 
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
		if($this->input['contribute_sort_report'])
		{
			$condition .= "AND c.user_id in ($reporterid)";
		}
		if ($this->input['contribute_is_follow'])
		{
			$condition .= " AND c.is_follow = ".$this->input['contribute_is_follow'] ; 
		}
		if ($this->input['other_show_id'])
		{
			$condition .= "  and c.sort_id in (".$this->input['other_show_id'].") " ; 
		}
		if ($this->input['other_audit_id'])
		{
			$condition .= "  and c.audit in (".$this->input['other_audit_id'].") " ; 
		}
		return $condition;
	}
	
	function detail()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'manage'));
		/*********权限验证结束*********/
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->con->detail( $id );
		$this->addItem($data);
		$this->output();
	}
	
	
	public function add()
	{
		$this->show();
	}
	
	
	public function output_sort()
	{
		$ret = $this->con->allsort();
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function show_sort(){
		$ret = $this->con->allsort();
		$this->addItem($ret);
		$this->output();
	}
	
	
	//内容详细页面
	public function show_opration()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'manage'));
		/*********权限验证结束*********/
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
	public function download()
	{
		$contribute_id = intval($this->input['id']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'materials WHERE content_id='.$contribute_id;
		$material = array();
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$material[] = $row['dir'] . $row['material_path'] . $row['pic_name'];
		}
		if(!$material)
		{
			$this->errorOutput('该报料不存在图片素材，下载失败');
		}
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$material_server = new material();
		$str = '';
		foreach ($material as $m)
		{
			$str .= ',' . $m;
		}
		$str = trim($str, ',');
		$zip = $material_server->zip_material($str);
		$zip_url = $zip[0];
		$this->addItem($zip_url);
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
	
	/**
	 * 
	 * @Description  显示地图的默认位置
	 * @author Kin
	 * @date 2013-4-23 下午04:10:31
	 */
	public function show_position()
	{
		$position = DEFAULT_POSITION;
		$this->addItem($position);
		$this->output();
	}
	
	/**
	 * 
	 * @Description 检测转发信息
	 * @author Kin
	 * @date 2013-4-23 下午04:11:21
	 */
	public function check_sort()
	{
		$id = intval($this->input['id']);
		$data = $this->con->check_sort($id);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 
	 * @Description  显示图片和视频截图
	 * @author Kin
	 * @date 2013-6-14 下午02:38:22
	 */
	public function show_pic()
	{
		$id = intval($this->input['id']);   //爆料id
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->con->show_pic($id);
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