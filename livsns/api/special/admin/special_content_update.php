<?php

require('global.php');
define('MOD_UNIQUEID', 'special_content'); //模块标识
require(ROOT_PATH . 'lib/class/curl.class.php');

class specialContentUpdateApi extends adminUpdateBase
{

    public function __construct()
    {
        parent::__construct();
        //$this->verify_node_prms(array('_action'=>'manage'));
        include(CUR_CONF_PATH . 'lib/special_content.class.php');
        $this->obj      = new specialContent();
        require_once(ROOT_PATH . 'lib/class/material.class.php');
        $this->material = new material();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {
        $special_id = $this->input['speid'];
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];

            if (!in_array('create', $action))
            {
                $return = array(
                    'error' => 'NO_PRIVILEGE',
                );
                $this->addItem($return);
                $this->output();
                exit;
            }
        }
        $sql = "select * from " . DB_PREFIX . "special where id = " . $special_id;
        $spe = $this->db->query_first($sql);

        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (!$this->user['prms']['default_setting']['manage_other_data'])
            {
                if ($this->user['user_id'] != $spe['user_id'])
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
            else
            {
                //组织以内
                if ($this->user['prms']['default_setting']['manage_other_data'] == 1 && $this->user['slave_org'])
                {
                    $sgroup = $this->user['slave_org'];
                    if ($sgroup && is_array($sgroup))
                    {
                        if (!in_array($spe['org_id'], $sgroup))
                        {
                            $return = array(
                                'error' => 'NO_PRIVILEGE',
                            );
                            $this->addItem($return);
                            $this->output();
                            exit;
                        }
                    }
                }
            }
        }

        $title = $this->input['title'];
        if (empty($title))
        {
            $return = array(
                'error' => '请填写专题内容标题',
            );
            $this->addItem($return);
            $this->output();
            exit;
        }
        /* $sql = "select id from " . DB_PREFIX . "special_content where title = '".$title."'";
          $q = $this->db->query_first($sql);
          if($q['id'])
          {
          $this->errorOutput("专题内容标题已存在");
          } */

        $info       = $child_info = array(
            'column_id' => $this->input['special_column_id'],
            'special_id' => $this->input['speid'],
            'title' => addslashes($title),
            'brief' => $this->input['brief'],
            'outlink' => $this->input['outlink'],
            'indexpic' => html_entity_decode($this->input['pic_info']),
            'source' => 1, //1手动,0选取
            'org_id' => $this->user['org_id'],
            'user_id' => $this->user['user_id'],
            'user_name' => $this->user['user_name'],
            'ip' => $this->user['ip'],
            'create_time' => TIMENOW,
            'update_time' => TIMENOW,
        );
        $ret        = $this->obj->create($info);
        $this->obj->update_special_content(array('order_id' => $ret), 'special_content', " id IN({$ret})");

        $this->obj->update_special_content('count = count+1', 'special_columns', " id =" . $info['column_id']);

        $this->obj->update_special_content('content_count = content_count+1', 'special', " id IN({$special_id})");

        $child_info['id'] = $ret;
        //$child_info['count']  = $q['num'];
        $this->obj->create_child($child_info);
        $return           = array(
            'success' => true,
            'id' => $ret,
        );
        $this->addItem($return);
        $this->output();
    }

    public function delete()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if (!in_array('delete', $action))
            {
                $this->errorOutput("NO_PRIVILEGE");
            }
        }

        $id = $this->input['id'];
        if (empty($id))
        {
            $this->errorOutput("请选择需要删除的专题内容");
        }

        $sql = "select * from " . DB_PREFIX . "special_content where id  IN({$id})";
        $sq  = $this->db->query_first($sql);
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (!$this->user['prms']['default_setting']['manage_other_data'])
            {
                if ($this->user['user_id'] != $sq['user_id'])
                {
                    $this->errorOutput("NO_PRIVILEGE");
                }
            }
            else
            {
                //组织以内
                if ($this->user['prms']['default_setting']['manage_other_data'] == 1 && $this->user['slave_org'])
                {
                    $sgroup = $this->user['slave_org'];
                    if ($sgroup && is_array($sgroup))
                    {
                        if (!in_array($sq['org_id'], $sgroup))
                        {
                            $this->errorOutput("NO_PRIVILEGE");
                        }
                    }
                }
            }
        }
        $id_arr = explode(',',$id);
        if(is_array($id_arr))
        {
        	foreach($id_arr as $k=>$v)
        	{
        		$sqll = "SELECT a.*,b.name as special_name,c.name as spesort_name,d.column_name from " . DB_PREFIX . "special_content  a " .
		                " LEFT JOIN " . DB_PREFIX . "special b ON a.special_id = b.id" .
		                " LEFT JOIN " . DB_PREFIX . "special_sort c ON b.sort_id = b.id" .
		                " LEFT JOIN " . DB_PREFIX . "special_columns d ON d.id = a.column_id" .
		                " WHERE  a.id = " . $v;
		        $rr   = $this->db->query_first($sqll);
		        if (!$rr['spesort_name'])
		        {
		            $rr['spesort_name'] = '无分类';
		        }
		        $spe_arr            = $up_info            = array();
		        $spe_arr['special'] = serialize(
		                array($rr['column_id'] => array(
		                        'id' => $rr['column_id'],
		                        'name' => $rr['column_name'],
		                        'special_id' => $rr['special_id'],
		                        'del' => '1',
		        )));
		
		
		        require_once(ROOT_PATH . 'lib/class/curl.class.php');
		
		        $host = $this->settings['App_publishplan']['host'];
		        $dir  = $this->settings['App_publishplan']['dir'] . 'admin/';
		        $curl = new curl($host, $dir);
		        $curl->setSubmitType('post');
		        $curl->initPostData();
		        $curl->addRequestData('a', 'update_content');
		
		        $curl->addRequestData('module_id', $rr['module_id']);
		        $curl->addRequestData('bundle_id', $rr['bundle_id']);
		        $curl->addRequestData('content_fromid', $rr['content_fromid']);
		        if ($spe_arr && is_array($spe_arr))
		        {
		            foreach ($spe_arr as $key => $val)
		            {
		                $curl->addRequestData('data[' . $key . ']', $val);
		            }
		        }
		        $curl->addRequestData('html', '1');
		        $re = $curl->request('publish.php');
	        }
        }
         
        $ret = $this->obj->delete($id);               
        $this->addItem($id);
        $this->output();
    }

    public function update()
    {

        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if (!in_array('update', $action))
            {
                $return = array(
                    'error' => 'NO_PRIVILEGE',
                );
                $this->addItem($return);
                $this->output();
                exit;
            }
        }

        $sql = "select * from " . DB_PREFIX . "special_content  where id = " . $this->input['id'];
        $con = $this->db->query_first($sql);

        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (!$this->user['prms']['default_setting']['manage_other_data'])
            {
                if ($this->user['user_id'] != $con['user_id'])
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
            else
            {
                //组织以内
                if ($this->user['prms']['default_setting']['manage_other_data'] == 1 && $this->user['slave_org'])
                {
                    $sgroup = $this->user['slave_org'];
                    if ($sgroup && is_array($sgroup))
                    {
                        if (!in_array($con['org_id'], $sgroup))
                        {
                            $return = array(
                                'error' => 'NO_PRIVILEGE',
                            );
                            $this->addItem($return);
                            $this->output();
                            exit;
                        }
                    }
                }
            }
        }

        $title = $this->input['title'];
        if (empty($title))
        {
            $return = array(
                'error' => "请填写专题内容标题",
            );
            $this->addItem($return);
            exit;
        }
        $info = array();

        $info = array(
            'id' => intval($this->input['id']),
            'title' => $title,
            'brief' => $this->input['brief'],
            'outlink' => $this->input['outlink'],
            'indexpic' => html_entity_decode($this->input['pic_info']),
            'source' => 1,
            'ip' => $this->user['ip'],
            'update_time' => TIMENOW,
        );

        $this->obj->update($info);

        $s = "select * from " . DB_PREFIX . "special_content_child  where id = " . $info['id'];
        $r = $this->db->query_first($s);
        if ($r)
        {
            $this->obj->update_child($info);
        }

        $return = array(
            'success' => true,
            'id' => $info['id'],
        );
        $this->addItem($return);
        $this->output();
    }

    public function update_special_column()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if ($action && is_array($action))
            {
                if (!in_array('update', $action))
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
        }


        $column_name = $this->input['column_name'];
        if (empty($column_name))
        {
            $return = array(
                'error' => "请填写专题栏目标题",
            );
            $this->addItem($return);
            exit;
        }
        $info = array();

        if ($this->input['column_dir'])
        {
            $column_dir = trim(urldecode($this->input['column_dir']), '/');
            $column_dir = '/' . $column_dir;
        }
        else
        {
            $column_dir = '';
        }

        $info = array(
            'id' => intval($this->input['id']),
            'column_name' => $column_name,
            'outlink' => $this->input['outlink'],
            'maketype' => $this->input['maketype'],
            'colindex' => $this->input['colindex'],
            'column_domain' => $this->input['column_domain'],
            'column_file' => $this->input['column_file'],
            'column_dir' => $column_dir,
        );

        $this->obj->update_special_content($info, 'special_columns', " id =" . $this->input['id']);

        $this->addItem($this->input['id']);
        $this->output();
    }

    public function upload()
    {
        $material = $this->material->addMaterial($_FILES);
        if ($material)
        {
            $material['pic'] = array(
                'host' => $material['host'],
                'dir' => $material['dir'],
                'filepath' => $material['filepath'],
                'filename' => $material['filename'],
            );
            $material['pic'] = serialize($material['pic']);

            $spe_mater = array(
                'material_id' => $material['id'],
                'material' => $material['pic'],
                'name' => $material['name'],
                'mark' => $material['mark'],
                'type' => $material['type'],
                'filesize' => $material['filesize'],
                'ip' => $this->user['ip'],
                'create_time' => TIMENOW,
            );
            $this->obj->insert_data($spe_mater, "special_material");

            $material['filesize'] = hg_bytes_to_size($material['filesize']);
            $return               = array(
                'success' => true,
                'id' => $material['id'],
                'filename' => $material['filename'] . '?' . hg_generate_user_salt(4),
                'name' => $material['name'],
                'mark' => $material['mark'],
                'type' => $material['type'],
                'filesize' => $material['filesize'],
                'path' => $material['host'] . $material['dir'],
                'dir' => $material['filepath'],
                'pic' => $material['pic'],
            );
        }
        else
        {
            $return = array(
                'error' => '文件上传失败',
            );
        }

        $this->addItem($return);
        $this->output();
    }

    public function select()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if ($action && is_array($action))
            {
                if (!in_array('create', $action))
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
        }
        $sql = "select * from " . DB_PREFIX . "special where id = " . $this->input['speid'];
        $spe = $this->db->query_first($sql);

        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if (!$this->user['prms']['default_setting']['manage_other_data'])
            {
                if ($this->user['user_id'] != $spe['user_id'])
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
            else
            {
                //组织以内
                if ($this->user['prms']['default_setting']['manage_other_data'] == 1 && $this->user['slave_org'])
                {
                    $sgroup = $this->user['slave_org'];
                    if ($sgroup && is_array($sgroup))
                    {
                        if (!in_array($spe['org_id'], $sgroup))
                        {
                            $return = array(
                                'error' => 'NO_PRIVILEGE',
                            );
                            $this->addItem($return);
                            $this->output();
                            exit;
                        }
                    }
                }
            }
        }

        if ($this->input['info'])
        {
            $error   = $success = '';
            $count = 0;
            foreach ($this->input['info'] as $k => $v)
            {
                $content_info = json_decode((html_entity_decode($v['info'])), 1);
                $s            = "select id from " . DB_PREFIX . "special_content where special_id = " . $this->input['speid'] . " and column_id = " . $this->input['special_column_id'] . " and cid =".$content_info['cid'];
                $r            = $this->db->query_first($s);

                if ($r['id'])
                {
                    $error .=',' . $content_info['title'];
                }
                else
                {
                	if($content_info['outlink'])
                	{
                		$info = array(
	                        'pub_id' => $content_info['id'],
	                        'org_id' => $this->user['org_id'],
	                        'user_id' => $this->user['user_id'],
	                        'user_name' => $this->user['user_name'],
	                        'title' => addslashes($content_info['title']),
	                        'special_id' => $this->input['speid'],
	                        'column_id' => $this->input['special_column_id'],
	                        'columns' => $content_info['column_id'],
	                        'source' => 1, //1手动,0选取
	                        'state' => 1, //1已审核
	                        'weight' => $content_info['weight'],
	                        'content_fromid' => $content_info['content_fromid'],
	                        'outlink' => $content_info['outlink'],
	                        'indexpic' => addslashes(serialize($content_info['indexpic'])),
	                        'module_id' => $content_info['module_id'],
	                        'bundle_id' => $content_info['bundle_id'],
	                        'cid' => $content_info['cid'],
	                        //'ip'				=> $content_info['ip'],
	                        'create_time' => strtotime($content_info['create_time']),
	                        'update_time' => TIMENOW,
	                    );
                	}
                	else
                	{
                		$info = array(
	                        'pub_id' => $content_info['id'],
	                        'org_id' => $this->user['org_id'],
	                        'user_id' => $this->user['user_id'],
	                        'user_name' => $this->user['user_name'],
	                        'title' => addslashes($content_info['title']),
	                        'special_id' => $this->input['speid'],
	                        'column_id' => $this->input['special_column_id'],
	                        'columns' => $content_info['column_id'],
	                        'source' => 0, //1手动,0选取
	                        'state' => 1, //1已审核
	                        'weight' => $content_info['weight'],
	                        'content_fromid' => $content_info['content_fromid'],
	                        'indexpic' => addslashes(serialize($content_info['indexpic'])),
	                        'module_id' => $content_info['module_id'],
	                        'bundle_id' => $content_info['bundle_id'],
	                        'cid' => $content_info['cid'],
	                        //'ip'				=> $content_info['ip'],
	                        'create_time' => strtotime($content_info['create_time']),
	                        'update_time' => TIMENOW,
	                    );
                	}
                    
                    if ($content_info['column_name'] && $content_info['main_column_id'])
		            {
		            	$column_content = array();
		            	$column_content[$content_info['main_column_id']]  = $content_info['column_name'];
		                $info['column_content'] = addslashes(serialize($column_content));
		            }
		            if ($content_info['id'] && $content_info['main_column_id'])
		            {
		            	$column_url = array();
		            	$column_url[$content_info['main_column_id']]  = $content_info['id'];
		                $info['column_url'] = addslashes(serialize($column_url));
		            }
                    $ret  = $this->obj->storedIntoDB($info, 'special_content', 1);
                    $count++;
                    $this->obj->update_special_content(array('order_id' => $ret), 'special_content', " id IN({$ret})");
                    if ($ret)
                    {
                        $success .= ',' . $content_info['id'];

                        $sqll = "SELECT a.name as special_name,b.name as spesort_name,c.column_name from " . DB_PREFIX . "special  a " .
                                " LEFT JOIN " . DB_PREFIX . "special_sort b ON a.sort_id = b.id" .
                                " LEFT JOIN " . DB_PREFIX . "special_columns c ON c.special_id = a.id" .
                                " WHERE  a.id = " . $this->input['speid'] .
                                " AND c.id = " . $this->input['special_column_id'];
                        $rr   = $this->db->query_first($sqll);
                        if (!$rr['spesort_name'])
                        {
                            $rr['spesort_name'] = '无分类';
                        }
                        $spe_arr            = $up_info            = array();
                        $spe_arr['special'] = serialize(
                                array($this->input['special_column_id'] => array(
                                        'id' => $this->input['special_column_id'],
                                        'name' => $rr['column_name'],
                                        'special_id' => $this->input['speid'],
                                        'show_name' => $rr['spesort_name'] . ' &gt; ' . $rr['special_name'] . ' &gt; ' . $rr['column_name'],
                        )));


                        require_once(ROOT_PATH . 'lib/class/curl.class.php');

                        $host = $this->settings['App_publishplan']['host'];
                        $dir  = $this->settings['App_publishplan']['dir'] . 'admin/';
                        $curl = new curl($host, $dir);
                        $curl->setSubmitType('post');
                        $curl->initPostData();
                        $curl->addRequestData('a', 'update_content');

                        $curl->addRequestData('module_id', $info['module_id']);
                        $curl->addRequestData('bundle_id', $info['bundle_id']);
                        $curl->addRequestData('content_fromid', $info['content_fromid']);
                        if ($spe_arr && is_array($spe_arr))
                        {
                            foreach ($spe_arr as $key => $val)
                            {
                                $curl->addRequestData('data[' . $key . ']', $val);
                            }
                        }
                        $curl->addRequestData('html', '1');
                        $re = $curl->request('publish.php');
                    }
                }
            }
        }

        $special_id = $this->input['speid'];

        //$q = $this->obj->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id . " AND column_id = " . $this->input['special_column_id']);
        $this->obj->update_special_content('count = count+'.$count, 'special_columns', " id =" . $this->input['special_column_id']);

        //$count = $this->obj->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id);
        $this->obj->update_special_content('content_count = content_count+'.$count, 'special', " id IN({$special_id})");

        $sqll = "DELETE FROM " . DB_PREFIX . "special_content_child WHERE special_id = " . $this->input['speid'] . " AND column_id = " . $this->input['special_column_id'];
        $this->db->query($sqll);

        $sql = "SELECT *
				FROM  " . DB_PREFIX . "special_content
				WHERE special_id = " . $this->input['speid'] . " AND column_id = " . $this->input['special_column_id'] . " ORDER BY id DESC LIMIT 5";
        $q_  = $this->db->query($sql);

        while ($row = $this->db->fetch_array($q_))
        {
            $this->obj->create_child($row);
        }
        $return = array();
        if ($error)
        {
            $return['con_error'] = ltrim($error, ',');
        }
        if ($success && !$error)
        {
            $return['success'] = '内容添加成功';
        }
        if ($return)
        {
            $this->addItem($return);
            $this->output();
        }
    }

    public function create_column()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if ($action && is_array($action))
            {
                if (!in_array('create', $action))
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
        }

        $info = array(
            'column_name' => $this->input['name'],
            'special_id' => intval($this->input['speid']),
        );

        $ret = $this->obj->insert_data($info, 'special_columns');

        $this->obj->update_special_content(array('order_id' => $ret), 'special_columns', " id IN({$ret})");

        $return = array(
            'success' => true,
            'id' => $ret,
            'name' => $this->input['name'],
        );
        $this->addItem($return);
        $this->output();
    }

    public function del_column()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if ($action && is_array($action))
            {
                if (!in_array('delete', $action))
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
        }

        $sq = "select count(*) as count from " . DB_PREFIX . "special_content where column_id = " . $this->input['column_id'];
        $q  = $this->db->query_first($sq);
        if ($q['count'])
        {
            $return = array(
                'error' => "请删除栏目下内容",
                'column_id' => $this->input['column_id'],
            );
            $this->addItem($return);
        }
        else
        {
            $sql    = "DELETE FROM " . DB_PREFIX . "special_columns where id = " . $this->input['column_id'];
            $this->db->query($sql);
            $return = array(
                'success' => true,
                'column_id' => $this->input['column_id'],
            );
            $this->addItem($return);
        }
        $this->output();
    }

    public function update_column()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if ($action && is_array($action))
            {
                if (!in_array('update', $action))
                {
                    $return = array(
                        'error' => 'NO_PRIVILEGE',
                    );
                    $this->addItem($return);
                    $this->output();
                    exit;
                }
            }
        }


        $this->obj->update_special_content(array('column_name' => $this->input['name']), 'special_columns', " id =" . $this->input['column_id']);
        $return = array(
            'success' => true,
            'column_id' => $this->input['column_id'],
            'name' => $this->input['name'],
        );
        $this->addItem($return);
        $this->output();
    }

    public function update_weight()
    {
        //检测
        if (empty($this->input['data']))
        {
            $this->errorOutput(NO_DATA);
        }
        $data  = $this->input['data'];
        $data  = htmlspecialchars_decode($data);
        $data  = json_decode($data, 1);
        $sql   = "CREATE TEMPORARY TABLE tmp (id int primary key, weight int)";
        $this->db->query($sql);
        $sql   = "INSERT INTO tmp VALUES ";
        $space = '';
        $id    = array();
        foreach ($data as $k => $v)
        {
            $id[]  = $k;
            $sql .= $space . "(" . $k . ", " . $v . ")";
            $this->check_weight_prms($v);
            $space = ',';
        }
        $this->db->query($sql);
        $sql = "UPDATE " . DB_PREFIX . "special_content a,tmp SET a.weight = tmp.weight WHERE a.id = tmp.id";
        $this->db->query($sql);

        $id = implode(',', $id);
        //$this->addLogs('修改权重','','', '修改权重' . $id);
        $this->addItem('true');
        $this->output();
    }

    /**
     * 设置权重
     * @name 		update_weight
     */
    public function check_weight_prms($input_weight = 0, $org_weight = 0)
    {
        if ($this->user['group_type'] < MAX_ADMIN_TYPE)
        {
            return;
        }
        $set_weight_limit = $this->user['prms']['default_setting']['set_weight_limit'];
        if ($org_weight > $set_weight_limit)
        {
            $this->errorOutput(MAX_WEIGHT_LIMITED);
        }
        if ($input_weight > $set_weight_limit)
        {
            $this->errorOutput(MAX_WEIGHT_LIMITED);
        }
    }

    public function audit()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if ($action && is_array($action))
            {
                if (!in_array('audit', $action))
                {
                    $this->errorOutput("NO_PRIVILEGE");
                }
            }
        }

        $id = urldecode($this->input['id']);
        if (!$id)
        {
            $this->errorOutput("未传入专题ID");
        }
        $idArr = explode(',', $id);

        if (intval($this->input['audit']) == 1)
        {
            $this->obj->update_special_content(array('state' => 1), 'special_content', " id IN({$id})");
            $return = array('status' => 1, 'id' => $idArr, 'color' => $this->settings['state_color']['1']);
        }
        else if (intval($this->input['audit']) == 0)
        {
            $this->obj->update_special_content(array('state' => 2), 'special_content', " id IN({$id})");
            $return = array('status' => 2, 'id' => $idArr, 'color' => $this->settings['state_color']['2']);
        }

        //$this->addLogs($opration,'','',$opration . '+' . $id);	
        $this->addItem($return);
        $this->output();
    }

    public function drag_order()
    {
        $ids       = explode(',', $this->input['content_id']);
        $order_ids = explode(',', $this->input['order_id']);
        foreach ($ids as $k => $v)
        {
            $sql = "UPDATE " . DB_PREFIX . "special_content  SET order_id = '" . $order_ids[$k] . "'  WHERE id = '" . $v . "'";
            $this->db->query($sql);
        }

        $this->addItem(array('id' => $ids));
        $this->output();
    }

    public function drag_col_order()
    {
        $ids       = explode(',', $this->input['id']);
        $order_ids = explode(',', $this->input['order_id']);

        if ($ids && is_array($ids))
        {
            foreach ($ids as $k => $v)
            {
                $sql = "UPDATE " . DB_PREFIX . "special_columns  SET order_id = '" . $order_ids[$k] . "'  WHERE id = '" . $v . "'";
                $this->db->query($sql);
            }
        }

        $this->addItem(array('id' => $ids));
        $this->output();
    }

    public function sort()
    {
        
    }

    public function publish()
    {
        
    }

    public function insert_special_con()
    {
        $data     = $this->input['data'];
        $special  = $this->input['data']['special'];
        $spe_info = $sp_arr   = array();

        if ($special && $data['content_id'])
        {
            foreach ($special as $k => $v)
            {
                $special_id = $v['special_id'];
                $info       = array();
                $info       = array(
                    'cid' => $data['cid'],
                    'pub_id' => $data['content_id'],
                    'content_fromid' => $data['content_fromid'],
                    'column_id' => $k,
                    'columns' => $data['column_id'],
                    'column_url' => $data['column_url'],
                    'column_content	' => addslashes(serialize($data['publish_content_columns'])),
                    'special_id' => $v['special_id'],
                    'title' => $data['title'],
                    'brief' => $data['brief'],
                    'indexpic' => addslashes(serialize($data['indexpic'])),
                    'weight' => $data['weight'],
                    'state' => '1',
                    'source' => '2',
                    'outlink' => $data['outlink'],
                    'module_id' => $data['module_id'],
                    'bundle_id' => $data['bundle_id'],
                    'user_id' => $this->user['user_id'],
                    'user_name' => $this->user['user_name'],
                    'ip' => $this->user['ip'],
                    'create_time' => TIMENOW,
                    'update_time' => TIMENOW,
                );
                
                $sql = "select id from ".DB_PREFIX."special_content where cid='".$data['cid']."' AND column_id='".$k."'";
                $sc = $this->db->query_first($sql);
                if($sc)
                {
                    continue;
                }
                
                $sp_arr[] = $k;
                $ret      = $this->obj->create($info);


                $this->obj->update_special_content(array('order_id' => $ret), 'special_content', " id IN({$ret})");
                //$q = $this->obj->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id . " AND column_id = " . $k);
                $this->obj->update_special_content('count = count+1' , 'special_columns', " id =" . $k);

                //$count = $this->obj->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id);
                $this->obj->update_special_content('content_count = content_count+1' , 'special', " id IN({$special_id})");
            }

            /* $up_info = array(
              'special' => implode(',', $sp_arr),
              );

              require_once(ROOT_PATH . 'lib/class/curl.class.php');

              $host = $this->settings['App_publishplan']['host'];
              $dir  = $this->settings['App_publishplan']['dir'] . 'admin/';
              $curl = new curl($host, $dir);
              $curl->setSubmitType('get');
              $curl->initPostData();
              //$curl->addRequestData('a', 'update_content');

              $curl->addRequestData('module_id', $data['module_id']);
              $curl->addRequestData('bundle_id', $data['bundle_id']);
              $curl->addRequestData('content_fromid', $data['content_fromid']);
              if ($up_info && is_array($up_info))
              {
              foreach ($up_info as $key => $val)
              {
              $curl->addRequestData('data[' . $key . ']', $val);
              }
              }

              $curl->addRequestData('html', '1');
              //$re = $curl->request('publish.php'); */
        }

        $this->addItem('ture');
        $this->output();
    }

    public function update_special_con()
    {
        $data    = $this->input['data'];
        $special = $this->input['data']['special'];

        if ($special)
        {
            foreach ($special as $k => $v)
            {
                $special_id = $v['special_id'];
                $info       = array();
                $info       = array(
                    'cid' => $data['cid'],
                    'pub_id' => $data['content_id'],
                    'content_fromid' => $data['content_fromid'],
                    //'column_id'			=> $k,
                    //'special_id'		=> $v['special_id'],
                    'columns' => $data['column_id'],
                    'column_url' => $data['column_url'],
                    'column_content' => addslashes(serialize($data['publish_content_columns'])),
                    'title' => $data['title'],
                    'brief' => $data['brief'],
                    'indexpic' => addslashes(serialize($data['indexpic'])),
                    //'weight' => $data['weight'],
                    'outlink' => $data['outlink'],
                    'module_id' => $data['module_id'],
                    'bundle_id' => $data['bundle_id'],
                    //'user_id' => $data['user_id'],
                    //'user_name' => $data['publish_user'],
                    'ip' => $data['ip'],
                    //'create_time'		=> TIMENOW,
                    'update_time' => TIMENOW,
                );
                //$up_con['bundle_id']      = $data['bundle_id'];
                //$up_con['module_id']      = $data['module_id'];
                //$up_con['content_fromid'] = $data['content_fromid'];
                $this->obj->update_content($info);
                $sp_arr[]   = $k;
            }

            /* $up_info = array(
              'special' => implode(',', $sp_arr),
              );

              require_once(ROOT_PATH . 'lib/class/curl.class.php');

              $host = $this->settings['App_publishplan']['host'];
              $dir  = $this->settings['App_publishplan']['dir'] . 'admin/';
              $curl = new curl($host, $dir);
              $curl->setSubmitType('get');
              $curl->initPostData();
              //$curl->addRequestData('a', 'update_content');

              $curl->addRequestData('module_id', $data['module_id']);
              $curl->addRequestData('bundle_id', $data['bundle_id']);
              $curl->addRequestData('content_fromid', $data['content_fromid']);
              if ($up_info && is_array($up_info))
              {
              foreach ($up_info as $key => $val)
              {
              $curl->addRequestData('data[' . $key . ']', $val);
              }
              }

              $curl->addRequestData('html', '1');
              //$re = $curl->request('publish.php'); */
        }

        $this->addItem('ture');
        $this->output();
    }

    public function delete_special_cont()
    {
        $spe_columns = $this->input['data'];
        $pub_id      = $this->input['content_id'];
        //$content_fromid = intval($this->input['content_data']['content_fromid']);
        //$bundle_id      = $this->input['content_data']['bundle_id'];
        //$module_id      = $this->input['content_data']['module_id'];
        if ($spe_columns && $pub_id)
        {
            $sql = "DELETE FROM " . DB_PREFIX . "special_content WHERE cid = " . $pub_id . " AND column_id in(" . $spe_columns . ")";
            //$sql = "DELETE FROM " . DB_PREFIX . "special_content WHERE content_fromid='$content_fromid' AND bundle_id='$bundle_id' AND module_id='$module_id' AND column_id in(" . $spe_columns . ")";
            $this->db->query($sql);

            $sql_ = "DELETE FROM " . DB_PREFIX . "special_content_child WHERE cid = " . $pub_id . " AND column_id in(" . $spe_columns . ")";
            //$sql_ = "DELETE FROM " . DB_PREFIX . "special_content_child WHERE content_fromid='$content_fromid' AND bundle_id='$bundle_id' AND module_id='$module_id' AND column_id in(" . $spe_columns . ")";
            $this->db->query($sql_);

            $info = $this->obj->get_content_info('*', 'special_columns ', "  where id IN(" . $spe_columns . ")");
            if ($info && is_array($info))
            {
                foreach ($info as $ke => $va)
                {
                    $speids[$va['id']] = $va['special_id'];
                }
            }
            $colid_arr = explode(',', $spe_columns);
            if ($colid_arr && is_array($colid_arr))
            {
                foreach ($colid_arr as $k => $v)
                {
                    $special_id = $speids[$v];
                    $q          = $this->obj->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id . " AND column_id = " . $v);

                    $this->obj->update_special_content(array('count' => $q['num']), 'special_columns', " id =" . $v);

                    $count = $this->obj->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id);
                    $this->obj->update_special_content(array('content_count' => $count['num']), 'special', " id IN({$special_id})");
                }
            }
        }
        $this->addItem('ture');
        $this->output();
    }

    public function push_special()
    {
    }

    /**
     * 移动专题内容
     */
    public function ch_column()
    {
    	$id = $this->input['id'];
        $special_id = $this->input['speid'];
        $column_id = $this->input['column_id'];
        $column_name = $this->input['column_name'];
		$oldcol_idarr = explode(',',$this->input['old_columnid']);
		$id_arr  = explode(',',$id);
		$bundle_arr  = explode(',',$this->input['bundle_id']);
		$module_arr  = explode(',',$this->input['module_id']);
		$content_fromid_arr  = explode(',',$this->input['content_fromid']);
		$cid	= 	explode(',',$this->input['cid']);
		
		$ss = "SELECT cid FROM " . DB_PREFIX . "special_content WHERE column_id =  ". $column_id;
		$qs       = $this->db->query($ss);
		while ($rs = $this->db->fetch_array($qs))
        {
        	if($rs['cid'])
        	{
        		$cid_arr[] = $rs['cid'];
        	}
        }
		if($cid && is_array($cid))
		{
			foreach($cid as $k=>$v)
			{
				if(in_array($v,$cid_arr))
				{
					unset($id_arr[$k]);
					unset($bundle_arr[$k]);
					unset($oldcol_idarr);
					unset($module_arr[$k]);
					unset($content_fromid_arr[$k]);
				}
			}
		}
		$id = implode(',',$id_arr);
		
		if($id && $column_id)
        {
            $this->db->update_data(array('column_id'=>$column_id), 'special_content', ' id IN('.$id.')');
            $sqll = "SELECT a.name as special_name,b.name as spesort_name FROM  " . DB_PREFIX . "special  a " .
                " LEFT JOIN " . DB_PREFIX . "special_sort b ON a.sort_id = b.id" .
                " WHERE  a.id = " . $special_id ;
	        $rr   = $this->db->query_first($sqll);
	        if (!$rr['spesort_name'])
	        {
	            $rr['spesort_name'] = '无分类';
	        }
	        if($id_arr && is_array($id_arr))
	        {
	        	foreach($id_arr as $k=>$v)
				{
					if($oldcol_idarr[$k] != $column_id)
					{
						if($bundle_arr[$k] && $module_arr[$k] && $content_fromid_arr[$k] )
						{
							$spe_arr   = array();
		                    $spe_arr['special'] = serialize(
		                    	array($column_id => array(
		                            'id' => $column_id,
		                            'name' => $column_name,
		                            'special_id' => $special_id,
		                            'show_name' => $rr['spesort_name'] . ' &gt; ' . $rr['special_name'] . ' &gt; ' . $column_name,
		                    )));
		
		
		                    require_once(ROOT_PATH . 'lib/class/curl.class.php');
		
		                    $host = $this->settings['App_publishplan']['host'];
		                    $dir  = $this->settings['App_publishplan']['dir'] . 'admin/';
		                    $curl = new curl($host, $dir);
		                    $curl->setSubmitType('post');
		                    $curl->initPostData();
		                    $curl->addRequestData('a', 'update_content');
		
		                    $curl->addRequestData('module_id', $module_arr[$k]);
		                    $curl->addRequestData('bundle_id', $bundle_arr[$k]);
		                    $curl->addRequestData('content_fromid', $content_fromid_arr[$k]);
		                    $curl->addRequestData('delete_column_id', $oldcol_idarr[$k]);
		                    if ($spe_arr && is_array($spe_arr))
		                    {
		                        foreach ($spe_arr as $key => $val)
		                        {
		                            $curl->addRequestData('data[' . $key . ']', $val);
		                        }
		                    }
		                    $curl->addRequestData('html', '1');
		                    $re = $curl->request('publish.php');
						}
					}
				}
	        }
        }
      	$ret = array('success' => true);
      	$this->addItem($ret);
      	$this->output(); 
    }

    function array_to_add($str, $data)
    {
        $str = $str ? $str : 'data';
        if (is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if (is_array($vv))
                {
                    $this->array_to_add($str . "[$kk]", $vv);
                }
                else
                {
                    $curl->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }

    public function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new specialContentUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>