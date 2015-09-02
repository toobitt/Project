<?php
define('MOD_UNIQUEID','cinema');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/cinema_mode.php');
class cinema_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new cinema_mode();
		
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//权限
		$this->verify_content_prms(array('_action'=>'manage'));
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		$data = array(
			'title' => trim($this->input['title']),
			//'tel' => trim($this->input['tel']),
			'province_id'		=> $this->input['province_id'], //省
			'city_id' 			=> $this->input['city_id'], //市
			'status'				=> $status ? $status : 0,
			'area_id'			=> $this->input['area_id'], //区
			'stime'				=> strtotime($this->input['stime']), //营业时间开始
			'etime'				=> strtotime($this->input['etime']), //营业时间结束
			'indexpic'			=> intval($this->input['indexpic']),
			'baidu_longitude'	=> $this->input['baidu_longitude'], //经
			'baidu_latitude'	=> $this->input['baidu_latitude'], //纬
			'address'			=> trim($this->input['address']), //街道地址
			'create_time'		=> TIMENOW,
			'org_id'			=> $this->user['org_id'],	
			'user_id'			=> $this->user['user_id'],	
			'user_name'			=> $this->user['user_name'],	
			'ip'				=> hg_getip(),	
		);
		if(!$data['title'])
		{
			$this->errorOutput(NO_CINEMA_NAME);
		}
		if(!$data['province_id'] || !$data['city_id']  || !$data['area_id'])
		{
			$this->errorOutput(NO_AREA_ID);
		}
		if(!$data['baidu_longitude'] || !$data['baidu_latitude'])
		{
			$this->errorOutput(NO_LONGITUDE_OR_LATITUDE);
		}
		if($data['baidu_longitude'] && $data['baidu_latitude'])
		{
			$gps = $this->mode->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		
		//上传图片处理
		if ($_FILES)
		{
			$pics = $material = array();
			$pics['Filedata'] = $_FILES['indexpic'];
			$material = $this->mater->addMaterial($pics); //插入图片服务器
			
			if(!empty($material))
			{
				$img_info = array(
					'mid'      => $material['id'],
					'name'     => $material['name'],
					'host'     => $material['host'],
					'dir'      => $material['dir'],
					'filepath' => $material['filepath'],
					'filename' => $material['filename'],
					'type'     => $material['type'],
					'imgwidth' => $material['imgwidth'],
					'imgheight'=> $material['imgheight'],
					'filesize' => $material['filesize'],
				);
				
				$img_info = addslashes(serialize($img_info));
				
				
				$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . $img_info ."',create_time = '" . TIMENOW ."'";
				$this->db->query($sql);
				
				$data['indexpic'] = $this->db->insert_id();
			}
		}
		
		//电话处理
		$tel_name = $this->input['tel_name'];
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel)&&is_array($tel))
			{
				foreach ($tel as $k=>$v)
				{
					$telname=$tel_name[$k]?$tel_name[$k]:'联系电话'.($k+1);
					$tel_arr[] = array('telname'=>$telname,'tel'=>$v);
				}
			}
			$data['tel']=serialize($tel_arr);
		}
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$this->input['content'] = trim($this->input['content']);
			if($this->input['content'])
			{
				$brief = addslashes($this->input['content']);
				$sql = "INSERT INTO " .DB_PREFIX. "content SET cinema_id = " .$vid. ",content = '" .$brief. "'";
				$this->db->query($sql);
			}
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		$this->input['id'] = intval($this->input['id']);
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/**************更新数据权限判断***************/
		$sql = "SELECT * FROM " . DB_PREFIX ."cinema WHERE id = " . $this->input['id'];
		$q = $this->db->query_first($sql);
		$info['id'] = $q['id'];
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage';
		$s = $q['status'];
		$this->verify_content_prms($info);
		/*********************************************/
		###获取默认数据状态
		$status = $this->get_status_setting('update_audit',$s);
		//上传图片处理
		if ($_FILES)
		{
			//重新上传
			$pics = $material = array();
			$pics['Filedata'] = $_FILES['indexpic'];
			$material = $this->mater->addMaterial($pics,$this->input['id']); //插入图片服务器
			
			if(!empty($material))
			{
				$img_info = array(
					'mid'      => $material['id'],
					'name'     => $material['name'],
					'host'     => $material['host'],
					'dir'      => $material['dir'],
					'filepath' => $material['filepath'],
					'filename' => $material['filename'],
					'type'     => $material['type'],
					'imgwidth' => $material['imgwidth'],
					'imgheight'=> $material['imgheight'],
					'filesize' => $material['filesize'],
				);
				
				$img_info = addslashes(serialize($img_info));
				
				
				if(intval($this->input['indexpic_id']))
				{
					$sql = "UPDATE " . DB_PREFIX . "material SET img_info = '" . $img_info . "' WHERE id  = " . $this->input['indexpic_id'];
					$this->db->query($sql);
				}
				else 
				{
					$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . $img_info ."',create_time = '" . TIMENOW ."'";
					$this->db->query($sql);
					
					$this->input['indexpic_id'] = $this->db->insert_id();
				}
			}
		}
			
		
		$update_data = array(
			'title' => trim($this->input['title']),
			//'tel' => $contract_way,
			//'brief' => trim($this->input['brief']),
			'province_id'		=> $this->input['province_id'], //省
			'city_id' 			=> $this->input['city_id'], //市
			'status'				=> $status ? $status : 0,
			'area_id'			=> $this->input['area_id'], //区
			'stime'				=> strtotime($this->input['stime']), //营业时间开始
			'etime'				=> strtotime($this->input['etime']), //营业时间结束
			'indexpic'			=> intval($this->input['indexpic_id']),
			'baidu_longitude'	=> $this->input['baidu_longitude'], //经
			'baidu_latitude'	=> $this->input['baidu_latitude'], //纬
			'address'			=> trim($this->input['address']), //街道地址
		);
		if(!$update_data['title'])
		{
			$this->errorOutput(NO_CINEMA_NAME);
		}
		if(!$update_data['province_id'] || !$update_data['city_id']  || !$update_data['area_id'])
		{
			$this->errorOutput(NO_AREA_ID);
		}
		if(!$update_data['baidu_longitude'] || !$update_data['baidu_latitude'])
		{
			$this->errorOutput(NO_LONGITUDE_OR_LATITUDE);
		}
		if($update_data['baidu_longitude'] && $update_data['baidu_latitude'])
		{
			$gps = $this->mode->FromBaiduToGpsXY($update_data['baidu_longitude'],$update_data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		//电话处理
		$tel_name = $this->input['tel_name'];
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel)&&is_array($tel))
			{
				foreach ($tel as $k=>$v)
				{
					$telname=$tel_name[$k]?$tel_name[$k]:'联系电话'.($k+1);
					$tel_arr[] = array('telname'=>$telname,'tel'=>$v);
				}
			}
			$update_data['tel']=serialize($tel_arr);
		}
		$ret = $this->mode->update($this->input['id'],$update_data);
		
		//处理描述
		$brief = addslashes(trim($this->input['content']));
		$sql = "REPLACE INTO " .DB_PREFIX. "content SET cinema_id = " .$this->input['id']. ",content = '" .$brief. "'";
		$this->db->query($sql);
			
		if($ret)
		{
			$sql = "UPDATE " . DB_PREFIX . "cinema SET 
						update_user_name ='" . $this->user['user_name'] . "',
						update_user_id = '".$this->user['user_id']."',
						update_org_id = '".$this->user['org_id']."',
						update_ip = '" . hg_getip() . "', 
						update_time = '". TIMENOW . "' WHERE id=" . $this->input['id'];
			$this->db->query($sql);
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = " DELETE FROM " .DB_PREFIX. "project_list WHERE project_id IN (SELECT id FROM " .DB_PREFIX. "project WHERE cinema_id IN( " .$this->input['id']."))";
		/**************删除权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'cinema WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/*********************************************/	
		$ret = $this->mode->delete($this->input['id']);
		
		if($ret)
		{
			//删除相应的排片
			$sql = "DELETE FROM " .DB_PREFIX. "project_list WHERE project_id IN (SELECT id FROM " .DB_PREFIX. "project WHERE cinema_id IN( " .$this->input['id']."))";
			$this->db->query($sql);
			$this->db->query("DELETE FROM " .DB_PREFIX. "project WHERE cinema_id IN( " .$this->input['id']. " )");
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'cinema WHERE id IN ('. $this->input['id'] .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/*********************************************/
		
		$audit = intval($this->input['status']);
		
		$ret = $this->mode->audit($this->input['id'],$audit);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	/**
	 * 上传图片
	 */
	public function img_upload()
	{
		$picture['Filedata'] = $_FILES['Filedata'];
		
		if($picture['Filedata'])
		{
			$picture_pic = $this->mode->add_material($picture['Filedata']);
			$img_info = addslashes(serialize($picture_pic));	
		}
		if(!$picture_pic) 
		{
			$this->errorOutput(NO_IMGINFO);
		}
		$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . $img_info ."',create_time = '" . TIMENOW ."'";
		$query = $this->db->query($sql);
		
		$vid = $this->db->insert_id();
		$this->addItem($picture_pic);
		$this->output();
	}
	
	/**
	 * 上传影院描述图片
	 * @see adminUpdateBase::sort()
	 */
	public function upload()
	{
		$material = $this->mater->addMaterial($_FILES,0,0,intval($this->input['water_config_id']));
		if(!empty($material) && is_array($material))
		{
			$material['pic'] = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
            $code = $material['code'];
            /*
            $data = array(
                'material_id' => $material['id'],
                'name'        => $material['name'],
                'pic'         => serialize($material['pic']), 
                'host'        => $material['host'],
                'dir'         => $material['dir'],
                'filepath'    => $material['filepath'],
                'filename'    => $material['filename'],
                'type'        => $material['type'],
                'mark'        => $material['mark'],
                'imgwidth'    => $material['imgwidth'],
                'imgheight'   => $material['imgheight'],
                'filesize'    => $material['filesize'],
                'create_time' => $material['create_time'],
                'ip'          => $material['ip'],
                'remote_url'  => $material['remote_url'],      
            );
			$this->db->insert_data($data,"material");
			*/
			$material['filesize'] = hg_bytes_to_size($material['filesize']);
			$return = array(
				'success'    => true,
				'id'         => $material['id'],
				'filename'   => $material['filename'] . '?' . hg_generate_user_salt(4),
				'name'       => $material['name'],
				'mark'       => $material['mark'],
				'type'       => $material['type'],
				'filesize'   => $material['filesize'],
				'path'       => $material['host'] . $material['dir'],
				'dir'        => $material['filepath'],
				'code'       => $code,
			);
		}
		else
		{
			$return = array(
				'error' => '文件上传失败',
			);
		}
		$this->addLogs('上传图片','','', $return['name']);
		$this->addItem($return);
		$this->output();
	}
	
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new cinema_update();
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