<?php
define('MOD_UNIQUEID','mood_style');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/mood_style_mode.php');
require_once(ROOT_DIR . 'lib/class/material.class.php');
class mood_style_update extends adminUpdateBase
{
	private $mode;
	private $material;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new mood_style_mode();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage')); //判断是否有创建的权限
		}
		$status = $this->get_status_setting('create');
		if(!trim($this->input['name']))
		{
			$this->errorOutput(NO_TITLE);
		}
		$data = array(
			'name'             => trim($this->input['name']),
		    'status'           => $status,
		    'create_user_id'   => $this->user['user_id'],
		    'create_user_name' => $this->user['user_name'],
		    'create_time'      => TIMENOW,
		    'update_user_id'   => $this->user['user_id'],
		    'update_user_name' => $this->user['user_name'],
		    'update_time'      => TIMENOW,
		);
		$option_title = $this->input['mood_name'];
		$data['count'] = count(array_filter($option_title));
		if(!$data['count'])
		{
			$this->errorOutput("心情选项不能为空");
		}
		if($_FILES['Filedata'])
		{
			$picture = $this->material->addMaterial($_FILES);
			$picture_info = array(
			      'host'        => $picture['host'],
			      'dir'         => $picture['dir'],
			      'filepath'    => $picture['filepath'],
			      'filename'    => $picture['filename'],
			);
			$data['index_picture'] = $picture_info['filename'] ? @serialize($picture_info) : '';
		}
		$vid = $this->mode->create($data);
		//创建各选项
		if(is_array($option_title))
		{
			foreach ($option_title as $key=>$val)
			{
				if(trim($val))
				{
					if($_FILES['Filedata_'.$key])
					{
					$pic[$key]['Filedata'] = $_FILES['Filedata_'.$key];
					$picture = $this->material->addMaterial($pic[$key]);
					$picture_info = array(
			        'host'        => $picture['host'],
			        'dir'         => $picture['dir'],
			        'filepath'    => $picture['filepath'],
			        'filename'    => $picture['filename'],
					);
					$option_pic[] = $picture_info['filename'] ? @serialize($picture_info) : '';
					}
					$create_data = array(
					    'mood_name'   => trim($val),
					    'style_id'    => $vid,
					    'picture'     => $option_pic[$key],
					);
					$this->db->insert_data($create_data, 'mood_option');
				}
			}
		}		
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建样式','',$data,'创建心情样式' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage')); //判断是否有创建的权限
			/*********************************/
		}
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
				
		$vid = intval($this->input['id']);
		$sql = "SELECT * FROM " .DB_PREFIX.'mood_style WHERE id = ' .$vid;
		$re = $this->db->query_first($sql);
		$status = $re['status'];
		if($status == 1)
		{
			$status = $this->get_status_setting('update_audit');
		}
		if(!trim($this->input['name']))
		{
			$this->errorOutput(NO_TITLE);
		}
		$update_data = array(
			'name' => trim($this->input['name']),
		);
		$option_id = $option_title = $option_pic = $option_order_id = array();
		$option_id = $this->input['option_id']; //选项id
		$option_title = $this->input['mood_name']; //选项名称
		$option_order_id = $this->input['order_id'];  //选项排序
		$option_pic = '';
		$update_data['count'] = count(array_filter($option_title));
		if(!$update_data['count'])
		{
			$this->errorOutput("心情选项不能为空");
		}
		if($_FILES['Filedata'])
		{
			$main_pic['Filedata'] = $_FILES['Filedata'];
			$picture = $this->material->addMaterial($main_pic);
			$picture_info = array(
			      'host'        => $picture['host'],
			      'dir'         => $picture['dir'],
			      'filepath'    => $picture['filepath'],
			      'filename'    => $picture['filename'],
			);
			$update_data['index_picture'] = $picture ? @serialize($picture) : '';
		}
		$ret = $this->mode->update($vid,$update_data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('样式更新失败');
		}
		
		//更新标记
		$affected_rows = $ret['affected_rows'];
		
		$edit_option_id = $delete_option_id = array();
		
		$_options = $this->mode->get_options($vid);;  //取出现有的心情顶踩数据各选项

		if(is_array($_options))
		{
			foreach ($_options as $_k=>$_v)
			{
				$_option_id[] = $_v['id'];  //原有的各选项id
			}
		}
		
		$delete_option_id = @array_diff($_option_id,$option_id);
		if($delete_option_id)
		{
			$delete_option_ids = implode(',',$delete_option_id);	
			$this->mode->delete_options($delete_option_ids);
		}
		
		//更新创建各选项
		if(is_array($option_title))
		{
			foreach ($option_title as $key=>$val)
			{
				if(trim($val))
				{
					if($_FILES['Filedata_'.$key])
					{
						$pic[$key]['Filedata'] = $_FILES['Filedata_'.$key];
					$picture = $this->material->addMaterial($pic[$key]);
					$picture_info = array(
			        'host'        => $picture['host'],
			        'dir'         => $picture['dir'],
			        'filepath'    => $picture['filepath'],
			        'filename'    => $picture['filename'],
					);
					$option_pic[$key] = $picture_info['filename'] ? @serialize($picture_info) : '';
					}
					
					$option_data = array(
					        'mood_name' => trim($val),
					        'style_id'  => $vid,
					        'order_id'  => $option_order_id[$key],
					);	
					
					if($option_pic[$key])
					{
						$option_data['picture'] = $option_pic[$key];    //如果重新上传了图片，则更新图片
					}
					
					$op_id = intval($option_id[$key]);
					if($op_id)
					{
						$uret = $this->db->update_data($option_data, 'mood_option', 'id =' . $op_id);
					    if(!$affected_rows)
						{
							$affected_rows = $uret;
						}
					}
					else
					{
						$uret = $this->db->insert_data($option_data, 'mood_option');
						if(!$affected_rows)
						{
							$affected_rows = $uret;
						}
					}
				}	
			}
		}

		if($affected_rows)
		{
			$update_user = array(
		    'update_user_id'   => $this->user['user_id'],
		    'update_user_name' => $this->user['user_name'],
		    'update_time'      => TIMENOW,
			'status'           => $status,
			);
		    $ret = $this->mode->update($vid,$update_user);
		    if (!$ret['id'])
		    {
		    	$this->errorOutput('样式更新失败');
		    }
		}
		
		if($ret)
		{
			$this->addLogs('更新样式',$ret,'','更新' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage')); //判断是否有创建的权限
			/*********************************/
		}
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除样式',$ret,'','删除' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function audit()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'audit')); //判断是否有创建的权限
			/*********************************/
		}
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if($this->input['audit'] == '')
		{
			$this->errorOutput(NO_AUDIT);
		}		
		$ids = trim($this->input['id']);
		$status = intval($this->input['audit']);
		$ret = $this->mode->audit($ids,$status);
		if($ret)
		{
			$this->addLogs('审核样式','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	//心情选项排序
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('mood_style', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new mood_style_update();
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