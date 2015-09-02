<?php

require('global.php');
define('MOD_UNIQUEID', 'publishcontent'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/publishplan.class.php');

class contentApi extends adminBase
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
        $this->mPrmsMethods = array(
            'show' => '查看内容',
            'manage' => '管理[撤回/排序]',
        );
        parent::__construct();
        $this->pub_config   = new publishconfig();
        $this->pub_plan     = new publishplan();
        
        include(CUR_CONF_PATH . 'lib/content.class.php');   
        $this->obj          = new content();
        include_once(CUR_CONF_PATH . 'lib/column.class.php');
        $this->column       = new column();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @Description    :    获取发布库内容
     * @Author         :    dong(dong@hoge.cn)
     * @Category       :    publishcontent
     * @Date           :    2014-2-14
     * @LastUpdateDate :    2014-2-14
     * @Copyright      :    hogesoft
     * @Param          :    site_id(站点id);column_id(栏目id，多个逗号隔开);client_type(终端id);
     * @Return         :    json
     */
    public function show()
    {
        $site_id      = $column_id    = '';
        $content_data = array();
        $id           = urldecode($this->input['_id']);
        $app          = urldecode($this->input['con_app']) == 'all' ? '' : urldecode($this->input['con_app']);

        //查询出站点下模块的内容
        //$offset       = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $page         = $this->input['page'] ? intval(urldecode($this->input['page'])) : 1;
        $count        = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 20;
        $offset       = $count * ($page - 1);
        $content_data = $this->obj->get_content_allinfo($this->get_condition(), $offset, $count, $this->other_field);

        if ($content_data['col_id_str'])
        {
            $col_parent   = $this->column->get_col_parents($content_data['col_id_str']);
            $column_datas = $this->column->get_column_by_id(' id,name,site_id,column_dir,relate_dir,fid,childdomain,father_domain,custom_content_dir,col_con_maketype ', $content_data['col_id_str'], 'id');
        }
        if ($content_data['site_id_arr'])
        {
            $site_datas = $this->column->get_site(' id,site_name,weburl,sub_weburl,custom_content_dir ', ' AND id in(' . implode(',', $content_data['site_id_arr']) . ')', 'id');
        }
        unset($content_data['col_id_str']);
        unset($content_data['site_id_arr']);

        foreach ($content_data as $k => $v)
        {
            $content_data[$k]['state'] = $v['status'];
            if ($v['outlink'])
            {
                $content_data[$k]['content_url'] = stripos($v['outlink'], 'http') === 0 ? $v['outlink'] : ('http://' . $v['outlink']);
            }
            else
            {
                $v['column_id']                  = $v['use_maincolumn'] ? $v['main_column_id'] : $v['column_id'];
                $content_data[$k]['content_url'] = mk_content_url($site_datas[$v['site_id']], $column_datas[$v['column_id']], $v);
            }
        }

        //取出应用模块
        $appdata      = $this->obj->get_app();
        $appchilddata = $this->obj->get_app_child($app);

        //取出所有客户端
        include_once(CUR_CONF_PATH . 'lib/client.class.php');
        $client_obj = new client();
        $client     = $client_obj->get_all_client('*', '', 'id');
        $sql        = "SELECT COUNT(*) AS total" . $this->other_field . " FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id WHERE 1 " . $this->get_condition();
        $pagearr    = $this->db->query_first($sql);

        $pagearr['offset']       = $count;
        $pagearr['count']        = $count;
        $pagearr['total_page']   = ceil($pagearr['total'] / $count);
        $pagearr['current_page'] = floor($offset / $count) + 1;

        $alldata['content_data']  = $content_data;
        $alldata['col_parent']    = $col_parent;
        $alldata['app_data']      = $appdata;
        $alldata['appchild_data'] = $appchilddata;
        $alldata['client']        = $client;
        $alldata['page']          = $pagearr;
        $this->addItem($alldata);
        $this->output();
    }

    /**
     * 根据publishids得到clickNums
     * @return multitype:
     * $author cesc
     */
    public function getClicknumsByPublishids(){
    	$ret = array();
    	$publishIdsString = $this->input['publishids'];
    	$publishIds = explode(',', $publishIdsString);
    	foreach ($publishIds as $key => $val){
    		$info = $this->obj->get_content_relation_by_id(intval($val));	
    		$ret[$val] = array(
    			'click_num' =>$info['click_num'],
    			'comment_num' =>$info['comment_num'],	 
    		);
    	}
    	$this->addItem($ret);
    	$this->output();
    }
    
    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id  LEFT JOIN " . DB_PREFIX . "content c on r.content_id=c.id  WHERE 1 " . $this->get_condition();
        echo json_encode($this->db->query_first($sql));
    }

    private function get_condition()
    {
        $condition   = ' AND r.is_complete=1 ';
        $id          = ($this->input['_id']) ? $this->input['_id'] : urldecode($this->input['fid']);
        $client_type = intval($this->input['client_type']) ? intval($this->input['client_type']) : $this->settings['default_client_id'];
        $order_field = urldecode($this->input['order_field']);
        $app         = urldecode($this->input['con_app']) == 'all' ? '' : urldecode($this->input['con_app']);
        $appchild    = urldecode($this->input['con_appchild']) == 'all' ? '' : urldecode($this->input['con_appchild']);

        //获取该用户支持的栏目
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
           // $column_id = $this->get_support_column();
        }
        ####增加权限控制 用于显示####
        if($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if(!$this->user['prms']['default_setting']['show_other_data'])
            {
                $condition .= ' AND r.create_user = \''.$this->user['user_name'] . '\'';
            }
            else
            {
                //组织以内
                if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
                {
                    //$condition .= ' AND r.org_id IN('.$this->user['slave_org'].')';
                }
            }
            //if($authnode = $this->user['prms']['publish_prms'])
            //{
                $authnode = $this->user['prms']['publish_prms'];
                $authnode_str = $authnode && is_array($authnode) ? implode(',', $authnode) : '';
                if($authnode_str == '' && !$this->user['prms']['site_prms'])
                {
                    $condition .= ' AND r.column_id IN(' . intval($authnode_str) . ')';
                }
                if($authnode_str)
                {
                    $authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
                    $sql = 'SELECT id,childs FROM '.DB_PREFIX.'column WHERE id IN('.$authnode_str.')';
                    $query = $this->db->query($sql);
                    $authnode_array = array();
                    while($row = $this->db->fetch_array($query))
                    {
                        $authnode_array[$row['id']]= explode(',', $row['childs']);
                    }
                    $authnode_str = '';
                    foreach ($authnode_array as $node_id=>$n)
                    {
                        if($node_id == intval($this->input['_id']))
                        {
                            $node_father_array = $n;
                            if(!in_array(intval($this->input['_id']), $authnode))
                            {
                                continue;
                            }
                        }
                        $authnode_str .= implode(',', $n) . ',';
                    }
                    $authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
                    if(!$this->input['_id'])
                    {
                        $condition .= ' AND r.column_id IN(' . $authnode_str . ')';
                    }
                    else
                    {
                        $authnode_array = explode(',', $authnode_str);
                        if(!in_array($this->input['_id'], $authnode_array))
                        {
                            //
                            if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
                            {
                                $this->errorOutput(NO_PRIVILEGE);
                            }
                            //$this->errorOutput(var_export($auth_child_node_array,1));
                            $condition .= ' AND r.column_id IN(' . implode(',', $auth_child_node_array) . ')';
                        }
                    }
                }
            //}
        }

        if (strstr($id, "site") === false && $id != '')
        {
            //获取这个栏目下的所有栏目id
            $columns_data = $this->column->get_column_first(' id,name,fid,parents,site_id,childs,childdomain,father_domain ', $id);
            if (empty($columns_data))
            {
                $this->errorOutput('未获取到栏目信息');
            }
            $site_id = $columns_data['site_id'];

            if (isset($column_id))
            {
                $column_id_arr           = explode(',', $column_id);
                $columns_data_childs_arr = explode(',', $columns_data['childs']);
                $common_column_id_arr    = array_intersect($column_id_arr, $columns_data_childs_arr);
                $column_id               = implode(',', $common_column_id_arr);
            }
            else
            {
                $column_id = $columns_data['childs'];
            }
        }
        else if (!empty($id))
        {
            $site_id = str_replace('site', '', $id);
        }
        if ($this->input['site_id'])
        {
            $site_id = intval($this->input['site_id']);
        }
        if ($site_id)
        {
            $condition .= " AND r.site_id=" . $site_id;
        }
        if (isset($column_id))
        {
            $column_id = $column_id ? $column_id : 0;
            $condition .= " AND r.column_id in (" . $column_id . ")";
        }
        if ($app)
        {
            $condition .= " AND r.bundle_id='" . $app . "'";
        }
        if ($appchild)
        {
            $condition .= " AND r.module_id='" . $appchild . "'";
        }
        if ($create_user = trim($this->input['create_user'], ' '))
        {
            $condition .= " AND r.create_user='" . $this->input['create_user'] . "' ";
        }
        if ($publish_user = trim($this->input['publish_user'], ' '))
        {
            $condition .= " AND r.publish_user='" . $this->input['publish_user'] . "' ";
        }
        if ($client_type && $client_type != -1)
        {
            $condition .= " AND cr.client_type='" . $client_type . "'";
        }
        if ($k = $this->input['k'])
        {
            $k = $this->get_titleResult($k);
            if ($k)
            {
                if ($this->settings['App_textsearch'])
                {
                    $k                 = str_replace(' ', '+', $k);
                    $condition .= " AND MATCH (title_unicode) AGAINST ('" . $k . "' IN BOOLEAN MODE )";
                    $this->other_field = ",MATCH (title_unicode) AGAINST ('" . $k . "' IN BOOLEAN MODE ) AS title_score";
                }
                else
                {
                    $condition .= " AND r.title_unicode like '%" . $k . "%'";
                }
            }
        }
        if ($this->input['create_date_search'])
        {
            $start_time = trim(urldecode($this->input['start_timecreate']));
            if ($start_time = strtotime($start_time))
            {
                $condition .= " AND r.create_time >= '" . $start_time . "'";
            }
            $end_time = trim(urldecode($this->input['end_timecreate']));
            if ($end_time = strtotime($end_time))
            {
                $condition .= " AND r.create_time < '" . $end_time . "'";
            }
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['create_date_search']))
            {
                case -1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday     = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  r.create_time >= '" . $yesterday . "' AND r.create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  r.create_time >= '" . $today . "' AND r.create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND  r.create_time >= '" . $last_threeday . "' AND r.create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  r.create_time >= '" . $last_sevenday . "' AND r.create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }
        //权重
        if (isset($this->input['start_weight']) && intval($this->input['start_weight']) >= 0)
        {
            $condition .=" AND r.weight >= " . $this->input['start_weight'];
        }
        if (isset($this->input['end_weight']) && intval($this->input['end_weight']) >= 0)
        {
            $condition .=" AND r.weight <= " . $this->input['end_weight'];
        }

        if ($this->input['publish_date_search'])
        {
            $start_time = trim(urldecode($this->input['start_timepublish']));
            if ($start_time)
            {
                $start_time = strtotime($start_time);
                $condition .= " AND r.publish_time >= '" . $start_time . "'";
            }
            $end_time = trim(urldecode($this->input['end_timepublish']));
            if ($end_time)
            {
                $end_time = strtotime($end_time);
                $condition .= " AND r.publish_time < '" . $end_time . "'";
            }
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['publish_date_search']))
            {
                case -1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday     = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  r.publish_time >= '" . $yesterday . "' AND r.publish_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  r.publish_time >= '" . $today . "' AND r.publish_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND  r.publish_time >= '" . $last_threeday . "' AND r.publish_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  r.publish_time >= '" . $last_sevenday . "' AND r.publish_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }
        if ($this->other_field)
        {
            $condition .= " ORDER BY title_score DESC,r.order_id DESC";
        }
        else if ($order_field && $order_field != -1)
        {
            $ordertype  = substr($order_field, -1) == 'a' ? 'ASC' : 'DESC';
            $orderfield = substr($order_field, 0, strripos($order_field, '-'));
            $table      = 'r';
            $condition .= " ORDER BY " . $table . "." . $orderfield . " " . $ordertype;
             if($orderfield=='weight')
                {
                $condition .= ' ,r.order_id DESC ';
                }
        }
        else
        {
            $condition .= " ORDER BY r.order_id DESC";
        }
        return $condition;
    }

    public function get_support_column()
    {
        //获取该用户支持的栏目
        if ($this->user['publish_col_prms'] && is_array($this->user['publish_col_prms']))
        {
            $exclude_column  = implode(',', $this->user['publish_col_prms']);
            $sup_column_data = $this->column->get_column_by_id(' id,childs ', $exclude_column, 'id');
            if (is_array($sup_column_data))
            {
                $tag = '';
                foreach ($sup_column_data as $k => $v)
                {
                    $sup_column_id .= $tag . $v['childs'];
                    $tag = ',';
                }
                $sup_column_id_arr     = explode(',', $sup_column_id);
                $new_sup_column_id_arr = array_unique($sup_column_id_arr);
                $new_sup_column_id     = implode(',', $new_sup_column_id_arr);
            }
        }
        return $new_sup_column_id;
    }

    public function delete_by_rid()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if ($this->user['user_id'] < 1)
            {
                $this->errorOutput(USER_NOT_LOGIN);
            }
            if (!in_array('manage', (array) $this->user['prms']['app_prms'][APP_UNIQUEID]['action']))
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }

        if ($this->mNeedCheckIn && !$this->prms['delete'])
        {
            $this->errorOutput(NO_OPRATION_PRIVILEGE);
        }
        $rids = urldecode($this->input['rid']);
        if (empty($rids))
        {
            $this->errorOutput('没有要删除的内容');
        }
        $rc = $this->obj->get_cr_by_rid($rids);
        if (empty($rc['ridarr']))
        {
            $this->errorOutput('没有要删除的内容关联表id');
        }
        //查询出配置信息
        if (empty($rc['plan_set_idarr']))
        {
            $this->errorOutput('缺少内容计划配置id');
        }
        $plan_data = $this->pub_plan->get_plan_set(implode(',', $rc['plan_set_idarr']));

        //删除内容关联表
        $this->obj->delete_content_relation_by_id($rids);

        //删除百度视频收录
        $this->obj->update_video_record(array('rid' => $rids), 'del');

        //删除内容客户端关联表
        $this->obj->delete_content_client_by_rid($rids);

        //分布处理
        $content_column_ids = array();
        foreach ($rc['ridarr'] as $k => $v)
        {
            $content_column_ids[$v['content_id']][] = $v['column_id'];

            //插入到content_publish_time表中
            $this->obj->insert('content_publish_time', array('content_id' => $k, 'publish_time' => TIMENOW));

            //回掉原系统，删除栏目
            if (!empty($plan_data[0][$v['plan_set_id']]))
            {
                $u_data = array(
                    'is_delete_column' => 1,
                    'column_id' => $v['column_id'],
                    'from_id' => $v['content_fromid'],
                );
                $this->pub_plan->setAttribute($plan_data[0][$v['plan_set_id']]['host'], $plan_data[0][$v['plan_set_id']]['path'], $plan_data[0][$v['plan_set_id']]['filename'], 'delete_publish');
                $this->pub_plan->insert_pub_content_id($u_data);
            }
        }

        //查询映射表中还有没有关于此内容的关联，如果没有，则删除这个内容
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $this->cache = new Cache();
        foreach ($rc['cidarr'] as $k => $v)
        {
            //删除content_columns对应的栏目,数据处理
            if (!empty($content_column_ids[$k]))
            {
                $content_column_detail  = $this->obj->get_content_columns(' * ', array('content_id' => $k));
                $content_column_data    = @unserialize($content_column_detail['column_datas']);
                $content_column_ids_arr = explode(',', $content_column_detail['column_ids']);
                foreach ($content_column_ids[$k] as $col_id)
                {
                    $flkey = array_search($col_id, $content_column_ids_arr);
                    if ($flkey !== false)
                    {
                        unset($content_column_ids_arr[array_search($col_id, $content_column_ids_arr)]);
                        unset($content_column_data[$column_id]);
                    }
                }
                $new_content_columns = array(
                    'column_ids' => empty($content_column_ids_arr) ? '' : implode(',', $content_column_ids_arr),
                    'column_datas' => empty($content_column_data) ? '' : serialize($content_column_data),
                );
                $this->obj->update_content_columns($k, $new_content_columns);
            }

            $lastconrel = $this->obj->get_content_relation($k);
            if (empty($lastconrel))
            {
                //删除content_columns
                $this->obj->delete_content_columns($k);

                $expand_data = $this->obj->get_content_by_ids(' id,bundle_id,module_id,struct_id,site_id,expand_id,content_fromid ', $k, 'id');

                //删除对应的扩展表
                foreach ($expand_data as $k1 => $v1)
                {
                    $tablename                                   = get_tablename($v1['bundle_id'], $v1['module_id'], $v1['struct_id']);
                    $data[$tablename]['expand_ids'] .= $v1['expand_id'] . ',';
                    $data[$tablename]['table_part']['bundle_id'] = $v1['bundle_id'];
                    $data[$tablename]['table_part']['module_id'] = $v1['module_id'];
                    $data[$tablename]['table_part']['struct_id'] = $v1['struct_id'];
                }

                foreach ($data as $k2 => $v2)
                {
                    $eids = trim($v2['expand_ids'], ',');
                    if ($eids)
                    {
                        //递归删除各自关联的字表信息
                        $field = $this->obj->get_field($v2['table_part']['bundle_id'], $v2['table_part']['module_id'], $v2['table_part']['struct_id']);
                        if (!empty($field['child_table']))
                        {
                            $this->delete_child_table($field['child_table'], $v2['table_part'], $k2, $eids);
                        }

                        $this->obj->delete_expand($k2, $eids);

                        //删除缓存
                        $this->cache->initialize(CUR_CONF_PATH . 'cache/' . $k2 . '/');
                        $this->cache->delete($eids, true);
                    }
                }

                //删除内容标题表
                $this->obj->delete_expand('content', $k);

                //插入到content_publish_time表中(增加记录，删除各个系统对应cid数据)
                $this->obj->insert('content_publish_time', array('content_id' => intval($rids), 'cid' => $k, 'publish_time' => TIMENOW));

                //删除推送的内容
                $this->obj->content_push(array('special' => array(), 'block' => array(), 'content_fromid' => $expand_data[$k]['content_fromid'], 'bundle_id' => $expand_data[$k]['bundle_id'], 'module_id' => $expand_data[$k]['module_id']), $k, '', true, true);

                //删除xunsearch
                $this->xs_index($k, 'search_config_publish_content', 'del');

                //回掉原系统，更改
                if (!empty($plan_data[0][$v['plan_set_id']]))
                {
                    $u_data = array(
                        'column_id' => $v['column_id'],
                        'from_id' => $v['content_fromid'],
                        'expand_id' => 0,
                    );
                    $this->pub_plan->setAttribute($plan_data[0][$v['plan_set_id']]['host'], $plan_data[0][$v['plan_set_id']]['path'], $plan_data[0][$v['plan_set_id']]['filename'], 'delete_publish');
                    $this->pub_plan->insert_pub_content_id($u_data);
                }
            }
            else
            {
                //判断删除的栏目是不是主内容的主栏目id
                $content_detail = $this->obj->get_content_by_ids(' * ', $k);
                if (!in_array($content_detail['column_id'], $content_column_ids_arr))
                {
                    $this->obj->update_content_by_id($k, array('column_id' => intval($new_content_columns['column_ids'])));
                    if ($content_detail['use_maincolumn'])
                    {
                        $sql         = "select id,column_id,file_custom_filename,file_name from " . DB_PREFIX . "content_relation where content_id=" . $content_detail['id'];
                        $relate_info = $this->db->query($sql);
                        while ($relate_row  = $this->db->fetch_array($relate_info))
                        {
                            if ($relate_row['id'] == intval($new_content_columns['column_ids']))
                            {
                                $main_column = $relate_row;
                            }
                            else
                            {
                                $update_relate_column[] = $main_column;
                            }
                        }
                        if ($update_relate_column)
                        {
                            foreach ($update_relate_column as $k => $v)
                            {
                                $this->obj->update('content_relation', ' id=' . $v['id'], array('file_name' => $main_column['file_name']));
                            }
                        }
                    }
                }

                //更改xunsearch上的数据
                $content_detail = $this->obj->get_content_by_id(' * ', $k);
                $this->update_xunsearch($content_detail, $content_detail, $new_content_columns);
            }
        }

        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $this->addItem('success');
        $this->output();
    }

    public function delete()
    {
        $eid         = $idbyfromids = '';
        $data        = array();

        //从接口访问处理  根据content_fromid来判断
        $getdata = $this->input['data'];

        //批量删除内容，用不到，后面删了，只能单个删除内容
        if (!empty($getdata))
        {
            $column_id      = $getdata['column_id'];
            $column_idarr   = explode(',', $column_id);
            $delete_all     = $getdata['delete_all'];
            $content_detail = $this->obj->get_content_by_fromid(' * ', $getdata['bundle_id'], $getdata['module_id'], $getdata['struct_id'], $getdata['content_fromid']);
            $cid            = $content_detail['id'];
            if (!$cid)
            {
                $result['msg']   = '没有可删除的内容';
                $result['error'] = '3';
                $this->addItem($result);
                $this->output();
            }
        }
        else
        {
            $result['msg']   = '没有可删除的内容';
            $result['error'] = '3';
            $this->addItem($result);
            $this->output();
        }

        if ($delete_all === '0')
        {
            //表示只删除对应栏目下的内容，删除映射表关联
            $column_id = trim($column_id, ',');
            $rids      = $this->obj->delete_content_relation($cid, $column_id, true);
            $this->obj->update_column_content_num($column_id, false);
            $rids_str  = @implode(',', $rids);

            //删除百度视频收录
            $this->obj->update_video_record(array('rid' => $rids_str), 'del');

            //content_client_relation处理
            if (!empty($rids_str))
            {
                $this->obj->delete_content_client_by_rid($rids_str);
            }

            //content_columns处理
            $content_column_detail  = $this->obj->get_content_columns(' * ', array('content_id' => $cid));
            $content_column_data    = @unserialize($content_column_detail['column_datas']);
            $content_column_ids_arr = explode(',', $content_column_detail['column_ids']);
            foreach ($column_idarr as $col_id)
            {
                $flkey = array_search($col_id, $content_column_ids_arr);
                if ($flkey !== false)
                {
                    unset($content_column_ids_arr[array_search($col_id, $content_column_ids_arr)]);
                    unset($content_column_data[$column_id]);
                }
            }
            $new_content_columns = array(
                'column_ids' => empty($content_column_ids_arr) ? '' : implode(',', $content_column_ids_arr),
                'column_datas' => empty($content_column_data) ? '' : serialize($content_column_data),
            );
            $this->obj->update_content_columns($cid, $new_content_columns);
            $del_columnarr       = explode(',', $column_id);
            foreach ($rids as $v)
            {
                //插入到content_publish_time表中
                $this->obj->insert('content_publish_time', array('content_id' => $v, 'publish_time' => TIMENOW));
            }

            //查询映射表中还有没有关于此内容的关联，如果没有，则删除这个内容
            $lastconrel = $this->obj->get_content_relation($cid);
            if (!empty($lastconrel))
            {
                //判断删除的栏目是不是主内容的主栏目id
                if (!in_array($content_detail['column_id'], $content_column_ids_arr))
                {
                    $this->obj->update_content_by_id($cid, array('column_id' => intval($new_content_columns['column_ids'])));
                    if ($content_detail['use_maincolumn'])
                    {
                        $sql         = "select id,column_id,file_custom_filename,file_name from " . DB_PREFIX . "content_relation where content_id=" . $content_detail['id'];
                        $relate_info = $this->db->query($sql);
                        while ($relate_row  = $this->db->fetch_array($relate_info))
                        {
                            if ($relate_row['id'] == intval($new_content_columns['column_ids']))
                            {
                                $main_column = $relate_row;
                            }
                            else
                            {
                                $update_relate_column[] = $main_column;
                            }
                        }
                        if ($update_relate_column)
                        {
                            foreach ($update_relate_column as $k => $v)
                            {
                                $this->obj->update('content_relation', ' id=' . $v['id'], array('file_name' => $main_column['file_name']));
                            }
                        }
                    }
                }

                //更改xunsearch上的数据
                $this->update_xunsearch($content_detail, $content_detail, $new_content_columns);

                //清除memcache缓存
                $this->memcache_flush(APP_UNIQUEID);

                $result['msg'] = '0'; //0表示不去更新各自模块的expand_id
                $this->addItem($result);
                $this->output();
            }
        }
        else if ($delete_all != 1)
        {
            $result['msg']   = '未知何种删除操作';
            $result['error'] = '3';
            $this->addItem($result);
            $this->output();
        }

        $expand_data = $this->obj->get_content_by_ids(' bundle_id,module_id,struct_id,site_id,expand_id,content_fromid ', $cid);

        //删除对应的扩展表
        foreach ($expand_data as $k => $v)
        {
            $tablename                                   = get_tablename($v['bundle_id'], $v['module_id'], $v['struct_id']);
            $data[$tablename]['expand_ids'] .= $v['expand_id'] . ',';
            $data[$tablename]['table_part']['bundle_id'] = $v['bundle_id'];
            $data[$tablename]['table_part']['module_id'] = $v['module_id'];
            $data[$tablename]['table_part']['struct_id'] = $v['struct_id'];
        }

        foreach ($data as $k => $v)
        {
            $eids = trim($v['expand_ids'], ',');
            if ($eids)
            {
                //递归删除各自关联的字表信息
                $field = $this->obj->get_field($v['table_part']['bundle_id'], $v['table_part']['module_id'], $v['table_part']['struct_id']);
                if (!empty($field['child_table']))
                {
                    $this->delete_child_table($field['child_table'], $v['table_part'], $k, $eids);
                }
                $this->obj->delete_expand($k, $eids);

                //删除缓存
                if (intval($eids))
                {
                    include_once(CUR_CONF_PATH . 'lib/cache.class.php');
                    $this->cache = new Cache();
                    $this->cache->initialize(CUR_CONF_PATH . 'cache/' . $k . '/');
                    $this->cache->delete(intval($eids));
                }
            }
        }
        //删除内容标题表
        $this->obj->delete_expand('content', $cid);

        //删除content_columns
        $this->obj->delete_content_columns($cid);

        //删除内容关联表
        $rids = $this->obj->delete_content_relation($cid, '', true);
        if ($rids)
        {
            foreach ($rids as $v)
            {
                //插入到content_publish_time表中
                $this->obj->insert('content_publish_time', array('content_id' => $v, 'publish_time' => TIMENOW));
            }
        }
        else
        {
            if (!isset($rids_str))
            {
                $rids_str = @implode(',', $rids);
            }
            if ($rids_str)
            {
                //插入到content_publish_time表中
                $this->obj->insert('content_publish_time', array('content_id' => intval($rids_str), 'cid' => $cid, 'publish_time' => TIMENOW));
            }
        }


        //删除百度视频收录
        $this->obj->update_video_record(array('rid' => implode(',', $rids)), 'del');

        //content_client_relation处理
        if (!empty($rids))
        {
            $this->obj->delete_content_client_by_rid(implode(',', $rids));
        }

        //content_columns处理
        $content_column_detail  = $this->obj->get_content_columns(' * ', array('content_id' => $cid));
        $content_column_data    = @unserialize($content_column_detail['column_datas']);
        $content_column_ids_arr = explode(',', $content_column_detail['column_ids']);
        foreach ($column_idarr as $col_id)
        {
            $flkey = array_search($col_id, $content_column_ids_arr);
            if ($flkey !== false)
            {
                unset($content_column_ids_arr[array_search($col_id, $content_column_ids_arr)]);
                unset($content_column_data[$column_id]);
            }
        }

        $new_content_columns = array(
            'column_ids' => empty($content_column_ids_arr) ? '' : implode(',', $content_column_ids_arr),
            'column_datas' => empty($content_column_data) ? '' : serialize($content_column_data),
        );
        $this->obj->update_content_columns($cid, $new_content_columns);

        //删除推送的内容
        $this->obj->content_push(array('special' => array(), 'block' => array(), 'content_fromid' => $content_detail['content_fromid'], 'bundle_id' => $content_detail['bundle_id'], 'module_id' => $content_detail['module_id']), $cid, '', true, true);

        //删除xunsearch上的数据
        $this->xs_index($cid, 'search_config_publish_content', 'del');

        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $result['msg'] = '1'; //1表示去更新各自模块的expand_id
        $this->addItem($result);
        $this->output();
    }

    //只删除子级内容
    public function delete_child()
    {
        //从接口访问处理  根据content_fromid来判断
        $getdata         = $this->input['data'];
        $content_fromids = $getdata['content_fromid'];
        if (empty($getdata['struct_ast_id']))
        {
            $result['msg']   = '没有子级表标识'; //1表示去更新各自模块的expand_id
            $result['error'] = 2;
            $this->addItem($result);
            $this->output();
        }

        $k          = get_tablename($getdata['bundle_id'], $getdata['module_id'], $getdata['struct_id']);
        $table_name = get_tablename($getdata['bundle_id'], $getdata['module_id'], $getdata['struct_id'], $getdata['struct_ast_id']);

        //判断有无子级，删除子级对应内容
        $field = $this->obj->get_field($getdata['bundle_id'], $getdata['module_id'], $getdata['struct_id'], $getdata['struct_ast_id']);
        $eids  = $this->obj->get_update_child_id($table_name, '', $content_fromids, true);
        if (!empty($field['child_table']))
        {
            $this->delete_child_table($field['child_table'], $getdata, $k, $eids);
        }
        //删除此内容
        $this->obj->delete_expand($table_name, $eids);

        //删除缓存
        if ($eids)
        {
            include_once(CUR_CONF_PATH . 'lib/cache.class.php');
            $this->cache = new Cache();
            $this->cache->initialize(CUR_CONF_PATH . 'cache/' . $table_name . '/');
            $this->cache->delete($eids);
        }

        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $result['msg'] = '1'; //1表示去更新各自模块的expand_id
        $this->addItem($result);
        $this->output();
    }

    public function delete_child_table($child_table, $tablenamearr, $k, $eids)
    {
        $field = $this->obj->get_field($tablenamearr['bundle_id'], $tablenamearr['module_id'], $tablenamearr['struct_id'], $child_table);
        if (!empty($field['child_table']))
        {
            //查询出要删除子表信息的id
            $child_expand = $this->obj->get_expand_by_expand_id($k . '_' . $child_table, $eids);
            foreach ($child_expand as $kc => $vc)
            {
                $child_expandids .= $vc['id'] . ',';
            }
            $eids = trim($child_expandids, ',');
        }
        $cchild_table = $field['child_table'];
        if ($cchild_table && $eids)
        {
            $this->delete_child_table($cchild_table, $tablenamearr, $k, $eids);
        }
        if ($eids)
        {
            //如果有子表，则删除子表信息
            $this->obj->delete_child_expand($k . '_' . $child_table, $eids);

            //删除缓存
            if (intval($eids))
            {
                include_once(CUR_CONF_PATH . 'lib/cache.class.php');
                $this->cache = new Cache();
                $this->cache->initialize(CUR_CONF_PATH . 'cache/' . $k . '_' . $child_table . '/');
                $this->cache->delete(intval($eids));
            }
        }
    }

    public function detail()
    {
        $cid      = intval($this->input['cid']);
        $fieldid  = intval($this->input['fieldid']);
        $expandid = intval($this->input['expandid']);

        if ($fieldid)
        {
            $content = $this->obj->get_field_by_id($fieldid);
            if (empty($content))
            {
                $this->errorOutput("没有相关内容");
            }
            $field     = $content;
            $tablename = get_tablename($content['bundle_id'], $content['module_id'], $content['struct_id'], $content['struct_ast_id']);

            //查询出创建的主扩展表内容
            $expand = $this->obj->get_expand_by_expand_id($tablename, $expandid);
        }
        else
        {
            $content   = $this->obj->get_content_by_id(' * ', $cid);
            $tablename = get_tablename($content['bundle_id'], $content['module_id'], $content['struct_id']);

            if (!$tablename)
            {
                $this->errorOutput("没有相关表");
            }

            $field = $this->obj->get_field($content['bundle_id'], $content['module_id'], $content['struct_id']);

            //查询出创建的主扩展表内容
            $expand = $this->obj->get_expand($tablename, $content['expand_id']);
        }

        //查询出对应表中需要显示的字段
        $show_field = unserialize($field['show_field']);

        //查询出此扩展表的字表的table_title
        $child_tablearr = explode(',', $field['child_table']);
        if (empty($child_tablearr))
        {
            $child_data = array();
        }
        else
        {
            foreach ($child_tablearr as $k => $v)
            {
                if (!empty($v))
                {
                    $child_field  = $this->obj->get_field($content['bundle_id'], $content['module_id'], $content['struct_id'], $v);
                    $child_data[] = array('id' => $child_field['id'], 'title' => $child_field['table_title']);
                }
            }
        }

        $alldata['show_field'] = $show_field;
        $alldata['expand']     = $expand;
        $alldata['child_data'] = $child_data;
        $this->addItem($alldata);
        $this->output();
    }

    public function update()
    {
        $con         = $content_con = '';
        $data        = $this->input['data'];
        if (!$data['content_fromid'])
        {
            exit;
        }

        $fromid                = $data['content_fromid'];
        $data['struct_ast_id'] = empty($data['struct_ast_id']) ? '' : $data['struct_ast_id'];
        $field                 = $this->obj->get_field($data['bundle_id'], $data['module_id'], $data['struct_id'], $data['struct_ast_id']);
        $tablename             = get_tablename($data['bundle_id'], $data['module_id'], $data['struct_id'], $data['struct_ast_id']);
        $fieldsarr             = explode(',', $field['field']);
        unset($data['id']);
        unset($data['expand_id']);
        foreach ($data as $ku => $vu)
        {
            if (in_array($ku, $fieldsarr))
            {
                $con .= $ku . "='" . (is_array($vu) ? serialize($vu) : $vu) . "',";
            }
        }
        $con = trim($con, ',');

        if (!$con)
        {
            exit;
        }
        $this->obj->update_child_table($tablename, $con, $fromid);

        //请求前端更新,插入到content_publish_time表中,查询关联表里的id
        $relation_ids_arr = $this->obj->get_relationid_by_expand_id($data['bundle_id'], $data['module_id'], $data['struct_id'], $fromid);

        //如果没有子表，则同时更新content表
        if (!$data['struct_ast_id'])
        {
            $content_con = '';
            //对keywords每个字进行转码
            $keywordstr  = '';
            if (!empty($data['keywords']))
            {
                $keywordstr = str_utf8_unicode($data['keywords']);
            }

            //对title每个字进行转码
            $titlestr = '';
            if (!empty($data['title']))
            {
                $titlestr = $this->get_titleResult($data['title']);
                
                $title_pinyin_str = get_spell_title($data['title']);
            }
            //检测文稿内容正文里有无视频跟图集
            if($data['content'])
            {
                $material_result = $this->obj->check_material_by_content($data['content']);
            }
            $update_content_data = array(
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'brief' => $data['brief'],
                'keywords' => $data['keywords'],
                'indexpic' => is_array($data['indexpic']) ? serialize($data['indexpic']) : $data['indexpic'],
                'video' => is_array($data['video']) ? serialize($data['video']) : $data['video'],
                'outlink' => $data['outlink'], //trim($firstsite['weburl'],'/').'/content/'.$data['struct_id'].'_'
                'child_num' => $data['child_num'],
                'source' => $data['source'],
                'ip' => $data['ip'],
                'create_user' => $data['user_name'],
                'verify_user' => $data['verify_user'],
                'template_sign' => $data['template_sign'],
            	'catalog'		=> $data['catalog'],
                'tcolor' => $data['tcolor'],
                'isbold' => $data['isbold'],
                'isitalic' => $data['isitalic'],
                'author'=>$data['author'],
            	'iscomment' => $data['iscomment'] ? 1 : 0,
            	'is_praise' => $data['is_praise'] ? 1 : 0, 
            );
            $sql_extra           = $space               = ' ';
            foreach ($update_content_data as $k => $v)
            {
                $sql_extra .=$space . $k . "='" . $v . "'";
                $space = ',';
            }
            //更新content表并返回新信息
            $content_detail = $this->obj->update_content($data['bundle_id'], $data['module_id'], $data['struct_id'], $fromid, $sql_extra);

            $update_content_relation_data = array(
                'is_have_indexpic' => empty($data['indexpic']) ? 0 : 1,
                'is_have_video' => empty($data['video']) ? 0 : 1,
                'keywords_unicode' => addslashes($keywordstr),
                'title_unicode' => addslashes($titlestr),
                'title_pinyin' => addslashes($title_pinyin_str),
                'share_num' => $data['share_num'],
                'comment_num' => $data['comment_num'],
                'click_num' => $data['click_num'],
                'create_time' => empty($data['create_time']) ? TIMENOW : $data['create_time'],
                'verify_time' => empty($data['verify_time']) ? TIMENOW : $data['verify_time'],
            	'praise_count'=> $data['praise_count'],
                'is_have_content_video' => intval($material_result['video']),
                'is_have_content_tuji' => intval($material_result['tuji']),
            );
            if($this->settings['is_support_update_weight'])
            {
                $update_content_relation_data['weight'] = $data['weight'];
            }
            $this->obj->update('content_relation', ' content_id=' . $content_detail['id'], $update_content_relation_data);

            $sql   = "select * from " . DB_PREFIX . "content_relation where content_id=" . $content_detail['id'];
            $rinfo = $this->db->query($sql);
            while ($rrow  = $this->db->fetch_array($rinfo))
            {
                $rid_column_id[$rrow['id']]          = $rrow['column_id'];
                $rid_column_data[$rrow['column_id']] = $rrow;
                $last_relation_data                  = $rrow;
            }

            /**
              //更新relation表
              $update_relation_data = array(
              'file_domain' => $data['file_domain'],
              'file_dir' => $data['file_dir'],
              'file_custom_filename' => $data['file_custom_filename'],
              );
              $sql                  = "select id,column_id,file_custom_filename,file_name from " . DB_PREFIX . "content_relation where content_id=" . $content_detail['id'];
              $rinfo                = $this->db->query($sql);
              while ($rrow                 = $this->db->fetch_array($rinfo))
              {
              $rid_column_id[$rrow['id']] = $rrow['column_id'];
              if ($data['file_custom_filename'])
              {
              if (strrpos($v['file_name'], '/') === false)
              {
              $update_relation_data['file_name'] = $data['file_custom_filename'];
              }
              else
              {
              $update_relation_data['file_name'] = substr($v['file_name'], 0, strrpos($v['file_name'], '/') + 1) . $v['file_custom_filename'];
              }
              }
              $this->obj->update('content_relation', ' id=' . $rrow['id'], $update_relation_data);
              }
             */
            //更新百度视频收录
            if ($content_detail['is_have_video'])
            {
                $this->obj->update_content_video_record(implode(',', $relation_ids_arr), $content_detail);
            }

            //判断有无推送，有则插入到content_push表中
            $this->obj->content_push($data, $content_detail['id'], $rid_column_data[$content_detail['column_id']]['id'], true);

            //更新xunsearch
            $content_columns = $this->obj->get_content_columns(' * ', array('content_id' => $content_detail['id']));
            $this->update_xunsearch($data, $content_detail + $last_relation_data, $content_columns, '');
        }

        //删除缓存
        if ($data['struct_ast_id'])
        {
            $data_id = $this->obj->get_update_child_id($tablename, $con, $fromid, false);
        }
        else
        {
            $data_id = $this->obj->get_update_child_id($tablename, $con, $fromid);
        }
        if ($data_id)
        {
            include(CUR_CONF_PATH . 'lib/cache.class.php');
            $this->cache = new Cache();
            $this->cache->initialize(CUR_CONF_PATH . 'cache/' . $tablename . '/');
            $this->cache->delete($data_id);
        }

        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        foreach ($relation_ids_arr as $v)
        {
            if ($data['use_maincolumn'])
            {
                if ($rid_column_id[$v] == $content_detail['column_id'])
                {
                    $this->obj->insert('content_publish_time', array('content_id' => $v, 'publish_time' => TIMENOW));
                }
            }
            else
            {
                $this->obj->insert('content_publish_time', array('content_id' => $v, 'publish_time' => TIMENOW));
            }
        }

        //更新主内容childs_data内容
        if ($data['bundle_id'] == 'tuji')
        {
            $this->obj->insert_childs_to_content($data['bundle_id'], $data['module_id'], $data['struct_id'], 'tuji_pics', '', $v);
        }
    }

    public function update_is_complete()
    {
        $data = $this->input['data'];
        if ($data['content_rid'])
        {
            $ret = $this->obj->update_content_is_complete($data);
            if ($ret)
            {
                if (!$this->settings['is_need_audit'])
                {
                    foreach ($ret as $k => $v)
                    {
                        //插入到cotent_publish_time生成页面
                        $this->obj->insert('content_publish_time', array('content_id' => $v['id'], 'publish_time' => TIMENOW));
                    }

                    //清除memcache缓存
                    $this->memcache_flush(APP_UNIQUEID);
                }
            }
        }
        else
        {
            $this->errorOutput('NO_ID');
        }
    }

    public function update_content_relation()
    {
        $rid          = intval($this->input['id']);
        $publish_time = strtotime(trim(urldecode($this->input['publish_time'])));
        if (!$rid)
        {
            $this->errorOutput('NO_RID');
        }
        $update_data = array('publish_time' => $publish_time);
        $this->obj->update('content_relation', 'id=' . $rid, $update_data);
        //$this->obj->update('content_client_relation', 'relation_id=' . $v, array('publish_time' => $publish_time));

        if ($this->settings['is_syn_clouds'])
        {
            include_once(CUR_CONF_PATH . 'lib/content_syn.class.php');
            $content_syn = new content_syn();
            $content_syn->update_syn_content($rid,$update_data);
        }
    }

    public function update_weight()
    {
        if ($this->mNeedCheckIn && !$this->prms['weight'])
        {
            $this->errorOutput(NO_OPRATION_PRIVILEGE);
        }
        $data = json_decode(html_entity_decode($this->input['data']), 1);
        if (!is_array($data) || !$data)
        {
            $this->errorOutput('NO_FORMAT');
        }
        foreach ($data as $rid => $weight)
        {
            if ($rid)
            {
                $this->obj->update_content_relation_by_id($rid, array('weight' => $weight));
                //$this->obj->update('content_client_relation', 'relation_id=' . $rid, array('weight' => $weight));
            }
        }

        if ($this->settings['is_syn_clouds'])
        {
            include_once(CUR_CONF_PATH . 'lib/content_syn.class.php');
            $content_syn = new content_syn();
            $content_syn->update_syn_weight($data);
        }

        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $this->addItem('success');
        $this->output();
    }

    public function update_content()
    {
        $rid = intval($this->input['rid']);
        if (!$rid)
        {
            $this->errorOutput('NO_RID');
        }
        $sql  = "select id,content_id from " . DB_PREFIX . "content_relation where id=" . $rid;
        $info = $this->db->query_first($sql);
        if (isset($this->input['click_num']))
        {
            $update_relation_data['click_num'] = intval($this->input['click_num']);
        }
        if (isset($this->input['comment_num']))
        {
            $update_relation_data['comment_num'] = intval($this->input['comment_num']);
        }
        if (isset($this->input['title']))
        {
            $update_data['title'] = urldecode($this->input['title']);
            //对title每个字进行转码
            $titlestr             = '';
            if ($this->input['title'])
            {
                $update_relation_data['title_unicode'] = $this->get_titleResult($this->input['title']);
            }
        }
        if ($info['content_id'] && $update_data)
        {
            $this->obj->update_content_by_id($info['content_id'], $update_data);
        }
        if ($update_relation_data)
        {
            $this->obj->update('content_relation', ' content_id=' . $info['content_id'], $update_relation_data);
        }
    		if(trim($this->input['brief']))
        {
        		$update_content_data['brief'] = trim($this->input['brief']);
        		$this->obj->update('content', ' id=' . $info['content_id'], $update_content_data);
        }
        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $this->addItem('success');
        $this->output();
    }

    /**
      public function remk_content_html($rid,$use_maincolumn=false,$relation_ids_arr = array())
      {
      if(!$relation_ids_arr)
      {
      $relation_content = $this->obj->get_content_relation_by_id($rid);
      $relation_contents = $this->obj->get_content_relation($relation_content['content_id']);
      foreach($content as $k=>$v)
      {
      $relation_ids_arr[] = $v['id'];
      }
      }
      if(!$relation_ids_arr || !is_array($relation_ids_arr))
      {
      return false;
      }
      foreach ($relation_ids_arr as $v)
      {
      if ($data['use_maincolumn'])
      {
      if ($rid_column_id[$v] == $content_detail['column_id'])
      {
      $this->obj->insert('content_publish_time', array('content_id' => $v, 'publish_time' => TIMENOW));
      }
      }
      else
      {
      $this->obj->insert('content_publish_time', array('content_id' => $v, 'publish_time' => TIMENOW));
      }
      }
      }
     */
    //根据content.id更新内容
    public function update_content_by_cid()
    {
        $content_id = intval($this->input['content_id']);
        $data       = $this->input['data'];
        if ($data && is_array($data))
        {
            $this->obj->update('content_relation', 'content_id=' . $content_id, $data);
        }
    }
    
    //根据rid更新内容
    public function update_content_by_rid()
    {
    	$rid = intval($this->input['rid']);
    	$data       = $this->input['data'];
    	if ($data && is_array($data))
    	{
    		$relationInfo = $this->obj->get_content_relation_by_id($rid);
    		$contentId = $relationInfo['content_id'];
    		if($contentId)
    		{
    			$this->obj->update("content", 'id=' . $contentId, array(
    					'indexpic' => html_entity_decode($data['indexpic'])
    			));
    		}
    	}
    }

    public function update_xunsearch($data, $content, $content_columns)
    {
        $column_id = intval($content_columns['column_ids']);
        if ($column_id)
        {
            $column_detail = $this->column->get_column_first(' id,name,parents ', $column_id);
        }
        $tag = $column_idstr = '';
        if(is_array($column_datas = unserialize($content_columns['column_datas'])))
        {
            foreach($column_datas as $k=>$v)
            {
                $column_idstr .= $tag.$v['parents'];
                $tag=',';
            }
            $column_idstr = "'".implode("','",array_unique(explode(',',$column_idstr)))."'";
        }
        $xundata = array(
            'id' => $content['id'],
            'title' => $content['title'],
            'subtitle' => $content['subtitle'],
            'content' => $data['content'],
            'bundle_id' => $content['bundle_id'],
            'module_id' => $content['module_id'],
            'struct_id' => $content['struct_id'],
            'site_id' => $content['site_id'],
            'column_name' => $column_detail ? $column_detail['name'] : '',
            'column_ids' => $column_idstr,
            'column_datas' => $content_columns['column_datas'],
            'expand_id' => $content['expand_id'],
            'content_fromid' => $content['content_fromid'],
            'is_have_indexpic' => $content['is_have_indexpic'],
            'is_have_video' => $content['is_have_video'],
            'share_num' => $content['share_num'],
            'comment_num' => $content['comment_num'],
            'click_num' => $content['click_num'],
            'publish_time' => $content['publish_time'],
            'create_time' => $content['create_time'],
            'verify_time' => $content['verify_time'],
            'publish_user' => $content['publish_user'],
            'create_user' => $content['create_user'],
            'verify_user' => $content['verify_user'],
            'outlink' => $content['outlink'],
            'ip' => $content['ip'],
            'video' => $content['video'],
            'indexpic' => $content['indexpic'],
            'brief' => $content['brief'],
            'keywords' => $content['keywords'],
        );
        $this->xs_index($xundata, 'search_config_publish_content', 'update');
    }

    /*     * 参数:video_id(视频的id可以多个),order_id(视频的排序id),table_name(需要排序的表名)
     * 功能:对视频列表进行排序操作
     * 返回值:将视频id以逗号隔开，字符串的形式返回
     * */

    public function drag_order($table_name, $order_name,$key = 'id',$reType = 0)
    {
        if (!$this->input['content_id'])
        {
            $this->errorOutput(NOID);
        }
        $ids       = explode(',', urldecode($this->input['content_id']));
        $order_ids = explode(',', urldecode($this->input['order_id']));
        foreach ($ids as $k => $v)
        {
            $this->obj->update('content_relation', 'id=' . $v, array('order_id' => $order_ids[$k]));
            //$this->obj->update('content_client_relation', 'relation_id=' . $v, array('order_id' => $order_ids[$k]));
            //xunsearch更新
            //$this->xs_index(array('id' => $v, 'order_id' => $order_ids[$k]), 'search_config_publish_content', 'update');
        }
        //清除memcache缓存
        $this->memcache_flush(APP_UNIQUEID);

        $this->addItem(array('id' => $ids));
        $this->output();
    }

    public function get_pub_content_type()
    {
        $appdata = $this->obj->get_app();
        $this->addItem($appdata);
        $this->output();
    }

    public function audit()
    {
        if (!$this->settings['is_need_audit'])
        {
            $this->errorOutput('NO_SUPPORT');
        }
        $status = intval($this->input['audit']);
        $rid    = intval($this->input['rid']);
        if (!$rid)
        {
            $this->errorOutput('NO_RID');
        }
        include_once(CUR_CONF_PATH . 'lib/column.class.php');
        $column_obj = new column();
        if ($status == 1)
        {
            //审核
            $data         = $this->obj->get_all_content_by_relationid($rid, true);
            $crd_id       = $rid;
            $check_result = $data['cid'];

            $sql  = "select * from " . DB_PREFIX . "content_relation where content_id=" . $data['cid'] . " and status=1 and id!=" . $rid;
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $other_relate[] = $row;
                $column_ids[]   = $row['column_id'];
            }
            $column_ids[] = $data['column_id'];

            //整理内容栏目的信息，插入到xunsearch中;先查出现有的content_columns
            $column_detail_datas = $column_obj->get_column_by_id(' id,site_id,name,support_client,column_url,folderformat,fileformat ', implode(',', $column_ids), 'id');
            $firstcolumn         = $column_detail_datas[$data['column_id']];
            if ($other_relate)
            {
                $content_columns = $this->obj->get_content_columns(' * ', array('content_id' => $data['cid']));
                $column_datas    = @unserialize($content_columns['column_datas']);
                if (!$column_datas[$data['column_id']])
                {
                    $column_datas[$firstcolumn['id']] = array(
                        'id' => $firstcolumn['id'],
                        'column_url' => $firstcolumn['column_url'],
                        'name' => $firstcolumn['name'],
                        'relation_id' => $data['id'],
                    );
                    $new_content_columns              = array(
                        'column_ids' => $content_columns['column_ids'] . ',' . $data['column_id'],
                        'column_datas' => serialize($column_datas),
                    );
                    $this->obj->update_content_columns($data['cid'], $new_content_columns);
                }
            }
            else
            {
                $column_datas = array(
                    $data['column_id'] => array(
                        'id' => $data['column_id'],
                        'column_url' => $firstcolumn['column_url'],
                        'name' => $firstcolumn['name'],
                        'relation_id' => $data['id'],
                ));

                $content_columns      = array(
                    'content_id' => $data['cid'],
                    'column_ids' => $data['column_id'],
                    'column_datas' => serialize($column_datas),
                );
                $content_column_exist = $this->obj->get_content_columns(' * ', array('content_id' => $data['cid']));
                if (!$content_column_exist)
                {
                    $this->obj->insert('content_columns', $content_columns);
                }
            }
            if (!empty($data['video']))
            {
                //更新百度视频收录
                $this->obj->update_video_record($data + array('rid' => $rid));
            }

            //插入到xunsearch中
            if ($other_relate)
            {
                $this->opration_xunsearch($data, $new_content_columns, 'update', $column_detail_datas);
            }
            else
            {
                //插入到xunsearch中
                $this->opration_xunsearch($data, $content_columns, 'add', $column_detail_datas);
            }


            //插入到content_publish_time表中
            if (!$data['use_maincolumn'])
            {
                $this->obj->insert('content_publish_time', array('content_id' => $crd_id, 'publish_time' => empty($data['publish_time']) ? TIMENOW : $data['publish_time']));
            }

            //判断有无推送，有则插入到content_push表中,到各自系统里取出对应推送的栏目
            if (!$other_relate)
            {
                include_once(ROOT_PATH . 'lib/class/publishplan.class.php');
                $pub_plan         = new publishplan();
                $search_data_con  = array('from_id' => $data['content_fromid'], 'bundle_id' => $data['bundle_id'], 'module_id' => $data['module_id'], 'struct_id' => $data['struct_id']);
                $content_fromdata = $pub_plan->get_content_by_fromid($search_data_con);
                if (is_array($content_fromdata[0]) && $content_fromdata[0])
                {
                    $data['special'] = $content_fromdata[0]['special'];
                    $data['block']   = $content_fromdata[0]['block'];
                }
                $this->obj->content_push($data, $check_result, $crd_id, true);
            }

            $this->obj->update('content_relation', ' id=' . $rid, array('status' => 1));
        }
        else
        {
            //打回
            $this->obj->update('content_relation', ' id=' . $rid, array('status' => 2));

            $data = $this->obj->get_all_content_by_relationid($rid, true);

            $column_id = $data['column_id'];
            $rids[]    = $rid;
            $rids_str  = @implode(',', $rids);
            $cid       = $data['cid'];
            //删除百度视频收录
            $this->obj->update_video_record(array('rid' => $rids_str), 'del');

            //content_columns处理
            $content_column_detail  = $this->obj->get_content_columns(' * ', array('content_id' => $cid));
            $content_column_data    = @unserialize($content_column_detail['column_datas']);
            $content_column_ids_arr = explode(',', $content_column_detail['column_ids']);
            $column_idarr[]         = $data['column_id'];
            foreach ($column_idarr as $col_id)
            {
                $flkey = array_search($col_id, $content_column_ids_arr);
                if ($flkey !== false)
                {
                    unset($content_column_ids_arr[array_search($col_id, $content_column_ids_arr)]);
                    unset($content_column_data[$column_id]);
                }
            }
            $new_content_columns = array(
                'column_ids' => empty($content_column_ids_arr) ? '' : implode(',', $content_column_ids_arr),
                'column_datas' => empty($content_column_data) ? '' : serialize($content_column_data),
            );

            if (!$new_content_columns['column_datas'])
            {
                $this->obj->delete_content_columns($cid);
            }
            else
            {
                $this->obj->update_content_columns($cid, $new_content_columns);
            }

            $del_columnarr = explode(',', $column_id);
            foreach ($rids as $v)
            {
                //插入到content_publish_time表中
                $this->obj->insert('content_publish_time', array('content_id' => $v, 'publish_time' => TIMENOW));
            }

            //查询映射表中还有没有关于此内容的关联，如果没有，则删除这个内容
            $lastconrel = $this->obj->get_content_relation($cid, ' AND status=1');
            if (!empty($lastconrel))
            {
                //判断删除的栏目是不是主内容的主栏目id
                if (!in_array($content_detail['column_id'], $content_column_ids_arr))
                {
                    $this->obj->update_content_by_id($cid, array('column_id' => intval($new_content_columns['column_ids'])));
                    if ($content_detail['use_maincolumn'])
                    {
                        $sql         = "select id,column_id,file_custom_filename,file_name from " . DB_PREFIX . "content_relation where content_id=" . $data['cid'];
                        $relate_info = $this->db->query($sql);
                        while ($relate_row  = $this->db->fetch_array($relate_info))
                        {
                            if ($relate_row['id'] == intval($new_content_columns['column_ids']))
                            {
                                $main_column = $relate_row;
                            }
                            else
                            {
                                $update_relate_column[] = $main_column;
                            }
                        }
                        if ($update_relate_column)
                        {
                            foreach ($update_relate_column as $k => $v)
                            {
                                $this->obj->update('content_relation', ' id=' . $v['id'], array('file_name' => $main_column['file_name']));
                            }
                        }
                    }
                }
            }
            else
            {
                //删除推送的内容
                $this->obj->content_push(array('special' => array(), 'block' => array(), 'content_fromid' => $data['content_fromid'], 'bundle_id' => $data['bundle_id'], 'module_id' => $data['module_id']), $cid, '', true, true);
            }

            //更改xunsearch上的数据
            if (empty($new_content_columns['column_datas']))
            {
                $this->opration_xunsearch($data, array('id' => $cid) + $data, 'del');
            }
            else
            {
                $this->update_xunsearch($data, array('id' => $cid) + $data, $new_content_columns);
            }
        }
        $r = array('status' => $status == 1 ? 1 : 2, 'id' => array($rid));
        $this->addItem($r);
        $this->output();
    }

    public function opration_xunsearch($data, $content_columns, $opration, $column_detail_datas = array())
    {
        $xundata = array(
            'id' => empty($data['content_id']) ? $data['id'] : $data['content_id'],
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'content' => $data['content'],
            'bundle_id' => $data['bundle_id'],
            'module_id' => $data['module_id'],
            'struct_id' => $data['struct_id'],
            'site_id' => $data['site_id'],
            'column_name' => $content_columns['column_ids'] ? $column_detail_datas[intval($content_columns['column_ids'])]['name'] : '', //用作分类统计搜索
            'column_ids' => $content_columns['column_ids'],
            'column_datas' => $content_columns['column_datas'],
            'expand_id' => $data['expand_id'],
            'content_fromid' => $data['content_fromid'],
            'is_have_indexpic' => $data['is_have_indexpic'],
            'is_have_video' => $data['is_have_video'],
            'share_num' => $data['share_num'],
            'comment_num' => $data['comment_num'],
            'click_num' => $data['click_num'],
            'publish_time' => $data['publish_time'],
            'create_time' => $data['create_time'],
            'verify_time' => $data['verify_time'],
            'publish_user' => $data['publish_user'],
            'create_user' => $data['create_user'],
            'verify_user' => $data['verify_user'],
            'outlink' => $data['outlink'],
            'ip' => $data['ip'],
            'video' => $data['video'],
            'indexpic' => $data['indexpic'],
            'brief' => $data['brief'],
            'keywords' => $data['keywords'],
        );
        $this->xs_index($xundata, 'search_config_publish_content', $opration);
    }

    //cdn发布
    public function cdn_publish()
    {
        $rid = intval($this->input['id']);
        if (!$rid)
        {
            $this->errorOutput('NO_ID');
        }
        $content = $this->obj->get_all_content_by_relationid($rid, true);
        if (!$content)
        {
            $this->errorOutput('NO_CONTENT');
        }
        if ($this->settings['is_need_audit'])
        {
            if ($content['status'] != 1)
            {
                $this->errorOutput('NO_AUDIT');
            }
        }
        if (strstr($content['content_url'], "http") !== false)
        {
            include_once(ROOT_PATH . 'lib/class/cdn.class.php');
            $cdn = new cdn();
            $cdn->push($content['content_url'], '', '');
        }

        $this->addItem(true);
        $this->output();
    }

    /**
     * 空方法
     * @name unknow
     * @access public
     * @author repheal
     * @category hogesoft
     * @copyright 	ho	gesoft
     */
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new contentApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
