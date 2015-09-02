<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-7-31
 * Time: 上午11:15
 */
require('global.php');
define('MOD_UNIQUEID', 'topic');

class topicUpdateApi extends adminUpdateBase
{
    private $refer_field_map = array(
        'type' => 'type',
        'bundle_id' => 'app_uniqueid',
        'module_id' => 'mod_uniqueid',
        'id' => 'rid',
        'title' => 'title',
        'brief' => 'brief',
        'indexpic' => 'indexpic',
        'content_url' => 'link',
    );

    public function __construct()
    {
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/mode.class.php';
        $this->mode = new mode();
    }

    public function create()
    {
        if (!$this->input['title']) {
            $this->errorOutput('标题不能为空');
        }
        if (!$this->input['start_time']) {
            $this->errorOutput('开始日期不能为空');
        }
        if (!$this->input['end_time']) {
            $this->errorOutput('结束日期不能为空');
        }
        if (!intval($this->input['channel_id'])) {
            $this->errorOutput('请选择频道');
        }

        $nodes = array();
        $nodes['_action'] = 'manage';
        $nodes['nodes'][intval($this->input['channel_id'])] = intval($this->input['channel_id']);
        $this->verify_content_prms($nodes);

        $data = array(
            'channel_id' => intval($this->input['channel_id']),
            'channel_name' => $this->input['channel_name'],
            'title' => $this->input['title'],
            'brief' => $this->input['brief'],
            'indexpic' => $this->input['indexpic'],
            'guest_ids' => $this->input['guests_id'],
            'create_time' => TIMENOW,
            'update_time' => TIMENOW,
            'user_id' => $this->user['user_id'],
            'user_name' => $this->user['user_name'],
            'org_id' => $this->user['org_id'],
            'status' => $this->get_status_setting('create'),
        );

        /** 时间限制 **/
        $this->input['start_time'] = intval(strtotime($this->input['start_time']));
        $this->input['end_time'] = intval(strtotime($this->input['end_time']));
        if ($this->input['start_time'] >= $this->input['end_time']) {
            $this->errorOutput('开始时间不能大于等于结束时间');
        }
        //不能与其他话题时间有交集
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "topics
                WHERE (
                        (start_time < " . $this->input['start_time'] . " AND end_time > " . $this->input['start_time'] . ")
                    OR (start_time < " . $this->input['end_time'] . " AND end_time > " . $this->input['end_time'] . ")
                    OR (start_time > " . $this->input['start_time'] . " AND end_time < " . $this->input['end_time'] . ")
                )
                AND channel_id = " . $data['channel_id'];
        $q = $this->db->query_first($sql);
        if ($q['total']) {
            $this->errorOutput('该话题与同一频道的其他话题存在时间交集');
        }
        $data['start_time'] = $this->input['start_time'];
        $data['end_time'] = $this->input['end_time'];
        /** 时间限制 **/

        if ($data['channel_id'] && $this->settings['App_live']) {
            include_once(ROOT_PATH . 'lib/class/live.class.php');
            $this->live = new live();
            $channel = $this->live->getChannelById($data['channel_id']);
            if (!empty($channel)) {
                $data['channel_name'] = $channel[0]['name'];
            }
        }

        $topic_id = $insert_id = $this->mode->insert($data);

        if (!$insert_id) {
            $this->errorOutput('话题添加失败');
        }

        //更改已上传图片topic_id
        $material_id = $this->input['material_id'];
        if (is_array($material_id) && count($material_id) > 0) {
            $material_id = implode(', ', $material_id);
            $where = ' id IN(' . $material_id . ')';
            if (!$this->mode->update(array('topic_id' => $insert_id), $where, 'materials')) {

            }
        }

        //引用内容处理 start
        $refer_content = $this->input['refer'];
        $refer_fields = array_keys($this->refer_field_map);
        if ($refer_content) {
            foreach ((array)$refer_content['type'] as $k => $v) {
                $content = array();
                foreach ((array)$refer_content as $key => $val) {
                    if (!in_array($key, $refer_fields)) {
                        continue;
                    }
                    if ($this->refer_field_map[$key] == 'title' && !$val[$k])
                    {
                        continue 2;
                    }
                    $content[$this->refer_field_map[$key]] = $val[$k];
                }
                $content['topic_id'] = $topic_id;
                $content['create_time'] = $content['update_time'] = TIMENOW;
                $content['indexpic'] = htmlspecialchars_decode(urldecode($content['indexpic']));
                $this->mode->insert($content, 'refer_content');
            }
        }
        //引用内容处理 end

        //创建聊天室
//        include_once ROOT_PATH . 'lib/class/im.class.php';
//        $this->im = new im();
//        $chatroom_data = array(
//            'title' => $data['title'],
//            'brief' => $data['brief'],
//            'indexpic' => '',
//            'settings' => array(
//
//            ),
//        );
//        $chatroom = $this->im->create_session($chatroom_data);
//        $this->mode->update(array('chatroom_id' => $chatroom['id']), ' id='.$insert_id);
        $data['chatroom_id'] = $this->mode->create_chatroom($topic_id);
        //创建聊天室

        $data['id'] = $insert_id;
        $this->addItem($data);
        $this->output();
    }

    public function update()
    {

        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }

        if (!$this->input['title']) {
            $this->errorOutput('标题不能为空');
        }

        if (!$this->input['start_time']) {
            $this->errorOutput('开始时间不能为空');
        }
        if (!$this->input['end_time']) {
            $this->errorOutput('结束结束不能为空');
        }
        if (!intval($this->input['channel_id'])) {
            $this->errorOutput('请选择频道');
        }

        $topic_id = intval($this->input['id']);
        $ori_topic_info = $this->mode->getOne(' AND id = ' . $topic_id);

        #########
        $nodes = array();
        $nodes['_action'] = 'manage';
        $nodes['nodes'][intval($this->input['channel_id'])] = intval($this->input['channel_id']);
        $nodes['id'] = $topic_id;
        $nodes['user_id'] = $ori_topic_info['user_id'];
        $nodes['org_id'] = $ori_topic_info['org_id'];
        $this->verify_content_prms($nodes);
        #########

        //获取商品原始的素材
        $where = ' AND topic_id = ' . $topic_id;
        $old_material = $this->mode->select_material($where);
        $old_material_id = array();
        foreach ((array)$old_material as $key => $val) {
            $old_material_id[] = $val['id'];
        }

        $data = array(
            'channel_id' => intval($this->input['channel_id']),
            'channel_name' => $this->input['channel_name'],
            'title' => $this->input['title'],
            'brief' => $this->input['brief'],
            'indexpic' => $this->input['indexpic'],
            'guest_ids' => $this->input['guests_id'],
        );

        /** 时间限制 **/
        $this->input['start_time'] = intval(strtotime($this->input['start_time']));
        $this->input['end_time'] = intval(strtotime($this->input['end_time']));
        if ($this->input['start_time'] >= $this->input['end_time']) {
            $this->errorOutput('开始时间不能大于等于结束时间');
        }
        //不能与其他话题时间有交集
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "topics
                WHERE (
                        (start_time < " . $this->input['start_time'] . " AND end_time > " . $this->input['start_time'] . ")
                    OR (start_time < " . $this->input['end_time'] . " AND end_time > " . $this->input['end_time'] . ")
                    OR (start_time > " . $this->input['start_time'] . " AND end_time < " . $this->input['end_time'] . ")
                )
                AND channel_id = " . $data['channel_id'] . " AND id != " . $topic_id;
        $q = $this->db->query_first($sql);
        if ($q['total']) {
            $this->errorOutput('该话题与同一频道的其他话题存在时间交集');
        }

        $data['start_time'] = $this->input['start_time'];
        $data['end_time'] = $this->input['end_time'];

        if ($data['channel_id'] && $this->settings['App_live']) {
            include_once(ROOT_PATH . 'lib/class/live.class.php');
            $this->live = new live();
            $channel = $this->live->getChannelById($data['channel_id']);
            if (!empty($channel)) {
                $data['channel_name'] = $channel[0]['name'];
            }
        }

        $topic_update_ret = $this->mode->update($data, ' `id` = ' . $topic_id);

        //更改素材
        $material_id = $this->input['material_id'];

        if (is_string($material_id)) {
            $material_id = explode(', ', $material_id);
        }
        $del_material_id = array_unique(array_diff($old_material_id, $material_id));
        if (is_array($del_material_id) && count($del_material_id) > 0) {
            $where = ' AND id IN(' . implode(', ', $del_material_id) . ')';
            $del_material_ret = $this->mode->delete_material($where);
        }
        if (is_array($material_id) && count($material_id) > 0) {
            $material_id = implode(', ', $material_id);
            $where = ' id IN(' . $material_id . ')';
            if (!($update_material_ret = $this->mode->update(array('topic_id' => $topic_id), $where, 'materials'))) {

            }
        }

        /*** 引用内容处理 start  ***/
        $this->mode->deleteRefer(' AND topic_id = ' . $topic_id);
        $refer_content = $this->input['refer'];
        $refer_fields = array_keys($this->refer_field_map);
        if ($refer_content) {
            foreach ((array)$refer_content['type'] as $k => $v) {
                $content = array();
                foreach ((array)$refer_content as $key => $val) {
                    if (!in_array($key, $refer_fields)) {
                        continue;
                    }
                    if ($this->refer_field_map[$key] == 'title' && !$val[$k])
                    {
                        continue 2;
                    }
                    $content[$this->refer_field_map[$key]] = $val[$k];
                }
                $content['topic_id'] = $topic_id;
                $content['create_time'] = $content['update_time'] = TIMENOW;
                $content['indexpic'] = htmlspecialchars_decode(urldecode($content['indexpic']));
                $this->mode->insert($content, 'refer_content');
            }
        }
        /*** 引用内容处理 end  ***/

        if ($topic_update_ret || $del_material_ret || $update_material_ret) {
            $data = array(
                'last_update_userid' => $this->user['user_id'],
                'last_update_username' => $this->user['user_name'],
                'update_time' => TIMENOW,
                'status' => $this->get_status_setting('update_audit', $ori_topic_info['status']),
            );
            $this->mode->update($data, ' `id` = ' . $topic_id);
        }


        /*** 如果此话题没有申请聊天室 重新创建聊天室 ***/
        if (!$ori_topic_info['chatroom_id']) {
            $data['chatroom_id'] = $this->mode->create_chatroom($topic_id);
        }
        /*** 如果此话题没有申请聊天室 重新创建聊天室 ***/

        $data['id'] = $topic_id;
        $this->addItem($data);
        $this->output();
    }

    public function delete()
    {
        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }
        $id = $this->input['id'];

        if (is_array($id)) {
            $id = implode(', ', $id);
        }

        if ($this->user['group_type'] > MAX_ADMIN_TYPE) {
            $topic = $this->mode->select(' AND id IN(' . $id . ')');
            foreach ((array)$topic as $k => $v) {
                //验证是否有权限修改他人数据
                $node = array($v['channel_id'] => $v['channel_id']);
                $this->verify_content_prms(array('id' => $v['id'], 'user_id' => $v['user_id'], 'org_id' => $v['org_id'], '_action' => 'manage', 'node' => $node));
            }
        }


        if (!$this->mode->delete(' AND id IN(' . $id . ')')) {
            $this->errorOutput('删除失败');
        }

        $this->addItem($id);
        $this->output();
    }

    public function audit()
    {
        $id = urldecode($this->input['id']);
        if (!$id) {
            $this->errorOutput("未传入ID");
        }
        $idArr = explode(',', $id);

        if ($this->user['group_type'] > MAX_ADMIN_TYPE) {
            $topic = $this->mode->select(' AND id IN(' . $id . ')');
            foreach ((array)$topic as $k => $v) {
                //验证是否有权限修改他人数据
                $node = array($v['channel_id'] => $v['channel_id']);
                $this->verify_content_prms(array('id' => $v['id'], 'user_id' => $v['user_id'], 'org_id' => $v['org_id'], '_action' => 'manage', 'node' => $node));
            }
        }

        if (intval($this->input['audit']) == 1) {
            $this->mode->update(array('status' => 1), " id IN({$id})");
            $return = array('status' => 1, 'id' => $idArr);
        } else if (intval($this->input['audit']) == 0) {
            $this->mode->update(array('status' => 2), " id IN({$id})");
            $return = array('status' => 2, 'id' => $idArr);
        }
        $this->addItem($return);
        $this->output();
    }

    /**
     * 拖动排序
     */
    public function drag_order()
    {

        //$this->verify_content_prms(array('_action' => 'update'));

        parent::drag_order('topics', 'order_id');
    }

    public function sort()
    {

    }

    public function publish()
    {

    }

    public function upload()
    {

        include_once(ROOT_PATH . 'lib/class/material.class.php');
        $this->mater = new material();
        $material = $this->mater->addMaterial($_FILES);
        if (!empty($material) && is_array($material)) {
            $material['pic'] = array(
                'host' => $material['host'],
                'dir' => $material['dir'],
                'filepath' => $material['filepath'],
                'filename' => $material['filename'],
            );
            $data = array(
                'material_id' => $material['id'],
                'pic' => addslashes(json_encode($material['pic'])),
            );
            $insert_id = $this->mode->insert($data, 'materials');
            $return = array(
                'id' => $insert_id,
                'filename' => $material['filename'] . '?' . hg_generate_user_salt(4),
                'name' => $material['name'],
                'mark' => $material['mark'],
                'type' => $material['type'],
                'filesize' => $material['filesize'],
                'path' => $material['host'] . $material['dir'],
                'dir' => $material['filepath'],
                'pic' => $material['pic'],
                'material_id' => $material['id'],
            );
        } else {
            $return = array(
                'error' => '文件上传失败',
            );
        }
        $this->addLogs('上传图片', '', '', $return['name']);
        $this->addItem($return);
        $this->output();
    }

    public function upload_other()
    {
        if ($_FILES['Filedata']) {
            include_once(ROOT_PATH . 'lib/class/material.class.php');
            $this->mater = new material();
            $material = $this->mater->addMaterial($_FILES); //插入各类服务器
            if (!empty($material)) {
                $material['success'] = true;
                $return = array(
                    'material_id' => $material['id'],
                    'host' => $material['host'],
                    'dir' => $material['dir'],
                    'filepath' => $material['filepath'],
                    'filename' => $material['filename'],
                );
            } else {
                $return = array(
                    'success' => false,
                    'error' => '文件上传失败',
                );
            }
        } else {
            $return = array(
                'success' => false,
                'error' => '文件上传失败',
            );
        }
        $this->addItem($return);
        $this->output();
    }

    public function unknow()
    {
        $this->errorOutput('方法不存在');
    }

    public function __destruct()
    {
        parent::__destruct();
    }

}

$out = new topicUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
/* End of file topic_update.php */
 