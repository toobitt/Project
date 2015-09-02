<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class content_syn extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //DB_PREFIX
    function content($relation_id)
    {
        //get content_client_relation data by relation_id
        $sql  = "select * from " . DB_PREFIX . "content_client_relation where relation_id=" . $relation_id;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $content_client_relation[] = $row;
        }
        if (!$content_client_relation)
        {
            $this->delete_syn_content($relation_id);
            exit;
        }
        
        //get content_relation data by relation_id
        $sql              = "select * from " . DB_PREFIX . "content_relation where id=" . $relation_id;
        $content_relation = $this->db->query_first($sql);
        if (!$content_relation)
        {
            $this->delete_syn_content($relation_id);
            exit;
        }
        
        //get content data by content_id
        $sql     = "select * from " . DB_PREFIX . "content where id=" . $content_relation['content_id'];
        $content = $this->db->query_first($sql);
        if (!$content)
        {
            return 'NO_CONTENT';
        }

        //select site_id,replace source site_id
        $site_idarr[] = $content_relation['site_id'];
        $site_idarr[] = $content['site_id'];
        $sql          = "select id,syn_id from " . DB_PREFIX . "site where id in (" . implode(',', $site_idarr) . ")";
        $info         = $this->db->query($sql);
        while ($row          = $this->db->fetch_array($info))
        {
            $site_arr[$row['id']] = $row;
        }

        //select column_id,replace source column_id
        $column_idarr[] = $content_relation['column_id'];
        $column_idarr[] = $content['column_id'];
        $sql            = "select id,syn_id from " . DB_PREFIX . "column where id in (" . implode(',', $column_idarr) . ")";
        $info           = $this->db->query($sql);
        while ($row            = $this->db->fetch_array($info))
        {
            $column_arr[$row['id']] = $row;
        }
        
        $content['site_id']            = $site_arr[$content['site_id']]['syn_id'];
        $content_relation['site_id']   = $site_arr[$content_relation['site_id']]['syn_id'];
        $content['column_id']          = $column_arr[$content['column_id']]['syn_id'];
        $content_relation['column_id'] = $column_arr[$content_relation['column_id']]['syn_id'];

        $bundle_id = $content['bundle_id'];
        $module_id = $content['module_id'];
        $struct_id = $content['struct_id'];

        $sql    = "select id,child_table from " . DB_PREFIX . "content_field where bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND (struct_ast_id='' OR struct_ast_id is NULL)";
        $struct = $this->db->query_first($sql);
        if (!$struct)
        {
            return 'NO_STRUCT';
        }

        $struct_table_name = $bundle_id . '_' . $module_id . '_' . $struct_id;

        $sql            = "select * from " . DB_PREFIX . $struct_table_name . " where id=" . $content['expand_id'];
        $struct_content = $this->db->query_first($sql);

        if ($struct['child_table'])
        {
            $struct_ast_table_name = $bundle_id . '_' . $module_id . '_' . $struct_id .'_'. $struct['child_table'];

            $sql                            = "select * from " . DB_PREFIX . $struct_ast_table_name . " where expand_id=" . $content['expand_id'];
            $struct_ast_content             = $this->db->fetch_all($sql);
            $result[$struct_ast_table_name] = $struct_ast_content;
        }

        $result['content_client_relation'] = $content_client_relation;
        $result['content_relation']        = $content_relation;
        $result['content']                 = $content;
        $result[$struct_table_name]        = $struct_content;

        include(CUR_CONF_PATH . 'lib/publishcontent_syn.class.php');
        $cs_obj = new publishcontent_syn();
        $result = $cs_obj->content($result);
    }
    
    function delete_syn_content($relation_id)
    {
        //查询出这个所有站点id
        $siteids = $this->get_all_sites();
        if (!$siteids)
        {
            return false;
        }
        $result['_site_syn_ids'] = $siteids;
        $result['rid'] = $relation_id;
        include(CUR_CONF_PATH . 'lib/publishcontent_syn.class.php');
        $cs_obj = new publishcontent_syn();
        $cs_obj->delete_syn_content($result);
    }
    
    function update_syn_weight($data)
    {
        $siteids = $this->get_all_sites();
        if (!$siteids)
        {
            return false;
        }
        include(CUR_CONF_PATH . 'lib/publishcontent_syn.class.php');
        $cs_obj = new publishcontent_syn();
        $cs_obj->update_syn_weight($siteids,$data);
    }
    
    function update_syn_content($rid,$data)
    {
        $siteids = $this->get_all_sites();
        if (!$siteids)
        {
            return false;
        }
        include(CUR_CONF_PATH . 'lib/publishcontent_syn.class.php');
        $cs_obj = new publishcontent_syn();
        $cs_obj->update_syn_content($siteids,$data,$rid);
    }
    
    function get_all_sites()
    {
        $siteids = '';
        $sql  = "SELECT id,syn_id FROM " . DB_PREFIX . "site ORDER BY id limit 100";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            if ($row['syn_id'])
            {
                $siteids .= $tag . $row['syn_id'];
                $tag = ',';
            }
        }
        return $siteids;
    }
    
    function get_content_in_site($content_id,$_site_syn_ids)
    {
        $sql   = "SELECT id,expand_id,content_fromid FROM " . DB_PREFIX . "content WHERE syn_cid=" . $content_id;
        $sql .= " AND site_id=" . $_site_syn_ids;
        return $this->db->query_first($sql);
    }
    
    function get_content_relation_in_site($rid,$_site_syn_ids)
    {
        $sql   = "SELECT id,content_id,content_fromid,bundle_id,module_id,struct_id FROM " . DB_PREFIX . "content_relation r WHERE r.site_id in (".$_site_syn_ids.") AND r.syn_rid=".$rid;
        return $this->db->query_first($sql);
    }
    
    function get_content_relation_by_columnid($content_id,$column_id)
    {
        $sql   = "SELECT id FROM " . DB_PREFIX . "content_relation WHERE column_id='".$column_id."' AND content_id=" . $content_id;
        return $this->db->query_first($sql);
    }
    
    function get_struct_content($content_id,$column_id)
    {
        $sql   = "SELECT id FROM " . DB_PREFIX . "content_relation WHERE column_id='".$column_id."' AND content_id=" . $content_id;
        return $this->db->query_first($sql);
    }
    
    function delete_content_relation($rid)
    {
        $sql = "DELETE r.*,cr.* FROM ".DB_PREFIX."content_client_relation cr LEFT JOIN ".DB_PREFIX."content_relation r ON cr.relation_id=r.id WHERE r.id=".$rid;
        $this->db->query($sql);
        return true;
    }

}

?>
