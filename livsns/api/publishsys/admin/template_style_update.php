<?php
/**
 * 
 */
require_once './global.php';
define('MOD_UNIQUEID','template_style');
class template_style_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/style.class.php';
		$this->obj = new style();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template_style',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		if(!$this->input['title'])
		{
			$this->errorOutput('no title');
		}
		if(!$this->input['mark'])
		{
			$this->errorOutput('no mark');
		}
		$data = array(
			'title'         =>   $this->input['title'],
			'mark'          =>   $this->input['mark'],
			'pic'           =>   $this->input['log'],
			'state'         =>   intval($this->input['state']),
//			'isusing'       =>   intval($this->input['isusing']),
			'site_id'       =>   $this->input['site_id'],
			'create_time'   =>   TIMENOW,
			'update_time'   =>   TIMENOW,
			'user_id'       =>   $this->user['user_id'],
			'user_name'     =>   $this->user['user_name'],
			'appid'         =>   $this->user['appid'],
			'appname'       =>   $this->user['display_name'],
		);
		if($this->check_mark($data['mark']))
		{
			$this->errorOutput('mark exists');
		}
		$history_material_id = $now_material_id = array();
		if(is_array($data['pic']) && count($data['pic']))
		{
			foreach($data['pic'] as $k => $v)
			{
				$v= json_decode(html_entity_decode($v),1);
				$material[$k] = $v[0];
				$now_material_id[] = $v[0]['id'];
			}
			$data['pic'] = addslashes(json_encode($material));
		}
		if(is_array($this->input['history']) && count($this->input['history']))
		{
			foreach($this->input['history'] as $k => $v)
			{
				$v = json_decode(html_entity_decode($v),1);
				$history_material_id[] = $v[0]['id'];
			}
			$del_material_id = array_diff($history_material_id,$now_material_id);
			if($del_material_id)
			{
				include_once(ROOT_PATH . 'lib/class/material.class.php');
				$this->mater = new material();	
				$del_material_id = implode(',',$del_material_id);
				$this->mater->delMaterialById($del_material_id,2);	
			}		
		}				
		$data['id'] = $this->obj->create($data);
//		if($data['isusing'] == 1)
//		{
//			$this->obj->update_using($data['id'], 0);
//		}
		$this->addItem($data);
		$this->output();
	}
	public function update()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template_style',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$id= $this->input['id'];
		if(!$id)
		{
			$this->errorOutput('no id');
		}
		$condition = " AND id = " . $id;
		$stylePreInfo = $this->obj->detail($condition);
		if(!$stylePreInfo)
		{
			$this->errorOutput('template style not exists');
		}	
		$data = array();
		if($stylePreInfo['isdefault'])
		{
			$data = array(
				'title'         =>  $this->input['title'],
				'update_time'   =>  TIMENOW, 
			);
		}
		else
		{
			$data = array(
				'title'         =>   $this->input['title'],
				'mark'          =>   $this->input['mark'],
				'pic'           =>   $this->input['log'],
				'state'         =>   intval($this->input['state']),
//				'isusing'       =>   intval($this->input['isusing']),
				'site_id'       =>   $this->input['site_id'],
				'update_time'	=>   TIMENOW,			
			);
			if(!$data['mark'])
			{
				$this->errorOutput('no mark');
			}
			$info=$this->check_mark($data['mark']);
			if($info && $info['id'] != $id)
			{
				$this->errorOutput('mark exists');
			}
			$old_material_id = $history_material_id = $now_material_id = array();
			if(is_array($data['pic']) && count($data['pic']) > 0)
			{
				foreach($data['pic'] as $k => $v)
				{
					$v= json_decode(html_entity_decode($v),1);
					$data['pic'][$k] = $v[0];
					$now_material_id[] = $v[0]['id'];
				}
				$data['pic'] = addslashes(json_encode($data['pic']));
			}
			include_once(ROOT_PATH . 'lib/class/material.class.php');
			$this->mater = new material();				
			if($info['pic'])      //处理已经入库后删除的图片
			{
				$old_material = json_decode($info['pic'],1);
				if(is_array($old_material) && count($old_material) > 0 )
				{
					foreach($old_material as $k => $v)
					{
						$old_material_id[] = $v['id'];
					}
				}
				$old_del_id = array_diff($old_material_id,$now_material_id);
				if($old_del_id)  
				{
					$old_del_id = implode(',',$old_del_id);
					$this->mater->delMaterialById($old_del_id,2);	
				}
			}
			if(is_array($this->input['history']) && count($this->input['history']))   //处理还没有入库 删除的图片
			{
				foreach($this->input['history'] as $k => $v)
				{
					$v = json_decode(html_entity_decode($v),1);
					$history_material_id[] = $v[0]['id'];
				}
				$del_material_id = array_diff($history_material_id,$now_material_id);
				if($del_material_id)
				{
					$del_material_id = implode(',',$del_material_id);
					$this->mater->delMaterialById($del_material_id,2);	
				}		
			}											
		}
		$this->obj->update($data,$condition);		
		$data['id'] = $id;
//		if($data['isusing'] == 1)
//		{
//			$this->obj->update_using($data['id'], 0);
//		}		
		$this->addItem($data);
		$this->output();
	}
	public function audit()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('no id');
		}
		if($this->input['audit'] == 0)
		{
			$condition = " AND id IN(" . $id . ")";
			$info = $this->obj->show($condition);
			if(is_array($info) && count($info)>0)
			{
				foreach($info as $k => $v)
				{
					if($v['isdefault'] ==1)
					{
						$ret = array('errno' => 1,'errmsg' => $v['title'] . '为系统内置套系,不能关闭');
						$this->addItem($ret);
						$this->output();
					}
					if($v['isusing'] == 1)
					{
						$ret = array('errno' => 1, 'errmsg' => $v['title'] . '正在使用,不能关闭');
						$this->addItem($ret);
						$this->output();
					}
				}
			}
		}
		$audit = intval($this->input['audit']);
		$ret = $this->obj->audit($id, $audit);
		$this->addItem($ret);
		$this->output();
	}
	public function delete()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template_style',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('no id');
		}
		$condition = " AND id IN(" . $id . ")";
		$info = $this->obj->show($condition);
		if(is_array($info) && count($info) > 0)
		{
			foreach($info as $k => $v)
			{
				if($v['isusing'])
				{
					$this->errorOutput($v['title'] . '正在使用,不能删除');
				}
				if($v['isdefault'] == 1)
				{
					$this->errorOutput( $v['title'] . '为系统内置,不能删除');
				}
			}		
		}
		$ret = $this->obj->delete($condition);
		$this->addItem($id);
		$this->output();
	}
	public function sort(){}
	public function publish(){}
	public function upload()
	{
		if($_FILES['Filedata'])
		{			
			include_once(ROOT_PATH . 'lib/class/material.class.php');
			$this->mater = new material();
			$material = $this->mater->addMaterial($_FILES); //插入各类服务器
			if(!empty($material))
			{
				$material['success'] = true;
			    $return = $material;
			}
			else
			{
				$return = array(
					'success' => false,
					'error' => '文件上传失败',
				);
			}			
		}
		else 
		{
			$return = array(
				'success' => false,
				'error' => '文件上传失败',
			);
		}
		$this->addItem($return);	
		$this->output();	
	}
    
    /**
     * 修改套系为当前站点正在使用套系
     */
	public function update_using()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('no id');
		}
		$ret = $this->obj->update_using($id);		
		$this->addItem($ret);
		$this->output();
	}
    
    /**
     * 取标识为mark的套系
     */
	private function check_mark($mark)
	{
		$condition = " AND mark='".$mark."'";
		$ret = $this->obj->detail($condition);
		return $ret;
	}
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}	
}
$out = new template_style_update();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = "unknow";
}
$out->$action();
?>
