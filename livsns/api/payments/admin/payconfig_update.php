<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: payconfig_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/payconfig.class.php';
define('MOD_UNIQUEID', 'pay_config'); //模块标识

class payconfigUpdateApi extends adminUpdateBase
{
	private $payconfig;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->payconfig = new payconfigClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->payconfig);
	}
	

	/**
	** 信息更新操作
	**/
	public function update()
	{
		$id = intval($this->input['id']);
		if (empty($id)){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$ip = hg_getip();
			
			if($this->input['typecode']=="alipay")
			{
				$this->input['alipay_c'] = implode(",", $this->input['alipay_c']);
				$paycode = $this->input['alipay_c'];
			}
			elseif ($this->input['typecode']=="tenpay")
			{
				$this->input['tenpay_c'] = implode(",", $this->input['tenpay_c']);
				$paycode = $this->input['tenpay_c'];
			}
			
			$typecode = $this->input['typecode'];
			$sellername = $this->input['sellername'];
			$sellernumber = $this->input['sellernumber'];
			$sellerkey = $this->input['sellerkey'];
			$sellerid = $this->input['sellerid'];
			$is_on=$this->input['is_on'];
			$update_time = TIMENOW;
			$updateData = array();
			$updateData['ip'] = $ip;
			$updateData['update_time'] = $update_time;
			$updateData['typecode'] = $typecode;
			$updateData['paycode'] = $paycode;
			$updateData['sellername'] = $sellername;
			$updateData['sellernumber'] = $sellernumber;
			$updateData['sellerkey'] = $sellerkey;
			$updateData['sellerid'] = $sellerid;
			$updateData['call_back_url'] = $this->input['call_back_url'];
			$updateData['notify_url'] = $this->input['notify_url'];
			$updateData['merchant_url'] = $this->input['merchant_url'];
			$updateData['is_on'] = $is_on;
			$updateData['appid']=$this->user['appid'];
			$updateData['appname']=$this->user['display_name'];
			$updateData['user_id']=$this->user['user_id'];
			$updateData['user_name']=$this->user['user_name'];
			$updateData['order_id']=$this->input['order_id'];
			
			//更新主表
			if ($updateData)
			{
				$result = $this->payconfig->update($updateData,$id);
			}
			else
			{
				$updateData = true;
			}
			
			//重新生成支付所需验证得字段
			$payid = $id;
			$paycode = $updateData['paycode'];
			$sellernumber = $updateData['sellernumber'];
			$sellerkey = $updateData['sellerkey'];
			$sellerid = $updateData['sellerid'];
			$call_back_url = $updateData['call_back_url'];
			$notify_url = $updateData['notify_url'];
			$merchant_url = $updateData['merchant_url'];
			$is_on = $updateData['is_on'];
			
			//重新生成配置文件
			$sing_arr = "<?php";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."payid = '$payid';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."paycode = '$paycode';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."sellernumber = '$sellernumber';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."sellerkey = '$sellerkey';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."sellerid = '$sellerid';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."call_back_url = '$call_back_url';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."notify_url = '$notify_url';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."merchant_url = '$merchant_url';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "$"."is_on = '$is_on';  ";
			$sing_arr .= "\r\n";
			$sing_arr .= "?>";
			
		 	//判断文件夹是否可写
		    $is_file_w = $this->set_writeable("../cache/");
	        if($is_file_w)
	        {
		        file_put_contents("../cache/".$payid."_config_cache.php",$sing_arr);
	        }
	        else
	        {
	        	$this->errorOutput("生成配置缓存文件失败，请开启文件夹权限");
	        }
			
			
			$this->addItem($updateData);
			$this->output();
		}
	}
	
	
	
	public function delete()
	{
		$ids = trim(urldecode($this->input['id']));
		if(empty($ids))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		//设置数据库active为0
		$result = $this->payconfig->delete($ids);
		
		//删除该配置的缓存文件
		if (is_int($ids))
		{
			//文件路径
			$cachefile = "../cache/".$ids."_config_cache.php";
			if (file_exists($cachefile)) {
				$result=unlink ($cachefile);
				if(!$result)
				{
					$this->errorOutput("该配置的缓存文件自动删除失败，请手动删除!");
				}
		 	}
		}
		elseif (is_string($ids))
		{
			$arr_ids = array();
			$arr_ids = explode(",",$ids);
		    $arrayLen = sizeof($arr_ids);
		    for( $i = 0; $i< $arrayLen ;$i++ )
		    {
		    	//文件路径
				$cachefile = "../cache/".$arr_ids[$i]."_config_cache.php";
				if (file_exists($cachefile)) {
					$result=unlink ($cachefile);
					if(!$result)
					{
						$this->errorOutput("该配置的缓存文件自动删除失败，请手动删除!");
					}
			 	}
		    }
		}
		
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
		$createData = array();
		
		$createData['ip'] = hg_getip();
		$createData['create_time'] = TIMENOW;
		$createData['update_time'] = TIMENOW;
		
		//签约类型
		if($this->input['typecode']=="alipay")
		{
			$this->input['alipay_c'] = implode(",", $this->input['alipay_c']);
			$createData['paycode'] = $this->input['alipay_c'];
		}
		elseif ($this->input['typecode']=="tenpay")
		{
			$this->input['tenpay_c'] = implode(",", $this->input['tenpay_c']);
			$createData['paycode'] = $this->input['tenpay_c'];
		}
		else
		{
			$createData['paycode'] = "";
		}
		//支付类型
		$createData['typecode'] = $this->input['typecode'];
		$createData['sellername'] = $this->input['sellername'];
		$createData['sellernumber'] = $this->input['sellernumber'];
		$createData['sellerkey'] = $this->input['sellerkey'];
		$createData['sellerid'] = $this->input['sellerid'];
		$createData['call_back_url'] = $this->input['call_back_url'];
		$createData['notify_url'] = $this->input['notify_url'];
		$createData['merchant_url'] = $this->input['merchant_url'];
		$createData['is_on'] = $this->input['is_on'];
		$createData['appid']=$this->user['appid'];
		$createData['appname']=$this->user['display_name'];
		$createData['user_id']=$this->user['user_id'];
		$createData['user_name']=$this->user['user_name'];
		$createData['order_id']=$this->input['order_id'];
		
		$result = $this->payconfig->create($createData);
		
		//支付所需验证得字段
		$payid = $result['id'];
		$paycode = $createData['paycode'];
		$sellernumber = $createData['sellernumber'];
		$sellerkey = $createData['sellerkey'];
		$sellerid = $createData['sellerid'];
		$call_back_url = $createData['call_back_url'];
		$notify_url = $createData['notify_url'];
		$merchant_url = $createData['merchant_url'];
		$is_on = $createData['is_on'];
		
		//生成配置文件
		$sing_arr = "<?php";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."payid = '$payid';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."paycode = '$paycode';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."sellernumber = '$sellernumber';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."sellerkey = '$sellerkey';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."sellerid = '$sellerid';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."call_back_url = '$call_back_url';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."notify_url = '$notify_url';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."merchant_url = '$merchant_url';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."is_on = '$is_on';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "?>";
		
	 	//判断文件夹是否可写
	    $is_file_w = $this->set_writeable("../cache/");
        if($is_file_w)
        {
	        file_put_contents("../cache/".$payid."_config_cache.php",$sing_arr);
        }
        else
        {
        	$this->errorOutput("生成配置缓存文件失败，请开启文件夹权限");
        }
		
		//
		$this->addItem($createData);
		$this->output();
		
	}
	
	//设置文件/文件夹的写属性
	public function set_writeable($filename){
		
		if (is_dir($filename)==false)
		{
			if(@mkdir($filename, 0777))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if(is_writable($filename))
			{
				return true;
			}
			else{
				if(@chmod($filename,0777))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}
	
	
	//审核
	public function audit()
	{
	}

	public function publish()
	{
		
	}

	public function sort()
	{
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}
$out = new payconfigUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>