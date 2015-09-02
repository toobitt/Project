<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: show.php 2315 2011-02-27 13:33:03Z yuna $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class showApi extends appCommonFrm
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	public function show()
	{
		$ids = $this->input['ids'];
		if(empty($ids))
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}
		else
		{
			$ids = urldecode($ids);
			$idArr = array_unique(explode(',',$ids));
			$num = count($idArr);
			if($num > BATCH_FETCH_LIMIT)
			{		
				$idArr = array_slice($idArr,0,BATCH_FETCH_LIMIT);
			}
			foreach ($idArr as $key => $value)
			{
				$idArr[$key] = "'".$value."'";					
			}	
			$ids = implode(',',$idArr);
			$sql = "select m.id,m.user_group_id,g.groupname from ".DB_PREFIX."member m left join ".DB_PREFIX."member_group g on g.id=m.user_group_id where m.id in ($ids)";
			$rt = $this->db->fetch_all($sql);
			$this->setXmlNode('status','group_id');
			foreach((array)$rt as $key => $value)
			{
				if($value['user_group_id'] == 2)
				{
					$value['status'] = 'false';
				}
				else
				{
					$value['status'] = 'true';
				}
				unset($value['user_group_id']);
				$this->addItem($value);	
			}
			
			return $this->output();	
		}
		
	}	

}
$out = new showApi();
$out->show();
?>