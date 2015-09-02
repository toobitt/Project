<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: $
接口使用说明：
#init方法用于初始化整个推送环境 主要是参数@param $_INPUT[columnid] 也可以自定义 但是要指定$_INPUT[colname]
#
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require_once('./common_functions.php');
require('livcms_frm.php');
require_once('./mkqueue.php');
class content extends LivcmsFrm
{
	private $col_fields = array();//栏目模型字段
	private $site_info = array();//站点信息 默认为1
	private $argsetting = array();//cms设置选项
	private $data_info = array();//所有数据信息 input
	private $colids = array();//栏目ID
	private $column_info = array();//栏目CMS信息
	private $mkqueue = '';//CMS队列
	function __construct()
	{
		parent::__construct();
		if($this->input['a'] != 'cancell_publish')
		{
			$init = $this->__init();
			if(!$init)
			{
				//初始化失败 结束操作
				$this->errorOutput(INIT_ERROR); 
			}
		}
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//重写token防止token报错失败
	function getSiteinfo()
	{
	}
	function unknown()
	{
		$this->errorOutput(UNKNOWN_ACTION);
	}
	//初始化
	function __init()
	{
		$this->user['user_name'] = trim($this->user['user_name']) ? $this->user['user_name'] : $this->input['_admin_name'];
		$this->user['user_id'] = intval($this->user['user_id']) ? $this->user['user_id'] : $this->input['_admin_id'];
		$this->data_info = $this->input;
		//file_put_contents(ROOT_PATH.'uploads/tmp/t4.txt', var_export($this->input,1), FILE_APPEND);
		//对所有的curl的数据解码
		foreach($this->data_info as $k=>$v)
		{
			if(!is_array($v))
			{
				$this->data_info[urldecode($k)]=urldecode($v);
			}
			else
			{
				foreach($v as $kk=>$vv)
				{
					if(!is_array($vv))
					{
						$this->data_info[$k][urldecode($kk)]=urldecode($vv);
					}
				}
			}
		}
		//传递的栏目表单名称
		if($this->data_info['colname'])
		{
			$this->colids = $this->data_info[$this->data_info['colname']];
		}
		//默认columnid
		else
		{
			$this->colids = $this->data_info['columnid'];
		}
		//无栏目ID 则初始化失败
		if(!$this->colids)
		{
			return false;
		}
		//栏目可以是1,2,3 也可以是数组
		$this->colids = is_array($this->colids) ? $this->colids : explode(',', $this->colids);
		//取出各发布栏目在CMS中的记录
		$sql = 'SELECT * FROM '.DB_PREFIX.'column WHERE columnid IN('.implode(',',$this->colids).')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$this->column_info[$row['columnid']] = $row;
		}
		//去站点信息时用到的变量 此处必须所有栏目属于同一个站点
		$default_col = $this->colids[0];
		//获取栏目模型字段
		$this->col_fields = $this->getModeFields();
		$sql = 'SELECT s.* FROM '.DB_PREFIX.'siteconf s LEFT JOIN '.DB_PREFIX.'column c ON c.siteid = s.siteid WHERE c.columnid = '.$default_col;
		$this->site_info = $this->db->query_first($sql);
		//获取栏目设置
		$args = array('content_split', 'is_open');
		$sql = 'SELECT varname,value FROM '.DB_PREFIX.'setting WHERE varname IN("'.implode('","', $args).'")';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$this->argsetting[$row['varname']] = $row['value'];
		}
		//初始化CMS队列类
		$this->mkqueue = new mkqueue($this->site_info);
		return true;
	}
	//#返回栏目模型的表名 表字段 转发字段 应用ID
	private function getModeFields()
	{
		$colids_str = implode(',', $this->colids);
		$primarymode = array();
		//如果没有传递模型应用ID 则根据栏目查询 支持多栏目
		if(!$this->input['applyid'])
		{
			$sql = 'SELECT columnid,primarymode FROM '.DB_PREFIX.'column WHERE columnid In('.$colids_str.')';
			$q  = $this->db->query($sql);
			if(!$primarymode)
			{
				while($row = $this->db->fetch_array($q))
				{
					$primarymode[$row['columnid']] = $row['primarymode'];
				}
				if(!$primarymode)
				{
					$this->errorOutput(NO_MODEL);
				}
			}
		}
		//如果传递了模型应用的ID 则无需查询
		else
		{
			foreach($this->colids as $k=>$v)
			{
				$primarymode[$v] = $this->input['applyid'];
			}
		}
		$sql = 'SELECT appr.applyid,appr.fieldname,appr.colmodeid,appr.display_style,mo.bodyfield FROM '.DB_PREFIX.'apply_relate 
		appr LEFT JOIN '.DB_PREFIX.'mode_apply mo ON mo.applyid = appr.applyid WHERE appr.applyid In('.implode(',', $primarymode).')';
		$q = $this->db->query($sql);
		$modeid = $fields = $bodyfield = array();
		while($row = $this->db->fetch_array($q))
		{
			$fields[$row['applyid']][$row['fieldname']] = $row['display_style'];
			$modeid[$row['applyid']] = $row['colmodeid'];
			$bodyfield[$row['applyid']] = $row['bodyfield'];
		}
		$return = array();
		//获取栏目表名
		$tablename = $this->getModelName(array_unique($modeid));
		//返回栏目模型的表名 表字段 转发字段 应用ID 
		foreach ($primarymode as $colid=>$pri)
		{
			$return[$colid]['table_name'] = $tablename[$modeid[$pri]];
			$return[$colid]['trans_field'] = $bodyfield[$modeid[$pri]];
			$return[$colid]['applyid'] = $pri;
			$return[$colid]['fields'] = $fields[$pri];
		}
		return  $return;
	}
	//获取模型的表名
	//#返回模型应用的表名
	private function getModelName($modeid)
	{
		$return = array();
		if(!$modeid)
		{
			return $return;
		}
		$sql = 'SELECT colmodeid,modesign FROM '.DB_PREFIX.'contentmode WHERE colmodeid IN('.implode(',', $modeid).')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$return[$row['colmodeid']] = $row['modesign'];
		}
		return $return;
	}
	//根据是否需要转存 调用此方法
	//@colid 栏目id
	//@contentid 内容id
	//@trans_field_value 转发字段的值
	//@$primary_id
	//#返回总的页数
	private function transStore($colid, $contentid,$trans_field_value)
	{
		$pages = 1;
		//如果设置分页字符
		if ($this->argsetting['content_split'])
		{
			$contents = explode($this->argsetting['content_split'], $trans_field_value);
			$pages = 0;
			foreach ($contents AS $content)
			{
				if ($content)
				{
					$pages++;
					$sqlv[] = "(".$this->col_fields[$colid]['applyid'].",$contentid,'".addslashes($content)."',$pages,'1')";
				}
			}
			$sqlv = implode(',', $sqlv);
		}
		else
		{
			$sqlv = "(".$this->col_fields[$colid]['applyid'].",$contentid,'".addslashes($trans_field_value)."',1,'1')";
		}
		$sql = "insert into ".DB_PREFIX.$this->col_fields[$colid]['table_name']."_contentbody (applyid,".$this->col_fields[$colid]['table_name']."id,content, pageid, pagetitle) values $sqlv";
		$this->db->query($sql);
		//返回总的页数
		return $pages;
	}
	//更新contentmap表
	//@$colid 栏目ID
	//contentinfo  @$newid 新插入的内容ID @$pages 转存之后产生的页数 
	//#返回contentmapid 
	private function createContentMap($colid, $contentinfo=array())
	{
		//默认数据处理
		$contentmap = array(
		'contentid' => $contentinfo['contentid'],
		'siteid' => $this->site_info['siteid'],
		'columnid' => $colid,
		'modeid' => $this->col_fields[$colid]['applyid'],
		'pubdate' => TIMENOW,
		'orderid' => 0,
		'linktype' => 1,
		'rcmdtype' => 0,
		'status' => 3,
		'pointnum' => 0,
		'promulgator'=>urldecode($this->user['user_name']),
		'userid'=>$this->user['user_id'],
		'fulltitle' => implode(' ', duality_word($this->data_info['title'])),
		'source_column' => $colid,
		'title' => $this->data_info['title'],
		'indexpic' => $this->data_info['indexpic'],
		'pages' => $contentinfo['pages'],
		'liv_material_suffix' => $this->data_info['liv_material_suffix'],
		);
		if($this->input['pub_time'])
		{
			$contentmap['pubdate'] = $this->input['pub_time'];
		}
        $sql = 'INSERT INTO '.DB_PREFIX.'contentmap SET ';
        foreach ($contentmap as $field=>$value)
        {
        	$sql .= $field ."='$value',";	
        }
        $sql = trim($sql, ',');
        $this->db->query($sql);
		$needid  = $this->db->insert_id();
		//设置队列数据
		$this->mkqueue->set($needid, 0);
		//生成网页路径
		$madeartpath = build_content_dir($this->column_info[$colid], $needid, strtotime($contentmap['pubdate']),$this->site_info);
		$sql = "UPDATE " . DB_PREFIX . "contentmap SET staticfile='".$madeartpath."', orderid = '".$this->data_info['orderid']."',quoteid = '$needid' WHERE id = '".$needid."' ";
		$this->db->query($sql);
		//返回contentmapid
		return $needid;
	}
	//发布新增 初始化时的栏目数据
	function create_publish()
	{
		$contentinfo  = array();
		//主模型数据只入库一次 contentinfo = array('contentid', 'pages');
		$contentinfo = $this->create_content($this->colids[0]);
		foreach($this->colids as $colid)
		{	
			//返回发布信息 此处为插入的数据
			$insert_mapid = $this->createContentMap($colid, $contentinfo);
			//cms设置是否开启了同步推送 变量标志必须为is_open
			if($this->argsetting['is_open'])
			{
				$sql = 'SELECT * FROM '.DB_PREFIX.'column_map WHERE local_cms_id = '.intval($colid);
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$this->db->query("INSERT INTO ".DB_PREFIX.'remote_cms_queue SET content_map_id = '.$insert_mapid.', remote_cms_id='.intval($row['remote_cms_id']));
				}
			}
			$this->addItem(array('cms_contentmap_id' => $insert_mapid, 'pub_time'=>TIMENOW));
		}
		//记录至队列表
		//$this->_debug_($pub_return_info);
		$this->mkqueue->build_queue();
		$this->output();
	}
	//已发布数据的更新
	function update_publish()
	{
		//如果已经发布数据则更新主模型数据
		if($this->input['cms_contentmap_id'])
		{
			$mapid = is_array($this->input['cms_contentmap_id'])  ? implode(',', $this->input['cms_contentmap_id']) : $this->input['cms_contentmap_id'];
			$title = trim(urldecode($this->data_info['title']));
			if($title)
			{
				$this->db->query("UPDATE ".DB_PREFIX."contentmap SET title='".$title."', orderid='{$this->data_info['orderid']}' WHERE id IN({$mapid})");
			}
			$_mapid = explode(',',$mapid);
			if($_mapid && is_array($_mapid))
			{
				foreach($_mapid as $v)
				{
					$this->mkqueue->set($v, 0);
				}
			}
			$sql = 'SELECT * FROM '.DB_PREFIX.'contentmap WHERE id = '.intval($_mapid[0]);
			$record = $this->db->query_first($sql);
			//此处是更新主模型数据 传递了内容ID 和 索引图片
			$this->create_content($this->colids[0], $record['contentid'], $record['indexpic']);
			//重新入队列更新内容
			$this->mkqueue->build_queue();
			$this->addItem(array('cms_contentmap_id' => intval($this->input['cms_contentmap_id'])));
			//return $_mapid ? $_mapid  : false;
		}
		else
		{
			$this->addItem(array('cms_contentmap_id' => 0));
		}
		$this->output();
	}
	//取消发布方法
	//传递INPUT['cancell'] = array('contentmapid'=>'columnid')
	function cancell_publish()
	{
		if($this->input['cms_contentmap_id'])
		{
			$mapid = is_array($this->input['cms_contentmap_id']) ? implode(',', $this->input['cms_contentmap_id']) : urldecode($this->input['cms_contentmap_id']);
			$sql = 'SELECT id,indexpic,columnid,staticfile,modeid,contentid FROM '.DB_PREFIX.'contentmap WHERE id IN('.$mapid.') AND linktype=1';
			$q = $this->db->query($sql);
			$materialids = array();
			$queue = array();
			$columnid = 0;
			while($row = $this->db->fetch_array($q))
			{
				$columnid = $row['columnid'];
				$queue[$row['id']] = $row;
				$modeid = $row['modeid'];
				$contentid[] = $row['contentid'];
				$materialids[] = $row['indexpic'];
			}
			//删除素材
			if($materialids)
			{
				$this->delete_material($materialids);
			}
			//删除主模型数据
			if($modeid)
			{
				$is_delete = false;
				$sql = 'SELECT cm.modesign FROM '.DB_PREFIX.'mode_apply ma LEFT JOIN '.DB_PREFIX.'contentmode cm ON cm.colmodeid = ma.colmodeid WHERE ma.applyid = '.intval($modeid);
				$table = $this->db->query_first($sql);
				if($table)
				{
					$is_delete = $this->delete_content($contentid, $table['modesign']);
				}
				if(!$is_delete)
				{
					$this->addItem(array('success' => 0));
					$this->output();
				}
			}
			//取出站点信息并初始化队列
			if($columnid)
			{
				$sql = 'SELECT s.* FROM '.DB_PREFIX.'siteconf s LEFT JOIN '.DB_PREFIX.'column c ON c.siteid = s.siteid WHERE c.columnid = '.$columnid;
				$this->site_info = $this->db->query_first($sql);
				$this->mkqueue = new mkqueue($this->site_info);
				$this->mkqueue->cancell_queue($queue);
			}
			$this->db->query("DELETE FROM ".DB_PREFIX."contentmap WHERE id IN(". $mapid . ")");
			$this->addItem(array('success' => intval($this->input['cms_contentmap_id'])));
		}
		else
		{
			$this->addItem(array('success' => 0));
		}
		$this->output();
	}
	//删除素材
	function delete_material($materialid)
	{
		if(!$materialid)
		{
			return false;
		}
		$materialid = is_array($materialid) &&  $materialid ? implode(',', $materialid) : $materialid;
		include_once (ROOT_DIR . 'lib/class/curl.class.php');
		$curl = new curl(LIVCMS_HOST, LIVCMS_PLUGIN_DIR);
		$curl->initPostData();
		//file_put_contents(ROOT_DIR.'uploads/tmp/1.txt', '素材ID：' . $materialid . '\r\n');
		$curl->addRequestData('materialid', $materialid);
		$del = $curl->request('delmaterial.php');
		//file_put_contents(ROOT_DIR.'uploads/tmp/1.txt', '素材路径：' . $del);
		if(!$del)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'material WHERE materialid IN('.$materialid.')';
		$this->db->query($sql);
		return true;
	}
	//删除内容
	function delete_content($contentid, $table)
	{
		if(!$contentid || !$table)
		{
			return false;
		}
		$contentid = is_array($contentid) &&  $contentid ? implode(',', $contentid) : $contentid;
		$sql = 'DELETE FROM '.DB_PREFIX.$table.' WHERE '.$table.'id IN('.$contentid.')';
		$this->db->query($sql);
		return true;
	}
	//主模型内容数据
	//@$primary_id 内容是更新还是插入
	//@$materialid 素材是更新还是新增
	function create_content($colid, $primary_id = 0, $materialid = 0)
	{
		$fields = $this->col_fields[$colid]['fields'];
		$table = $this->col_fields[$colid]['table_name'];
		$trans_field = $this->col_fields[$colid]['trans_field'];
		if(!$fields || empty($fields))
		{
			$this->errorOutput(NO_MODEL);
		}
		$where = '';
		if($primary_id)
		{
			$op = 'UPDATE ';
			$where = ' WHERE '.$table . 'id' . '=' . $primary_id;
		}
		else
		{
			$op = 'INSERT INTO ';
		}
		$sql = $op . DB_PREFIX.$table.' SET ';
		//主模型数据入库开始
		$is_trans = false;
		foreach ($fields as $field=>$type)
		{
			if(!$this->data_info[$field])
			{
				continue;
			}
			//图片的本地化
			if($type == 'file')
			{
				if(!$curl)
				{
					include_once (ROOT_DIR . 'lib/class/curl.class.php');
					$curl = new curl(LIVCMS_HOST, LIVCMS_PLUGIN_DIR);
				}
				$curl->initPostData();
				$curl->addRequestData('url', $this->data_info[$field]);
				$curl->addRequestData('columnid', $colid);
				$curl->addRequestData('siteid', $this->site_info['siteid']);
				//file_put_contents('/web/livsns/uploads/m.txt',$this->site_info['siteid']);
				$curl->addRequestData('materialid', $materialid);
				$curl->addRequestData('fname', '');
				//图片本地化返回的是素材ID 和 类型
				$material = explode('|',$curl->request('filetolocal.php'));
				$materialid = $material[0];
				$this->data_info['liv_material_suffix'] = $material[1];
				$this->data_info['indexpic'] = $materialid;
				//更新存在图片需要更新contentmap表
				if($this->data_info['indexpic'] && intval($this->input['cms_contentmap_id']))
				{
					$this->db->query('UPDATE '.DB_PREFIX.'contentmap set indexpic = '.$this->data_info['indexpic'].',liv_material_suffix="'.$this->data_info['liv_material_suffix'].'" where id='.intval($this->input['cms_contentmap_id']));
				}
				$sql .= $field . "={$materialid},";
			}
			else if($field == $trans_field)
			{
				//主模型数据字段内容为空 插入至转存表
				$is_trans = true;
				$sql .= $field . "='',";
			}
			else
			{
				$sql .= $field . "='{$this->data_info[$field]}',";
			}
		}
		$sql = $sql . 'applyid = '.$this->col_fields[$colid]['applyid'] . $where;
		//主模型数据入库结束
		$this->db->query($sql);
		if(($newid = $this->db->insert_id()) || $primary_id)
		{
			$newid = $newid ? $newid : $primary_id;
			if($is_trans)
			{
				$pages = $this->transStore($colid, $newid,$this->data_info[$this->col_fields[$colid]['trans_field']], $primary_id);
			}
		}
		return array('contentid'=>$newid,'pages'=>$pages);
	}
	function _debug_($data)
	{
		if(is_array($data))
		{
			echo("----------------------------调试开始----------------------------");
			echo "<pre>";
			print_r($data);
			echo "</pre>";
			exit("----------------------------调试结束----------------------------");
		}
		exit($data);
	}
}
$out = new content();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'unknown';
}
$out->$action();
?>