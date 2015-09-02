<?php
if(in_array($_REQUEST['a'],array('import_data')))
{
	define('DB_SWITCH',TRUE);
}
require './global.php';
define ('MOD_UNIQUEID', 'data_manager');
class dataManagerUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/data_manager.class.php');
        $this->obj = new data_manager();
        $prms_data = array(
        	'_action' => 'manger',
        );
    	$this->verify_content_prms($prms_data);
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {	
    	$table_name = trim($this->input['table_name']) ? trim($this->input['table_name']) : '';
    	$primary_key = trim($this->input['primary_key']) ? trim($this->input['primary_key']) : '';
    	if(empty($table_name))
    	{
	    	$this->errorOutput(NO_TABLE_NAME);
    	}
    	$table_info = $this->obj->get_field($table_name);
    	if(!$table_info)
    	{
	    	$this->errorOutput(NO_TABLE);
    	}
    	$data = array();
    	foreach($table_info as $key => $value)
    	{
    		if($primary_key != $value)
    		{
    			$data[$value] = $this->input[$value];
    		}
    	}
    	
    	$ret = $this->obj->create($table_name,$data);
    	if($ret['error'])
    	{
    		$this->errorOutput($ret['error']);
	    	
    	}
    	$this->addItem($ret);
        $this->output();
    }
    public function update()
    {
	    $id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	if(empty($id))
    	{
	    	$this->errorOutput(NO_ID);
    	}	
    	if(empty($this->input['table_name']))
    	{
	    	$this->errorOutput(NO_TABLE_NAME);
    	}
    	$table_name = trim($this->input['table_name']);
    	$ret = $this->obj->get_field($table_name);
    	$sql = "UPDATE " . DB_PREFIX . $table_name ." SET ";
    	$cond = ' WHERE ';
    	$space = '';
    	if($this->input['primary_key'] && $ret)
    	{
	    	foreach($ret as $k => $v)
	    	{
		    	if($v == $this->input['primary_key'])
		    	{
		    		$cond .= $v . "='" . $this->input[$v] . "'";			    	
		    	}
		    	else
		    	{
		    		$sql .= $space . $v . "='" . $this->input[$v] . "'";			    	
			    	$space = ",";
		    	}
	    	}
	    	$sql .= $cond;
	    	$this->db->query($sql);
	    	file_put_contents(CACHE_DIR . 'sss',$sql);
	    	$affected_rows = $this->db->affected_rows();
	    	
	    	if($affected_rows)
	    	{
		    	$this->addItem(array($this->input['primary_key']=>$this->input[$this->input['primary_key']]));
		    	$this->output();
	    	}
	    	else
	    	{
		    	$this->addItem(array());
		    	$this->output();
	    	}
    	}
    	else
    	{
	    	$this->errorOutput(TABLE_FIELDS_IS_WRONG);
    	}
    }
    public function update_field()
    {
    	$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	if(empty($id))
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	if(empty($this->input['key']))
    	{
	    	$this->errorOutput(NO_KEY);
    	}
    	if(empty($this->input['key_value']))
    	{
	    	$this->errorOutput(NO_KEY_VALUE);
    	}    	
    	if(empty($this->input['table_name']))
    	{
	    	$this->errorOutput(NO_TABLE_NAME);
    	}
    	$data = array(
    		'table_name' => trim($this->input['table_name']),
    		'key' => trim($this->input['key']),
    		'key_value' => trim($this->input['key_value']),
       	);
    	
    	$ret = $this->obj->update_field($data,$id);
    	if($ret['error'])
    	{
    		$this->errorOutput($ret['error']);
	    	
    	}
        $this->addItem($ret);
        $this->output();
    }
        
    public function audit()
    {
       
    }
    
    public function delete()
    {
        $id = $this->input['id'] ? trim($this->input['id']) : '';
        if (!$id) {
            $this->errorOutput(NO_ID);
        }
        if(empty($this->input['table_name']))
        {
	        $this->errorOutput(NO_TABLE_NAME);
        }
        $ret = $this->obj->delete($id,trim($this->input['table_name']));
        $this->addItem($ret);
        $this->output();       
        
    }
    
    public function import_data()
    {
    	$is_empty = intval($this->input['is_empty']) ? 1 : 0;
    	$table_name = $this->input['table_name'] ? trim($this->input['table_name']) : '';
    	if(!$table_name)
    	{
	    	$this->errorOutput(NO_TABLE_NAME);
    	}
    	
    	$ret = array('success');
    	$file = $_FILES['import'];
		$type = substr($file['name'], strrpos($file['name'], '.')+1);
		//$type = 'txt';
    	$data = $this->file2array($file['tmp_name'],$type,$table_name);
    	//var_dump($data);exit;
    	if(isset($data['error']) && $data['error'])
    	{
	    	$this->errorOutput($data['error']);
    	}
    	if($is_empty)
    	{
	    	$ret = $this->obj->checkTableExists($table_name);
	    	if(isset($ret['error']) && $ret['error'])
	    	{
		    	$this->errorOutput($ret['error']);
	    	}
	    	$ret = $this->obj->add_truncate($table_name);
	    	if(isset($ret['error']) && $ret['error'])
	    	{
		    	$this->errorOutput($ret['error']);
	    	}
    	}
    	unset($ret);
    	if($type != 'txt')
    	{
	    	$ret = array();
	    	//hg_pre($data);exit;
	    	foreach($data as $k => $v)
	    	{
		    	$ret = $this->obj->create($table_name,$v);
		    	if(isset($ret['error']) && $ret['error'])
		    	{
			    	$this->errorOutput($ret['error']);
		    	}
	    	}
		    $this->addItem(array('success'));	    	
    	}
    	else
    	{
    		if(!$data)
    		{
    			$this->obj->start_import($table_name);
	    		$this->addItem(array('success'));
    		}
	    }
        $this->output();
    }
    
    function file2array($file_name, $type,$table_name)
	{
		if(!$file_name)
		{
			return;
		}
		$return = array();
		switch($type)
		{
			case 'txt':
			{
				if(file_exists($file_name))
				{			
					hg_split_file($file_name,$table_name.'_');
					$return = false;
					/*
					$table_info = $this->obj->get_field($table_name);
					$new_table_info = array();
					foreach($table_info as $k => $v)
					{
						if($v != 'id')
						{
							$new_table_info[] = $v;
						}						
					}
					$return = $this->txt2array($new_table_info,$table_name);	
					*/	
				}
				else
				{
					$return = array('error' => FILE_IS_NOT_EXISTS);
				}
				break;
			}
			case 'xls':
			{
				$return = $this->xls2array($file_name,$table_name);
				break;
			}
			case 'csv':
			{
			//	$return = $this->csv2array($file_name,$table_name);
			//	break;
			}
			default:exit('暂不支持此类型节目单上传');
		}
		return $return;
	}
	
	public function txt2array($table_info,$table_name)
	{
		$return = array();
		$fp = opendir(DATA_DIR);
		$data = array();
		while(false != $file = readdir($fp))
		{
			if($file != '.' && $file != '..' && strstr($file,$table_name.'_'))
		    {
		    	$tmp_total = hg_get_total_line(DATA_DIR.$file);
		    	$data = hg_getFileLines(DATA_DIR.$file,1,$tmp_total);
		    	@unlink(DATA_DIR.$file);
		    	break;
		    }
		}
		closedir($fp);
		foreach($data as $k => $v)
		{
			$v = str_replace(array('|',',','\\'),array(',',',',''),$v);
			$tmp_data = explode(',',$v);
			$length = count($tmp_data);
			if($length != count($table_info))
			{
				file_put_contents(CACHE_DIR . 'error.log',var_export($v,1),FILE_APPEND);
				$fp = opendir(DATA_DIR);
				while(false != $file = readdir($fp))
				{
					if($file != '.' && $file != '..' && strstr($file,$table_name.'_'))
				    {
				    	@unlink(DATA_DIR.$file);
				    }
				}
				closedir($fp);
				return array('error' => TABLE_FIELDS_IS_WRONG);
			}
			for($i = 0; $i < $length;$i++)
			{
				$return[$k][$table_info[$i]] = iconv("gbk","UTF-8",$tmp_data[$i]);
			}
		}
		//hg_pre($return);exit;
		if($return)
		{
	    	foreach($return as $k => $v)
	    	{
		    	$ret = $this->obj->create($table_name,$v);
		    	if(isset($ret['error']) && $ret['error'])
		    	{
			    	return $ret;
		    	}
	    	}
	    	unset($data,$return);
	    	//$this->txt2array($table_info,$table_name);				
		}
		else
		{
			return true;	
		}
	}
	
	public function csv2array($file_name,$table_name)
	{
		if (!$file_name)
		{
			return false;
		}
		
		require_once CUR_CONF_PATH . 'lib/PHPExcel.php';
		require_once CUR_CONF_PATH . 'lib/PHPExcel/IOFactory.php';
		require_once CUR_CONF_PATH . 'lib/PHPExcel/Reader/CSV.php';
		$objReader		= PHPExcel_IOFactory::createReader('CSV');
		//$objReader->setInputEncoding('GBK');
		$objPHPExcel 	= $objReader->load($file_name); 
		echo 321;exit;
		$sheet 			= $objPHPExcel->getSheet(0); 
		$highestRow 	= $sheet->getHighestRow();    //取得总行数 
		$highestColumn 	= $sheet->getHighestColumn(); //取得总列数
		$highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);//将字符转为十进制
		echo $highestColumn;exit; 
	//	$A1 = trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(ord($currentColumn) - 65, 1)->getValue());//default 默认为表名，但是不用于
		
		hg_pre($A1);exit;
	}
	
	public function xls2array($file_name,$table_name)
	{
		if (!$file_name)
		{
			return false;
		}
		
		require_once CUR_CONF_PATH . 'lib/PHPExcel.php';
		require_once CUR_CONF_PATH . 'lib/PHPExcel/IOFactory.php';
		require_once CUR_CONF_PATH . 'lib/PHPExcel/Reader/Excel5.php';
		
		$objReader		= PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel 	= $objReader->load($file_name); 
		$sheet 			= $objPHPExcel->getSheet(0); 
		$highestRow 	= $sheet->getHighestRow();    //取得总行数 
		$highestColumn 	= $sheet->getHighestColumn(); //取得总列数
		$highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);//将字符转为十进制
		$A1 = trim($objPHPExcel->getActiveSheet()->getCell("A1")->getValue());//default 默认为表名，但是不用于
		
		if(trim($A1) != $table_name)
		{
			//return array('error' => TABLE_NAME_IS_WRONG);
		}
		$table_info = $this->obj->get_field($table_name);
		$length = count($table_info);
		$xls_keys = array();
		$xls_order = array();
		$data = array();
		//echo $highestRow;exit;
		/*****反转取最大值******/
		$max = 100;
		$xls_order = $this->get_order($max);
		$flip_order = array_flip($xls_order);
		//$highestColumn = $flip_order[$highestColumn];
		/****end****/
		if($highestColumn == 1)
		{
			$xls_order = $this->get_order($highestColumn);
			$tmp_name = $xls_order[$highestColumn] . 2;
			$xls_keys = trim($objPHPExcel->getActiveSheet()->getCell($tmp_name)->getValue());
			$xls_keys = explode(',',$xls_keys);
			foreach($xls_keys as $k => $v)
			{
				$xls_keys[$k] = trim($v,'"');
			}
		//	hg_pre($xls_keys);exit;
			
			$data = array();
			for($i=3; $i<=$highestRow; $i++)
			{
				foreach($xls_order as $k => $v )
				{
					$tmp_value = trim($objPHPExcel->getActiveSheet()->getCell($v.$i)->getValue());
					$tmp_value = explode(',',$tmp_value);
					for($j=0;$j<count($tmp_value);$j++)
					{
						$data[$i][$xls_keys[$j]] = trim($tmp_value[$j],'"');
					}					
				}
			}
			//hg_pre($data);exit;
		}
		else
		{
			$xls_order = $this->get_order($highestColumn);  
            /*
            for($i = 1;$i<=$highestColumn;$i++)
            {
                    $tmp_name = $xls_order[$i] . 2;
                    $xls_keys[$i] = trim($objPHPExcel->getActiveSheet()->getCell($tmp_name)->getValue());
            }
            $checkRepeat = array_diff($xls_keys,$table_info);
            if(!empty($checkRepeat))
            {
                      return array('error' => TABLE_KEYS_IS_WRONG);
            }*/
            $data = array();
            unset($xls_keys);
            for($i=1; $i<=$highestRow; $i++)
            {
                foreach($xls_order as $k => $v )
                {
                    if($i == 1)
                    {
                        $xls_keys[$k] = trim($objPHPExcel->getActiveSheet()->getCell($v.$i)->getValue());
                    }
                    else
                    {
                    	$data[$i][$xls_keys[$k]] = trim($objPHPExcel->getActiveSheet()->getCell($v.$i)->getValue());
                    }
                }
            }			
		}
		
		if($data)
		{
			return $data;
		}
		else
		{
			return array('error' => FAILED);
		}		
	}
	
	private function get_order($max=0)
	{
		$xls_order = array();
		for($i=1;$i<=$max;$i++)
		{
			if($i > count($this->settings['xls_order']))
			{
				$bei = floor($i/count($this->settings['xls_order']));
				$yu = $i%count($this->settings['xls_order']);
				if(!$yu)
				{
					$xls_order[$i] = $this->settings['xls_order'][$bei-1].$this->settings['xls_order'][count($this->settings['xls_order'])];
				}
				else
				{
					$xls_order[$i] = $this->settings['xls_order'][$bei].$this->settings['xls_order'][$i-$bei*count($this->settings['xls_order'])];
				}
			}
			else
			{
				$xls_order[$i] = $this->settings['xls_order'][$i];
			}
		}
		return $xls_order;
	}
    
    
    public function import_data1()
    {
    	$is_empty = intval($this->input['is_empty']) ? 1 : 0;
    	$table_name = $this->input['table_name'] ? trim($this->input['table_name']) : '';
    	if(!$table_name)
    	{
	    	$this->errorOutput(NO_TABLE_NAME);
    	}
    	
    	$ret = array('success');
    	//$file = $_FILES['import'];
		//$type = substr($file['name'], strrpos($file['name'], '.')+1);
		$type = 'txt';
    	$data = $this->file2array($file['tmp_name'],$type,$table_name);
    	//var_dump($data);exit;
    	if($type != 'txt')
    	{
	    	    	
    	}
    	else
    	{
    		if(!$data)
    		{
	    		$this->addItem(array('success'));
    		}
    		else
    		{
		    	if(isset($data['error']) && $data['error'])
		    	{
			    	$this->errorOutput($data['error']);
		    	}
    		}
	    }
        $this->output();
    }

    
    public function unknow(){}
}

$out = new dataManagerUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
