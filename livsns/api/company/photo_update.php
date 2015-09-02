<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: photo_update.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/content.class.php';
include_once ROOT_PATH . 'lib/class/tuji.class.php';
include_once ROOT_PATH . 'lib/class/publishcontent.class.php';
include_once ROOT_PATH . 'lib/class/message.class.php';
// include_once ROOT_PATH . 'lib/class/praise.class.php';
define('MOD_UNIQUEID', 'tuji');  //模块标识

class tujiApi extends appCommonFrm
{
    private $api;
    private $tuji;
    private $publishcontent;
//     private $praise;
    public function __construct()
    {
        parent::__construct();
        $this->api = new content();
        $this->tuji = new tuji();
        $this->publishcontent = new publishcontent();
        $this->message = new message();
//         $this->praise = new praise();
        
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
        unset($this->tuji);
        unset($this->message);
    }
    
    /**
     * 创建图集
     */
    public function create()
    {
        $site_id = intval($this->input['site_id']);
        if ($site_id <= 0) $this->errorOutput(PARAM_WRONG);
        $data = $this->filter_data();
        $info = $this->tuji->create($data);
        if ($info)
        {
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
            $column_path = $this->input['n_column_path'];
            $localData = array(
                'site_id' => $site_id,
                'source_id' => $info['id'],
                'source' => 'photo',
                'title' => $info['title'],
                'keywords' => $info['keywords'],
                'brief' => $info['comment'],
                'weight' => $info['weight'],
                'column_id' => $info['column_id'],
                'column_path' => $column_path ? serialize($column_path) : '',
                'state' => $info['status'] == -1 ? 0 : $info['status'],
                'app_uniqueid' => 'tuji',
                'mod_uniqueid' => 'tuji',
                'user_id' => $info['user_id'],
                'user_name' => $info['user_name'],
                'org_id' => $info['org_id'],
                'appid' => $info['appid'],
                'appname' => $info['appname'],
                'create_time' => $info['create_time'],
                'ip' => $info['ip'],
                'indexpic'=>$indexpic ? addslashes(serialize($indexpic)) : '',
                'template_sign'    => $info['template_sign'],
                'iscomment'    => $info['iscomment'],
//             	'is_praise'    => $info['is_praise'],
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
            //praise表中增加对应praise信息
//             if($info['is_praise'])
//             {
//             	$this->praise->create($info['is_praise'],intval($info['id']),MOD_UNIQUEID);
//             }
        }
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 编辑图集
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0) $this->errorOutput(PARAM_WRONG);
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'photo'));
        if (!$content_info) $this->errorOutput(PARAM_WRONG);
        $data = $this->filter_data();
        $info = $this->tuji->update($data, $content_info['source_id']);        
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
        if ($info['cover_url'])
        {
            $info['cover_url'] = @unserialize($info['cover_url']);
        }
        $indexpic = '';
        if ($info['cover_url']['host'] && is_array($info))
        {
            $indexpic = array(
                'id'        => $info['cover_url']['id'],
                'host'        => $info['cover_url']['host'],
                'dir'        => $info['cover_url']['dir'],
                'filepath'    => $info['cover_url']['filepath'],
                'filename'    => $info['cover_url']['filename'],
                'imgheight'    => $info['cover_url']['imgheight'],
                'imgwidth'    => $info['cover_url']['imgwidth'],
            );
        }
        $column_path = $this->input['n_column_path'];
        $localData = array(
            'title' => $info['title'],
            'keywords' => $info['keywords'],
            'brief' => $info['comment'],
            'weight' => $info['weight'],
            'column_id' => $info['column_id'],
            'state' => $info['status'] == -1 ? 0 : $info['status'],
            'column_path' => $column_path ? serialize($column_path) : '',
            'indexpic'    => $indexpic ? addslashes(serialize($indexpic)) : '',
            'template_sign'    => $info['template_sign'],
            'iscomment'    => $info['iscomment'],
//         	'is_praise' => $info['is_praise'] ? 1 : 0,
        );
        $result = $this->api->update('content', $localData, array('id' => $id));
        //更新栏目和内容对应关系
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
        $sourceIds = implode(',', $relation);    //来源ID，图集ID
        $result = $this->tuji->delete($sourceIds);
        if ($result)
        {
            $this->api->delete('content', array('id'=>$ids));
            //删除对应关系
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
     * @Description  删除图集中的图片
     * @author Kin
     * @date 2014-2-18 下午03:27:59
     */
    public function delete_pic()
    {
        $ids = $this->input['id'];
        if (!$ids)
        {
            $this->errorOutput(NOID);
        }
        $result = $this->tuji->delete_pic($ids);
        $this->addItem($result);
        $this->output();
    }
    /**
     * 
     * @Description 图集审核，支持批量
     * @author Kin
     * @date 2014-2-25 下午02:36:57
     */
    public function audit()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即视图集ID
        $result = $this->tuji->audit($sourceIds, 1);
        if ($result && $result['status'])
        {
            $result['status'] = 1;
            $this->api->update('content', array('state' => 1), array('id'=>$ids));
        }
        $this->addItem($result);
        $this->output();
    }
    
    public function back()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即视图集ID
        $result = $this->tuji->audit($sourceIds, 0);
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
        $n_comment = trim($this->input['n_brief']);
        $n_keywords = trim($this->input['n_keywords']);
        $n_weight = intval($this->input['n_weight']);
        $n_img_info = trim($this->input['n_img_info']);
        $pic_links = trim($this->input['pic_links']);
        $n_template_sign = intval($this->input['n_template_sign']);
        $n_iscomment = intval($this->input['n_iscomment']) ? 1 : 0;
        $n_author    = trim($this->input['n_author']);
        $n_source    = trim($this->input['n_source']);
        $n_click_num = $this->input['n_click_num'] ? intval($this->input['n_click_num']) : 0;
//         $n_ispraise = intval($this->input['n_ispraise']) ? 1 : 0;
        if (empty($n_title)) $this->errorOutput(PARAM_WRONG);
        return array(
            'column_id' => $n_column,
            'title' => $n_title,
            'comment' => $n_comment,
            'keywords' => $n_keywords,
            'weight' => $n_weight,
            'imgs' => $n_img_info,
            'pic_links'    => $pic_links,
            'template_sign'    => $n_template_sign,
            '_outercall' => 1, //防止用户group_type被改变的问题
            'iscomment'    => $n_iscomment,
            'author'    => $n_author,
            'source'    => $n_source,
//         	'is_praise'	=> $n_ispraise,
        	'click_num'	=> $n_click_num,
        );
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
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'photo'));        
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
        $info = $this->tuji->updateWeight($data, $content_info['source_id']);
        if ($info)
        {
            $localData = array(
                'weight' => $weight,
            );
            $result = $this->api->update('content', $localData, array('id' => $id));
            //更新发布库
            if ($result)
            {
                $photoInfo = $this->tuji->detail($content_info['source_id']);
                if ($photoInfo['tuji']['column_url'])
                {
                    $column_url = @unserialize($photoInfo['tuji']['column_url']);
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
     * 更换图集的栏目
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
    	$ret = $this->tuji->editColumnsById(intval($content_info['source_id']),$column_id,$column_path);
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
     * 方法不存在的时候调用的方法
     */
    public function none()
    {
        $this->errorOutput('调用的方法不存在');
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
    	$photo_id = intval($data['source_id']);//文稿库id
    	$id = intval($data['id']);//company下id
    
    	 
    	//取消company中的栏目记录
    	$this->api->new_update('content', array(
    			'column_id'		=> '',
    			'column_path'	=> '',
    	), array(
    			'id'		=>	$id,
    			'source_id'	=>	$photo_id,
    			'source'	=> 	trim($data['source']),
    	));
    	//取消栏目和内容关系
    	$this->api->delete('column_cid', array(
    			'cid'	=> $id,
    	));
    	//修改news库
    	$this->tuji->moveToTrash($id,$photo_id);
    	$this->addItem(array('return' => true));
    	$this->output();
    }
    
}

$out = new tujiApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'none';
}
$out->$action();
?>
