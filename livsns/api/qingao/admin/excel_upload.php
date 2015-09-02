<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: excel_upload.php 6548 2012-04-25 06:49:25Z lijiaying $
***************************************************************************/
require('global.php');
class excelUploadApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 验证文件
	 */
	public function uploadXlsApi()
	{
		if (urldecode($this->input['type']) != 'application/vnd.ms-excel')
		{
			$this->errorOutput('文件格式不正确，请上传excel格式的文件');
		}
		
		$file = array(
			'name' => urldecode($this->input['name']),
			'type' => urldecode($this->input['type']),
			'tmp_name' => urldecode($this->input['tmp_name']),
			'error' => $this->input['error'],
			'size' => $this->input['size']
		);
			
		if($this->input['leadExcel'] == "true")
		{
			$filename = $file['name'];
			$tmp_name = $file['tmp_name'];
			
			$msg = $this->uploadFile($filename,$tmp_name);

			$this->addItem($msg);
			$this->output();
		}
	}
	

	/**
	 * 导入Excel文件
	 */
	function uploadFile($file,$filetempname) 
	{
		//设置的上传文件存放路径
		$filePath = QINGAO_XLS_DIR;

		require_once './lib/PHPExcel.php';
		require_once './lib/PHPExcel/IOFactory.php';
		require_once './lib/PHPExcel/Reader/Excel5.php';

		//注意设置时区
		$time=date("YmdHis");
		//获取上传文件的扩展名
		$extend=strrchr ($file,'.');
		//上传后的文件名
		$name = $time . $extend;
		$uploadfile = $filePath . $name;//上传后的文件名地址 
		
		$result = copy($filetempname,$uploadfile);
	
		if($result) //如果上传文件成功，就执行导入excel操作
		{
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($uploadfile); 
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow();           //取得总行数 
			$highestColumn = $sheet->getHighestColumn(); //取得总列数

			//循环读取excel文件,读取一条,插入一条
			$data = array();
			for ($j = 3;$j < $highestRow-1;$j++)                        //从第三行开始读取数据
			{ 
				$k_v = urldecode($objPHPExcel->getActiveSheet()->getCell("A$j")->getValue());		//院校编号
				$data[$k_v] = urldecode($objPHPExcel->getActiveSheet()->getCell("B$j")->getValue());//读取单元格 院校名
			}
			
			$title = urldecode($objPHPExcel->getActiveSheet()->getCell("A1")->getValue());
			$num = substr($title,15,2);
			$aname = substr($title,18);
			
			if ($num)
			{
				$sql_a = "INSERT INTO " . DB_PREFIX . "area SET aid = '" . $num . "', aname = '" . $aname . "'";
			
				if ($this->db->query($sql_a) && is_array($data))
				{
					foreach ($data AS $k => $v)
					{
						$sql = "INSERT INTO " . DB_PREFIX . "college SET aid = '" . $num . "', cid = '" . $k . "', cname = '" . $v . "'";
						$this->db->query($sql);
					}
				}
				else
				{
					$sql = "DELETE " . DB_PREFIX . "area WHERE aid = '" . $num . "'";
					$this->db->query($sql);
				}
			}
			
			unlink($uploadfile); //删除上传的excel文件
			$msg = array('filename' => $file, 'msg' => '导入成功！');
		}
		else
		{
			$msg = array('filename' => $file, 'msg' => '导入失败！');
		}
		
		return $msg;
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}

}

$out = new excelUploadApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>