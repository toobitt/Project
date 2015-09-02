<?php
require('global.php');
define('MOD_UNIQUEID','subway');//模块标识
class subwayUpdateApi extends adminUpdateBase
{
    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/subway.class.php');
        $this->obj = new subway();
        require_once(ROOT_PATH . 'lib/class/material.class.php');
        $this->material = new material();
        
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function create() 
    {   
        #####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
        if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['sort_id'])
        {
            $sql = 'SELECT id, parents FROM '.DB_PREFIX.'subway_sort WHERE id IN('.$this->input['sort_id'].')';
            $query = $this->db->query($sql);
            while($row = $this->db->fetch_array($query))
            {
                $nodes['nodes'][$row['id']] = $row['parents'];
            }
        }
        $nodes['_action'] = 'operate';
        $this->verify_content_prms($nodes);
        #####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
        //file_put_contents('0',var_export($this->input,1));exit;
        $title = $this->input['title'];
        if(!$title)
        {
            $this->errorOutput("请填写线路名称");
        }
        $data = array(
            'title'             => $title,
            'sort_id'           => $this->input['sort_id'],
            'sign'              => $this->input['sign'],
            'color'             => $this->input['fontcolor'],
            'start'             => $this->input['start'],
            'end'               => $this->input['end'],
            'start_egname'      => $this->input['start_egname'],
            'end_egname'        => $this->input['end_egname'],
            'start_time'        => $this->input['start_time'],
            'end_time'          => $this->input['end_time'],
            'is_operate'        => $this->input['is_operate'],
            //'indexpic'          => $this->input['log'],
            'create_time'       => TIMENOW,
            'update_time'       => TIMENOW,
            'user_id'           => $this->user['user_id'],
            'user_name'         => $this->user['user_name'],                
            'ip'                => $this->user['ip'],
            'org_id'            => $this->user['org_id'],
        );
        $ret = $this->obj->create($data);
        
        $this->obj->update_data(array('order_id' => $ret), 'subway', " id IN({$ret})");
                    
        $data['id'] = $ret;
        $this->addLogs('新增线路' , '' , $data , $data['title']);
        $this->addItem($data);
        $this->output();
    }
    
    public function update()
    {   
        $title = $this->input['title'];
        if(!$title)
        {
            $this->errorOutput("请填写线路名称");
        }
        //查询修改线路之前的信息
        $sql = "SELECT * FROM " . DB_PREFIX ."subway WHERE id = " . $this->input['id'];
        $q = $this->db->query_first($sql);
        #####节点权限检测数据收集
        if($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $_sort_ids = '';
            if($q['sort_id'])
            {
                $_sort_ids = $q['sort_id'];
            }
            if($this->input['sort_id'])
            {
                $_sort_ids  = $_sort_ids ? $_sort_ids . ',' . $this->input['sort_id'] : $this->input['sort_id'];
            }
            if($_sort_ids)
            {
                $sql = 'SELECT id, parents FROM '.DB_PREFIX.'subway_sort WHERE id IN('.$_sort_ids.')';
                $query = $this->db->query($sql);
                while($row = $this->db->fetch_array($query))
                {
                    $data_['nodes'][$row['id']] = $row['parents'];
                }
            }
        }
        #####节点权限
        
        $data_['id'] = $this->input['id'];
        $data_['user_id'] = $q['user_id'];
        $data_['org_id'] = $q['org_id'];
        $data_['_action'] = 'operate';
        $this->verify_content_prms($data_);
        
        $data = array(
            'id'                => $this->input['id'],
            'title'             => $title,
            'sort_id'           => $this->input['sort_id'],
            'sign'              => $this->input['sign'],
            'color'             => $this->input['fontcolor'],
            'start'             => $this->input['start'],
            'end'               => $this->input['end'],
            'start_egname'      => $this->input['start_egname'],
            'end_egname'        => $this->input['end_egname'],
            'end_egname'        => $this->input['end_egname'],
            'start_time'        => $this->input['start_time'],
            'end_time'          => $this->input['end_time'],
            'site_count'        => $this->input['site_count'],
            'is_operate'        => $this->input['is_operate'],
            //'pic'             => $this->input['log'],
            'update_time'       => TIMENOW,
        );
        $s =  "SELECT * FROM " . DB_PREFIX . "subway WHERE id = " . $this->input['id'];
        $pre_data = $this->db->query_first($s);
        
        $ret = $this->obj->update($data);   
    
        $sq =  "SELECT * FROM " . DB_PREFIX . "subway WHERE id = " . $this->input['id'];
        $up_data = $this->db->query_first($sq);
        
        $this->addLogs('更新地铁线路' , $pre_data , $up_data , $pre_data['title']);
        $this->addItem($data);
        $this->output();
    }
    
    
    public function delete()
    {   
        $ids = urldecode($this->input['id']);
        if(empty($ids))
        {
            $this->errorOutput("请选择需要删除的地铁线路");
        }
        $sql_ = "SELECT * FROM " . DB_PREFIX  ."subway WHERE id IN (" . $ids . ")";
        $q_ = $this->db->query($sql_);
        while($row = $this->db->fetch_array($q_))
        {
            $sort_arr[] = $row['sort_id'];
            $data[$row['id']] = array(
                'title' => $row['title'],
                'delete_people' => trim(urldecode($this->user['user_name'])),
                'cid' => $row['id'],
                'catid' => $row['sort_id'],
                'user_id'=>$row['user_id'],
                'org_id'=>$row['org_id'],
                'id'=>$row['id'],
            );
            $sort_ids[] = $row['sort_id'];
        }
        
        $sqll =  "SELECT * FROM " . DB_PREFIX . "subway_relation  WHERE sub_id IN (" . $ids . ")";
        $sll = $this->db->query($sqll);
        $ret = array();
        while($rowl = $this->db->fetch_array($sll))
        {
            $re[] = $rowl;
        }
        
        if($re)
        {
            $this->errorOutput("请先删除线路下的站点");
        }   
            
        if($sort_ids)
        {
            $sql = 'SELECT id,parents FROM '.DB_PREFIX.'subway_sort WHERE id IN('.implode(',',$sort_ids).')';
            $query = $this->db->query($sql);
            $sort_ids_array = array();
            while($row = $this->db->fetch_array($query))
            {
                $sort_ids_array[$row['id']] = $row['parents'];
            }
        }
        #####整合数据进行权限
        if(!empty($data))
        {
            foreach ($data as $key=>$value)
            {
                if($value['catid'])
                {
                    $value['nodes'][$value['catid']] = $sort_ids_array[$value['catid']];
                }
                $this->verify_content_prms($value);
            }
        }
        #####整合数据进行权限结束
        $ret = $this->obj->delete($ids);
        
        $this->addItem($ret);
        $this->output();
        
    }
    
    public function audit()
    {
        $this->verify_content_prms();
        
        $id = urldecode($this->input['id']); 
        if(!$id)
        {
            $this->errorOutput("未传入地铁线路ID");
        }       
        $idArr = explode(',',$id);
        
        if(intval($this->input['audit']) == 1)
        {
            $this->obj->update_data(array('state' => 1), 'subway', " id IN({$id})");
            $return = array('status' => 1,'id'=> $idArr);   
        }
        else if(intval($this->input['audit']) == 0)
        {
            $this->obj->update_data(array('state' => 2), 'subway', " id IN({$id})");
            $return = array('status' =>2,'id' => $idArr);
        }
        
        $this->addItem($return);
        $this->output();
    }
    
    public function operate()
    {
        $id = urldecode($this->input['id']); 
        if(!$id)
        {
            $this->errorOutput("未传入地铁线路ID");
        }       
        $idArr = explode(',',$id);
        
        if(intval($this->input['is_operate']) == 1)
        {
            $this->obj->update_data(array('is_operate' => 1), 'subway', " id IN({$id})");
            $return = array('is_operate' => 1,'id'=> $idArr);   
        }
        else if(intval($this->input['is_operate']) == 0)
        {
            $this->obj->update_data(array('is_operate' => 0), 'subway', " id IN({$id})");
            $return = array('is_operate' => 0 ,'id' => $idArr);
        }
        
        $this->addItem($return);
        $this->output();
    }
    
    public function upload()
    {
        //上传图片
        if($_FILES['Filedata'])
        {
            if (!$this->settings['App_material'])
            {
                $this->errorOutput('图片服务器未安装！');
            }
            $material_pic = new material();
            $img_info = $this->material->addMaterial($_FILES,'','','-1');
            $img_data = array(
                'host'          => $img_info['host'],
                'dir'           => $img_info['dir'],
                'filepath'      => $img_info['filepath'],
                'filename'      => $img_info['filename'],
            );
            
            $data = $img_data;
            $data['cid']            = 0;//lbs的id,直接置零
            $data['original_id']    = $img_info['id'];
            $data['type']           = $img_info['type'];
            $data['mark']           = 'img';
            $data['imgwidth']       = $img_info['imgwidth'];
            $data['imgheight']      = $img_info['imgheight'];
            $data['flag']           = 1;
            $vid = $this->obj->insert_img($data);
            if($vid)
            {
            		$sql = "UPDATE " .DB_PREFIX. "subway_materials SET order_id = " .$vid. " WHERE id = " .$vid;
                $this->db->query($sql);
            		$data['id'] = $vid;
                $this->addItem($data);
                $this->output();
            }
        }
    }
    
    public function delete_img()
    {
        $ids = $this->input['id'];
        if (!$ids)
        {
            $this->errorOutput(NOID);
        }
        $ret = $this->obj->deleteMaterials($ids);
        $this->addItem($ret);
        $this->output();
    }
    
    
    public function create_site()
    {
        $site_title = $this->input['site_name'];
        if(!$site_title)
        {
            $this->errorOutput("请填写地铁站点名称");
        }
        
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if (!in_array('operate', $action))
            {
                 $this->errorOutput("NO_PRIVILEGE");
            }
        }
        
        $sub_id = $this->input['sub_id'];
        $sign  = $this->input['site_sign'];
        /*$sqll =  "SELECT id FROM " . DB_PREFIX . "subway_site WHERE title = '".$site_title."' AND sign = '".$sign."'";
        $sub_site = $this->db->query_first($sqll);
        
        if($sub_site)
        {
            $return['error'] = '站点已存在';
            $this->addItem($return);
            $this->output();exit;
        }*/
        
        $data = array(
            'title'             => $site_title,
            'sign'              => $sign,
            'brief'             => $this->input['site_brief'],
            'egname'            => $this->input['site_egname'],
            'longitude'         => $this->input['site_longitude'],
            'latitude'          => $this->input['site_latitude'],
            'site_x'            => $this->input['site_x'],
            'site_y'            => $this->input['site_y'],
            'peaktime'          => $this->input['site_peaktime'],
            'peakstart'         => $this->input['site_peakstart'],
            'peakend'           => $this->input['site_peakend'],
            'peakbrief'         => $this->input['site_peakbrief'],
            'has_toilet'        => $this->input['has_toilet'],
            'create_time'       => TIMENOW,
            'update_time'       => TIMENOW,
            'user_id'           => $this->user['user_id'],
            'user_name'         => $this->user['user_name'],                
            'ip'                => $this->user['ip'],
            'org_id'            => $this->user['org_id'],
        );
        $ret = $this->obj->insert_data($data,'subway_site');
        
        $data['id'] = $ret;
        $this->addLogs('新增站点' , '' , $data , $data['title']);
        
        $materialIds = $this->input['new_site_indexpic'];   
        if (is_array($materialIds) && !empty($materialIds))
        {
            $mids = implode(',',$materialIds);
            $sql = 'UPDATE '.DB_PREFIX.'subway_materials SET cid = '.$ret .',flag = 0,cid_type =2 WHERE id IN ('.$mids.')';
            $this->db->query($sql);
        }   
        
        if($this->input['line'] && is_array($this->input['line']))
        {
            foreach($this->input['line'] as $k=>$v)
            {
                if($v && $v !='-1')
                {
                    $stdata = $endsta =  array();
                    $stkey = $v.'_start';
                    $endkey  = $v.'_end';
                    $stdata = array(
                        'sub_id'            => $v,
                        'site_id'           => $ret,
                        'direction '        => 'start',
                        'start '            => $this->input[$stkey][0],
                        'end '              => $this->input[$endkey][0],
                    );
                    $staid = $this->obj->insert_data($stdata,'subway_relation');
                    $this->obj->update_data(array('order_id' => $staid), 'subway_relation', " id IN({$staid})");
                    
                    $endsta = array(
                        'sub_id'            => $v,
                        'site_id'           => $ret,
                        'direction '        => 'end',
                        'start '            => $this->input[$stkey][1],
                        'end '              => $this->input[$endkey][1],
                    );
                    $endid = $this->obj->insert_data($endsta,'subway_relation');
                    $this->obj->update_data(array('order_id' => $endid), 'subway_relation', " id IN({$endid})");
                    $this->obj->update_data('site_count = site_count+1' , 'subway', " id =" . $v);
                }
            }
        }
                
        $return['site_id'] = $ret;
        $return['order_id'] = $endid;
        $this->addItem($return);
        $this->output();
    }
    
    public function update_site()
    {
        $site_title = $this->input['site_name'];
        if(!$site_title)
        {
            $this->errorOutput("请填写地铁站点名称");
        }
        
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if (!in_array('operate', $action))
            {
                $this->errorOutput("NO_PRIVILEGE");
            }
        }
        
        $site_id = $this->input['site_id'];
        $data = array(
            'id'                => $site_id,
            'title'             => $site_title,
            'egname'            => $this->input['site_egname'],
            'sign'              => $this->input['site_sign'],
            'brief'             => $this->input['site_brief'],
            'longitude'         => $this->input['site_longitude'],
            'latitude'          => $this->input['site_latitude'],
            'site_x'            => $this->input['site_x'],
            'site_y'            => $this->input['site_y'],
            'peaktime'          => $this->input['site_peaktime'],
            'peakstart'         => $this->input['site_peakstart'],
            'peakend'           => $this->input['site_peakend'],
            'peakbrief'         => $this->input['site_peakbrief'],
            'has_toilet'        => $this->input['has_toilet'],
            'update_time'       => TIMENOW,
            'org_id'            => $this->user['org_id'],
        );
        
        
        $s =  "SELECT * FROM " . DB_PREFIX . "subway_site WHERE id = " . $site_id;
        $pre_data = $this->db->query_first($s);
        
        $ret = $this->obj->update_data($data,'subway_site',' id = '.$site_id);
    
        $sq =  "SELECT * FROM " . DB_PREFIX . "subway_site WHERE id = " . $site_id;
        $up_data = $this->db->query_first($sq);
        
        $this->addLogs('更新站点' , $pre_data , $up_data , $pre_data['title']);
        
        $materialIds = $this->input['new_site_indexpic'];   
        if (is_array($materialIds) && !empty($materialIds))
        {
            $mids = implode(',',$materialIds);
            //$this->obj->update_data(array('cid' => 0), 'subway_materials', " cid IN({$site_id}) AND cid_type =2");
            $sql = 'UPDATE '.DB_PREFIX.'subway_materials SET cid = '.$site_id .',flag = 0,cid_type =2 WHERE id IN ('.$mids.')';
            $this->db->query($sql);
        }   
        $sqlr = 'SELECT distinct sub_id FROM ' .DB_PREFIX. 'subway_relation  WHERE site_id = '.$site_id ;
        $qmr = $this->db->query($sqlr);
        while ($rmr = $this->db->fetch_array($qmr))
        {
            $sub_arr[] = $rmr['sub_id'];
        }
        
        if($this->input['line'] && is_array($this->input['line']))
        {
            $sub_dif = array_diff($sub_arr,$this->input['line']);
            $sub_str = implode(',',$sub_dif);
            if($sub_str)
            {
                $condition = " AND sub_id in (".$sub_str.") AND site_id = ".$site_id;
                $this->obj->delete_data($condition ,'subway_relation');
            }
            
            foreach($this->input['line'] as $k=>$v)
            {
                if($v && $v !='-1')
                {
                    if(in_array($v,$sub_arr))
                    {
                        $stkey = $v.'_start';
                        $endkey  = $v.'_end';
                        $start = array(
                            'start'     =>  $this->input[$stkey][0],
                            'end '      => $this->input[$endkey][0],
                        );
                        $conditon = " sub_id = ".$v ." AND site_id = ".$site_id ." AND direction = 'start'";
                        $this->obj->update_data($start, 'subway_relation', $conditon);
                        
                        $end = array(
                            'start'     =>  $this->input[$stkey][1],
                            'end '      => $this->input[$endkey][1],
                        );
                        $conditon = " sub_id = ".$v ." AND site_id = ".$site_id ." AND direction = 'end'";
                        $this->obj->update_data($end, 'subway_relation', $conditon);
                    }
                    else
                    {
                        $stdata = $endsta =  array();
                        $stkey = $v.'_start';
                        $endkey  = $v.'_end';
                        $stdata = array(
                            'sub_id'            => $v,
                            'site_id'           => $site_id,
                            'direction '        => 'start',
                            'start '            => $this->input[$stkey][0],
                            'end '              => $this->input[$endkey][0],
                        );
                        $staid = $this->obj->insert_data($stdata,'subway_relation');
                        $this->obj->update_data(array('order_id' => $staid), 'subway_relation', " id IN({$staid})");
                        
                        $endsta = array(
                            'sub_id'            => $v,
                            'site_id'           => $site_id,
                            'direction '        => 'end',
                            'start '            => $this->input[$stkey][1],
                            'end '              => $this->input[$endkey][1],
                        );
                        $endid = $this->obj->insert_data($endsta,'subway_relation');
                        $this->obj->update_data(array('order_id' => $endid), 'subway_relation', " id IN({$endid})");
                        $this->obj->update_data('site_count = site_count+1' , 'subway', " id =" . $v);
                    }
                }
            }
        }
        $return['site_id'] = $site_id;
        $this->addItem($return);
        $this->output();
        
    }
    
    public function delete_site()
    {   
        $id = urldecode($this->input['site_id']);
        $sub_id = $this->input['sub_id'];
        if(empty($id))
        {
            $this->errorOutput("请选择需要删除的地铁线路站点");
        }
        
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if (!in_array('delete', $action))
            {
                 $this->errorOutput("NO_PRIVILEGE");
            }
        }
        
        $ret = $this->obj->delete_site($id,$sub_id);
        
        $return['site_id'] = $id;
        $this->addItem($return);
        $this->output();
        
    }
    
    public function operate_site_gate()
    {
        //file_put_contents('00',var_export($this->input,1));exit;
        
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if (!in_array('operate', $action))
            {
                $this->errorOutput("NO_PRIVILEGE");
            }
        }
        $this->obj->delete_data(' AND type in(1,3) AND site_id = '.$this->input['site_id'] ,'subway_site_exinfo');
        
        if($this->input['new_gate_title'] && is_array($this->input['new_gate_title']))
        {
            foreach($this->input['new_gate_title'] as $k=>$v)
            {
                if($v && $v !='-1')
                {
                    $data = array(
                        'type'              => '1',
                        'site_id'           => $this->input['site_id'],
                        'brief'             => $this->input['new_gate_brief'][$k],
                        'longitude'         => $this->input['new_gate_longitude'][$k],
                        'latitude'          => $this->input['new_gate_latitude'][$k],
                        'sign'              => $this->input['new_gate_sign'][$k],
                        'title'             => $this->input['new_gate_title'][$k],
                        'order_id'          => $this->input['order_id'][$k],
                        //'indexpic '           => serialize($pic),
                    );
                    
                    $gate_id = $this->obj->insert_data($data,'subway_site_exinfo');
                    
                    $key = $k+1;
                    $materialIds = $this->input[$key.'_new_site_indexpic']; 
                    if (is_array($materialIds) && !empty($materialIds))
                    {
                        $mids = implode(',',$materialIds);
                        if($mids)
                        {
                            $sql = 'UPDATE '.DB_PREFIX.'subway_materials SET cid = '.$gate_id .',flag = 0,cid_type =3 WHERE id IN ('.$mids.')';
                            $this->db->query($sql);
                        }
                    }   
        
                    $info = array(
                        'type_id'           => $this->input[$k.'_typeid'],
                        'brief'             => $this->input[$k.'_new_extend_brief'],
                        'station_id'        => $this->input[$k.'_new_extend_station_id'],
                        'station_name'      => $this->input[$k.'_new_extend_station_name'],
                        'extend_img'        => $this->input[$k.'_extend_img'],
                    );
                    if($info['type_id'] && is_array($info['type_id']))
                    {
                        foreach($info['type_id'] as $k=>$v)
                        {
                            if($v)
                            {
                                $exdata = array(
                                    'type'              => '3',
                                    'site_id'           => $this->input['site_id'],
                                    'gate_id'           => $gate_id,
                                    'type_id'           => $v,
                                    'brief'             => $info['brief'][$k],
                                    'station_name'      => $info['station_name'][$k],
                                    'station_id'        => $info['station_id'][$k],
                                );
                                $ex_id = $this->obj->insert_data($exdata,'subway_site_exinfo');
                                
                                $mid_ = $info['extend_img'][$k];
                                if($mid_)
                                {
                                    $this->obj->update_data(array('cid' => 0), 'subway_materials', " cid IN({$v}) AND cid_type =4");
                                    
                                    $sql_ = 'UPDATE '.DB_PREFIX.'subway_materials SET cid = '.$v .',flag = 0,cid_type =4 WHERE id IN ('.$mid_.')';
                                    $this->db->query($sql_);
                                }
                                if($exdata['brief'] || $mid_)
                                {
                                    $this->obj->update_data(array('brief' => $exdata['brief'],'indexpic' => $mid_), 'subway_site_type', " id IN({$v})");
                                }
                            }
                        }
                    }
                    
                    //$exid = $this->obj->insert_subway_site_exinfo($this->input['site_id'],$gate_id, $info);
                    //$gate_id = $this->obj->insert_data($data,'subway_site_exinfo');
                }
            }
        }
        $return = array(
                'success'    => true,
        );
        $this->addItem($return);
        $this->output();
        
    }
    
    
    public function operate_site_service()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $action = array();
            $action = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
            if (!in_array('operate', $action))
            {
                $this->errorOutput("NO_PRIVILEGE");
            }
        }
        $this->obj->delete_data(' AND type =2 AND site_id = '.$this->input['site_id'] ,'subway_site_exinfo');
        if($this->input['serivce_id'] && is_array($this->input['serivce_id']))
        {
            foreach($this->input['serivce_id'] as $k=>$v)
            {
                if($v && $v !='-1')
                {
                    $pic = html_entity_decode($this->input['service_img'][$k]);
                    $data = array(
                        'type'              => '2',
                        'type_id'           => $v,
                        'site_id'           => $this->input['site_id'],
                        'brief'             => $this->input['brief'][$k],
                    );
                    if($this->input['color'][$k])
                    {
                        $data['color']  =$this->input['color'][$k];
                    }
                    $serid = $this->obj->insert_data($data,'subway_site_exinfo');
                    
                    $mids = $this->input['service_img'][$k];    
                    if ($mids)
                    {
                        $this->obj->update_data(array('cid' => 0), 'subway_materials', " cid IN({$v}) AND cid_type =4");
                                                
                        $sql = 'UPDATE '.DB_PREFIX.'subway_materials SET cid = '.$v .',flag = 0,cid_type =4 WHERE id IN ('.$mids.')';
                        $this->db->query($sql);
                    }   
                    if($data['brief'] || $data['color'] || $mids)
                    {
                        $this->obj->update_data(array('brief' => $data['brief'],'color' => $data['color'],'indexpic' => $mids), 'subway_site_type', " id IN({$v})");
                    }
                }
            }
        }
        $return = array(
                'success'    => true,
        );
        $this->addItem($return);
        $this->output();
    }
    
    public function operate_site_type()
    {
        $data = array();
        if($this->input['type'] =='delete')
        {
            $this->obj->delete_data(' AND id = '.$this->input['id'] ,'subway_site_type');
            $data['id'] = $this->input['id'];
        }
        else
        {
            $data = array(
                'type'              => $this->input['type'],
                'title'             => $this->input['type_title'],
                'sign'              => $this->input['sign'],
            );
            $ret = $this->obj->insert_data($data,'subway_site_type');
            $data['id'] = $ret;
        }
        
        $this->addItem($data);
        $this->output();
    }
    
	public function img_order()
    {
    		$order_id = $this->input['order_id'];
    		$content_id = $this->input['content_id'];
    		if($order_id && $content_id)
        {
        		$order_id = explode(',', $order_id);
        		$content_id = explode(',', $content_id);
            foreach($content_id as $k => $v)
            {
                $sql = "UPDATE " .DB_PREFIX . "subway_materials  SET order_id = '".$order_id[$k]."'  WHERE id = '".$v."'";
                $this->db->query($sql);
            }
        }
    		$this->addItem('success');
    		$this->output();
    }
    
    public function sort()
    {
    	$ids       = explode(',',urldecode($this->input['content_id']));
        $order_ids = explode(',',urldecode($this->input['order_id']));
        $sub_id    = $this->input['sub_id'];
        $flag      = $this->input['flag'];
        if($flag)
        {
            if($ids && is_array($ids))
            {
                foreach($ids as $ke => $va)
                {
                    if ($va && $sub_id)
                    {
                        $sql_ = "UPDATE " . DB_PREFIX . "subway_relation   SET order_id = '" . $order_ids[$ke] . "'  WHERE site_id = '" . $va . "'  AND sub_id =" . $sub_id;
                        $this->db->query($sql_);
                    }
                }
            }
        }
        else
        {
            if($ids && is_array($ids))
            {
                foreach($ids as $k => $v)
                {
                    $sql = "UPDATE " .DB_PREFIX . "subway  SET order_id = '".$order_ids[$k]."'  WHERE id = '".$v."'";
                    $this->db->query($sql);
                }
            }
        }
        $this->addItem(array('id' =>$ids));
        $this->output();
    }
    public function publish()
    {
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

$out = new subwayUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();

?>