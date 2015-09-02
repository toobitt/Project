<?php
require('global.php');
define('MOD_UNIQUEID','publishcontent_block_set');//模块标识
require_once(ROOT_PATH.'lib/class/publishsys.class.php');
class xstestApi extends BaseFrm
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$data = array (
  'id' => '1242',
  'title' => '多艘海监船',
  'content' => '&lt;p class=&quot;f_center&quot;&gt;&lt;a href=&quot;http://v.163.com/zixun/V7M3CBCH5/V8ADT656B.html&quot; target=&quot;_blank&quot;&gt;中国海监船编队抵钓鱼岛海域维权巡航执法&lt;/a&gt;&lt;/p&gt;&lt;p class=&quot;f_center&quot;&gt;&lt;img alt=&quot;中国多艘海监船抵达钓鱼岛海域&quot; src=&quot;http://img.dev.hogesoft.com:233/material/news/img/640x/2012/09/20120914090029fmj.jpg&quot; class=&quot;image&quot; oldwidth=&quot;640&quot; imageid=&quot;243&quot; _style=&quot;style1&quot; style=&quot;padding: 5px; border: 1px solid rgb(221, 221, 221); float: none; &quot;&gt;&lt;br&gt;中国海监船编队9月14日上午进入钓鱼岛海域进行维权巡航 &lt;br&gt;&lt;br&gt;&lt;/p&gt;&lt;p class=&quot;f_center&quot;&gt;&lt;img alt=&quot;中国多艘海监船抵达钓鱼岛海域&quot; src=&quot;http://img.dev.hogesoft.com:233/material/news/img/640x/2012/09/20120914090029r4T9.jpg&quot; class=&quot;image&quot; oldwidth=&quot;640&quot; imageid=&quot;244&quot; style=&quot;width: 479px; &quot;&gt;&lt;br&gt;中国海监船编队9月14日上午进入钓鱼岛海域进行维权巡航 &lt;br&gt;&lt;/p&gt;&lt;p class=&quot;f_center&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;【环球网综合报道】据中国中央电视台9月14日报道，北京时间14日早晨6时许，由中国海监50、15、26、27船和中国海监51、66船组成的2个维权巡航编队，抵达钓鱼岛周边海域，对钓鱼岛及其附属岛屿附近海域进行维权巡航执法。&lt;/p&gt;&lt;p&gt;这是中国政府宣布《中华人民共和国政府关于钓鱼岛及其附属岛屿领海基线的声明》后，中国海监首次在钓鱼岛及其附属岛屿海域开展的维权巡航执法，通过维权巡航执法行动，体现中国政府对钓鱼岛及其附属岛屿的管辖，维护中国的海洋权益。&lt;/p&gt;&lt;p&gt;另据日本新闻网14日报道，据日本海上保安厅第11管区那霸海上保安本部发表的消息称，14日上午6时20分许，中国2艘海监船驶入钓鱼岛附近海域，进入所谓的&quot;日本领海”。目前，这两艘海监船继续在朝钓鱼岛附近海域行进。&lt;/p&gt;&lt;p&gt;报道称，两艘中国海监船分别是&quot;海监51号”和&quot;海监66号”，这是日本野田政府实施钓鱼岛&quot;国有化”之后，中方公务船首次进入钓鱼岛附近海域巡航。&lt;/p&gt;&lt;p&gt;那霸海保部称，14日清晨5时许，两艘中国海监船从钓鱼岛赤尾屿的北侧方向进入钓鱼岛海域毗邻水域，正在&quot;警戒”中的海上保安厅巡视船
向中国海监船发出了&quot;不要进入日本领海”的警告，但中国海监船回答说&quot;钓鱼岛是中国领土，我们正在进行正常的巡逻任务。”随后在6时20分许，这两艘中国
海监船进入钓鱼岛海域。&lt;/p&gt;&lt;p&gt;消息说，日海保厅巡视船没有进行&quot;强行拦堵”，目前，中国海监船正在钓鱼岛附近海域行驶中。&lt;/p&gt;&lt;p&gt;另有媒体报道称，日方发现中国海监船北京时间早上5点20分左右进入距离钓鱼岛12海里的海域。该媒体同时称共有8艘中方船只在钓鱼岛附近海域活动。&lt;/p&gt;  ',
  'bundle_id' => 'news',
  'module_id' => 'news',
  'struct_id' => 'article',
  'site_id' => NULL,
  'expand_id' => NULL,
  'content_fromid' => '1079',
  'order_id' => NULL,
  'is_have_indexpic' => NULL,
  'is_have_video' => NULL,
  'weight' => NULL,
  'share_num' => NULL,
  'comment_num' => NULL,
  'click_num' => NULL,
  'publish_time' => NULL,
  'create_time' => NULL,
  'verify_time' => NULL,
  'publish_user' => NULL,
  'create_user' => NULL,
  'verify_user' => NULL,
  'outlink' => NULL,
  'ip' => NULL,
  'video' => NULL,
  'indexpic' => NULL,
  'brief' => NULL,
  'keywords' => NULL,
);
		$result = $this->xs_index($data,'search_config_publish_content','update');
		print_r($result);exit;
	}
	
}

$out = new xstestApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
