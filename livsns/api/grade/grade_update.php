<?php
define('MOD_UNIQUEID','grade');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require_once(CUR_CONF_PATH . 'lib/grade_mode.php');
class grade_update extends outerUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new grade_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建初始记录
	 */
	public function create()
	{
		/******************************************
		$c_id = intval($this->input['content_id']);
		$style_id = intval($this->input['style_id']);
		//$scores = $this->input['scores'];
		
		//通过内容id查看是否已经有此id的记录(防止更换样式)
		//如果已经有此记录,就要在更新order_id时同时更新style_id
		
		//通过内容id取得评分对象信息
		require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->pub_content = new publishcontent();
		$data['content_id'] = $c_id;
		$result = $this->pub_content->get_content($data);
		$result = $result[0];
		//通过样式id取得样式信息
		$sql = "SELECT name,index_pic,points_system FROM " .DB_PREFIX. "grade_style WHERE id=" .$style_id;
		$re = $this->db->query_first($sql);
		//节点入库
		$data = array(
			'style_id' => $style_id,
			'c_id' => $c_id,
			'c_title' => $result['title'],
			'c_column' => $result['column_name'],
			//'c_type' => $result['c_title'],	//类型??????
			'c_user_name' => $result['create_user'],
			'c_create_time' => $result['create_time'],
			'c_ip' => $result['ip'],
			'scores' => 0, //初始化为0
			'style_name' => $re['name'],
			'index_pic' => $re['index_pic'],
			'points_system' => $re['points_system'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
		);
		//取栏目id
		$ret = $this->db->query_first("SELECT id FROM " . DB_PREFIX . "grade_node WHERE name='".$data['c_column']."'");
		$node_id = $ret['id'];
		if(!$node_id)
		{
			$sql = "INSERT INTO " . DB_PREFIX . "grade_node SET is_last='1',name='" .$data['c_column']. "'";
			$this->db->query($sql);
			$column_id = $this->db->insert_id();
			if($column_id)
			{
				$data['column_id'] = $column_id;
			}
		}
		else
		{
			$data['column_id'] = $node_id;
		}
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$data['index_pic'] = unserialize($data['index_pic']);
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem($data);
			$this->output();
		}
		******************************************/
	}
	
	public function update()
	{
		$c_id = $this->input['content_id']; //内容id
		$style_id = intval($this->input['style_id']); //样式id
		$scores = intval($this->input['scores']); //得分
		$points_system = $this->input['points_system']; //分制
		
		//查看是否有初始化记录
		if(!$c_id)
		{
			$this->errorOutput("没有内容id");
		}
		if(!$style_id)
		{
			//$this->errorOutput("没有样式id");
		}
		if(!$points_system)
		{
			//$this->errorOutput("没有分制");
		}
		$sql = "SELECT id FROM " .DB_PREFIX. "grade WHERE c_id=".$c_id;
		$r = $this->db->query_first($sql);
		$id = $r['id'];
		
		//如果没有初始记录,就创建,否则就更新
		if(!$id)
		{
			//通过内容id取得评分对象信息
			require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
			$this->pub_content = new publishcontent();
			$data['content_id'] = $c_id;
			$result = $this->pub_content->get_content($data);
			$result = $result[0];
			//通过样式id取得样式信息
			$sql = "SELECT name,index_pic,points_system FROM " .DB_PREFIX. "grade_style WHERE id=" .$style_id;
			$re = $this->db->query_first($sql);
			//节点入库
			$data = array(
				'style_id' => $style_id,
				'c_id' => $c_id,
				'c_title' => $result['title'],
				'c_column' => $result['column_name'],
				//'c_type' => $result['c_title'],	//类型??????
				'c_user_name' => $result['create_user'],
				'c_create_time' => $result['create_time'],
				'c_ip' => $result['ip'],
				'scores' => 0, //初始化为0
				'style_name' => $re['name'],
				'index_pic' => $re['index_pic'],
				'points_system' => $re['points_system'],
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
			);
			//取栏目id
			$ret = $this->db->query_first("SELECT id FROM " . DB_PREFIX . "grade_node WHERE name='".$data['c_column']."'");
			$node_id = $ret['id'];
			if(!$node_id)
			{
				$sql = "INSERT INTO " . DB_PREFIX . "grade_node SET is_last='1',name='" .$data['c_column']. "'";
				$this->db->query($sql);
				$column_id = $this->db->insert_id();
				if($column_id)
				{
					$data['column_id'] = $column_id;
				}
			}
			else
			{
				$data['column_id'] = $node_id;
			}
			$vid = $this->mode->create($data);
			if($vid)
			{
				$data['id'] = $vid;
				$data['index_pic'] = unserialize($data['index_pic']);
				//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
				$this->addItem($data);
				$this->output();
			}
		}
		else
		{
			//$scores = intval($this->input['scores']);
			/*
			 * 提前要取得分制$points_system
			 * 可以$this->input获取,减少查库
			 */
			//$points_system = $this->input['points_system']; //分制
			if($scores)
			{
				//查出原来
				$sql = " SELECT * FROM " .DB_PREFIX. "grade WHERE id = '" .$id. "'";
				$pre_data = $this->db->query_first($sql);
				if(!$pre_data)
				{
					return false;
				}
				$sql = "UPDATE " .DB_PREFIX. "grade SET ";
				if($points_system == '10')
				{
					$star = intval($scores/2);
				}
				else if($points_system == '5')
				{
					$star = intval($scores);
				}
				switch ($star)
				{
					case 1:$sql .= "one_star=one_star+1,";break;
					case 2:$sql .= "two_star=two_star+1,";break;
					case 3:$sql .= "three_star=three_star+1,";break;
					case 4:$sql .= "four_star=four_star+1,";break;
					case 5:$sql .= "five_star=five_star+1,";break;
				}
				
				$sql .= "people_num=people_num+1,total=total+" .$scores. ",update_time=" .TIMENOW. " WHERE id='" .$id."'";
				$ret = $this->db->query($sql);
				if($ret)
				{
					$sql = " SELECT scores,total,people_num FROM " .DB_PREFIX. "grade WHERE id = '" .$id. "'";
					$ret_data = $this->db->query_first($sql);
					if($ret_data['people_num'])
					{
						$ret_data['scores'] = round($ret_data['total']/$ret_data['people_num'],1);
					}	 
					//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
					$this->addItem($ret_data);
					$this->output();
				}
			}
			else
			{
				$sql = " SELECT scores,total,people_num FROM " .DB_PREFIX. "grade WHERE id = '" .$id. "'";
				$ret_data = $this->db->query_first($sql);
				if($ret_data['people_num'])
				{
					$ret_data['scores'] = round($ret_data['total']/$ret_data['people_num'],1);
				}	 
				//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
				$this->addItem($ret_data);
				$this->output();
			}
		}
		
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	public function create_grade()
	{
		$data = array(
			/*
			 * 
			 * */
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new grade_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>