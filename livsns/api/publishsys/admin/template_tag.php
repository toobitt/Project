<?php

require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID', 'template_tag'); //模块标识

class templateTagApi extends nodeFrm
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/template_tag.class.php');
        $this->obj = new templateTag();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function show()
    {
//    	if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('template_tag',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		$ret = array();
        $condition = $this->get_condition();
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
        $limit     = " limit {$offset}, {$count}";
        $ret       = $this->obj->show($condition, $limit);
		if($ret)
		{
			$this->addItem($ret);
		}
		else
		{
			$this->addItem(array());
		}
        
        $this->output();
    }

    public function detail()
    {
    	$id = $this->input['id'];
    	$sql = 'SELECT *
				FROM '.DB_PREFIX.'template_tag WHERE id = '. $id;
		$ret = $this->db->query_first($sql);
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
        $sql        = 'SELECT count(*) as total from ' . DB_PREFIX . 'template_tag WHERE 1 ' . $this->get_condition();
        $template_tag_total = $this->db->query_first($sql);
        echo json_encode($template_tag_total);
    }


	 /* 检索条件应用，模块,操作，来源，用户编号，用户名
     * @name get_condition
     * @access private
     * @author gaoyuan
     * @category hogesoft
     * @copyright hogesoft
     */
    public function get_condition()
    {
        $condition = '';
        if ($this->input['k'])
        {
            $condition .= ' AND title LIKE "%' . trim($this->input['k']) . '%"' ;
        }
        return $condition;
    }
	
}

$out    = new templateTagApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
