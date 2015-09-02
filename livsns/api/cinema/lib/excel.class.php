<?php
require_once (ROOT_PATH.'lib/excel/Classes/PHPExcel.php');
require_once (ROOT_PATH.'lib/excel/Classes/PHPExcel/IOFactory.php');
require_once (ROOT_PATH.'lib/excel/Classes/PHPExcel/Reader/Excel5.php');
require_once (CUR_CONF_PATH.'lib/project_list_mode.php');
class excel extends InitFrm
{
	private $user = array();
	public function __construct()
	{
		parent::__construct();
		$this->phpexcel	=	new PHPExcel();
		$this->obj = new project_list_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($uploadfile,$filetempname,$user)
	{
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
			$highestColumn = 'I'; //$sheet->getHighestColumn(); // 取得总列数
			
			//循环读取excel文件,一行一行的
			for($j=2;$j<=$highestRow;$j++)
			{
				$str = "";
				for($k='A';$k<=$highestColumn;$k++)
				{
					$str .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'|';//读取单元格
				}
				if($str)
				{
					$tmp = array();
					$tmp = explode('|', $str);
					$val = $tmp[3].'|'.$tmp[4].'|'.$tmp[5].'|'.$tmp[7].'|'.$tmp[8];
					$excel_arr[trim($tmp[0])][trim($tmp[1])][trim($tmp[2])][] = $val;
				}
				else 
				{
					continue;
				}
			}
			unlink($uploadfile); //删除上传的excel文件
			$ret = $excel_arr;
		}
		else
		{
			$ret = FALSE;
		}
		return $ret;
	}
	
	
	function trimall($str)//删除空格
	{
   		$qian=array(" ","　","\t","\n","\r");
	    	$hou=array("","","","","");
	    	return str_replace($qian,$hou,$str);   
	}
}

?>