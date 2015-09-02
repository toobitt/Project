<?php
/***************************************************************************
 * HOGE WEB
 *
 * @package     DingDone WEB
 * @author      RDC3 - Kin
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-28
 * @encoding    UTF-8
 * @description 发布接口文件
 **************************************************************************/
define('MOD_UNIQUEID', 'vote_publish');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'frm/publish_interface.php');
class VotePublish extends adminUpdateBase implements publish
{
	private $curl;
	
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function create()
    {
        
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }

    public function audit()
    {
        
    }

    public function sort()
    {
        
    }

    public function publish()
    {
        
    }

    public function get_content()
    {
        $id         = intval($this->input['from_id']);
        $sort_id    = intval($this->input['sort_id']);
        $offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $num        = $this->input['num'] ? intval($this->input['num']) : 10;      
        $this->curl = new curl($this->settings['App_vote']['host'], $this->settings['App_vote']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_content');
        $this->curl->addRequestData('from_id', $id);
        $this->curl->addRequestData('sort_id', $sort_id);
        $this->curl->addRequestData('offset', $offset);
        $this->curl->addRequestData('num', $num);
        $ret = $this->curl->request('admin/vote_publish.php');
        $ret = $ret[0];
        $this->addItem($ret);
        $this->output();
    }

    public function update_content()
    {
        $data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
        $this->curl = new curl($this->settings['App_vote']['host'], $this->settings['App_vote']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_content');
        $this->array_to_add('data', $data);
        $this->curl->request('admin/vote_publish.php');
        //更新叮当数据
        $sql = "SELECT * FROM " . DB_PREFIX . "content WHERE source = 'vote' AND source_id = " . $data['from_id'];
        $ret = $this->db->query_first($sql);
      	if ($ret['state'] != 1)
        {
            $sql = "UPDATE " . DB_PREFIX . "content SET publish_id = 0, order_id = 0  WHERE source = 'vote' AND source_id = " . $data['from_id'];
            $this->db->query_first($sql);
        }
        else 
        {
        	if (!empty($data['content_url']) && is_array($data['content_url']))
            {
            	$publish_id = $data['content_url'][key($data['content_url'])];
            	$sql = "UPDATE " . DB_PREFIX . "content SET publish_id = " . $publish_id . ", order_id = ".$publish_id." WHERE source = 'vote' AND  source_id = " . $data['from_id'];
        		$this->db->query($sql);
            }
        }
      	if ($data['expand_id'] === '0' || $data['expand_id'] === NULL )   //如果expand_id为空说明为打回状态，则更改字表expand_id字段
        {
           	$sql = "UPDATE " . DB_PREFIX . "content SET publish_id = 0, order_id = 0 WHERE source = 'vote' AND  source_id = " . $data['from_id'];
            $this->db->query($sql);
        }
        $this->addItem('true');
        $this->output();
    }
	//目前用不到
    public function delete_publish()
    {
        $data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
        $this->curl = new curl($this->settings['App_vote']['host'], $this->settings['App_vote']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete_publish');
        $this->array_to_add('data', $data);
        $this->curl->request('admin/vote_publish.php');
        //叮当只能发布到一个栏目中，不存在只删除某一个栏目
        $sql = " UPDATE ".DB_PREFIX."content SET publish_id = 0, order_id = 0  WHERE source = 'vote' AND source_id = ".$data['from_id'];
        $this->db->query($sql);
        $this->addItem('true');
        $this->output();
    }
    
    //专题应用，添加文稿数据，回调,目前用不到
    public function up_content()
    {
    	$data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
    	$this->curl = new curl($this->settings['App_vote']['host'], $this->settings['App_vote']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'up_content');
       	$this->array_to_add('data', $data);
        $this->curl->request('admin/vote_publish.php');
    }
    
    //区块应用，添加文稿数据，回调，目前用不到
    public function update_block_content()
    {
     	$data = $this->input['data'];
        if (empty($data))
        {
            return false;
        }
    	$this->curl = new curl($this->settings['App_vote']['host'], $this->settings['App_vote']['dir']);
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_block_content');
        $this->array_to_add('data', $data);
        $this->curl->request('admin/vote_publish.php');
    }

    public function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }
    
	public function array_to_add($str , $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if(is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}

}

$out    = new VotePublish();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
