<?php
class identifierUserSystem extends publicCore
{
	protected  $iusid = 0;
	protected $iusidS = array();//IUSID别名变量，用于储存多个iusid和验证
	protected $iusname = '';
	protected $brief = '';
	protected $identifier = 0;
	protected $opened = 0;
	protected $user_id = 0;
	protected $user_name = '';
	protected $create_time = 0;
	protected $update_time = 0;
	public function __construct()
	{
		parent::__construct();
		parent::newSql();
		parent::getSql()->setTable('identifier_user_system');

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function rules()
	{
		return array();
	}

	public function show()
	{
		$this->setWhere($this->params);
		return parent::show();
	}

	public function count()
	{
		$this->setWhere($this->params);
		return parent::count();
	}

	public function detail()
	{
		$this->setWhere($this->params);
		return parent::detail();
	}

	public function display($opened)
	{
		$ret = array();
		parent::getSql()->unsetWhere();
		$openedInfo = $this->setFieldS('opened')->detail();
		if($openedInfo['opened']  != $opened)
		{
			$this->setDatas('opened',$opened);
			$ret = $this->update();
		}
		$arr = array(
			'iusid' => $this->getIusid(),
			'opened' => $opened ? 1 : 0,
		);
		return $arr;
	}

	public function create()
	{
		parent::getSql()->setData($this->data);
		parent::getSql()->setOrderId(true);
		parent::getSql()->setPk('iusid');
		parent::getSql()->setOrderField('iusid');
		return parent::create();
	}

	public function update()
	{
		$this->setWhere($this->params);
		$this->setData($this->data);
		return parent::update();
	}

	public function delete()
	{
		$this->checkIdentifierUser();
		$this->setWhere($this->params);
		return parent::delete();
	}

	public function checkIdentifierUser()
	{
		$member = new member();
		foreach ($this->getIusidS() as $v)
		{
			if($identifier = $this->getIdentifierForIusid($v))
			{
				if($member->getMemberIdForIdentifier($identifier))
				{
					throw new Exception(IDENTIFIER_SYSTEM_NOT_DEL, 200);
				}
			}
		}
	}

	public function checkIdentifier()
	{
		if($this->settings['identifierUserSystem'])
		{
			if(!$this->verify(array('identifier' => $this->getIdentifier(),'opened'=>1)))
			{
				throw new Exception(IDENTIFIER_SYSTEM_NOT, 200);
			}
		}
		return $this->getIdentifier();
	}

	public function checkIusId($_iusid)
	{
		$_iusid = (int)$_iusid;
		if(!$_iusid)
		{
			throw new Exception(NO_DATA_ID, 200);
		}
		else if ($_iusid<0)
		{
			throw new Exception(DATA_ID_MIN_0_ERROR, 200);
		}
		return $_iusid;
	}

	public function verifyIusId()
	{
		if(!$this->verify(array('iusid' => $this->iusid)))
		{
			throw new Exception(NO_DATA, 200);
		}
		return $this;
	}

	public function modifyForbidIdentifierForZero()
	{
		$identifierInfo = $this->getIdentifierForIusidAll($this->iusidS?$this->iusidS:array($this->iusid));
		if($identifierInfo && is_array($identifierInfo))
		{
			foreach ($identifierInfo as $v)
			{
				if($v==0)
				{
					throw new Exception(IDENTIFIER_SYSTEM_SYSTEM, 200);
				}
			}
		}
		return $this;
	}

	public function getIdentifierForIusidAll(array $iusid)
	{
		$this->setWhere(array('iusid' => $iusid));
		$this->setFieldS('iusid,identifier');
		$this->setStype(4);
		$this->setKeyS('iusid');
		$this->setSotherKey('identifier');
		return parent::show();
	}

	public function getIdentifierForIusid($iusid)
	{
		$iusid = (int)$iusid;
		$IdentifierInfo = $this->getIdentifierForIusidAll(array($iusid));
		return $IdentifierInfo[$iusid];
	}

	public function getIusNameForIdentifierAll(array $identifier)
	{
		$this->setWhere(array('identifier' => $identifier));
		$this->setFieldS('iusname,identifier');
		$this->setStype(4);
		$this->setKeyS('identifier');
		$this->setSotherKey('iusname');
		$ret = parent::show();
		$rets = array();
		foreach ($identifier as $v)
		{
			if(isset($ret[$v]))
			{
				$rets[$v] = $ret[$v]."($v)";
			}
			else
			{
				$rets[$v] = "系统未定义($v)";
			}
		}
		return $rets;
	}

	public function getIusNameForIdentifier($identifier)
	{
		$identifier = (int)$identifier;
		$IdentifierInfo = $this->getIusNameForIdentifierAll(array($identifier));
		return $IdentifierInfo[$identifier];
	}


	public function setIusid($_iusid)
	{
		$this->iusid = $_iusid;
		return $this;
	}

	public function getIusid()
	{
		return $this->iusid;
	}

	public function setIusidS(array $_iusids)
	{
		$this->iusidS = $_iusids;
		return $this;
	}

	public function getIusidS()
	{
		return $this->iusidS;
	}

	public function checkIusIdS($_iusids)
	{
		$_iusids = (string)trim($_iusids);
		if(!$_iusids)
		{
			throw new Exception(NO_DATA_ID, 200);
		}
		else if ($iusIdArr = dexplode($_iusids,2))
		{
			foreach ($iusIdArr as $tmpid)
			$this->checkIusId($tmpid);
		}
		return $iusIdArr;
	}

	public function verifyIusIdS()
	{
		foreach ($this->iusidS as $val)
		{
			!isset($tmpid) && $tmpid = $this->iusid;
			$this->iusid = $val;
			$this->verifyIusId();
		}
		isset($tmpid) && $this->iusid = $tmpid;
		return $this;
	}

	public function checkIusname($_iusname)
	{
		$_iusname = trim($_iusname);
		if (!$_iusname)
		{
			throw new Exception(NO_NAME, 200);
		}
		return $_iusname;
	}

	public function setIusname($_iusname)
	{
		$this->iusname = $_iusname;
		return $this;
	}

	public function getIusname()
	{
		return $this->iusname;
	}

	public function setBrief($_brief)
	{
		$this->brief = trim($_brief);
		return $this;
	}

	public function getBrief()
	{
		return $this->brief;
	}

	public function setOpened($opened = 0)
	{
		$this->opened = (int)$opened;
		return $this;
	}

	public function getOpened()
	{
		return $this->opened;
	}

	public function setUpdateTime()
	{
		$this->update_time = TIMENOW;
		return $this;
	}

	public function getUpdateTime()
	{
		return $this->update_time;
	}

	public function setCreateTime()
	{
		$this->create_time = TIMENOW;
		return $this;
	}

	public function getCreateTime()
	{
		return $this->create_time;
	}

	public function setUserName($_userName)
	{
		$this->user_name = $_userName;
		return $this;
	}

	public function setUserId($_userId)
	{
		$this->user_id = (string)$_userId;
		return $this;
	}

	public function setIdentifier($Identifier = null)
	{
		if($Identifier<0){
			throw new Exception(IDENTIFIER_SYSTEM_ID_ERROR, 200);
		}
		$this->identifier = isset($Identifier)?(int)$Identifier:$this->markIdentifier();
		return $this;
	}

	public function getIdentifier()
	{
		return $this->identifier;
	}

	/**
	 * 制作Identifier
	 *  ...
	 */
	private function markIdentifier()
	{
		return $this->getMaxIdentifier() + 1;
	}


	private function getMaxIdentifier()
	{
		$this->setFieldS('max(identifier) as identifier');
		$identifier = $this->detail();
		return (int)$identifier['identifier'];
	}


}

?>