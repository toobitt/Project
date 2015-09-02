<?php
define('MOD_UNIQUEID','praise');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/praise_mode.php');
include_once ROOT_PATH . 'lib/class/publishcontent.class.php';
include_once ROOT_PATH . 'lib/class/news.class.php';
include_once ROOT_PATH . 'lib/class/tuji.class.php';
include_once ROOT_PATH . 'lib/class/livmedia.class.php';
include_once ROOT_PATH . 'lib/class/vote.class.php';
class praise_update extends adminUpdateBase
{
	private $mode;
	private $publishcontent;
	private $news;
	private $tuji;
	private $livmedia;
	private $vote;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new praise_mode();
		$this->publishcontent = new publishcontent();
		$this->news = new news();
		$this->tuji = new tuji();
		$this->livmedia = new livmedia();
		$this->vote = new vote();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	/**
	 * 增加了一条内容的时候如果他选择了开启赞功能，则创建一条赞
	 * 
	 * @see adminUpdateBase::create()
	 */
	public function create()
	{
		$content_id = intval($this->input['content_id']);
		$conent_module = trim($this->input['source']);
		$is_praise = intval($this->input['is_praise']);
		$data = array(
			'content_id' => $content_id,
			'content_module' => $conent_module,
			'is_praise'  => $is_praise,
		);
		$vid = $this->mode->create($data);
		if($vid)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	/**
	 * 编辑内容时对赞功能的处理
	 * @see adminUpdateBase::update()
	 */
	public function update()
	{
		$content_id = intval($this->input['content_id']);
		$conent_module = trim($this->input['source']);
		$is_praise = intval($this->input['is_praise']);
		
		$detail = $this->mode->getPraiseInfoByContentId($content_id);
		
		//如果已经存在并且 $detail['is_praise']!=$is_praise则进行update   
		//没有则create并且 $is_praise=1则create
		if($detail && $detail['is_praise']!=$is_praise) 
		{
			$updateArray = array(
				'is_praise' => $is_praise,
			);
			
			$result = $this->mode->update($detail['id'],$updateArray);
		}
		elseif(!$detail && $is_praise == 1)
		{
			$data = array(
					'content_id' => $content_id,
					'content_module' => $conent_module,
					'is_praise'  => $is_praise,
			);
			$result = $this->mode->create($data);
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 赞的操作： 赞或者取消赞
	 * @see adminUpdateBase::update()
	 */
	public function updateDoPraise()
	{
		if(!$this->user)
		{
			$this->errorOutput(NO_MEMBER_INFO);
		}
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$operate = trim($this->input['operate']);
		if($operate!='add' && $operate!='cancel')
		{
			$this->errorOutput(OPERATE_WRONG);
		}
		$member_id = $this->user['user_id'];	
		$id = intval($this->input['id']);
		$device_token = trim($this->input['device_token']);	
		//根据$id获取内容信息
		$info = $this->publishcontent->get_content_by_rid($id);
		$content_id = intval($info['content_fromid']);
		//如果是赞的话，do_praise里增加一条数据,praise中的对应的数据count+1
		//取消赞，do_praise里对应数据删除，praise中对应数据count-1	
		$praise_info = $this->mode->getPraiseInfoByContentId($content_id);
		//do_praise表增加或者删除赞的信息
		//如果是add先判断是否已经赞过
		$do_praise_info = $this->mode->getOneDoPraiseInfo($content_id,$member_id);
		if($operate == 'add' && $do_praise_info)
		{
			$this->errorOutput(HAS_PRAISE);
		}
		if($operate == 'cancel' && !$do_praise_info)
		{
			$this->errorOutput(NEVER_PRAISE);
		}
		$this->mode->doPraise($content_id,$device_token,$operate,$member_id);	
		//更新praise表 返回更新后praise信息
		$retInfo = $this->mode->updatePraise($praise_info['id'],$operate);
		//更新news/article中praise_count
		//更新发布库/content_relation 中praise_count
		if($retInfo)
		{
			switch($retInfo['content_module'])
			{
				case 'news':
					$this->news->update_praise_count($operate , $content_id , 1);
					break;
				case 'tuji':
					$this->tuji->update_praise_count($operate , $content_id , 1);
					break;
				case 'video':
					$this->livmedia->update_praise_count($operate , $content_id , 1);
					break;
				case 'vote':
					$this->vote->update_praise_count($operate , $content_id , 1);
					break;
			}
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 检测该会员是否赞过此文章
	 */
	public function checkPraise()
	{
		$member_id = $this->user['user_id'];
		$id = intval($this->input['id']);//发布库ID
		$info = $this->publishcontent->get_content_by_rid($id);
		$content_id = intval($info['content_fromid']);
		$result = $this->mode->getOneDoPraiseInfo($content_id,$member_id);
		if($result)
		{
			$this->addItem(1);
		}
		else
		{
			$this->addItem(0);	
		}
		$this->output();
	}
	
	/**
	 * 删除相关内容的赞的信息
	 * @see adminUpdateBase::delete()
	 */
	public function delete()
	{	
		$content_id = intval($this->input['content_id']);
		$source = trim($this->input['source']);
		//先删除praise
		$ret= $this->mode->deletePraiseByContentId($content_id,$source);
		if($ret)
		{
			//再删除do_praise表
			$result = $this->mode->deleteDoPraise($content_id);
		}
		$this->addItem($result);
		$this->output();	
	}
		
	public function audit(){}
	
	public function sort(){}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new praise_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>