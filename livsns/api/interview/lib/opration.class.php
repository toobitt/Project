<?php 
class opration extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function op($user_id,$interview_id)
	{
		$sql = 'SELECT ug.role FROM ' .DB_PREFIX .'user_group ug 
				LEFT JOIN '.DB_PREFIX.'interview_user iu ON  ug.id=iu.group 
				where iu.user_id ='.$user_id.' AND iu.interview_id ='.$interview_id;
		$res = $this->db->query_first($sql);
		$r = $res['role']?$res['role']:0;
		if ($r)
		{
			$op = $this->settings['roleoption'][$r];
		}else {
			$op = array();
		}
		return $op;
		
	}
	/**
	 * 
	 * 改变状态
	 * @param int $id  内容ID
	 * @param int $state 状态位   1-未审核，2-已审核，3-已忽略，4-待回复，9-删除
	 */
	public function changeState($id,$state)
	{
		$sql = 'UPDATE '.DB_PREFIX.'records SET state='.$state.' WHERE id IN ('.$id.')';
		$this->db->query($sql);
		return true;
	}
	/**
	 * 
	 * 修改内容
	 * @param int $id 内容ID
	 * @param string $question  修改的内容
	 */
	public function updateQuestion($id,$question)
	{
		$sql = 'UPDATE '.DB_PREFIX.'records SET question="'.$question.'" WHERE id = '.$id;
		$this->db->query($sql);
		return true;
	}
	/**
	 * 
	 * 获取引用内容
	 * @param int $id
	 * @return string $question 返回内容
	 */
	public function getQuestion($id)
	{
		$sql = 'SELECT user_name,question FROM '.DB_PREFIX.'records WHERE id='.$id;
		$res = $this->db->query_first($sql);
		$question = $res['question'];
		return $question;
	}
	public function getPub($id)
	{
		$sql = 'SELECT is_pub FROM '.DB_PREFIX.'records WHERE id='.$id;
		$res = $this->db->query_first($sql);
		$pub = $res['is_pub'];
		return $pub;
	}
	/**
	 * 
	 * 通过内容ID获取访谈ID
	 * @param unknown_type $id
	 */
	public function getId($id)
	{
		$sql = 'SELECT interview_id FROM '.DB_PREFIX.'records WHERE id='.$id;
		$res = $this->db->query_first($sql);
		return $res['interview_id'];
	}

}




