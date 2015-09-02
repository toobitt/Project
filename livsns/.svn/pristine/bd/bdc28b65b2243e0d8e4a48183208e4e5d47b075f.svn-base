<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|count|unknow
* @private function get_condition
*
* $Id: program_api.php 5822 2012-02-01 09:03:59Z zhoujiafei $
***************************************************************************/
require('global.php');
class programApiApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include program.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program.class.php');
		$this->program = new program();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 根据频道，按时段显示节目，包含节目计划以及没有节目时自动填充
	 * @name show
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @return $program array 节目内容，键为日期，必须产生键，保证输出数据的完整性
	 */
	function show()
	{
		$condition = $this->get_condition();
		$channel_id = $this->input['channel_id'];
		$channel = $this->program->getChannelById($channel_id);
		if(!$channel)
		{
			$this->errorOutput("此频道不存在或者已被删除！");
		}
		$channel_name = $channel['name'];
		
		$save_time = $channel['save_time'];

		$start_time = strtotime(date("Y-m-d",(TIMENOW - 3600 * $save_time))." 00:00:00");
		$end_time = strtotime(date("Y-m-d",TIMENOW)." 23:59:59");
		$condition = " AND channel_id=" . $channel_id . " AND  start_time>'" . $start_time ."' AND start_time<" . $end_time ."";
		$info = $this->program->show($condition,$channel_id,$start_time,$end_time,$save_time);

		$date_type = array(
			'00:00~09:00' => array(
				'start'=> '00:00:00',
				'end'=> '08:59:59'
					),	
			'09:00~13:00' => array(
				'start'=> '09:00:00',
				'end'=> '12:59:59'
					),	
			'13:00~19:00' => array(
				'start'=> '13:00:00',
				'end'=> '18:59:59'
					),	
			'19:00~24:00' => array(
				'start'=> '19:00:00',
				'end'=> '23:59:59'
					),	
		);
		//串联单时移
		$dates = urldecode($this->input['dates']);
		$today = date('Y-m-d');
		$stime = urldecode($this->input['stime']);
		
		if ($dates > $today)
		{
			$day_offset = (strtotime($dates) - strtotime($today))/86400;
		}
		$i = $day_offset;
		
		$program = array();
		foreach($info as $ks => $vs)
		{
			$day_program = array();
			$v['dates'] = '';
			foreach($vs as $k => $v)
			{
				if ($dates > $today)
				{
					$_dates = date('Y-m-d', strtotime($today) + 86400 * $i);
				}
				
				$v['starttime'] =  date("Y-m-d H:i:s",$v['start_time']);
				$v['endtime'] =  date("Y-m-d H:i:s",($v['start_time']+$v['toff']));
				$v['start'] = date("H:i",$v['start_time']);
				$v['channel_name'] = $channel_name;
				//补齐频道id
				$v['channel_id'] = $v['channel_id'] ? $v['channel_id'] : $channel_id;
				foreach($date_type as $key => $value)
				{
					$start = strtotime($v['dates'] . " " . $value['start']);
					$end = strtotime($v['dates'] . " " . $value['end']);
					if($v['start_time'] >= $start && $v['start_time'] < $end)
					{
						if ($dates > $today)
						{
							$v['starttime'] = $_dates . ' ' . date('H:i:s', $v['start_time']);
							$v['endtime'] = $_dates . ' ' . date('H:i:s', ($v['start_time'] + $v['toff']));
							$v['dates'] = $_dates;
							$v['start_time'] = strtotime($v['starttime']);
							$v['weeks'] = date('W', $v['start_time']);
							if (strtotime($_dates . ' ' . $stime) < ($v['start_time']+$v['toff']) && $i == $day_offset)
							{
								$v['display'] = 0;
							}
						}
						$day_program[$key][] = $v;
					}
				}
			}
			if($day_program)
			{
				$program[$v['dates']] = $day_program;
			}
			
			$i --;
		}
		$this->addItem($program);
		$this->output();
	}

	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->encode->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		return $condition;
 }

	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new programApiApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			