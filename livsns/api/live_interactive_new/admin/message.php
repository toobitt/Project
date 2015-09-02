<?php
require('global.php');
define('MOD_UNIQUEID','message');
class messageApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        
        include_once(ROOT_PATH . 'lib/class/im.class.php');
        $this->im = new im();
    }
    
    /**
     * 获取消息列表
     */
    public function show()
    {}
    
	/**
     * 获取某一个聊天室的消息内容
     */
	public function detail()
	{
    		if(!$this->input['session_id'])
    		{
            $this->errorOutput('没有聊天室id');
        }
        $return = $this->im->detail($this->input);
        $this->addItem($return);
        $this->output();
    }


	/**
     * 删除消息
     */
    public function delete()
    {
        $id = urldecode($this->input['id']);
        if(!$id)
        {
            $this->errorOutput("未传入ID");
        }
        $return = $this->im->delete($id);
        $this->addItem($return);
        $this->output();
    }
    
	/**
     * 审核消息
     */
    public function audit()
    {
        $id = urldecode($this->input['id']);
        if(!$id)
        {
            $this->errorOutput("未传入ID");
        }
        $return = $this->im->audit($id, $this->input['audit']);
        $this->addItem($return);
        $this->output();
    }
    
	/**
     * 推荐消息
     */
    public function recommend()
    {
        $id = urldecode($this->input['id']);
        if(!$id)
        {
            $this->errorOutput("未传入ID");
        }
        $return = $this->im->recommend($id, $this->input['op']);
        $this->addItem($return);
        $this->output();
    }
    
	/**
     * 添加消息标签
     */
    public function tag()
    {
    		$id = urldecode($this->input['id']); //消息id
        $tag_id = urldecode($this->input['tag_id']);
        if(!$tag_id)
        {
            //$this->errorOutput("未传入标签ID");
        }
    		if(!$id)
        {
            $this->errorOutput("没有消息ID");
        }
        if(!($tag_name = $this->input['tag_name']) && $tag_id)
        {
        		//如果没传标签id,就手动去取
        		$sql = "SELECT title FROM " .DB_PREFIX. "tags WHERE id = {$tag_id}";
        		$re = $this->db->query_first($sql);
			if(!$re['title'])
			{
				$this->errorOutput('没有得到标签名称');
			}
			else
			{
				$tag_name = $re['title'];
			}
        }
        $return = $this->im->tag($id, $tag_id, $tag_name);
        $this->addItem($return);
        $this->output();
    }

    public function create_comment()
    {
        if (!$this->input['message_id'])
        {
            $this->errorOutput('No message_id');
        }

        if (!$this->input['comment'])
        {
            $this->errorOutput('No comment');
        }
        $return = $this->im->create_comment($this->input['message_id'], $this->input['comment']);
        $this->addItem($return);
        $this->output();
    }

    public function update_comment()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput('No id');
        }
        if (!$this->input['comment'])
        {
            $this->errorOutput('No comment');
        }
        $return = $this->im->update_comment($this->input['id'], $this->input['comment']);
        $this->addItem($return);
        $this->output();
    }

    public function delete_comment()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput('No id');
        }
        $return = $this->im->delete_comment($this->input['id']);
        $this->addItem($return);
        $this->output();
    }
    
    private function get_condition()
    {
        $condition = '';
    
        return $condition;
    }
    
	/**
     * 根据条件查询消息总数
     */
    public function count()
    {
        $condition = $this->get_condition();
        $total = $this->im->session_count($condition);
        echo json_encode($total);
    }


    /**
     * 获取聊天室里消息
     */
    public function session_messages()
    {
        $session_id = intval($this->input['session_id']);
        if(!$session_id) {
            $this->errorOutput(NO_SESSSION_ID);
        }
        if(!$this->user['user_id']) {
            $this->errorOutput(NOT_LOGIN);
        }
        $ret = $this->im->session_messages($this->input);
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 获取从上次刷新后未读消息数
     *
     * @param session_id   会话id
     * @param refresh_time  上次刷新时间
     */
    public function get_unread_num()
    {
        $session_id = intval($this->input['session_id']);
        if(!$session_id) {
            $this->errorOutput(NO_SESSSION_ID);
        }
        $ret = $this->im->get_unread_num($session_id, $this->input['refresh_time'], $this->input['is_presenter']);
        $this->addItem($ret);
        $this->output();
    }

    
    /***************************** 可能用到的 ***********************************/

    
    /**
     * 获取某一消息里的用户
     */    
    public function get_session_users()
    {
        $session_id = intval($this->input['session_id']);
        if (!session_id)
        {
            $this->errorOutput(NO_SESSION_ID);
        }
        $users = $this->im->session_users($session_id);        
        $users = $users[$session_id];
        $this->addItem($users);
        $this->output();
    }

    /**
     * 消息详情接口
     */
    public function session_detail()
    {
        $session_id = intval($this->input['session_id']);
        if (!$session_id)
        {
            $this->output();
        }

        $info = $this->im->session_info($session_id);
        $messages = $this->im->get_session_messages($session_id);
        $messages = $messages[$session_id];
        $users = $this->im->session_users($session_id);
        $users = $users[$session_id];

        $ret = array('session_info' => $info, 'messages' => $messages, 'users' => $users);

        $this->addItem($ret);
        $this->output();
    }


    public function send_form()
    {
        $ret = array();
        if ($this->input['session_id'])
        {
            $ret['session_id'] = intval($this->input['session_id']);
        }
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 添加联系人  页面  临时
     */
    function add_person_form()
    {
        if (!$this->input['session_id'])
        {
            $this->errorOutput(NO_SESSIONID);
        }

        $this->addItem(array('session_id' => intval($this->input['session_id'])));
        $this->output();
    }

    
	/***********************************************************************/
    
    public function unknow()
    {
        $this->errorOutput("此方法不存在");
    }
    
	public function index(){}
	
    
    public function __destruct()
    {
        parent::__destruct();
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