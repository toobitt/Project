<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video_update.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/content.class.php';
include_once ROOT_PATH . 'lib/class/livmedia.class.php';
include_once ROOT_PATH . 'lib/class/publishcontent.class.php';
include_once ROOT_PATH . 'lib/class/message.class.php';
// include_once ROOT_PATH . 'lib/class/praise.class.php';
define('MOD_UNIQUEID', 'video');  //模块标识

class videoApi extends appCommonFrm
{
    private $api;
    private $video;
    private $publishcontent;
//     private $praise;
    public function __construct()
    {
        parent::__construct();
        $this->api = new content();
        $this->video = new livmedia();
        $this->publishcontent = new publishcontent();
        $this->message = new message();
//         $this->praise = new praise();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
        unset($this->video);
        unset($this->message);
    }
    
    /**
     * 创建视频
     */
    public function create()
    {        
        $site_id = intval($this->input['site_id']);
        if ($site_id <= 0) $this->errorOutput(PARAM_WRONG);
        $data = $this->filter_data();
        if (!$_FILES['video'])
        {
            if (!$this->input['n_chain_m3u8'])
            {
                $this->errorOutput(PARAM_WRONG);
            }else {
                $data['chain_title']    = $this->input['n_chain_title'];
                $data['ori_url']        = $this->input['n_chain_url'];
                $data['chain_m3u8']        = $this->input['n_chain_m3u8'];
                $data['chain_swf']        = $this->input['n_chain_swf'];
                $data['chain_img']        = $this->input['n_chain_img'];
                $data['chain_duration']        = $this->input['n_chain_duration'];
                unset($this->input['n_chain_m3u8']);
                unset($this->input['n_chain_img']);
                unset($this->input['n_chain_title']);
                unset($this->input['n_chain_url']);
                unset($this->input['n_chain_swf']);
                unset($this->input['n_chain_duration']);
                $ret = $this->video->chain($data);
            }
        }else {
            $_FILES['videofile'] = $_FILES['video'];
            unset($_FILES['video']);
            //加完成回调地址
            $ret = $this->video->create($data, $_FILES);            
        }
        if ($ret['id'])
        {
            $info = $this->video->get_videos($ret['id']);
            $info = $info[0][$ret['id']];
            if ($info['img_info'])
            {
                $info['img_info'] = @unserialize($info['img_info']);
            }
            $indexpic = '';
            if ($info['img_info']['host'])
            {
                $indexpic = array(
                    'id'        => $info['img_info']['id'],
                    'host'        => $info['img_info']['host'],
                    'dir'        => $info['img_info']['dir'],
                    'filepath'    => $info['img_info']['filepath'],
                    'filename'    => $info['img_info']['filename'],
                    'imgheight'    => $info['img_info']['imgheight'],
                    'imgwidth'    => $info['img_info']['imgwidth'],
                );
            }
            switch ($info['status'])
            {
                case 0:
                    $state = 3; //转码中
                break;
                case 1:
                    $state = 0; //待审核
                break;
                case 2:
                    $state = 1; //已审核
                break;
                case 3:
                    $state = 2; //已打回
                break;
            }
            if ($info)
            {
                $column_path = $this->input['n_column_path'];
                $localData = array(
                    'site_id' => $site_id,
                    'source_id' => $info['id'],
                    'source' => 'video',
                    'title' => $info['title'],
                    'keywords' => $info['keywords'],
                    'brief' => $info['comment'],
                    'weight' => $info['weight'],
                    'column_id' => $info['column_id'],
                    'column_path' => $column_path ? serialize($column_path) : '',
                    'state' => $state,
                    'app_uniqueid' => 'livmedia',
                    'mod_uniqueid' => 'livmedia',
                    'user_id' => $info['user_id'],
                    'user_name' => $info['addperson'],
                    'org_id' => $info['org_id'],
                    'appid' => $info['from_appid'],
                    'appname' => $info['from_appname'],
                    'create_time' => $info['create_time'],
                    'ip' => $info['ip'],
                    'indexpic'=> $indexpic ? addslashes(serialize($indexpic)) : '',
                    'outlink' => $info['ori_url'],
                    'template_sign' => $info['template_sign'],
                    'iscomment' => $info['iscomment'],
//                 	'is_praise' => $info['is_praise'],
                );
                $result = $this->api->create('content', $localData);
                if ($result['id'])
                {
                    //建立栏目ID和内容ID
                    if ($data['column_id'])
                    {
                        $this->api->column_cid($data['column_id'], $result['id']);
                    }
                }
                //praise表中对应praise信息处理
//                 if($info['is_praise'])
//                 {
//                 	$this->praise->create($info['is_praise'],intval($info['id']),MOD_UNIQUEID);
//                 }
            }
        }
        
        $this->addItem($result);
        $this->output();
    }
    
    public function video_callback()
    {
        $data = $this->input['data'];
        if($data)
        {
            $data = json_decode(html_entity_decode($data),1);
            if($data && $data['video_id'])
            {
                switch ($data['status'])
                {
                    case 0:
                        $_status = 3;break;//转码中
                    case 1:
                        $_status = 0;break;//待审核
                    case 2:
                        $_status = 1;break; //已审核
                    case 3:
                        $_status = 2;break; //已打回
                    case -1:
                        $_status = -1;break; //转码失败
                }

                $updateData = array('state' => $_status);
                $_condition = array(
                    'source' => "'video'",
                    'source_id' => $data['video_id'],
                );
                $result = $this->api->update('content', $updateData, $_condition);
                $this->addItem($result);
                $this->output();
            }
        }
    }
    
    /**
     * 编辑视频
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0) $this->errorOutput(PARAM_WRONG);
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'video'));
        if (!$content_info) $this->errorOutput(PARAM_WRONG);
        $data = $this->filter_data();        
        if($this->input['n_chain_m3u8'])
        {
            $url = parse_url($this->input['n_chain_m3u8']);
            $data['hostwork'] = 'http://'.$url['host'] . ($url['port'] ? ':' . $url['port'] : '');
            $data['video_path'] = substr($this->input['n_chain_m3u8'],strlen($data['hostwork'].'/'));
        }
        
        $res = $this->video->update($data, $content_info['source_id']);
        $info = $this->video->get_videos($content_info['source_id']);
        
        $info = $info[0][$content_info['source_id']];
        
        
//         if ($info['img_info'])
//         {
//             $info['img_info'] = @unserialize($info['img_info']);
//         }
//         $indexpic = '';
//         if ($info['img_info']['host'])
//         {
//             $indexpic = array(
//                 'id'        => $info['img_info']['id'],
//                 'host'        => $info['img_info']['host'],
//                 'dir'        => $info['img_info']['dir'],
//                 'filepath'    => $info['img_info']['filepath'],
//                 'filename'    => $info['img_info']['filename'],
//                 'imgheight'    => $info['img_info']['imgheight'],
//                 'imgwidth'    => $info['img_info']['imgwidth'],
//             );
//         }
        $indexpic = '';
        if($data['n_chain_img'])
        {
        	$indexpic = array(
        			'id' => $data['n_chain_img']['id'],
        			'host' => $data['n_chain_img']['host'],
        			'dir' => $data['n_chain_img']['dir'],
        			'filepath' => $data['n_chain_img']['filepath'],
        			'filename' => $data['n_chain_img']['filename'],
        			'imgheight' => $data['n_chain_img']['imgheight'],
        			'imgwidth' => $data['n_chain_img']['imgwidth'],
        	);
        }
        
        
        switch ($info['status'])
        {
            case 0:
                $state = 3; //转码中
            break;
            case 1:
                $state = 0; //待审核
            break;
            case 2:
                $state = 1; //已审核
            break;
            case 3:
                $state = 2; //已打回
            break;
        }
        //如果是被发布的数据，更新发布库数据
//        if ($info['column_id'] && $info['column_url'])
//        {
//            $column_url = @unserialize($info['column_url']);
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
		
		if ($info['column_url'])
		{
			$column_url = @unserialize($info['column_url']);
			foreach ($column_url as $v)
			{
				$rid = $v;
			}
		}
		if($indexpic && $rid)
		{
			$ret = $this->publishcontent->updateContentbyrid($rid, array('indexpic' => serialize($indexpic)));
		}

        $column_path = $this->input['n_column_path'];
        $info['column_id'] = $info['column_id'] ? @unserialize($info['column_id']) : '';
        if (!$info['column_id'] || empty($info['column_id']))
        {
            $info['column_id'] = '';
        }else {
            $info['column_id'] = serialize($info['column_id']);
        }
        $localData = array(
            'title' => $info['title'],
            'keywords' => $info['keywords'],
            'brief' => $info['comment'],
            'weight' => $info['weight'],
            'column_id' => $info['column_id'],
            'state' => $state,
            'column_path' => $column_path ? serialize($column_path) : '',
            'template_sign' => $info['template_sign'],
            'iscomment' => $info['iscomment'],
//         	'is_praise' => $info['is_praise'],
        );
        if($indexpic)
        {
        	$localData['indexpic'] = addslashes(serialize($indexpic));
        }
        $result = $this->api->update('content', $localData, array('id' => $id));
        $this->api->column_cid($data['column_id'], $id);
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
        $sourceIds = implode(',', $relation);    //来源ID，视频ID
        $result = $this->video->delete($sourceIds);
        if ($result['id'])
        {
            $this->api->delete('content', array('id'=>$ids));
            $this->api->del_column_cid($ids);
            //删除内容对应的评论
            $rss = $this->message->deleteComment('', MOD_UNIQUED , MOD_UNIQUED, $sourceIds);
            //转换视频输出接口格式
            $result['id'] = implode(',', $result['id']);
            
            //删除内容的赞的信息
//             $praise = $this->praise->delete($result,MOD_UNIQUEID);
        }
        $this->addItem($result['id']);
        $this->output();
    }
    /**
     * 
     * @Description 视频审核，支持批量
     * @author Kin
     * @date 2014-2-25 下午02:34:50
     */
    public function audit()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即视频库ID
        //视频状态 0转码中 1待审核 2已审核 3已打回
        $result = $this->video->audit($sourceIds, 1);
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
     * @Description 视频打回，支持批量
     * @author Kin
     * @date 2014-2-25 下午04:56:05
     */
    public function back()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即视频库ID
        //视频状态 0转码中 1待审核 2已审核 3已打回
        $result = $this->video->audit($sourceIds, 0);
        if ($result && $result['status'])
        {
            $result['status'] = 2;
            $this->api->update('content', array('state' => 2), array('id'=>$ids));
        }
        $this->addItem($result);
        $this->output();
    }
    /**
     * 处理提交的数据
     */
    private function filter_data()
    {
        $n_column = trim($this->input['n_column']);
        $n_title = trim($this->input['n_title']);
        $n_subtitle = trim($this->input['n_subtitle']);
        $n_keywords = trim($this->input['n_keywords']);
        $n_weight = intval($this->input['n_weight']);
        $n_brief = trim($this->input['n_brief']);
        $n_author = trim($this->input['n_author']);
        $n_source = trim($this->input['n_source']);
        $n_template_sign = intval($this->input['n_template_sign']);
        $n_iscomment = intval($this->input['n_iscomment']) ? 1 : 0;
//         $n_ispraise = intval($this->input['n_ispraise']) ? 1 : 0;
        $n_chain_img  = $this->input['chain_img'];
        $n_click_num = $this->input['n_click_num'] ? intval($this->input['n_click_num']) : 0;
        if($n_chain_img)
        {
        	$img_src_cpu = $n_chain_img['host'].$n_chain_img['dir'].$n_chain_img['filepath'].$n_chain_img['filename'];
        }
        $n_chain_title  = $this->input['chain_title'];
        $data =  array(
            'column_id' => $n_column,
            'title' => $n_title,
            'subtitle' => $n_subtitle,
            'keywords' => $n_keywords,
            'weight' => $n_weight,
            'comment' => $n_brief,
            'author' => $n_author,
            'source' => $n_source,
            'template_sign'    => $n_template_sign,
            '_outercall' => 1, //防止用户group_type被改变的问题
            'iscomment'    => $n_iscomment,
//         	'is_praise'	=> $n_ispraise,
        	'img_src_cpu' => $img_src_cpu,	
        	'click_count'	=> $n_click_num,
        );
        if($n_chain_img['id'])
        {
        	$data['n_chain_img'] = $n_chain_img;
        }
        return $data;
    }
    
    //幻灯片切换
    public function updateSlide()
    {
        $id = intval($this->input['id']);
        $weight = intval($this->input['n_weight']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }
        
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => MOD_UNIQUEID));
        if (!$content_info)
        {
            $this->errorOutput(NOT_EXISTS_CONTENT);
        }
        
        //更新视频库里面视频的权重
        $data = array(
            $content_info['source_id']    => $weight,    
        );
        //对文稿进行特殊处理
        $data = array(
            'data'=>htmlspecialchars(json_encode($data)),
        );
        $info = $this->video->updateWeight($data, $content_info['source_id']);
        if ($info)
        {
            $localData = array(
                'weight' => $weight,
            );
            $result = $this->api->update('content', $localData, array('id' => $id));
            //更新发布库
            if ($result)
            {
                $videoInfo = $this->video->get_videos($content_info['source_id']);
                $videoInfo = $videoInfo[0][$content_info['source_id']];
                if ($videoInfo['column_url'])
                {
                    $column_id = @unserialize($videoInfo['column_url']);
                    if (is_array($column_id) && !empty($column_id))
                    {
                        $weightData = array();
                        foreach ($column_id as $pubContentId)
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
            $this->addItem($result);
            $this->output();
        }
    }
    
    /**
     * 批量修改视频所属栏目
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
    	$ret = $this->video->editColumnsById(intval($content_info['source_id']),$column_id,$column_path);
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
    	$news_id = intval($data['source_id']);//文稿库id
    	$id = intval($data['id']);//company下id
    
    	 
    	//取消company中的栏目记录
    	$this->api->new_update('content', array(
    			'column_id'		=> '',
    			'column_path'	=> '',
    	), array(
    			'id'		=>	$id,
    			'source_id'	=>	$news_id,
    			'source'	=> 	trim($data['source']),
    	));
    	//取消栏目和内容关系
    	$this->api->delete('column_cid', array(
    			'cid'	=> $id,
    	));
    	//修改news库
    	$this->video->moveToTrash($id,$news_id);
    	$this->addItem(array('return' => true));
    	$this->output();
    }
    
    /**
     * 方法不存在的时候调用的方法
     */
    public function none()
    {
        $this->errorOutput('调用的方法不存在');
    }
}

$out = new videoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'none';
}
$out->$action();
?>
