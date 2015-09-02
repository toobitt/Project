<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH . "global.php");
require(CUR_CONF_PATH . "lib/functions.php");
define('MOD_UNIQUEID', 'column');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');

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
        $result             = $support_client_arr = array();
        $condition          = '';
        if ($site_id            = intval($this->input['site_id']))
        {
            $condition .= " AND c.site_id=" . $site_id;
        }

        if ($id = urldecode($this->input['id']))
        {
            $top_column = intval($this->input['top_column']);
            if ($top_column)
            {
                $sql  = 'SELECT parents FROM ' . DB_PREFIX . "column  WHERE id=" . intval($id);
                $info = $this->db->query_first($sql);
                if ($info['parents'])
                {
                    $parents = explode(',', $info['parents']);
                    $id      = $parents[count($parents) - 1];
                }
            }
            
            $top_child = intval($this->input['top_child']);
            if ($top_child)
            {
				$idstr = $id;
				$id    = explode(',', $id);
                $sql  = 'SELECT childs FROM ' . DB_PREFIX . "column  WHERE id IN ('" . implode("','", $parents) . "') AND fid=0";
                $info = $this->db->query_first($sql);             
				if ($info['childs'])
                {
					$condition .= " AND c.id in (" . $info['childs'] . ")";   
					$specorder = 'c.fid ASC,';
                }
                else
                {
					$condition .= " AND 0 ";   
                }
            }
            else
            {
				$idstr = $id;
				$id    = explode(',', $id);
				$condition .= " AND c.id in ('" . implode("','", $id) . "')";
            }
        }
        else
        {
            $column_name = trim($this->input['column_name']);
            if ($column_name)
            {
                $fid        = intval($this->input['fid']);
                if (!$this->input['onlyfid'])
                {
					$tmp_column = array($fid);
					$sql        = 'SELECT parents FROM ' . DB_PREFIX . "column  WHERE id=" . $fid;
					$info       = $this->db->query_first($sql);
					if ($info)
					{
						$tmp_column = array($info['parents']);
						$sql  = 'SELECT id FROM ' . DB_PREFIX . "column  WHERE fid IN (" . $info['parents'] . ')';
						$info = $this->db->query($sql);
						while ($row  = $this->db->fetch_array($info))
						{
							$tmp_column[] = $row['id'];
						}
					}
					$this->input['fid'] = implode(',', $tmp_column);
                }
            }
            if (isset($this->input['fid']))
            {
                if ($this->input['fid'] > -1)
                {
                    $fid = $this->input['fid'];
                    $fid = explode(',', $fid);
                    $condition .= " AND c.fid in ('" . implode("','", $fid) . "')";
                }
            }
            if ($column_name)
            {
                $column_name = explode(',', $column_name);
                $condition .= " AND c.name in ('" . implode("','", $column_name) . "')";
            }
            if($keywords = trim($this->input['keywords']))
            {
                $condition .= " AND c.keywords like \"" . $keywords . "\" ";
            }
        }
        $offset = intval($this->input['offset']);
        $count  = intval($this->input['count']);
        $count  = $count ? $count : 10;
        if ($this->input['sort_type'] == 'DESC')
        {
            $descasc = 'DESC';
        }
        else
        {
            $descasc = 'ASC';
        }
        if ($this->input['use_custom_sort'] && $idstr)
        {
            //根据自己想要的顺序排列栏目输出
            $order_str = ' ORDER BY field(c.id,' . $idstr . ')';
        }
        else
        {
            $order_str = ' ORDER BY ' . $specorder . 'c.order_id ' . $descasc;
        }
        $column_data = $this->get_column($condition, $offset, $count, $order_str);
        if ($this->input['need_count'])
        {

            $sql        = "SELECT count(*) AS total FROM " .
                    DB_PREFIX . "column c WHERE 1" . $condition;
            $totalcount = $this->db->query_first($sql);
            $this->addItem_withkey('total', $totalcount['total']);
            $this->addItem_withkey('data', $column_data);
        }
        else
        {
            $this->addItem_withkey('', $column_data);
        }
        $this->output();
    }

    /** 根据站点取栏目数据接口 
     * 返回以fid为索引的栏目数据
     */
    public function get_column_by_site()
    {
        $condition = '';
        $site_id   = intval($this->input['site_id']);
        if (!$site_id)
        {
            $this->errorOutput(NO_SITE_ID);
        }
        $condition .= " AND site_id=" . $site_id;
        $orderby = ' ORDER BY order_id ASC';
        if ($this->input['fields'])
        {
            $fields = explode(',', $this->input['fields']);
        }
        $sql         = 'SELECT * FROM ' . DB_PREFIX . "column  WHERE 1" . $condition . $orderby;
        $column_data = array();
        $info        = $this->db->query($sql);
        while ($row         = $this->db->fetch_array($info))
        {
            $row = $this->parse_column($row);
            $tmp = array();
            if ($fields)
            {
                foreach ($fields AS $v)
                {
                    if (isset($row[$v]))
                    {
                        $tmp[$v] = $row[$v];
                    }
                }
            }
            else
            {
                $tmp = $row;
            }
            $column_data[$row['fid']][] = $tmp;
        }
        $ret = array();
        if ($column_data[0])
        {
            $ret[0] = $column_data[0];
            foreach ($column_data[0] AS $v)
            {
                if ($column_data[$v['id']])
                {
                    $ret[$v['id']] = $column_data[$v['id']];
                }
            }
        }
        $this->addItem_withkey('', $ret);
        $this->output();
    }

    public function get_parents()
    {
        $result             = $support_client_arr = array();
        $condition          = '';
        if ($site_id            = intval($this->input['site_id']))
        {
            $condition .= " AND site_id=" . $site_id;
        }
        if ($id = intval($this->input['id']))
        {
            $condition .= " AND id = " . $id;
        }
        else
        {
            $this->errorOutput("NO_COLUMN_SPECIFY");
        }


        $sql  = "SELECT id,parents FROM " .
                DB_PREFIX . "column  WHERE 1" . $condition;
        $info = $this->db->query_first($sql);

        if (!$info['id'])
        {
            $this->errorOutput("NO_COLUMN_DATA");
        }
        $condition   = " AND c.id in (" . $info['parents'] . ")";
        $column_data = $this->get_column($condition, 0, 100, ' ORDER BY c.depath ASC');
        $this->addItem_withkey('', $column_data);
        $this->output();
    }

    public function get_childs()
    {
        $result             = $support_client_arr = array();
        $condition          = '';
        if ($site_id            = intval($this->input['site_id']))
        {
            $condition .= " AND c.site_id=" . $site_id;
        }
        if ($id = intval($this->input['id']))
        {
            $condition .= " AND c.id = " . $id;
        }
        else
        {
            $this->errorOutput("NO_COLUMN_SPECIFY");
        }

        $offset = intval($this->input['offset']);
        $count  = intval($this->input['count']);
        $count  = $count ? $count : 20;

        $sql         = "SELECT s.sub_weburl,s.weburl,c.*,c.content AS brief FROM " .
                DB_PREFIX . "column c LEFT JOIN " . DB_PREFIX . "site s ON c.site_id=s.id  WHERE 1" . $condition;
        $column_data = $this->db->query_first($sql);
        if (!$column_data['id'])
        {
            $this->errorOutput("NO_COLUMN_DATA");
        }
        $column_data = $this->parse_column($column_data);
        $condition   = " AND c.id != $id AND c.fid =" . $id;
        $limit_id    = $this->input['limit_id'];
        if ($limit_id)
        {
            $limit_id = explode(',', $limit_id);
            $condition .= " AND c.id in ('" . implode("','", $limit_id) . "')";
        }
        $columns = $this->get_column($condition, $offset, $count);
        if ($columns)
        {
            $column_data = array_merge(array($column_data), $columns);
            $this->addItem_withkey('', $column_data);
        }
        else
        {
            $this->addItem($column_data);
        }

        $this->output();
    }

    private function parse_column($row)
    {
        $row['client_pic'] = $row['client_pic'] ? unserialize($row['client_pic']) : array();
        if ($row['pic'])
        {
            $pic             = unserialize($row['pic']);
            $row['indexpic'] = array(
                'id'   => $pic['id'],
                'host' => $pic['host'],
                'dir' => $pic['dir'],
                'filepath' => $pic['filepath'],
                'filename' => $pic['filename'],
            );
        }
        else
        {
            $row['indexpic'] = array();
        }
        $row['column_url']    = mk_column_url($row);
        $row['column_domain'] = mk_column_url($row, false);
        if ($row['client_pic'][$this->user['appid']])
        {
            $tpic = $row['client_pic'][$this->user['appid']];
            $pic  = array(
                'host' => $tpic['host'],
                'dir' => $tpic['dir'],
                'filepath' => $tpic['filepath'],
                'filename' => $tpic['filename'],
            );
        }
        else
        {
            $pic = $row['indexpic'];
        }
        if ($pic)
        {
            $row['icon']['icon_1']['default']       = $pic;
            $row['icon']['icon_1']['activation']    = $pic;
            $row['icon']['icon_1']['no_activation'] = $pic;
            $row['icon']['icon_2']['default']       = $pic;
            $row['icon']['icon_2']['activation']    = $pic;
            $row['icon']['icon_2']['no_activation'] = $pic;
        }
        unset($row['client_pic'], $row['custom_content_dir'], $row['pic']);
        return $row;
    }

    private function get_column($condition, $offset = 0, $count = 100, $orderby = ' ORDER BY c.order_id ASC')
    {
        $sql         = "SELECT s.site_name,s.sub_weburl,s.weburl,c.*,c.content AS brief FROM " .
                DB_PREFIX . "column c LEFT JOIN " . DB_PREFIX . "site s ON c.site_id=s.id  WHERE 1" . $condition . $orderby . '  LIMIT ' . $offset . ',' . $count;
        $info        = $this->db->query($sql);
        $column_data = array();
        while ($row         = $this->db->fetch_array($info))
        {
            if ($row['shortname'])
            {
                $tf               = array('{site_title}', '{column_title}');
                $tr               = array($row['site_name'], $row['name']);
                $row['shortname'] = str_replace($tf, $tr, $row['shortname']);
            }
            $row           = $this->parse_column($row);
            $column_data[] = $row;
        }

        return $column_data;
    }

    /**
     * is_site有值表示column_id记录的是站点id
     * is_site没有值表示column_id记录的是栏目id
     * */
    public function column_support_content()
    {
        $ids     = $this->input['id'];
        $is_site = $this->input['is_site'];
        if (!$ids)
        {
            $this->addItem('error');
            $this->output();
        }
        if ($is_site)
        {
            $sql = "SELECT id,site_name as name,support_content_type FROM " . DB_PREFIX . "site WHERE id in(" . $ids . ")";
        }
        else
        {
            $sql = "SELECT id,name,support_content_type FROM " . DB_PREFIX . "column WHERE id in(" . $ids . ")";
        }
        $info = $this->db->query($sql);
        $tag  = '';
        while ($row  = $this->db->fetch_array($info))
        {
            if ($row['support_content_type'])
            {
                $content_type .= $tag . $row['support_content_type'];
                $result[$row['id']]['support_content_type'] = explode(',', $row['support_content_type']);
            }
            else
            {
                $result[$row['id']]['support_content_type'] = array();
            }
            $tag                        = ',';
            $result[$row['id']]['name'] = $row['name'];
        }

        //获取内容标识，名称
        $content_type_data = $this->pub_content->content_field_by_ids($content_type);

        foreach ($result as $k => $v)
        {
            foreach ($v['support_content_type'] as $kk => $vv)
            {
                $result[$k]['support_content_type'][$kk] = $content_type_data[$vv]['bundle_id'] . '/' . $content_type_data[$vv]['module_id'] . '/' . $content_type_data[$vv]['struct_id'];
            }
        }
        $this->addItem($result);
        $this->output();
    }

    //将icon表中图片导入到colunmn表pic中
    public function import_pic()
    {
        $client = $this->input['client'];
        if (!$client)
        {
            $this->addItem('请输入导入前客户端id');
            $this->output();
        }
        $appid = $this->input['app_id'];
        $sql   = "SELECT * FROM " . DB_PREFIX . "column_icon WHERE client  = " . $client;
        $info  = $this->db->query($sql);
        while ($row   = $this->db->fetch_array($info))
        {
            $pic = array();
            if ($row['icon_default'])
            {
                $pic = $row['icon_default'];
            }
            if (!$pic)
            {
                $pic = $row['activation'];
            }
            if (!$pic)
            {
                $pic = $row['no_activation'];
            }
            $sqll = '';
            if ($pic && !$appid)
            {
                $sqll = 'UPDATE ' . DB_PREFIX . 'column SET pic = "' . addslashes($pic) . '"  WHERE id =' . $row['column_id'];
            }
            if ($pic && $appid)
            {
                $indpic[$appid] = unserialize($pic);
                $client_pic     = serialize($indpic);
                $sqll           = "UPDATE " . DB_PREFIX . "column SET client_pic = '" . addslashes($client_pic) . "'" . "  WHERE id =" . $row['column_id'];
            }
            if ($sqll)
            {
                $this->db->query($sqll);
            }
        }
    }

    public function update_column()
    {
        $id = intval($this->input['id']);
        if (!$id)
        {
            $this->errorOutput('NO_ID');
        }
        if (isset($this->input['name']))
        {
            $data['name'] = $this->input['name'];
        }
        if (isset($this->input['shortname']))
        {
            $data['shortname'] = $this->input['shortname'];
        }
        if (isset($this->input['linkurl']))
        {
            $data['linkurl'] = $this->input['linkurl'];
        }
        if (isset($this->input['keywords']))
        {
            $data['keywords'] = $this->input['keywords'];
        }
        if (isset($this->input['content']))
        {
            $data['content'] = $this->input['content'];
        }
        include(CUR_CONF_PATH . 'lib/column.class.php');
        $column_obj = new column();
        $column_obj->update_column($id, $data);
        $detail     = $column_obj->get_column_first(' id,name,keywords,content,linkurl ', $id);
        $this->addItem($detail);
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

$out    = new columnApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
