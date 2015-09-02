<?php
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel.php');
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel/IOFactory.php');
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel/Reader/Excel5.php');
require_once (CUR_CONF_PATH.'lib/carpark_mode.php');
class excel extends InitFrm
{
	private $user = array();
	public function __construct()
	{
		parent::__construct();
		$this->phpexcel	=	new PHPExcel();
		$this->obj = new carpark_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($uploadfile,$filetempname,$user)
	{
		//move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
		$result=move_uploaded_file($filetempname,$uploadfile);//假如上传到当前目录下
		if($result) //如果上传文件成功，就执行导入excel操作
		{ 	
			if($user)
			{
				$this->user = $user;
			}
			$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
			$objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数
			
			//循环读取excel文件,读取一条,插入一条
			for($j=2;$j<=$highestRow;$j++)
			{
				$str = "";
				for($k='A';$k<=$highestColumn;$k++)
				{
					$str .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//读取单元格
				}
				
				if($str)
				{
					$this->insert_carpark($str);
				}
				else 
				{
					continue;
				}
			}
			
			unlink($uploadfile); //删除上传的excel文件
			$msg = TRUE;
		}
		else
		{
			$msg = FALSE;
		}
		return $msg;
	}
	
	public function insert_carpark($str)
	{
		if(!$str)
		{
			continue;
		}
		
		//explode:函数把字符串分割为数组。
		if(stripos($str, ' ')!== false)
		{
			$str = $this->trimall($str);
		}
		
		$arr = array();
		$arr = explode("\\",trim($str,"\\"));
		if(empty($arr))
		{
			continue;
		}
		
		//file_put_contents('44.txt', var_export($arr,1));exit();
		/*$arr = array(
		  	0 => '010202',//地点编号
		  	1 => '紫阳路2',//名称
		  	2 => '一级区域',//类型
		  	3 => '机床新村27#旁',//地址
		  	//4 => '120.278897',//经度
		  	5 => '100',//停车位总数
		  	//6 => '120.278897',//百度坐标
		  	7 => '31',//空位数
		  	8 => '120.278897',//百度x坐标
		  	9 => '31.278897',//百度y坐标
			
		);*/
		
		$data = array();
		
		//编号
		if($arr[0])
		{
			$data['parking_num'] = $arr[0];
		}
		//名称
		if($arr[1])
		{
			$data['name'] = $arr[1];
		}
		
		//站点类型
		if($arr[2])
		{
			$sql = "SELECT id FROM " . DB_PREFIX . "carpark_type WHERE name = '" . $arr['2'] . "'";
			$type = $this->db->query_first($sql);
			if($type['id'])
			{
				$data['type_id'] = $type['id'];
			}
		}
		
		//地址
		if($arr[3])
		{
			$data['address'] = $arr[3];
		}
		
		//停车位
		if($arr[5])
		{
			$data['parking_space'] = $arr[5];
		}
		
		//空位
		if($arr[7])
		{
			$data['empty_space'] = $arr[7];
		}
		
		//处理坐标
		if($arr[8])
		{
			$data['baidu_longitude'] = $arr[8];
		}
		if($arr[9])
		{
			$data['baidu_latitude'] = $arr[9];
		}
		
		//处理简介
		if($arr[10])
		{
			$data['description'] = $arr[10];
		}
		//入库
		if(empty($data))
		{
			continue;
		}

		$data['create_time'] = TIMENOW;
		$data['org_id']		 = $this->user['org_id'];	
		$data['user_id']	 = $this->user['user_id'];	
		$data['user_name']	 = $this->user['user_name'];	
		$data['ip']			 = $this->user['ip'];
		
		$res = $this->obj->create($data);
		if(!$res)
		{
			continue;
		}
	}
	
	//删除空格
	function trimall($str)
	{
   		$qian=array(" ","　","\t","\n","\r");
    	$hou=array("","","","","");
    	return str_replace($qian,$hou,$str);   
	}
}

?>