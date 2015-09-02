<?php
/**
 * 乐清政务查询
 */
class yqzw
{
	public function __construct()
	{
		$this->can = array(
			'projid' => $_REQUEST['projid'],
			'pwd' => $_REQUEST['pwd'],
			'num' => $_REQUEST['num'],
			'month' => $_REQUEST['month'],
			'day' => $_REQUEST['day'],
		);
		$this->C = new SoapClient('http://10.20.158.73/axis2/services/PIIWS?wsdl');
	}
	
	public function show()
	{
		$fun = $_REQUEST['f'];
		if(!$fun)
		{
			echo 'NO FUNTION NAME';exit;
		}
		if(!method_exists('yqzw',$fun))
		{
			echo 'FUNTION NO EXISTS';exit;
		}
		$this->$fun($this->can);
	}
	
	
	/**
	 * （1）根据申报号（受理编号）查询办件信息
	 *	@param String projid 申报号（受理编号）
	 *	@返回值：apasInfoJson 办件信息对象的Json字符串
	 *	方法名：getInfoJsonFromProjid(String projid)
	 *	返回值：apasInfo的Json字符串 
	 */
	public function getInfoJsonFromProjid()
	{
		try{
			if(!$this->can['projid'])
			{
				echo 'NO PARAM projid';exit;
			}
			$obj = $this->C->getInfoJsonFromProjid(array('projid' => $this->can['projid']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	/**
	 * （2）根据申报号（受理编号）和查询密码查询办件信息
	@param String projid 申报号（受理编号）
	@param String pwd 查询密码
	@返回值：apasInfoJson 办件信息对象的Json字符串
	方法名：getInfoJsonFromProjidAndPwd(String projid, String pwd)
	返回值：apasInfo的Json字符串 
	 */
	public function getInfoJsonFromProjidAndPwd()
	{
		try{
			if(!$this->can['projid'])
			{
				echo 'NO PARAM projid';exit;
			}
			if(!$this->can['pwd'])
			{
				echo 'NO PARAM pwd';exit;
			}
			$obj = $this->C->getInfoJsonFromProjidAndPwd(array('projid' => $this->can['projid'],'pwd' => $this->can['pwd']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	/**
	 * (3）查询当天所有的办件信息（所有状态）
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getInfosJsonByToday()
	返回值：ApasInfoList的Json字符串
	 */
	public function getInfosJsonByToday()
	{
		try{
			$obj = $this->C->getInfosJsonByToday();
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （4）查询当天所有的办件信息（所有状态） - 限制取num条数量的结果
	 @param int num 限制取num条
 	@return apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getInfosJsonByTodayLimit(int num)
	返回值：ApasInfoList的Json字符串
	 */
	public function getInfosJsonByTodayLimit()
	{
		try{
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getInfosJsonByTodayLimit(array('num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	/**
	 * （5）查询当天办结的办件信息
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getBanjieInfosJsonByToday()
	返回值：ApasInfoList的Json字符串 
	 */
	public function getBanjieInfosJsonByToday()
	{
		try{
			$obj = $this->C->getBanjieInfosJsonByToday();
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （6）查询当天办结的办件信息 - 限制取num条数量的结果
	@param int num 限制取num条
	 @return apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getBanjieInfosJsonByTodayLimit(int num)
	返回值：ApasInfoList的Json字符串

	 */
	public function getBanjieInfosJsonByTodayLimit()
	{
		try{
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getBanjieInfosJsonByTodayLimit(array('num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （7）查询当天在办的办件信息
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getZaiBanInfosJsonByToday()
	返回值：ApasInfoList的Json字符串 
	 */
	public function getZaiBanInfosJsonByToday()
	{
		try{
			$obj = $this->C->getZaiBanInfosJsonByToday();
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （8）查询当天在办的办件信息 - 限制取num条数量的结果
	@param int num 限制取num条
	 @return apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getZaiBanInfosJsonByTodayLimit(int num)
	返回值：ApasInfoList的Json字符串 
	 */
	public function getZaiBanInfosJsonByTodayLimit()
	{
		try{
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getZaiBanInfosJsonByTodayLimit(array('num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （9）按月份查询所有的办件信息（所有状态）
	@param int month 月份
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getInfosJsonByMonth(int month)
	返回值：ApasInfoList的Json字符串 
	 */
	public function getInfosJsonByMonth()
	{
		try{
			if(!$this->can['month'])
			{
				echo 'NO PARAM month';exit;
			}
			$obj = $this->C->getInfosJsonByMonth(array('month' => $this->can['month']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （10）按月份查询所有的办件信息（所有状态）- 限制取num条数量的结果
	@param int month 月份
	@param int num 限制取num条
	 @return apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getInfosJsonByMonthLimit(int month, int num)
	返回值：ApasInfoList的Json字符串 

	 */
	public function getInfosJsonByMonthLimit()
	{
		try{
			if(!$this->can['month'])
			{
				echo 'NO PARAM month';exit;
			}
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getInfosJsonByMonthLimit(array('month' => $this->can['month'],'num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （11）按月份查询办结的办件信息
	@param int month 月份
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getBanjieInfosJsonByMonth(int month)
	返回值：ApasInfoList的Json字符串
	 */
	public function getBanjieInfosJsonByMonth()
	{
		try{
			if(!$this->can['month'])
			{
				echo 'NO PARAM month';exit;
			}
			$obj = $this->C->getBanjieInfosJsonByMonth(array('month' => $this->can['month']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （12）按月份查询办结的办件信息- 限制取num条数量的结果
	@param int month 月份
	@param int num 限制取num条
	 @return apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getBanjieInfosJsonByMonthLimit(int month, int num)
	返回值：ApasInfoList的Json字符串
	 */
	public function getBanjieInfosJsonByMonthLimit()
	{
		try{
			if(!$this->can['month'])
			{
				echo 'NO PARAM month';exit;
			}
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getBanjieInfosJsonByMonthLimit(array('month' => $this->can['month'],'num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （13）按月份查询在办的办件信息
	@param int month 月份
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getZaiBanInfosJsonByMonth(int month)
	返回值：ApasInfoList的Json字符串 
	 */
	public function getZaiBanInfosJsonByMonth()
	{
		try{
			if(!$this->can['month'])
			{
				echo 'NO PARAM month';exit;
			}
			$obj = $this->C->getZaiBanInfosJsonByMonth(array('month' => $this->can['month']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （14）按月份查询在办的办件信息- 限制取num条数量的结果
	@param int month 月份
	@param int num 限制取num条
	 @return apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getZaiBanInfosJsonByMonthLimit(int month, int num)
	返回值：ApasInfoList的Json字符串
	 */
	public function getZaiBanInfosJsonByMonthLimit()
	{
		try{
			if(!$this->can['month'])
			{
				echo 'NO PARAM month';exit;
			}
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getZaiBanInfosJsonByMonthLimit(array('month' => $this->can['month'],'num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （15）查询n天以前所有的办件信息（所有状态）
	  查询例举:（1）要查询一星期的办件信息，day=7即可 
         （2）要查询一个月的办件信息，day=30或31即可（根据大小月调整值） 
         （3）要查询一年的办件信息，day=365或366即可（根据大小年调整值） 
         （4）要自定义查询n天的办件信息，day=n（n=你想要查询的天数）。
	@param int n 天数
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getInfosJsonByDay(int day)
	返回值：ApasInfoList的Json字符串 
	 */
	public function getInfosJsonByDay()
	{
		try{
			if(!$this->can['day'])
			{
				echo 'NO PARAM day';exit;
			}
			$obj = $this->C->getInfosJsonByDay(array('day' => $this->can['day']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （16）查询n天以前所有的办件信息（所有状态）- 限制取num条数量的结果
	  查询例举:（1）要查询一星期的办件信息，day=7即可 
         （2）要查询一个月的办件信息，day=30或31即可（根据大小月调整值） 
         （3）要查询一年的办件信息，day=365或366即可（根据大小年调整值） 
         （4）要自定义查询n天的办件信息，day=n（n=你想要查询的天数）。
	@param int n 天数
	@param int num 限制取num条
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getInfosJsonByDayLimit(int day, int num)
	返回值：ApasInfoList的Json字符串 
	 */
	public function getInfosJsonByDayLimit()
	{
		try{
			if(!$this->can['day'])
			{
				echo 'NO PARAM day';exit;
			}
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getInfosJsonByDayLimit(array('day' => $this->can['day'],'num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （17）查询n天以前办结的办件信息
	查询例举:（1）要查询一星期的办件信息，day=7即可 
         （2）要查询一个月的办件信息，day=30或31即可（根据大小月调整值） 
         （3）要查询一年的办件信息，day=365或366即可（根据大小年调整值） 
         （4）要自定义查询n天的办件信息，day=n（n=你想要查询的天数）。
	@param int n 天数
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getBanJieInfosJsonByDay(int day)
	返回值：ApasInfoList的Json字符串
	 */
	public function getBanJieInfosJsonByDay()
	{
		try{
			if(!$this->can['day'])
			{
				echo 'NO PARAM day';exit;
			}
			$obj = $this->C->getBanJieInfosJsonByDay(array('day' => $this->can['day']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （18）查询n天以前办结的办件信息- 限制取num条数量的结果
	查询例举:（1）要查询一星期的办件信息，day=7即可 
         （2）要查询一个月的办件信息，day=30或31即可（根据大小月调整值） 
         （3）要查询一年的办件信息，day=365或366即可（根据大小年调整值） 
         （4）要自定义查询n天的办件信息，day=n（n=你想要查询的天数）。
	@param int n 天数
	@param int num 限制取num条
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getBanJieInfosJsonByDayLimit(int day, int num)
	返回值：ApasInfoList的Json字符串 
	 */
	public function getBanJieInfosJsonByDayLimit()
	{
		try{
			if(!$this->can['day'])
			{
				echo 'NO PARAM day';exit;
			}
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getBanJieInfosJsonByDayLimit(array('day' => $this->can['day'],'num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （19）查询n天以前在办的办件信息
	查询例举:（1）要查询一星期的办件信息，day=7即可 
         （2）要查询一个月的办件信息，day=30或31即可（根据大小月调整值） 
         （3）要查询一年的办件信息，day=365或366即可（根据大小年调整值） 
         （4）要自定义查询n天的办件信息，day=n（n=你想要查询的天数）。
	@param int n 天数
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getZaiBanInfosJsonByDay(int day)
	返回值：ApasInfoList的Json字符串
	 */
	public function getZaiBanInfosJsonByDay()
	{
		try{
			if(!$this->can['day'])
			{
				echo 'NO PARAM day';exit;
			}
			$obj = $this->C->getZaiBanInfosJsonByDay(array('day' => $this->can['day']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * （20）查询n天以前在办的办件信息- 限制取num条数量的结果
	查询例举:（1）要查询一星期的办件信息，day=7即可 
         （2）要查询一个月的办件信息，day=30或31即可（根据大小月调整值） 
         （3）要查询一年的办件信息，day=365或366即可（根据大小年调整值） 
         （4）要自定义查询n天的办件信息，day=n（n=你想要查询的天数）。
	@param int n 天数
	@param int num 限制取num条
	@返回值：apasInfoListJson 办件信息对象数组的Json字符串（结果按时间倒序输出）
	方法名：getZaiBanInfosJsonByDayLimit(int day, int num)
	返回值：ApasInfoList的Json字符串
	 */
	public function getZaiBanInfosJsonByDayLimit()
	{
		try{
			if(!$this->can['day'])
			{
				echo 'NO PARAM day';exit;
			}
			if(!$this->can['num'])
			{
				echo 'NO PARAM num';exit;
			}
			$obj = $this->C->getZaiBanInfosJsonByDayLimit(array('day' => $this->can['day'],'num' => $this->can['num']));
			$ret = $obj->return;
			echo $ret;exit;
		}catch(SoapFault $e){
     		echo $e->getMessage();
 		}catch(Exception $e){
     		echo $e->getMessage();
 		}
	}
	
	/**
	 * 
	 */
	
	/**
	 * 
	 */
	

 
	
	
	
	
	/**
	 * 对象变数组
	 */
	public function objectToArray($object){
	
	        $result = array();
	
	        $object = is_object($object) ? get_object_vars($object) : $object;
	
	        foreach ($object as $key => $val) {
	
	                $val = (is_object($val) || is_array($val)) ? objectToArray($val) : $val;
	
	                $result[$key] = $val;
	        }
	
	        return $result;
	}
}

$out = new yqzw();
$out->show();


?>
