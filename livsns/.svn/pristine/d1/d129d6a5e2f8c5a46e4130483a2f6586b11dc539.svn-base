<?php
ini_set("memory_limit","-1");
define ( 'MOD_UNIQUEID', 'pay_order' );
require ('./global.php');
require CUR_CONF_PATH . 'core/Core.class.php';
require CUR_CONF_PATH . 'lib/PHPExcel/PHPExcel.php';
class ExportAPI extends adminReadBase {
	private $obj = null;
	private $tbname = 'order';
	private $stateconfig = array ();
	public function __construct() {
		parent::__construct ();
		$this->obj = new Core ();
		$this->stateconfig = $this->settings ['order_status'];
		$this->trace_stepconfig = $this->settings ['trace_step'];
	}

	public function detail(){
		
	}
	
	public function show() {
		$condition = $this->get_condition ();
		$data_limit = $condition . ' order by o.id ';
		$query = "SELECT 
                  o.*,
                  contact.email as contact_email,
                  contact.phone as contact_phone,
                  contact.address as contact_address,
                  contact.telphone as contact_telphone,
                  contact.contact_name as nameofcontact,
                  consignee.email as consignee_email,
                  consignee.phone as consignee_phone,
                  consignee.address as consignee_address,
                  consignee.telephone as consignee_telphone,
                  consignee.consignee_name as nameofconsignee
                  FROM " . DB_PREFIX . "order o
                  LEFT JOIN " . DB_PREFIX . "order_consignee consignee 
                  ON o.id=consignee.order_id 
                  LEFT JOIN " . DB_PREFIX . "order_contact contact 
                  ON o.id=contact.order_id ";
		$q = $this -> db -> query($query. $data_limit);
		$info = array();
		if (PHP_SAPI == 'cli')
			die('This programming should only be run from a Web Browser');
		
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		$cacheSettings =array('memoryCacheSize'=>'160MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($this->user['user_name'])
		->setLastModifiedBy($this->user['user_name'])
		->setTitle("Office 2007 XLSX Order export")
		->setSubject("Office 2007 XLSX Order export")
		->setDescription("Order export")
		->setKeywords("Order ")
		->setCategory("Order ");
		
		$columns = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","T","U","V","W","X","Y","Z");
		//增加表头
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', '订单编号')
		->setCellValue('B1', '下单人')
		->setCellValue('C1', '收货人')
		->setCellValue('D1', '联系人信息')
		->setCellValue('E1', '下单时间')
		->setCellValue('F1', '金额')
		->setCellValue('G1', '数量')
		->setCellValue('H1', '订单内容')
		->setCellValue('I1', '发票抬头')
		->setCellValue('J1', '支付状态')
		->setCellValue('K1', '是否需要配送')
		;
		$i=2;
		/*
		 * 增加了一次循环
		foreach ( $datas as $k => $v ) {
			$v ['is_cancel_title'] = $this->stateconfig ['is_cancel'] [$v ['is_cancel']];
			$v ['is_completed_title'] = $this->stateconfig ['is_completed'] [$v ['is_completed']];
			$v ['is_comment_title'] = $this->stateconfig ['is_comment'] [$v ['is_comment']];
			$v ['pay_status_title'] = $this->stateconfig ['pay_status'] [$v ['pay_status']];
			$v ['order_status_title'] = $this->stateconfig ['order_status'] [$v ['order_status']];
			$v ['submit_time'] = date ( "Y-m-d H:i", $row ['submit_time'] );
			$v ['pay_start_time'] = date ( "Y-m-d H:i", $row ['pay_start_time'] );
			$v ['pay_end_time'] = date ( "Y-m-d H:i", $row ['pay_end_time'] );
			$v ['goods_out_time'] = date ( "Y-m-d H:i", $row ['goods_out_time'] );
			$v ['goods_wait_time'] = date ( "Y-m-d H:i", $row ['goods_wait_time'] );
			$v ['completed_time'] = date ( "Y-m-d H:i", $row ['completed_time'] );
			$v ['delivery_tracing_title'] = $this->trace_stepconfig [$v ['delivery_tracing']];
			
			if($v['nameofcontact']){
				$contactor = $v['nameofcontact'];
				$phone = $v['contact_telphone'];
				$address = $v['contact_address'];
			}else{
				$contactor = $v['nameofconsignee'];
				$phone = $v['consignee_telphone'];
				$address = $v['consignee_address'];
			}
			// Miscellaneous glyphs, UTF-8
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $v['order_id'])
			->setCellValue('B'.$i, $v['user_name'])
			->setCellValue('C'.$i, $contactor)
			->setCellValue('D'.$i, '电话:'.$phone.'地址:'.$address)
			->setCellValue('E'.$i, $v ['create_time'])
			->setCellValue('F'.$i, '商品金额:'.$v['goods_value'].'运费:'.$v['delivery_fee'])
			->setCellValue('G'.$i, $v['order_quantity'])
			->setCellValue('H'.$i, $v['title'])
			->setCellValue('I'.$i, $v['bill_header_content'])
			->setCellValue('J'.$i, $v ['pay_status_title'])
			->setCellValue('K'.$i, $v ['delivery_tracing_title']);
			$i++;
		}
		*/
		while (($v = $this -> db -> fetch_array($q)) != false){
			$v ['is_cancel_title'] = $this->stateconfig ['is_cancel'] [$v ['is_cancel']];
			$v ['is_completed_title'] = $this->stateconfig ['is_completed'] [$v ['is_completed']];
			$v ['is_comment_title'] = $this->stateconfig ['is_comment'] [$v ['is_comment']];
			$v ['pay_status_title'] = $this->stateconfig ['pay_status'] [$v ['pay_status']];
			$v ['order_status_title'] = $this->stateconfig ['order_status'] [$v ['order_status']];
			$v ['submit_time'] = date ( "Y-m-d H:i", $row ['submit_time'] );
			$v ['pay_start_time'] = date ( "Y-m-d H:i", $row ['pay_start_time'] );
			$v ['pay_end_time'] = date ( "Y-m-d H:i", $row ['pay_end_time'] );
			$v ['goods_out_time'] = date ( "Y-m-d H:i", $row ['goods_out_time'] );
			$v ['goods_wait_time'] = date ( "Y-m-d H:i", $row ['goods_wait_time'] );
			$v ['completed_time'] = date ( "Y-m-d H:i", $row ['completed_time'] );
			$v ['delivery_tracing_title'] = $this->trace_stepconfig [$v ['delivery_tracing']];
				
			if($v['nameofcontact']){
				$contactor = $v['nameofcontact'];
				$phone = $v['contact_telphone'];
				$address = $v['contact_address'];
			}else{
				$contactor = $v['nameofconsignee'];
				$phone = $v['consignee_telphone'];
				$address = $v['consignee_address'];
			}
			// Miscellaneous glyphs, 
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $v['order_id'])
			->setCellValue('B'.$i, $v['user_name'])
			->setCellValue('C'.$i, $contactor)
			->setCellValue('D'.$i, '电话:'.$phone.'地址:'.$address)
			->setCellValue('E'.$i, date ( "Y-m-d H:i",$v ['create_time']))
			->setCellValue('F'.$i, '商品金额:'.$v['goods_value'].'运费:'.$v['delivery_fee'])
			->setCellValue('G'.$i, $v['order_quantity'])
			->setCellValue('H'.$i, $v['title'])
			->setCellValue('I'.$i, $v['bill_header_content'])
			->setCellValue('J'.$i, $v ['pay_status_title'])
			->setCellValue('K'.$i, $v ['delivery_tracing_title']);
			$i++;
		}
		$this->db->close();
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('order');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="order export.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: '.gmdate('D, d M Y H:i:s').' GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	public function count() {
		$condition = $this->get_condition ();
		$info = $this->obj->count ( $this->tbname . " o", $condition );
		echo json_encode ( $info );
	}
	public function index() {
		
	}
	
	private function get_condition() {
		$condition = " where 1 ";
		/***** 权限 *****/
		$this->verify_content_prms(array('_action'=>'manage'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//操作他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND o.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//组织内
			{
				$condition .= ' AND o.org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***** 权限 *****/
		if (isset ( $this->input ['title'] ) && $this->input ['title']) {
			$condition .= " and o.title like '%" . $this->input ['title'] . "%' ";
		}
		
		/**
		 * 支付状态
		 */
		if (isset ( $this->input ['pay_status'] ) && ($this->input ['pay_status'] > 0)) {
			$condition .= " and o.pay_status=" . $this->input ['pay_status'];
		}
		
		/**
		 * 支付状态
		 */
		if (isset ( $this->input ['session'] ) && $this->input ['session']) {
			$condition .= " and gev.session=" . $this->input ['session']; // goodsextensionvalue
		}
		
		/**
		 * 配送跟踪
		 */
		if (isset ( $this->input ['trace_step'] ) && ($this->input ['trace_step'] > 0)) {
			$condition .= " and o.delivery_tracing=" . $this->input ['trace_step'];
		}
		
		// 查询发布的时间
		if ($this->input ['date_search']) {
			$today = strtotime ( date ( 'Y-m-d' ) );
			$tomorrow = strtotime ( date ( 'Y-m-d', TIMENOW + 24 * 3600 ) );
			switch (intval ( $this->input ['date_search'] )) {
				case 1 : // 所有时间段
					break;
				case 2 : // 昨天的数据
					$yesterday = strtotime ( date ( 'y-m-d', TIMENOW - 24 * 3600 ) );
					$condition .= " AND  o.create_time > '" . $yesterday . "' AND o.create_time < '" . $today . "'";
					break;
				case 3 : // 今天的数据
					$condition .= " AND  o.create_time > '" . $today . "' AND o.create_time < '" . $tomorrow . "'";
					break;
				case 4 : // 最近3天
					$last_threeday = strtotime ( date ( 'y-m-d', TIMENOW - 2 * 24 * 3600 ) );
					$condition .= " AND  o.create_time > '" . $last_threeday . "' AND o.create_time < '" . $tomorrow . "'";
					break;
				case 5 : // 最近7天
					$last_sevenday = strtotime ( date ( 'y-m-d', TIMENOW - 6 * 24 * 3600 ) );
					$condition .= " AND  o.create_time > '" . $last_sevenday . "' AND o.create_time < '" . $tomorrow . "'";
					break;
				default : // 所有时间段
				        if ($this->input['start_time'] == $this->input['end_time']) 
				        {
							$his = date('His', strtotime($this->input['start_time']));
							if (! intval($his)) 
							{
								$this->input['start_time'] = date('Y-m-d', strtotime($this->input['start_time'])). ' 00:00';
								$this->input['end_time'] = date('Y-m-d', strtotime($this->input['end_time'])). ' 23:59';
							}
       				 }
					//查询创建的起始时间
					if($this->input['start_time'])
					{
						$start_time = strtotime($this->input['start_time']);
						$condition .= " AND o.create_time >= " . $start_time;
					}
			
					//查询创建的结束时间
					if($this->input['end_time'])
					{
						$end_time = strtotime(date('Y-m-d', strtotime($this->input['end_time'])). ' 23:59:59');
						$condition .= " AND o.create_time <= " . $end_time;
						$start_time > $end_time && $this->errorOutput('搜索开始时间不能大于结束时间');
					}
					break;
			}
		}
		return $condition;
	}
	public function unknow() {
		$this->errorOutput ( NO_ACTION );
	}
}

$out = new ExportAPI ();
$action = $_INPUT ['a'];
if (! method_exists ( $out, $action )) {
	$action = 'unknow';
}
$out->$action ();
?>