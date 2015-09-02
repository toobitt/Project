<?php
/**
 * Created by PhpStorm.
 * User: maliangbin
 * Date: 15/7/22
 * Time: 上午11:18
 */
require_once './global.php';       //引入公共文件
require_once './conf/config.php';       //引入公共文件
define('MOD_UNIQUEID','rules_api');//模块标识
class RulesApi extends outerReadBase
{
    public function __construct()
    {

        parent::__construct();
        global $gGlobalConfig,$groupType;
        $this->setGroup = &$groupType;
		$this->access_token = $this->input['access_token'];
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        $this->curl = new curl($gGlobalConfig['App_shengwen']['host'], $gGlobalConfig['App_shengwen']['dir']);

    }
    public function __destruct()
    {
        parent::__destruct();
        unset($this->curl);
    }
    function count()
    {
    }

    public function show()
    {


        $this->output();
    }

    /*
     * name:appiont
     * 作用：查询该应用的下级可指派人*/
    public function appiont(){
        $bundle_id = $this->input['bundle_id'];

        //查询level
        if(in_array($this->user['group_type'],$this->setGroup)){
            $level = 0;
        }else{
            $sql = "select level from ".DB_PREFIX."relevance where appoint_id=".$this->user['user_id']." limit 1;";
            $res = $this->db->query($sql);
            $level_arr = $this->db->fetch_array($res);
            if(empty($level_arr)){
                $data_list = array('error'=>0,'message'=>"参数错误，非被指派人");
            }else{
                $level = $level_arr['level'];
                $level = $level+1;
            }
        }

        //该管理员为被指派人
        if($level >= 0){
            $level = $level + 1;

            $sql = 'select `id`,`user`,`level` from '.DB_PREFIX.'rulesdetail where level = '.$level.' and rules_id=
                (select rules_id from '.DB_PREFIX.'rulesbundle where bundle_id="'.$bundle_id.'");';

            $res = $this->db->query($sql);
            $data_list = $this->db->fetch_array($res);
            if(!empty($data_list)){
                $data_list = unserialize($data_list['user']);
            }
        }


        $this->addItem($data_list);
        $this->output();
    }

    /*查询有审核规则的应用*/
    public function detail_app(){
        $sql = 'select id,bundle_id,bundle_name from '.DB_PREFIX.'rulesbundle order by id asc;';
        $res = $this->db->query($sql);
        $data_list = array();
        while($row = $this->db->fetch_array($res)){
            $data_list[] = $row;
        }

        $this->addItem($data_list);
        $this->output();
    }

    public function detail()
    {
        $bundle_id = $this->input['bundle_id'];
        $offset = $this->input['offset'];
        $count = $this->input['count'];
        $state = $this->input['state'];
        $keywords = mysql_real_escape_string(trim($this->input['keywords']));

        //文稿的状态
        switch($state){
            case 1:  //未处理
                $article_status = 2;
                $audit_status = 0;
                break;
            case 2:  //已通过
                $article_status = 3;
                $audit_status = 2;
                break;
            case 3:  //已打回
                $article_status = 4;
                $audit_status = 1;
                break;
        }

        /*为编辑人员，查文稿库*/
        if(in_array($this->user['group_type'],$this->setGroup)){
            $sql = "select show_api from ".DB_PREFIX."rulesbundle where bundle_id='".$bundle_id."';";
            $res = $this->db->query($sql);
            $data = $this->db->fetch_array($res);
            $show_api = unserialize($data['show_api']); //获得列表显示需要请求的api
            $this->device_token = trim($this->input['device_token']);
            $this->curl = new curl($this->settings['App_'.$bundle_id]['host'],$this->settings['App_'.$bundle_id]['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('device_token', $this->device_token);
            $this->curl->addRequestData('bundle_id', $bundle_id);
            $this->curl->addRequestData('article_status', $article_status);
            $this->curl->addRequestData('access_token', $this->input['access_token']);
            $this->curl->addRequestData('key', urlencode($keywords));
            $this->curl->addRequestData('offset', $offset);
            $this->curl->addRequestData('count', $count);
            $this->curl->addRequestData('a', $show_api['a']);
            $old_data_list = $this->curl->request($show_api['api']);
            $old_data_list = (array)$old_data_list[0];
            $data_list = array();
            $content_id_str = '';
            foreach($old_data_list as $key=>$vo){
                $content_id_str .= $vo['id'].',';
                $data_list[$key]['title'] = $vo['title'];
                $data_list[$key]['id'] = $vo['id'];
                $data_list[$key]['content_id'] = $vo['id'];
                $data_list[$key]['indexpic'] = $vo['indexpic_url'];
                $data_list[$key]['state'] = $vo['state'];
                $data_list[$key]['state_show'] = $vo['audit'];
                $data_list[$key]['status'] = 1;
                $data_list[$key]['status_show'] = "未处理";
                $data_list[$key]['brief'] = $vo['brief'];
                $data_list[$key]['create_user'] = $vo['author'];
                $data_list[$key]['create_time'] = $vo['create_time_show'];
                $data_list[$key]['update_time'] = $vo['update_time_show'];
                $data_list[$key]['user_id'] = '';
                $data_list[$key]['user_name'] = '';
                $data_list[$key]['is_power'] = 1;
            }
            if(!empty($content_id_str)){
                $sql = 'select content_id,update_time,user_id,user_name,status from '.DB_PREFIX.'content where content_id in('.substr($content_id_str,0,-1).')';
                $res = $this->db->query($sql);
                $ids_data = array();
                while($row = $this->db->fetch_array($res)){
                    $ids_data[] = $row;
                }
                foreach($data_list as $key=>$vo){
                    foreach($ids_data as $k=>$v){
                        if($vo['id'] == $v['content_id']){
                            $data_list[$key]['user_id'] = $v['user_id'];
                            $data_list[$key]['user_name'] = $v['user_name'];
                            if($this->user['user_id'] != $v['user_id']){
                                $data_list[$key]['is_power'] = 0;
                            }
                            if($v['status'] ==1){
                                $data_list[$key]['is_power'] = 1;
                            }
                            switch($v['status']){
                                case 0:
                                    $data_list[$key]['status'] = 1;
                                    $data_list[$key]['status_show'] = "未处理";
                                    break;
                                case 1:
                                    $data_list[$key]['status'] = 3;
                                    $data_list[$key]['status_show'] = '已打回';
                                    break;
                                case 2:
                                    $data_list[$key]['status'] = 2;
                                    $data_list[$key]['status_show'] = '已通过';
                                    break;
                            }
                        }
                    }
                }
            }

            //查询统计接口
            $sql = "select `count_api` from ".DB_PREFIX."rulesbundle where bundle_id='".$bundle_id."';";
            $res = $this->db->query($sql);
            $count_api_data = $this->db->fetch_array($res);
            $count_api = unserialize($count_api_data['count_api']); //获得列表显示需要请求的api

            $curl1 = $this->curl;
            $curl1->setSubmitType('post');
            $curl1->setReturnFormat('json');
            $curl1->initPostData();
            $curl1->addRequestData('device_token', $this->device_token);
            $curl1->addRequestData('access_token', $this->input['access_token']);
            $curl1->addRequestData('article_status', $article_status);
            $curl1->addRequestData('key', urlencode($keywords));
            $curl1->addRequestData('a', $count_api['a']);
            $total_num = $curl1->request($count_api['api']);
            $total_num = $total_num['total'];
        }else{
            $keywords_where = '';
            if($keywords){
                $keywords_where = " and title like '%".$keywords."%' ";
            }

            $state_where = '';
            if(!$audit_status){
                $state_where = " and status = ".$audit_status." ";
            }

            $sql2= "select * from ".DB_PREFIX."content where content_id in(select content_id from ".DB_PREFIX."relevance where (user_id='".$this->user['user_id']."' or appoint_id=".$this->user['user_id'].")) ".$keywords_where." ".$state_where." order by update_time desc limit ".$offset.",".$count;
            $res = $this->db->query($sql2);
            //file_put_contents("./1112.txt",$sql2)
            $data_list = array();
            while($row = $this->db->fetch_array($res)){
                $row['create_time'] = date("Y-m-d H:i",$row['create_time']); //创建时间
                if($row['update_time']){
                    $row['update_time'] = date("Y-m-d H:i",$row['update_time']); //创建时间
                }

                //检查is_power
                if($this->user['user_id'] == $row['user_id']){
                    $row['is_power'] = 1;
                }else{
                    $row['is_power'] = 0;
                }

                //查询当前管理员最新操作状态
                $sql = "select status from ".DB_PREFIX."relevance where user_id=".$this->user['user_id']." and content_id=".$row['content_id']." order by create_time desc limit 1;";
                $status_res = $this->db->query($sql);
                $status_data = $this->db->fetch_array($status_res);

                //审核状态
                switch($status_data['status']){
                    case '0':
                        $row['status'] = 1;
                        $row['status_show'] = "未处理";
                        break;
                    case '1':
                        $row['status'] = 3;
                        $row['status_show'] = "已打回";
                        break;
                    case '2':
                        $row['status'] = 2;
                        $row['status_show'] = "已通过";
                        break;
					case '':
						$row['status'] = 1;
						$row['status_show'] = "未处理";
                        break;

                }

                switch($row['state']){
                    case '0':
                        $row['state'] = 0;
                        $row['state_show'] = '待审核';
                        break;
                    case '1':
                        $row['state'] = 1;
                        $row['state_show'] = '已审核';
                        break;
                    default:
                        $row['state'] = 2;
                        $row['state_show'] = '已打回';
                        break;
                }

                $data_list[] = $row;
            }

            //获取总数total_num
            $sql = "select COUNT(*) cnt from ".DB_PREFIX."content where content_id in(select content_id from ".DB_PREFIX."relevance where user_id='".$this->user['user_id']."' or appoint_id=".$this->user['user_id']." ) ".$keywords_where." ".$state_where;
            $res = $this->db->query($sql);
            $total_num = $this->db->fetch_array($res);
            $total_num = $total_num['cnt'];
        }

        //获取总页数
        $total_page = $total_num ? ceil($total_num/$count) : 0;
        $data_list = array('data_list'=>$data_list,'total_num'=>$total_num,'total_page'=>$total_page);
        $this->addItem($data_list);

        $this->output();
    }

    /*
     * name:detail_info
     * 作用：返回详情展示数据
     * */
    public function detail_info(){
        $bundle_id = $this->input['bundle_id'];
        $id = $this->input['id'];

        //查询该api详情接口
        $sql = "select detail_api from ".DB_PREFIX."rulesbundle where bundle_id='".$bundle_id."';";
        $res = $this->db->query($sql);
        $data = $this->db->fetch_array($res);
        $detail_api = unserialize($data['detail_api']); //获得列表显示需要请求的api

        //查询该文章信息
        $this->curl = new curl($this->settings['App_'.$bundle_id]['host'],$this->settings['App_'.$bundle_id]['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('device_token', trim($this->input['device_token']));
        $this->curl->addRequestData('access_token', $this->input['access_token']);
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('a', $detail_api['a']);
        $old_data_list = $this->curl->request($detail_api['api']);
        $old_data_list = (array)$old_data_list[0];
        $data_list = array();
        $data_list['title'] = $old_data_list['title'];
        $data_list['create_time'] = $old_data_list['create_time_show'];
        $data_list['create_user'] = $old_data_list['author'];
        $data_list['indexpic'] = $old_data_list['indexpic_url']['host'].$old_data_list['indexpic_url']['dir'].$old_data_list['indexpic_url']['filepath'].$old_data_list['indexpic_url']['filename'];

        $content     = htmlspecialchars_decode($old_data_list['content']);
        $pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r", '<script');
        $pregfind    = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '', '&#60;script');
        $content     = str_replace($pregfind, $pregreplace, $content);

        $new_arr = array('content'=>$content,'data_list'=>$data_list);
        $this->addItem($new_arr);
        $this->output();
    }


    /*
     * name:audit
     * 作用：审核
    */
    public function audit(){
        $id = $this->input['id'];
        $status = $this->input['status'];
        $bundle_id = $this->input['bundle_id'];
        $remark = mysql_real_escape_string($this->input['remark']);

        if($status == 3 && ($remark =='')){
            $this->addItem(array('error'=>0,'message'=>"打回原因不可为空"));
            $this->output();
            exit;
        }

        if(in_array($this->user['group_type'],$this->setGroup)){
            $error = 0;
            $result  = "无操作权限";
        }else{
            $level_arr = $this->power_check($id,0);
            if(($level_arr['user_id'] == $this->user['user_id']) && ($level_arr['status'] == 0)){   //有操作权限
                $sql2 = 'select `id`,`level`,`user` from '.DB_PREFIX.'rulesdetail where level='.$level_arr['level'].' and rules_id=
                    (select rules_id from '.DB_PREFIX.'rulesbundle where bundle_id="'.$bundle_id.'");';
                $res_rules = $this->db->query($sql2);
                $rules_arr = $this->db->fetch_array($res_rules);
                $tag = 0;
                $user_arr = unserialize($rules_arr['user']);
                foreach($user_arr as $vo){
                    if(in_array($this->user['user_name'],$vo)){
                        $tag = 1;
                        break;
                    }
                }
                if($tag == 1){
                    switch($status){
                        case 3:
                            $audit = 2;  //已打回
                            $article_audit = 0;
                            $new_status = 1; //已打回
                            $isdelete = 1;
                            break;
                        case 2:
                            $audit = 1;     //已通过
                            $article_audit = 1;
                            $new_status = 2;  //已通过哦
                            $isdelete = 0;
                            break;
                    }

                    //查询是否有下级审核人员
                    $sql = 'select count(*) cnt from '.DB_PREFIX.'rulesdetail where level='.($rules_arr['level']+1).' and rules_id=(select rules_id from '.DB_PREFIX.'rulesbundle where bundle_id="'.$bundle_id.'");';
                    $res = $this->db->query($sql);
                    $cnt = $this->db->fetch_array($res);
                    if($cnt['cnt']>0 && ($status!=3)){
                        $audit = 0;  //如果有下级审核人员，原状态不变
                    }

                    if((!$cnt['cnt']) || ($status == 3)){   //无下级审核人员或被打回
                        $sql5 = "select update_api from ".DB_PREFIX."rulesbundle where bundle_id='".$bundle_id."';";
                        $res = $this->db->query($sql5);
                        $data = $this->db->fetch_array($res);
                        $update_api = unserialize($data['update_api']);//获得列表显示需要请求的api 
                        $this->curl = new curl($this->settings['App_'.$bundle_id]['host'],$this->settings['App_'.$bundle_id]['dir'].'admin/');
                        $this->curl->setSubmitType('post');
                        $this->curl->setReturnFormat('json');
                        $this->curl->initPostData();
                        $this->curl->addRequestData('access_token', trim($this->input['access_token']));
                        $this->curl->addRequestData('id', $id);
                        $this->curl->addRequestData('audit', $article_audit);
                        $this->curl->addRequestData('a', $update_api['a']);
                        $this->curl->request($update_api['api']);
                    }

                    //更新内容表
                    $sql = "update ".DB_PREFIX."content set update_time='".time()."',status=".$new_status.",state=".$audit."
                        ,remark='".$remark."' where content_id=".$id." and user_id=".$this->user['user_id'].";";
                    //file_put_contents("./88.txt",$sql);
                    $result = $this->db->query($sql);

                    //向关联表中插入操作
                    //如果打回，将状态值1
                    if($status == 3){
                        $sql = 'update '.DB_PREFIX.'relevance set isdelete =1 where content_id='.$id;
                        $this->db->query($sql);
                    }
                    $sql = "insert into ".DB_PREFIX."relevance(`content_id`,`user_id`,`user_name`,`status`,`remark`,`create_time`,`level`,`isdelete`)
                         values('".$id."',".$this->user['user_id'].",'".$this->user['user_name']."',".$new_status.",'".$remark."','".time()."',".$level_arr['level'].",".$isdelete.")";
                    $this->db->query($sql);
                    if($result){
                        $error = 1;
                        $result = "审核成功";
                    }else{
                        $error = 0;
                        $result = "审核失败";
                    }
                }else{
                    $error = 0;
                    $result = "无审核权限";
                }
            }else{
                $error = 0;
                $result = "无审核权限";
            }
        }

        $this->addItem(array('error'=>$error,'message'=>$result));
        //$this->addItem($sql2);
        $this->output();
    }


    /*pass_check
    验证是否被审核*/
    public function pass_check(){
        $id = $this->input['id'];
        $sql = "select status,user_id,user_name,level from ".DB_PREFIX."content where content_id='".$id."';";
        $res = $this->db->query($sql);
        $status_arr = $this->db->fetch_array($res);
        return $status_arr;
    }

    /*name:designate
    *作用：完成指派功能
    */
    public function designate(){
        $bundle_id = $this->input['bundle_id'];
        $id = intval($this->input['id']);
        $user_id = intval($this->input['user_id']);
        $user_name = $this->input['user_name'];

        if(in_array($this->user['group_type'],$this->setGroup)){  //如果是编辑
            //查询该记录是否存在或被打回
            $res_arr = $this->pass_check();
            if(empty($res_arr)){   //没有被签发
                //向内容表添加一条记录 
                $sql = "select detail_api from ".DB_PREFIX."rulesbundle where bundle_id='".$bundle_id."';";
                $res = $this->db->query($sql);
                $data = $this->db->fetch_array($res);
                $detail_api = unserialize($data['detail_api']); //获得列表显示需要请求的api  

                //查询该文章信息 
                $this->curl = new curl($this->settings['App_'.$bundle_id]['host'],$this->settings['App_'.$bundle_id]['dir']);
                $this->curl->setSubmitType('post');
                $this->curl->setReturnFormat('json');
                $this->curl->initPostData();
                $this->curl->addRequestData('device_token', trim($this->input['device_token']));
                $this->curl->addRequestData('access_token', $this->input['access_token']);
                $this->curl->addRequestData('id', $id);
                $this->curl->addRequestData('a', $detail_api['a']);
                $data_list = $this->curl->request($detail_api['api']);
                $new_data_list = (array)$data_list[0];
                $indexpic_url = $new_data_list['indexpic_url']['host'].$new_data_list['indexpic_url']['dir'].$new_data_list['indexpic_url']['filepath'].$new_data_list['indexpic_url']['filename'];

                $sql = "insert into ".DB_PREFIX."content(`bundle_id`,`content_id`,`indexpic`,`title`,`brief`,`state`,`create_time`,`create_user`,`update_time`,`level`,`user_id`,`user_name`,`status`)
                    values('".$bundle_id."',".$id.",'".$indexpic_url."','".$new_data_list['title']."','".$new_data_list['brief']."',".$new_data_list['state'].",'".$new_data_list['create_time']."','".$new_data_list['user_name']."',
                    '".time()."','1',".$user_id.",'".$user_name."',0)";
                $result = $this->db->query($sql);
                if($result){
                    $error = 1;
                    $result = "指派成功";
                }else{
                    $error = 0;
                    $result = "指派失败";
                }
            }elseif($res_arr['status'] == 1){//被打回的，可重新签发
                $sql = "update ".DB_PREFIX."content set user_id=".$user_id.",user_name='".$user_name."',status=0,update_time='".time()."',level=1 where content_id='".$id."';";
               ///file_put_contents("./666.txt",$sql);
                $result = $this->db->query($sql);
                if($result){
                    $error = 1;
                    $result = "重新签发成功";
                }else{
                    $error = 0;
                    $result = "重新签发失败";
                }
            }else{
                $error = 0;
                $result = "不可重复签发";
            }

            if(empty($res_arr) || ($res_arr['status'] == 1)){
                //向关联表插入数据
                $sql = "insert into ".DB_PREFIX."relevance(`content_id`,`user_id`,`user_name`,`status`,`create_time`,`appoint_id`,
                    `appoint_name`,`level`) values('".$id."',".$this->user['user_id'].",'".$this->user['user_name']."',
                    0,'".time()."',".$user_id.",'".$user_name."',0)";
                $this->db->query($sql);
            }
        }else{  //为审核人员
            //查看该记录是否被审核过
            $status_arr = $this->pass_check();
            if($status_arr['user_id'] != $this->user['user_id']){
                $error = 0;
                $result = "无指派权限或非当前指派人";
            }else{
                if($status_arr['status'] == 0){
                    $error = 0;
                    $result = "请先完成审核";
                }elseif($status_arr['status'] == 2){
                    $sql = 'select id,level,user from '.DB_PREFIX.'rulesdetail where level='.$status_arr['level'].' and rules_id=
                    (select rules_id from '.DB_PREFIX.'rulesbundle where bundle_id="'.$bundle_id.'");';
                    $res_rules = $this->db->query($sql);
                    $rules_arr = $this->db->fetch_array($res_rules);
                    if($rules_arr['level']){
                        //更新内容表
                        $sql = 'update '.DB_PREFIX.'content set user_id='.$user_id.',user_name="'.$user_name.'",status=0
                                ,level = '.($rules_arr['level']+1).',update_time="'.time().'" where content_id="'.$id.'";';
                        $result = $this->db->query($sql);

                        //更新关联表
                        $sql = 'update '.DB_PREFIX.'relevance set appoint_id='.$user_id.',appoint_name="'.$user_name.'",create_time="'.time().'" where content_id
                                ="'.$id.'" and user_id='.$this->user['user_id'].';';
                        $this->db->query($sql);
                        if($result){
                            $error = 1;
                            $result = "指派成功";
                        }else{
                            $error = 0;
                            $result = "指派失败";
                        }
                    }else{
                        $error = 0;
                        $result = "无权限指派";
                    }
                }else{
                    $error = 0;
                    $result = '该文章已被打回';
                }
            }
        }

        $this->addItem(array('error'=>$error,'message'=>$result));
        $this->output();
    }

    //权限检查
    public function power_check($id,$status){
        //查询该管理员处于的层级和是否为该文章的此层级指派人
        $sql = 'select `level`,`user_id`,`user_name`,`status` from '.DB_PREFIX.'content where content_id='.$id.' and status = '.$status.';';
        $res = $this->db->query($sql);
        $level_arr = $this->db->fetch_array($res);
        return $level_arr;
    }

    //审核追踪流程
    public function trak_info(){
        //查询出该记录的所有的历史操作记录
        $id = $this->input['id'];
        $bundle_id = $this->input['bundle_id'];

        $data = array();
        $sql = "select * from ".DB_PREFIX."relevance where content_id = ".$id." order by create_time asc;";
        $res = $this->db->query($sql);
        while($row = $this->db->fetch_array($res)){
            $row['create_time'] = date("Y-m-d H:i",$row['create_time']);
            switch($row['status']){
                case 0:
                    $row['status_show'] = "未处理";
                    break;
                case 1:
                    $row['status_show'] = "已打回";
                    break;
                case 2:
                    $row['status_show'] = '已通过';
                    break;
                default:
                    $row['status_show'] = '未处理';
            }
            $data[] = $row;
        }

        $trak_arr = array();
        if(!empty($data)){
            foreach($data as $k=>$vo){
                if($vo['level'] == 0){
                    $trak = "编辑人员".$vo['user_name']."签发给".$vo['appoint_name'].",签发时间：".$vo['create_time'].' ';
                    $trak_arr[] = $trak;
                }else{
                    $trak = $vo['level']."审核人员".$vo['user_name']."审核,审核状态：".$vo['status_show']."; 指派人:".$vo['appoint_name'].
                    ", 时间：".$vo['create_time'].", 备注：".$vo['remark'];
                    $trak_arr[] = $trak;
                }
            }
        }

        $this->addItem($trak_arr);
        $this->output();
    }



    /*
     * name:getUserInfo
     * */
    public function getUserInfo(){
        $access_token = $this->input['access_token'];

        $this->curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('access_token', $access_token);
        $this->curl->addRequestData('a', 'get_user_info');
        $data_list = $this->curl->request("get_access_token.php");
        $this->addItem($data_list);
        $this->output();
    }



}
$out = new RulesApi();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'show';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();