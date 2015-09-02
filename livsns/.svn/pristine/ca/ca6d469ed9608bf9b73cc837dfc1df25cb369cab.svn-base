<?php
define('MOD_UNIQUEID','verify_bgpicture');
require_once('./global.php');
require_once(CUR_CONF_PATH . 'lib/verify_pic_font_mode.php');
class verify_bgpicture_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new verify_pic_font_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//权限
		//$this->verify_content_prms(array('_action'=>'manage_verify_change'));
		
		$picture = $_FILES['filedata'];
		$size = ini_get('upload_max_filesize');
		if(!$picture)
		{
			$this->errorOutput('上传失败');
		}
		
		$tmp_size = substr($size, 0, -1);
		if($picture['size'] > $tmp_size*1024*1024)
		{
			$this->errorOutput('图片不得大于'.$size);
		}
		//格式限制
		$uptypes = array(
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                    'image/pjpeg',
                    'image/gif',
                    'image/bmp',
                    'image/x-png'
        );
		$ptype = $picture["type"];
		if(!in_array($ptype,$uptypes))
		{
		    $this->errorOutput("上传的图片文件格式不正确");
		}
		//取得文件名及后缀
		$name = substr($picture['name'], 0,strrpos($picture['name'], '.'));
		$type = substr($picture['name'], strrpos($picture['name'], '.')+1);
		$data = array(
			"name" 			=> 	$name,
			"type"			=>	$type,
			"create_time" 	=> 	TIMENOW,
			"org_id" 		=> 	$this->user['org_id'],
			"user_id"		=> 	$this->user['user_id'],
			"user_name" 		=> 	$this->user['user_name'],
			"ip" 			=>	hg_getip(),
		);
		
		$this->data_check($data);	//数据验证
		
		//上传文件
		$dir = PIC_DIR;
		if(!is_dir($dir))
		{
			hg_mkdir($dir);
		}
		if(!move_uploaded_file($picture['tmp_name'],$dir.$picture['name']))
		{
			$this->errorOutput('error');
		}
		
		//缩放图片
		/*****************/
		$width = $this->settings['width']; //宽高在配置文件里改
		$height = $this->settings['height'];
		$image = imagecreatetruecolor($width,$height);
		$pic_info = getimagesize($dir.$name.'.'.$type);//获取图片宽高
		switch ($pic_info[2])
		{
			case 1:$im_in = imagecreatefromgif($dir.$name.'.'.$type);break;
			case 2:$im_in = imagecreatefromjpeg($dir.$name.'.'.$type);break;
			case 3:$im_in = imagecreatefrompng($dir.$name.'.'.$type);break;
			case 15:$im_in = imagecreatefromwbmp($dir.$name.'.'.$type);break;
			case 16:$im_in = imagecreatefromxbm($dir.$name.'.'.$type);break;
		}
		imagecopyresampled($image,$im_in,0,0,0,0,$width,$height,$pic_info[0],$pic_info[1]);
		imagejpeg($image,$dir.$picture['name']);
		/*****************/
		
		//入库
		$vid = $this->mode->create($data,'bgpicture');
		if($vid)
		{
			$data['id'] = $vid;
			$data['create_time'] = date('Y-m-d H:i',$data['create_time']);
			$verify = $this->settings['App_verifycode'];
			$data['dir'] = $verify['protocol'].$verify['host'].'/'.$verify['dir'].'data/pictures/'.$data['name'].'.'.$data['type'];
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/**************删除他人数据权限判断***************
		$sql = 'SELECT * FROM '.DB_PREFIX.'bgpicture WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_verify_change'));
			}
		}
		*********************************************/
		
		//删除判断,看是否被使用
		/*
		$id_arr = explode(',',$this->input['id']);
		$sql = "SELECT bgpicture_id FROM " .DB_PREFIX. "verify GROUP BY bgpicture_id";
		$arr = $this->db->fetch_all($sql);
		foreach($arr AS $k => $v)
		{
			$bgpicture_ids[] = $v['bgpicture_id'];
		}
		$same = array_intersect($id_arr,$bgpicture_ids);
		if($same)
		{
			$this->errorOutput('图片被占用,不可以删除');
		}
		*/
		$sql = "SELECT * FROM " .DB_PREFIX. "bgpicture WHERE is_using=1 AND id IN (".$this->input['id'].")";
		$re = $this->db->fetch_all($sql);
		if($re)
		{
			$this->errorOutput('背景图片正在使用,不可以删除');
		}
		$ret = $this->mode->delete($this->input['id'],'bgpicture');
		if($ret)
		{
			//删除文件
			foreach($ret AS $k => $v)
			{
				$file = CUR_CONF_PATH.'data/pictures/'.$v['name'].'.'.$v['type'];
				unlink($file);
			}
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		$id = urldecode($this->input['id']);	//背景图片id们
		$audit = $this->input['audit']; //操作标识,'审核'或'打回'
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}

		/**************审核权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'bgpicture WHERE id IN ('. $id .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				//$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
				if($val['is_using'] && $audit == 0)
				{
					$this->errorOutput('背景图片正在使用中,不可打回<br/>若要打回,请先确定要打回的图片不被占用');
				}
			}
		}
		/*********************************************/
		
		if($audit == 1)	//'审核'操作
		{
			$status = 1;
			$audit_status = '已审核';
		}
		elseif($audit == 0)	//'打回'操作
		{
			$status = 2;
			$audit_status = '已打回';
		}
		
		$sql = " UPDATE " .DB_PREFIX. "bgpicture SET status = " .$status. " WHERE id in (" . $id . ")";
		$this->db->query($sql);
		$ret = array('status' => $status,'id' => $id,'audit'=>$audit_status);
	
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核验证码背景图片' . $id);	//此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/** 数据检测 **/
	private function data_check($data = array())
	{
		if(!$data['name'])
		{
			$this->errorOutput('没有名称');
		}
		
		//检查名字是否重复
		$sql = "SELECT id FROM " . DB_PREFIX . "bgpicture WHERE name='" . $data['name'] . "'";
		$arr = $this->db->query_first($sql);
		$c_id = $arr['id'];
		if($c_id)
		{
			$this->errorOutput('该图片已存在');
		}
	}
	
	public function sort()
	{
        $ret = $this->drag_order('bgpicture', 'order_id');
        $this->addItem($ret);
        $this->output();
	
	}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new verify_bgpicture_update();
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