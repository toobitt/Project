<?php
abstract class publicCore extends classCore
{
	private static $membersql;
	private static $oldWith = array();
	protected $params = array();
	protected $paramType = array();
	protected $data = array();
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * @return Array
	 */
	abstract public function rules();
	
	public function setParams($key,$val = null,$diykey = array(),$asname='')
	{	
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if(is_array($val)&&isset($val[$v]))
				{
					$this->setParams($v, $val[$v],$diykey,$asname);
				}
				else {
					$this->setParams($v,$val,$diykey,$asname);
				}
			}
		}
		elseif($key&&isset($val))
		{
			$this->params[($asname?$asname.'.':'').$key] = $val;
		}
		else if ($key)
		{
			$this->params[($asname?$asname.'.':'').$key] = isset($this->$diykey[$key])?$this->$diykey[$key]:$this->$key;
		}
		return $this->params[($asname?$asname.'.':'').$key];
	}
	
	public function unsetParams($key = '',$asname='')
	{
		if($key&&isset($this->params[($asname?$asname.'.':'').$key]))
		{
			unset($this->params[($asname?$asname.'.':'').$key]);
		}
		elseif(!$key)
		{
			$this->params = array();
		}
	}
	
	public function setParamType($key,$type,$val = 1,$asname='')
	{
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if($type)
				{
					$this->setParamType($v,$type,$val,$asname);
				}
			}
		}
		elseif($key&&$type) {
			$this->paramType[($asname?$asname.'.':'').$key][$type] = $val;
		}
		return $this->paramType[($asname?$asname.'.':'').$key];
	}
	public function unsetParamType($key = '')
	{
		if($key&&isset($this->paramType[$key]))
		{
			unset($this->paramType[$key]);
		}
		elseif(!$key)
		{
			$this->paramType = array();
		}
	}
	
	public function setDatas($key,$val = null)
	{
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if($val && isset($val[$k]))
				{
					$this->setDatas($v, $val[$k]);
				}
				else {
					$this->setDatas($v);
				}
			}
		}
		elseif($key&&isset($val)) {			
			$this->data[$key] = $val;
		}
		elseif($key)
		{
			isset($this->$key)&&$this->data[$key] = $this->$key;
		}
		return is_array($key)?$this->data:$this->data[$key];
	}
	
	public function unsetDatas($key = '')
	{
		if($key&&isset($this->data[$key]))
		{
			unset($this->data[$key]);
		}
		elseif(!$key)
		{
			$this->data = array();
		}
	}
	
	public function setAs($asname='')
	{
		self :: getSql()->setAsTable($asname);
		return $this;
	}
	
	public function setLimit($_offset,$_count)
	{
		self :: getSql()->limit($_offset, $_count);
		return $this;
	}
	public function setFieldS($_field = '*')
	{
	    self :: getSql()->setSelectField($_field);
		return $this;
	}
	public function setWhere($_where)
	{
		self :: getSql()->where($_where);
		return $this;
	}
	public function setKeyS($_key = '')
	{
		self :: getSql()->setKey($_key);
		return $this;
	}
	
	public function setStype($stype = 1)
	{
		self :: getSql()->setType($stype);
		return $this;
	}
	
	public function setSotherKey($SotherKey = '')
	{
		self :: getSql()->setOtherKey($SotherKey);
		return $this;
	}
	
	public function setObType($type)
	{
		return $type?' ASC':' DESC';
	}
	
	public function setOrderbyS($_orderby)
	{
		self :: getSql()->orderby($_orderby);
		return $this;
	}
	
	/**
	 * 
	 * 这个是membersql设置数据的方法 ...
	 * @param unknown_type $_orderby
	 */
	public function setData($_data)
	{
		self :: getSql()->setData($_data);
		return $this;
	}
	
	public function setOrderId($isOrderId)
	{
		self :: getSql()->setOrderId($isOrderId);
		return $this;
	}
	
	public function setDataFormat($dataformat)
	{
		self :: getSql()->setDataFormat($dataformat);
	}
	
	public function setJoin($sql = '')
	{
		self :: getSql()->join($sql);
	}
	
	public static function newSql()
	{
		return self :: $membersql = new membersql();
	}
	
	public static function getSql()
	{
		return self::$membersql;
	}
	
	public static function setWith(publicCore $obj)
	{
		self::$oldWith['membersql'] = self::$membersql;
		self::$membersql = $obj->getSql();
	}
	
	public static function unWith(){
		self::$membersql = self::$oldWith['membersql'];
	}
	
	public function update()
	{
		return self :: getSql()->update();
	}
	
	public function create()
	{
		return self :: getSql()->create();
	}
	
	public function delete()
	{
		return self :: getSql()->delete();
	}
	
	public function count()
	{
		return self :: getSql()->count();
	}
	
	public function show()
	{
		return self :: getSql()->show();
	}
	
	public function detail()
	{
		return self :: getSql()->detail();
	}
	
	/**
	 * 
	 * 等待完善,INPUT参数检测 ...
	 * @param unknown_type $input
	 * @throws Exception
	 */
	public function checkrules($input = array())
	{
		$rulesConfig =  $this->rules();
	}
	
	public function verify($param)
	{
		return self :: getSql()->verify('',$param);
	}
}
?>