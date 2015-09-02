<?php
require('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishsys.class.php');
require_once(ROOT_PATH.'lib/class/publishcms.class.php');
define('MOD_UNIQUEID','column_rebuild');//模块标识
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
		include(CUR_CONF_PATH . 'lib/column.class.php');
		$this->obj = new column();
		$this->pub_cms = new publishcms();
		//如果是从部署那边来访问，则切换input值
		if(!empty($this->input['pub_input']))
		{
			$this->input = $this->input['pub_input'];
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function addwx()
	{
		//查询出栏目
		$column_id = intval($this->input['id']);
		$column_data = $this->obj->get_column(' id,name,is_last,column_dir,parents,content,fid,site_id ', ' AND id>75',0, 1000);
		foreach($column_data AS $k => $v)
		{
			$columninfo = $v;
			$columndir = $columninfo['column_dir'];
			$columndir = explode('/', $columndir);
			$cdir = $columndir[(count($columndir) - 1)];
			if (is_numeric($cdir))
			{
				$cdir = 'folder' . $cdir;
			}
			elseif(!$cdir)
			{
				$cdir = 'folder' . $v['id'];
			}
			$columndir = '/' . ltrim($cdir, '/');

			$cms_column_data = array(
				'cms_columnid' => $columninfo['id'],
				'name' => $columninfo['name'],
				'brief' => $columninfo['content'],
				'cms_fid' => $columninfo['fid'],
				'cms_siteid' => $columninfo['site_id'],
				'column_dir'=> $columndir,
				'linkurl'=> $columninfo['linkurl'],
				'orderid'=> $columninfo['id'],
				'relate_dir'=> $columninfo['relate_dir'],
				'childdomain'=> $columninfo['childdomain'],
				'colindex' => $columninfo['colindex'],
			);
			$this->pub_cms->insert_cms_column($cms_column_data);
		}
		
	}

	public function show()
	{
		//查询出栏目
		$column_id = intval($this->input['id']);
		$column_data = $this->obj->get_column(' id,name,is_last,column_dir,parents,content,fid,site_id ', ' AND fid=' . $column_id,0, 1000);
		foreach($column_data AS $k => $v)
		{
			$columninfo = $v;
			$columndir = $columninfo['column_dir'];
			$columndir = explode('/', $columndir);
			$cdir = $columndir[(count($columndir) - 1)];
			if (is_numeric($cdir))
			{
				$cdir = 'folder' . $cdir;
			}
			elseif(!$cdir)
			{
				$cdir = 'folder' . $v['id'];
			}
			$columndir = '/' . ltrim($cdir, '/');

			$sql = 'UPDATE ' . DB_PREFIX . "column set column_dir='{$columndir}', depath=" . (count(explode(',', $columninfo['parents'])) - 1) . " WHERE id={$columninfo['id']}";
			echo $sql . "<br />\n";
			$this->db->query($sql);
			$cms_column_data = array(
				'column_id' => $columninfo['id'],
				'name' => $columninfo['name'],
				'brief' => $columninfo['content'],
				'cms_fid' => $columninfo['fid'],
				'cms_siteid' => $columninfo['site_id'],
				'column_dir'=> $columndir,
				'linkurl'=> $columninfo['linkurl'],
			);
			$this->pub_cms->update_cms_column($cms_column_data);
		}
		foreach($column_data AS $k => $v)
		{
			$this->updateFolder($v['id'], $v['column_dir']);
		}
		
	}
	private function updateFolder($id, $dir)
	{
		$column_data = $this->obj->get_column(' id,name,is_last,column_dir,parents,content,fid,site_id ', ' AND fid=' . $id, 0, 1000);
		foreach ($column_data AS $columninfo)
		{
			$columndir = $columninfo['column_dir'];
			$columndir = explode('/', $columndir);
			$cdir = $columndir[(count($columndir) - 1)];
			if (is_numeric($cdir))
			{
				$cdir = 'folder' . $cdir;
			}
			$columndir = $dir . '/' . $cdir;
			$columndir = rtrim($columndir, '/');
			$sql = 'UPDATE ' . DB_PREFIX . "column set column_dir='{$columndir}', depath=" . (count(explode(',', $columninfo['parents'])) - 1) . " WHERE id={$columninfo['id']}";
			echo $sql . "<br />\n";
			$this->db->query($sql);
			$cms_column_data = array(
				'column_id' => $columninfo['id'],
				'name' => $columninfo['name'],
				'brief' => $columninfo['content'],
				'cms_fid' => $columninfo['fid'],
				'cms_siteid' => $columninfo['site_id'],
				'column_dir'=> $columndir,
				'linkurl'=> $columninfo['linkurl'],
			);
//			$this->pub_cms->update_cms_column($cms_column_data);
			if (!$columninfo['is_last'])
			{
				$this->updateFolder($columninfo['id'], $columndir);
			}
		}
	}
	
	//重建relate_dir相对目录 father_domain更新
	public function relate_dir_rebuild( $fid = '',$site = array(),$first = TRUE)
	{
		$column_id = $fid?$fid:intval($this->input['id']);
		
		$site = $site?$site:$this->obj->get_site(' id,sub_weburl ','','id');
		
		if($first)
		{
			$sql = "UPDATE ".DB_PREFIX."column set relate_dir=column_dir";
			$this->db->query($sql);
		}
		
		$column_data = $this->obj->get_column(' id,name,is_last,column_dir,parents,relate_dir,childs,childdomain,father_domain,content,fid,site_id ', " AND fid=" . $column_id,0, 1000);
		foreach($column_data as $k=>$v)
		{
			if($v['childdomain'])
			{
				$sql = "UPDATE ".DB_PREFIX."column SET father_domain='".$v['childdomain']."', relate_dir=RIGHT(relate_dir,LENGTH(relate_dir)-".strlen($v['relate_dir']).") WHERE id in (".$v['childs'].")";
//				echo $sql."<br>";
				$this->db->query($sql);
			}
			else
			{
				if(!$v['fid'])
				{
					$father_domain = '';
				}
				else
				{
					$father_column = $this->obj->get_column_by_id(' id,father_domain ',$v['parents'],'id');
					$parents_arr = explode(',',$v['parents']);
					$father_domain = $this->get_father_domain($site,$father_column,$v['fid']);
				}
				$sql = "UPDATE ".DB_PREFIX."column SET father_domain='".$father_domain."' WHERE id in (".$v['childs'].")";
//				echo $sql."<br>";
				$this->db->query($sql);
			}
		}
		
		$new_column_data = $this->obj->get_column(' id,name,colindex,is_last,column_dir,parents,relate_dir,childs,childdomain,father_domain,content,fid,site_id,linkurl ', " AND fid=" . $column_id,0, 1000);
		foreach($new_column_data as $k=>$v)
		{
			$cms_column_data = array(
				'column_id' => $v['id'],
				'name' => $v['name'],
				'brief' => $v['content'],
				'cms_fid' => $v['fid'],
				'cms_siteid' => $v['site_id'],
				'column_dir'=> $v['column_dir'],
				'relate_dir'=> $v['relate_dir'],
				'linkurl'=> $v['linkurl'],
				'childdomain'=> $v['childdomain'],
				'colindex' => $v['colindex'],
			);
			$this->pub_cms->update_cms_column($cms_column_data);
		}
		
		foreach($column_data as $k=>$v)
		{
			$this->relate_dir_rebuild($v['id'],$site,FALSE);
		}
		
		
		echo "重建成功<br>";
	}
	
	public function get_father_domain($site,$parents_arr,$fid)
	{
		if(!$fid)
		{
			return '';
		}
		if($parents_arr[$fid]['father_domain'])
		{
			return $parents_arr[$fid]['father_domain'];
		}
		else
		{
			$this->get_father_domain($site,$parents_arr,$parents_arr[$fid]['fid']);
		}
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
