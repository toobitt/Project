<?php
/*******************************************************************
 *Merry Christmas
 * 新会员公告相关更新操作
 ******************************************************************/
define('MOD_UNIQUEID', 'members_notice_update');
//模块标识
require ('global.php');
class members_notice_UpdateApi extends adminUpdateBase {
    public function __construct() {
        parent::__construct();
        include (CUR_CONF_PATH . 'lib/Core.class.php');
        $this -> obj = new Core();
    }

    public function create() {
        if(!$this->input['start_time'])
        {
            $this -> errorOutput("NO_START_TIME");
        }
        if(!$this->input['end_time'])
        {
            $this -> errorOutput("NO_END_TIME");
        }
        if(!$this->input['title'])
        {
            $this -> errorOutput("NO_TITLE");
        }
        if(!$this->input['content'])
        {
            $this -> errorOutput("NO_CONTENT");
        }
        $content_params['title'] = $this->input['title'];
        $content_params['content'] = $this->input['content'];
        $content_params['user_id'] = $this->user['user_id'];
        $content_params['org_id'] = $this->user['org_id'];
        $content_params['user_name'] = $this->user['user_name'];
        $content_params['appid'] = $this->user['appid'];
        $content_params['appname'] = trim(($this->user['display_name']));
        $content_params = $this->obj->insert('notice_content',$content_params);
    
        $params['is_deleted'] = 2;
        $params['notice_id'] = $content_params['id'];
        $params['user_type'] = 'members';
        $params['create_time'] = TIMENOW;
        $params['update_time'] = TIMENOW;
        $params['start_time'] = strtotime($this->input['start_time']);
        $params['end_time'] = strtotime($this->input['end_time']);
        $params = $this->obj->insert('notice',$params);
        
        $this->addItem($params);
        $this->output();  
    }

    public function update() {
        if(!$this->input['id'])
        {
            $this -> errorOutput("NO_ID");
        }
        $id = intval($this->input['id']);
        $condition = " where id = $id";
        $notice = $this->obj->detail('notice',$condition);
        if($this->input['title']){
            $content_params['title'] = $this->input['title'];
        }
        
        if($this->input['content'])
        {
            $content_params['content'] = $this->input['content'];
        }
        
        if($this->input['start_time'])
        {
            $params['start_time'] = strtotime($this->input['start_time']);
        }
        if($this->input['end_time'])
        {
            $params['end_time'] = strtotime($this->input['end_time']);
        }
        $cond = " where id=".$notice['notice_id'];
        $params['update_time'] = TIMENOW;
        
        $datas = $this->obj->update('notice_content',$content_params,$cond);
        $datas = $this->obj->update('notice',$params,$condition);
        $this->addItem($datas);
        $this->output();
        
    }

    public function audit() {
    }

    public function delete() {
        if(!$this->input['id'])
        {
            $this -> errorOutput("NO_ID");
        }
        $id = $this->input['id'];
        $cond = " where notice_id in ($id) ";
        $params['is_deleted'] = 1;
        $params['update_time'] = TIMENOW;
        $re = $this->obj->update('notice',$params,$cond);
        $this->addItem($re);
        $this->output();
    }

    public function publish() {

    }

    public function unknow() {
        $this -> errorOutput("此方法不存在！");
    }

    public function sort() {
    }

    public function __destruct() {
        parent::__destruct();
    }

}

$out = new members_notice_UpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>