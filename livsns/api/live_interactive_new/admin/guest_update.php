<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-7-31
 * Time: 上午11:15
 */
require('global.php');
define('MOD_UNIQUEID','guest');
class guestUpdateApi extends adminUpdateBase
{
    public function __construct()
    {
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/mode.class.php';
        $this->mode = new mode();
    }

    public function create() {
        if (!$this->input['title']) {
            $this->errorOutput('标题不能为空');
        }
        $data = array(
            'title'             => $this->input['title'],
            'brief'             => $this->input['brief'],
            'indexpic'          => htmlspecialchars_decode(urldecode($this->input['log'])),
            'link'              => $this->input['link'],
            'create_time'       => TIMENOW,
            'update_time'       => TIMENOW,
            'user_id'           => $this->user['user_id'],
            'user_name'         => $this->user['user_name'],
            'org_id'            => $this->user['org_id'],
            'status'            => $this->get_status_setting('create'),
        );
        $data['indexpic'] = json_decode($data['indexpic'], 1);
        if ($data['indexpic'])
        {
            foreach ((array)$data['indexpic'] as $key => $val)
            {
                if ($val && is_array($val))
                {
                    $data['indexpic'][$key] = array(
                        'material_id' => $val['id'],
                        'host'        => $val['host'],
                        'dir'         => $val['dir'],
                        'filepath'    => $val['filepath'],
                        'filename'    => $val['filename'],
                    );
                }
            }
            $data['indexpic'] = json_encode($data['indexpic']);
        }
        else
        {
            $data['indexpic'] = '';
        }


        $insert_id = $this->mode->insert($data, 'guests');

        if (!$insert_id) {
            $this->errorOutput('嘉宾添加失败');
        }

        $data['id'] = $insert_id;
        $this->addItem($data);
        $this->output();
    }

    public function update() {

        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }


        $id = intval($this->input['id']);

        $ori_topic_info = $this->mode->getOneGuest(' AND id = ' . $id);


        //更改话题
        $data = array(
            'title'             => $this->input['title'],
            'brief'             => $this->input['brief'],
            'link'              => $this->input['link'],
            'indexpic'          => htmlspecialchars_decode(urldecode($this->input['log'])),
        );
        $data['indexpic'] = json_decode($data['indexpic'], 1);
        if ($data['indexpic'])
        {
            foreach ((array)$data['indexpic'] as $key => $val)
            {
                if ($val && is_array($val))
                {
                    $data['indexpic'][$key] = array(
                        'material_id' => $val['id'],
                        'host'        => $val['host'],
                        'dir'         => $val['dir'],
                        'filepath'    => $val['filepath'],
                        'filename'    => $val['filename'],
                    );
                }
            }
            $data['indexpic'] = json_encode($data['indexpic']);
        }
        else
        {
            $data['indexpic'] = '';
        }

        $update_ret = $this->mode->update($data, ' `id` = ' . $id, 'guests');

        if ($update_ret ) {
            $data = array(
                'update_time' => TIMENOW,
                'status'      => $this->get_status_setting('update_audit', $ori_topic_info['status']),
            );
            $this->mode->update($data, ' `id` = ' . $id, 'guests');
        }
        $data['id'] = $id;
        $this->addItem($data);
        $this->output();
    }

    public function delete(){
        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }
        $id = $this->input['id'];

        if (is_array($id)) {
            $id = implode(', ', $id);
        }

        if (!$this->mode->deleteGuests(' AND id IN(' . $id . ')')) {
            $this->errorOutput('删除失败');
        }

        $this->addItem($id);
        $this->output();
    }

    public function audit()
    {
        $id = urldecode($this->input['id']);
        if(!$id) {
            $this->errorOutput("未传入ID");
        }
        $idArr = explode(',',$id);

        if(intval($this->input['audit']) == 1)
        {
            $this->mode->update(array('status' => 1), " id IN({$id})", 'guests');
            $return = array('status' => 1,'id'=> $idArr);
        }
        else if(intval($this->input['audit']) == 0)
        {
            $this->mode->update(array('status' => 2), " id IN({$id})", 'guests');
            $return = array('status' =>2,'id' => $idArr);
        }
        $this->addItem($return);
        $this->output();
    }

    /**
     * 拖动排序
     */
    public function drag_order() {

        //$this->verify_content_prms(array('_action' => 'update'));

        parent::drag_order('guests', 'order_id');
    }

    public function sort(){

    }

    public function publish(){

    }

    public function upload()
    {
        if($_FILES['Filedata'])
        {
            include_once(ROOT_PATH . 'lib/class/material.class.php');
            $this->mater = new material();
            $material = $this->mater->addMaterial($_FILES); //插入各类服务器
            if(!empty($material))
            {
                $material['success'] = true;
                $return = $material;
            }
            else
            {
                $return = array(
                    'success' => false,
                    'error' => '文件上传失败',
                );
            }
        }
        else
        {
            $return = array(
                'success' => false,
                'error' => '文件上传失败',
            );
        }
        $this->addItem($return);
        $this->output();
    }

    public function unknow() {
        $this->errorOutput('方法不存在');
    }

    public function __destruct() {
        parent::__destruct();
    }

}
$out = new guestUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
    $action = 'unknow';
}
$out->$action();
/* End of file topic_update.php */
 