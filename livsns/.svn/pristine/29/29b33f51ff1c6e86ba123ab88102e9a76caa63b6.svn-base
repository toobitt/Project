<?php
define('MOD_UNIQUEID','market_store');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/market_store_mode.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/recycle.class.php');
class market_store_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new market_store_mode();
		$this->recycle = new recycle();
		/******************************权限*************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$actions = (array)$this->user['prms']['app_prms']['supermarket']['action'];
			if(!in_array('manger',$actions))
			{
				$this->errorOutput('您没有权限访问此接口');
			}
		}
		/******************************权限*************************/
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{

		if(!$this->input['name'])
		{
			$this->errorOutput(NO_NAME);
		}
		if(!$this->input['market_id'])
		{
			$this->errorOutput('门店所属的商店不能为空');
		}
		if($this->input['tel'])		
		{
			$tel = implode(',', array_filter($this->input['tel']));
		}
		if($this->input['logo_id'])
		{
           $img_id = implode(',' , array_filter($this->input['logo_id']));  //多图图片数组去空
		}
		
		$data = array(
			'name'                => trim($this->input['name']),
		    'logo_id'             => isset($img_id) ? $img_id : '',
		    'index_pic'           => intval($this->input['logo']),
		    'market_id'           => intval($this->input['market_id']),
			'address'             => trim($this->input['address']),
		    'tel'                 => isset($tel) ? $tel : '',
		    'opening_time'        => trim($this->input['opening_time']),
		    'parking_num'         => $this->input['parking_num'],
		    'brief'               => trim($this->input['brief']),
		    'traffic'             => trim($this->input['traffic']),
			'free_bus'            => $this->input['free_bus'],
			'baidu_longitude'     => $this->input['baidu_longitude'],
			'baidu_latitude' 	  => $this->input['baidu_latitude'],
		    'ip'				  => hg_getip(),
		    'create_time'         => TIMENOW,
			'update_time'         => TIMENOW,
			'user_name'	          => $this->user['user_name'],
			'user_id'	          => $this->user['user_id'],
		    'org_id'	          => $this->user['org_id'],
			'update_user_name'	  => $this->user['user_name'],
			'update_user_id'	  => $this->user['user_id'],
		);
		
			
		//如果百度坐标存在的话，就转换为gps坐标也存起来
		if($data['baidu_latitude'] && $data['baidu_longitude'])
		{
			$gps = FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['gps_x'] = $gps['gps_x'];
			$data['gps_y'] = $gps['gps_y'];
		}
		else
		{
			$data['gps_x'] = 0;
			$data['gps_y'] = 0;
		}
		
		$ret = $this->mode->create($data);
		
		if($ret)
		{
			$this->addLogs('创建门户信息','',$ret,'创建门户信息' . $ret['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if(!$this->input['name'])
		{
			$this->errorOutput(NO_NAME);
		}
		if($this->input['tel'])		
		{
			$tel = implode(',',array_filter($this->input['tel']));
		}
	    if($this->input['logo_id'])
		{
            $img_id = implode(',' , array_filter($this->input['logo_id']));  //多图图片数组去空
		}
		
		$update_data = array(
			'name'                => trim($this->input['name']),
		    'index_pic'           => intval($this->input['logo']),
		    'logo_id'             => isset($img_id) ? $img_id : '',
		    'address'             => trim($this->input['address']),
		    'opening_time'        => trim($this->input['opening_time']),
		    'tel'                 => isset($tel) ? $tel : '',
		    'parking_num'         => $this->input['parking_num'],
			'baidu_longitude'     => $this->input['baidu_longitude'],
			'baidu_latitude' 	  => $this->input['baidu_latitude'],
		    'brief'               => trim($this->input['brief']),
		    'traffic'             => trim($this->input['traffic']),
		    'free_bus'            => $this->input['free_bus'],
			'update_time'         => TIMENOW,
			'update_user_name'	  => $this->user['user_name'],
			'update_user_id'	  => $this->user['user_id'],
		);
		
		//如果百度坐标存在的话，就转换为gps坐标也存起来
		if($update_data['baidu_latitude'] && $update_data['baidu_longitude'])
		{
			$gps = FromBaiduToGpsXY($update_data['baidu_longitude'],$update_data['baidu_latitude']);
			$update_data['gps_x'] = $gps['gps_x'];
			$update_data['gps_y'] = $gps['gps_y'];
		}
		else
		{
			$update_data['gps_x'] = 0;
			$update_data['gps_y'] = 0;
		}			
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新门户信息',$ret,'','更新门户信息' . $this->input['id']);
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
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			foreach ($ret AS $k => $v)
			{
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 		=> $v['name'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('market_store' => $v),
				);
			}
			
			/********************************回收站***********************************/
			if($recycle)
			{
				foreach($recycle as $key => $value)
				{
					$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				}
			}
			/********************************回收站***********************************/
			$this->addLogs('删除门户信息',$ret,'','删除门户信息' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit(){}

	public function sort(){}
	public function publish(){}
	
	/**
	 * 上传logo 图片
	 */
	public function upload_img()
	{
		$logo['Filedata'] = $_FILES['logo'];
		if($logo['Filedata'] )
		{
			$material_pic = new material();
			$logo_info = $material_pic->addMaterial($logo);
			
			$logo  = array();
			$logo_pic = array(
			'host'     => $logo_info['host'],
			'dir'      =>$logo_info['dir'],
			'filepath' =>$logo_info['filepath'],
			'filename' =>$logo_info['filename'],
			'imgwidth' =>$logo_info['imgwidth'],
			'imgheight' =>$logo_info['imgheight'],
			);
			$img_info = addslashes(serialize($logo_pic));	
		}
		if(!$logo_pic) 
		{
			$this->errorOutput('没有上传的图片信息');
		}
		$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . $img_info ."'";
		$query = $this->db->query($sql);
		
		$vid = $this->db->insert_id();
        $data['id'] = $vid;
		$data['img_info'] = hg_fetchimgurl($logo_pic);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 删除上传的图片
	 */
	public function delete_upload_img()
	{
	    if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->delete_img($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除门店图片',$ret,'','删除门店图片' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
		
	}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new market_store_update();
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