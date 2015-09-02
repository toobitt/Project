<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: special_update.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/content.class.php';
include_once ROOT_PATH . 'lib/class/special.class.php';
include_once ROOT_PATH . 'lib/class/publishcontent.class.php';
include_once ROOT_PATH . 'lib/class/message.class.php';
define('MOD_UNIQUEID', 'special');  //模块标识

class specialTopicApi extends appCommonFrm
{
    private $api;
    private $special;
    private $publishcontent;
    public function __construct()
    {
        parent::__construct();
        $this->api = new content();
        $this->special = new special();
        $this->publishcontent = new publishcontent();
        $this->message = new message();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
        unset($this->special);
        unset($this->message);
    }
    
    /**
     * 创建专题
     */
    public function create()
    {
        $site_id = intval($this->input['site_id']);
        if ($site_id <= 0) $this->errorOutput(PARAM_WRONG);
        $data = $this->filter_data();
        if ($_FILES['index_pic'])
        {
            $_FILES['Filedata'] = $_FILES['index_pic'];
            unset($_FILES['index_pic']);
        }
        if ($_FILES['topic_pic'])
        {
            $_FILES['bigFiledata'] = $_FILES['topic_pic'];
            unset($_FILES['topic_pic']);
        }
        $info = $this->special->create($data, $_FILES);
        if ($info['id'])
        {
            if ($info['pic'])
            {
                $info['pic'] = @unserialize($info['pic']);
            }
            $indexpic = '';
            if ($info['pic']['host'])
            {
                $indexpic = array(
                    'id'        => $info['pic']['id'],
                    'host'        => $info['pic']['host'],
                    'dir'        => $info['pic']['dir'],
                    'filepath'    => $info['pic']['filepath'],
                    'filename'    => $info['pic']['filename'],
                    'imgheight'    => $info['pic']['imgheight'],
                    'imgwidth'    => $info['pic']['imgwidth'],
                );
            }
            $column_path = $this->input['n_column_path'];
            $localData = array(
                'site_id' => $site_id,
                'source_id' => $info['id'],
                'source' => 'special',
                'title' => $info['name'],
                'keywords' => $info['keywords'],
                'brief' => $info['brief'],
                'weight' => $info['weight'],
                'column_id' => $info['column_id'],
                'column_path' => serialize($column_path),
                'state' => $info['state'],
                'app_uniqueid' => 'special',
                'mod_uniqueid' => 'special',
                'user_id' => $info['user_id'],
                'user_name' => $info['user_name'],
                'org_id' => $info['org_id'],
                'appid' => $this->user['appid'],
                'appname' => $this->user['display_name'],
                'create_time' => $info['create_time'],
                'ip' => $info['ip'],
                'indexpic'=> $indexpic ? addslashes(serialize($indexpic)) : '',
            );
            $result = $this->api->create('content', $localData);
            if ($result['id'])
            {
                $this->api->column_cid($data['column_id'], $result['id']);
            }
        }
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 编辑专题
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0) $this->errorOutput(PARAM_WRONG);
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'special'));
        if (!$content_info) $this->errorOutput(PARAM_WRONG);
        $data = $this->filter_data();
        if ($_FILES['index_pic'])
        {
            $_FILES['Filedata'] = $_FILES['index_pic'];
            unset($_FILES['index_pic']);
        }
        if ($_FILES['topic_pic'])
        {
            $_FILES['bigFiledata'] = $_FILES['topic_pic'];
            unset($_FILES['topic_pic']);
        }
        $info = $this->special->update($data, $content_info['source_id'], $_FILES);
        //如果是被发布的数据，更新发布库数据
        if ($info['column_id'] && $info['column_url'])
        {
            $column_url = @unserialize($info['column_url']);
            if (is_array($column_url) && !empty($column_url))
            {
                $weightData = array();
                foreach ($column_url as $pubContentId)
                {
                    $weightData[$pubContentId] = $info['weight'];
                }
                if (!empty($weightData))
                {
                    $ret = $this->publishcontent->update_weight($weightData);
                    if ($ret[0] != 'success')
                    {
                        $this->errorOutput('更新发布库权重失败');
                    }
                } 
            }
        }        
        if ($info['pic'])
        {
            $info['pic'] = @unserialize($info['pic']);
        }
        $indexpic = '';
        if ($info['pic']['host'])
        {
            $indexpic = array(
                'id'        => $info['pic']['id'],
                'host'        => $info['pic']['host'],
                'dir'        => $info['pic']['dir'],
                'filepath'    => $info['pic']['filepath'],
                'filename'    => $info['pic']['filename'],
                'imgheight'    => $info['pic']['imgheight'],
                'imgwidth'    => $info['pic']['imgwidth'],
            );
        }
        $column_path = $this->input['n_column_path'];
        $localData = array(
            'title' => $info['name'],
            'keywords' => $info['keywords'],
            'brief' => $info['brief'],
            'weight' => $info['weight'],
            'column_id' => serialize($info['column_id']),
            'state' => $info['state'],
            'column_path' => serialize($column_path),
            'indexpic'=> $indexpic ? addslashes(serialize($indexpic)) : '',
        );        
        $result = $this->api->update('content', $localData, array('id' => $id));
        $this->api->column_cid($data['column_id'], $id);
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
        $sourceIds = implode(',', $relation);    //来源ID，专题ID
        $result = $this->special->delete($sourceIds);
        if ($result)
        {
            $this->api->delete('content', array('id'=>$ids));
            $this->api->del_column_cid($ids);
            //删除内容对应的评论
            $rss = $this->message->deleteComment('', MOD_UNIQUED , MOD_UNIQUED, $sourceIds);
        }
        $this->addItem($result);
        $this->output();
    }
    /**
     * 
     * @Description 专题审核，支持批量
     * @author Kin
     * @date 2014-2-25 下午02:37:57
     */
    public function audit()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即专题ID
        $result = $this->special->audit($sourceIds, 1);
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
     * @Description 专题打回，支持批量
     * @author Kin
     * @date 2014-2-25 下午04:54:41
     */
    public function back()
    {
        $ids = $this->input['id'];                //内容ID
        $relation = $this->input['relation'];    //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);    //来源ID，即专题ID
        $result = $this->special->audit($sourceIds, 0);
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
        $n_keywords = trim($this->input['n_keywords']);
        $n_weight = intval($this->input['n_weight']);
        $n_brief = trim($this->input['n_brief']);
        $n_client = $this->input['n_client_pic'];
        $n_new_summary = $this->input['n_new_summary'];
        $n_new_detail = $this->input['n_new_detail'];
        $n_summary = $this->input['n_summary'];
        $n_detail = $this->input['n_detail'];
        $n_new_attach = $this->input['n_new_attach'];
        $n_attach = $this->input['n_attach'];
        if (empty($n_title))
        {
            $this->errorOutput(PARAM_WRONG);
        }
        return array(
            'column_id' => $n_column,
            'name' => $n_title,
            'brief' => $n_brief,
            'keywords' => $n_keywords,
            'weight' => $n_weight,
            'client_top_pic' => $n_client,
            'new-summary' => $n_new_summary,
            'new-detail' => $n_new_detail,
            'summary' => $n_summary,
            'detail' => $n_detail,
            'new-attach-id' => $n_new_attach,
            'attach-id' => $n_attach,
            'flag' => true
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
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'special'));        
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
        $info = $this->special->updateWeight($data, $content_info['source_id']);
        if ($info)
        {
            $localData = array(
                'weight' => $weight,
            );
            $result = $this->api->update('content', $localData, array('id' => $id));
            //更新发布库
            if ($result)
            {
                $specialInfo = $this->special->detail($content_info['source_id']);                
                if ($specialInfo['column_url'])
                {
                    $column_url = @unserialize($specialInfo['column_url']);
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
     * 方法不存在的时候调用的方法
     */
    public function none()
    {
        $this->errorOutput('调用的方法不存在');
    }
}

$out = new specialTopicApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'none';
}
$out->$action();
?>
