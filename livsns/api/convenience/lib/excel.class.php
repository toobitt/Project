<?php
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel.php');
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel/IOFactory.php');
require_once (CUR_CONF_PATH.'lib/excel/Classes/PHPExcel/Reader/Excel5.php');
class excel extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->phpexcel	=	new PHPExcel();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($uploadfile,$filetempname,$type = 0)
	{
		 $str = "";
		 //move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
		 $result=move_uploaded_file($filetempname,$uploadfile);//假如上传到当前目录下
		if($result) //如果上传文件成功，就执行导入excel操作
		{ 	
			$strtotimes=strtotime(date('Ymd'));
			$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
			$objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数
			//循环读取excel文件,读取一条,插入一条
			for($j=4;$j<=$highestRow;$j++)
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . 'bus_query (departDate,departTime,departStation,arriveStation,terminalStation,takeTime,startStation,fullPrice,halfPrice,mileages,arriveTime,create_time,type) VALUES ';
				for($k='C';$k<=$highestColumn;$k++)
				{
					$str .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//读取单元格
				}
				//explode:函数把字符串分割为数组。
				if(stripos($str, ' ')!== false)
				{
				$str = $this->trimall($str);
				}
				$strs = explode("\\",trim($str,"\\"));
				$strs[2]=$strs[2]?$strs[2]:$strs[1];//如果下客站为空则终点站为下客站

				if(stripos($strs[3], '|')!== false)
				{
				$times=explode("|",trim($strs[3]));
				foreach ($times as $timev)
				{
				$strs[3]=strtotime($timev);
				$strs[6]=$strs[6]?$strs[6]:0;//途时
				$strs[7]=$strs[3]+$strs[6]*3600;//到达时间
				$strs[8]=$strs[4]/2; //半价票
				$sql.=$this->excelsql($strs,$strtotimes);
				}
				}
				elseif(stripos($strs[3], '-')!== false)
				{
				$tmp=$strs[3];
				$times=explode("-",trim($tmp));
				$time_a=strtotime($times[0]);
				$time_b=strtotime($times[1]);
				$time_c=$times[2]*60;
				do
				{
				$strs[3]=$time_a;
				$strs[6]=$strs[6]?$strs[6]:0;//途时
				$strs[7]=$time_a+$strs[6]*3600;//到达时间
				$strs[8]=$strs[4]/2; //半价票
				$sql.=$this->excelsql($strs,$strtotimes);
				$time_a +=$time_c;
				}
				while($time_a<=$time_b);
				unset($tmp);
				}
				else
				{
				$strs[3]=strtotime($strs[3]);
				$strs[6]=$strs[6]?$strs[6]:0;//途时
				$strs[7]=$strs[3]+$strs[6]*3600;//到达时间
				$strs[8]=$strs[4]/2; //半价票
				$sql.=$this->excelsql($strs,$strtotimes,$type);
				}

				$sql = rtrim($sql,',');
				
				if(!$this->db->query($sql))
				{
					return false;
				}
				$str="";
				$sql="";
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
	function excelsql($strs,$strtotimes,$type = 0)
	{
		$sql ='('.$strtotimes.','.$strs[3].',\''.$strs[0].'\','."'$strs[1]'".','."'$strs[2]'".','.$strs[6].",'".$strs[0]."',".$strs[4].','.$strs[8].','.$strs[5].','.$strs[7].','.TIMENOW.','.$type.'),';
		return $sql;
	}
	function trimall($str)//删除空格
	{
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str);   
	}
	

}

?>