<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','template_classify');//模块标识
class templateClassifyApi extends nodeFrm
{
	
	public function __construct()
	{	
		$this->setNodeTable('template_classify');
		$this->setNodeVar('template_classify');
		parent::__construct();
		
		include(CUR_CONF_PATH . 'lib/template_classify.class.php');
		$this->obj = new templateClassify();
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
	}

	public function __destruct()
	{	
		parent::__destruct();
	}
	
	public function  show()
	{	
//		if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('template_classify',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		$site_id = $this->input['site_id'] ? $this->input['site_id'] : $this->settings['site_default'];
		$this->user['group_type'] = '1';
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeTable('template_sort');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
        $con = '';
        if($site_id)
        {
            $con = ' AND site_id='.$site_id;
        }
		$this->getNodeChilds($con);
		$this->output();		
	}
	
	
	public function detail()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template_classify',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		if (!$this->input['id'])
		{
			return ;
		}
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'template_sort WHERE id = '.urldecode($this->input['id']);
		$r = $this->db->query_first($sql);
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'template_sort WHERE 1 '.$this->get_condition();;
		$templates_classify_total = $this->db->query_first($sql);
		echo json_encode($templates_classify_total);	
	}
	

	/**
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function get_condition()
	{	
		$condition = '';
		//查询应用分组
		//获取站点id或者父分类id
	 	//$sql = "SELECT site_id FROM ".DB_PREFIX."site_tem_sort WHERE id =".intval($this->input['_id']);
		//$r = $this->db->query_first($sql);
		$site_id = isset($this->input['site_id']) ? $this->input['site_id'] :1;
		if($site_id)
		{
			$condition .=" AND site_id=".$site_id;
		}
		if(intval($this->input['fid']))
		{
			$condition .=" AND fid =". intval($this->input['fid']);
		}
		else
		{
			$condition .=" AND fid = 0";
		}
		if($this->input['k'])
		{
			$condition = " AND name like '%".urldecode($this->input['k'])."%' ";
		}
		return $condition;
	}
}
	
$out = new templateClassifyApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
