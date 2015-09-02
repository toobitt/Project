<?phprequire_once './global.php';require_once CUR_CONF_PATH.'lib/pic.class.php';define('MOD_UNIQUEID','weather');//模块标识class webapppicUpdateApi extends adminUpdateBase{	private $pic;	public function __construct()	{		parent::__construct();		$this->pic = new picClass();	}	public function __destruct()	{		parent::__destruct();	}		public function update()	{		$id = intval($this->input['id']);		if (!$id)		{			$this->errorOutput(OBJECT_NULL);		}		//查询已设置的图片		$sql = 'SELECT * FROM '.DB_PREFIX.'webapp_material WHERE id = '.$id;					$res = $this->db->query_first($sql);		$app_user_image = unserialize($res['app_user_image'])?unserialize($res['app_user_image']):array();		$user_image = unserialize($res['user_img']) ? unserialize($res['user_img']) : array();						$apps = $this->input['app'];		$custom_name = $this->input['custom_name'];		$delete_user_image = $this->input['delete_user_image'];		$delete_app_user_image = $this->input['delete_app_user_image'];				$data = array();				//有删除的优先删除		if ($delete_user_image)		{			$user_image = array();		}		if ($delete_bg_image)		{			$bg_image = array();		}		if (!empty($delete_app_user_image))		{			foreach ($app_user_image as $key=>$val)			{				if ($delete_app_user_image[$key])				{					unset($app_user_image[$key]);				}			}		}				$add_user_image = array();		$add_app_user_image = array();				//上传		if ($_FILES)		{			if ($_FILES['Filedata_user_image'])			{				$pic = array();				foreach($_FILES['Filedata_user_image'] AS $k =>$v)				{					$pic['Filedata'][$k] = $_FILES['Filedata_user_image'][$k];				}				$ret = $this->pic->uploadToPicServer($pic, $id);				if (!empty($ret))				{					$add_user_image = array(						'host'=>$ret['host'],						'dir'=>$ret['dir'],						'filepath'=>$ret['filepath'],						'filename'=>$ret['filename'],					);				}else {					$this->errorOutput("图片上传异常");				}				}						if (is_array($apps) && !empty($apps))			{				foreach ($apps as $key=>$val)				{										//用户自定义图片					if ($_FILES['Filedata_app_user_'.$val])					{						$temp = array();						$pic= array();						foreach($_FILES['Filedata_app_user_'.$val] AS $k =>$v)						{							$pic['Filedata'][$k] = $_FILES['Filedata_app_user_'.$val][$k];						}						$temp = $this->pic->uploadToPicServer($pic,'');						if (!empty($temp))						{							$add_app_user_image[$val] = array(								'appid'=>$val,								'custom_name'=>$custom_name[$key],								'host'=>$temp['host'],								'dir'=>$temp['dir'],								'filepath'=>$temp['filepath'],								'filename'=>$temp['filename'],							);						}else {							$this->errorOutput("图片上传异常");						}					}				}			}		}		//数据整合		if (!empty($add_user_image))		{			$user_image = $add_user_image;		}		if (!empty($add_app_user_image))		{			foreach ($add_app_user_image as $key=>$val)			{				$app_user_image[$key]=$val;			}		}		//入库前数据处理		$user_image = !empty($user_image) ? addslashes(serialize($user_image)) : '';		$app_user_image = !empty($app_user_image) ? addslashes(serialize($app_user_image)) : '';				$title = $this->input['title'];		$pic_icon = $this->input['pic_icon'];		$pic_value = $this->input['pic_value'];		$is_on = $this->input['is_on'];		$data = array(			'title'=>$title,			//'pic_icon'=>$pic_icon,			'pic_value'=>$pic_value,			'step_value'=>intval($this->input['step_value']),			'is_on'=>$is_on,			'user_img'=>$user_image,			'app_user_image' => $app_user_image,			'update_time' => TIMENOW,			'user_id'=>$this->user['user_id'],			'user_name'=>$this->user['user_name'],			'appid'=>$this->user['appid'],			'appname'=>$this->user['appname'],			'ip'=>$this->user['ip'],		);		$ret = $this->pic->update($data,$id);		$this->addItem('sucess');		$this->output();	}		public function delete()	{		$id = $this->input['id'];		$ret = $this->pic->delete($id);		$this->addItem($ret);		$this->output();	}	public function create()	{		$title = $this->input['title'];		$pic_icon = $this->input['pic_icon'];		$pic_value = $this->input['pic_value'];		$is_on = $this->input['is_on'];		$apps = $this->input['app'];		$custom_name = $this->input['custom_name'];		$user_image = array();		$app_user_image = array();		$data = array();		$res = $this->pic->check_title($title);		if (!$res)		{			$this->errorOutput("分类名称已存在");		}		//图片上传，上传的系统图		if ($_FILES)		{			//用户自定义图片			if ($_FILES['Filedata_user_image'])			{				$pic = array();				foreach($_FILES['Filedata_user_image'] AS $k =>$v)				{					$pic['Filedata'][$k] = $_FILES['Filedata_user_image'][$k];				}				$ret = $this->pic->uploadToPicServer($pic,'');				if (!empty($ret))				{					$user_image = array(						'host'=>$ret['host'],						'dir'=>$ret['dir'],						'filepath'=>$ret['filepath'],						'filename'=>$ret['filename'],					);				}else {					$this->errorOutput("图片上传异常");				}			}			//不同客户端图片			if (is_array($apps) && !empty($apps))			{				foreach ($apps as $key=>$val)				{					if ($_FILES['Filedata_app_user_'.$val])					{						$temp = array();						$pic = array();						foreach($_FILES['Filedata_app_user_'.$val] AS $k =>$v)						{							$pic['Filedata'][$k] = $_FILES['Filedata_app_user_'.$val][$k];						}						$temp = $this->pic->uploadToPicServer($pic,'');						if (!empty($temp))						{							$app_user_image[$val] = array(								'appid'=>$val,								'custom_name'=>$custom_name[$key],								'host'=>$temp['host'],								'dir'=>$temp['dir'],								'filepath'=>$temp['filepath'],								'filename'=>$temp['filename'],							);						}else {							$this->errorOutput("图片上传异常");						}					}				}			}		}		//入库前数据处理		$user_image = !empty($user_image) ? addslashes(serialize($user_image)) : '';		$app_user_image = !empty($app_user_image) ? addslashes(serialize($app_user_image)) : '';				//入库		$data =array(			'title'=>addslashes($title),			'pic_icon'=>$pic_icon,			'pic_value'=>$pic_value,			'step_value'=>intval($this->input['step_value']),			'is_on'=>$is_on,			'user_img'=>$user_image,			'app_user_image'=>$app_user_image,			'create_time'=>TIMENOW,			'update_time'=>TIMENOW,			'user_id'=>$this->user['user_id'],			'user_name'=>$this->user['user_name'],			'appid'=>$this->user['appid'],			'appname'=>$this->user['appname'],			'ip'=>$this->user['ip'],		);		$id = $this->pic->create($data);		$this->addItem($id);		$this->output();	}	public function del_img()	{		$id =$this->input['id'];		$this->pic->del_img($id);		$arr = array(			'id'=>explode(',', $id),		);		$this->addItem($arr);		$this->output();	}		public function unknow()	{		$this->errorOutput('此方法不存在！');	}	public function audit()	{			}	public function sort()	{			}	public function publish()	{			}	}$ouput = new webapppicUpdateApi();if(!method_exists($ouput, $_INPUT['a'])){	$action = 'unknow';}else{	$action = $_INPUT['a'];}$ouput->$action();?>			