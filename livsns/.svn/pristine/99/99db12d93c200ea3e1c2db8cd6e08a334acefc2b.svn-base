<?php

require('global.php');
define('MOD_UNIQUEID', 'publishcontent'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');

class content_set_syncApi extends adminBase
{

    public function __construct()
    {
        parent::__construct();
        $this->pub_config = new publishconfig();
        include(CUR_CONF_PATH . 'lib/content.class.php');
        $this->obj        = new content();
        include(CUR_CONF_PATH . 'lib/content_syn.class.php');
        $this->syn_obj    = new content_syn();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function content()
    {
        $data                    = $this->input['data'];
        $content_client_relation = $data['content_client_relation'];
        $content_relation        = $data['content_relation'];
        $content                 = $data['content'];

        if (!$content_client_relation || !$content_relation || !$content)
        {
            $this->errorOutput('NO_DATA');
        }

        $bundle_id = $content['bundle_id'];
        $module_id = $content['module_id'];
        $struct_id = $content['struct_id'];

        //content process
        $syn_content       = $this->syn_obj->get_content_in_site($content['id'], $content['site_id']);
        $update_contentarr = $content;
        unset($update_contentarr['id']);
        if ($syn_content)
        {
            //struct_table update process
            $this->struct($bundle_id, $module_id, $struct_id, $data, 'update', $syn_content['expand_id']);

            unset($update_contentarr['syn_cid'],$update_contentarr['expand_id'],$update_contentarr['plan_set_id'],$update_contentarr['bundle_id'],
                    $update_contentarr['module_id'],$update_contentarr['struct_id'],$update_contentarr['content_fromid']);
            $this->obj->update('content', ' id=' . $syn_content['id'], $update_contentarr);
            $content_id = $syn_content['id'];
        }
        else
        {
            //struct_table insert process
            $update_contentarr['expand_id'] = $this->struct($bundle_id, $module_id, $struct_id, $data);

            $update_contentarr['syn_cid'] = $content['id'];
            $content_id                   = $this->obj->insert('content', $update_contentarr);
        }

        //content_relation process
        $syn_content_relation       = $this->syn_obj->get_content_relation_by_columnid($content_id, $content_relation['column_id']);
        $update_content_relationarr = $content_relation;
        unset($update_content_relationarr['id']);
        if ($syn_content_relation)
        {
            unset($update_content_relationarr['syn_rid'],$update_content_relationarr['content_id'],$update_content_relationarr['order_id'],$update_contentarr['bundle_id'],
                    $update_contentarr['module_id'],$update_contentarr['struct_id']);
            $this->obj->update('content_relation', ' id=' . $syn_content_relation['id'], $update_content_relationarr);
            $content_relation_id = $syn_content_relation['id'];
        }
        else
        {
            $update_content_relationarr['syn_rid'] = $content_relation['id'];
            $update_content_relationarr['content_id'] = $content_id;
            
            $content_relation_id                   = $this->obj->insert('content_relation', $update_content_relationarr);
            $this->obj->update('content_relation',' id='.$content_relation_id,array('order_id'=>$content_relation_id));
            
            //add content_client_relation
            //get client
            include(CUR_CONF_PATH . 'lib/client.class.php');
            $client_obj     = new client();
            $client_typearr = $client_obj->get_all_client();
            foreach ($client_typearr as $kct => $vct)
            {
                $content_client_data['client_type'] = $vct['id'];
                $content_client_data['relation_id'] = $content_relation_id;
                $this->obj->insert('content_client_relation', $content_client_data);
            }
        }
    }

    public function struct($bundle_id, $module_id, $struct_id, $data, $type = 'insert', $id = '')
    {
        //struct_table process
        $sql    = "select id,child_table,field from " . DB_PREFIX . "content_field where bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND (struct_ast_id='' OR struct_ast_id is NULL)";
        $struct = $this->db->query_first($sql);
        if($struct['field'])
        {
            $struct['field'] = explode(',',$struct['field']);
        }
        else
        {
            $this->errorOutput('NO_TABLE_FIELD');
        }
            
        $struct_table_name = $bundle_id . '_' . $module_id . '_' . $struct_id;
        if ($data[$struct_table_name] && is_array($data[$struct_table_name]))
        {
            foreach($data[$struct_table_name] as $k=>$v)
            {
                if(!in_array($k,$struct['field']))
                {
                    unset($data[$struct_table_name][$k]);
                }
            }
            unset($data[$struct_table_name]['id']);
            if ($type == 'insert')
            {
               $struct_id_int = $this->obj->insert($struct_table_name, $data[$struct_table_name]);
            }
            else
            {
                $this->obj->update($struct_table_name, ' id=' . $id, $data[$struct_table_name]);
               $struct_id_int = $id;
            }
            $this->struct_ast($bundle_id, $module_id, $struct_id, $data, $type = 'insert', $struct_id_int,$struct);
        }
        if (!$struct_id_int)
        {
            $this->errorOutput('NO_STRUCT_ID');
        }
        return $struct_id_int;
    }

    public function struct_ast($bundle_id, $module_id, $struct_id, $data, $type = 'insert', $expand_id = '',$struct='')
    {
        //struct_ast_table process
        if ($struct)
        {
            $sql        = "select id,child_table,field from " . DB_PREFIX . "content_field where bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND child_table='" . $struct['child_table'] . "'";
            $struct_ast = $this->db->query_first($sql);
            if ($struct_ast['field'])
            {
                $struct_ast['field'] = explode(',', $struct_ast['field']);
            }
            else
            {
                $this->errorOutput('NO_TABLE_FIELD');
            }
            
            $struct_ast_table_name = $bundle_id . '_' . $module_id . '_' . $struct_id . '_' . $struct['child_table'];

            if ($data[$struct_ast_table_name] && is_array($data[$struct_ast_table_name]))
            {
                foreach ($data[$struct_ast_table_name] as $k => $v)
                {
                    if (!in_array($k, $struct_ast['field']))
                    {
                        unset($data[$struct_ast_table_name][$k]);
                    }
                }
                $this->obj->delete_child_expand($struct_ast_table_name, $expand_id, false);
                foreach ($data[$struct_ast_table_name] as $k => $v)
                {
                    $v['expand_id'] = $expand_id;
                    unset($v['id']);
                    $this->obj->insert($struct_ast_table_name, $v);
                }
            }
            return true;
        }
    }

    public function delete()
    {
        $rid           = intval($this->input['rid']);
        $_site_syn_ids = $this->input['_site_syn_ids'];

        $content_relation = $this->syn_obj->get_content_relation_in_site($rid, $_site_syn_ids);
        $bundle_id        = $content_relation['bundle_id'];
        $module_id        = $content_relation['module_id'];
        $struct_id        = $content_relation['struct_id'];
        if (!$content_relation)
        {
            $this->errorOutput('NO_RID');
        }
        if (!$content_relation['content_id'])
        {
            $this->errorOutput('NO_CONTENT_ID');
        }

        $this->syn_obj->delete_content_relation($content_relation['id']);

        //search other relation
        $other_relation = $this->obj->get_content_relation($content_relation['content_id']);
        if ($other_relation)
        {
            $this->addItem('SUCCESS DELETE RELATION ' . $content_relation['id']);
            $this->output();
        }

        //delete content
        $struct_table_name = $bundle_id . '_' . $module_id . '_' . $struct_id;
        $sql               = "SELECT id,child_table FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND (struct_ast_id='' OR struct_ast_id is NULL)";
        $struct            = $this->db->query_first($sql);
        
        if ($struct)
        {
            if($struct['child_table'])
            {
                $struct_ast_table_name = $bundle_id . '_' . $module_id . '_' . $struct_id . '_' . $struct['child_table'];
                $sql                   = "DELETE c.*,s.*,sa.* FROM " . DB_PREFIX . "content c LEFT JOIN " . DB_PREFIX . $struct_table_name . " s ON c.expand_id=s.id LEFT JOIN " . DB_PREFIX . $struct_ast_table_name . " sa ON s.id=sa.expand_id WHERE c.id=" . $content_relation['content_id'];
                $this->db->query($sql);
            }
            else
            {
                $sql = "DELETE c.*,s.* FROM " . DB_PREFIX . "content c LEFT JOIN " . DB_PREFIX . $struct_table_name . " s ON c.expand_id=s.id WHERE c.id=" . $content_relation['content_id'];
                $this->db->query($sql);
            }
        }
        else
        {
            $sql = "DELETE c.* FROM " . DB_PREFIX . "content c WHERE c.id=" . $content_relation['content_id'];
            $this->db->query($sql);
        }
        $this->addItem('SUCCESS DELETE RELATION ' . $content_relation['id'] . ' AND CONTENT ' . $content_relation['content_id']);
        $this->output();
    }
    
    public function update_syn_weight()
    {
        if ($this->mNeedCheckIn && !$this->prms['weight'])
        {
            //$this->errorOutput(NO_OPRATION_PRIVILEGE);
        }
        $_site_syn_ids = $this->input['_site_syn_ids'];
        $data = $this->input['data'];
        if(!$_site_syn_ids)
        {
            $this->errorOutput('NO_SITES');
        }
        if(!is_array($data) || !$data)
        {
            $this->errorOutput('NO_FORMAT');
        }
        
        foreach ($data as $rid => $weight)
        {
            $custom_rids .= $tag.intval($rid);
            $tag = ',';
        }
        if(!$custom_rids)
        {
            $this->errorOutput('NO_CUSTOM_RIDS');
        }
        
        $relation = array();
        $sql = "SELECT id,syn_rid FROM ".DB_PREFIX."content_relation WHERE site_id in (".$_site_syn_ids.") AND syn_rid IN (".$custom_rids.")";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $relation[$row['syn_rid']] = $row;
        }
        
        foreach ($data as $rid => $weight)
        {
            $rid = $relation[$rid]['id'];
            if ($rid)
            {
                $this->obj->update_content_relation_by_id($rid, array('weight' => $weight));
                //$this->obj->update('content_client_relation', 'relation_id=' . $rid, array('weight' => $weight));
            }
        }
        
        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $this->addItem('success');
        $this->output();
    }
    
    public function update_syn_content()
    {
        $_site_syn_ids = $this->input['_site_syn_ids'];
        $data = $this->input['data'];
        $rid = intval($this->input['rid']);
        if(!$_site_syn_ids || !$rid)
        {
            $this->errorOutput('NO_ID');
        }
        if(!is_array($data) || !$data)
        {
            $this->errorOutput('NO_FORMAT');
        }
        
        $relation = array();
        $sql      = "SELECT id,syn_rid FROM " . DB_PREFIX . "content_relation WHERE site_id in (" . $_site_syn_ids . ") AND syn_rid=" . $rid;
        $relation = $this->db->query_first($sql);

        if ($relation['id'])
        {
            $this->obj->update_content_relation_by_id($relation['id'], $data);
            //$this->obj->update('content_client_relation', 'relation_id=' . $rid, array('weight' => $weight));
        }

        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $this->addItem('success');
        $this->output();
    }

}

$out    = new content_set_syncApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
