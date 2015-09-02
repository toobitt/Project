<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','statistics');//模块标识
class statUpdateApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/statistics.class.php');
		$this->obj = new statistics();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function insert_record()
	{
		$statistics_data = $this->input['statistics_data'];
//		foreach($data as $k=>$w) 
//			$statistics_data[$k]=$w;
		//如果同一应用同一模块同一篇内容的同一个归属人相同 则删除之前记录
		/**
		$sql = "SELECT id FROM " . DB_PREFIX ."records WHERE content_id=$content_id " .
				"AND father_id=$father_id AND type=$type AND user_id=$user_id" .
				"AND app=$app AND module=$module" ;
		$record = $this->db->fetch_all($sql);
		if(!empty($record))
		{
			foreach($record as $k=>$v)
			{
				$ids .= $v['id'].',';
			}	
			$sql = "DELETE FROM " . DB_PREFIX ."records WHERE id in (".trim($ids,',').") ";
			$this->db->query($sql);
		}
		*/
		
		if(empty($statistics_data['app_uniqueid']) || empty($statistics_data['module_uniqueid']))
		{
			$this->errorOutput('WITH_OUT_UNIQUEID');
		}
		
		//根据应用和模块标识判断是否进行工作量统计
		$sql = "SELECT * FROM " . DB_PREFIX ."app_set WHERE app_uniqueid='".$statistics_data['app_uniqueid']."' AND module_uniqueid='".$statistics_data['module_uniqueid']."'";
		$statdata = $this->db->query_first($sql);
		if(!empty($statdata))
		{
			if(!$statdata['status'])
			{
				$this->errorOutput('IS_NOT_OPEN');
			}
		}
		
		$cidarr = explode(',',$statistics_data['content_id']);
		$uidarr = explode(',',$statistics_data['user_id']);
		$unamearr = explode(',',$statistics_data['user_name']);
		
		foreach($cidarr as $k=>$v)
		{
			//审核被打回  先判断之前有没有审核通过记录 ，有则删除
			
			/**
			 * if($statistics_data['type'] == "verify_fail" || $statistics_data['type'] == "verify_suc") 
			{
				$deletetype = ($statistics_data['type'] == "verify_fail")?"verify_suc":"verify_fail";
				$sql = "SELECT id FROM " . DB_PREFIX ."records WHERE app_uniqueid ='".$statistics_data['app_uniqueid']."' AND module_uniqueid='".$statistics_data['module_uniqueid']."' AND content_id=$v AND type=".$this->settings['statistics_type'][$deletetype];
				$recorddata = $this->db->fetch_all($sql);
				$reids = '';
				if(!empty($recorddata))
				{
					foreach($recorddata as $k1=>$v1)
					{
						$reids .= $v1['id'].',';
					}
					$reids = trim($reids,',');
					$sql = "DELETE FROM " . DB_PREFIX ."records WHERE id in (".$reids.")" ;
					
					$this->db->query($sql);
				}
			}
			*/
			$data=array(
				'user_id' => empty($uidarr[$k])?'':$uidarr[$k],
				'user_name' => empty($unamearr[$k])?'':$unamearr[$k],
				'type' => $this->settings['statistics_type'][$statistics_data['type']],
				'douser_id' => $this->user['user_id'],
				'douser_name' => $this->user['user_name'],
				'create_time' => TIMENOW,
				'year' => date('Y',TIMENOW),
				'month' => date('m',TIMENOW),
				'day' => date('d',TIMENOW),
			);
			$all_data = array_merge($statistics_data,$data);
			$sql="INSERT INTO " . DB_PREFIX . "records SET";
			
			$sql_extra=$space=' ';
			foreach($all_data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
			$this->db->query($sql);
			
		}
		//将新的用户添加到user表中，唯一
		$sql = "SELECT * FROM " . DB_PREFIX ."user WHERE user_id='".$this->user['user_id']."'";
		if(!$this->db->query_first($sql))
		{
			$sql = "INSERT INTO " . DB_PREFIX . "user SET user_id='".$this->user['user_id']."',user_name='".$this->user['user_name']."'";
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();		
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

$out = new statUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			