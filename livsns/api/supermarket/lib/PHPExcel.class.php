<?php

/*
 *读取excel里面的数据并且转换为数组
 * 
 */
require_once(CUR_CONF_PATH . 'lib/PHPExcel/PHPExcel.php');
class PHPExcelInfo
{
	private $PHPExcel;
	private $data = array();
	public function __construct($filePath = '')
	{
		//如果存在就实例化读取对象
		if (file_exists($filePath))
		{
			$this->PHPExcel = PHPExcel_IOFactory::load($filePath);
		}
	}
	
	//获取数据
	public function getData()
	{
		if($this->PHPExcel)
		{
			$sheet_count = $this->PHPExcel->getSheetCount();
			for ($s = 0; $s < $sheet_count; $s++)
			{
			    $currentSheet = $this->PHPExcel->getSheet($s);//当前页
			    $row_num = $currentSheet->getHighestRow();//当前页行数
			    $col_max = $currentSheet->getHighestColumn();//当前页最大列号
			    for($i = 2; $i <= $row_num; $i++) 
			    {
			    	$row_data = array();
			        for($j = 'A'; $j <= $col_max; $j++)
			        {
			            $address = $j . $i; //单元格坐标
			            $row_data[] = $currentSheet->getCell($address)->getFormattedValue();
			        }
			        $this->data[] = $row_data;
			    }
			}
		}
		return  $this->data;
	}
}