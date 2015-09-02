<?php
/***************************************************************************
* $Id: vote_add.php 44560 2015-03-11 06:45:19Z sunfengxiang $
* 添加投票选项
***************************************************************************/
define('MOD_UNIQUEID', 'vote');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class voteAddApi extends appCommonFrm
{
	private $mVote;
	private $mVerifyCode;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/vote.class.php';
		$this->mVote = new vote();
		
		require_once ROOT_PATH . 'lib/class/verifycode.class.php';
		$this->mVerifyCode = new verifycode();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 投票接口
	 * @param $id int 投票ID
	 * @param $option_id string 选项id (1,2,3)
	 * @param $verify_code string 验证码
	 * @param $other_title string 用户提交过来的其他选项
	 * 
	 */
	public function vote_option()
	{
		$data=array(
			'title' => $this->input['title'],
			'vote_question_id' => $this->input['vote_question_id'],
			'pictures_info' => html_entity_decode($this->input['pictures_info']),
			'feedback_id' => $this->input['id'],
		);
		$id=$this->db->insert_data($data, 'question_option');
		if($id){
			echo '1';
		}
	}
	
	public function unknow()
	{}
}

$out = new voteAddApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>