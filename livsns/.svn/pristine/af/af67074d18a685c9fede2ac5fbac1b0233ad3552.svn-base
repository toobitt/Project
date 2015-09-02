<?php
require_once('global.php');
define('MOD_UNIQUEID', 'news');
class batchReview extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//重新发布没有发布成功的内容
	function batch_review_publish()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."article WHERE state = 1 AND column_id !='' AND column_id != 'a:0:{}' AND expand_id = 0";
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q)) {
            publish_insert_query($row,'delete');
			echo '正在重新发布' . $row['id'] . '-----' . $row['title'] . str_repeat(' ', 4096) .  "<br/>";
			publish_insert_query($row,'insert');
			echo $row['id'] . '-----' . $row['title'] . '已放入发布队列' . str_repeat(' ', 4096) .  "<br/>";
			ob_flush();
		}	
		exit('成功');		
	}
	
	
	
	//修改内容  替换单引号 双引号等字符
	public function update_content()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "article_contentbody WHERE 1 ";
		$q = $this->db->query($sql);
		$pregfind = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
		$pregreplace = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');		
		while ($row = $this->db->fetch_array($q)) {
			$row['content'] = str_replace($pregfind, $pregreplace, $row['content']);
			$sql = "UPDATE " .DB_PREFIX. "article_contentbody SET content = '" . addslashes($row['content']) . "' WHERE articleid = " . $row['articleid'];
			$this->db->query($sql);
			echo $row['articleid'] . '----修改完成' . str_repeat(' ', 4096) . "<br/>";
			ob_flush();
		}
	}
	
}

$out = new batchReview();
if (!method_exists($out, $_INPUT['a'])) {
	$action = 'batch_review_publish';
}
else {
	$action = $_INPUT['a'];
}
$out->$action();
?>