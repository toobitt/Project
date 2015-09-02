<?php
define('MOD_UNIQUEID','market_member');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/Barcodegen.class.php');
define('LENGTH',100);
ini_set('max_execution_time', 3600);
ini_set('memory_limit', '1024M');
class update_barcode extends adminBase
{
	private $barcode;
	private $material;
    public function __construct()
	{
		parent::__construct();
		$this->barcode = new Barcodegen();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function run()
	{
		$condition = $this->get_condition();
		if(!$this->input['start'] && !$this->input['end'])
		{
			$this->errorOutput('至少有一个id限制条件');
		}
		
		//运行之前获取导入进度
		$progressPath 	= CACHE_DIR . 'progress.txt';
		$totalPath 		= CACHE_DIR . 'total.txt';
		if(file_exists($progressPath))
		{
			$progress = file_get_contents($progressPath);
		}
		else 
		{
			file_put_contents($progressPath,0);
			$sql = "SELECT count(*) as total FROM " .DB_PREFIX. "market_member WHERE 1 " . $condition;
			$total = $this->db->query_first($sql);
			file_put_contents($totalPath, $total['total']);
			$progress = 0;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "market_member WHERE 1 " . $condition . " ORDER BY id DESC LIMIT " . $progress . "," . LENGTH;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			//如果原来有条形码，就不执行了
			if($r['barcode_img'])
			{
				continue;
			}
			//根据卡号生成条形码图片
			$barcode_img_path = CACHE_DIR . $r['card_number'] . '.png';
			$img_path = 'http://' . $this->settings['App_supermarket']['host'] . '/' .  $this->settings['App_supermarket']['dir'] . 'cache/' . $r['card_number'] . '.png';
			if($img_info = $this->createBarCode($r['card_number'],$barcode_img_path,$img_path))
			{	
				$sql = "UPDATE " . DB_PREFIX . "market_member SET barcode_img = '" .serialize($img_info). "' WHERE id = '" .$r['id']. "'";
				$this->db->query($sql);
			}
		}
		
		file_put_contents($progressPath,$progress + LENGTH);
		$total = file_get_contents($totalPath);
		if(intval($progress + LENGTH) < intval($total))
		{
			$percent = round(intval($progress + LENGTH)/intval($total) * 100,2) . "%";
			$message = '正在更新数据...';
			include_once(CUR_CONF_PATH . 'tpl/progress.php');
			
			$param = '';
			$space = '';
			foreach ($this->input AS $_k => $_v)
			{
				$param .= $space . $_k . '=' . $_v;
				$space = '&';
			}
			$this->redirect('update_barimg.php?' . $param);
		}
		echo "数据更新完成";
	}
	
	//创建条形码并且提交到图片服务器(卡号，条形码图片存放的目录,生成之后图片的访问链接)
	private function createBarCode($card_number = '',$img_dir = '',$img_url = '')
	{
		if(!$img_dir || !$card_number || !$img_url)
		{
			return false;
		}
		
		if(!$this->barcode->create($card_number,$img_dir))
		{
			return false;
		}
		
		$img_info = $this->material->localMaterial($img_url);
		if($img_info && $img_info[0] && $img_info[0]['id'])
		{
			$img_info = $img_info[0];
			$img_info = array(
				'host'     => $img_info['host'],
				'dir'      => $img_info['dir'],
				'filepath' => $img_info['filepath'],
				'filename' => $img_info['filename'],
				'imgwidth' => $img_info['imgwidth'],
				'imgheight'=> $img_info['imgheight'],
			);
			@unlink($img_dir);
			return $img_info;
		}
		return false;
	}
	
	private function redirect($url)
	{
		$jsStr  = "<SCRIPT LANGUAGE='JavaScript'>";
		$jsStr .= "window.location.href='" .$url. "'";
		$jsStr .= "</SCRIPT>"; 
		echo $jsStr;
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['market_id'])
		{
			$condition .= " AND market_id = '" . $this->input['market_id'] . "' ";
		}
		else 
		{
			$this->errorOutput('没有market_id');
		}
		
		//扫瞄的开始位置
		if($this->input['start'])
		{
			$condition .= " AND id >= '" . $this->input['start'] . "' ";
		}
		
		//扫瞄的结束位置
		if($this->input['end'])
		{
			$condition .= " AND id <= '" . $this->input['end'] . "' ";
		}
		return $condition;
	}
	
	//运行之前要清理一下cache里面的进度与总数信息，防止有历史遗留数据
	public function initCache()
	{
		$arr = array(CACHE_DIR . 'progress.txt',CACHE_DIR . 'total.txt');
		foreach ($arr AS $k => $v)
		{
			if(file_exists($v))
			{
				unlink($v);
			}
		}
		echo '初始化完毕，可以更新条形码了';
	}
	
	
	public function rebind()
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "market_member WHERE member_id != 0 AND member_id !=1 ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$arr = array();
			$sql1 = "SELECT id FROM " .DB_PREFIX. "bind_log WHERE market_id = '" .$r['market_id']. "' AND market_member_id = '" .$r['id']. "' AND member_id = '" .$r['member_id']. "'";
			$arr = $this->db->query_first($sql1);
			if($arr)
			{
				continue;
			}
			
			$_sql = "INSERT INTO " .DB_PREFIX. "bind_log SET market_id = '" .$r['market_id']. "',market_member_id = '" .$r['id']. "',member_id = '" .$r['member_id']. "',create_time = '" .TIMENOW. "'";
			$this->db->query($_sql);
		}
		echo 'success';
	}

	protected function verifyToken(){}
}

$out = new update_barcode();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'initCache';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>