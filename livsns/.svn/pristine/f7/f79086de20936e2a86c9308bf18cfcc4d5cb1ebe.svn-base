<?php
define('MOD_UNIQUEID','busmanage');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/bus_manage.class.php');
class bus_types_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->busmanage = new busmanage();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create(){}

	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
			if(!$this->input['departStation']||!$this->input['arriveStation']||!$this->input['terminalStation'])
		{
			$this->errorOutput('上车站,到达站,终点站均不允许为空!');
		}
		$strtotimes=intval(strtotime(date('Ymd')));
		$departDate=$this->input['departDate']?intval(strtotime($this->input['departDate'])):$strtotimes;
		$startStation=$this->input['startStation']?trim($this->input['startStation']):trim($this->input['departStation']);
		$terminalStation=$this->input['terminalStation']?trim($this->input['terminalStation']):trim($this->input['arriveStation']);
		$halfPrice=$this->input['halfPrice']?floatval($this->input['halfPrice']):floatval($this->input['fullPrice']/2);
		$buslevel=$this->input['busLevel']?trim($this->input['busLevel']):'客车';
		$input=array(
			'departDate'	 => $departDate, //发车日期
			'busCode'		 => trim($this->input['busCode']),  //车次
			'departTime'	 => intval(strtotime($this->input['departTime'])),  //发车时间
			'departStation'	 => trim($this->input['departStation']),  //上车站名称
			'arriveStation'	 => trim($this->input['arriveStation']),  //到达站名称
			'terminalStation'=> $terminalStation,  //终点站名称
			'takeTime'		 => $this->input['takeTime'],  //途时
			'seats'			 => $this->input['seats'],  //客座数
			'busLevel'		 => $buslevel,  //车辆等级
			'remainTickets'	 => $this->input['remainTickets'], //余票
			'startStation'	 => $startStation, //始发站名称
			'fullPrice'		 => $this->input['fullPrice'], //全票价
			'halfPrice'		 => $halfPrice, //半票价
			'verifyMessage'	 => $this->input['verifyMessage'], //校验信息
			'mileages'		 => $this->input['mileages'], //里程
			'arriveTime'     => strtotime($this->input['departTime'])+$this->input['takeTime']*3600, //发车时间+途时
		);

		$ret = $this->busmanage->update($this->input['id'],$input);
		if($ret)
		{
			$this->addLogs('更新车次',$ret,'','更新' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	public function copy()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$ret = $this->busmanage->copy($this->input['id']);
		if($ret)
		{
			$this->addLogs('添加车次',$ret,'','更新' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$ret = $this->busmanage->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除出行类型',$ret,'','删除' . $this->input['id']);
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