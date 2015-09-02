<?php
define('MOD_UNIQUEID','epaper');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/epaper_mode.php');
require_once(ROOT_PATH . 'lib/class/recycle.class.php');
class epaper_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new epaper_mode();
		$this->recycle = new recycle();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//权限判断(只有管理员可创建)
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput('您没有创建报刊的权限');
		}
		$name = trim($this->input['title']);
		if(!$name)
		{
			return false;
		}
		
		$pub_company = trim($this->input['publishing_company']);
		if(!$pub_company)
		{
			//$this->errorOutput('请填写出版社');
		}
		$sponsor = trim($this->input['unit']);
		if(!$sponsor)
		{
			//$this->errorOutput("请填写主办单位");
		}
		
		$init_period = trim($this->input['period']);
		if(!$init_period && $init_period != 0)
		{
			$this->errorOutput('请填写出初始期数');
		}
		$init_date = trim($this->input['date']);
		if(!$init_date)
		{
			$this->errorOutput('请填写初始日期');
		}
		
		$sort_id = $this->input['type'];
		if($sort_id < 0)
		{
			$this->errorOutput("请选择电子报类型");
		}
		if($this->check_exist($name))
		{
			echo 1;
			exit;
		}
		$init_date = strtotime($this->input['date']);
		

		$data = array(

			'name'			=> 	$this->input['title'],
			'init_stage'	=>	$init_period,
			'init_time'		=> 	$init_date,
			'pub_company' 	=> 	$pub_company,
			'pub_no'		=> 	trim($this->input['number']),
			'code_name' 	=> 	trim($this->input['code']),
			'sponsor'		=> 	$sponsor,
			'sort_id'		=> 	$this->input['type'],
			'user_name'		=> 	$this->user['user_name'],
			'user_id' 		=> 	$this->user['user_id'],
			'org_id' 		=> 	$this->user['org_id'],
			'ip' 			=> 	hg_getip(),
			'create_time'	=>	TIMENOW,
			'update_time' 	=> 	TIMENOW,
			//'cur_time'	=>  $init_time,
			//'cur_stage'	=> 	$init_stage,//当前期数
		);
		//图片上传
		$cover['Filedata'] = $_FILES['Filedata'];
		if($cover['Filedata'])
		{
			include_once ROOT_PATH  . 'lib/class/material.class.php';
			$material = new material();
			$re = $material->addMaterial($cover);
			$cover  = array();
			$cover = array(
			'host' => $re['host'],
			'dir'=>$re['dir'],
			'filepath'=>$re['filepath'],
			'filename'=>$re['filename'],
			);
			$data['picture'] = addslashes(serialize($cover));
		}
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			$ret['update_time'] = date('Y-m-d H:i',$ret['update_time']);
			$ret['create_time'] = date('Y-m-d H:i',$ret['create_time']);
			$ret['init_time'] 	= date('Y-m-d',$ret['init_time']);
			
			$this->addLogs('创建',$ret,'','创建电子报' . $ret['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function update()
	{
		$id = $this->input['epaper_id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		/**************判断是否有权限更新报刊*************/
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids && implode(',', $prms_epaper_ids) != -1 && !in_array($id,$prms_epaper_ids))
		{
			$this->errorOutput('您没有更新此报刊的权限');
		}
		
		/**************更新他人数据权限判断***************/
		$sql = "select * from " . DB_PREFIX ."epaper where id = " . $id;
		$q = $this->db->query_first($sql);
		
		$info['id'] = $id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage_epaper';
		$this->verify_content_prms($info);
		/*********************************************/
		$name = trim($this->input['title']);
		if(!$name)
		{
			$this->errorOutput('请填写报刊名称'); 
		}
		
		$pub_company = trim($this->input['pub_company']);
		if(!$pub_company)
		{
			//$this->errorOutput('请填写出版社');
		}
		$sponsor = trim($this->input['sponsor']);
		if(!$sponsor)
		{
			//$this->errorOutput("请填写主办单位");
		}
		
		$init_period = trim($this->input['init_stage']);
		if(!$init_period && $init_period != 0)
		{
			$this->errorOutput('请填写出初始期数');
		}
		$init_date = trim($this->input['init_time']);
		if(!$init_date)
		{
			$this->errorOutput('请填写初始日期');
		}
		$sort_id = $this->input['type'];
		if($sort_id < 0)
		{
			$this->errorOutput("请选择电子报类型");
		}
		/*********************************************/

		if($this->check_exist($name) && $name != trim($this->input['old_name']))
		{
			echo 1;
			exit;
		}
		$init_time = strtotime($this->input['date']);
		$init_stage = intval($this->input['period']);
		$info = array(
			'name'			=> 	trim($this->input['title']),
			'init_stage'	=>	intval($this->input['init_stage']),
			'init_time'		=> 	strtotime($this->input['init_time']),
			'pub_company' 	=> 	trim($this->input['pub_company']),
			'pub_no'		=> 	trim($this->input['pub_no']),
			'code_name'	 	=> 	trim($this->input['code_name']),
			'sponsor'		=> 	trim($this->input['sponsor']),
			'sort_id'		=> 	$this->input['type'],
			//'user_name'	=> 	$this->user['sort_id'],
			//'ip' 			=> 	hg_getip(),
			//'update_time' => 	TIMENOW		
		);
		//图片上传
		$cover['Filedata'] = $_FILES['Filedata'];
		if($cover['Filedata'])
		{
			include_once ROOT_PATH  . 'lib/class/material.class.php';
			$material = new material();
			$re = $material->addMaterial($cover);
			$cover  = array();
			$cover = array(
				'host' => $re['host'],
				'dir'=>$re['dir'],
				'filepath'=>$re['filepath'],
				'filename'=>$re['filename'],
			);
			$info['picture'] = addslashes(serialize($cover));
		}
		$row = $this->mode->update($id,$info);
		if($row) //如果内容更改了
		{
			$info['update_user_name'] = $this->user['user_name'];
			$info['update_user_id'] = $this->user['user_id'];
			$info['update_org_id'] = $this->user['org_id'];
			$info['update_time'] = TIMENOW;
			$info['update_ip'] = $this->user['ip'];
			$sql = "UPDATE " . DB_PREFIX . "epaper SET 
					update_user_name ='" . $info['update_user_name'] . "',
					update_user_id = '".$info['update_user_id']."',
					update_org_id = '".$info['update_org_id']."',
					update_ip = '" . $info['update_ip'] . "', 
					update_time = '". TIMENOW . "' WHERE id=" . $id;
			$this->db->query($sql);
		}
		$this->addLogs('更新',$info,'','更新电子报' . $this->input['epaper_id']);//此处是日志，自己根据情况加一下
		$this->addItem($info);
		$this->output();
	}
	//检测是否重复
	public function check_exist($name)
	{
		$sql = "SELECT id FROM " . DB_PREFIX . "epaper WHERE name='" . $name . "'";
		$arr = $this->db->query_first($sql);
		$c_id = $arr['id'];
		return $c_id;
	}
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ids = urldecode($this->input['id']);
		//判断删除的杂志是否属于查看的杂志
		$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $node && implode(',', $node) != -1 )
		{
			//被删除杂志ids
			$arr_ids = explode(',', $ids);
			
			foreach ($arr_ids as $k => $v)
			{
				if(!in_array($v, $node))
				{
					$this->errorOutput('没权限删除报刊');
					break;
				}
			}
		}
		
		/**************删除他人数据权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'epaper WHERE id IN ('.$ids.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_epaper'));
			}
		}
		/*********************************************/	

		$ret = $this->mode->delete($this->input['id']);
		if(!$ret)
		{
			$this->errorOutput("报刊下面还有期刊,不能删除");
		}
		else
		{
			/******************回收站********************/
			foreach($ret as $ke => $v)
			{
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 			=> $v['name'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('epaper' => $v)
				);
			}
			if($this->settings['App_recycle'] && !empty($recycle))
			{
				foreach($recycle as $key => $value)
				{
					$re = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
					$result = $re['sucess'];
					$is_open = $re['is_open'];
				}
				if (!$result)
				{
					$this->errorOutput('删除失败，数据不完整');
				}
				if ($is_open)
				{
					//删除主表
					$sql = " DELETE FROM " .DB_PREFIX. "epaper WHERE id IN (" . $this->input['id'] . ")";
					$ret = $this->db->query_first($sql);
					return $ret;
				}
			}
			$this->addLogs('删除',$ret,'','删除电子报' . $this->input['id']);//此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = $this->input['id'];
		$ids = explode('_', $id);
		$epaper_id = $ids[0];
		$period_id = $ids[1];
		//权限判断
		$this->verify_content_prms(array('_action'=>'audit')); 
		//判断审核的报刊是否属于可查看的报刊
		$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $node && implode(',', $node)!=-1)
		{
			/************修改他人数据权限判断(需要时启用)*****/
			$sql = "select * from " . DB_PREFIX ."period where id = " . $period_id;
			$q = $this->db->query_first($sql);
			
			$info['id'] = $period_id;
			$info['org_id'] = $q['org_id'];
			$info['user_id'] = $q['user_id'];
			$info['_action'] = 'manage_period';
			$this->verify_content_prms($info);
			/*********************************************/
		}
		
			
		
		
		$epaper_id_arr = explode(',',$id);
		if(count($epaper_id_arr) <= 1)
		{
			$ret = $this->mode->audit($epaper_id,$period_id);
		}
		else
		{
			//报刊所在页的批量审核根据需求添加
			//$status = 2;
			//$sql = " UPDATE " .DB_PREFIX. "period SET status = " .$status. " WHERE epaper_id in (".$epaper_ids.")";
			//$this->db->query($sql);
		}
		
		if($ret)
		{
			$this->addLogs('审核','',$ret,'电子报' . $id . '最近一期' . $period_id);//此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new epaper_update();
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