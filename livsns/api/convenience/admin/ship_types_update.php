<?php
define('MOD_UNIQUEID','ship_types');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/bus_types.class.php');
require_once(CUR_CONF_PATH . 'lib/excel.class.php');
class bus_types_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->bustypes = new bustypes();
		$this->excel = new excel();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		if(!$this->input['departStation']||!$this->input['arriveStation'])
		{
			$this->errorOutput('上船站,到达站不允许为空!');
		}
		$strtotimes=intval(strtotime(date('Ymd')));
		$departDate=$this->input['departDate']?intval(strtotime($this->input['departDate'])):$strtotimes;
		$startStation=$this->input['startStation']?trim($this->input['startStation']):trim($this->input['departStation']);
		$terminalStation=$this->input['terminalStation']?trim($this->input['terminalStation']):trim($this->input['arriveStation']);
		$halfPrice=$this->input['halfPrice']?floatval($this->input['halfPrice']):floatval($this->input['fullPrice']/2);
		$buslevel=$this->input['busLevel']?trim($this->input['busLevel']):'客船';
		$input=array(
			'departDate'	 => $departDate, //发船日期
			'busCode'		 => trim($this->input['busCode']),  //船次
			'departTime'	 => intval(strtotime($this->input['departTime'])),  //发船时间
			'departStation'	 => trim($this->input['departStation']),  //上船站名称
			'arriveStation'	 => trim($this->input['arriveStation']),  //到达站名称
			'terminalStation'=> $terminalStation,  //终点站名称
			'takeTime'		 => $this->input['takeTime'],  //途时
			'seats'			 => $this->input['seats'],  //客座数
			'busLevel'		 => $buslevel,  //船辆等级
			'remainTickets'	 => $this->input['remainTickets'], //余票
			'startStation'	 => $startStation, //始发站名称
			'fullPrice'		 => $this->input['fullPrice'], //全票价
			'halfPrice'		 => $halfPrice, //半票价
			'verifyMessage'	 => $this->input['verifyMessage'], //校验信息
			'mileages'		 => $this->input['mileages'], //里程
			'arriveTime'     => strtotime($this->input['departTime'])+$this->input['takeTime']*3600, //发船时间+途时
			'create_time'    => TIMENOW,
			'type'			 =>	1,
		);
		$ret = $this->bustypes->create($input);
		if($ret)
		{
			$this->addLogs('添加船次',$ret,'','添加' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	public function update(){}

	public function copy()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NO_DATE);
		}
		if(!$this->input['station'])
		{
			$this->errorOutput('没有站点');
		}
		$condition=$this->get_condition();
		$ret = $this->bustypes->copy($condition);
		if($ret)
		{
			$this->addLogs('添加船次',$ret,'','复制' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	public function excel_update()
	{
		//获取文件扩展名
		 $extend = pathinfo($_FILES["excel"]["name"]);
		 $extend = strtolower($extend["extension"]);
		 //获取文件扩展名结束
		 $time=date("Y-m-d-H-i-s");//取当前上传的时间
		 $name=$time.'.'.$extend; //重新组装上传后的文件名
		 $uploadfile=CACHE_DIR.$name;//上传后的文件名地址
		if ((($extend == "xls")&&($_FILES["file"]["size"] < 200000)))
		{
			$tmp_name=$_FILES["excel"]["tmp_name"];
			$strtotimes=strtotime(date('Ymd'));
			$key=md5_file($tmp_name);
			$sql=" SELECT filekey FROM " .DB_PREFIX. "con_fileinfo WHERE filekey = '" .$key. "' AND create_time =".$strtotimes;
			$re=$this->db->query_first($sql);
			if ($_FILES["excel"]["error"] > 0)
			{
				$this->errorOutput("Return Code: " . $_FILES["excel"]["error"] . "<br />");
			}
			elseif($re['filekey']==$key)
			{
				$this->errorOutput('已经导入成功,无需重复导入');
			}
			else
			{
				$isupload=$this->excel->show($uploadfile,$tmp_name,'1');
				if($isupload)
				{
					$sql = 'INSERT INTO ' . DB_PREFIX . 'con_fileinfo SET filekey = \''.$key.'\',create_time ='.$strtotimes;
					$this->db->query($sql);
					// 删除除今天以外的文件MD5值.
					$sql = " DELETE FROM " .DB_PREFIX. "con_fileinfo WHERE 1 AND create_time NOT IN (".$strtotimes.")";
					$this->db->query($sql);
					$this->addItem($isupload);
					$this->output();
				}
				else $this->errorOutput('导入失败');

			}
		}
		else
		{
			$this->errorOutput('文件错误,仅支持xls,xlsx.或者文件大于2M');
		}

	}

	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NO_DATE);
		}
		$date=$this->input['id'];
		if(stripos($date,'_')!== false)
		{
			$tmp=explode(',', $date);
			$del_station = array();
			foreach ($tmp as $key=>$val)
			{
				$tmps=explode('_', $val);
				$tmps[1] && $del_station[urldecode($tmps[1])][] = $tmps[0];
			}
			foreach ($del_station as $k => $v)
			{
			   $this->input['id']= implode(',', $v);
			   $this->input['station'] = $k;
			   $condition = $this->get_condition();
		       $ret = $this->bustypes->delete($condition);
			}
				
		}
		if($ret)
		{
			$this->addLogs('删除船期',$ret,'','删除' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}

	public function sort(){}
	public function audit(){}
	public function publish(){}

	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$date=$this->input['id'];
			$date=$this->bustypes->date($date);
			$condition .= " AND departDate IN (".$date.")";
		}
		if($this->input['station'])
		{
			$condition .= ' AND  departStation  = \''.trim(urldecode($this->input['station'])).'\'';
		}
		$condition .= ' AND type = 1';
		return $condition;
	}
}

$out = new bus_types_update();
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