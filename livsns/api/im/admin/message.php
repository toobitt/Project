<?php
require('global.php');
define('MOD_UNIQUEID','message');
class messageApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        
        include_once(CUR_CONF_PATH . 'lib/message.class.php');
        $this->mode = new message();
    }
    
    public function index(){}
    public function detail(){}
    
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * 根据条件获取会话列表接口
     */
    public function show() {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = "1 ORDER BY last_time DESC LIMIT $offset, $count";
        $condition = $this->get_condition();
        $session = $this->mode->session_list($condition . $limit, '*', 'id');       
        $session_id = array_keys($session);
        
        
        //取出会话中所有的用户
        $users = $this->mode->session_users($session_id);
        
        
        foreach ((array)$session as $k => $v) {
            if ($v) {
                $v['settings'] = $v['settings'] ? unserialize($v['settings']) : array();
                $v['last_time_show'] = date('Y-m-d H:i:s', $v['last_time']);
                $v['users'] = $users[$k];
                $this->addItem($v);
            }
            
            //$this->addItem($v);
        }       
        $this->output();
    }
    
    /**
     * 根据条件查询会话总数
     */
    public function count() {
        $condition = $this->get_condition();
        $total = $this->mode->session_count($condition);
        echo json_encode($total);
    }
    
    /**
     * 取出会话里所有的聊天
     *
     */
    public function get_session_messages() {
        $session_id = intval($this->input['session_id']);
        if (!$session_id) {
            $this->errorOutput(NO_SESSION_ID);
        }
        $message = $this->mode->get_session_messages($session_id);
        $message = $message[$session_id];
        
        $this->addItem($message);
        $this->output();
    }
    
    /**
     * 获取某一会话里的用户
     */    
    public function get_session_users() {
        $session_id = intval($this->input['session_id']);
        if (!$session_id) {
            $this->errorOutput(NO_SESSION_ID);
        }
        $users = $this->mode->session_users($session_id);        
        $users = $users[$session_id];
        $this->addItem($users);
        $this->output();
    }
    
    /**
     * 会话详情接口
     */
    public function session_detail() {
        $session_id = intval($this->input['session_id']); 
        if (!$session_id) {
            $this->output();
        }
        /***************权限*****************/
    		$this->verify_content_prms(array('_action'=>'show'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***********************************/
        $info = $this->mode->session_info($session_id,'*',$condition);
        $messages = $this->mode->get_session_messages($session_id);
        $messages = $messages[$session_id];
        $users = $this->mode->session_users($session_id);        
        $users = $users[$session_id];
       
        $ret = array('session_info' => $info, 'messages' => $messages, 'users' => $users);
        
        $this->addItem($ret);
        $this->output();
    } 


    function send_form() {
        $ret = array();
        if ($this->input['session_id']) {
            $ret['session_id'] = intval($this->input['session_id']);
        }    
        $this->addItem($ret);
        $this->output();
    }
    
    /**
     * 添加联系人  页面  临时
     */
    function add_person_form() {
        if (!$this->input['session_id']) {
            $this->errorOutput(NO_SESSIONID);
        }
        
        $this->addItem(array('session_id' => intval($this->input['session_id'])));
        $this->output();
    } 
     
    
    function unknow()
    {
        $this->errorOutput("此方法不存在");
    }
    
    private function get_condition()
    {
    		$condition = '';
    		/***** 权限 *****/
		$this->verify_content_prms(array('_action'=>'show'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***** 权限 *****/
    
        return $condition;
    }   

}

$out = new messageApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();

?>