<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID', 'column');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
class columnApi extends adminBase
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		$this->pub_content = new publishcontent();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$result = $support_client_arr = array();
		$condition = '';
		if($site_id = intval($this->input['site_id']))
		{
			$condition .= " AND c.site_id=".$site_id;
		}
		if($id = urldecode($this->input['id']))
		{
			$condition .= " AND c.id in (".$id.")";
		}
		else
		{
			if(isset($this->input['fid']))
			{
				if ($this->input['fid'] > -1)
				{
					$fid = $this->input['fid'];
					$condition .= " AND c.fid in (".$fid.")";
				}
			}
		}
		if(isset($this->input['client_type']) && $this->input['client_type'])
		{
			$support_client_arr = explode(',',urldecode($this->input['client_type']));
		}
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		$count = $count ? $count : 10;
		
		$sql = "SELECT s.sub_weburl,s.weburl,c.id,c.site_id,c.name,c.fid,c.parents,c.childs,c.depath,c.childdomain,c.father_domain,c.relate_dir,c.colindex,c.is_last,c.keywords,c.content AS brief,c.support_content_type,c.support_client FROM ".
				DB_PREFIX."column c LEFT JOIN ".DB_PREFIX."site s ON c.site_id=s.id  WHERE 1".$condition . ' ORDER BY c.order_id DESC LIMIT ' . $offset . ',' . $count;
		$info = $this->db->query($sql);
		$columnids = array();
		$content_type_tag = '';
		while($row = $this->db->fetch_array($info))
		{
			$row['column_url'] = mk_column_url($row);
			
			if(empty($support_client_arr))
			{
				$result[] = $row;
				$columnids[] = $row['id'];
				if($row['support_content_type'])
				{
					$content_type .= $content_type_tag.$row['support_content_type'];
					$content_type_tag = ',';
				}
			}
			else
			{
				if(array_intersect(explode(',',$row['support_client']),$support_client_arr))
				{
					$result[] = $row;
					$columnids[] = $row['id'];
					if($row['support_content_type'])
					{
						$content_type .= $content_type_tag.$row['support_content_type'];
						$content_type_tag = ',';
					}
					
				}
			}
		}
		if (!$columnids)
		{
			$this->errorOutput('NO_COLUMNS');
		}
		
		//获取内容标识，名称
		//$content_type_data = $this->pub_content->content_field_by_ids($content_type);
		
		$sql = "SELECT * FROM ".DB_PREFIX."column_icon WHERE column_id in(".implode(',', $columnids).")";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$i_d = $a = $n_a = array();
			if(!empty($row['icon_default']))
			{
				$icon_default = unserialize($row['icon_default']);
				$i_d['host'] = $icon_default['host'];
				$i_d['dir'] = $icon_default['dir'];
				$i_d['filename'] = $icon_default['filename'];
				$i_d['filepath'] = $icon_default['filepath'];
			}
			if(!empty($row['activation']))
			{
				$activation = unserialize($row['activation']);
				$a['host'] = $activation['host'];
				$a['dir'] = $activation['dir'];
				$a['filename'] = $activation['filename'];
				$a['filepath'] = $activation['filepath'];
			}
			if(!empty($row['no_activation']))
			{
				$no_activation = unserialize($row['no_activation']);
				$n_a['host'] = $no_activation['host'];
				$n_a['dir'] = $no_activation['dir'];
				$n_a['filename'] = $no_activation['filename'];
				$n_a['filepath'] = $no_activation['filepath'];
			}
			$result_icon[$row['column_id']]['icon_'.$row['client']]['default'] = empty($i_d)?'':$i_d;
			$result_icon[$row['column_id']]['icon_'.$row['client']]['activation'] = empty($a)?'':$a;
			$result_icon[$row['column_id']]['icon_'.$row['client']]['no_activation'] = empty($n_a)?'':$n_a;
		}
		
		foreach ($result as $key => $value) 
		{
			if($value['support_content_type'])
			{
				$support_content_type_arr = explode(',',$value['support_content_type']);
				$value['support_content_type'] = array();
				foreach($support_content_type_arr as $content_type_id)
				{
					$value['support_content_type'][] = $content_type_data[$content_type_id];
				}
			}
			else
			{
				$value['support_content_type'] = array();
			}
			
			$value['icon'] = $result_icon[$value['id']];
			$this->addItem($value);
		}
		$this->output();
	}
	
	/**
	 * is_site有值表示column_id记录的是站点id
	 * is_site没有值表示column_id记录的是栏目id
	 * */
	public function column_support_content()
	{
		$ids = $this->input['id'];
		$is_site = $this->input['is_site'];
		if(!$ids)
		{
			$this->addItem('error');
			$this->output();
		}
		if($is_site)
		{
			$sql = "SELECT id,site_name as name,support_content_type FROM ".DB_PREFIX."site WHERE id in(".$ids.")";
		}
		else
		{
			$sql = "SELECT id,name,support_content_type FROM ".DB_PREFIX."column WHERE id in(".$ids.")";
		}
		$info = $this->db->query($sql);
		$tag = '';
		while($row = $this->db->fetch_array($info))
		{
			if($row['support_content_type'])
			{
				$content_type .= $tag.$row['support_content_type'];
				$result[$row['id']]['support_content_type'] = explode(',',$row['support_content_type']);
			}
			else
			{
				$result[$row['id']]['support_content_type'] = array();
			}
			$tag = ',';
			$result[$row['id']]['name'] = $row['name'];
		}
		
		//获取内容标识，名称
		$content_type_data = $this->pub_content->content_field_by_ids($content_type);
		
		foreach($result as $k=>$v)
		{
			foreach($v['support_content_type'] as $kk=>$vv)
			{
				$result[$k]['support_content_type'][$kk] = $content_type_data[$vv]['bundle_id'].'/'.$content_type_data[$vv]['module_id'].'/'.$content_type_data[$vv]['struct_id'];
			}
		}
		$this->addItem($result);
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

$out = new columnApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
