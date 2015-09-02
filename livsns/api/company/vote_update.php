<?php
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/content.class.php';
include_once ROOT_PATH . 'lib/class/vote.class.php';
include_once ROOT_PATH . 'lib/class/publishcontent.class.php';
include_once ROOT_PATH . 'lib/class/message.class.php';
// include_once ROOT_PATH . 'lib/class/praise.class.php';
define('MOD_UNIQUEID', 'vote');  //模块标识

class voteApi extends appCommonFrm
{
    private $api;
    private $vote;
    private $publishcontent;
//     private $praise;
    public function __construct()
    {
        parent::__construct();
        $this->api = new content();
        $this->vote = new vote();
        $this->publishcontent = new publishcontent();
        $this->message = new message();
//         $this->praise = new praise();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
        unset($this->vote);
        unset($this->message);
    }
    /**
     * 
     * @Description 投票创建方法
     * @author Kin
     * @date 2014-2-27 下午02:41:54
     */
    public function create()
    {        
        $site_id = intval($this->input['site_id']);
        if ($site_id <= 0)
        {
            $this->errorOutput(PARAM_WRONG);
        }
        $data =  array(
            'column_id'            => $this->input['column_id'],
            'title'                => $this->input['title'],
            'describes'            => $this->input['describes'],
            'option_title'        => $this->input['option_title'],
            'option_describes'    => $this->input['option_describes'],
            'ini_num'            => $this->input['ini_num'],
            'start_time'        => $this->input['start_time'],
            'end_time'            => $this->input['end_time'],
            'more_info'            => $this->input['more_info'],
            'option_type'        => $this->input['option_type'],
            'min_option'        => $this->input['min_option'],
            'max_option'        => $this->input['max_option'],
            'is_other'            => $this->input['is_other'],
            'is_ip'                => $this->input['is_ip'],
            'is_userid'            => $this->input['is_userid'],
            'is_verify_code'    => $this->input['is_verify_code'],
            'ip_limit_time'        => $this->input['ip_limit_time'],
            'userid_limit_time'    => $this->input['userid_limit_time'],
            'is_user_login'        => $this->input['is_user_login'],
            'sort_id'            => $this->input['sort_id'],
            'source_type'        => $this->input['source_type'],
            'weight'            => intval($this->input['weight']),
            'template_sign'        => intval($this->input['template_sign']),
            'iscomment'            => intval($this->input['iscomment']) ? 1 : 0,
//         	'is_praise'            => intval($this->input['is_praise']) ? 1 : 0,
            'author'            => trim($this->input['author']),
            'source'            => trim($this->input['source']),
        	'is_device'	        => intval($this->input['is_device']),  //设备限制
        	'device_limit_time'	=> $this->input['is_device'] ? (float)$this->input['device_limit_time'] : 0,
        	'device_limit_num'  => $this->input['is_device'] ? intval($this->input['device_limit_num']) : 1,
        	'is_userid'         => 	intval($this->input['is_userid']),  //用户限制
        	'userid_limit_time' => $this->input['is_userid'] ? (float)$this->input['userid_limit_time'] : 0,
        	'userid_limit_num'  => $this->input['is_userid'] ? intval($this->input['userid_limit_num']) : 0,
        );
        $data['_outercall'] = 1;//防止用户group_type被改变的问题
        //向投票应用提交数据
        $info = $this->vote->create($data,$_FILES);
        if (!$info['id'])
        {
            $this->errorOutput('创建失败');
        }
        $column_path = $this->input['n_column_path'];
        $indexpic = '';
        if ($info['pictures_info']['host'])
        {
            $indexpic = array(
                'id'        => $info['pictures_info']['id'],
                'host'        => $info['pictures_info']['host'],
                'dir'        => $info['pictures_info']['dir'],
                'filepath'    => $info['pictures_info']['filepath'],
                'filename'    => $info['pictures_info']['filename'],
                'imgheight'    => $info['pictures_info']['imgheight'],
                'imgwidth'    => $info['pictures_info']['imgwidth'],
            );
        }
        //本地数据入库
        $arr = array(
            'site_id'         => $site_id,
            'source_id'     => $info['id'],
            'source'         => 'vote',
            'title'         => $info['title'],
            'keywords'         => $info['keywords'],
            'brief'         => $info['describes'],
            'weight'         => $info['weight'],
            'column_id'     => $info['column_id'],
            'column_path'     => $column_path ? serialize($column_path) : '',
            'state'         => $info['status'],
            'app_uniqueid'     => 'vote',
            'mod_uniqueid'     => 'vote',
            'user_id'         => $info['user_id'],
            'user_name'     => $info['user_name'],
            'org_id'         => $info['org_id'],
            'appid'         => $this->user['appid'],
            'appname'         => $this->user['display_name'],
            'create_time'     => strtotime($info['create_time']),
            'ip'             => $info['ip'],
            'indexpic'        => $indexpic ? addslashes(serialize($indexpic)) : '',
            'template_sign'    => $info['template_sign'],
            'iscomment'        => $info['iscomment'],
//            	'is_praise'      => $info['is_praise'],
        
        );
        $result = $this->api->create('content', $arr);
        if ($result['id'])
        {
            //建立栏目ID和内容ID
            if ($data['column_id'])
            {
                $this->api->column_cid($this->input['column_id'], $result['id']);
            }
        }
//         //praise表中对应praise信息处理
//         if($info['is_praise'])
//         {
//         	$this->praise->create($info['is_praise'],intval($info['id']),MOD_UNIQUEID);
//         }
        $this->addItem($result);
        $this->output();
        
    }
    /**
     * 
     * @Description 投票更新方法
     * @author Kin
     * @date 2014-2-27 下午02:41:36
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(PARAM_WRONG);
        } 
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'vote'));
        if (!$content_info)
        {
            $this->errorOutput(PARAM_WRONG);    
        }
        $data =  array(
            'option_id'            => $this->input['option_id'],
            'column_id'            => $this->input['column_id'],
            'title'                => $this->input['title'],
            'describes'            => $this->input['describes'],
            'option_title'        => $this->input['option_title'],
            'option_describes'    => $this->input['option_describes'],
            'ini_num'            => $this->input['ini_num'],
            'start_time'        => $this->input['start_time'],
            'end_time'            => $this->input['end_time'],
            'more_info'            => $this->input['more_info'],
            'option_type'        => $this->input['option_type'],
            'min_option'        => $this->input['min_option'],
            'max_option'        => $this->input['max_option'],
            'is_other'            => $this->input['is_other'],
            'is_ip'                => $this->input['is_ip'],
            'is_userid'            => $this->input['is_userid'],
            'is_verify_code'    => $this->input['is_verify_code'],
            'ip_limit_time'        => $this->input['ip_limit_time'],
            'userid_limit_time'    => $this->input['userid_limit_time'],
            'is_user_login'        => $this->input['is_user_login'],
            'sort_id'            => $this->input['sort_id'],
            'source_type'        => $this->input['source_type'],
            'weight'            => intval($this->input['weight']),
            'template_sign'        => intval($this->input['template_sign']),
            'iscomment'            => intval($this->input['iscomment']) ? 1 : 0,
//         	'is_praise'            => intval($this->input['is_praise']) ? 1 : 0,
            'author'            => trim($this->input['author']),
            'source'            => trim($this->input['source']),       
        	'is_device'	        => intval($this->input['is_device']),  //设备限制
        	'device_limit_time'	=> $this->input['is_device'] ? (float)$this->input['device_limit_time'] : 0,
        	'device_limit_num'  => $this->input['is_device'] ? intval($this->input['device_limit_num']) : 1,
        	'is_userid'         => 	intval($this->input['is_userid']),  //用户限制
        	'userid_limit_time' => $this->input['is_userid'] ? (float)$this->input['userid_limit_time'] : 0,
        	'userid_limit_num'  => $this->input['is_userid'] ? intval($this->input['userid_limit_num']) : 0,
        );
        $data['_outercall'] = 1;//防止用户group_type被改变的问题
        //向投票应用提交数据
        $info = $this->vote->update($data, $content_info['source_id'], $_FILES);        
        if (!$info)
        {
            $this->errorOutput('更新失败');
        }
        $indexpic = '';
        if ($info['pictures_info']['host'])
        {
            $indexpic = array(
                'id'        => $info['pictures_info']['id'],
                'host'        => $info['pictures_info']['host'],
                'dir'        => $info['pictures_info']['dir'],
                'filepath'    => $info['pictures_info']['filepath'],
                'filename'    => $info['pictures_info']['filename'],
                'imgheight'    => $info['pictures_info']['imgheight'],
                'imgwidth'    => $info['pictures_info']['imgwidth'],
            );
        }
        //如果是被发布的数据，更新发布库数据
//        if ($info['column_id'] && $info['column_url'])
//        {
//            $column_url = $info['column_url'];
//            if (is_array($column_url) && !empty($column_url))
//            {
//                $weightData = array();
//                foreach ($column_url as $pubContentId)
//                {
//                    $weightData[$pubContentId] = $info['weight'];
//                }
//                if (!empty($weightData))
//                {
//                    $ret = $this->publishcontent->update_weight($weightData);
//                    if ($ret[0] != 'success')
//                    {
//                        $this->errorOutput('更新发布库权重失败');
//                    }
//                } 
//            }
//        }
        $column_path = $this->input['n_column_path'];
        $arr = array(
            'title'         => $info['title'],
            'keywords'      => $info['keywords'],
            'brief'         => $info['describes'],
            'weight'        => $info['weight'],
            'column_id'     => $info['column_id'],
            'state'         => $info['status'],
            'column_path'   => $column_path ? serialize($column_path) : '',
            'indexpic'      => $indexpic ? addslashes(serialize($indexpic)) : '',
            'template_sign' => $info['template_sign'],
            'iscomment'     => $info['iscomment'],
//         	'is_praise'     => $info['is_praise'],
        );
        $result = $this->api->update('content', $arr, array('id' => $id));
        $this->api->column_cid($this->input['column_id'], $id);
//         $this->praise->update($info['is_praise'],intval($info['id']),MOD_UNIQUEID);
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 
     * @Description 删除，支持批量
     * @author Kin
     * @date 2014-2-27 下午02:37:58
     */
    public function delete()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，投票ID
        $result = $this->vote->delete($sourceIds);
        if ($result)
        {
            $this->api->delete('content', array('id'=>$ids));
            $this->api->del_column_cid($ids);
            //删除内容对应的评论
            $rss = $this->message->deleteComment('', MOD_UNIQUED , MOD_UNIQUED, $sourceIds);
            //删除内容的赞的信息
//             $praise = $this->praise->delete($result,MOD_UNIQUEID);
        }
        $this->addItem($result);
        $this->output();
    }
    /**
     * 
     * @Description 投票审核，支持批量
     * @author Kin
     * @date 2014-2-25 下午02:37:57
     */
    public function audit()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即投票ID
        $result = $this->vote->audit($sourceIds, 0);
        if ($result && $result['status'])
        {
            $result['status'] = 1;
            $this->api->update('content', array('state' => 1), array('id'=>$ids));
        }
        $this->addItem($result);
        $this->output();
    }
    /**
     * 
     * @Description 投票打回，支持批量
     * @author Kin
     * @date 2014-2-25 下午04:54:41
     */
    public function back()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即投票ID
        $result = $this->vote->audit($sourceIds, 1);
        if ($result && $result['status'])
        {
            $result['status'] = 2;
            $this->api->update('content', array('state' => 2), array('id'=>$ids));
        }
        $this->addItem($result);
        $this->output();
    }
    /**
     * 
     * @Description 幻灯片切换
     * @author Kin
     * @date 2014-2-20 下午07:48:36
     */
    public function updateSlide()
    {
        $id = intval($this->input['id']);
        $weight = intval($this->input['n_weight']);
        if ($id <= 0)
        {
            $this->errorOutput(PARAM_WRONG);
        } 
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'vote'));        
        if (!$content_info)
        {
            $this->errorOutput(PARAM_WRONG);
        } 
        $data = array(
            $content_info['source_id']    => $weight,    
        );
        //对文稿进行特殊处理
        $data = array(
            'data'=>htmlspecialchars(json_encode($data)),
        );
        $info = $this->vote->updateWeight($data, $content_info['source_id']);
        if ($info)
        {
            $localData = array(
                'weight' => $weight,
            );
            $result = $this->api->update('content', $localData, array('id' => $id));
            //更新发布库
            if ($result)
            {
                $voteInfo = $this->vote->detail($content_info['source_id']);                
                if ($voteInfo['column_url'])
                {
                    $column_url = $voteInfo['column_url'];
                    if (is_array($column_url) && !empty($column_url))
                    {
                        $weightData = array();
                        foreach ($column_url as $pubContentId)
                        {
                            $weightData[$pubContentId] = $weight;
                        }
                        if (!empty($weightData))
                        {
                            $this->publishcontent->update_weight($weightData);
                        } 
                    }
                }
            }
        }
        $this->addItem($result);
        $this->output();        
    }
    
    /**
     * 批量修改投票的所属栏目
     */
    public function editColumnsById()
    {
    	$id = intval($this->input['id']);
    	$column_id = intval($this->input['column_id']);
    	$column_name = trim($this->input['column_name']);
    	$content_info = $this->api->detail('content',array('id' => $id));
    	$column_path = serialize(array(
    			$column_id => $column_name,
    	));
    	//修改news/article下column_id和column_path
    	$ret = $this->vote->editColumnsById(intval($content_info['source_id']),$column_id,$column_path);
    	//再修改company/content的column_id和column_path   
    	$localData = array(
    			'column_path' => $column_path,
    			'column_id'   => $ret['column_id'],
    			 
    	);
    	$localRet = $this->api->update('content', $localData, array('id' => $id));
    	//建立栏目和内容关系
    	$this->api->column_cid($column_id , $id);
    	$this->addItem($localRet);
    	$this->output();
    }
    
    /**
     * 文稿移到垃圾箱
     */
    public function moveToTrash()
    {
    	$data = $this->input;
    	if(!$data['id'])
    	{
    		$this->errorOutput(NO_ID);
    	}
    	$vote_id = intval($data['source_id']);//文稿库id
    	$id = intval($data['id']);//company下id
    
    	 
    	//取消company中的栏目记录
    	$this->api->new_update('content', array(
    			'column_id'		=> '',
    			'column_path'	=> '',
    	), array(
    			'id'		=>	$id,
    			'source_id'	=>	$vote_id,
    			'source'	=> 	trim($data['source']),
    	));
    	//取消栏目和内容关系
    	$this->api->delete('column_cid', array(
    			'cid'	=> $id,
    	));
    	//修改news库
    	$this->vote->moveToTrash($id,$vote_id);
    	$this->addItem(array('return' => true));
    	$this->output();
    }
    
    /**
     * 方法不存在的时候调用的方法
     */
    public function unknown()
    {
        $this->errorOutput('调用的方法不存在');
    }
}

$out = new voteApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknown';
}
$out->$action();
?>
