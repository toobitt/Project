<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 * ************************************************************************* */

//在没有出入任何可选数据时 控件默认调用的类
class column
{

    private $site;
    private $db;
    private $input;

    function __construct()
    {
        global $_INPUT;
        global $gGlobalConfig;
        $this->db    = hg_checkDB();
        $this->input = $_INPUT;
        if ($gGlobalConfig['App_publishcontent'])
        {
            $this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir'] . 'admin/');
        }
    }

    function __destruct()
    {
        
    }

    //默认栏目 支持指定父栏目或者栏目ID
    public function getdefaultcol($colid = 0, $fid = 0, $type = 0, $siteid = 1, $exclude = array())
    {
        if (!$this->curl)
        {
            return;
        }
        $hg_columns = array();
        if (!$siteid)
        {
            return $hg_columns;
        }
//		//如果顶级栏目超出2000则出现bug
        $offset     = $this->input['offset'] ? $this->input['offset'] : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 2000;
        $data_limit = ' LIMIT ' . $offset . ' , ' . $count;
        $condition  = $this->get_conditions($colid, $fid, $type, $siteid) . ' ORDER BY order_id ASC ';
        $data       = array(
            'data_limit' => $data_limit,
            'condition' => $condition,
        );
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('data', json_encode($data));
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('a', 'get_pub_column');
        $columns    = $this->curl->request('column.php');
        foreach ($columns[0] as $k => $v)
        {
            $columns[0][$k]['is_last'] = $columns[0][$k]['is_last'] == 1 ? 0 : 1;
        }
        return $columns[0];
    }

    public function getAuthoredColumns($siteid = 1)
    {
        if (!$this->curl)
        {
            return;
        }
        $hg_columns = array();
        if (!$siteid)
        {
            return $hg_columns;
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_authored_columns');
        $this->curl->addRequestData('siteid', $siteid);
        $columns = $this->curl->request('column_node.php');
        return $columns;
    }

    //run.php中change_node方法调用取栏目数据
    public function getColumns($type = 1, $count = 0, $offset = 0, $siteid = 1)
    {
        if (!$this->curl)
        {
            return;
        }
//		//分页参数设置
        $offset     = $offset ? $offset : 0;
        $count      = $count ? $count : 2000;
        $data_limit = ' LIMIT ' . $offset . ' , ' . $count;
        //获取查询条件
        $condition  = $this->get_conditions(0, 0, $type) . ' ORDER BY sort_id ASC';

        $data                  = array(
            'data_limit' => $data_limit,
            'condition' => $condition,
        );
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('data', json_encode($data));
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('a', 'get_pub_column');
        $columns               = $this->curl->request('column.php');
        $columns[0]['input_k'] = '_colid';
        $columns[0]['input_t'] = '_fid';
        foreach ($columns[0] as $k => $v)
        {
            $columns[0][$k]['is_last'] = $columns[0][$k]['is_last'] == 1 ? 0 : 1;
        }
        return $columns[0];
    }

    private function get_conditions($colid, $fid, $type, $siteid = 1)
    {
        $conditions = ' AND site_id = ' . $siteid;
        $conditions .= ' AND fid = ' . intval($fid);
        if ($colid)
        {
            $conditions .= ' AND id = ' . $colid;
        }
        $type = $type ? intval($type) : 1;
        //默认只加载顶级栏目
        return $conditions;
    }

    //模板中获取已选中的栏目
    public function get_selected_col($selected_id)
    {
        if (!$this->curl)
        {
            return;
        }
        $result = array();
        if (!$selected_id)
        {
            return;
        }
        if (is_array($selected_id))
        {
            $selected_id = implode(',', $selected_id);
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $selected_id);
        $this->curl->addRequestData('html', true);
        $this->curl->addRequestData('a', 'get_pub_column_by_id');
        $columns = $this->curl->request('column.php');
        foreach ($columns as $k => $v)
        {
            $result[$v['id']] = $v['name'];
        }
        return $result;
    }

    //模板中获取已选中的栏目
    public function get_selected_column_path($selected_id)
    {
        if (!$this->curl)
        {
            return;
        }
        $result = array();
        if (!$selected_id)
        {
            return array();
        }
        if (is_array($selected_id))
        {
            $selected_id = implode(',', $selected_id);
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $selected_id);
        $this->curl->addRequestData('a', 'get_selected_column_path');
        $columns = $this->curl->request('column_node.php');
        return $columns;
    }

    //获取栏目类型
    public function get_col_type($type = '')
    {
        return $return;
    }

    //获取所有站点
    function getallsites()
    {
        if (!$this->curl)
        {
            return;
        }
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_authorized_site');
        $sites = $this->curl->request('site.php');
        foreach ($sites as $k => $v)
        {
            $site[$v['id']] = $v['site_name'];
        }
        return $site;
    }

    //获取支持推送的页面区块
    public function get_page_block($page_id, $column_ids)
    {
        global $gGlobalConfig;
        $this->curl = new curl($gGlobalConfig['App_publishsys']['host'], $gGlobalConfig['App_publishsys']['dir'] . 'admin/');
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('fid', $page_id);
        $this->curl->addRequestData('column_ids', $column_ids);
        $this->curl->addRequestData('a', 'page_block');
        $ret        = $this->curl->request('page.php');
        return $ret[0];
    }

    /* 	//默认栏目 支持指定父栏目或者栏目ID
      public function getdefaultcol($colid = 0, $fid = 0, $type=0, $siteid=1, $exclude=array())
      {
      $hg_columns = array();
      if(!$siteid)
      {
      return $hg_columns;
      }
      //如果顶级栏目超出2000则出现bug
      $offset = $this->input['offset'] ? $this->input['offset'] : 0;
      $count = $this->input['count'] ? intval($this->input['count']) : 2000;
      $data_limit = ' LIMIT ' . $offset . ' , ' . $count;
      $sql = 'SELECT col.id,col.name,col.fatherid,col.is_last FROM '.DB_PREFIX.'columns col LEFT JOIN '.DB_PREFIX.'column_type_map col_map ON col.id = col_map.columnid  WHERE 1 ';
      $conditions = $this->get_conditions($colid, $fid, $type, $siteid) . ' ORDER BY col.id ASC ';
      $sql = $sql . $conditions  . $data_limit;
      $q = $this->db->query($sql);
      while($row = $this->db->fetch_array($q))
      {
      if(in_array($row['id'], $exclude))
      {
      continue;
      }
      $row['fid'] = $row['fatherid'];
      unset($row['fatherid']);
      $hg_columns[$row['id']] = $row;
      }
      return $hg_columns;
      }
      //run.php中change_node方法调用取栏目数据
      public function getColumns($type=1, $count=0, $offset=0, $siteid=1)
      {
      //分页参数设置
      $offset = $offset ? $offset : 0;
      $count = $count ? $count : 2000;

      $data_limit = ' LIMIT ' . $offset . ' , ' . $count;

      $fields = 'col.*';
      $sql = "SELECT {$fields}
      FROM ".DB_PREFIX."columns col LEFT JOIN ".DB_PREFIX."column_type_map col_map ON col.id=col_map.columnid WHERE col.siteid={$siteid}";

      //获取查询条件
      $condition = $this->get_conditions(0,0,$type) . ' ORDER BY col.id ASC';
      $sql = $sql . $condition . $data_limit;
      $q = $this->db->query($sql);
      $return = array();
      while(false !== ($row = $this->db->fetch_array($q)))
      {
      //修正
      $row['is_last'] = $row['is_last'] ? 0 : 1;
      $return[] = array(
      'id'=>$row['id'],
      'name'=>$row['name'],
      'fid'=>$row['fatherid'],
      'is_last'=>$row['is_last'],
      'depth'=>count(explode(',', $row['parents'])),
      'input_k'=>'_colid',
      'input_t'=>'_fid',
      );
      }
      return $return;
      }
      private function get_conditions($colid, $fid, $type, $siteid=1)
      {
      $conditions = ' AND col.siteid = '.$siteid;
      $conditions .= ' AND col.fatherid = '.intval($fid);
      if($colid)
      {
      $conditions .= ' AND col.id = '.$colid;
      }
      $type = $type ? intval($type) : 1;
      //默认只加载顶级栏目
      $conditions .= ' AND col_map.column_flag = '.$type;
      return $conditions;
      }
      //模板中获取已选中的栏目
      public function get_selected_col($selected_id)
      {
      if(!$selected_id)
      {
      return;
      }
      if(is_array($selected_id))
      {
      $selected_id = implode(',',$selected_id);
      }
      $return = array();
      $sql = 'SELECT id,name FROM '.DB_PREFIX.'columns WHERE id in('.$selected_id.')';
      $q = $this->db->query($sql);
      while($row = $this->db->fetch_array($q))
      {
      $return[$row['id']] = $row['name'];
      }
      return $return;
      }
      //获取栏目类型
      public function get_col_type($type = '')
      {
      if($type)
      {
      $type = " AND type_flag IN({$type})";
      }
      $return = array();
      $sql = 'SELECT * FROM '.DB_PREFIX.'column_type WHERE 1 ' . $type . ' ORDER BY type_flag ASC';
      $q = $this->db->query($sql);
      while($row = $this->db->fetch_array($q))
      {
      $return[$row['type_flag']] = $row['type_name'];
      }
      return $return;
      }
      //获取所有站点
      function getallsites()
      {
      $sql = 'SELECT * FROM '.DB_PREFIX.'sites WHERE del!=1';
      $sites = array();
      $q = $this->db->query($sql);
      while($row = $this->db->fetch_array($q))
      {
      $sites[$row['id']] = $row['site_name'];
      }
      return $sites;
      }
     */

    function getallclients()
    {
        if (!$this->curl)
        {
            return;
        }
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_pub_client');
        $sites = $this->curl->request('client.php');
        if (is_array($sites[0]))
        {
            foreach ($sites[0] as $k => $v)
            {
                $site[$v['id']] = $v['name'];
            }
        }
        return $site;
    }

    function get_content_type()
    {
        if (!$this->curl)
        {
            return;
        }
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_all_content_type');
        $sites = $this->curl->request('content_set.php');
        return $sites[0];
    }

}

?>