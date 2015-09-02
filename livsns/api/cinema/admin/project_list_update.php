<?php
define('MOD_UNIQUEID','project_list');
require_once('./global.php');
require_once(CUR_CONF_PATH . 'lib/project_list_mode.php');
class project_list_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new project_list_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//权限
		$this->verify_content_prms(array('_action'=>'project'));
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		$cinema_id = intval($this->input['cinema_id']);
		$dates = $this->input['dates'];
		$movie_id = intval($this->input['movie_id']);
		$movie_name = trim($this->input['movie_name']);
		if(!$cinema_id)
		{
			$this->errorOutput(NOCINEMAID);
		}
		if(!$movie_id)
		{
			$this->errorOutput(NOMOVIEID);
		}
		if(!$dates)
		{
			$this->errorOutput(NODATES);
		}
		
		//检测该影片该日期是否已经有排片表
		if(!$this->check_dates($cinema_id, $movie_id, $dates, ''))
		{
			//echo json_encode(array('msg' => '该日期排片已存在'));exit;
			$this->errorOutput('该日期排片已存在');
		}
		
		//排片入库
		$project_data = array(
			'cinema_id' 			=> $cinema_id,
			'movie_id' 			=> $movie_id,
			'movie_name' 		=> $movie_name,
			'dates'				=> $dates,
			'create_time'		=> TIMENOW,
			'status'				=> $status ? $status : 0,
			'org_id'				=> $this->user['org_id'],	
			'user_id'			=> $this->user['user_id'],	
			'user_name'			=> $this->user['user_name'],	
			'ip'					=> hg_getip(),
		);
		$project_id = $this->mode->create($project_data,'project');	
		if(!$project_id)
		{
			$this->errorOutput('添加失败');
		}
		
		//排片详情入库
		$datas = json_decode(html_entity_decode($this->input['data']),1);
		if(!empty($datas))
		{
			foreach((array)$datas as $k => $v)
			{
				$project_list_data = array(
					'project_id'			=> $project_id,
					'project_time'		=> strtotime($dates.$v['project_time']), //场次(放映时间)
					'hall' 				=> $v['hall'], //厅号
					'ticket_price'		=> $v['ticket_price'], //票价
					'language'			=> trim($v['language']), //语言
					'dimension'	 		=> $v['dimension'], //影片维度
					'create_time'		=> TIMENOW,
					'org_id'				=> $this->user['org_id'],	
					'user_id'			=> $this->user['user_id'],	
					'user_name'			=> $this->user['user_name'],	
					'ip'					=> hg_getip(),
				);
				$vid = $this->mode->create($project_list_data,'project_list');
			}
		}
		else
		{
			$this->errorOutput(NODATAS);
		}
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			if(!$this->input['is_excel']) //如果不是excel调用create接口
			{
				$this->addItem('success');
				$this->output();
			}
		}
	}
	
	
	public function update()
	{
		$project_id = intval($this->input['project_id']);
		$cinema_id = intval($this->input['cinema_id']);
		$dates = $this->input['dates'];
		$movie_id = intval($this->input['movie_id']);
		$movie_name = trim($this->input['movie_name']);
		
		if(!$project_id)
		{
			$sql = "SELECT id FROM " .DB_PREFIX. "project WHERE cinema_id = " .$cinema_id. " AND movie_id = " .$movie_id. " AND dates = '" .$dates. "'";
			$project_id = $this->db->query_first($sql);
			$project_id = $project_id['id'];
		}
		if(!$cinema_id)
		{
			$this->errorOutput(NOCINEMAID);
		}
		if(!$movie_id)
		{
			$this->errorOutput(NOMOVIEID);
		}
		if(!$dates)
		{
			//$this->errorOutput(NODATES);
		}
		
		/**************更新数据权限判断***************/
		$sql = "SELECT * FROM " . DB_PREFIX ."project WHERE id = " .$project_id;
		$q = $this->db->query_first($sql);
		$info['id'] = $q['id'];
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'project';
		$s = $q['status'];
		$this->verify_content_prms($info);
		/*********************************************/
		###获取默认数据状态
		$status = $this->get_status_setting('update_audit',$s);
		//检测该影片该日期是否已经有排片表
		if(!$this->check_dates($cinema_id, $movie_id, $dates, $project_id))
		{
			//echo json_encode(array('msg' => '该日期排片已存在'));exit;
			$this->errorOutput('该日期排片已存在');
		}
		
		//更新排片
		$project_update_data = array(
			'cinema_id' 			=> $cinema_id,
			'movie_id' 			=> $movie_id,
			'movie_name' 		=> $movie_name,
			'dates'				=> $dates,
			'status'				=> $status ? $status : 0,
		);
		$re = $this->mode->update($project_id,$project_update_data,'project');	
		if($re)
		{
			$sql = "UPDATE " . DB_PREFIX . "project SET 
							update_user_name ='" . $this->user['user_name'] . "',
							update_user_id = '".$this->user['user_id']."',
							update_org_id = '".$this->user['org_id']."',
							update_ip = '" . hg_getip() . "', 
							update_time = '". TIMENOW . "' WHERE id=" . $project_id;
			$this->db->query($sql);
		}
			
		//更新详情
		$datas = json_decode(html_entity_decode($this->input['data']),1);
		//删除原来的数据
		$sql = "DELETE FROM " .DB_PREFIX. "project_list WHERE project_id = " .$project_id;
		$this->db->query($sql);
		if(!empty($datas))
		{
			//插入数据
			foreach((array)$datas as $k => $v)
			{
				$project_list_data = array(
					'project_id'			=> $project_id,
					'project_time'		=> strtotime($dates.$v['project_time']), //场次(放映时间)
					'hall' 				=> $v['hall'], //厅号
					'ticket_price'		=> $v['ticket_price'], //票价
					'language'			=> trim($v['language']), //语言
					'dimension'	 		=> $v['dimension'], //影片维度
					'create_time'		=> TIMENOW,
					'org_id'				=> $this->user['org_id'],	
					'user_id'			=> $this->user['user_id'],	
					'user_name'			=> $this->user['user_name'],	
					'ip'					=> hg_getip(),
				);
				$ret = $this->mode->create($project_list_data,'project_list');
			}
		}
		else
		{
			//$this->errorOutput(NODATAS);
		}
		//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
		$this->addItem('success');
		$this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		/**************删除权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'project WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'project'));
			}
		}
		/*********************************************/	
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
		/**************审核权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'project WHERE id IN ('. $this->input['id'] .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'project'));
			}
		}
		/*********************************************/
		$ret = $this->mode->audit($this->input['id'],$this->input['audit']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	private function check_dates($cinema_id, $movie_id, $dates, $project_id)
	{
		if($project_id)
		{
			$where = " AND id != " .$project_id;
		}
		$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "project WHERE cinema_id = " .$cinema_id. " AND movie_id = " .$movie_id. " AND dates = '" .$dates. "'" . $where;
		$re = $this->db->query_first($sql);
		if($re['total'] > 0)
		{
			return false;
		}
		else 
		{
			return true;
		}
	}
	
	/*
	 * excel导入排片
	 */
	public function excel_update()
	{
	 	if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $this->errorOutput('只有管理员可以操作');
        }
        
		require_once(CUR_CONF_PATH . 'lib/excel.class.php');
		$excel = new excel();
		//获取文件扩展名
		$extend = pathinfo($_FILES["excel"]["name"]);
		$extend = strtolower($extend["extension"]);
		//获取文件扩展名结束
		$time=date("Y-m-d-H-i-s");//取当前上传的时间
		$name=$time.'.'.$extend; //重新组装上传后的文件名
		$uploadfile=CACHE_DIR.$name;//上传后的文件名地址
		if ((($extend == "xls") && ($_FILES["file"]["size"] < 2000000)))
		{
			$tmp_name=$_FILES["excel"]["tmp_name"];
			if ($_FILES["excel"]["error"] > 0)
			{
				$this->errorOutput("Return Code: " . $_FILES["excel"]["error"] . "<br />");
			}
			else
			{
				$excel_info = $excel->show($uploadfile,$tmp_name,$this->user);
				if($excel_info && is_array($excel_info))
				{
					//取影院信息
					$sql = "SELECT id,title FROM " .DB_PREFIX. "cinema";
					$query = $this->db->query($sql);
					while($row = $this->db->fetch_array($query))
					{
						$cinema_info[$row['title']] = $row['id'];
					}
					
					//取影片信息
					$sql = "SELECT id,title FROM " .DB_PREFIX. "movie";
					$query = $this->db->query($sql);
					while($row = $this->db->fetch_array($query))
					{
						$movie_info[$row['title']] = $row['id'];
					}
					//处理excel信息
					foreach($excel_info as $key => $val)
					{
						if($cinema_info[$key])
						{
							$this->input['cinema_id'] = $cinema_info[$key];
						}
						else
						{
							$cinema_data = array(
								'title' 			=> trim($key),
								'create_time'	=> TIMENOW,
								'org_id'			=> $this->user['org_id'],	
								'user_id'		=> $this->user['user_id'],	
								'user_name'		=> $this->user['user_name'],	
								'ip'				=> hg_getip(),	
							);
							$insert_id = $this->db->insert_data($cinema_data, 'cinema');
							$this->input['cinema_id'] = $insert_id;
							$this->db->query("UPDATE ".DB_PREFIX."cinema SET order_id = {$insert_id}  WHERE id = {$insert_id}");
						}
						foreach((array)$val as $ke => $va)
						{
							if($movie_info[$ke])
							{
								$this->input['movie_id'] = $movie_info[$ke];
							}
							else 
							{
								$movie_data = array(
									'title' 			=> trim($ke),
									'status' 		=> 1,
									'create_time'	=> TIMENOW,
									'org_id'			=> $this->user['org_id'],	
									'user_id'		=> $this->user['user_id'],	
									'user_name'		=> $this->user['user_name'],	
									'ip'				=> hg_getip(),	
								);
								$insert_id = $this->db->insert_data($movie_data, 'movie');
								$this->input['movie_id'] = $insert_id;
								$this->db->query("UPDATE ".DB_PREFIX."movie SET order_id = {$insert_id}  WHERE id = {$insert_id}");
							}
							$this->input['movie_name'] = trim($ke);
							foreach((array)$va as $k => $v)
							{
								$this->input['dates'] = trim($k);
								$this->input['data'] = array();
								foreach((array)$v as $kk => $vv)
								{
									$tmp = array();
									$tmp = explode('|', $vv);
									$this->input['data'][] = array(
									    'project_time' => trim($tmp[0]),
									    'hall' => trim($tmp[4]),
									    'ticket_price' => trim($tmp[3]),
									    'language' => trim($tmp[1]),
									    'dimension' => trim($tmp[2]),
									    'id' => '0',
									);
								}
								if($this->input['data'])
								{
									$this->input['data'] = htmlentities(json_encode($this->input['data']));
								}
								$this->input['is_excel'] = 1;
								$this->create();
							}
						}
						
					}
					$this->addItem('success');
					$this->output();
				}
				else $this->errorOutput('导入失败');
			}
		}
		else
		{
			$this->errorOutput('文件错误,仅支持xls,文件不能大于2M');
		}
	}
	
	public function sort()
	{
        $ret = $this->drag_order('project', 'order_id');
        $this->addItem($ret);
        $this->output();
	
	}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new project_list_update();
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