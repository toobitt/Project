<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: live_backup.php
***************************************************************************/
require_once('./global.php');
class live_backup extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 备播文件列表显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @return $r array 所有备播文件内容信息
	 */
	function show()
	{
		$offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
		$count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
		$limit = " limit {$offset}, {$count}";
		$orders = array('id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'backup  WHERE 1' . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('adv_positions','adv_position');
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			if($r['toff'])
			{
				if(intval($r['toff']/1000/60))
				{
					$r['toff'] = intval($r['toff']/1000/60) . "'" . intval(($r['toff']/1000/60-intval($r['toff']/1000/60))*60) .'"' ;
				}
				else 
				{
					$r['toff'] = intval($r['toff']/1000) . '"' ;
				}
			}
			if($r['vodinfo_id'])
			{
			//	$r['file_uri'] = $this->settings['vod_url'].hg_num2dir($r['vodinfo_id']).$r['vodinfo_id'].VIDEO_MARK_FILE;
				$r['file_uri'] = $this->settings['vod_url'].$r['filepath'] . $r['newname'];
			}
			else 
			{
				$r['file_uri'] = UPLOAD_BACKUP_MMS_URL.hg_num2dir($r['id']) . $r['newname'];
			}
			
			$r['img'] = SOURCE_THUMB_PATH.$r['img'];
			$this->addItem($r);
			//hg_pre($r);
		}
		$this->output();
	}
		
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and name like \'%'.urldecode($this->input['k']).'%\'';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id IN('.trim(urldecode($this->input['id'])).')';
		}
		return $condition;
	}
		
	/**
	 * 单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 备播文件ID
	 * @return $row array 单条频道信息
	 */
	function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('未传入ID或者不存在');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'backup WHERE id ='.$id;
		$row = $this->db->query_first($sql);
		$row['img'] = SOURCE_THUMB_PATH . $row['img'];
		if($row['toff'])
		{
			if(intval($row['toff']/1000/60))
			{
				$row['toff'] = intval($row['toff']/1000/60) . "'" . intval(($row['toff']/1000/60-intval($row['toff']/1000/60))*60) .'"' ;
			}
			else 
			{
				$row['toff'] = intval($row['toff']/1000) . '"' ;
			}
		}
		$this->addItem($row);
		$this->output();
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $ret string 总数，json串
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "backup AS v WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$ret = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($ret);
	}
}
$output= new live_backup();
if(!method_exists($output, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>