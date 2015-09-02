<?php
define('MOD_UNIQUEID','excel_download');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class excel_download extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function download()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		$data = $this->mode->show();
		if($data)
		{
			if(!class_exists('PHPExcel'))
			{
				include_once(CUR_CONF_PATH . 'lib/PHPExcel/PHPExcel.php');
			}
			
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
										 ->setLastModifiedBy("Maarten Balliauw")
										 ->setTitle("Office 2007 XLSX Test Document")
										 ->setSubject("Office 2007 XLSX Test Document")
										 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
										 ->setKeywords("office 2007 openxml php")
										 ->setCategory("Test result file");
	
			//设置标题
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', '姓名')
			            ->setCellValue('B1', '单位')
			            ->setCellValue('C1', '职务')
			            ->setCellValue('D1', '手机号')
			            ->setCellValue('E1', '邮箱')
			            ->setCellValue('F1', '嘉宾身份');
										 
			for($i = 0,$n = 2;$i< count($data);$i++,$n++)
			{
			    //加入数据
			    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A' . $n, $data[$i]['name'])
			            ->setCellValue('B' . $n, $data[$i]['company'])
			            ->setCellValue('C' . $n, $data[$i]['job'])
			            ->setCellValue('D' . $n, $data[$i]['telephone'])
			            ->setCellValue('E' . $n, $data[$i]['email'])
			            ->setCellValue('F' . $n, $data[$i]['guest_type_text']);
			}
           
			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('会议嘉宾');
			
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="会议嘉宾.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
		}
		else
		{
			$this->errorOutput('NO_DATA');
		}
	}
}

$out = new excel_download();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'download';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>