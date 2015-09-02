<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class templateApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/column.class.php');
		$this->obj= new column();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	function show_site()
	{				
		$sitedata = $this->obj->get_site("id,site_name",'');
		//file_put_contents('1.txt',var_export($this->input,true));
		$fid = $this->input['fid'];
		$flag = $fid?1:'';
		if($fid)
		{
			if(strstr($fid,"site"))
			{
				$site_id = str_replace('site','',$fid);
				$fid = 0;
			}
			else
			{
				$sql = "SELECT site_id FROM ".DB_PREFIX."site_tem_sort WHERE id=".$fid;
				$re = $this->db->query_first($sql);
				$site_id = $re['site_id'];
			}
		}
		if($flag)
		{	
			$this->setXmlNode('nodes' , 'node');
			$this->setNodeTable('site_tem_sort');
			$this->setNodeID($fid);
			$this->addExcludeNodeId($this->input['_exclude']);
			$this->getNodeChilds(' AND site_id ='.$site_id);
		}
		else
		{
			foreach($sitedata as $k=>$v)
			{
				$m = array('id'=>'site'.$v['id'],"name"=>$v['site_name'],"fid"=>$fid,"depth"=>1 );
				//判断站点下有无模板
				$sql = "select * from ".DB_PREFIX."site_tem_sort where site_id=".$v['id'];
				$info = $this->db->fetch_all($sql);
				if(empty($info))
				{
					$m['is_last'] = 1;
				}
				
				$this->addItem($m);
			}
		}
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

$out = new templateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>
