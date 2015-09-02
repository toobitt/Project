<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: $
***************************************************************************/
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','livcms');//模块标识
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class column extends LivcmsFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function getSiteinfo()
	{
		
	}
	public function create()
	{
		$data_arr = array(array(
		'colname' => trim($this->input['name']),
		'content' => trim(urldecode($this->input['brief'])),
		'fatherid' =>  intval($this->input['cms_fid']),
		'siteid'=>intval($this->input['cms_siteid']),
		'columnid'=>intval($this->input['cms_columnid']),
		'linkurl' => trim(urldecode($this->input['linkurl'])),
		'column_dir' => trim(urldecode($this->input['column_dir'])),
		'orderid' => intval($this->input['orderid']),
		'childdomain' => urldecode($this->input['childdomain']),
		'relate_dir' => urldecode($this->input['relate_dir']),
		'colindex' => urldecode($this->input['colindex']),
		)
		);												   
		$iid = array();
		foreach ($data_arr as $key=>$data_info)//写入数据库
		{  
			$fatherid = intval($data_info['fatherid']);
			$finfo = $this->getfatherpath($fatherid);
			if($fatherid>0 && $finfo)
			{
				$fatherdir = $finfo['coldir'];
				$coldepth = count(explode("/",$fatherdir));
			}
			else
			{
				$fatherdir = '';
			}
			$column = array(
				'columnid' => $data_info['columnid'],
				'siteid' => $data_info['siteid'],
				'linkurl'=> $data_info['linkurl'],
				'coldir'=> $data_info['column_dir'],
				'coldepth'=> $data_info['coldepth'],
				'fatherid' => ($fatherid) ? $fatherid : -1,
				'colname' => trim($data_info['colname']),
				'colindex' => $data_info['colindex'],
				'coldepth' => $coldepth,
				'folderformat' => 'Y/m',
				'fileformat' => 'Y-m-d{ID}',
				'suffix' => '.php',
				'isbuilt' => 0,
				'maketype' => 2,
				'thumbwidth' => 120,
				'thumbheight' => 120,
				'contentdir' => 'showcontent',
				'contentfilename' => 'showcontent',
				'childdomain' => $data_info['childdomain'],
				'coltag' => '',
				'content' => $data_info['content'],
				'rssnum' => 15,
				'toindexnum' => 5,
				'use_dateformat' => 2,
				'article_maketype' => 2,
				'orderid' => $data_info['orderid'],
				'status' => 3,
				'channel_id'=>intval($this->input['channel_id']),
				'relate_dir'=>$data_info['relate_dir'],
			);
			$sql = 'INSERT INTO '.DB_PREFIX.'column SET ';
			foreach($column as $k=>$v)
			{
				$sql .= $k .' = "'.$v.'",';
			}
			$this->db->query(trim($sql, ','));
			$iid[] = $newcolumnid = $this->db->insert_id();	
			$colp = $finfo['colparents'] ? $newcolumnid.','.$finfo['colparents'] : $newcolumnid;	
			
			//
			$sql = "update ".DB_PREFIX."column set colchilds = '".$newcolumnid.
			"',colparents = '".$colp."' where columnid=".$newcolumnid;
			$this->db->query($sql);

			//
			//$sql = 'UPDATE '.DB_PREFIX.'column SET colchilds = concat(colchilds,"'.','.$newcolumnid.'") WHERE columnid = '.$data_info['fatherid'];
			//$this->db->query($sql);
			
			//
			
			$this->updatefcolchilds($newcolumnid,$fatherid);//更新父类ID
			//$recache = new cache;
			//$recache->column_recache();
		}
		
		include_once (ROOT_DIR . 'lib/class/curl.class.php');
		$curl = new curl(LIVCMS_HOST, 'task/');
		$curl->initPostData();
		$curl->addRequestData('cache', 'column');
		$curl->request('recache.php');
		echo implode(',', $iid);
		exit();
	}
	//更改原节点的所有父节点的子节点
	private function updatefnodes($cid)
	{
		$current_chnodes = $this->getfatherpath($cid);
		/*
		$cidchilds = explode(',', $current_chnodes['colchilds']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'column WHERE columnid in('.$current_chnodes['colparents'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($cid == $row['columnid'])
			{
				continue;
			}
			//临时变量
			$t = explode(',', $row['colchilds']);
			$differnodes = array_diff($t, $cidchilds);
			$sql = 'UPDATE '.DB_PREFIX.'column SET colchilds = "'.implode(',', $differnodes).'" WHERE columnid = '.$row['columnid'];
			$this->db->query($sql);
		}*/
		$sql = 'UPDATE '.DB_PREFIX.'column SET colchilds = REPLACE(colchilds, "'.','.$current_chnodes['colchilds'].'","") WHERE columnid IN('.$current_chnodes['colparents'].')';
		$this->db->query($sql);
	}
	//获取指定栏目的节点关系
	private function getfatherpath($fid)
	{
		if(!$fid)
		{
			return array();
		}
		$sql = 'SELECT colparents,colchilds,coldepth FROM '.DB_PREFIX.'column WHERE columnid = '.$fid;
		$dir = $this->db->query_first($sql);
		return $dir ? $dir : array();
	}
	//创建节点时修改节点的父节点数据 所有父节点的子节点添加新创建的节点的ID
	private function updatefcolchilds($newcolumnid, $fatherid)
	{
		if($fatherid <= 0)
		{
			return;
		}
		$sql = 'SELECT colparents FROM '.DB_PREFIX.'column WHERE columnid = '.$fatherid;
		$fatherids = $this->db->query_first($sql);
		$sql = 'UPDATE '.DB_PREFIX.'column SET colchilds = concat(colchilds,"'.','.$newcolumnid.'") WHERE columnid in('.$fatherids['colparents'].')';
		$this->db->query($sql);
	}
	//更新目标节点的所有父节点和当前节点的子节点
	private function updatenewfcol($dfid,$cid, $coldepth)
	{
		$dfidinfo = $this->getfatherpath($dfid);
		$cidinfo = $this->getfatherpath($cid);
		$ciddir = '/'.$this->settings['liv_cms']['folderPreFix'].$cid;
		if($dfidinfo)
		{
			/*
			$sql = 'SELECT * FROM '.DB_PREFIX.'column WHERE columnid in('.$dfidinfo['colparents'].')';
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$sql = 'UPDATE '.DB_PREFIX.'column SET colchilds = "'.$row['colchilds'].','.$cidinfo['colchilds'].'" WHERE columnid = '.$row['columnid'];
				$this->db->query($sql);
			}*/
			//目标节点的所有父节点添加子节点
			$sql = 'UPDATE '.DB_PREFIX.'column SET colchilds = CONCAT(colchilds,"'.','.$cidinfo['colchilds'].'") WHERE columnid IN('.$dfidinfo['colparents'].')';
			$this->db->query($sql);
			//更新所有字节点的父节点为新目标节点的父节点 同事修改生成的目录
			$sql = 'UPDATE '.DB_PREFIX.'column SET colparents = REPLACE(colparents,"'.
			$cidinfo['colparents'].'","'.$cid.','.$dfidinfo['colparents'].'") WHERE columnid in('.$cidinfo['colchilds'].')';
			$this->db->query($sql);
		}
		else
		{
			$sql = 'UPDATE '.DB_PREFIX.'column SET colparents = REPLACE(colparents,"'.
			$cidinfo['colparents'].'","'.$cid.'") WHERE columnid in('.$cidinfo['colchilds'].')';
			$this->db->query($sql);
		}
		//$sql = 'UPDATE '.DB_PREFIX.'column SET coldir = REPLACE(coldir,"'.$cidinfo['colparents'].'","'.$cid.'") WHERE columnid in('.$cidinfo['colchilds'].')';
		//$this->db->query($sql);
	}
	public function update()
	{
		if(!$columnid = intval($this->input['column_id']))
		{
			exit('0');
		}
		$data_arr = array(array(
		'colname' => trim($this->input['name']),
		'content' => trim(urldecode($this->input['brief'])),
		'fatherid' =>  intval($this->input['cms_fid']),
		'siteid'=>intval($this->input['cms_siteid']),
		'column_dir'=>trim(urldecode($this->input['column_dir'])),
		'linkurl'=>trim(urldecode($this->input['linkurl'])),
		'coldepth' => trim(urldecode($this->input['depath'])),
//		'childdomain' => urldecode($this->input['childdomain']),
		'relate_dir' => urldecode($this->input['relate_dir']),
		'colindex' => urldecode($this->input['colindex']),
		)
		);
		foreach ($data_arr as $key=>$data_info)//写入数据库
		{  
			$fatherid = intval($data_info['fatherid']);
			$finfo = $this->getfatherpath($fatherid);
			$cinfo = $this->getfatherpath($columnid);
			if($fatherid>0 && $finfo)
			{
				$fatherdir = $finfo['coldir'];
				$coldepth = count(explode("/",$fatherdir));
			}
			else
			{
				$fatherdir = '';
			}
			$column = array(
				'siteid' => $data_info['siteid'],
				'fatherid' => ($fatherid) ? $fatherid : -1,
				'colname' => trim($data_info['colname']),
				'content' => $data_info['content'],
				'coldir'=> $data_info['column_dir'],
				'linkurl'=> $data_info['linkurl'],
				'coldepth'=> $data_info['coldepth'],
//				'childdomain' => $data_info['childdomain'],
				'relate_dir' => $data_info['relate_dir'],
				'colindex' => $data_info['colindex'],
				//'primarymode' => 1,
				//'channel_id'=>intval($this->input['channel_id']),更新栏目时不需要更新直播频道ID
			);
			$sql = 'UPDATE '.DB_PREFIX.'column SET ';
			foreach($column as $k=>$v)
			{
				$sql .= $k .' = "'.$v.'",';
			}
			$sql = trim($sql, ',') . ' WHERE columnid = '.$columnid;
			$this->db->query($sql);

			$coldepth = $cinfo['coldepth'] - $coldepth;
			$this->updatefnodes($columnid);
			$this->updatenewfcol($fatherid, $columnid, $coldepth);
			
		}
		include_once (ROOT_DIR . 'lib/class/curl.class.php');
		$curl = new curl(LIVCMS_HOST, 'task/');
		$curl->initPostData();
		$curl->addRequestData('cache', 'column');
		$curl->request('recache.php');
		exit('1');
	}
	public function delete()
	{
		//修改栏目状态 栏目表1代表workben中的栏目被删除
		if(!$this->input['columnid'])
		{
			return;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'column WHERE columnid = '.intval($this->input['columnid']);
		//file_put_contents('1.txt', $sql);
		$this->db->query($sql);
		echo $this->db->affted_rows();
		exit;
	}
	public function unknown()
	{
		$this->addItem('intvalid action');
		$this->output();
	}
	//CMS栏目排序
	public function drag_order()
	{
		$order = json_decode(html_entity_decode($this->input['order']), 1);
		if($order)
		{
			foreach($order as $colid=>$orderid)
			{
				$sql = "UPDATE " .DB_PREFIX. "column  SET orderid = '".$orderid."'  WHERE columnid = '".$colid."'";
				$this->db->query($sql);
			}
		}
		$this->addItem('success');
		$this->output();
	}
}
$out = new column();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'unknown';
}
$out->$action();
?>