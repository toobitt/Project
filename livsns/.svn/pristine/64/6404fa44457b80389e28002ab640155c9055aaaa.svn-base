<?php
define('MOD_UNIQUEID','market_member');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/market_member_mode.php');
require_once(CUR_CONF_PATH . 'lib/Barcodegen.class.php');
require_once(CUR_CONF_PATH . 'lib/PHPExcel.class.php');
require_once(CUR_CONF_PATH . 'lib/IdCard.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH .'lib/class/recycle.class.php');
require_once(ROOT_PATH .'lib/class/curl.class.php');
ini_set('max_execution_time', 3600);
ini_set('memory_limit', '1024M');
class market_member_update extends adminUpdateBase
{
	private $mode;
	private $barcode;
	private $material;
	private $recycle;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new market_member_mode();
		$this->barcode = new Barcodegen();
		$this->material = new material();
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
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['name'])
		{
			$this->errorOutput(NO_NAME);
		}
		
		if(!$this->input['phone_number'])
		{
			$this->errorOutput('没有手机号');
		}
		
		//验证卡号
		if(!$this->input['card_number'])
		{
			$this->errorOutput(NO_CARD_NUMBER);
		}
		else if($this->mode->isExistsMember(" card_number = '" .$this->input['card_number']. "' AND market_id = '" .$this->input['market_id']. "' "))
		{
			$this->errorOutput(THE_CARD_NUMBER_ALREADY_EXSIST);
		}
		
		if(!$this->input['birthday'])
		{
			$this->errorOutput(NO_BIRTHDAY);
		}
		
		//验证身份证
		/*
		if(!$this->input['id_card'])
		{
			$this->errorOutput(NO_ID_CARD);
		}
		else if($this->mode->isExistsMember(" id_card = '" .$this->input['id_card']. "' AND market_id = '" .$this->input['market_id']. "' "))
		{
			$this->errorOutput(THE_ID_CARD_ALREADY_EXSIST);
		}
		else if(!$this->idCardCheck->isIdNum($this->input['id_card']))
		{
			$this->errorOutput(ID_CARD_ERROR);
		}
		*/
		
		//根据出生日期以及年龄并且保存起来
		$idCardInfo = new IdCard();
		$birthday = $this->input['birthday'];
		$age = $idCardInfo->getAge($birthday);
		$month = intval(date('m',strtotime($birthday)));
		$day   = intval(date('d',strtotime($birthday)));
		$constellation_id = $idCardInfo->getConstellation($birthday);
		
		$data = array(
			'market_id' 		=> $this->input['market_id'],
			'card_number' 		=> $this->input['card_number'],
			'name' 				=> $this->input['name'],
			'age' 				=> $age,
			'month' 			=> $month,
			'day' 				=> $day,
			'birthday'			=> $birthday,
			'constellation_id' 	=> $constellation_id,
			'phone_number' 		=> $this->input['phone_number'],
			'email' 			=> $this->input['email'],
			'id_card' 			=> $this->input['id_card'],
			'user_id' 			=> $this->user['user_id'],
			'user_name' 		=> $this->user['user_name'],
			'update_user_id' 	=> $this->user['user_id'],
			'update_user_name' 	=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
		);
		
		//根据卡号生成条形码图片
		$barcode_img_path = CACHE_DIR . $data['card_number'] . '.png';
		$img_path = 'http://' . $this->settings['App_supermarket']['host'] . '/' .  $this->settings['App_supermarket']['dir'] . 'cache/' . $data['card_number'] . '.png';
		if($img_info = $this->createBarCode($data['card_number'],$barcode_img_path,$img_path))
		{
			$data['barcode_img'] = serialize($img_info);
		}

		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建商超会员',$data,'','创建商超会员' . $vid);
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
		
		if(!$this->input['phone_number'])
		{
			$this->errorOutput('没有手机号');
		}
		
		//查询出该会员所属的超市
		$member_info = $this->mode->detail($this->input['id']);
		$market_id = $member_info['market_id'];
			
		if(!$this->input['name'])
		{
			$this->errorOutput(NO_NAME);
		}
		
		//验证卡号
		if(!$this->input['card_number'])
		{
			$this->errorOutput(NO_CARD_NUMBER);
		}
		else if($this->mode->isExistsMember(" card_number = '" .$this->input['card_number']. "' AND id != '" .$this->input['id']. "' AND market_id = '" .$market_id. "' "))
		{
			$this->errorOutput(THE_CARD_NUMBER_ALREADY_EXSIST);
		}
		
		if(!$this->input['birthday'])
		{
			$this->errorOutput(NO_BIRTHDAY);
		}
		
		//验证身份证
		/*
		if(!$this->input['id_card'])
		{
			$this->errorOutput(NO_ID_CARD);
		}
		else if($this->mode->isExistsMember(" id_card = '" .$this->input['id_card']. "' AND id != '" .$this->input['id']. "' AND market_id = '" .$market_id. "' "))
		{
			$this->errorOutput(THE_ID_CARD_ALREADY_EXSIST);
		}
		else if(!$this->idCardCheck->isIdNum($this->input['id_card']))
		{
			$this->errorOutput(ID_CARD_ERROR);
		}
		*/

		//根据出生日期以及年龄并且保存起来
		$idCardInfo = new IdCard();
		$birthday = $this->input['birthday'];
		$age = $idCardInfo->getAge($birthday);
		$month = intval(date('m',strtotime($birthday)));
		$day   = intval(date('d',strtotime($birthday)));
		$constellation_id = $idCardInfo->getConstellation($birthday);

		$update_data = array(
			'card_number' 		=> $this->input['card_number'],
			'name' 				=> $this->input['name'],
			'age' 				=> $age,
			'month' 			=> $month,
			'day' 				=> $day,
			'birthday'			=> $birthday,
			'constellation_id' 	=> $constellation_id,
			'phone_number' 		=> $this->input['phone_number'],
			'email' 			=> $this->input['email'],
			'id_card' 			=> $this->input['id_card'],
			'update_user_id' 	=> $this->user['user_id'],
			'update_user_name' 	=> $this->user['user_name'],
			'update_time' 		=> TIMENOW,
		);
		
		//根据卡号生成条形码图片
		$barcode_img_path = CACHE_DIR . $update_data['card_number'] . '.png';
		$img_path = 'http://' . $this->settings['App_supermarket']['host'] . '/' .  $this->settings['App_supermarket']['dir'] . 'cache/' . $update_data['card_number'] . '.png';
		if($img_info = $this->createBarCode($update_data['card_number'],$barcode_img_path,$img_path))
		{
			$update_data['barcode_img'] = serialize($img_info);
		}

		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新商超会员',$ret,'','更新商超会员' . $this->input['id']);
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
					'content'		=> array('market_member' => $v),
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
			
			$this->addLogs('删除商超会员',$ret,'','删除商超会员' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	/***************************************扩展操作*****************************************/
	//绑定会员（对手机用户提交的信息与库里面的信息进行比较，一致则绑定成功）
	public function bind()
	{
		if(!$this->input['member_id'])
		{
			$this->errorOutput('没有会员id');
		}

		if(!$this->input['market_id'])
		{
			$this->errorOutput('没有超市id');
		}

		if(!$this->input['card_number'])
		{
			$this->errorOutput('没有卡号');
		}
		
		if(!$this->input['phone_number'])
		{
			$this->errorOutput('没有手机号');
		}

		$member_data = array(
			'phone_number' 	=> $this->input['phone_number'],
			'card_number' 	=> $this->input['card_number'],
			'market_id' 	=> $this->input['market_id'],
		);
		$isSuccess = $this->mode->bind($member_data,$this->input['member_id']);
		$this->addItem(array('return' => $isSuccess));
		$this->output();
	}
	
	//判断有没有绑定会员(需要制定是哪个超市的会员)
	public function isBind()
	{
		if(!$this->input['member_id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['market_id'])
		{
			$this->errorOutput('没有超市id');
		}

		//通过用户中心的会员id判断有没有绑定超时里面的会员
		$ret = $this->mode->isBind($this->input['member_id'],$this->input['market_id']);
		if($ret)
		{
			$this->addItem($ret);
		}
		else 
		{
			$this->addItem(array('return' => false));
		}
		$this->output();
	}
	
	//导入会员数据（小数据量）
	public function importMemberData()
	{
		$market_id = $this->input['market_id'];
		if(!$market_id)
		{
			$this->errorOutput(NOID);
		}
		
		//首先将上传上来的excel文件放到data目录
		if(!$_FILES['excelfile']['tmp_name'])
		{
			$this->errorOutput(NO_FILE);
		}
				
		$original 	= urldecode($_FILES['excelfile']['name']);
		$filetype 	= strtolower(strrchr($original, '.'));
		
		if(!in_array($filetype,array('.xlsx','.xls')))
		{
			$this->errorOutput('此文件格式不支持');
		}
		
		$name = date('Y-m-d',TIMENOW) . '-' . TIMENOW . hg_rand_num(6);
		$filename = $name . $filetype;
		$filepath = DATA_DIR . 'excel/';
		
		if (!hg_mkdir($filepath) || !is_writeable($filepath))
		{
			$this->errorOutput(NOWRITE);
		}
		
		if (!@move_uploaded_file($_FILES['excelfile']['tmp_name'], $filepath . $filename))
		{
			$this->errorOutput(FAIL_MOVE);
		}
		
		//上传成功之后就初始化数据,将excel数据读入缓存文件中
		$PHPExcelInfo = new PHPExcelInfo($filepath . $filename);
		$memberInfo = $PHPExcelInfo->getData();
		if($memberInfo)
		{
			foreach($memberInfo AS $k => $v)
			{
				//验证卡号
				if(!$v[0])
				{
					continue;
				}
				else if($this->mode->isExistsMember(" card_number = '" .$v[0]. "' AND market_id = '" .$market_id. "' "))
				{
					continue;
				}
				
				//名称
				if(!$v[1])
				{
					continue;
				}
				
				//验证生日
				if(!$v[2])
				{
					continue;
				}
				
				//验证手机号
				if(!$v[3])
				{
					continue;
				}
				
				//根据身份证号得到出生日期以及年龄并且保存起来
				$idCardInfo = new IdCard();
				$birthday = date('Y-m-d',strtotime($v[2]));
				$age = $idCardInfo->getAge($birthday);
				$month = intval(date('m',strtotime($birthday)));
				$day   = intval(date('d',strtotime($birthday)));
				$constellation_id = $idCardInfo->getConstellation($birthday);
	
				$data = array(
						'card_number' 		=> $v[0],
						'name' 				=> $v[1],
						'phone_number' 		=> $v[3],
						'age' 				=> $age,
						'month' 			=> $month,
						'day' 				=> $day,
						'birthday'			=> $birthday,
						'constellation_id' 	=> $constellation_id,
						'market_id' 		=> $market_id,
						'user_id' 			=> $this->user['user_id'],
						'user_name' 		=> $this->user['user_name'],
						'update_user_id' 	=> $this->user['user_id'],
						'update_user_name' 	=> $this->user['user_name'],
						'create_time' 		=> TIMENOW,
						'update_time' 		=> TIMENOW,
						'ip' 				=> hg_getip(),
				);
				$this->mode->create($data);
			}
		}
		$this->addItem('success');
		$this->output();
	}

	//导入会员数据
	public function importMemberData2()
	{
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}
		
		//首先将上传上来的excel文件放到data目录
		if(!$_FILES['excelfile']['tmp_name'])
		{
			$this->errorOutput(NO_FILE);
		}
				
		$original 	= urldecode($_FILES['excelfile']['name']);
		$filetype 	= strtolower(strrchr($original, '.'));
		
		if(!in_array($filetype,array('.xlsx','.xls')))
		{
			$this->errorOutput('此文件格式不支持');
		}
		
		$name = date('Y-m-d',TIMENOW) . '-' . TIMENOW . hg_rand_num(6);
		$filename = $name . $filetype;
		$filepath = DATA_DIR . 'excel/';
		
		if (!hg_mkdir($filepath) || !is_writeable($filepath))
		{
			$this->errorOutput(NOWRITE);
		}
		
		if (!@move_uploaded_file($_FILES['excelfile']['tmp_name'], $filepath . $filename))
		{
			$this->errorOutput(FAIL_MOVE);
		}
		
		//上传成功之后就初始化数据,将excel数据读入缓存文件中
		$PHPExcelInfo = new PHPExcelInfo($filepath . $filename);
		$memberInfo = $PHPExcelInfo->getData();
		$dataPath = $filepath . $name .  '.json';
		file_put_contents($dataPath,json_encode($memberInfo));
		if($memberInfo)
		{
			@unlink($filepath . $filename);
			$taskPath = DATA_DIR . 'excel/task.k';
			if(file_exists($taskPath))
			{
				$task = file_get_contents($taskPath);
				$task = unserialize($task);
			}
			else 
			{
				$task = array();
			}
			
			$task[] = array(
					'filename' => $dataPath,
					'market_id'=> $this->input['market_id'],
					'name'	   => $original,
			);
			
			file_put_contents($taskPath, serialize($task));
			$this->addItem('success');
			$this->output();
		}
	}
	
	//创建条形码并且提交到图片服务器(卡号，条形码图片存放的目录,生成之后图片的访问链接)
	private function createBarCode($card_number = '',$img_dir = '',$img_url = '')
	{
		if(!$img_dir || !$card_number || !$img_url)
		{
			return false;
		}
		
		if(!$this->barcode->create($card_number,$img_dir))
		{
			return false;
		}
		
		$img_info = $this->material->localMaterial($img_url);
		if($img_info && $img_info[0] && $img_info[0]['id'])
		{
			$img_info = $img_info[0];
			$img_info = array(
				'host'     => $img_info['host'],
				'dir'      => $img_info['dir'],
				'filepath' => $img_info['filepath'],
				'filename' => $img_info['filename'],
				'imgwidth' => $img_info['imgwidth'],
				'imgheight'=> $img_info['imgheight'],
			);
			@unlink($img_dir);
			return $img_info;
		}
		return false;
	}
	
	//给制定会员推送消息
	public function pushMessageToMember()
	{
		if(!$this->input['member_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$data = array(
			'title' 			=> $this->input['memberinfo'],
			'content' 			=> $this->input['memberinfo'],
			'member_id' 		=> $this->input['member_id'],
			'market_id' 		=> $this->input['market_id'],
			'scope' 			=> 3,
			'status'			=> 2,
			'expire_time' 		=> TIMENOW + 24 * 3600,
			'user_id' 			=> $this->user['user_id'],	
			'user_name' 		=> $this->user['user_name'],	
			'org_id' 			=> $this->user['org_id'],	
			'update_user_id' 	=> $this->user['user_id'],	
			'update_user_name' 	=> $this->user['user_name'],	
			'ip' 				=> hg_getip(),	
			'create_time' 		=> TIMENOW,	
			'update_time' 		=> TIMENOW,
		);
		
		$ret = $this->mode->pushMessageToMember($data);
		if($ret)
		{
			$this->addItem('sucess');
			$this->output();
		}
	}
	
	//解绑定
	public function unbind()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->unbind($this->input['id']);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}

	/***************************************扩展操作*****************************************/
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new market_member_update();
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