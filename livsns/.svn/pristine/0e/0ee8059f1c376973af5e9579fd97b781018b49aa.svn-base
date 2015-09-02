<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function image|getMaterialById|getThumbById|unknow
*
* $Id: material.php 6577 2012-04-26 07:04:14Z wangleyuan $
***************************************************************************/
define('MOD_UNIQUEID','material');
require('global.php');
class materialApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/material.class.php');
		$this->obj = new material();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function show(){}
	public function detail(){}
	public function count(){}
    
    public function get_material_by_ids() {
        if (!$this->input['material_ids']) {
            $this->errorOutput(NO_IDS);
        }
        $material_ids = explode(',', $this->input['material_ids']);
        $material_ids = implode("','", $material_ids);
        $sql = "SELECT id,bundle_id,filepath, filename,type,mark,filesize,create_time 
                FROM ".DB_PREFIX."material WHERE 1 AND id IN('".$material_ids."')";
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['host'] = hg_getimg_host($row['bs']);
            $row['dir'] = app_to_dir($row['bundle_id'],$row['mark']);
            $ret[] = $row;
        }
        $this->addItem($ret);
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

	protected function verifyToken()
	{
	}
}

$out = new materialApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>
