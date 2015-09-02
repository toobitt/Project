<?php
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel.php');
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel/IOFactory.php');
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel/Reader/Excel5.php');
require_once (CUR_CONF_PATH.'lib/lbs.class.php');
require_once (CUR_CONF_PATH.'core/lbs.core.php');
class excel extends InitFrm
{
	private $user = array();
	public function __construct()
	{
		parent::__construct();
		$this->phpexcel	=	new PHPExcel();
		$this->lbs = new ClassLBS();
		$this->lbs_field = new lbs_field();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($uploadfile,$filetempname,$user,$sort_id='')
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
			
			if($sort_id)
			{
				$sql = "SELECT id FROM " . DB_PREFIX . "lbs WHERE sort_id = {$sort_id}";
				$q = $this->db->query($sql);
				
				$id_arr = array();
				while ($r = $this->db->fetch_array($q))
				{
					$id_arr[] = $r['id'];
				}
				if(!empty($id_arr))
				{
					$ids = implode(',', $id_arr);
				}
				
				if($ids)
				{
					$this->lbs->delete($ids);
					$this->lbs_field->field_contentdelete($ids);//删除附加信息内容
				}
			}
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
					$this->insert_lbs($str,$sort_id);
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
	
	
	function trimall($str)//删除空格
	{
   		$qian=array(" ","　","\t","\n","\r");
    	$hou=array("","","","","");
    	return str_replace($qian,$hou,$str);   
	}
	
	public function insert_lbs($str,$sort_id='')
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
		  	0 => '机床1#',//名称
		  	1 => '机床新村27#旁',//地址
		  	2 => '120.278897',//经度
		  	3 => '31.56376533',//纬度
		  	4 => '120.278897',//百度坐标
		  	5 => '31.56376533',
		  	6 => '江苏省',//省
		  	7 => '无锡市',//城市
		  	8 => '滨湖区',//区
		  	9 => '公共设施',//分类
		  	10 => '客服：025-89809090|售前：025-87898009',//联系电话
		  	11 => '9:00-18:00',//营业时间
		  	12 => 'http://g.hiphotos.baidu.com/image/pic/item/08f790529822720eb25fa86479cb0a46f31fab9f.jpg',//索引图
			13 => '简介',//简介
			14 => 'HJSWUXBHQ00001',//地点编号
		);*/
		
		
		$data = array();
		
		if($arr[14])
		{
			$data['local_id'] = $arr[14];
			
			//判断数据是否导入过
			/*$sql = "SELECT local_id FROM " . DB_PREFIX . "lbs WHERE local_id = '" . $data['local_id'] . "'";
			$res = $this->db->query_first($sql);
			
			if($res['local_id'])
			{
				continue;
			}*/
		}
		if($arr[0])
		{
			$data['title'] = $arr[0];
		}
		if($arr[1])
		{
			$data['address'] = $arr[1];
		}
		if($arr[2])
		{
			$data['GPS_longitude'] = $arr[2];
		}
		if($arr[3])
		{
			$data['GPS_latitude'] = $arr[3];
		}
		if($arr[4])
		{
			$data['baidu_longitude'] = $arr[4];
		}
		if($arr[5])
		{
			$data['baidu_latitude'] = $arr[5];
		}
		//处理省市区
		if($arr[6])
		{
			$sql = "SELECT id FROM " . DB_PREFIX . "province WHERE name LIKE '%" . $arr[6] . "%'";
			$res = $this->db->query_first($sql);
			
			if($res['id'])
			{
				$data['province_id'] = $res['id'];
			}
			
			if($data['province_id'] && $arr[7])
			{
				$sql = "SELECT id FROM " . DB_PREFIX . "city WHERE province_id = " . $data['province_id'] . " AND city LIKE '%" . $arr[7] . "%'";
				$city_res = $this->db->query_first($sql);
				
				if($city_res['id'])
				{
					$data['city_id'] = $city_res['id'];
				}
			}
			
			if($arr[8] && $data['city_id'])
			{
				$sql = "SELECT id FROM " . DB_PREFIX . "area WHERE city_id = " . $data['city_id'] . " AND area LIKE '%" . $arr[8] . "%'";
				$area_res = $this->db->query_first($sql);
				if($area_res['id'])
				{
					$data['area_id'] = $area_res['id'];
				}
			}
		}
		
		//处理分类
		if($sort_id)
		{
			$data['sort_id'] = $sort_id;
		}
		else if($arr[9])
		{
			$sql = "SELECT id FROM " . DB_PREFIX . "sort WHERE name = '" . $arr[9] . "'";
			$sort_res = $this->db->query_first($sql);
			if($sort_res['id'])
			{
				$data['sort_id'] = $sort_res['id'];
			}
		}
		
		//处理联系电话
		if($arr[10])
		{
			$tel_arr = array();
			if(stripos($arr[10], '|') !== false)
			{
				$tel = explode('|',trim($arr[10]));
				
				if (!empty($tel) && is_array($tel))
				{
					foreach ($tel as $key => $val)
					{
						if(stripos($val, ':') !== false)
						{
							$tmp = explode(':', $val);
						}
						else if(stripos($val, '：') !== false)
						{
							$tmp = explode('：', $val);
						}
						
						
						if(!empty($tmp))
						{
							$telname=$tmp[0]?$tmp[0]:'联系电话'.($key+1);
							$tel_arr[] = array('telname'=>$telname,'tel'=>$tmp[1]);
						}
					}
				}
				$data['tel'] = serialize($tel_arr);
			}
			else
			{
				if(stripos($arr[10], ':') !== false)
				{
					$tmp = explode(':', $arr[10]);
				}
				else if(stripos($arr[10], '：') !== false)
				{
					$tmp = explode('：', $arr[10]);
				}

				if(!empty($tmp))
				{
					$telname=$tmp[0]?$tmp[0]:'联系电话'.($key+1);
					$tel_arr[] = array('telname'=>$telname,'tel'=>$tmp[1]);
				}
				$data['tel'] = serialize($tel_arr);
			}
		}
		
		//处理经营时间
		if($arr[11])
		{
			if(stripos($arr[11], '-') !== false)
			{
				$jy_time = explode('-', $arr[11]);
				$ymd = date('Y-m-d',TIMENOW);
				
				$stime = strtotime($ymd . ' ' . $jy_time[0]);
				$etime = strtotime($ymd . ' ' . $jy_time[1]);
				
				$data['stime'] = $stime;
				$data['etime'] = $etime;
			}
		}
		
		//入库
		if(empty($data))
		{
			continue;
		}
		//如果百度坐标存在gps不存在的话，就转换为gps坐标也存起来
		if($data['baidu_longitude'] && $data['baidu_latitude'])
		{
			$gps = $this->lbs->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		//如果GPS坐标存在并且百度坐标不存在的话，就转换为百度坐标也存起来
		if(!$data['baidu_longitude'] && !$data['baidu_latitude'] && $data['GPS_longitude'] && $data['GPS_latitude'])
		{
			$baidu = array();
			//防止网络崩溃，暂时注释
			$baidu = $this->lbs->FromGpsToBaiduXY($data['GPS_longitude'],$data['GPS_latitude']);
			if(!empty($baidu))
			{
				$data['baidu_longitude'] = $baidu['x'];
				$data['baidu_latitude'] = $baidu['y'];
			}
		}
		
		$data['create_time'] = TIMENOW;
		$data['org_id']		 = $this->user['org_id'];	
		$data['user_id']	 = $this->user['user_id'];	
		$data['user_name']	 = $this->user['user_name'];	
		$data['ip']			 = $this->user['ip'];
		$lbs = $this->lbs->add_lbs($data);
		$id = $lbs['id'];
		
		if(!$id)
		{
			continue;
		}
		
		if($arr[12])
		{
			//对索引图的处理
			include_once(ROOT_PATH.'lib/class/material.class.php');
			$material_pic = new material();
			
			$img_info = array();
			
			$img_info = $material_pic->localMaterial($arr[12]);
			
			//图片本地化失败，重复请求3次
			/*if(empty($img_info))
			{
				for ($i=0;$i<3;$i++)
				{
					$img_info = $material_pic->localMaterial($arr[12]);
					if(!empty($img_info))
					{
						break;
					}
				}
			}*/
			if(!empty($img_info))
			{
				$img_info = $img_info[0];
				$img_data = array(
					'host' 			=> $img_info['host'],
					'dir' 			=> $img_info['dir'],
					'filepath' 		=> $img_info['filepath'],
					'filename' 		=> $img_info['filename'],
				);
	
				$img_data['cid'] 			= $id;//lbs的id,直接置零
				$img_data['original_id'] 	= $img_info['id'];
				$img_data['type'] 			= $img_info['type'];
				$img_data['mark'] 			= 'img';
				$img_data['imgwidth'] 		= $img_info['imgwidth'];
				$img_data['imgheight'] 		= $img_info['imgheight'];
				$img_data['flag']			= 1;
				$vid = $this->lbs->insert_img($img_data);
			}
			if($vid)
			{
				$sql = 'UPDATE '.DB_PREFIX.'lbs set indexpic = '.$vid.' WHERE id = '.$id;
				$this->db->query($sql);
			}
		}
		
		//简介描述
		if($arr[13])
		{
			$this->lbs->add_content($arr[13], $id);
		}
			
	}
}

?>