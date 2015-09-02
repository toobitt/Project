<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
require_once(ROOT_PATH . 'lib/class/mediaserver.class.php');
require_once(ROOT_PATH . 'lib/class/livmedia.class.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class ClassSeekhelp extends InitFrm
{
    private $media;
    private $members;
    private $livmedia;
	public function __construct()
    {
        parent::__construct();
        $this->material = new material();
        $this->member = new member();
        $this->media = new mediaserver();
        $this->members = new members();
        $this->livmedia = new livmedia();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     *
     * @Description  有官方回复优先显示官方回复，其次为推荐答案
     * @author Kin
     * @date 2013-6-14 上午10:23:48
     */
    public function show($condition, $orderby, $offset, $count,$uid = 0)
    {
    	if(!$offset && !$count)
        {
        	$limit = '';
        }
        else 
        {
	    	$limit = " limit {$offset}, {$count}";
        }
        $sql = 'SELECT  sh.*, c.content,
                a.name AS account_name, a.avatar as account_avatar,
                s.name AS sort_name,se.name AS section_name
                FROM '.DB_PREFIX.'seekhelp  sh
                LEFT JOIN '.DB_PREFIX.'content c ON sh.id = c.id
                LEFT JOIN '.DB_PREFIX.'account a ON sh.account_id = a.id
                LEFT JOIN '.DB_PREFIX.'section se ON sh.section_id = se.id
                LEFT JOIN '.DB_PREFIX.'sort s ON sh.sort_id = s.id
                WHERE 1 '. $condition.$orderby.$limit;

        $query = $this->db->query($sql);
        $res = array();
        $replyIds = array();
        $commentIds = array();
        $memberIds = array();
        $Ids = array();
        $myJoint = array(); //我联名的数据
        $myAttention = array(); //我关注的数据
        while ($row = $this->db->fetch_array($query))
        {
            $row['title'] = stripcslashes($row['title']);
            $row['content'] = hg_back_value(stripcslashes(urldecode($row['content'])));
            $row['section_name'] = hg_back_value($row['section_name']);
            if ($row['is_reply'] && $row['reply_id'])
            {
                $replyIds[$row['id']] = $row['reply_id'];
            }
            if (!$row['is_reply'] && $row['comment_id'] && $row['is_recommend'])
            {
                $commentIds[$row['id']] = $row['comment_id'];
            }
            $account_avatar = unserialize($row['account_avatar']);
            $row['account_avatar'] = $account_avatar ? $account_avatar : '';
            $banword = $row['banword'] ? unserialize($row['banword']) : '';
            $row['banword'] = $banword ? $banword : '';
            $row['pass_time'] = TIMENOW-$row['create_time'];
            $row['format_create_time'] = date('Y-m-d H:i:s', $row['create_time']);
            
            $row['status_name'] = $this->settings['seekhelp_status'][$row['status']];
            switch ($row['status'])
            {
                case 0 : $row['status_name'] = '待审核';break;
                case 1 : $row['status_name'] = '已审核';break;
                case 2 : $row['status_name'] = '被打回';break;
            }
            switch ($row['is_push'])
            {
                case 0 : $row['push_status'] = '未推送';break;
                case 1 : $row['push_status'] = '已推送';break;
            }
            $row['state'] = $row['status'];    //只为纪录操作使用，其他无用
            $memberIds[$row['id']] = $row['member_id'];
            $Ids[$row['id']] = $row['reply_id'];
            $res[] = $row;
        }
        if (!empty($replyIds))
        {
            $reply_ids = implode(',', $replyIds);
            $sql = 'SELECT id, content FROM '.DB_PREFIX.'reply WHERE id IN ('.$reply_ids.')';
            $query = $this->db->query($sql);
            $replys = array();
            while ($row = $this->db->fetch_array($query))
            {
                $replys[$row['id']] = $row['content'];
            }
        }
        if (!empty($commentIds))
        {
            $comment_ids = implode(',', $commentIds);
            $sql = 'SELECT * FROM '.DB_PREFIX.'comment WHERE id IN ('.$comment_ids.')';
            $query = $this->db->query($sql);
            $comments = array();
            while ($row = $this->db->fetch_array($query))
            {
                $comments[$row['id']] = $row;
            }
        }
        if (!empty($memberIds))
        {
            $member_ids = implode(',', $memberIds);
            //新旧会员处理
            if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
            {
                if ($this->settings['App_members'])
                {
                    $members = array();
                    $temp_members = $this->members->get_newUserInfo_by_ids($member_ids);
                    if ($temp_members && !empty($temp_members) && is_array($temp_members))
                    {
                        foreach ($temp_members as $val)
                        {
                            $members[$val['member_id']] = $val;
                        }
                    }
                    //hg_pre($members);exit();
                }
            }
            else
            {
                if ($this->settings['App_member'])
                {
                    $members = $this->member->getMemberByIds($member_ids);
                    $members = $members[0];

                }
            }

        }
        if (!empty($Ids))
        {
            $_ids = implode(',', array_keys($Ids));
            $comment_nums = $this->comment_num($_ids);
            $attention_nums = $this->attention_num($_ids);
            $joint_nums = $this->joint_num($_ids);
            $materials = $this->handle_materials($Ids);
            if ($uid)
            {
                //查询列表中是否有我联名的数据
                $sql = 'SELECT cid FROM '.DB_PREFIX.'joint 
                        WHERE member_id  = '.intval($uid).' AND cid IN ('.$_ids.')';
                $query = $this->db->query($sql);
                while ($row = $this->db->fetch_array($query))
                {
                    $myJoint[] = $row['cid'];
                }               
                //查询列表中是否有我关注的数据
                $sql = 'SELECT cid FROM '.DB_PREFIX.'attention 
                        WHERE member_id  = '.intval($uid).' AND cid IN ('.$_ids.')';
                $query = $this->db->query($sql);
                while ($row = $this->db->fetch_array($query))
                {
                    $myAttention[] = $row['cid'];
                }
            }
        }
        foreach ($res as $key=>$val)
        {
            $res[$key]['is_joint'] = in_array($val['id'], $myJoint) ? '1' : '0';
            $res[$key]['is_attention'] = in_array($val['id'], $myAttention) ? '1' : '0';
            if($materials&&$materials[$val['id']]&&is_array($materials[$val['id']]))
            {
                foreach ($materials[$val['id']] as $k=>$v)
                {
                    $res[$key][$k]=$v;
                }
            }
            if ($val['is_reply'] && $val['reply_id'])
            {   
                $res[$key]['reply'] = $replys[$val['reply_id']];
            }
            if (!$val['is_reply'] && $val['comment_id'] && $val['is_recommend'])
            {
            	$res[$key]['reply'] = $comments[$val['comment_id']];
            }
            if ($members[$val['member_id']])
            {
                if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
                {
                    $res[$key]['member_name'] = IS_HIDE_MOBILE ? hg_hide_mobile($members[$val['member_id']]['nick_name']) : $members[$val['member_id']]['nick_name'];
                    $res[$key]['member_avatar'] = $members[$val['member_id']]['avatar'];
                    $res[$key]['member_level'] = intval($members[$val['member_id']]['digital']);
                }
                else
                {
                    $res[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($members[$val['member_id']]['nick_name']):$members[$val['member_id']]['nick_name'];
                    $res[$key]['member_avatar'] = array(
                        'host'=>$members[$val['member_id']]['host'],
                        'dir'=>$members[$val['member_id']]['dir'],
                        'filepath'=>$members[$val['member_id']]['filepath'],
                        'filename'=>$members[$val['member_id']]['filename'],
                    );
                }
            }
            //初始化默认值
//             $res[$key]['comment_num'] = "0" ;
//             if($val['is_reply'])
//             {
//                 $res[$key]['comment_num'] = "1";
//             }
            $res[$key]['attention_num'] = "0" ;
//             $res[$key]['joint_num'] = "0" ;
//             if (!empty($comment_nums))
//             {
//                 $res[$key]['comment_num'] += $comment_nums[$val['id']] ? ($comment_nums[$val['id']]) : "0";
//             }
            if (!empty($attention_nums))
            {
                $res[$key]['attention_num'] = $attention_nums[$val['id']] ? $attention_nums[$val['id']] : "0";
            }
//             if (!empty($joint_nums))
//             {
//                 $res[$key]['joint_num'] = $joint_nums[$val['id']] ? $joint_nums[$val['id']] : "0";
//             }
        }
        //数据异常处理
        $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE flag = 1';
        $this->db->query($sql);
        return $res;
    }

    /**
     *
     * @Description  有官方回复优先显示官方回复，其次为推荐答案
     * @author Kin
     * @date 2013-6-14 上午10:23:48
     */
    public function getSeekhelplist($condition, $orderby, $offset, $count,$sort_id = 0)
    {
        if(!$offset && !$count)
        {
            $limit = '';
        }
        else
        {
            $limit = " limit {$offset}, {$count}";
        }

        $sql = 'SELECT  sh.*, c.content
                FROM '.DB_PREFIX.'seekhelp  sh
                LEFT JOIN '.DB_PREFIX.'content c ON sh.id = c.id
                WHERE 1 '. $condition.$orderby.$limit;
        $query = $this->db->query($sql);
        $res = array();
        $Ids = array();
        $memberIds = array();
        while ($row = $this->db->fetch_array($query))
        {
            $row['title'] = stripcslashes($row['title']);
            $row['content'] = hg_back_value(stripcslashes(urldecode($row['content'])));
            $row['format_create_time'] = date('Y-m-d H:i:s', $row['create_time']);

            $row['status_name'] = $this->settings['seekhelp_status'][$row['status']];
            $row['state'] = $row['status'];    //只为纪录操作使用，其他无用
            $memberIds[$row['id']] = $row['member_id'];
            $Ids[$row['id']] = $row['reply_id'];

            switch ($row['status'])
            {
                case 0 : $row['status_name'] = '待审核';break;
                case 1 : $row['status_name'] = '已审核';break;
                case 2 : $row['status_name'] = '被打回';break;
            }
            $res[] = $row;
        }
        $sql = 'SELECT * FROM '.DB_PREFIX.'section
                WHERE sort_id='.$sort_id;
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query))
        {
            $sectionInfo[] = $row;
        }
        foreach($res as $k=>$v)
        {
            foreach($sectionInfo as $ko=>$vo)
            {
                if($v['section_id'] == $vo['id'])
                {
                    $res[$k]['section_name'] = $vo['name'];
                }
            }
        }



        if (!empty($memberIds))
        {
            $member_ids = implode(',', $memberIds);
            //新旧会员处理
            if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
            {
                if ($this->settings['App_members'])
                {
                    $members = array();
                    $temp_members = $this->members->get_newUserInfo_by_ids($member_ids);
                    if ($temp_members && !empty($temp_members) && is_array($temp_members))
                    {
                        foreach ($temp_members as $val)
                        {
                            $members[$val['member_id']] = $val;
                        }
                    }
                    //hg_pre($members);exit();
                }
            }
            else
            {
                if ($this->settings['App_member'])
                {
                    $members = $this->member->getMemberByIds($member_ids);
                    $members = $members[0];

                }
            }

        }

        if (!empty($Ids))
        {
            $materials = $this->handle_materials($Ids);
        }
        foreach ($res as $key=>$val)
        {
            if($materials&&$materials[$val['id']]&&is_array($materials[$val['id']]))
            {
                foreach ($materials[$val['id']] as $k=>$v)
                {
                    $res[$key][$k]=$v;
                }
            }
            if ($members[$val['member_id']])
            {
                if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
                {
                    $res[$key]['member_name'] = IS_HIDE_MOBILE ? hg_hide_mobile($members[$val['member_id']]['nick_name']) : $members[$val['member_id']]['nick_name'];
                    $res[$key]['member_avatar'] = $members[$val['member_id']]['avatar'];
                    $res[$key]['member_level'] = intval($members[$val['member_id']]['digital']);
                }
                else
                {
                    $res[$key]['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($members[$val['member_id']]['nick_name']):$members[$val['member_id']]['nick_name'];
                    $res[$key]['member_avatar'] = array(
                        'host'=>$members[$val['member_id']]['host'],
                        'dir'=>$members[$val['member_id']]['dir'],
                        'filepath'=>$members[$val['member_id']]['filepath'],
                        'filename'=>$members[$val['member_id']]['filename'],
                    );
                }
            }
            //初始化默认值
        }
        //数据异常处理
        $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE flag = 1';
        $this->db->query($sql);
        return $res;
    }

    public function count($condition)
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'seekhelp sh WHERE 1'.$condition;
        $ret = $this->db->query_first($sql);
        return $ret;
    }

    /**
     *
     * @Description
     * @author Kin
     * @date 2013-6-6 下午04:08:55
     */
    public function add_seekhelp($data)
    {
        if (empty($data) || !is_array($data))
        {
            return false;
        }
        $sql = 'INSERT INTO '.DB_PREFIX.'seekhelp SET ';
        foreach ($data as $key=>$val)
        {
            $sql .=  $key.'="'.addslashes($val).'",';
        }
        $sql = rtrim($sql,',');
        $this->db->query($sql);
        $id = $this->db->insert_id();
        $sql = 'UPDATE '.DB_PREFIX.'seekhelp set order_id = '.$id.' WHERE id = '.$id;
        $this->db->query($sql);
        $data['id'] = $id;
        return $data;
    }

    public function add_content($content, $id)
    {
        if (!$id || !$content)
        {
            return false;
        }
        $sql = 'INSERT INTO '.DB_PREFIX.'content (id, content) VALUES ('.$id.',"'.urlencode($content).'")';
        $res = $this->db->query($sql);
        return $content;
    }

    /**
     *
     * @Description: 获取上传图片的类型
     * @author Kin
     * @date 2013-4-13 下午03:50:44
     */
    public function getPhotoConfig()
    {
        $ret = $this->material->get_allow_type();
        if (!$ret) {
            return false;
        }
        $photoConfig = array();
        if (is_array($ret['img']) && !empty($ret['img']))
        {
            foreach ($ret['img'] as $type)
            {
                $photoConfig['type'][] = $type;
            }
            $photoConfig['hit'] = implode(',', $ret['img']);
        }
        return $photoConfig;
    }

    /**
     *
     * @Description: 上传图片服务器
     * @author Kin
     * @date 2013-4-13 下午04:04:52
     */
    public function uploadToPicServer($file,$content_id)
    {
        $material = $this->material->addMaterial($file,$content_id); //插入图片服务器
        return $material;
    }

    /**
     *
     * @Description: 单图片上传入库
     * @author Kin
     * @date 2013-4-13 下午04:09:39
     */
    public function upload_pic($data)
    {
        if (!is_array($data) || !$data)
        {
            return false;
        }
        $sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
        foreach ($data as $key=>$val)
        {
            $sql .= $key.'="'.$val.'",';
        }
        $sql = rtrim($sql,',');
        $this->db->query($sql);
        $id = $this->db->insert_id();
        if ($id)
        {
            $ret = array(
                'host'      => $data['host'],
                'dir'       => $data['dir'],
                'filepath'  => $data['filepath'],
                'filename'  => $data['filename'],
                'imgheight' => $data['imgheight'],
                'imgwidth'  => $data['imgwidth'],
            );
            return $ret;
        }
        else
        {
            return false;
        }

    }

    /**
     *
     * @Description: 单视频上传入库
     * @author Kin
     * @date 2013-4-13 下午04:09:39
     */
    public function upload_vod($data)
    {
        if (!is_array($data) || !$data)
        {
            return false;
        }
        $sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
        foreach ($data as $key=>$val)
        {
            $sql .= $key.'="'.$val.'",';
        }
        $sql = rtrim($sql,',');
        $this->db->query($sql);
        $id = $this->db->insert_id();
        if ($id)
        {
            $ret = array(
                'host'      => $data['host'],
                'dir'       => $data['dir'],
                'filepath'  => $data['filepath'],
                'filename'  => $data['filename'],
                'vodid'     => $data['original_id'],
            );
            return $ret;
        }
        else
        {
            return false;
        }
    }
    
    /**
     *
     * @Description 更新求助信息的回复，图片，视频等状态
     * @author Kin
     * @date 2013-6-15 上午11:47:16
     */
    public function update_status($status, $id)
    {
        if (!is_array($status) || !$id)
        {
            return false;
        }
        $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET ';
        foreach ($status as $key=>$val)
        {
            $sql .= $key.'="'.$val.'",';
        }
        $sql = rtrim($sql,',');
        $sql .= ' WHERE id IN('.$id.')';
        $this->db->query($sql);
        return $status;
    }

    /**
     *
     * @Description 关注
     * @author Kin
     * @date 2013-6-15 上午09:23:38
     */
    public function attention($id, $userInfor)
    {
        $data = array(
            'cid'           => $id,
            'create_time'   => TIMENOW,
            'member_id'     => $userInfor['user_id'],
            'ip'            => $userInfor['ip'],
        );
        $sql = 'REPLACE INTO '.DB_PREFIX.'attention SET ';
        foreach ($data as $key => $val)
        {
            $sql .= $key.'="'.addslashes($val).'",';
        }
        $sql = rtrim($sql,',');
        $this->db->query($sql);
        $iid = $this->db->insert_id();
        $data['id'] = $iid;
        return $data;
    }

    /**
     *
     * @Description 取消关注
     * @author Kin
     * @date 2013-6-15 上午09:23:51
     */
    public function cancel_attention($cid, $user_id)
    {
        $sql = 'DELETE FROM '.DB_PREFIX.'attention WHERE cid ='.$cid.' AND member_id = '.$user_id;
        $this->db->query($sql);
        $arr = array(
            'cid'=>$cid,
            'member_id'=>$user_id,
        );
        return $arr;
    }

    /**
     *
     * @Description  取评论数
     * @author Kin
     * @date 2013-6-15 上午11:19:52
     */
    public function comment_num($ids)
    {
        if (!$ids)
        {
            return false;
        }
        $sql = 'SELECT cid, COUNT(*) AS total FROM '.DB_PREFIX.'comment WHERE cid IN ('.$ids.') GROUP BY cid';
        $query = $this->db->query($sql);
        $res = array();
        while ($row = $this->db->fetch_array($query))
        {
            $res[$row['cid']] = intval($row['total']);
        }
                
        return $res;
    }

    /**
     *
     * @Description 取联名数
     * @author Kin
     * @date 2013-6-15 上午11:20:38
     */
    public function joint_num($ids)
    {
        if (!$ids)
        {
            return false;
        }
        $sql = 'SELECT cid, COUNT(*) AS total FROM '.DB_PREFIX.'joint WHERE cid IN ('.$ids.') GROUP BY cid';
        $query = $this->db->query($sql);
        $res = array();
        while ($row = $this->db->fetch_array($query))
        {
            $res[$row['cid']] = $row['total'];
        }
        return $res;
    }

    /**
     *
     * @Description  取关注数
     * @author Kin
     * @date 2013-6-15 上午11:21:41
     */
    public function attention_num($ids)
    {
        if (!$ids)
        {
            return false;
        }
        $sql = 'SELECT cid, COUNT(*) AS total FROM '.DB_PREFIX.'attention WHERE cid IN ('.$ids.') GROUP BY cid';
        $query = $this->db->query($sql);
        $res = array();
        while ($row = $this->db->fetch_array($query))
        {
            $res[$row['cid']] = $row['total'];
        }
        return $res;
    }

    /**
     *
     * @Description 检测某个用户是否联名
     * @author Kin
     * @date 2013-6-17 上午11:03:04
     */
    public function check_is_joint($user_id, $cid)
    {
        if (!$user_id || !$cid)
        {
            return false;
        }
        $sql = 'SELECT member_id FROM '.DB_PREFIX.'joint WHERE member_id = '.$user_id . ' AND cid = '.$cid;
        $ret = $this->db->query_first($sql);
        return $ret['member_id'];
    }

    /**
     *
     * @Description 检测某个用户是否关注
     * @author Kin
     * @date 2013-6-17 上午11:07:25
     */
    public function check_is_attention($user_id, $cid)
    {
        if (!$user_id || !$cid)
        {
            return false;
        }
        $sql = 'SELECT member_id FROM '.DB_PREFIX.'attention WHERE member_id = '.$user_id . ' AND cid = '.$cid;
        $ret = $this->db->query_first($sql);
        return $ret['member_id'];
    }

    public function detail($id, $member_id = 0)
    {
        $sql = 'SELECT sh.*, c.content,
                a.name AS account_name, a.avatar AS account_avatar, 
                s.name AS sort_name,
                r.content AS reply, r.create_time AS reply_time, r.user_id AS reply_user_id 
                FROM '.DB_PREFIX.'seekhelp sh 
                LEFT JOIN '.DB_PREFIX.'content c ON sh.id = c.id
                LEFT JOIN '.DB_PREFIX.'account a ON sh.account_id = a.id
                LEFT JOIN '.DB_PREFIX.'sort s ON sh.sort_id = s.id
                LEFT JOIN '.DB_PREFIX.'reply r ON sh.reply_id = r.id 
                WHERE sh.id = '.$id;
        $ret = $this->db->query_first($sql);
        if(!$ret)
        {
	        $sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id='.$id.'';
	        $ret = $this->db->query_first($sql);
        }
        $ret['format_create_time'] = date('Y-m-d', $ret['create_time']);
        $ret['account_avatar'] = $ret['account_avatar'] ? unserialize($ret['account_avatar']) : '';
        $ret['title'] = stripcslashes($ret['title']);
        $ret['content'] = seekhelp_clean_value(stripcslashes(urldecode($ret['content'])));
        $ret['banword'] = $ret['banword'] ? unserialize($ret['banword']) : '' ;
        //初始化变量
        $ret['is_joint'] = 0;
        $ret['is_attention'] = 0;
        $ret['comment_num'] = 0;
        $ret['attention_num']= 0;
        $ret['joint_num'] = 0;
        $ret['pic'] = '';
        $ret['video'] = '';
        $ret['vodeo_audio'] = '';
        $ret['member_name'] = '';
        $ret['member_avatar'] = '';

        $ret['reply_user_name'] = '';
        $ret['reply_avatar'] = '';
        $ret['reply_pass_time'] = '';
        $ret['reply_pic'] = '';
        $ret['reply_video'] = '';
        $ret['reply_video_audio'] = '';

        $ret['recommend_answer'] = '';
        $ret['recommend_user_id'] = '';
        $ret['recommend_user_name'] = '';
        $ret['recommend_avatar'] = '';
        $ret['recommend_time'] = '';
        $ret['recommend_pass_time'] = '';

        $comment_num = $this->comment_num($id);
        $attention_num = $this->attention_num($id);
        $joint_num = $this->joint_num($id);
        $ret['comment_num'] = $comment_num[$id] ? $comment_num[$id]: 0;
        $ret['attention_num'] = $attention_num[$id] ? $attention_num[$id] : 0;
        $ret['joint_num'] = $joint_num[$id] ? $joint_num[$id] : 0;
        //取系统用户
        if ($ret['reply_user_id'] && $this->settings['App_auth'])
        {
            $curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
            $curl->setSubmitType('post');
            $curl->setReturnFormat('json');
            $curl->initPostData();
            $curl->addRequestData('a', 'getMemberById');
            $curl->addRequestData('id',$ret['reply_user_id']);
            $userInfor = $curl->request('member.php');
            //print_r($userInfor);exit();
            $userInfor = $userInfor[0][0];
            if ($userInfor)
            {
                $ret['reply_user_name'] = IS_HIDE_MOBILE?hg_hide_mobile($userInfor['user_name']):$userInfor['user_name'];
                $ret['reply_avatar'] = $userInfor['avatar'];
            }
        }
        if ($ret['is_reply'])
        {
            $ret['comment_num'] +=1;//如果有金牌回复,则评论数+1
            if($ret['reply'])
            {           
                $ret['reply_pass_time'] = TIMENOW - $ret['reply_time'];
            }
        }
        //联名、关注状态判断
        if ($member_id)
        {
            $is_joint = $this->check_is_joint($member_id, $id);
            $is_attention = $this->check_is_attention($member_id, $id);
            $ret['is_joint'] = $is_joint ? 1 : 0;
            $ret['is_attention'] = $is_attention ? 1 : 0 ;
        }
        //用户信息处理
        $memberIds = array();
        $memberIds[] = $ret['member_id'];
        if ($ret['is_recommend'] && $ret['comment_id'])
        {
            $sql = 'SELECT * FROM '.DB_PREFIX.'comment
                    WHERE id = ' . $ret['comment_id'] . ' AND status = 1 AND is_recommend = 1';
            $commentInfor = $this->db->query_first($sql);
            if ($commentInfor)
            {
                $ret['recommend_answer'] = $commentInfor['content'];
                $ret['recommend_user_id'] = $commentInfor['member_id'];
                $ret['recommend_time'] = $commentInfor['create_time'];
                $ret['recommend_pass_time'] = TIMENOW - $commentInfor['create_time'];
                $memberIds[] = $commentInfor['member_id'];
            }
        }
        //新旧会员处理
        if (defined('SEEKHELP_NEW_MEMBER') && SEEKHELP_NEW_MEMBER)
        {
            if ($this->settings['App_members'] && !empty($memberIds))
            {
                $memberIds = implode(',', $memberIds);
                $member = array();
                $temp_members = $this->members->get_newUserInfo_by_ids($memberIds);
                if ($temp_members && !empty($temp_members) && is_array($temp_members))
                {
                    foreach ($temp_members as $val)
                    {
                        $member[$val['member_id']] = $val;
                    }
                }
            }
            if ($member && $ret['member_id'])
            {
                $ret['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($member[$ret['member_id']]['nick_name']):$member[$ret['member_id']]['nick_name'];
                $ret['member_avatar'] = array(
                                            'host'=>$member[$ret['member_id']]['avatar']['host'],
                                            'dir'=>$member[$ret['member_id']]['avatar']['dir'],
                                            'filepath'=>$member[$ret['member_id']]['avatar']['filepath'],
                                            'filename'=>$member[$ret['member_id']]['avatar']['filename'],
                );
            }
            if ($member && $ret['recommend_user_id'])
            {
                $ret['recommend_user_name'] = IS_HIDE_MOBILE?hg_hide_mobile($member[$ret['recommend_user_id']]['member_name']):$member[$ret['recommend_user_id']]['member_name'];
                $ret['recommend_avatar'] = array(
                                            'host'=>$member[$ret['recommend_user_id']]['avatar']['host'],
                                            'dir'=>$member[$ret['recommend_user_id']]['avatar']['dir'],
                                            'filepath'=>$member[$ret['recommend_user_id']]['avatar']['filepath'],
                                            'filename'=>$member[$ret['recommend_user_id']]['avatar']['filename'],
                );
            }
        }
        else
        {
            if ($this->settings['App_member'] && !empty($memberIds))
            {
                $memberIds = implode(',', $memberIds);
                $member = $this->member->getMemberByIds($memberIds);
                $member = $member[0];
            }
            if ($member && $ret['member_id'])
            {
                $ret['member_name'] = IS_HIDE_MOBILE?hg_hide_mobile($member[$ret['member_id']]['nick_name']):$member[$ret['member_id']]['nick_name'];
                $ret['member_avatar'] = array(
                                            'host'=>$member[$ret['member_id']]['host'],
                                            'dir'=>$member[$ret['member_id']]['dir'],
                                            'filepath'=>$member[$ret['member_id']]['filepath'],
                                            'filename'=>$member[$ret['member_id']]['filename'],
                );
            }
            if ($member && $ret['recommend_user_id'])
            {
                $ret['recommend_user_name'] = IS_HIDE_MOBILE?hg_hide_mobile($member[$ret['recommend_user_id']]['nick_name']):$member[$ret['recommend_user_id']]['nick_name'];
                $ret['recommend_avatar'] = array(
                                            'host'=>$member[$ret['recommend_user_id']]['host'],
                                            'dir'=>$member[$ret['recommend_user_id']]['dir'],
                                            'filepath'=>$member[$ret['recommend_user_id']]['filepath'],
                                            'filename'=>$member[$ret['recommend_user_id']]['filename'],
                );
            }
        }

        //获取素材
        /*
        if ($ret['reply_id'])
        {
        $sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE cid = '.$id.' OR rid = '.$ret['reply_id'];
        }
        else
        {
        $sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE cid = '.$id;
        }
        */
        //排除异常数据
        $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE flag = 1';
        $this->db->query($sql);
        $sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE cid = '.$id .' ORDER BY id DESC';
        $query = $this->db->query($sql);
        $content_materials = array();
        $reply_materials = array();
        $vodIds = array();
        $id_original_id = array();
        while ($row = $this->db->fetch_array($query))
        {
            if ($row['mark'] == 'video')
            {
                $vodIds[] = $row['original_id'];
                $id_original_id[$row['original_id']] = $row['id'];
            }
            if ($row['cid'] == $id && !$row['rid'])
            {
                if ($row['mark'] == 'img')
                {
                    $content_materials['pic'][] = array(
                        'id'=>$row['id'],
                        'host'=>$row['host'],
                        'dir'=>$row['dir'],
                        'filepath'=>$row['filepath'],
                        'filename'=>$row['filename'],
                        'imgwidth'=>$row['imgwidth'],
                        'imgheight'=>$row['imgheight'],
                    );
                }
                if ($row['mark'] == 'video')
                {
                    $content_materials['vodid'][] = $row['original_id'];
                }
            }
            if ($ret['reply_id'] && $row['rid'] == $ret['reply_id'])
            {
                if ($row['mark'] == 'img')
                {
                    $reply_materials['pic'][] = array(
                        'id'=>$row['id'],
                        'host'=>$row['host'],
                        'dir'=>$row['dir'],
                        'filepath'=>$row['filepath'],
                        'filename'=>$row['filename'],
                        'imgwidth'=>$row['imgwidth'],
                        'imgheight'=>$row['imgheight'],
                    );
                }
                if ($row['mark'] == 'video')
                {
                    $reply_materials['vodid'][] = $row['original_id'];
                }
            }
        }
        if (!empty($content_materials['pic']))
        {
            $ret['pic'] = $content_materials['pic'];
        }
        if (!empty($reply_materials['pic']))
        {
            $ret['reply_pic'] = $reply_materials['pic'];
        }
        if (!empty($vodIds))
        {
            $vod_ids = implode(',', $vodIds);
            $vodInfor = $this->livmedia->get_video($vod_ids);
            if ($vodInfor)
            {
                if (!empty($content_materials['vodid']) && is_array($content_materials['vodid']))
                {
                    foreach ($content_materials['vodid'] as $vodid)
                    {
                        if ($vodInfor[$vodid]['is_audio'] == 0)
                        {
                            $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                            $ret['video'][] = $vodInfor[$vodid];
                        }
                        if ($vodInfor[$vodid]['is_audio'])
                        {
                            $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                            $ret['vodeo_audio'][] = $vodInfor[$vodid];
                        }
                    }
                }
                if (!empty($reply_materials['vodid']) && is_array($reply_materials['vodid']))
                {
                    foreach ($reply_materials['vodid'] as $vodid)
                    {
                        if ($vodInfor[$vodid]['is_audio'] == 0)
                        {
                            $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                            $ret['reply_video'][] = $vodInfor[$vodid];
                        }
                        if ($vodInfor[$vodid]['is_audio'])
                        {
                            $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                            $ret['reply_video_audio'][] = $vodInfor[$vodid];
                        }
                    }
                }
            }
        }
        //输出常量
        $ret['show_other_data'] = false;
        if (defined('SHOW_OTHER_DATA'))
        {
            $ret['show_other_data'] = SHOW_OTHER_DATA;
        }
        return $ret;
    }
    
    /**
     * 取seekhelp详情
     */
    public function seekhelp_detail($id)
    {
    	$sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE 1 AND id='.$id.'';
    	$query = $this->db->query($sql);
    	$row = $this->db->fetch_array($query);
    	return $row;
    }

    public function sort($id, $exclude_id, $flag)
    {
        if ($exclude_id)
        {
            $cond = ' AND id NOT IN (' . $exclude_id . ')';
        }
        
        $sql = 'SELECT * FROM '.DB_PREFIX.'sort WHERE 1 ';
        if($flag)
        {
            $sql .= ' AND id IN (' . $id . ')';
        }
        else 
        {
            $sql .= ' AND fid IN (' . $id . ')';
        }
        
        $sql .= $cond .' ORDER BY order_id ASC';
        
        $query = $this->db->query($sql);
        $k = array();
        while(!false == ($row = $this->db->fetch_array($query)))
        {
            $k[] = array(
                'id'        => $row['id'],
                'name'      => $row['name'],
                'fid'       => $row['fid'],
                'parents'   => $row['parents'],
                'childs'    => $row['childs'],
                'depath'    => $row['depath'],
                'is_last'   => $row['is_last'],
            );
        }
        return $k;
    }

    /**
     *
     * @Description 审核操作
     * @author Kin
     * @date 2013-6-19 下午05:25:47
     */
    public function audit($ids, $status)
    {
        $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET status = '.$status.' WHERE id IN ('.$ids.')';
        $this->db->query($sql);
        $ids = explode(',', $ids);
        $arr = array(
            'id'=>$ids,
            'status'=>$status,
        );
        switch ($status)
        {
            case 0 : $arr['status_name'] = '待审核';break;
            case 1 : $arr['status_name'] = '已审核';break;
            case 2 : $arr['status_name'] = '被打回';break;
        }
        return $arr;
    }

    public function push($ids, $status)
    {
        $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET is_push = '.$status.' WHERE id IN ('.$ids.')';
        $this->db->query($sql);
        $ids = explode(',', $ids);
        $arr = array(
            'id'=>$ids,
            'status'=>$status,
        );
        return $arr;
    }

    public function delete($ids)
    {
        //求助信息表
        $sql = 'DELETE FROM '.DB_PREFIX.'seekhelp WHERE id IN ('.$ids.')';
        $this->db->query($sql);
        //内容表
        $sql = 'DELETE FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
        $this->db->query($sql);
        //回复
        $sql = 'DELETE FROM '.DB_PREFIX.'reply WHERE cid IN ('.$ids.')';
        $this->db->query($sql);
        //关注
        $sql = 'DELETE FROM '.DB_PREFIX.'attention WHERE cid IN ('.$ids.')';
        $this->db->query($sql);
        //联名
        $sql = 'DELETE FROM '.DB_PREFIX.'joint WHERE cid IN ('.$ids.')';
        $this->db->query($sql);
        //评论
        $sql = 'DELETE FROM '.DB_PREFIX.'comment WHERE cid IN ('.$ids.')';
        $this->db->query($sql);
        //素材
        $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE cid IN ('.$ids.')';
        $this->db->query($sql);
        return $ids;
    }

    public function update($id = '',$update_data = array(), $reply_pic= array(),$reply_vod= array(), $content = '',$gold_reply = '',$user_infor = array())
    {
        if(!$id || !$update_data || !is_array($update_data))
        {
            return false;
        }
        //源数据
        $sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE id = '.$id;
        $preData = $this->db->query_first($sql);
        if (!$preData)
        {
            return false;
        }
        $update_data['reply_id'] = $preData['reply_id'] ? $preData['reply_id'] : $update_data['reply_id'];  //回复id可能在上传图片和视频时已经被更新
        $update_data['is_reply'] = $preData['is_reply'] ? 1 : ($update_data['reply_id'] ? 1 : 0);  //回复id可能在上传图片和视频时已经被更新
        //更新主表
        $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET ';
        foreach ($update_data as $key=>$val)
        {
            $sql .= $key .'="' .addslashes($val).'",';
        }
        $sql = rtrim($sql,',');
        $sql .= 'WHERE id = '.$id;
        $this->db->query($sql);
        //更新问题描述
        if ($content)
        {
            $sql = 'REPLACE INTO '.DB_PREFIX.'content (id, content) VALUES ('.$id.',"'.addslashes($content).'")';
            $this->db->query($sql);
        }
        //推荐评论
        if ($update_data['comment_id'])
        {
            $sql = 'UPDATE '.DB_PREFIX.'comment SET
                is_recommend = 1,
                user_time ='.TIMENOW.',
                org_id = '.$user_infor['org_id'].',
                user_id = '.$user_infor['user_id'].',
                user_name = "'.addslashes($user_infor['user_name']).'",
                user_ip = "'.addslashes($user_infor['ip']).'" 
                WHERE id = '.$update_data['comment_id'];
            $this->db->query($sql);
            //防止异常数据
            $sql = 'UPDATE '.DB_PREFIX.'comment SET is_recommend = 0 WHERE cid='.$id.' AND id !='.$update_data['comment_id'];
            $this->db->query($sql);
        }
        else
        {
            //防止异常数据
            $sql = 'UPDATE '.DB_PREFIX.'comment SET is_recommend = 0 WHERE cid='.$id;
            $this->db->query($sql);
        }
        //金牌回复
        if ($gold_reply)
        {
            if ($update_data['reply_id'])
            {
                $sql = 'UPDATE '.DB_PREFIX.'reply SET
                        content = "'.addslashes($gold_reply).'",
                        update_time = '.TIMENOW.',
                        update_org_id = '.$user_infor['org_id'].',
                        update_user_id = '.$user_infor['user_id'].',
                        update_user_name = "'.addslashes($user_infor['user_name']).'",
                        update_ip = "'.addslashes($user_infor['ip']).'" 
                        WHERE id = '.$update_data['reply_id'].' AND cid ='.$id;
                $this->db->query($sql);
            }
            else
            {
                $sql = 'INSERT INTO '.DB_PREFIX.'reply (cid, content, create_time, org_id, user_id, user_name, ip,update_time)
                        VALUES ('.$id.',"'.addslashes($gold_reply).'"
                                ,'.TIMENOW.'
                                ,'.$user_infor['org_id'].'
                                ,'.$user_infor['user_id'].'
                                ,"'.addslashes($user_infor['user_name']).'"
                                ,"'.addslashes($user_infor['ip']).'",'.TIMENOW.')';
                $this->db->query($sql);
                $insert_id = $this->db->insert_id();
                $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET is_reply = 1, reply_id = '.$insert_id.' WHERE id = '.$id;
                $this->db->query($sql);
            }
        }
        else
        {
            if ($preData['reply_id'])
            {
                $sql = 'UPDATE '.DB_PREFIX.'reply SET
                content = "",
                update_time = '.TIMENOW.',
                update_org_id = '.$user_infor['org_id'].',
                update_user_id = '.$user_infor['user_id'].',
                update_user_name = "'.addslashes($user_infor['user_name']).'",
                update_ip = "'.addslashes($user_infor['ip']).'" 
                WHERE id = '.$preData['reply_id'];
                $this->db->query($sql);
            }
        }
        //金牌回复图片
        //原素材
        $sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE cid = '.$id;
        $query = $this->db->query($sql);
        $prePic = array();                  //互助图片
        $preVideo = array();                //互助视频
        $preReplyPic = array();             //回复图片
        $preReplyVideo = array();           //回复视频
        while ($row = $this->db->fetch_array($query))
        {

            if ($row['cid'] == $id && !$row['rid'])
            {
                if ($row['mark'] == 'img')
                {
                    $prePic[] = $row['id'];
                }
                if ($row['mark'] == 'video')
                {
                    $preVideo[] = $row['id'];
                }
            }
            if ($row['rid'] == $update_data['reply_id'])
            {
                if ($row['mark'] == 'img')
                {
                    $preReplyPic[] = $row['id'];
                }
                if ($row['mark'] == 'video')
                {
                    $preReplyVideo[] = $row['id'];
                }
            }
        }
        //金牌回复图片更新
        if (!empty($preReplyPic))
        {
            $DelReplyPic = array_diff($preReplyPic, $reply_pic);
            if (!empty($DelReplyPic))
            {
                $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE id IN ('.implode(',', $DelReplyPic).')';
                $this->db->query($sql);
            }
        }
        //金牌回复视频更新
        if (!empty($preReplyVideo))
        {
            $DelReplyVod = array_diff($preReplyVideo, $reply_vod);
            if (!empty($DelReplyVod))
            {
                $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE id IN ('.implode(',', $DelReplyVod).')';
                $this->db->query($sql);
            }
        }
        $this->update_reply($id);
        //屏蔽字处理
        if ($this->settings['App_banword'])
        {
            require_once(ROOT_PATH.'lib/class/banword.class.php');
            $this->banword = new banword();
            $str = $update_data['title'].$content.$gold_reply;
            $banword = $this->banword->exists($str);
            if ($banword && is_array($banword))
            {
                $banword_title = '';
                $banword_content = '';
                $banword_reply = '';
                $banwords = '';
                foreach ($banword as $key=>$val)
                {
                    if (strstr($update_data['title'], $val['banname']))
                    {
                        $banword_title .= $val['banname'].',';
                    }
                    if (strstr($content, $val['banname']))
                    {
                        $banword_content .= $val['banname'].',';
                    }
                    if (strstr($gold_reply, $val['banname']))
                    {
                        $banword_reply  .= $val['banname'].',';
                    }
                }
                $banword_title      = $banword_title ? rtrim($banword_title,',') : '';
                $banword_content    = $banword_content ? rtrim($banword_content,',') : '';
                $banword_reply      = $banword_reply ? rtrim($banword_reply,',') : '';
                if ($banword_title || $banword_content || $banword_reply)
                {
                    $banwords = array(
                        'title'     => $banword_title,
                        'content'   => $banword_content,
                        'reply'     => $banword_reply,
                    );
                    $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET banword = "'.addslashes(serialize($banwords)).'" WHERE id = '.$id;
                    $this->db->query($sql);
                }

            }
            else
            {
                $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET banword = "" WHERE id = '.$id;
                $this->db->query($sql);
            }
        }
        return $update_data;
    }
    
    private function update_reply($id)
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'reply r WHERE 1 AND r.content!=\'\' AND r.cid = '.$id;
        $ret = $this->db->query_first($sql);
        if(empty($ret['total']))
        {
            $sql = 'SELECT rid,COUNT(*) AS total FROM '.DB_PREFIX.'materials m WHERE 1 AND m.cid = '.$id;
            $_ret = $this->db->query_first($sql);
            if(empty($_ret['total']))
            {
                $sql = 'UPDATE '.DB_PREFIX.'seekhelp SET is_reply = 0, reply_id = 0 WHERE id = '.$id;
                $this->db->query($sql);
                //回复
                $sql = 'DELETE FROM '.DB_PREFIX.'reply WHERE cid = '.$id;
                $this->db->query($sql);
            }
            elseif ((empty($_ret['rid']))&&$_ret['total']>0)
            {
                //素材
                $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE cid = '.$id;
                if($this->db->query($sql))
                {
                    $this->update_reply($id);
                }
            }
        }
        return true;
    }
    
    public function add_gold_reply($data = array())
    {
    	if($data && $data['id'])
    	{
    		$id = $data['id'];
    		$gold_reply = $data['gold_reply'];
    		$sql = 'INSERT INTO '.DB_PREFIX.'reply (cid, content, create_time, org_id, user_id, user_name, ip,update_time) VALUES (' . $id . ',"'.addslashes($gold_reply).'"
		            ,'.TIMENOW.'
		            ,'.$data['org_id'].'
		            ,'.$data['user_id'].'
		            ,"'.addslashes($data['user_name']).'"
		            ,"'.addslashes($data['ip']).'",'.TIMENOW.')';
			$this->db->query($sql);
			$insert_id = $this->db->insert_id();
			$sql = 'UPDATE '.DB_PREFIX.'seekhelp SET is_reply=1, reply_id=' . $insert_id . ' WHERE id='.$id;
			$this->db->query($sql);
			
	        //金牌回复图片
	        //原素材
	        $sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE cid = ' . $id;
	        $query = $this->db->query($sql);
	        $prePic = array();                  //互助图片
	        $preVideo = array();                //互助视频
	        $preReplyPic = array();             //回复图片
	        $preReplyVideo = array();           //回复视频
	        while ($row = $this->db->fetch_array($query))
	        {
	
	            if ($row['cid'] == $id && !$row['rid'])
	            {
	                if ($row['mark'] == 'img')
	                {
	                    $prePic[] = $row['id'];
	                }
	                if ($row['mark'] == 'video')
	                {
	                    $preVideo[] = $row['id'];
	                }
	            }
	            if ($row['rid'] == $update_data['reply_id'])
	            {
	                if ($row['mark'] == 'img')
	                {
	                    $preReplyPic[] = $row['id'];
	                }
	                if ($row['mark'] == 'video')
	                {
	                    $preReplyVideo[] = $row['id'];
	                }
	            }
	        }
	        //金牌回复图片更新
	        if (!empty($preReplyPic))
	        {
	            $DelReplyPic = array_diff($preReplyPic, $reply_pic);
	            if (!empty($DelReplyPic))
	            {
	                $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE id IN ('.implode(',', $DelReplyPic).')';
	                $this->db->query($sql);
	            }
	        }
	        //金牌回复视频更新
	        if (!empty($preReplyVideo))
	        {
	            $DelReplyVod = array_diff($preReplyVideo, $reply_vod);
	            if (!empty($DelReplyVod))
	            {
	                $sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE id IN ('.implode(',', $DelReplyVod).')';
	                $this->db->query($sql);
	            }
	        }			
			$this->update_reply($id);
			return true;
    	}
    	else
    	{
	    	return false;
    	}
    }

    /**
     *
     * @Description  插入图片
     * @author Kin
     * @date 2013-7-5 下午03:03:22
     */
    public function insert_img($data = array(),$user_info)
    {
        if(!$data)
        {
            return false;
        }
        //先查询出该求助是否已经存在金牌回复
        $sql = "SELECT * FROM " .DB_PREFIX. "seekhelp WHERE id = '" .$data['cid']. "'";
        $seek_data = $this->db->query_first($sql);
        //如果不存在就创建为该求助创建一个空的回复
        if(!$seek_data['reply_id'])
        {
            $insert_reply_data = array(
                'cid'           => $data['cid'],
                'create_time'   => TIMENOW,
                'update_time'   => TIMENOW,
                'ip'            => $user_info['ip'],
                'org_id'        => $user_info['org_id'],
                'user_id'       => $user_info['user_id'],
                'user_name'     => $user_info['user_name'],
            );

            $sql = " INSERT INTO " . DB_PREFIX . "reply SET ";
            foreach ($insert_reply_data AS $k => $v)
            {
                $sql .= " {$k} = '{$v}',";
            }
            $sql = trim($sql,',');
            $this->db->query($sql);
            $rid = $this->db->insert_id();
            //更新求助的回复id
            $sql = "UPDATE " .DB_PREFIX. "seekhelp SET reply_id = '" .$rid. "',is_reply = 1 WHERE id = '" .$data['cid']. "'";
            $this->db->query($sql);
        }
        else
        {
            $rid = $seek_data['reply_id'];
        }

        $data['rid'] = $rid;
        $sql = " INSERT INTO " . DB_PREFIX . "materials SET ";
        foreach ($data AS $k => $v)
        {
            $sql .= " {$k} = '{$v}',";
        }
        $sql = trim($sql,',');
        $this->db->query($sql);
        $vid = $this->db->insert_id();
        return $vid;
    }

    public function insert_video($data = array(),$user_info)
    {
        if (!$data || !$user_info)
        {
            return false;
        }
        //先查询出该求助是否已经存在金牌回复
        $sql = "SELECT * FROM " .DB_PREFIX. "seekhelp WHERE id = '" .$data['cid']. "'";
        $seek_data = $this->db->query_first($sql);
        //如果不存在就创建为该求助创建一个空的回复
        if(!$seek_data['reply_id'])
        {
            $insert_reply_data = array(
                'cid'           => $data['cid'],
                'create_time'   => TIMENOW,
                'update_time'   => TIMENOW,
                'ip'            => $user_info['ip'],
                'org_id'        => $user_info['org_id'],
                'user_id'       => $user_info['user_id'],
                'user_name'     => $user_info['user_name'],
            );

            $sql = " INSERT INTO " . DB_PREFIX . "reply SET ";
            foreach ($insert_reply_data AS $k => $v)
            {
                $sql .= " {$k} = '{$v}',";
            }
            $sql = trim($sql,',');
            $this->db->query($sql);
            $rid = $this->db->insert_id();
            //更新求助的回复id
            $sql = "UPDATE " .DB_PREFIX. "seekhelp SET reply_id = '" .$rid. "',is_reply= 1 WHERE id = '" .$data['cid']. "'";
            $this->db->query($sql);
        }
        else
        {
            $rid = $seek_data['reply_id'];
        }
        $data['rid'] = $rid;
        $sql = " INSERT INTO " . DB_PREFIX . "materials SET ";
        foreach ($data AS $k => $v)
        {
            $sql .= " {$k} = '{$v}',";
        }
        $sql = trim($sql,',');
        $this->db->query($sql);
        $vid = $this->db->insert_id();
        return $vid;

    }

    /**
     *
     * @Description 所有机构
     * @author Kin
     * @date 2013-7-16 上午09:54:01
     */
    public function organization()
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'account WHERE status = 1 ORDER BY order_id DESC';
        $query = $this->db->query($sql);
        $org = array();
        while ($row = $this->db->fetch_array($query))
        {
            $temp = '';
            $temp['id'] = $row['id'];
            $temp['name'] = $row['name'];
            $temp['avatar'] = $row['avatar'] ? unserialize($row['avatar']) : '' ;
            $org[] = $temp;
        }
        return $org;
    }

    //百度坐标转换为GPS坐标
    public function FromBaiduToGpsXY($x,$y)
    {
        $Baidu_Server = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
        $result = @file_get_contents($Baidu_Server);
        $json = json_decode($result);
        if($json->error == 0)
        {
            $bx = base64_decode($json->x);
            $by = base64_decode($json->y);
            $GPS_x = 2 * $x - $bx;
            $GPS_y = 2 * $y - $by;
            return array('GPS_x' => $GPS_x,'GPS_y' => $GPS_y);//经度,纬度
        }
        else
        {
            return false;//转换失败
        }
    }

    //GPS坐标转换为百度坐标
    public function FromGpsToBaiduXY($x,$y)
    {
        $url = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);//关闭
        $info = json_decode($response,1);
        if($info && !$info['error'])
        {
            unset($info['error']);
            $info['x'] = base64_decode($info['x']);
            $info['y'] = base64_decode($info['y']);
            return $info;
        }
    }

    
    //分类异常数据处理
    public function sortException($sort = 0)
    {
        $sort = intval($sort);
        if (!intval($sort))
        {
            return 0;
        }
        $sql = 'SELECT id FROM '.DB_PREFIX.'sort WHERE id ='.intval($sort);
        $ret = $this->db->query_first($sql);
        if (!$ret)
        {
            return 0;
        }
        else
        {
            return $sort;
        }
    }

    private function handle_materials($Ids)
    {
        //排除异常数据
        $this->db->query('DELETE FROM '.DB_PREFIX.'materials WHERE flag = 1');
        if($_ids=@implode(',', array_keys($Ids)))
        {
            $sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE cid IN ('.$_ids .') ORDER BY id ASC';
            $query = $this->db->query($sql);
            $content_materials = array();
            $reply_materials = array();
            $vodIds = array();
            $id_original_id = array();
            while ($row = $this->db->fetch_array($query))
            {
                if ($row['mark'] == 'video')
                {
                    $vodIds[] = $row['original_id'];
                    $id_original_id[$row['original_id']] = $row['id'];
                }
                if (array_key_exists($row['cid'], $Ids) && !$row['rid'])
                {
                	if ($row['mark'] == 'img')
                    {
                        $content_materials[$row['cid']]['pic'][] = array(
                        'id'=>$row['id'],
                        'host'=>$row['host'],
                        'dir'=>$row['dir'],
                        'filepath'=>$row['filepath'],
                        'filename'=>$row['filename'],
                        'width'=>$row['imgwidth'],
                        'height'=>$row['imgheight'],
                        );
                    }
                    if ($row['mark'] == 'video')
                    {
                        $content_materials[$row['cid']]['vodid'][] = $row['original_id'];
                    }
                }
                if (in_array($row['rid'], $Ids) && $row['rid'])
                {
                    if ($row['mark'] == 'img')
                    {
                        $reply_materials[$row['cid']]['pic'][] = array(
                        'id'=>$row['id'],
                        'host'=>$row['host'],
                        'dir'=>$row['dir'],
                        'filepath'=>$row['filepath'],
                        'filename'=>$row['filename'],
                        'width'=>$row['imgwidth'],
                        'height'=>$row['imgheight'],
                        );
                    }
                    if ($row['mark'] == 'video')
                    {
                        $reply_materials[$row['cid']]['vodid'][] = $row['original_id'];
                    }
                }
            }

        }
        else
        {
            return false;
        }

        foreach ($Ids as $k => $v)
        {
            if (!empty($content_materials[$k]['pic']))
            {
                $ret[$k]['pic'] = $content_materials[$k]['pic'];
            }
            if (!empty($reply_materials[$k]['pic']))
            {
                $ret[$k]['reply_pic'] = $reply_materials[$k]['pic'];
            }
        }
        if (!empty($vodIds))
        {
            $vod_ids = implode(',', $vodIds);
            $vodInfor = $this->livmedia->get_video($vod_ids);
            if ($vodInfor)
            {
                foreach ($Ids as $k => $v)
                {
                        
                    if (!empty($content_materials[$k]['vodid']) && is_array($content_materials[$k]['vodid']))
                    {
                        foreach ($content_materials[$k]['vodid'] as $vodid)
                        {
                            if ($vodInfor[$vodid]['is_audio'] == 0)
                            {
                                $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                                $ret[$k]['video'][] = $vodInfor[$vodid];
                            }
                            if ($vodInfor[$vodid]['is_audio'])
                            {
                                $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                                $ret[$k]['vodeo_audio'][] = $vodInfor[$vodid];
                            }
                        }
                    }

                    if (!empty($reply_materials[$k]['vodid']) && is_array($reply_materials[$k]['vodid']))
                    {
                        foreach ($reply_materials[$k]['vodid'] as $vodid)
                        {
                            if ($vodInfor[$vodid]['is_audio'] == 0)
                            {
                                $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                                $ret[$k]['reply_video'][] = $vodInfor[$vodid];
                            }
                            if ($vodInfor[$vodid]['is_audio'])
                            {
                                $vodInfor[$vodid]['id'] = $id_original_id[$vodid];
                                $ret[$k]['reply_video_audio'][] = $vodInfor[$vodid];
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }
    
    /**
     * 更新帖子的版块
     */
    public function update_sekkhelp_section($sortId,$sectionId,$old_sectionId,$cids)
    {
    	if($sortId && $sectionId)
    	{
	    	$sql = 'UPDATE '.DB_PREFIX.'seekhelp SET section_id='.$sectionId.' WHERE sort_id='.$sortId.' AND section_id='.$old_sectionId.'';
    	}
    	if($cids)
    	{
    		$sql = 'UPDATE '.DB_PREFIX.'seekhelp SET section_id='.$sectionId.' WHERE sort_id='.$sortId.' AND id in ('.$cids.')';
    	}
    	
    	$this->db->query($sql);
    	return $sortId;
    }
/**
   *
   * @Description 视频上传
   * @author Kin
   * @date 2013-4-13 下午04:34:29
   */
  public function uploadToVideoServer($file,$title='',$brief = '',$vod_lexing = 1)                                                                           
  {
    $curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
    $curl->setSubmitType('post');
    $curl->setReturnFormat('json');
    $curl->initPostData();
    $curl->addFile($file);
    $curl->addRequestData('title', $title);
    $curl->addRequestData('comment',$brief);
    $curl->addRequestData('vod_leixing',$vod_lexing);//网页传的视频类型是1，手机传的视频是2
    $curl->addRequestData('app_uniqueid',APP_UNIQUEID);
    $curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
    $ret = $curl->request('create.php');
    return $ret[0];
  }
/**
   *
   * @Description  获取视频的配置
   * @author Kin
   * @date 2013-4-13 下午04:48:54
   */
  public function getVideoConfig()
  {
    $videoConfig = array();
    $curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
    $curl->setReturnFormat('json');
    $curl->initPostData();
    $curl->addRequestData('a','__getConfig');
    $ret = $curl->request('index.php');
    if (empty($ret))
    {
      return false;
    }
    $temp = explode(',', $ret[0]['video_type']['allow_type']);
    $videoConfig['type'] = $temp;
    if (is_array($temp) && !empty($temp))
    {
      foreach ($temp as $val)
      {
        $videoType[] = ltrim($val,'.');
      }
      $videoConfig['hit'] = implode(',', $videoType);

    }
    return $videoConfig;
  }
}