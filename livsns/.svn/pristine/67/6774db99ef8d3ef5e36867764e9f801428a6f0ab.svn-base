<?php
/***************************************************************************
* $Id: member.php 12589 2012-10-17 05:45:41Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class smilesApi extends appCommonFrm
{
	private $mMember;
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function import()
	{
		$folder="./smiles/qq/";
		$fp=opendir($folder);
		while(false!=$file=readdir($fp))
		{
		    if($file!='.' &&$file!='..')
		    {
		        $file="$file";
		        $arr_file[]=$file;
		    }
		}
		if(is_array($arr_file))
		{
			$host = 'localhost/community/';
			$dir = 'img/smiles/';
			$filepath = 'qq/';
			$sql = "insert into liv_smiles(host,dir,filepath,filename,mark,type) values";
			$space = "";
			$text = array(
			'[微笑]',
			'[难过]',
			'[色]',
			'[呆]',
			'[帅]',
			'[哭]',
			'[害羞]',
			'[闭嘴]',
			'[睡]',
			'[大哭]',
			'[大汗]',
			'[怒]',
			'[调皮]',
			'[大笑]',
			'[惊讶]',
			'[不高兴]',
			'[酷]',
			'[尴尬]',
			'[烦躁]',
			'[吐]',
			'[奸笑]',
			'[可爱]',
			'[白眼]',
			'[傲慢]',
			'[舔]',
			'[困]',
			'[惊恐]',
			'[汗]',
			'[哈哈]',
			'[大兵]',
			'[奋斗]',
			'[诅咒]',
			'[问号]',
			'[嘘]',
			'[晕]',
			'[抓狂]',
			'[黑脸]',
			'[骷髅]',
			'[敲头]',
			'[88]',
			'[擦汗]',
			'[抠鼻]',
			'[鼓掌]',
			'[糗]',
			'[坏笑]',
			'[左哼哼]',
			'[右哼哼]',
			'[哈欠]',
			'[鄙视]',
			'[委屈]',
			'[快哭了]',
			'[阴险]',
			'[亲亲]',
			'[吓]',
			'[可怜]',
			'[菜刀]',
			'[西瓜]',
			'[啤酒]',
			'[篮球]',
			'[乒乓]',
			'[咖啡]',
			'[米饭]',
			'[猪]',
			'[玫瑰]',
			'[凋谢]',
			'[红唇]',
			'[心]',
			'[心碎]',
			'[生日]',
			'[闪电]',
			'[炸弹]',
			'[小刀]',
			'[足球]',
			'[甲虫]',
			'[便便]',
			'[晚安]',
			'[太阳]',
			'[礼物]',
			'[抱抱]',
			'[赞]',
			'[贬]',
			'[握手]',
			'[胜利]',
			'[抱拳]',
			'[勾引]',
			'[加油]',
			'[小指]',
			'[爱你]',
			'[no]',
			'[ok]',			
			);
		    while(list($key,$value)=each($arr_file))
		    {
		      $sql .= $space . '("' . $host . '","' . $dir . '","' . $filepath . '","' . $value . '","' . $text[$key] . '",0)';
		      $space = ',';
		    }
	    }
		closedir($fp);
	}
	
	public function show()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "smiles where 1";
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{	
			$this->addItem($row);
		}
		$this->output();
	}
}

$out = new smilesApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>