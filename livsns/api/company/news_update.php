<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: news_update.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/content.class.php';
include_once ROOT_PATH . 'lib/class/news.class.php';
include_once ROOT_PATH . 'lib/class/publishcontent.class.php';
include_once ROOT_PATH . 'lib/class/message.class.php';
include_once ROOT_PATH . 'lib/class/catalog.class.php';
// include_once ROOT_PATH . 'lib/class/praise.class.php';
define('MOD_UNIQUEID', 'news');  //模块标识

class newsApi extends appCommonFrm
{
    private $api;
    private $news;
    private $publishcontent;
//     private $praise;
    public function __construct()
    {
        parent::__construct();
        $this->api = new content();
        $this->news = new news();
        $this->message = new message();
        $this->catalog = new catalog();
        $this->publishcontent = new publishcontent();
//         $this->praise = new praise();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
        unset($this->news);
        unset($this->message);
    }
    
    /**
     * 创建文稿
     */
    public function create()
    {
        $site_id = intval($this->input['site_id']);
        if ($site_id <= 0) $this->errorOutput(PARAM_WRONG);
        //采集数据的标识，有跳过此处
        if (!$this->input['gather'])
        {
            $data = $this->filter_data();
            $data['app'] = MOD_UNIQUEID;
            $data['mod'] = MOD_UNIQUEID;
            $data['need_indexpic'] = 1; //取索引图标识  
            //匹配内容图片
            if (!$data['indexpic'])
            {
                preg_match_all("/<img.*?\ssrc\s*=\s*[\"|\']?\s*([^>\"\'\s]*)(.*?)\/?>/i",htmlspecialchars_decode($data['content']),$arr);               
                if ($arr && is_array($arr) && !empty($arr[1]))
                {
                    $data['indexpic'] = $arr[1][0];
                }
            }
            $info = $this->news->create($data);
        }
        else 
        {
            $catalog=array();    
            $catalog = $this->input['catalog'];
            $data = array(
                    'title'         => $this->input['title'],
                    'subtitle'      => $this->input['subtitle'],
                    'brief'         => $this->input['brief'],
                    'keywords'      => $this->input['keywords'],
                    'content'       => $this->input['content'],
                    'author'        => $this->input['author'],
                    'source'        => $this->input['source'],
                    'iscomm'        => $this->input['iscomm'],                  //默认开启评论
                    'material_ids'  => $this->input['material_ids'],
                    'column_id'     => $this->input['column_id'],
                    'identifier'    => $this->input['identifier'],
                    'catalog_sort' => $this->input['catalog_sort'],
            );
            array_merge($data,$catalog);
            $data['app'] = MOD_UNIQUEID;
            $data['mod'] = MOD_UNIQUEID;
            $data['need_indexpic'] = 1; //取索引图标识  
            //匹配内容图片
            if (!$data['indexpic'])
            {
                preg_match_all("/<img.*?src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)(.*?)\/?>/i",htmlspecialchars_decode($data['content']),$arr);
                if ($arr && is_array($arr) && !empty($arr[1]))
                {
                    $data['indexpic'] = $arr[1][0];
                }
            }
            $info = $this->news->create($data,0);
        }
        if ($info['id'])
        {
            $indexpic = '';
            if ($info['indexpic_url']['host'] && is_array($info))
            {
                $indexpic = array(
                    'id'        => $info['indexpic_url']['id'],
                    'host'      => $info['indexpic_url']['host'],
                    'dir'       => $info['indexpic_url']['dir'],
                    'filepath'  => $info['indexpic_url']['filepath'],
                    'filename'  => $info['indexpic_url']['filename'],
                    'imgheight' => $info['indexpic_url']['imgheight'],
                    'imgwidth'  => $info['indexpic_url']['imgwidth'],
                );
            }
            $column_path = $this->input['n_column_path'];
            $localData = array(
                'site_id' => $site_id,
                'source_id' => $info['id'],
                'source' => 'news',
                'title' => $info['title'],
                'keywords' => $info['keywords'],
                'brief' => $info['brief'],
                'weight' => $info['weight'],
                'column_id' => $info['column_id'],
                'column_path' => $column_path ? serialize($column_path) : '',
                'state' => $info['state'],
                'app_uniqueid' => 'news',
                'mod_uniqueid' => 'news',
                'user_id' => $info['user_id'],
                'user_name' => $info['user_name'],
                'org_id' => $info['org_id'],
                'appid' => $info['appid'],
                'appname' => $info['appname'],
                'create_time' => $info['create_time'],
                'ip' => $info['ip'],
                'indexpic'=>$indexpic ? addslashes(serialize($indexpic)) : '',
                'template_sign' => $info['template_sign'],
                'outlink'   => $info['outlink'],
                'iscomment' => $info['iscomment'] ? 1 : 0,
//             	'is_praise' => $info['is_praise'] ? 1 : 0,
                'catalog'   => $info['catalog'],
            );
            $result = $this->api->create('content', $localData);
            if ($result['id'])
            {
                //记录栏目和内容ID的关系
                if ($this->input['column_id'])
                {
                    $relation = $this->api->column_cid($this->input['column_id'], $result['id']);
                }
            }
            //praise表中对应praise信息处理
//             if($info['is_praise'])
//             {
//             	$this->praise->create($info['is_praise'],intval($info['id']),MOD_UNIQUEID);
//             }
        }
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 编辑文稿
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0) $this->errorOutput(PARAM_WRONG);
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'news'));
        if (!$content_info) $this->errorOutput(PARAM_WRONG);
        $data = $this->filter_data();
        $data['need_indexpic'] = 1; //取索引图标识
        $info = $this->news->update($data, $content_info['source_id']);
        //如果是被发布的数据，更新发布库数据
//      if ($info['column_id'] && $info['column_url'])
//      {
//          $column_url = @unserialize($info['column_url']);
//          if (is_array($column_url) && !empty($column_url))
//          {
//              $weightData = array();
//              foreach ($column_url as $pubContentId)
//              {
//                  $weightData[$pubContentId] = $info['weight'];
//              }
//              if (!empty($weightData))
//              {
//                  $ret = $this->publishcontent->update_weight($weightData);
//                  if ($ret[0] != 'success')
//                  {
//                      $this->errorOutput('更新发布库权重失败');
//                  }
//              } 
//          }
//      }
        $indexpic = '';
        if ($info['indexpic_url']['host'] && is_array($info))
        {
            $indexpic = array(
                'id'        => $info['indexpic_url']['id'],
                'host'      => $info['indexpic_url']['host'],
                'dir'       => $info['indexpic_url']['dir'],
                'filepath'  => $info['indexpic_url']['filepath'],
                'filename'  => $info['indexpic_url']['filename'],
                'imgheight' => $info['indexpic_url']['imgheight'],
                'imgwidth'  => $info['indexpic_url']['imgwidth'],
            );
        }
        $column_path = $this->input['n_column_path'];
        $localData = array(
            'title' => $info['title'],
            'keywords' => $info['keywords'],
            'brief' => $info['brief'],
            'weight' => $info['weight'],
            'column_id' => $info['column_id'],
            'state' => $info['state'],
            'column_path' => $column_path ? serialize($column_path) : '',
            'indexpic'  => $indexpic ? addslashes(serialize($indexpic)) : '',
            'template_sign' => $info['template_sign'],
            'outlink'   => $info['outlink'],
            'iscomment' => $info['iscomment'] ? 1: 0,
//         	'is_praise' => $info['is_praise'] ? 1 : 0,
        );
        $result = $this->api->update('content', $localData, array('id' => $id));
        //建立栏目和内容关系
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
        $ids = $this->input['id'];              //内容ID
        $relation = $this->input['relation'];   //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);   //来源ID，文稿ID
        $result = $this->news->delete($sourceIds);
        
        if ($result)
        {
            $this->api->delete('content', array('id'=>$ids));
            //删除栏目和内容对应关系
            $this->api->del_column_cid($ids);
            //删除内容对应的评论
            $rss = $this->message->deleteComment('', MOD_UNIQUED , MOD_UNIQUED, $sourceIds);
            //删除内容的扩展字段
            $catalog = $this->catalog->delete($sourceIds);
            //删除内容的赞的信息
//             $praise = $this->praise->delete($result,MOD_UNIQUEID);
        }
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 
     * @Description 文稿审核，支持批量
     * @author Kin
     * @date 2014-2-25 下午02:35:30
     */
    public function audit()
    {       
        $ids = $this->input['id'];              //内容ID
        $relation = $this->input['relation'];   //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);   //来源ID，文稿ID
        $result = $this->news->audit($sourceIds, 1);
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
     * @Description 文稿打回，支持批量
     * @author Kin
     * @date 2014-2-25 下午04:50:20
     */
    public function back()
    {
        $ids = $this->input['id'];              //内容ID
        $relation = $this->input['relation'];   //内容ID与来源ID对应关系
        $sourceIds = implode(',', $relation);   //来源ID，文稿ID
        $result = $this->news->audit($sourceIds, 0);//
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
        $n_indexpic = $this->input['n_indexpic'];
        $n_material = $this->input['n_material'];
        $n_click_num = $this->input['n_click_num'] ? intval($this->input['n_click_num']) : 0;
        //过滤空值，防止提交空数据BUG
        if (is_array($n_material) && !empty($n_material))
        {
            $n_material = array_filter($n_material);
        }
        $n_brief = trim($this->input['n_brief']);
        $n_content = trim($this->input['n_content']);
        $n_author = trim($this->input['n_author']);
        $n_source = trim($this->input['n_source']);
        $n_iscomm = intval($this->input['n_iscomm']);
        $n_history_id = $this->input['n_history_id'];
        $n_template_sign = intval($this->input['n_template_sign']);
        $n_outlink  = $this->input['n_outlink'];
        $n_iscomment = intval($this->input['n_iscomment']) ? 1 : 0;
//         $n_ispraise = intval($this->input['n_ispraise']) ? 1 : 0;
        if (empty($n_title) || (!$this->input['n_content'] && !$this->input['outlink']))
        {
            $this->errorOutput(PARAM_WRONG);
        }   
        $catalog=array();    
        $catalog = $this->input['catalog'];
        if(!$catalog)
        {
        	$catalog = array();
        }
        foreach ($catalog as $k=>$v)
        {
        	if(strstr($k, "editor"))
        	{
        		if(is_array($v))
        		{
        			$catalog[$k] = addslashes(json_encode(array('title' => $v['title'],'content'=>html_entity_decode($v['content']))));
        		}
        	}
        }
        $identifier = $this->input['identifier'];
        $catalog_sort = $this->input['catalog_sort']; //分类标识
        $ret =  array(
            'column_id' => $n_column,
            'title' => $n_title,
            'subtitle' => $n_subtitle,
            'keywords' => $n_keywords,
            'weight' => $n_weight,
            'indexpic' => $n_indexpic,
            'material_id' => $n_material,
            'brief' => $n_brief,
            'content' => $n_content,
            'author' => $n_author,
            'source' => $n_source,
            'other_settings[iscomm]' => $n_iscomm,
            'material_history'=>$n_history_id,
            'template_sign' => $n_template_sign,
            'outlink'   => $n_outlink,
            '_outercall' => 1, //防止用户group_type被改变的问题
            'iscomment' => $n_iscomment,
//         	'is_praise'	=> $n_ispraise,
            'identifier' => $identifier,
            'catalog_sort' => $catalog_sort,
        	'click_num'	=> $n_click_num,
        );
        return array_merge($ret,$catalog);
    }
    /**
     * 
     * @Description 幻灯片切换
     * @author Kin
     * @date 2014-2-20 下午05:32:25
     */
    public function updateSlide()
    {
        
        $id = intval($this->input['id']);
        $weight = intval($this->input['n_weight']);
        if ($id <= 0)
        {
            $this->errorOutput(PARAM_WRONG);
        } 
        $content_info = $this->api->detail('content', array('id' => $id, 'source' => 'news'));
        if (!$content_info)
        {
            $this->errorOutput(PARAM_WRONG);
        } 
        $data = array(
            $content_info['source_id']  => $weight, 
        );
        //对文稿进行特殊处理
        $data = array(
            'data'=>htmlspecialchars(json_encode($data)),
        );
        $info = $this->news->updateWeight($data, $content_info['source_id']);
        if ($info)
        {
            $localData = array(
                'weight' => $weight,
            );
            $result = $this->api->update('content', $localData, array('id' => $id));
            //更新发布库
            if ($result)
            {
                $newInfo = $this->news->detail($content_info['source_id']);
                if ($newInfo['column_url'])
                {
                    $column_url = @unserialize($newInfo['column_url']);
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
     * 批量修改内容的所属栏目
     */
    public function editColumnsById()
    {    	
    	$id = intval($this->input['id']);
    	$column_id = intval($this->input['column_id']);
    	$column_name = trim($this->input['column_name']);
    	$content_info = $this->api->detail('content', array('id' => $id, 'source' => 'news'));
    	$column_path = serialize(array(
    			$column_id => $column_name,
    	));
    	//修改news/article下column_id和column_path
    	$ret = $this->news->editColumnsById(intval($content_info['source_id']),$column_id,$column_path);

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
    	$this->news->moveToTrash($id,$news_id);
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

$out = new newsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'none';
}
$out->$action();
?>
