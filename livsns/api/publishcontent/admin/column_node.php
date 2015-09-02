<?php

require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'column_node');
define('MOD_UNIQUEID', 'column');

class column_node extends nodeFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    //栏目编辑里获取的栏目
    public function get_node()
    {
        if($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $auth_node = $this->user['prms']['publish_prms'];
            $auth_node_str = $auth_node ? implode(',', $auth_node) : '';
            if(!$auth_node_str && !$this->user['prms']['site_prms'])
            {
                $no_auth = 1;
            }
            $auth_node_parents = array();
            if($auth_node_str)
            {
                $sql = 'SELECT id,parents FROM ' . DB_PREFIX . 'column WHERE id IN('.$auth_node_str.')';
                $query = $this->db->query($sql);
                while($row = $this->db->fetch_array($query))
                {
                    $auth_node_parents[$row['id']] = explode(',', $row['parents']);
                }
            }
        }
        $con    = '';
        $site_id = intval($this->input['site_id']);
        $fid    = intval($this->input['fid']);
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count  = $this->input['count'] ? $this->input['count'] : 1000;
        if (!$no_auth)
        {
            $sql    = "select c.*,s.sub_weburl,s.weburl from " . DB_PREFIX . "column c left join ".DB_PREFIX."site s on c.site_id=s.id where 1 ";
            if ($site_id)
            {
                $sql .= ' AND c.site_id=' . $site_id;
            }
            $sql .= ' AND c.fid=' . $fid;
            $sql .= " order by c.order_id ";
            $sql .= " limit $offset,$count ";
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $row['is_auth'] = 1;
                if( $auth_node && $this->user['group_type'] > MAX_ADMIN_TYPE)
                {
                    ###############非管理员用户数据过滤开始
                    $row['is_auth'] = 0;
                    //节点自身显示
                    if(in_array($row['id'], $auth_node))
                    {
                        $row['is_auth'] = 1;
                    }
                    //
                    if(!$row['is_auth'] && $auth_node_parents)
                    {
                        //父级节点显示
                        foreach ($auth_node_parents as $auth_node_id=>$auth_node_parent)
                        {
                            if(in_array($row['id'], $auth_node_parent))
                            {
                                $row['is_auth'] = 2;
                                break;
                            }
                        }
                        //孩子节点显示
                        if(array_intersect(explode(',', $row['parents']), $auth_node))
                        {
                            $row['is_auth'] = 3;
                        }
                    }
                    if($this->user['prms']['site_prms'] && in_array($row['site_id'], $this->user['prms']['site_prms']))
                    {
                        $row['is_auth'] = 1;
                    }
                    ###############非管理员用户数据过滤结束
                }

                if ($row['is_auth'])
                {
                    $r = array();
                    $r['id']      = $row['id'];
                    $r['name']    = $row['name'];
                    $r['fid']     = $row['fid'];
                    $r['childs']  = $row['childs'];
                    $r['parents'] = $row['parents'];
                    $r['depath']  = $row['depath'];
                    $r['is_last'] = $row['is_last'];
                    $r['site_id'] = $row['site_id'];
                    $r['url'] = mk_column_url($row+ array('sub_weburl' => $row['sub_weburl'], 'weburl' => $row['weburl']),false,true);
                    $column[] = $r;
                    $rowlast = $row;
                }
            }
        }
        if(!$site_id)
        {
            if(!$rowlast['site_id'])
            {
                $this->addItem(array());
                $this->output();
            }
            $site_id = $rowlast['site_id'];
        }
        
        //取栏目的page_id
        include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
        $this->pub_sys = new publishsys();
        $page_data     = $this->pub_sys->get_page_by_sign('column', $site_id);
        if ($page_data['id'])
        {
            $result['page'] = 'page_data_id'.$page_data['id'].'_';
            $result['main_page'] = 'page_id'.$page_data['id'];
        }
        $result['site_id'] = $site_id;
        if($this->settings['App_mkpublish'])
        {
            $is_open_mk = 1;
            if ($this->user['group_type'] > MAX_ADMIN_TYPE)
            {
                $this->user['prms']['app_prms']['mkpublish']['action'] = is_array($this->user['prms']['app_prms']['mkpublish']['action']) ? $this->user['prms']['app_prms']['mkpublish']['action'] : array();
                if (!in_array('manage', $this->user['prms']['app_prms']['mkpublish']['action']))
                {
                    $is_open_mk = 0;
                }
            }
        }
        else
        {
            $is_open_mk = 0;
        }
        $result['is_open_mk'] = $is_open_mk;
        $result['column'] = $column;
        $this->addItem($result);
        $this->output();
    }

    //发布库节点获取的栏目
    public function show()
    {
        $con    = '';
        $fid    = intval($this->input['fid']);
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count  = $this->input['count'] ? $this->input['count'] : 1000;
        $sql    = "select * from " . DB_PREFIX . "column where 1 ";
        if ($site_id)
        {
            $sql .= ' AND site_id=' . $site_id;
        }
        $sql .= ' AND fid=' . $fid;
        $sql .= " order by order_id ";
        $sql .= " limit $offset,$count ";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $r['id']      = $row['id'];
            $r['name']    = $row['name'];
            $r['fid']     = $row['fid'];
            $r['childs']  = $row['childs'];
            $r['parents'] = $row['parents'];
            $r['depath']  = $row['depath'];
            $r['is_last'] = $row['is_last'];
            $this->addItem($r);
        }
        $this->output();
    }

    //编辑
    public function detail()
    {
        $this->setXmlNode('nodes', 'node');
        $this->setNodeTable('column');
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
        if (!$id)
        {
            $this->errorOutput(NO_ID);
        }
        $this->setXmlNode('nodes', 'node');
        $this->setNodeTable('column');
        $this->initNodeData();
        $this->getMultiNodesInfo($id);
        $this->output();
    }

    public function get_selected_column_path()
    {
        $this->setNodeTable('column');
        $ids = urldecode($this->input['id']);
        if (!$ids)
        {
            $this->output();
        }
        $tree = $this->getParentsTreeById($ids, true, ',site_id');
        if ($tree)
        {
            foreach ($tree as $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    public function get_authored_columns()
    {
        $conditions = '';
        $this->input['count'] = $this->input['count'] ? intval($this->input['count']) : 1000;
        $this->input['fid'] = intval($this->input['fid']);
        $this->initNodeData();
        $this->setXmlNode('columns', 'column');
        $this->setNodeTable('column');
        $this->setNodeID(intval($this->input['fid']));
        $this->setNodeVar('column');
        if(!$this->input['fid'])
        {
            $conditions           = " and site_id = " . ($this->input['siteid'] ? intval($this->input['siteid']) : 1);
        }
        
        $this->getNodeChilds($conditions);
        $this->output();
    }

}

include(ROOT_PATH . 'excute.php');
?>
