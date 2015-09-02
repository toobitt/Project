<?php
/**
 * 拍客导航
 */
define('MOD_UNIQUEID','PaikeMenu');
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class PaikeMenuApi extends nodeFrm
{
    public function __construct()
    {
        global $gGlobalConfig;
        parent::__construct();
        $this->setNodeTable('menu');
        $this->setNodeVar('PaikeMenu');
        include(CUR_CONF_PATH . 'lib/Core.class.php');
        $this->obj = new Core();
    }
    public function __destruct()
    {
        parent::__destruct();
    }
    //默认载入第一维数据
    public function show()
    {
        $condition = $this->get_condition();
        $this->setXmlNode('nodes' , 'node');
        $this->setNodeID(intval($this->input['fid']));
        //$this->addExcludeNodeId($this->input['_exclude']);
        //不需要权限验证
        $this->getNodeChilds($condition,false);// getNodeChilds($condition='',$need_prms = true); 
        $this->output();
    }
    public function show_sort()
    {
        $this->addItem($this->obj->sort('menu',$this->input['id']));
        $this->output();
    }
    
    //编辑
    public function detail()
    {
        $this->initNodeData();
        $this->setNodeID(intval($this->input['id']));
        //查询出当前节点的信息
        $ret = $this->getOneNodeInfo();
        $this->addItem($ret);
        $this->output();
    }
    
    //获取选中的节点
    public function getSelectedNodes()
    {
        $id = trim(urldecode($this->input['id']));
        if(!$id)
        {
            $this->errorOutput(NO_ID);
        }
        $this->getMultiNodesInfo($id);
        $this->output();
    }
    //获取选中的节点树状路径   
    public function get_selected_node_path()
    {
        $ids = urldecode($this->input['id']);
        if(!$ids)
        {
            $this->errorOutput(NO_ID);
        }
        $tree = $this->getParentsTreeById($ids);
        if($tree)
        {
            foreach($tree as $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }
    //获取查询条件
    public function get_condition()
    {
        $condition = '';
        
        return $condition;
    }
    
    //用于分页
    public function count()
    {
        parent::count($this->get_condition());  
    }

}

$out=new PaikeMenuApi();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
    $action='show';
}
$out->$action();
?>